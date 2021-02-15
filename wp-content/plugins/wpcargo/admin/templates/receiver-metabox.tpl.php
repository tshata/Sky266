
<?php

     $whatsapp_1  = (!empty(get_post_meta($post->ID, 'whatsapp_1', true))) ? 'checked' : '';
     $whatsapp_2  = (!empty(get_post_meta($post->ID, 'whatsapp_2', true))) ? 'checked' : '';
?>

<div id="receiver-details" class="one-half first">
  <h1><?php echo apply_filters('wpc_receiver_details_label',esc_html__('1. Shipper Details', 'wpcargo' ) ); ?></h1>
  <?php do_action('wpc_before_receiver_details_table', $post->ID); ?>
  <table class="wpcargo form-table" >
    <?php do_action('wpc_before_receiver_details_metabox', $post->ID); ?>
    <tr>
      <th><label>
          <?php esc_html_e('Company Name', 'wpcargo'); ?>
        </label></th>
      <td><input type="text" id="wpcargo_receiver_company" name="wpcargo_receiver_company" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_company', true); ?>"size="25" placeholder="if applicable" /></td>
    </tr>
    <tr>
      <th><label>
          <?php esc_html_e('Shipper\'s Firstname', 'wpcargo'); ?>
        </label></th>
      <td><input type="text" id="wpcargo_receiver_fname" name="wpcargo_receiver_fname" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_fname', true); ?>"size="25" /></td>
    </tr>
    <tr>
      <th><label>
          <?php esc_html_e('Shipper\'s Surname', 'wpcargo'); ?>
        </label></th>
      <td><input type="text" id="wpcargo_receiver_sname" name="wpcargo_receiver_sname" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_sname', true); ?>"size="25" /></td>
    </tr>
    <tr>
      <th><label>
          <?php esc_html_e('Main Phone Number', 'wpcargo' ); ?>
        </label></th>
      <td><input style="width: 70%;" type="text" required id="wpcargo_receiver_phone_1" name="wpcargo_receiver_phone_1" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_phone_1', true); ?>"size="25" />
          <input style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" <?php echo $whatsapp_1; ?> type='checkbox' name="whatsapp_1" id="whatsapp_1"><label style="font: bolder;"><img style="width: 20px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>" title="Has whatsapp" alt="Whatsapp"></label>
      </td>
    </tr>
    <tr>
      <th><label>
          <?php esc_html_e('Alternative Phone', 'wpcargo' ); ?>
        </label></th>
      <td><input style="width: 70%;" type="text" id="wpcargo_receiver_phone_2" name="wpcargo_receiver_phone_2" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_phone_2', true); ?>"size="25" />
          <input style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" <?php echo $whatsapp_2; ?> type='checkbox' name="whatsapp_2" id="whatsapp_2"><label style="font: bolder;"><img style="width: 20px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>" title="Has whatsapp" alt="Whatsapp"></label>
      </td>
    </tr>
    <tr>
      <th><label>
          <?php esc_html_e('Email', 'wpcargo'); ?>
        </label></th>
      <td><input type="email" id="wpcargo_receiver_email" name="wpcargo_receiver_email" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_email', true); ?>"size="25" /></td>
    </tr>
    <?php do_action('wpc_after_receiver_details_metabox', $post->ID); ?>
  </table>
  <?php do_action('wpc_after_receiver_details_table', $post->ID); ?>
</div>

