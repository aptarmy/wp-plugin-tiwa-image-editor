<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Tiwa_Image_Editor
 * @subpackage Tiwa_Image_Editor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the tiwa image editor, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tiwa_Image_Editor
 * @subpackage Tiwa_Image_Editor/admin
 * @author     Your Name <email@example.com>
 */
class Tiwa_Image_Editor_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $tiwa_image_editor    The ID of this plugin.
	 */
	private $tiwa_image_editor;

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
	 * @param      string    $tiwa_image_editor       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $tiwa_image_editor, $version ) {

		$this->tiwa_image_editor = $tiwa_image_editor;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tiwa_Image_Editor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tiwa_Image_Editor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->tiwa_image_editor, plugin_dir_url( __FILE__ ) . 'css/tiwa-image-editor-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tiwa_Image_Editor_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tiwa_Image_Editor_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->tiwa_image_editor, plugin_dir_url( __FILE__ ) . 'js/tiwa-image-editor-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Add options in admin page using Titan Framework
	 */
	public function add_options_page(){
		$tiwa = TitanFramework::getInstance( 'tiwa_image_editor' );
		$adminPanel = $tiwa->createAdminPanel( array(
			'name' => 'Image Editor',
			'icon' => 'dashicons-format-gallery',
		) );
		// Tab 1
		$generalTab = $adminPanel->createTab( array(
			'name' => 'General Tab',
		) );
		$generalTab->createOption( array(
			'name' => 'My Text Option',
			'id' => 'general_redirectURL',
			'type' => 'text',
			'desc' => 'Redirect URL used after finish editing image to show users what images they have edited and what images they have been shared with, Place the URL of a page that use a shortcode "[tiwa-image-editor-my]"'
		) );
		$generalTab->createOption( array(
			'type' => 'save',
		) );
	}

}
