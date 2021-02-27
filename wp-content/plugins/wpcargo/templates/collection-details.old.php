<div id="shipper-details">
    <h2><?php echo apply_filters('wpc_shipper_details_label',esc_html__('Collection Details', 'wpcargo' ) ); ?></h2>
    <?php do_action('wpc_before_shipper_details_table', $post->ID); ?>
    <table class="wpcargo form-table">
        <?php do_action('wpc_before_shipper_details_metabox', $post->ID); ?>
        <tr>
            <th><label><?php esc_html_e('Address Type', 'wpcargo'); ?></label></th>
            <td>
                <select style="margin-left: 2%; width: 95%;" id="address_type" name="address_type"
                    onchange="address_fields_toggle(this)">
                    <option value=""><?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
                    <option>Residential Address</option>
                    <option>Bussiness Address</option>
                </select>
            </td>
        </tr>
        <!-- residential address fields -->
        <tr class="business residential">
            <th><label><?php esc_html_e('Contact Person', 'wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_shipper_name" name="wpcargo_shipper_name"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_name', true); ?>" size="25" /></td>
        </tr>
        <tr class="business residential">
            <th><label><?php esc_html_e('Phone Number','wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_shipper_phone" name="wpcargo_shipper_phone"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_phone', true); ?>" size="25" /></td>
        </tr>
        <!-- business address fields -->
        <tr class="business residential">
            <th><label><?php esc_html_e('City', 'wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_shipper_name" name="wpcargo_shipper_name"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_name', true); ?>" size="25" /></td>
        </tr>
        <tr class="business residential">
            <th><label><?php esc_html_e('Area', 'wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_shipper_name" name="wpcargo_shipper_name"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_name', true); ?>" size="25" /></td>
        </tr>
        <tr class="business residential">
            <th><label><?php esc_html_e('Street Name', 'wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_shipper_name" name="wpcargo_shipper_name"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_name', true); ?>" size="25" /></td>
        </tr>
        <tr class="business">
            <th><label><?php esc_html_e('Complex', 'wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_shipper_name" name="wpcargo_shipper_name"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_name', true); ?>" size="25" /></td>
        </tr>
        <tr class="business">
            <th><label><?php esc_html_e('Business Name', 'wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_shipper_name" name="wpcargo_shipper_name"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_name', true); ?>" size="25" /></td>
        </tr>
        <tr class="business">
            <th><label><?php esc_html_e('Shop No.','wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_shipper_phone" name="wpcargo_shipper_phone"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_phone', true); ?>" size="25" /></td>
        </tr>
        <tr class="residential">
            <th><label><?php esc_html_e('House No.', 'wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_shipper_address" name="wpcargo_shipper_address"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_address', true); ?>" size="25" /></td>
        </tr>
        <tr class="business">
            <th><label><?php esc_html_e('Office No.','wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_shipper_phone" name="wpcargo_shipper_phone"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_phone', true); ?>" size="25" /></td>
        </tr>
        <tr class="business residential">
            <th><label><?php esc_html_e('Email','wpcargo'); ?></label></th>
            <td><input type="email" class="notvalidate" class="notvalidate" id="wpcargo_shipper_email"
                    name="wpcargo_shipper_email"
                    value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_email', true); ?>" size="25" /></td>
        </tr>

        <?php do_action('wpc_after_shipper_details_metabox', $post->ID); ?>
    </table>
    <?php do_action('wpc_after_shipper_details_table', $post->ID); ?>
</div>