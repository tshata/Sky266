
   <div id="shipper-details">
      <h2><?php echo apply_filters('wpc_shipper_details_label',esc_html__('Collection Address', 'wpcargo' ) ); ?><span style="font-size: .6em;font-style: italic;margin-left:.7em;"> (Address where parcel is collected )</span></h2><hr style="border: 1px solid black;"/><br>
        <?php do_action('wpc_before_shipper_details_table', $post->ID); ?>
        <div class="wpcargo form-table wpcargo-row">
          <?php do_action('wpc_before_shipper_details_metabox', $post->ID); ?>
          <!-- residential address fields -->
          <div class="wpcargo-col-md-6">
               <div style="margin-bottom: 1.5em;">
                  <label class="form-label" class="form-label"><?php esc_html_e('Address Type', 'wpcargo'); ?></label>
                  <select style="width: 100%;" id="wpcargo_shipper_address_type" name="wpcargo_shipper_address_type" onchange="address_fields_toggle(this)">
                  		<option value=""><?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
                  		<option>Residential Address</option>
                  		<option>Business Address</option>
                  </select>
               </div>
              <div class="business" style="margin-bottom: 1.5em;">
                  <div><label class="form-label"><?php esc_html_e('Business Name:', 'wpcargo'); ?></label></div>
                  <div><input style="width: 100%;" type="text" class="notvalidate" id="wpcargo_shipper_bussiness" name="wpcargo_shipper_bussiness" value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_bussiness', true); ?>"/></div>
              </div>
              <div class="residential" style="margin-bottom: 1.5em;">
                  <div><label class="form-label"><?php esc_html_e('Complex/Building/Estate Name:', 'wpcargo'); ?></label></div>
                  <div><input style="width: 100%;" type="text" class="notvalidate" id="wpcargo_shipper_estate" name="wpcargo_shipper_estate" value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_estate', true); ?>" size="25" /></div>
              </div>
              <div class="business residential" style="margin-bottom: 1.5em;">
                  <div><label class="form-label"><?php esc_html_e('Full Address:', 'wpcargo'); ?></label></div>
                  <div><textarea rows="4" style="width:100%;" class="notvalidate" id="wpcargo_shipper_address" name="wpcargo_shipper_address" placeholder="type full address details here" ><?php echo get_post_meta($post->ID, 'wpcargo_shipper_address', true); ?></textarea></div>
              </div>
              <div class="business residential" style="margin-bottom: 1.5em;">
                  <div><label class="form-label"><?php esc_html_e('Collection Reference', 'wpcargo'); ?> </label></div>
                  <input style="width: 100%;" type="text" class="notvalidate" placeholder="Collection Reference" id="collection_reference" name="collection_reference" value="<?php echo get_post_meta($post->ID, 'collection_reference', true); ?>"size="25" />
              </div>
              <div class="business residential" style="margin-bottom: 1.5em;">
                  <div><label class="form-label">Collection Instructions:  </label></div>
                  <textarea rows="4" name="collection_instructions" id="collection_instructions" style="width:100%;" placeholder="type all instructions here"></textarea>
              </div>
          </div>
          <div class="wpcargo-col-md-6">
              <div class="business residential" style="margin-bottom: 1.5em;">
                  <div><label class="form-label"><?php esc_html_e('Contact Person', 'wpcargo'); ?></label></div>
                  <div><input style="width: 100%;" type="text" class="notvalidate" id="wpcargo_shipper_name" name="wpcargo_shipper_name" value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_name', true); ?>" size="25" /></div>
              </div>
              <div class="business residential" style="margin-bottom: 1.5em;">
                  <div><label class="form-label"><?php esc_html_e('Phone Number', 'wpcargo'); ?><span style="font-size: .87em;font-style: italic;"> (tick if number has WhatsApp)</span></label></div>
                  <input type="text" class="notvalidate wpcargo-col-md-7" id="wpcargo_shipper_phone_1" name="wpcargo_shipper_phone_1" value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_phone_1', true); ?>" size="25" />
                  <input class="wpcargo-col-md-5" style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" type='checkbox' name="wpcargo_shipper_whatsapp_1" id="wpcargo_shipper_whatsapp_1"><label style="font: bolder;"><img style="width: 30px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>" title="Has whatsapp" alt="Whatsapp"></label>
              </div>
              <div class="business residential" style="margin-bottom: 1.5em;">
                  <div><label class="form-label"><?php esc_html_e('Alternative Phone Number', 'wpcargo'); ?></label></div>
                  <input type="text" class="notvalidate wpcargo-col-md-7" id="wpcargo_shipper_phone_2" name="wpcargo_shipper_phone_2" value="<?php echo get_post_meta($post->ID, 'wpcargo_shipper_phone_2', true); ?>" size="25" />
                  <input class="wpcargo-col-md-5" style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" type='checkbox' name="wpcargo_shipper_whatsapp_2" id="wpcargo_shipper_whatsapp_2"><label style="font: bolder;"><img style="width: 30px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>" title="Has whatsapp" alt="Whatsapp"></label>
              </div>

          </div>

            <?php do_action('wpc_after_shipper_details_metabox', $post->ID); ?>
        </div>
        <?php do_action('wpc_after_shipper_details_table', $post->ID); ?>
   </div>

