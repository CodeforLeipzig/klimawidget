<?php

namespace OKLab_Klimawidget\Init;

class OKLab_Klimawidget_Rest_Routes extends \WP_REST_Controller {
	public static $transient_key_base = 'oklab_kw_';

	public static $interval = array( 'year', 'month', 'week' );

	public static $energy_source = array(
		'wind',
		'solar_power',
		'other_gases',
		'mineral_oil_products',
		'storage',
		'water',
		'sludge',
		'biomass',
		'natural_gas',
		'geothermics',
		'mine_gas',
		'solar_thermics',
		'lignite_coal',
		'none_biogenic_waste',
		'heat',
	);
	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		$version   = '1';
		$namespace = 'oklab_climate_data/v' . $version;
		$base      = 'sachsen';

		register_rest_route(
			$namespace,
			'/' . $base,
			array(
				array(
					'methods'     => \WP_REST_Server::READABLE,
					'callback'    => array( $this, 'get_sachsen_data' ),
					'permissions' => '__return_true',
					'args'        => array(),
				),
			)
		);

		register_rest_route(
			$namespace,
			'/' . $base . '/(?P<source>[a-zA-Z_]+)',
			array(
				array(
					'methods'     => \WP_REST_Server::READABLE,
					'callback'    => array( $this, 'get_sachsen_data' ),
					'permissions' => '__return_true',
					'args'        => array(
						'source' => array(
							'validate_callback' => function( $param, $request, $key ) {
								return in_array( $param, self::$energy_source, true );
							},
							'enum'              => self::$energy_source,
						),
					),
				),
			)
		);

		register_rest_route(
			$namespace,
			'/' . $base . '/(?P<source>[a-zA-Z_]+)/(?P<interval>[a-zA-Z]+)',
			array(
				array(
					'methods'     => \WP_REST_Server::READABLE,
					'callback'    => array( $this, 'get_sachsen_data' ),
					'permissions' => '__return_true',
					'args'        => array(
						'source'   => array(
							'validate_callback' => function( $param, $request, $key ) {
								return in_array( $param, self::$energy_source, true );
							},
							'enum'              => self::$energy_source,
						),
						'interval' => array(
							'validate_callback' => function( $param, $request, $key ) {
								return in_array( $param, self::$interval, true );
							},
							'enum'              => self::$interval,
						),
					),
				),
			)
		);
	}

	/**
	 * Get a collection of items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_sachsen_data( $request ) {
		$query_args = array(
			'energySource'      => 'WIND',
			'property'          => 'GROSS_POWER',
			'interval'          => 'YEAR',
			'aggregateFunction' => 'SUM',
		);

		$transient_key = self::$transient_key_base;

		// get parameters from request
		$params = $request->get_params();

		if ( ! empty( $params ) ) {
			foreach ( $params as $key => $value ) {
				switch ( $key ) {
					case 'source':
						$query_args['energySource'] = strtoupper( $value );
						break;
					case 'interval':
						$query_args['interval'] = strtoupper( $value );
						break;
				}
			}
		}

		foreach ( $query_args as $value ) {
			$transient_key .= strtolower( $value ) . '_';
		}

		$data = get_transient( $transient_key );

		$api_url_base = 'https://klimadashboard.danielgerber.eu/api';
		$api_url_path = 'mastr-units/state/statistics-for-interval';

		$api_url = $api_url_base . '/' . $api_url_path . '?' . http_build_query( $query_args );

		if ( false === $data ) {
			$api_response = wp_remote_get( $api_url );

			if ( ! is_wp_error( $api_response ) ) {
				$res_body = wp_remote_retrieve_body( $api_response );
				$res_body = json_decode( $res_body );

				set_transient( $transient_key, $res_body, 30 * MINUTE_IN_SECONDS );
				$data = $res_body;

			} else {
				$data = array( 'error' => $api_response->get_error_code() );
			}
		}

		$response = new \WP_REST_Response( $data, 200 );

		return $response;
	}

	/**
	 * Get the query params for collections
	 *
	 * @return array
	 */
	public function get_collection_params() {
		return array();
	}
}
