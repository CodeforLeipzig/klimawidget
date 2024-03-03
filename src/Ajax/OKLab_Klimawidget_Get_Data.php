<?php

namespace OKLab_Klimawidget\Ajax;

class OKLab_Klimawidget_Get_Data {

	private $data;

	public function get() {
		$response = $this->get_data();

		wp_send_json( $response );
	}

	private function set_args() {

	}

	private function get_data() {
		$query_key = 'oklab_kw_';
		$result    = get_transient( $query_key );

		$api_url = 'https://klimadashboard.danielgerber.eu/api/mastr-units/state/statistics-for-interval?interval=YEAR&property=GROSS_POWER&energySource=WIND&aggregateFunction=SUM';

		if ( false === $result ) {
			$response = wp_remote_get( $api_url );

			if ( ! is_wp_error( $response ) ) {
				$res_body = wp_remote_retrieve_body( $response );
				$res_body = json_decode( $res_body );

				set_transient( $query_key, $res_body, 1 * MINUTE_IN_SECONDS );
				$result = $res_body;
			} else {
				$result = array( 'error' => $response->get_error_code() );
			}
		}

		return $result;
	}

}
