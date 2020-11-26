<?php

/**
 * Register all shortcodes for the plugin.
 */
namespace Includes;

if (!class_exists('Geocoder_Map_Shortcodes')) {

    class Geocoder_Map_Shortcodes
    {

        /**
         * The array of actions registered with WordPress.
         *
         * @since    1.0.0
         * @access   protected
         * @var      array $shortcodes The actions registered with WordPress to fire when the plugin loads.
         */
        protected static $shortcodes = array();


        public static function add_shortcode($tag, $component, $callback)
        {

            self::$shortcodes = self::add(self::$shortcodes, $tag,$component,$callback);

        }

        private static function add($shortcodes,$tag,$component,$callback)
        {

            $shortcodes[] = array(
                'tag'       => $tag,
                'component' => $component,
                'callback'  => $callback
            );

			return $shortcodes;

		}

        public static function init_shortcodes()
        {

            foreach (self::$shortcodes as $shortcode) {
                add_shortcode($shortcode['tag'],array($shortcode['component'],$shortcode['callback']));
            }

        }

    }

}