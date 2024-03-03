<?php

namespace OKLab_Klimawidget\Init;

/**
*
*/
class OKLab_Klimawidget_Create_Post_Type {

	/**
	 *
	 */
	public $defaults;

	/**
	 *
	 */
	public function __construct() {
		$this->defaults = $this->default_args();
	}

	/**
	 * [create description]
	 * @param  [type] $post_types [description]
	 * @return [type]             [description]
	 */
	public function create( $post_type ) {
		if ( empty( $post_type ) ) {
			return;
		}

		$this->create_post_type( $post_type );

		if ( ! empty( $post_type['supports'] ) && in_array( 'thumbnail', $post_type['supports'] ) ) {
			add_theme_support( 'post-thumbnails', array( strtolower( $post_type['post_type_id'] ) ) );
		}
	}

	/**
	 *
	 */
	private function create_post_type( $post_type ) {

		$post_type_id = strtolower( $post_type['post_type_id'] );
		$args         = $this->get_args( $post_type );

		register_post_type( $post_type_id, $args );
	}

	/**
	 *
	 */
	private function get_args( $post_type ) {

		$post_type = (array) $post_type;
		$defaults  = (array) $this->defaults;

		$args = array();
		foreach ( $defaults as $name => $default ) {
			if ( array_key_exists( $name, $post_type ) ) {
				$args[ $name ] = $post_type[ $name ];
			} else {
				$args[ $name ] = $default;
			}
		}

		if ( ! $args['rest_base'] ) {
			$args['rest_base'] = strtolower( $post_type['post_type_id'] );
		}

		$args['labels'] = $this->get_labels( $post_type );

		return $args;
	}

	/**
	 *
	 */
	private function get_labels( $post_type ) {

		if ( empty( $post_type['singular_name'] ) ) {
			$post_type['singular_name'] = $post_type['post_type_id'];
		}

		if ( empty( $post_type['name'] ) ) {
			$post_type['name'] = $post_type['post_type_id'];
		}

		return array(
			'name'               => $post_type['name'],
			'singular_name'      => $post_type['singular_name'],
			'all_items'          => sprintf( __( 'All %s', 'oklab-klimawidget' ), $post_type['name'] ),
			'new_item'           => sprintf( __( 'New %s', 'oklab-klimawidget' ), $post_type['singular_name'] ),
			'add_new'            => __( 'Add New', 'oklab-klimawidget' ),
			'add_new_item'       => sprintf( __( 'Add New %s', 'oklab-klimawidget' ), $post_type['singular_name'] ),
			'edit_item'          => sprintf( __( 'Edit %s', 'oklab-klimawidget' ), $post_type['singular_name'] ),
			'view_item'          => sprintf( __( 'View %s', 'oklab-klimawidget' ), $post_type['singular_name'] ),
			'search_items'       => sprintf( __( 'Search %s', 'oklab-klimawidget' ), $post_type['name'] ),
			'not_found'          => sprintf( __( 'No %s found', 'oklab-klimawidget' ), $post_type['name'] ),
			'not_found_in_trash' => sprintf( __( 'No %s found in trash', 'oklab-klimawidget' ), $post_type['name'] ),
			'parent_item_colon'  => sprintf( __( 'Parent %s', 'oklab-klimawidget' ), $post_type['singular_name'] ),
			'menu_name'          => $post_type['name'],
		);
	}

	/**
	 *
	 */
	private function default_args() {

		return array(
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => array( 'title', 'editor' ),
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_icon'             => 'dashicons-admin-post',
			'show_in_rest'          => true,
			'rest_base'             => false,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'template'              => false,
			'taxonomies'            => array(),
		);
	}
}
