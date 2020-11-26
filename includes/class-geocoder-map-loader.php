<?php

/**
 * Loader class that includes and loads dependencies and implements activation and deactivation methods
 *
 * @since      1.0.0
 * @package    Geocoder_Map
 * @subpackage Geocoder_Map/includes
 *
 */

namespace Includes;

use Models\Administer\Geocoder_Map_Model_Admin_Notices;
use Models\Administer\Geocoder_Map_Model_Admin_Settings;

if (!class_exists('Geocoder_Map_Loader')) {

    class Geocoder_Map_Loader
    {

        /**
         *
         * @since    1.0.0
         * @access   private
         * @var      Geocoder_Map_Loader $instance Instance of this class.
         */
        private static $instance;

        /**
         * Provides access to a single instance of a module using the singleton pattern
         *
         * @return object
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
         * Constructor
         *
         * @since    1.0.0
         */
        protected function __construct()
        {

            spl_autoload_register(array($this, 'load_dependencies'));

            $this->set_locale();
            $this->register_hook_callbacks();

        }

        /**
         * Loads all Plugin dependencies
         *
         * @since    1.0.0
         */
        private function load_dependencies($class)
        {

            if (false !== strpos($class, Geocoder_Map::CLASS_PREFIX)) {

                $classFileName = 'class-' . str_replace('_', '-', strtolower($class)) . '.php';
                $folder = '/';

                if (false !== strpos($class, '_Admin')) {
                    $classFileName = preg_replace('/administer/', '', strtolower($classFileName), 1);
                    $classFileName = stripslashes($classFileName);
                    $folder .= 'admin/';

                }
                if (false !== strpos($class, Geocoder_Map::CLASS_PREFIX . 'Controller')) {
                    $classFileName = stripslashes(str_replace('controllers', '', strtolower($classFileName)));
                    $path = Geocoder_Map::get_plugin_path() . 'controllers' . $folder . $classFileName;
                    require_once($path);
                } elseif (false !== strpos($class, Geocoder_Map::CLASS_PREFIX . 'Model')) {
                    $classFileName = stripslashes(str_replace('models', '', strtolower($classFileName)));
                    $path = Geocoder_Map::get_plugin_path() . 'models' . $folder . $classFileName;
                    require_once($path);
                } else {

                    $classFileName = stripslashes(str_replace('includes', '', strtolower($classFileName)));
                    $path = Geocoder_Map::get_plugin_path() . 'includes' . $folder . $classFileName;
                    require_once($path);
                }

            }

        }

        /**
         * Define the locale for this plugin for internationalization.
         *
         * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
         * with WordPress.
         *
         * @since    1.0.0.0
         */
        private function set_locale()
        {

            $plugin_i18n = new Geocoder_Map_i18n();
            $plugin_i18n->set_domain(Geocoder_Map::PLUGIN_ID);

            Geocoder_Map_Actions_Filters::add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

        }

        /**
         * Register callbacks for actions and filters
         *
         * @since    1.0.0.0
         */
        public function register_hook_callbacks()
        {

            register_activation_hook(Geocoder_Map::get_plugin_path() . Geocoder_Map::PLUGIN_ID . '.php', array($this, 'activate'));
            register_deactivation_hook(Geocoder_Map::get_plugin_path() . Geocoder_Map::PLUGIN_ID . '.php', array($this, 'deactivate'));

        }

        /**
         * Prepares sites to use the plugin during single or network-wide activation
         *
         * @param bool $network_wide
         * @since    1.0.0
         */
        public function activate($network_wide)
        {

        }


        /**
         * Rolls back activation procedures when de-activating the plugin
         *
         * @since    1.0.0
         */
        public function deactivate()
        {

            Geocoder_Map_Model_Admin_Notices::remove_admin_notices();

        }

        /**
         * Fired when user uninstalls the plugin, called in unisntall.php file
         *
         * @since    1.0.0
         */
        public static function uninstall_plugin()
        {

            require_once dirname(plugin_dir_path(__FILE__)) . '/includes/class-geocoder-map.php';
            require_once dirname(plugin_dir_path(__FILE__)) . '/models/class-gecoder-map-model.php';
            require_once dirname(plugin_dir_path(__FILE__)) . '/models/admin/class-geocoder-map-model-admin.php';
            require_once dirname(plugin_dir_path(__FILE__)) . '/models/admin/class-geocoder-map-model-admin-settings.php';

            Geocoder_Map_Model_Admin_Settings::delete_settings();

        }

    }

}