<?php

namespace OKLab_Klimawidget\Inc;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    OKLab_Klimawidget
 */
class OKLab_Klimawidget_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'oklab-klimawidget',
			false,
			PLUGIN_OKLAB_KLIMAWIDGET_PATH_REL . '/languages'
		);
	}

	public function set_script_translations() {
		wp_set_script_translations(
			'oklab-klimawidget-block-admin-script',
			'oklab-klimawidget',
			PLUGIN_OKLAB_KLIMAWIDGET_PATH . 'languages'
		);
	}
}
