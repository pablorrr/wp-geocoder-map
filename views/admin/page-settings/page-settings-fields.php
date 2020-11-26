<?php if (!empty($address_field_id))://jesli pole puste dojdzie do duplikowania sie pol ?>
    <input type="text" id="<?php echo $address_field_id; ?>"
           name="<?php echo $settings_name . '[' . $address_field_id . ']'; ?>"
           value="<?php echo $settings_value; ?>"/>
<?php endif; ?>
<?php if (!empty($fullscreen_field_id)): ?>
    <input type="checkbox" id="<?php echo $fullscreen_field_id; ?>" class="fullscreen"
           name="<?php echo $settings_name . '[' . $fullscreen_field_id . ']'; ?>"
           <?php checked($settings_value, 'true'); ?>value="true">
<?php endif; ?>
<?php if (!empty($disable_defaultUI_field_id)): ?>
    <input type="checkbox" id="<?php echo $disable_defaultUI_field_id; ?>" class="disDefUI"
           name="<?php echo $settings_name . '[' . $disable_defaultUI_field_id . ']'; ?>"
           <?php checked($settings_value, 'true'); ?>value="true">
<?php endif; ?>
<?php if (!empty($draggable_field_id)): ?>
    <input type="checkbox" id="<?php echo $draggable_field_id; ?>" class="draggg"
           name="<?php echo $settings_name . '[' . $draggable_field_id . ']'; ?>"
           <?php checked($settings_value, 'true'); ?>value="true">
<?php endif; ?>
<?php if (!empty($map_type_field_id)): ?>
    <select id="<?php echo $map_type_field_id; ?>" class="mapt"
            name="<?php echo $settings_name . '[' . $map_type_field_id . ']'; ?>">

        <option value='roadmap'<?php selected($settings_value, 'roadmap'); ?>>roadmap</option>
        <option value='satellite'<?php selected($settings_value, 'satellite'); ?>>satellite</option>
        <option value='hybrid'<?php selected($settings_value, 'hybrid'); ?>>hybrid</option>
        <option value='terrain'<?php selected($settings_value, 'terrain'); ?>>terrain</option>

    </select>
<?php endif; ?>
<?php if (!empty($API_key_GM_field_id)): ?>
    <input type="text" id="<?php echo $API_key_GM_field_id; ?>"
           name="<?php echo $settings_name . '[' . $API_key_GM_field_id . ']'; ?>"
           value="<?php echo $settings_value; ?>"/>
<?php endif; ?>

