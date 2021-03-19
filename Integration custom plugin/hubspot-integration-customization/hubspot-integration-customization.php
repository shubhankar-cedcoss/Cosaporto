<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              makwebbetter.com
 * @since             1.0.0
 * @package           Hubspot_Integration_Customization
 *
 * @wordpress-plugin
 * Plugin Name:       HubSpot integration customization
 * Plugin URI:        makewebbetter.com/hubspot-for-woocommerce
 * Description:       Customizations for HubSpot Integration.
 * Version:           1.0.0
 * Author:            MakeWebBetter
 * Author URI:        makwebbetter.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hubspot-integration-customization
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'HUBSPOT_INTEGRATION_CUSTOMIZATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hubspot-integration-customization-activator.php
 */
function activate_hubspot_integration_customization() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hubspot-integration-customization-activator.php';
	Hubspot_Integration_Customization_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hubspot-integration-customization-deactivator.php
 */
function deactivate_hubspot_integration_customization() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hubspot-integration-customization-deactivator.php';
	Hubspot_Integration_Customization_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hubspot_integration_customization' );
register_deactivation_hook( __FILE__, 'deactivate_hubspot_integration_customization' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hubspot-integration-customization.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hubspot_integration_customization() {

	$plugin = new Hubspot_Integration_Customization();
	$plugin->run();

}
run_hubspot_integration_customization();
