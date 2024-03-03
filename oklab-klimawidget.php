<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           OKLab_Klimawidget
 *
 * @wordpress-plugin
 * Plugin Name:       OKLab Klimawidget
 * Plugin URI:
 * Description:
 * Version:           1.0.0
 * Author:            Steffen Peschel
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       oklab-klimawidget
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'OKLAB_KLIMAWIDGET_VERSION', '1.0.0' );
define( 'PLUGIN_OKLAB_KLIMAWIDGET_PATH', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_OKLAB_KLIMAWIDGET_PATH_REL', basename( dirname( __FILE__ ) ) );
define( 'PLUGIN_OKLAB_KLIMAWIDGET_URL', plugin_dir_url( __FILE__ ) );

// load composer autoloader
require_once PLUGIN_OKLAB_KLIMAWIDGET_PATH . 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_oklab_klimawidget() {
	\OKLab_Klimawidget\Plugin_OKLab_Klimawidget_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_oklab_klimawidget() {
	\OKLab_Klimawidget\Plugin_OKLab_Klimawidget_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_oklab_klimawidget' );
register_deactivation_hook( __FILE__, 'deactivate_oklab_klimawidget' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function oklab_klimawidget() {
	$plugin = new \OKLab_Klimawidget\Inc\OKLab_Klimawidget();
	$plugin->run();
}

oklab_klimawidget();
