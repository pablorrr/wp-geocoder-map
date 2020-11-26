<?php

/**
 * Controller class that implements Plugin public side controller class
 *
 * @since      1.0.0
 * @package    Geocoder_Map
 * @subpackage Geocoder_Map/controllers
 *
 */
namespace Controllers;
use Includes\Geocoder_Map;
use Includes\Geocoder_Map_Actions_Filters;

if (!class_exists('Geocoder_Map_Controller_Public')) {

    class Geocoder_Map_Controller_Public extends Geocoder_Map_Controller
    {

        /**
         * Constructor
         *
         * @since    1.0.0
         */
        protected function __construct()
        {

            $this->register_hook_callbacks();

        }

        /**
         * Register callbacks for actions and filters
         *
         * @since    1.0.0
         */
        protected function register_hook_callbacks()
        {

            Geocoder_Map_Actions_Filters::add_action('wp_enqueue_scripts', $this, 'enqueue_styles');
            Geocoder_Map_Actions_Filters::add_action('wp_enqueue_scripts', $this, 'enqueue_scripts');

        }


        /**
         * Register the stylesheets for the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function enqueue_styles()
        {

            /**
             * This function is provided for demonstration purposes only.
             */

            wp_enqueue_style(
                Geocoder_Map::PLUGIN_ID,
                Geocoder_Map::get_plugin_url() . 'views/css/' . Geocoder_Map::PLUGIN_ID . '.css',
                array(),
                Geocoder_Map::PLUGIN_VERSION,
                'all'
            );

        }

        /**
         * Register the JavaScript for the public-facing side of the site.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts()
        {
            // Register the script
            wp_register_script(Geocoder_Map::PLUGIN_ID, Geocoder_Map::get_plugin_url() . 'views/js/' . Geocoder_Map::PLUGIN_ID . '.js',
                array(),
                Geocoder_Map::PLUGIN_VERSION,
                true);
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
            wp_localize_script(Geocoder_Map::PLUGIN_ID, 'object_name', $option_array);
            wp_enqueue_script(Geocoder_Map::PLUGIN_ID);
            //TODO: API KEY MA SIE POJAWIAC POPRZEZ OPCJA Z FORMULARZA SPROBOWAC PRZEZ GET OPTION
            wp_enqueue_script(Geocoder_Map::PLUGIN_ID . 'geo-key',
                'https://maps.googleapis.com/maps/api/js?key=' . $geocoder_opt["API_key_GM_field_id"] . '&callback=initMap&libraries=&v=weekly', array(),
                '', true);

        }

    }
}