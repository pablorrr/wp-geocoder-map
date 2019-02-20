<?php
/**
 * Plugin Name: google maps geocoder NEW VER
 * Description:sending geo addrees 
 * Version: 2.0
 * Author: PABLOZZZ
 * Author URI: http://websitecreator.pl
 * License: GPLv2
 * License url: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; 

function gecoder_map_translate() {

	// Set filter for plugin's languages directory
	$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/lang/';
	$lang_dir = apply_filters( 'gecoder_lang_directory', $lang_dir );

	// Load the translations
	load_plugin_textdomain( 'geocoder-map', false, $lang_dir );
}
add_action( 'init', 'gecoder_map_translate' );

add_action( 'admin_menu', 'google_map_add_page' );

function google_map_add_page() {
 $geocoder_admin_page = add_menu_page('Simple Geocode Map Setting', 
								'Geocode Address', 
								'manage_options',
								'geocoder-map', 
								'geocoder_do_page' );
								
 add_action('load-'.$geocoder_admin_page, 'geocoder_map_admin_help_tab');	
					
}

function geocoder_map_admin_help_tab () {
    $screen = get_current_screen();
	$text = 'Afterwards you save your settings, dont forget to paste this shortcode: [geocoder_map]
 in your posts or page. To see results of your changes on a map you must refresh website before.';
    // Add my_help_tab if current screen is My Admin Page
    $screen->add_help_tab( array(
        'id'	=> 'help_tab',
        'title'	=>  __( 'Welcome', 'geocoder-map'),
        'content'	=> '<p>'. __( 'Thx you have choosen my plugin', 'geocoder-map').'</p>',
    ) );
	
	 $screen->add_help_tab( array(
        'id'	=> 'help_tab_two',
        'title'	=> __( 'How to use plugin', 'geocoder-map'),
 'content'	=> '<p>'. __( 'Before you apply your settings please paste your GM api key', 'simple-google-map-plugin').'</p>',
		
    ) );
	 $screen->add_help_tab( array(
        'id'	=> 'help_tab_three',
        'title'	=> __( 'Another clues', 'geocoder-map'),
		'content'	=> '<p>'. __( $text , 'geocoder-map').'</p>',		
    ) );
	
	$screen->set_help_sidebar(
			'<p><strong>'. __( 'Quick Links', 'geocoder-map').'</strong></p>' .
            '<p><a href="http://websitecreator.pl" target="_blank">'.__( 'Author website', 'geocoder-map').'
			</a></p>' .
            '<p><a href="https://www.google.pl/maps" target="_blank">Google Maps</a></p>' .
'<p><a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">'.__( 'Obtain Geoogle Map key link', 'geocoder-map').'</a></p>'
			);
}

add_action( 'admin_init', 'geocoder_settings_init' ); 
function geocoder_settings_init() { 


	register_setting( 'optionGroup', 'option_settings' );

	add_settings_section(
		'geocoder_map_id_section', 
		'' ,//no section description
		'__return_false', //no callback section
		'optionGroup'
	);

	add_settings_field( 
		'address_field', 
		__('Enter address','geocoder-map') ,
		'address_field_render', 
		'optionGroup', 
		'geocoder_map_id_section'
	);
	
	add_settings_field(
		'fullscreen_field',
	  __('Enable / disable to turn Full Screen control', 'geocoder-map'),
		'fullscreen_filed_render',
		'optionGroup', 
		'geocoder_map_id_section'
	);
	
	add_settings_field(
		'disable_defaultUI_field',
	  __('Enable / disable Default controls', 'geocoder-map'),
		'disable_defaultUI_filed_render',
		'optionGroup', 
		'geocoder_map_id_section'
	);
	
	add_settings_field(
		'draggable_field',
	  __('Enable / disable draggable map', 'geocoder-map'),
		'draggable_filed_render',
		'optionGroup', 
		'geocoder_map_id_section'
	);
	
	add_settings_field(
		'mapTypeId_field',
	  __('Select Map Type', 'geocoder-map'),
		'mapTypeId_filed_render',
		'optionGroup', 
		'geocoder_map_id_section'
	);
	
	add_settings_field( 
		'API_key_GM_field', 
		__('Enter your GM API key','geocoder-map') ,
		'API_key_GM_field_render', 
		'optionGroup', 
		'geocoder_map_id_section'
	);

}
//address
function address_field_render() { 
			$options = get_option( 'option_settings' );
			$addres = $options['address_field'] ?  $options['address_field'] : 'Australia';?>
		<input type='text'  id ='address_field' name='option_settings[address_field]' 
		value="<?php echo esc_html(esc_js($addres));?>" />
		<span class="description"><?php _e('Type address to geocode like e.g "Sydney, NSW"','geocoder-map');?></span>	
<?php } 

//full screen control
function fullscreen_filed_render() { 
			$options = get_option( 'option_settings' );?>
		<input type='checkbox'  class='fullscreen' name='option_settings[fullscreen_field]' 
		<?php checked( $options['fullscreen_field'],'true' ); ?> value='true'/>
		<span class="description"><?php _e('Check to enable Full Screen control','geocoder-map');?></span>
		<?php   }
		
//defaultUI on/off
function disable_defaultUI_filed_render() { 
				$options = get_option('option_settings' );?>
		<input type='checkbox'  class="disDefUI" name='option_settings[disable_defaultUI_field]' 
		<?php checked( $options['disable_defaultUI_field'],'true' ); ?> value='true'/>
		<span  class="description"><?php _e('Check to disable Default control','geocoder-map');?></span>
		<?php   }	
		
//draggable
function draggable_filed_render() { 
			 $options = get_option('option_settings' );?>
		<input type='checkbox' class="draggg"   name='option_settings[draggable_field]' 
		<?php checked( $options['draggable_field'],'true' ); ?> value='true'/>
		<span  class="description"><?php _e('Check to enable draggable map functionality','geocoder-map');?></span>
		<?php   }		

//map type
function mapTypeId_filed_render(){
			$options = get_option('option_settings'); ?> 
	<select class="mapt" name='option_settings[mapTypeId_field]'>
		<option value='roadmap'<?php selected( $options['mapTypeId_field'], 'roadmap');?>>roadmap</option>
		<option value='satellite'<?php selected( $options['mapTypeId_field'], 'satellite' ); ?>>satellite</option>
		<option value='hybrid'<?php selected($options['mapTypeId_field'],'hybrid' ); ?>>hybrid</option>
		<option value='terrain'<?php selected($options['mapTypeId_field'],'terrain'); ?>>terrain</option>
	</select>	
 
	<?php  }
	
//API_key_GM
function API_key_GM_field_render() { 
			$options = get_option( 'option_settings' );
			$API_key_GM = $options['API_key_GM_field'];?>
		<input type='text'  id ='API_key_GM_field' name='option_settings[API_key_GM_field]' 
		value="<?php echo esc_html(esc_js($API_key_GM));?>" />
		<span class="description"><?php _e('Enter your GM API key','geocoder-map');?></span>	
<?php } 	
		
		
		
function geocoder_do_page(){ 
 settings_errors();?>
  <div id="optionContener">
	<form action='options.php' method='post'>
        <?php 
		settings_fields( 'optionGroup' );
		do_settings_sections('optionGroup' );
		submit_button();
		?>
		</form>
  </div>
 <div id="cont">
       <div id="map" style="width: 100%; height: 100%;"></div>	
 </div>
<?php }
//back end map css
add_action('admin_enqueue_scripts', 'custom_map_css');
	function custom_map_css() {
	wp_enqueue_style( 'geocoder-admin-css', plugins_url('css/map-style.css', __FILE__) );
}
/*
 * shortcode
 * 
 */
