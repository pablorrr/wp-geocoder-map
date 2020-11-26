<?php

/**
 * Controller class that implements Plugin Admin Settings configurations
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/controllers/admin
 *
 */
namespace Controllers\Administer;

use Includes\Geocoder_Map;
use Includes\Geocoder_Map_Actions_Filters;
use Includes\Geocoder_Map_Shortcodes;
use Models\Administer\Geocoder_Map_Model_Admin_Settings;

if (!class_exists('Geocoder_Map_Controller_Admin_Settings')) {

    class Geocoder_Map_Controller_Admin_Settings extends Geocoder_Map_Controller_Admin
    {

        private static $hook_suffix = '';

        const SETTINGS_PAGE_URL = Geocoder_Map::PLUGIN_ID;
        const REQUIRED_CAPABILITY = 'manage_options';


        /**
         * Constructor
         *
         * @since    1.0.0
         */
        public function __construct()
        {

            static::$hook_suffix = 'settings_page_' . Geocoder_Map::PLUGIN_ID;

            $this->register_hook_callbacks();
            $this->model = Geocoder_Map_Model_Admin_Settings::get_instance();

        }

        /**
         * Register callbacks for actions and filters
         *
         * @since    1.0.0
         */
        protected function register_hook_callbacks()
        {

            Geocoder_Map_Actions_Filters::add_action('admin_menu', $this, 'plugin_menu');
            Geocoder_Map_Actions_Filters::add_action('admin_print_scripts-' . static::$hook_suffix, $this, 'enqueue_scripts');
            Geocoder_Map_Actions_Filters::add_action('admin_print_styles-' . static::$hook_suffix, $this, 'enqueue_styles');
            Geocoder_Map_Actions_Filters::add_action('load-' . static::$hook_suffix, $this, 'register_fields');

            Geocoder_Map_Actions_Filters::add_filter(
                'plugin_action_links_' . Geocoder_Map::PLUGIN_ID . '/' . Geocoder_Map::PLUGIN_ID . '.php',
                $this,
                'add_plugin_action_links'
            );
            //TODO: ZAMIENIC  GEOCODER MAP NA TSALA REPREZ TEN STRING
            Geocoder_Map_Shortcodes::add_shortcode('geocoder-map', $this, 'geocoder_shortcode');


        }

        /**
         * Create menu for Plugin inside Settings menu
         *
         * @since    1.0.0
         */
        public function plugin_menu()
        {

            static::$hook_suffix = add_options_page(
                __(Geocoder_Map::GEOCODER_MAP, Geocoder_Map::PLUGIN_ID),        // Page Title
                __(Geocoder_Map::GEOCODER_MAP, Geocoder_Map::PLUGIN_ID),        // Menu Title
                static::REQUIRED_CAPABILITY,           // Capability
                static::SETTINGS_PAGE_URL,             // Menu URL
                array($this, 'markup_settings_page') // Callback
            );

        }

        /**
         * Register the JavaScript for the admin area.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts($hook)
        {
            // Register the script
            wp_register_script(Geocoder_Map::PLUGIN_ID . '_admin-js', Geocoder_Map::get_plugin_url() . 'views/admin/js/' . Geocoder_Map::PLUGIN_ID . '-admin.js',
                array(),
                Geocoder_Map::PLUGIN_VERSION,
                true
            );
            // Localize the script with new data
            //TODO: ZAMIENIC NA STALA JUZ ZDEF IREPR geocoder-map
            $geocoder_opt = get_option('geocoder-map');
            $option_array = array(
                'address_field_id' => $geocoder_opt['address_field_id'],
                'fullscreen_field_id' => $geocoder_opt['fullscreen_field_id'],
                'disable_defaultUI_field_id' => $geocoder_opt['disable_defaultUI_field_id'],
                'draggable_field_id' => $geocoder_opt['draggable_field_id'],
                'map_type_field_id' => $geocoder_opt['map_type_field_id']
            );
            wp_localize_script(Geocoder_Map::PLUGIN_ID . '_admin-js', 'object_name', $option_array);
            wp_enqueue_script(Geocoder_Map::PLUGIN_ID . '_admin-js');

            wp_enqueue_script(Geocoder_Map::PLUGIN_ID . 'geo-key-admin',
                'https://maps.googleapis.com/maps/api/js?key=' . $geocoder_opt["API_key_GM_field_id"] . '&callback=initMap&libraries=&v=weekly', array(),
                '', true);
        }

        /**
         * Register the JavaScript for the admin area.
         *
         * @since    1.0.0
         */
        public function enqueue_styles($hook)
        {

            /**
             * This function is provided for demonstration purposes only.
             *
             */

            wp_enqueue_style(
                Geocoder_Map::PLUGIN_ID . '_admin',
                Geocoder_Map::get_plugin_url() . 'views/admin/css/' . Geocoder_Map::PLUGIN_ID . '-admin.css',
                array(),
                Geocoder_Map::PLUGIN_VERSION,
                'all'
            );

        }

        /**
         * Creates the markup for the Settings page
         *
         * @since    1.0.0
         */
        public function markup_settings_page()
        {

            if (current_user_can(static::REQUIRED_CAPABILITY)) {

                echo static::render_template(
                    'page-settings/page-settings.php',
                    array(
                        'page_title' => Geocoder_Map::GEOCODER_MAP,
                        'settings_name' => Geocoder_Map_Model_Admin_Settings::SETTINGS_NAME
                    )
                );

            } else {

                wp_die(__('Access denied.'));

            }

        }

        /**
         * Registers settings sections and fields
         *
         * @since    1.0.0
         */
        public function register_fields()
        {
            // Add Settings Page Section
            add_settings_section(
                'geocoder_map_section',                    // Section ID
                __('Settings', Geocoder_Map::PLUGIN_ID),                         // Section Title
                array($this, 'markup_section_headers'), // Section Callback
                static::SETTINGS_PAGE_URL                 // Page URL
            );

            // Add Settings Page Filed Address
            add_settings_field(
                'address_field_id',                        // Field ID
                __('Write Addres:', Geocoder_Map::PLUGIN_ID),                 // Field Title
                array($this, 'address_callback'),            // Field Callback
                static::SETTINGS_PAGE_URL,                  // Page
                'geocoder_map_section',                      // Section ID
                array(                                      // Field args jako atrr html pola
                    'id' => 'address_field_id',//przekaz param dla callback
                    'label_for' => 'address_field_id'
                )
            );
            // Add Settings Page Field FullScreen
            add_settings_field(
                'fullscreen_field_id',                        // Field ID
                __('Full Screen on/off Controll', Geocoder_Map::PLUGIN_ID),                 // Field Title
                array($this, 'full_screen_callback'),            // Field Callback
                static::SETTINGS_PAGE_URL,                  // Page
                'geocoder_map_section',                      // Section ID
                array(                                      // Field args
                    'id' => 'fullscreen_field_id',
                    'label_for' => 'fullscreen_field_id'
                )
            );

            // Add Settings Page Field DefaultUI on/off
            add_settings_field(
                'disable_defaultUI_field_id',                        // Field ID
                __('Zooming +,-  on/off Controll', Geocoder_Map::PLUGIN_ID),                 // Field Title
                array($this, 'defaultUI_callback'),            // Field Callback
                static::SETTINGS_PAGE_URL,                  // Page
                'geocoder_map_section',                      // Section ID
                array(                                      // Field args
                    'id' => 'disable_defaultUI_field_id',
                    'label_for' => 'disable_defaultUI_field_id'
                )
            );
            //Add Settings Page Field Draggable
            add_settings_field(
                'draggable_field_id',                        // Field ID
                __('Draggable on/off', Geocoder_Map::PLUGIN_ID),                 // Field Title
                array($this, 'draggable_callback'),            // Field Callback
                static::SETTINGS_PAGE_URL,                  // Page
                'geocoder_map_section',                      // Section ID
                array(                                      // Field args
                    'id' => 'draggable_field_id',
                    'label_for' => 'draggable_field_id'
                )
            );

            //Add Settings Page Field Map Type
            add_settings_field(
                'map_type_field_id',                        // Field ID
                __('Select Map Type', Geocoder_Map::PLUGIN_ID),                 // Field Title
                array($this, 'map_type_callback'),            // Field Callback
                static::SETTINGS_PAGE_URL,                  // Page
                'geocoder_map_section',                      // Section ID
                array(                                      // Field args
                    'id' => 'map_type_field_id',
                    'label_for' => 'map_type_field_id'
                )
            );

            //Add Settings Page Field API_key_GM
            add_settings_field(
                'API_key_GM_field_id',                        // Field ID
                __('Enter API key GM', Geocoder_Map::PLUGIN_ID),                 // Field Title
                array($this, 'API_key_GM_callback'),            // Field Callback
                static::SETTINGS_PAGE_URL,                  // Page
                'geocoder_map_section',                      // Section ID
                array(                                      // Field args
                    'id' => 'API_key_GM_field_id',
                    'label_for' => 'API_key_GM_field_id'
                )
            );
        }


        public function markup_section_headers($section)
        {

            echo static::render_template(
                'page-settings/page-settings-section-headers.php',
                array(
                    'section' => $section,
                    'text_example' => __('This is a text example for section header', Geocoder_Map::PLUGIN_ID)
                )
            );

        }

        /**
         * callabacks fot fields render
         */
        public function address_callback($field_args)
        {

            $address_field_id = $field_args['id'];
            $settings_value = static::get_model()->get_settings($address_field_id);

            echo static::render_template(
                'page-settings/page-settings-fields.php',
                array(
                    'address_field_id' => esc_attr($address_field_id),
                    'settings_name' => Geocoder_Map_Model_Admin_Settings::SETTINGS_NAME,
                    'settings_value' => !empty($settings_value) ? esc_attr($settings_value) : ''
                ),
                'always'
            );

        }

        public function full_screen_callback($field_args)
        {

            $fullscreen_field_id = $field_args['id'];
            $settings_value = static::get_model()->get_settings($fullscreen_field_id);

            echo static::render_template(
                'page-settings/page-settings-fields.php',
                array(
                    'fullscreen_field_id' => esc_attr($fullscreen_field_id),
                    'settings_name' => Geocoder_Map_Model_Admin_Settings::SETTINGS_NAME,
                    'settings_value' => !empty($settings_value) ? esc_attr($settings_value) : ''
                ),
                'always'
            );

        }

        public function defaultUI_callback($field_args)
        {
            $disable_defaultUI_field_id = $field_args['id'];
            $settings_value = static::get_model()->get_settings($disable_defaultUI_field_id);

            echo static::render_template(
                'page-settings/page-settings-fields.php',
                array(
                    'disable_defaultUI_field_id' => esc_attr($disable_defaultUI_field_id),
                    'settings_name' => Geocoder_Map_Model_Admin_Settings::SETTINGS_NAME,
                    'settings_value' => !empty($settings_value) ? esc_attr($settings_value) : ''
                ),
                'always'
            );

        }

        public function draggable_callback($field_args)
        {
            $draggable_field_id = $field_args['id'];
            $settings_value = static::get_model()->get_settings($draggable_field_id);

            echo static::render_template(
                'page-settings/page-settings-fields.php',
                array(
                    'draggable_field_id' => esc_attr($draggable_field_id),
                    'settings_name' => Geocoder_Map_Model_Admin_Settings::SETTINGS_NAME,
                    'settings_value' => !empty($settings_value) ? esc_attr($settings_value) : ''
                ),
                'always'
            );

        }

        public function map_type_callback($field_args)
        {
            $map_type_field_id = $field_args['id'];
            $settings_value = static::get_model()->get_settings($map_type_field_id);

            echo static::render_template(
                'page-settings/page-settings-fields.php',
                array(
                    'map_type_field_id' => esc_attr($map_type_field_id),
                    'settings_name' => Geocoder_Map_Model_Admin_Settings::SETTINGS_NAME,
                    'settings_value' => !empty($settings_value) ? esc_attr($settings_value) : ''
                ),
                'always'
            );

        }

        public function API_key_GM_callback($field_args)
        {
            $API_key_GM_field_id = $field_args['id'];
            $settings_value = static::get_model()->get_settings($API_key_GM_field_id);

            echo static::render_template(
                'page-settings/page-settings-fields.php',
                array(
                    'API_key_GM_field_id' => esc_attr($API_key_GM_field_id),
                    'settings_name' => Geocoder_Map_Model_Admin_Settings::SETTINGS_NAME,
                    'settings_value' => !empty($settings_value) ? esc_attr($settings_value) : ''
                ),
                'always'
            );

        }

        /**
         * Adds links to the plugin's action link section on the Plugins page
         *
         * @param array $links The links currently mapped to the plugin
         * @return array
         *
         * @since    1.0.0
         */
        public function add_plugin_action_links($links)
        {

            $settings_link = '<a href="options-general.php?page=' . static::SETTINGS_PAGE_URL . '">' . __('Settings', Geocoder_Map::PLUGIN_ID) . '</a>';
            array_unshift($links, $settings_link);

            return $links;

        }

        public function geocoder_shortcode()
        {  ob_start(); ?>
            <div id="cont">
                <div id="map" style="width: 100%; height:100%;"></div>
            </div>
            <?php return ob_get_clean();
        }

    }

}
