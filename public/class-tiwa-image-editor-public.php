<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Tiwa_Image_Editor
 * @subpackage Tiwa_Image_Editor/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the tiwa image editor, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tiwa_Image_Editor
 * @subpackage Tiwa_Image_Editor/public
 * @author     Your Name <email@example.com>
 */
class Tiwa_Image_Editor_Public {

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
         * Creative Cloud Credential
         */
        private $creative_cloud_credential;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $tiwa_image_editor       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $tiwa_image_editor, $version, $creative_cloud_credential ) {

		$this->tiwa_image_editor = $tiwa_image_editor;
		$this->version = $version;
                $this->creative_cloud_credential = $creative_cloud_credential;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_enqueue_style( $this->tiwa_image_editor, plugin_dir_url( __FILE__ ) . 'css/tiwa-image-editor-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( "{$this->tiwa_image_editor}-bootstrap", 'http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		if (!is_user_logged_in()) {
			return;
		}
		//wp_enqueue_script( "{$this->tiwa_image_editor}-aviary", 'http://feather.aviary.com/imaging/v3/editor.js', array(), $this->version, false );
		wp_enqueue_script( $this->tiwa_image_editor, plugin_dir_url( __FILE__ ) . 'js/tiwa-image-editor-public-upload.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( "{$this->tiwa_image_editor}-bootstrap", 'http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		
		// Localize script
		// Get admin option value
		$tiwa = TitanFramework::getInstance( 'tiwa_image_editor' );
		$tiwa_options = array();
		$tiwa_options["general_redirectURL"] = $tiwa->getOption("general_redirectURL");
		wp_localize_script(
			$this->tiwa_image_editor,
			'wp_localize_script',
			array(
				'creative_cloud_credential' => $this->creative_cloud_credential,
				'site_url' => get_site_url(),
				'rest_api_root' => esc_url_raw( rest_url() ),
				'tiwa_image_editor_API_URL' => array("my"=>esc_url_raw( rest_url() ) . "tiwa-image-editor-api/v1/my/images/", "shared"=>esc_url_raw( rest_url() ) . "tiwa-image-editor-api/v1/shared/images/"),
				'rest_api_nonce' => wp_create_nonce( 'wp_rest' ),
				'plugin_options' => $tiwa_options,
				'plugin_public_url' => plugin_dir_url( __FILE__ ),
			)
		);
        /**
         * Add new shortcode to display Edited Image(s)
         */
	}	
	public function add_shortcode_uploadfile() {
		if (!is_user_logged_in()) {
			return;
		}
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/tiwa-image-editor-public-display-upload.php';
	}
	public function add_shortcode_dashboard() {
		wp_enqueue_script( "{$this->tiwa_image_editor}-angularjs", plugin_dir_url( __FILE__ ) . "partials/dashboard/lib/ionic/js/angular/angular.min.js", array(), $this->version, false );
		wp_enqueue_script( "{$this->tiwa_image_editor}-angular-uirouter", plugin_dir_url( __FILE__ ) . "partials/dashboard/lib/ionic/js/angular-ui/angular-ui-router.min.js", array("{$this->tiwa_image_editor}-angularjs"), $this->version, false );
		wp_enqueue_script( "{$this->tiwa_image_editor}-angularjs-tiwapp-app", plugin_dir_url( __FILE__ ) . "partials/dashboard/js/app.js", array(), $this->version, false );
		wp_enqueue_script( "{$this->tiwa_image_editor}-angularjs-tiwapp-controllers", plugin_dir_url( __FILE__ ) . "partials/dashboard/js/controllers.js", array(), $this->version, false );
		wp_enqueue_script( "{$this->tiwa_image_editor}-angularjs-tiwapp-services", plugin_dir_url( __FILE__ ) . "partials/dashboard/js/services.js", array(), $this->version, false );
		wp_localize_script(
			"{$this->tiwa_image_editor}-angularjs-tiwapp-app",
			'wp_localize_script',
			array(
				'site_url' => get_site_url(),
				'tiwa_image_editor_API_URL' => array("my"=>esc_url_raw( rest_url() ) . "tiwa-image-editor-api/v1/my/images/", "shared"=>esc_url_raw( rest_url() ) . "tiwa-image-editor-api/v1/shared/images/"),
				'rest_api_nonce' => wp_create_nonce( 'wp_rest' ),
				'plugin_public_url' => plugin_dir_url( __FILE__ ),
			)
		);
		//wp_enqueue_style( "{$this->tiwa_image_editor}-ionic", plugin_dir_url( __FILE__ ) . "partials/dashboard/lib/ionic/css/ionic.css", array(), $this->version, 'all' );
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/dashboard/tiwa-image-editor-public-display-dashboard.php';		
	}
}