function gecoder_map_shortcode() {
ob_start();?>
	
	<div id="optionContener" style="visibility:hidden;">
	 <div>
	    <?php //render fields on front page to get a value
				address_field_render();
				fullscreen_filed_render();
				disable_defaultUI_filed_render();
			 draggable_filed_render();
				mapTypeId_filed_render();?>
	 </div>
	 </div>
		
	<div id="cont">
         <div id="map" style="width: 100%; height:100%;"></div>
	</div>
<?php return ob_get_clean(); } 
add_shortcode( 'geocoder_map', 'gecoder_map_shortcode' );

add_action('wp_print_scripts', 'enqueue_geocoder');
function enqueue_geocoder() {
	
    wp_register_script( 'geo-scripter', plugins_url( 'js/geocoder.js', __FILE__ ),array(),'', true );
	wp_enqueue_script ('geo-scripter');
	wp_enqueue_script('geo-key','https://maps.googleapis.com/maps/api/js?key=' . sanitize_text_field( $API_key_GM ) . '&callback=initMap',array(),'', true);  
	
}
//map ccs to front page
add_action( 'wp_enqueue_scripts', 'frontend_geocoder_map_css' );
function frontend_geocoder_map_css() {
	wp_enqueue_style( 'front-end-map', plugins_url( 'css/map-style.css', __FILE__ ));
}
