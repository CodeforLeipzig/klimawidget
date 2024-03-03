<?php

namespace OKLab_Klimawidget\Init;

/**
*
*/
class OKLab_Klimawidget_Create_Taxonomy {

	/**
	 *
	 */
	protected $defaults;

	/**
	 *
	 */
	public function __construct() {
		$this->defaults = $this->default_args();
	}

	public function create( $taxonomy ) {
		if ( empty( $taxonomy ) ) {
			return;
		}

		$this->create_taxonomy( $taxonomy );
	}

	/**
	 * $taxonomy: array
	 */
	private function create_taxonomy( $taxonomy ) {
		$taxonomy_id = strtolower( $taxonomy['taxonomy_id'] );
		$object_type = $taxonomy['object_types'];
		$args        = $this->get_args( $taxonomy );

		register_taxonomy( $taxonomy_id, $object_type, $args );
	}

	/**
	 *
	 */
	private function get_args( $taxonomy ) {
		$taxonomy = (array) $taxonomy;
		$defaults = (array) $this->defaults;

		$args = array();

		foreach ( $defaults as $name => $default ) {
			if ( array_key_exists( $name, $taxonomy ) ) {
				$args[ $name ] = $taxonomy[ $name ];
			} else {
				$args[ $name ] = $default;
			}
		}

		if ( ! $args['rest_base'] ) {
			$args['rest_base'] = strtolower( $taxonomy['taxonomy_id'] );
		}

		$args['labels'] = $this->get_labels( $taxonomy );

		return $args;
	}

	/**
	 *
	 */
	private function get_labels( $taxonomy ) {

		if ( empty( $taxonomy['singular_name'] ) ) {
			$taxonomy['singular_name'] = $taxonomy['taxonomy_id'];
		}

		if ( empty( $taxonomy['name'] ) ) {
			$taxonomy['name'] = $taxonomy['taxonomy_id'];
		}

		return array(
			'name'                       => $taxonomy['name'],
			'singular_name'              => $taxonomy['singular_name'],
			'search_items'               => sprintf( __( 'Search %s', 'oklab-klimawidget' ), $taxonomy['name'] ), 
			'popular_items'              => sprintf( __( 'Popular %s', 'oklab-klimawidget' ), $taxonomy['name'] ),
			'all_items'                  => sprintf( __( 'All %s', 'oklab-klimawidget' ), $taxonomy['name'] ),
			'parent_item'                => sprintf( __( 'Parent %s', 'oklab-klimawidget' ), $taxonomy['singular_name'] ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'oklab-klimawidget' ), $taxonomy['singular_name'] ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'oklab-klimawidget' ), $taxonomy['singular_name'] ),
			'update_item'                => sprintf( __( 'Update %s', 'oklab-klimawidget' ), $taxonomy['singular_name'] ),
			'add_new_item'               => sprintf( __( 'New %s', 'oklab-klimawidget' ), $taxonomy['singular_name'] ),
			'new_item_name'              => sprintf( __( 'New %s', 'oklab-klimawidget' ), $taxonomy['singular_name'] ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'oklab-klimawidget' ), $taxonomy['name'] ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove Cities', 'oklab-klimawidget' ), $taxonomy['name'] ),
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'oklab-klimawidget' ), $taxonomy['name'] ),
			'not_found'                  => sprintf( __( 'No %s found.', 'oklab-klimawidget' ), $taxonomy['name'] ),
			'menu_name'                  => $taxonomy['name'],
		);
	}

	/**
	 *
	 */
	private function default_args() {
		return array(
			'hierarchical'          => false,
			'public'                => true,
			'show_in_nav_menus'     => true,
			'show_ui'               => true,
			'show_admin_column'     => false,
			'query_var'             => true,
			'rewrite'               => true,
			'show_in_rest'          => true,
			'rest_base'             => false,
			'rest_controller_class' => 'WP_REST_Terms_Controller',
		);
	}
}
