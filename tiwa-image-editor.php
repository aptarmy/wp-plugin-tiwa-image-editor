<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Tiwa_Image_Editor
 *
 * @wordpress-plugin
 * Plugin Name:       Tiwa Image Editor
 * Plugin URI:        http://example.com/tiwa-image-editor-uri/
 * Description:       Dummy Description
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tiwa-image-editor
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tiwa-image-editor-activator.php
 */
function activate_tiwa_image_editor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tiwa-image-editor-activator.php';
	Tiwa_Image_Editor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tiwa-image-editor-deactivator.php
 */
function deactivate_tiwa_image_editor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tiwa-image-editor-deactivator.php';
	Tiwa_Image_Editor_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tiwa_image_editor' );
register_deactivation_hook( __FILE__, 'deactivate_tiwa_image_editor' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tiwa-image-editor.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tiwa_image_editor() {

	$plugin = new Tiwa_Image_Editor();
	$plugin->run();

}
run_tiwa_image_editor();
