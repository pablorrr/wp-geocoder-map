<?php

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Geocoder_Map
 * @subpackage Geocoder_Map/includes
 *
 */
namespace Includes;

use Controllers\Administer\Geocoder_Map_Controller_Admin_Notices;
use Controllers\Administer\Geocoder_Map_Controller_Admin_Settings;
use Controllers\Geocoder_Map_Controller_Public;
use Includes\Geocoder_Map_Loader;

if (!class_exists('Geocoder_Map')) {

    class Geocoder_Map
    {

        /**
         *
         * @since    1.0.0
         * @access   private
         * @var      Plugin_Name $instance Instance of this class.
         */
        private static $instance;

        /**
         * The modules variable holds all modules of the plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      object $modules Maintains all modules of the plugin.
         */
        private static $modules = array();

        /**
         * Main plugin path /wp-content/plugins/<plugin-folder>/.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_path Main path.
         */
        private static $plugin_path;

        /**
         * Absolute plugin url <wordpress-root-folder>/wp-content/plugins/<plugin-folder>/.
         *
         * @since    1.0.0
         * @access   private
         * @var      string $plugin_url Main path.
         */
        private static $plugin_url;


        /**
         * The unique identifier of this plugin.
         *
         * @since    1.0.0
         */
        const PLUGIN_ID = 'geocoder-map';

        /**
         * The name identifier of this plugin.
         *
         * @since    1.0.0
         * previously was PLUGIN_NAME
         */
        const GEOCODER_MAP = 'Geocoder Map';


        /**
         * The current version of the plugin.
         *
         * @since    1.0.0
         */
        const PLUGIN_VERSION = '1.0.0';

        /**
         * The plugin prefix to referenciate classes inside the plugin
         *
         * @since    1.0.0
         */
        const CLASS_PREFIX = 'Geocoder_Map_';

        /**
         * The plugin prefix to referenciate files and prefixes inside the plugin
         *
         * @since    1.0.0
         */
        const PLUGIN_PREFIX = 'geocoder-map-';

        /**
         * Provides access to a single instance of a module using the singleton pattern
         *
         * @return object
         *
         * @since    1.0.0
         */
        public static function get_instance()
        {

            if (null === self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;

        }

        /**
         * Define the core functionality of the plugin.
         *
         * Load the dependencies, define the locale, and set the hooks for the admin area and
         * the public-facing side of the site.
         *
         * @since    1.0.0
         */
        protected function __construct()
        {
            self::$plugin_path = plugin_dir_path(dirname(__FILE__));
            self::$plugin_url = plugin_dir_url(dirname(__FILE__));

            require_once(self::$plugin_path . 'includes/class-' . self::PLUGIN_PREFIX . 'loader.php');
            //bardzo wazna funkcja loaderaklas automtycznie
            // laduje sciezke dostepowa do klasy ropzonajac jeje nazwe , wymagana zgodnosc nazewnictwa klass
            self::$modules['Geocoder_Map_Loader'] = Geocoder_Map_Loader::get_instance();
            //puliczny kotroleer r do niewralgicznych plikow public  js css htmlfront end
            self::$modules['Geocoder_Map_Controller_Public'] = Geocoder_Map_Controller_Public::get_instance();
            //kotrpoller amdinasisdytatcji
            self::$modules['Geocoder_Map_Controller_Admin_Settings'] = Geocoder_Map_Controller_Admin_Settings::get_instance();
            //kotrollerpowiadimin  adminitracfcyunych
            self::$modules['Geocoder_Map_Controller_Admin_Notices'] = Geocoder_Map_Controller_Admin_Notices::get_instance();


            //zainicjowanie akcji i filtrow
            //metoda ta zwiera jakby napisane funkcje wbudowane w WP takieak add_acrion , add_filter itp.
            Geocoder_Map_Shortcodes::init_shortcodes();
            Geocoder_Map_Actions_Filters::init_actions_filters();

        }

        /**
         * Get plugin's absolute path.
         *
         * @since    1.0.0
         */
        public static function get_plugin_path()
        {
            return isset(self::$plugin_path) ? self::$plugin_path : plugin_dir_path(dirname(__FILE__));

        }

        /**
         * Get plugin's absolute url.
         *
         * @since    1.0.0
         */
        public static function get_plugin_url()
        {
            return isset(self::$plugin_url) ? self::$plugin_url : plugin_dir_url(dirname(__FILE__));
        }
    }
}