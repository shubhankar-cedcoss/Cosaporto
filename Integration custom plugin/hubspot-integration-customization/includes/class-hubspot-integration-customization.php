<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       makwebbetter.com
 * @since      1.0.0
 *
 * @package    Hubspot_Integration_Customization
 * @subpackage Hubspot_Integration_Customization/includes
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
 * @package    Hubspot_Integration_Customization
 * @subpackage Hubspot_Integration_Customization/includes
 * @author     MakeWebBetter <support@makewebbetter.com>
 */
class Hubspot_Integration_Customization {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hubspot_Integration_Customization_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'HUBSPOT_INTEGRATION_CUSTOMIZATION_VERSION' ) ) {
			$this->version = HUBSPOT_INTEGRATION_CUSTOMIZATION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'hubspot-integration-customization';

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
	 * - Hubspot_Integration_Customization_Loader. Orchestrates the hooks of the plugin.
	 * - Hubspot_Integration_Customization_i18n. Defines internationalization functionality.
	 * - Hubspot_Integration_Customization_Admin. Defines all hooks for the admin area.
	 * - Hubspot_Integration_Customization_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubspot-integration-customization-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubspot-integration-customization-i18n.php';
		/**
		 * The class responsible for all of the REST API calls
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubspot-integration-customization-rest.php';
		/**
		 * The class responsible for WooCommerce Box Office compatibility
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubspot-integration-customization-box-office.php';
		/**
		 * The class responsible for product inventory management for WooCommerce
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-hubspot-integration-customization-products-iv.php';		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-hubspot-integration-customization-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-hubspot-integration-customization-public.php';

		$this->loader = new Hubspot_Integration_Customization_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Hubspot_Integration_Customization_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Hubspot_Integration_Customization_i18n();

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

		$plugin_admin = new Hubspot_Integration_Customization_Admin( $this->get_plugin_name(), $this->get_version() );

		// hubwoo hooks

		$this->loader->add_action( 'hubwoo_ecomm_deal_created', $plugin_admin, 'hubwoo_update_ecomm_deal' );
		// $this->loader->add_action( 'hubwoo_abandoned_cart_html', $plugin_admin, 'hubwoo_abandoned_cart_html', 10, 2 );		

		// schedule events

		// $this->loader->add_action( 'hubwoo_single_schedule_event', $plugin_admin, 'hubwoo_single_schedule_event', 10, 2 );
		// $this->loader->add_action( 'hubwoo_sync_extra_deal_data', $plugin_admin, 'hubwoo_sync_extra_deal_data' );

		// update hooks

		// $this->loader->add_action( 'woocommerce_update_product', $plugin_admin, 'hubwoo_product_did_updated', 10 );		
		// $this->loader->add_action( 'woocommerce_update_order', $plugin_admin, 'hubwoo_order_did_updated', 10 );		


		// from here you can mapp contact properties
		$this->loader->add_filter( 'hubwoo_map_new_properties', $plugin_admin, 'hubwoo_extend_contact_properties', 10, 2 );	

		// for syncing custom deals properties
		$this->loader->add_action( 'woocommerce_checkout_order_processed', $plugin_admin, 'woocommerce_custom_field', 99, 1 );

		$this->loader->add_action( 'hubwoo_ecomm_deal_created', $plugin_admin, 'woocommerce_custom_field' );

		//for syncing custom product properties 
		$this->loader->add_action( 'hubwoo_update_product_property', $plugin_admin , 'hubwoo_custom_product_properties' );

				//for syncing custom product properties 
		$this->loader->add_action( 'hubwoo_update_product_property', $plugin_admin , 'hubwoo_custom_product_properties' );

		//for company syncing
		$this->loader->add_action( 'admin_init', $plugin_admin , 'hubwoo_custom_company_properties' );

		$this->loader->add_action( 'hubwoo_ecomm_deal_created', $plugin_admin , 'hubwoo_associate_company', 99, 1 );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Hubspot_Integration_Customization_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_public, 'hubwoo_add_abncart_products', 10 );		

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
	 * @return    Hubspot_Integration_Customization_Loader    Orchestrates the hooks of the plugin.
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
//end of file
}
