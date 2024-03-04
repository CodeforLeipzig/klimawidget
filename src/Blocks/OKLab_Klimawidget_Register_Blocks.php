<?php

namespace OKLab_Klimawidget\Blocks;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class OKLab_Klimawidget_Register_Blocks {
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * register block editor style
	 */
	public function register_block_editor_styles() {
		/** Klimawidget Block */
		wp_register_style(
			$this->plugin_name . '-klimawidget-editor-style',
			PLUGIN_OKLAB_KLIMAWIDGET_URL . 'dist/klimawidget.css',
			array(),
			filemtime( PLUGIN_OKLAB_KLIMAWIDGET_PATH . 'dist/klimawidget.css' ),
			'all'
		);
	}

	/**
	 * register block editor script
	 */
	public function register_block_editor_scripts() {
		/** Klimawidget Block */
		$widget_block_script_path       = PLUGIN_OKLAB_KLIMAWIDGET_PATH . 'dist/klimawidget.js';
		$widget_block_script_asset_path = PLUGIN_OKLAB_KLIMAWIDGET_PATH . 'dist/klimawidget.asset.php';
		$widget_block_script_asset      = file_exists( $widget_block_script_asset_path )
		? require $widget_block_script_asset_path
		: array(
			'dependencies' => array(),
			'version'      => filemtime( $widget_block_script_path ),
		);
		$widget_block_script_url        = PLUGIN_OKLAB_KLIMAWIDGET_URL . 'dist/klimawidget.js';

		wp_register_script(
			$this->plugin_name . '-klimawidget-editor-script',
			$widget_block_script_url,
			$widget_block_script_asset['dependencies'],
			$widget_block_script_asset['version'],
			false
		);

		wp_set_script_translations(
			$this->plugin_name . '-klimawidget-editor-script',
			'oklab-klimawidget',
			PLUGIN_OKLAB_KLIMAWIDGET_PATH . 'languages'
		);
	}

	/**
	 * register block frontend script
	 */
	public function register_block_scripts() {
		/** Klimawidget Block */
		$widget_script_path       = PLUGIN_OKLAB_KLIMAWIDGET_PATH . 'dist/widgets.js';
		$widget_script_asset_path = PLUGIN_OKLAB_KLIMAWIDGET_PATH . 'dist/widgets.asset.php';
		$widget_script_asset      = file_exists( $widget_script_asset_path )
		? require $widget_script_asset_path
		: array(
			'dependencies' => array(),
			'version'      => filemtime( $widget_script_path ),
		);
		$widget_script_url        = PLUGIN_OKLAB_KLIMAWIDGET_URL . 'dist/widgets.js';

		wp_register_script(
			$this->plugin_name . '-klimawidget-script',
			$widget_script_url,
			$widget_script_asset['dependencies'],
			$widget_script_asset['version'],
			true
		);

		wp_localize_script(
			$this->plugin_name . '-klimawidget-script',
			'oklabKlimawidgetGlobal', // Array containing dynamic data for a JS Global.
			array(
				'data_api_base' => get_rest_url( null, 'oklab_climate_data/v1' ),
			),
		);
	}

	/**
	 * register block style
	 */
	public function register_block_styles() {
		/** Klimawidget Block */
		wp_register_style(
			$this->plugin_name . '-klimawidget-style',
			PLUGIN_OKLAB_KLIMAWIDGET_URL . 'dist/style-klimawidget.css',
			array(),
			filemtime( PLUGIN_OKLAB_KLIMAWIDGET_PATH . 'dist/style-klimawidget.css' ),
			'all'
		);
	}

	/**
	 * Hook to register Block in Parent Theme for Inline Styles
	 *
	 * @return void
	 */
	public function kftheme_register_blocks( $blocks ) {
		$blocks['oklab-blocks/klimawidget'] = array(
			'name'         => 'oklab-blocks/klimawidget',
			'style_handle' => $this->plugin_name . '-klimawidget-style',
			'style_path'   => PLUGIN_OKLAB_KLIMAWIDGET_PATH . 'dist/style-klimawidget.css',
			'style_deps'   => array(),
		);

		return $blocks;
	}

	public function register_block_types() {
		/**
		 * Register Gutenberg block on server-side.
		 *
		 * Register the block on server-side to ensure that the block
		 * scripts and styles for both frontend and backend are
		 * enqueued when the editor loads.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
		 * @since 1.16.0
		 */
		register_block_type(
			'oklab-blocks/klimawidget',
			array(
				'editor_script'   => $this->plugin_name . '-klimawidget-editor-script',
				'editor_style'    => $this->plugin_name . '-klimawidget-editor-style',
				'script'          => $this->plugin_name . '-klimawidget-script',
				'render_callback' => array( 'OKLab_Klimawidget\Rendering\OKLab_Klimawidget_Klimawidget_Block', 'callback' ),
			)
		);
	}
}
