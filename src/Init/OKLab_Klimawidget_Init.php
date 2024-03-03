<?php

namespace OKLab_Klimawidget\Init;

use OKLab_Klimawidget\Init\OKLab_Klimawidget_Create_Post_Type;
use OKLab_Klimawidget\Init\OKLab_Klimawidget_Create_Taxonomy;


class OKLab_Klimawidget_Init {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Init posttype member
	 */
	public function init_posttype() {
		// $args = array(
		// 	'post_type_id'      => 'member',
		// 	'name'              => __( 'Members', 'oklab-klimawidget' ),
		// 	'singular_name'     => __( 'Member', 'oklab-klimawidget' ),
		// 	'show_in_rest'      => false,
		// 	'public'            => false,
		// 	'show_ui'           => false,
		// 	'show_in_nav_menus' => false,
		// 	'supports'          => array( 'title', 'editor', 'author', 'custom-fields', 'thumbnail', 'page-attributes' ),
		// 	'taxonomies'        => array( 'cconnector', 'state', 'suptype' ),
		// );

		// if ( current_user_can( 'manage_options' ) ) {
		// 	$args['show_ui']           = true;
		// 	$args['show_in_nav_menus'] = true;
		// }

		// $init_posttype = new OKLab_Klimawidget_Create_Post_Type();
		// $init_posttype->create( $args );
	}

	/**
	 * Init taxonomy state
	 *
	 * @return void
	 */
	public function init_taxonomy() {
		// $init_taxonomy = new OKLab_Klimawidget_Create_Taxonomy();
		// $init_taxonomy->create(
		// 	array(
		// 		'taxonomy_id'        => 'state',
		// 		'object_types'       => array( 'member', 'campaign' ),
		// 		'name'               => __( 'States', 'oklab-klimawidget' ),
		// 		'singular_name'      => __( 'State', 'oklab-klimawidget' ),
		// 		'show_in_rest'       => true,
		// 		'public'             => true,
		// 		'publicly_queryable' => false,
		// 		'show_admin_column'  => true,
		// 	)
		// );
	}
}
