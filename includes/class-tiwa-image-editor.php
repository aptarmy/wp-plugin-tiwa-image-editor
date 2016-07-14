<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Tiwa_Image_Editor
 * @subpackage Tiwa_Image_Editor/includes
 */

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
 * @package    Tiwa_Image_Editor
 * @subpackage Tiwa_Image_Editor/includes
 * @author     Your Name <email@example.com>
 */
class Tiwa_Image_Editor {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Tiwa_Image_Editor_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $tiwa_image_editor    The string used to uniquely identify this plugin.
	 */
	protected $tiwa_image_editor;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
        
        /**
         * Creative Cloud Client ID and Client Secret for Adobe Creative Cloud usage
         * 
         * @since   1.0.0
         * @access  private
         * @var     array   client_secret, client_id
         */
        private $creative_cloud_credentials = array(
            "client_secret" => "6caf73e2-7315-47c7-9e2a-d6b4848e5e0a",
            "client_id" => "d3da383da82844a7b726470c25e6e01c" // development mode
        );

        /**
	 * Define the core functionality of the plugin.
	 *
	 * Set the tiwa image editor and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->tiwa_image_editor = 'tiwa-image-editor';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Tiwa_Image_Editor_Loader. Orchestrates the hooks of the plugin.
	 * - Tiwa_Image_Editor_i18n. Defines internationalization functionality.
	 * - Tiwa_Image_Editor_Admin. Defines all hooks for the admin area.
	 * - Tiwa_Image_Editor_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * Titan Framework
		 * the framework helps create admin-option-page/customizer-page/meta-box easier
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/titan-framework/titan-framework-embedder.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tiwa-image-editor-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tiwa-image-editor-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tiwa-image-editor-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-tiwa-image-editor-public.php';
                
                /**
		 * The class responsible for defining REST API
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tiwa-image-editor-rest.php';

		$this->loader = new Tiwa_Image_Editor_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Tiwa_Image_Editor_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Tiwa_Image_Editor_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Tiwa_Image_Editor_Admin( $this->get_tiwa_image_editor(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'tf_create_options', $plugin_admin, 'add_options_page');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Tiwa_Image_Editor_Public( $this->get_tiwa_image_editor(), $this->get_version(), $this->get_creative_cloud_credential() );
		$plugin_rest = new Tiwa_Image_Editor_Rest();

		$this->loader->add_action( 'rest_api_init', $plugin_rest, 'register_rest_api');
		
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_shortcode('tiwa-image-editor-upload', $plugin_public, 'add_shortcode_uploadfile');
		$this->loader->add_shortcode('tiwa-image-editor-dashboard', $plugin_public, 'add_shortcode_dashboard');
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
	public function get_tiwa_image_editor() {
		return $this->tiwa_image_editor;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Tiwa_Image_Editor_Loader    Orchestrates the hooks of the plugin.
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
        
        /**
         * Retrive the Credential for using Adobe Creative Cloude API
         * @return  array   Creadential got from Adobe
         */
        public function get_creative_cloud_credential() {
            return $this->creative_cloud_credentials;
        }
        
}
