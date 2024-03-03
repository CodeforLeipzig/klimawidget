<?php

namespace OKLab_Klimawidget\Inc;

use OKLab_Klimawidget\Inc\OKLab_Klimawidget_Loader;
use OKLab_Klimawidget\Inc\OKLab_Klimawidget_i18n;
use OKLab_Klimawidget\Init\OKLab_Klimawidget_Init;
use OKLab_Klimawidget\Admin\OKLab_Klimawidget_Admin;
use OKLab_Klimawidget\Blocks\OKLab_Klimawidget_Register_Blocks;
use OKLab_Klimawidget\Frontend\OKLab_Klimawidget_Frontend;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    OKLab_Klimawidget
 */
class OKLab_Klimawidget {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      OKLab_Klimawidget_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'OKLAB_KLIMAWIDGET_VERSION' ) ) {
			$this->version = OKLAB_KLIMAWIDGET_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'oklab-klimawidget';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_init_hooks();
		$this->define_admin_hooks();
		$this->define_frontend_hooks();
		$this->define_blocks();
	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - OKLab_Klimawidget_Loader. Orchestrates the hooks of the plugin.
	 * - OKLab_Klimawidget_i18n. Defines internationalization functionality.
	 * - OKLab_Klimawidget_Admin. Defines all hooks for the admin area.
	 * - OKLab_Klimawidget_Frontend. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new OKLab_Klimawidget_Loader();
	}
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the OKLab_Klimawidget_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new OKLab_Klimawidget_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_i18n, 'set_script_translations', 11 );
	}
	/**
	 * Register all of the init hooks
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_init_hooks() {
		// $plugin_init = new OKLab_Klimawidget_Init( $this->get_plugin_name(), $this->get_version() );
	}
	/**
	 * Register all editor blocks
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_blocks() {
		$plugin_blocks = new OKLab_Klimawidget_Register_Blocks( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_init', $plugin_blocks, 'register_block_editor_styles' );
		$this->loader->add_action( 'admin_init', $plugin_blocks, 'register_block_editor_scripts' );
		$this->loader->add_action( 'init', $plugin_blocks, 'register_block_styles' );
		$this->loader->add_action( 'init', $plugin_blocks, 'register_block_scripts' );
		$this->loader->add_action( 'init', $plugin_blocks, 'register_block_types' );
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		// $plugin_admin = new OKLab_Klimawidget_Admin( $this->get_plugin_name(), $this->get_version() );
		// $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		// $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_frontend_hooks() {
		// $plugin_frontend = new OKLab_Klimawidget_Frontend( $this->get_plugin_name(), $this->get_version() );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_frontend, 'enqueue_styles' );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_frontend, 'enqueue_scripts' );
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}
	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    OKLab_Klimawidget_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
