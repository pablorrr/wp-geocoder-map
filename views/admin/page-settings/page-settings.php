<div class="wrap">

    <h2><?php esc_html_e(__($page_title)); ?></h2>

    <div id="message_update" class="updated notice is-dismissible" style="display:none;">
        <p>
            <strong><?php echo __('Geocoder Map Settings Updated'); ?>.</strong>
        </p>
    </div>
    <div id="optionContener">
        <form id="geocoder-map" method="post" action="options.php">
            <?php
            settings_fields($settings_name);
            do_settings_sections($settings_name);
            submit_button();
            ?>
        </form>
    </div>
    <div id="cont">
        <div id="map" style="width: 100%; height: 100%;"></div>
    </div>
    <?php
   // $test =get_option('geocoder-map');
    //echo  $test['address_field_id'];
    ?>
</div> <!-- .wrap -->