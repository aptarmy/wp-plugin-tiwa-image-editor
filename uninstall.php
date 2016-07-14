<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the tiwa image editor
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Tiwa_Image_Editor
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Drop plugin-created tables
global $wpdb;
$sql = "
	DROP TABLE IF EXISTS ". $wpdb->prefix ."tiwaimageeditor_images;
	DROP TABLE IF EXISTS ". $wpdb->prefix ."tiwaimageeditor_images_permission;
";
$wpdb->query($sql);

// Delete plugin-ptions
delete_option("tiwa_image_editor_options");