<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Tiwa_Image_Editor
 * @subpackage Tiwa_Image_Editor/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Tiwa_Image_Editor
 * @subpackage Tiwa_Image_Editor/includes
 * @author     Your Name <email@example.com>
 */
class Tiwa_Image_Editor_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

			// Create Tables
			global $wpdb;
			$prefix = $wpdb->prefix;
			$collate = '';
			if ( $wpdb->has_cap( 'collation' ) ) {
				$collate = $wpdb->get_charset_collate();
			}
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			
			// Create Table
			$sql = "
				CREATE TABLE ". $prefix ."tiwaimageeditor_images (
					ID bigint(20) UNSIGNED NOT NULL auto_increment,
					path text COLLATE utf8_unicode_ci NOT NULL,
					image_title tinytext COLLATE utf8_unicode_ci NOT NULL,
					image_description text COLLATE utf8_unicode_ci NOT NULL,
					owner_id bigint(20) NOT NULL,
					PRIMARY KEY  (ID),
					KEY owner_id (owner_id)
				) $collate;
				CREATE TABLE ". $prefix ."tiwaimageeditor_images_permission (
					ID bigint(20) NOT NULL auto_increment,
					image_id bigint(20) NOT NULL,
					shared_users_id bigint(20) NOT NULL,
					PRIMARY KEY  (ID),
					KEY image_id (image_id),
					KEY shared_users_id (shared_users_id)
				) $collate;
			";
			dbDelta($sql);
	}

}
