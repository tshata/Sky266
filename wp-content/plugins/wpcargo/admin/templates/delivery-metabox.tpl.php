
<?php

     $wpcargo_delivery_whatsapp_1  = (!empty(get_post_meta($post->ID, 'wpcargo_delivery_whatsapp_1', true))) ? 'checked' : '';
     $wpcargo_delivery_whatsapp_2  = (!empty(get_post_meta($post->ID, 'wpcargo_delivery_whatsapp_2', true))) ? 'checked' : '';
     $del_after_hours = (!empty(get_post_meta($post->ID, 'del_after_hours', true))) ? 'checked' : '';
     $delivery_time_max = (!empty(get_post_meta($post->ID, 'delivery_time_max', true))) ? get_post_meta($post->ID, 'delivery_time_max', true) : '23:59';
?>

<div id="shipper-details" class="one-half">
    <h1><?php echo apply_filters('wpc_delivery_details_label',esc_html__('3(b). Delivery Details', 'wpcargo' ) ); ?></h1><hr/>
    <?php do_action('wpc_before_delivery_details_table', $post->ID); ?>
    <table class="wpcargo form-table">
        <?php do_action('wpc_before_delivery_details_metabox', $post->ID); ?>

		<tr>
			<th><br><label class="form-label"><?php esc_html_e('Delivery Date:','wpcargo'); ?></label></th>
			<td><br>
					<select id="delivery_schedule_id" name="delivery_schedule_id" class="" onchange="check_if_late(this)">
                        <?php  if(!empty(get_post_meta($post->ID, 'delivery_schedule_id', true))) {
                                  global $wpdb;
                                  echo '<option value="">-- Select One --</option>';
                                  $current_datetime = date('Y-m-d H:i:s');
                                  $schedule_id = get_post_meta($post->ID, 'delivery_schedule_id', true);
                                  $selected_schedule = $wpdb->get_results("SELECT * FROM collection_schedules WHERE id = '$schedule_id'");
                                  $dest_city = $selected_schedule[0]->schedule_city;
                                  $city = $wpdb->get_results("SELECT * FROM countries_cities WHERE id='$dest_city'"); 
                                  $option_label = ($city[0]->country_depot == 1)? $city[0]->city_name." Daily" : date_format(date_create($selected_schedule[0]->schedule_date),'d-M-Y')."(".$selected_schedule[0]->schedule_name.")";
                                  if($selected_schedule[0]->final_cut_off <= $current_datetime)
                                        echo "<option data-value='".$selected_schedule[0]->late_cut_off."' data-schedule_date='".$selected_schedule[0]->schedule_date."' value='".$selected_schedule[0]->id."' selected >".$option_label."</option>";
                                  $schedules = $wpdb->get_results("SELECT * FROM collection_schedules WHERE schedule_city = '$dest_city' AND status!='Closed' AND status!='Terminated' ORDER BY schedule_date ASC ");
                                  foreach($schedules as $schedule){
                                          $final_cut_off = $schedule->final_cut_off;
                                          $option_label = ($city[0]->country_depot == 1)? $city[0]->city_name." Daily" : date_format(date_create($schedule->schedule_date),'d-M-Y')."(".$schedule->schedule_name.")";
                                          if($final_cut_off > $current_datetime || $schedule_id==$schedule->id){
                                            $show = ($schedule_id==$schedule->id)? "selected":"";
                                            echo "<option data-value='".$schedule->late_cut_off."' data-schedule_date='".$schedule->schedule_date."' value='".$schedule->id."' ".$show.">".$option_label."</option>";
                                            $c++;
                                            if($c==4)  break;
                                        }
                                  }

                                }
                                else{
                                    echo '<option value="">-- Select One --</option>';
                                }
                        ?>
					</select>
                    <input style="display: none;" type="checkbox" id="is_late_booking" name="is_late_booking" <?php if(wpcargo_get_postmeta($post->ID, 'is_late_booking', true)=="on") echo "checked"; ?> >
              </td>
		</tr>
         <tr id="del_time">
            <th> <label class="form-label" for="label">Delivery Times: </label></th>
            <td><table style="width:100%;">
                  <tr><td><p><input type='checkbox' onclick="delivery_hours(this)" disabled="disabled" checked="checked" name="del_working_hours" style="width: 15px; height: 15px;"  id="del_working_hours" >&nbsp;&nbsp;Working Hrs&nbsp;(<b>08:00 - 16:30hrs</b>)</p></td></tr>
                  <tr><td><p><input type='checkbox' onclick="delivery_hours(this)" name="del_after_hours" <?php echo $del_after_hours; ?> style="width: 15px; height: 15px;" id="del_after_hours">&nbsp;&nbsp;After Hours&nbsp;(<b>16:30 - </b><input type='time' name='delivery_time_max' id='delivery_time_max' value='<?php echo $delivery_time_max; ?>' style='width: 100px;'> hrs)</p></td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <th><label class="form-label" class="form-label"><?php esc_html_e('Address Type', 'wpcargo'); ?></label></th>
            <td><select id="wpcargo_delivery_address_type" name="wpcargo_delivery_address_type" onchange="address_fields_toggle(this)">
            <?php echo (!empty(get_post_meta($post->ID, 'wpcargo_delivery_address_type', true))) ? "<option>".get_post_meta($post->ID, 'wpcargo_delivery_address_type', true)."</option>"
                           : "<option value=''>-- Select One --</option>";
                     $services = array('Residential Address','Business Address');
                     foreach ($services as $service){
                           if($service != get_post_meta($post->ID, 'wpcargo_delivery_address_type', true)) echo "<option>".$service."</option>";
                     }
                ?>
            </select></td>
        </tr>
        <tr class="business">
            <th><label class="form-label"><?php esc_html_e('Business Name:', 'wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_delivery_bussiness" name="wpcargo_delivery_bussiness" value="<?php echo get_post_meta($post->ID, 'wpcargo_delivery_bussiness', true); ?>"/></td>
        </tr>
        <tr class="residential">
            <th><label class="form-label"><?php esc_html_e('Complex/Building/Estate Name:', 'wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_delivery_estate" name="wpcargo_delivery_estate" value="<?php echo get_post_meta($post->ID, 'wpcargo_delivery_estate', true); ?>" size="25" /></td>
        </tr>
        <tr class="business residential">
            <th><label class="form-label"><?php esc_html_e('Full Address:', 'wpcargo'); ?></label></th>
            <td><textarea  rows="4" style="width:90%;" class="notvalidate" id="wpcargo_delivery_address" name="wpcargo_delivery_address" placeholder="type full address details here" ><?php echo get_post_meta($post->ID, 'wpcargo_delivery_address', true); ?></textarea></td>
        </tr>
        <tr class="business residential">
            <th><label class="form-label"><?php esc_html_e('Contact Person', 'wpcargo'); ?></label></th>
            <td><input type="text" class="notvalidate" id="wpcargo_delivery_name" name="wpcargo_delivery_name" value="<?php echo get_post_meta($post->ID, 'wpcargo_delivery_name', true); ?>" size="25" /></td>
        </tr>
        <tr class="business residential">
            <th><label class="form-label"><?php esc_html_e('Phone Number', 'wpcargo'); ?></label></th>
            <td><input style="width: 70%;" type="text" id="wpcargo_delivery_phone_1" name="wpcargo_delivery_phone_1" value="<?php echo get_post_meta($post->ID, 'wpcargo_delivery_phone_1', true); ?>" size="25" />
                <input class="wpcargo-col-md-5" style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" <?php echo $wpcargo_delivery_whatsapp_1; ?> type='checkbox' name="wpcargo_delivery_whatsapp_1" id="wpcargo_delivery_whatsapp_1"><label style="font: bolder;"><img style="width: 20px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>" title="Has whatsapp" alt="Whatsapp"></label></td>
        </tr>
        <tr class="business residential">
            <th><label class="form-label"><?php esc_html_e('Alternative Phone', 'wpcargo'); ?></label></th>
            <td><input style="width: 70%;" type="text" id="wpcargo_delivery_phone_2" name="wpcargo_delivery_phone_2" value="<?php echo get_post_meta($post->ID, 'wpcargo_delivery_phone_2', true); ?>" size="25" />
                <input class="wpcargo-col-md-5" style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" <?php echo $wpcargo_delivery_whatsapp_2; ?> type='checkbox' name="wpcargo_delivery_whatsapp_2" id="wpcargo_delivery_whatsapp_2"><label style="font: bolder;"><img style="width: 20px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>" title="Has whatsapp" alt="Whatsapp"></label></td>
        </tr>    <tr class="business residential">
            <th><label class="form-label"> <?php esc_html_e('Delivery Reference', 'wpcargo'); ?> </label></th>
            <td><input type="text" class="notvalidate" name="delivery_reference" id="delivery_reference" placeholder="Delivery Reference" value="<?php echo get_post_meta($post->ID, 'delivery_reference', true); ?>"> </td>
         </tr>
        <tr class="business residential">
            <th><label class="form-label" for="label">Delivery Instructions </label></th>
            <td><textarea  rows="4" style="width:90%;" name="delivery_instructions" id="delivery_instructions" style="overflow: hidden; word-wrap: break-word;" placeholder="type all instructions here"><?php echo get_post_meta($post->ID, 'delivery_instructions', true); ?></textarea> </td>
        </tr>

        <?php do_action('wpc_after_delivery_details_metabox', $post->ID); ?>
    </table>
    <?php do_action('wpc_after_delivery_details_table', $post->ID); ?>
</div> <!-- delivery-details -->


