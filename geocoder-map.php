<?php

/**
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Geocoder_Map
 *
 * @wordpress-plugin
 * Plugin Name:       WP Geocoder Map
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       Print your Google Map by address and set up GM behavior optionally by Settings Page
 * Version:           1.0.0
 * Author:            Pablozzz Corp
 * Author URI:        http://websitecreator.pl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       geocoder-map
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
use Includes\Geocoder_Map;

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'GEOCODER_MAP_REQUIRED_PHP_VERSION', '5.3' ); // because of get_called_class()
define( 'GEOCODER_MAP_REQUIRED_WP_VERSION',  '3.0' );
define( 'GEOCODER_MAP_REQUIRED_WP_NETWORK',  false ); // because plugin is not compatible with WordPress multisite

/**
 * Checks if the system requirements are met
 *
 * @since    1.0.0
 * @return bool True if system requirements are met, false if not
 */
function geocoder_map_requirements_met() {

	global $wp_version;

	if ( version_compare( PHP_VERSION, GEOCODER_MAP_REQUIRED_PHP_VERSION, '<' ) ) {
		return false;
	}
	if ( version_compare( $wp_version, GEOCODER_MAP_REQUIRED_WP_VERSION, '<' ) ) {
		return false;
	}
	if ( is_multisite() != GEOCODER_MAP_REQUIRED_WP_NETWORK ) {
		return false;
	}

	return true;

}

/**
 * Prints an error that the system requirements weren't met.
 *
 * @since    1.0.0
 */
function geocoder_map_show_requirements_error() {

	global $wp_version;
	require_once( dirname( __FILE__ ) . '/views/admin/errors/requirements-error.php' );

}

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_geocoder_map() {

	/**
	 * Check requirements and load main class
	 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met.
	 * Otherwise older PHP installations could crash when trying to parse it.
	 **/
	if ( geocoder_map_requirements_met() ) {

		/**
		 * The core plugin class that is used to define internationalization,
		 * admin-specific hooks, and public-facing site hooks.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-geocoder-map.php';

		/**
		 * Begins execution of the plugin.
		 *
		 * Since everything within the plugin is registered via hooks,
		 * then kicking off the plugin from this point in the file does
		 * not affect the page life cycle.
		 *
		 * @since    1.0.0
		 */
        //metoda zawarta we weczesiej zaladowanej class-geocoder-map.php, laduje caly plugin
        //get instance spwowduej rowniez uruchomienia konstruktora tylko raz i tylko po to jest ta lnia


		$plugin = Geocoder_Map::get_instance();

	} else {

		add_action( 'admin_notices', 'geocoder_map_show_requirements_error' );
        //njprwd laduje definicje metody deactivate_plugins
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );//TODO : SPRAWDZ NAJPIERW CZY DEACTIVE PLLUGINS ISTNIEJE!!
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
}
run_geocoder_map();