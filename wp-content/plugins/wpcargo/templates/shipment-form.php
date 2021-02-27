<style>
#shipment-details .form-table,
#shipment-details .form-table td,
#shipment-details .form-table tr,
#shipment-details .form-table th {
    border: none;
}
</style>
<div id="shipment-details">
    <input type="hidden" id="route_abrs" name="route_abrs" value="">
    <h2><?php echo apply_filters('wpc_shipment_details_label', esc_html__('Shipment Details', 'wpcargo' ) ); ?></h2>
    <hr style="border: 1px solid black;" /><br>
    <div class="wpcargo form-table wpcargo-row">
        <?php do_action('wpc_before_shipment_details_table', $post->ID); ?>
        <div class="wpcargo-col-md-6">
            <th><label class="form-label"><?php esc_html_e('Origin Country:','wpcargo'); ?></label></th>
            <td>
                <?php $countries = wpc_get_countries_cities("GROUP BY country_name");
                    if( !empty($countries) ){ ?>
                <select id="org_1" name="wpcargo_origin_field" id="wpcargo_origin_field"
                    onchange="schedule_selector(this)">
                    <option value=""><?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
                    <?php foreach ( $countries as $country) { ?>
                    <option value="<?php echo trim($country->country_name); ?>"
                        <?php echo ( trim($country->country_name) == get_post_meta($post->ID, 'wpcargo_origin_field', true)) ? 'selected' : '' ; ?>>
                        <?php echo trim($country->country_name); ?></option>
                    <?php } ?>
                </select>
                <?php } ?>
                <?php if( empty( $countries ) ): ?>
                <span class="meta-box error">
                    <strong>
                        <?php esc_html__('No Selection setup, Please add selection', 'wpcargo'); ?>
                        <a
                            href="<?php echo admin_url().'/admin.php?page=wpcargo-settings'; ?>"><?php esc_html__('here.', 'wpcargo'); ?></a>
                    </strong>
                </span>
                <?php endif; ?>
            </td>
        </div>
        <div class="wpcargo-col-md-6">
            <th><label class="form-label"><?php esc_html_e('Destination Country:','wpcargo'); ?></label></th>
            <td> <?php
				   if( !empty($countries) ){ ?>
                <select id="dest_1" name="wpcargo_destination" onchange="schedule_selector(this)">
                    <option value=""><?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
                    <?php foreach ( $countries as $country) { ?>
                    <?php if($country->is_destination) { ?>
                    <option value="<?php echo trim($country->country_name); ?>"
                        <?php echo ( trim($country->country_name) == get_post_meta($post->ID, 'wpcargo_destination', true)) ? 'selected' : '' ; ?>>
                        <?php echo trim($country->country_name); ?></option>
                    <?php } } ?>
                </select>
                <?php } ?>
            </td>
        </div>
        <div class="wpcargo-col-md-6">
            <th><br><label class="form-label"> <?php esc_html_e('Origin City:','wpcargo'); ?></label></th>
            <td><br>
                <select id="org_1_1" name="wpcargo_origin_city_field" onchange="schedule_selector(this)">
                    <option data-value="" data-city_id="" data-moreinfo="" selected value="">
                        <?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
                    <?php	$cities = wpc_get_countries_cities("ORDER BY country_name");
						      foreach ( $cities as $city ) { ?>
                    <?php if($city->is_origin) { ?>
                    <option data-value="<?php echo trim($city->city_abr); ?>"
                        data-city_id="<?php echo trim($city->id); ?>"
                        data-moreinfo="<?php echo trim($city->has_deport).','.trim($city->has_collection).','.trim($city->has_delivery);?>"
                        value="<?php echo trim($city->city_name); ?>"
                        <?php echo ( trim($city->city_name) == get_post_meta($post->ID, 'wpcargo_origin_city_field', true)) ? 'selected' : '' ; ?>>
                        <?php echo trim($city->city_name); ?></option>
                    <?php } } ?>
                </select>
                <input name="org_1_1_other" style="width: 90%; display: none;" value=""
                    placeholder="Type city name here" />
            </td>
        </div>
        <div class="wpcargo-col-md-6">
            <th><br><label class="form-label"><?php esc_html_e('Destination City:','wpcargo'); ?></label></th>
            <td><br>
                <select id="dest_1_1" name="wpcargo_destination_city" onchange="schedule_selector(this)">
                    <option data-value="" data-city_id="" data-moreinfo="" selected value="">
                        <?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
                    <?php
                              $cities = wpc_get_countries_cities("ORDER BY country_name");
                              foreach ( $cities as $city ) { ?>
                    <?php if($city->is_destination) { ?>
                    <option value="<?php echo trim($city->city_name); ?>"
                        data-value="<?php echo trim($city->city_abr); ?>" data-city_id="<?php echo trim($city->id); ?>"
                        data-moreinfo="<?php echo trim($city->has_deport).','.trim($city->has_collection).','.trim($city->has_delivery);?>"
                        <?php echo ( trim($city->city_name) == get_post_meta($post->ID, 'wpcargo_destination_city', true)) ? 'selected' : '' ; ?>>
                        <?php echo trim($city->city_name); ?></option>
                    <?php } }  ?>
                </select>
                <input name="dest_1_1_other" style="width: 90%; display: none;" value=""
                    placeholder="Type city name here" />
            </td>
        </div>
        <div class="wpcargo-col-md-6">
            <th><br><label class="form-label" for="label">Mode of Transport:</label></th>
            <td><select id="transport_mode" required name="transport_mode">
                    <?php echo "<option value=''>-- Select One --</option>"; ?>
                    <option value='Road'
                        <?php echo (get_post_meta($post->ID, "transport_mode", true)=="Road") ? 'selected' : ''; ?>>Road
                        Freight</option>
                    <option value='Air'
                        <?php echo (get_post_meta($post->ID, "transport_mode", true)=="Air") ? 'selected' : ''; ?>>Air
                        Freight</option>
                    <option value='Ocean'
                        <?php echo (get_post_meta($post->ID, "transport_mode", true)=="Ocean") ? 'selected' : ''; ?>>
                        Ocean Freight</option>
                </select></td>
        </div>
        <div class="wpcargo-col-md-6">
            <th><br><label class="form-label" for="label">Select service:</label></th>
            <td><select id="service_type" name="service_type" onchange="collection_toggle(this)">
                    <option value=""><?php esc_html_e('Select one', 'wpcargo' ); ?></option>
                    <option value="Door to Depo"><?php esc_html_e('Door to Depo', 'wpcargo' ); ?></option>
                    <option value="Door to Door"><?php esc_html_e('Door to Door', 'wpcargo' ); ?></option>
                    <option value="Depo to Depo"><?php esc_html_e('Depo to Depo', 'wpcargo' ); ?></option>
                    <option value="Depo to Door"><?php esc_html_e('Depo to Door', 'wpcargo' ); ?></option>
                </select></td>
        </div>
        <div class="wpcargo-col-md-6" id="col_time"> <br>
            <i style="font-size: 11px; display: block;"><b>Note that collection fee will be charged for this
                    service</b></i>
            <th><label class="form-label" for="label">Collection Times: </label></th>
            <td>
                <div style="margin-left: 50px;">
                    <div><input type='checkbox' onclick="collection_hours(this)" disabled="disabled" checked="checked"
                            name="col_working_hours" style="width: 15px; height: 15px;" id="col_working_hours"><label
                            style="font: bolder;">&nbsp;Working Hours: <b>08:00 - 16:30hrs</b></label><br
                            style="line-height: 0px;"> </div>
                    <div><input type='checkbox' onclick="collection_hours(this)" name="col_after_hours"
                            style="width: 15px; height: 15px;" id="col_after_hours"><label style="font: bolder;"
                            id="col_after_hours_label">&nbsp;After Hours</label> </div>
                    <p class="wpcargo-col-md-5" 0 id="time_note"></p>
                </div>
            </td>
        </div>
        <div class="wpcargo-col-md-6" id="del_time"> <br>
            <i style="font-size: 11px; display: block;"><b>Note that delivery fee will be charged for this
                    service</b></i>
            <th><label class="form-label" for="label">Delivery Times: </label></th>
            <td>
                <div style="margin-left: 50px;">
                    <div><input type='checkbox' onclick="collection_hours(this)" disabled="disabled" checked="checked"
                            name="del_working_hours" style="width: 15px; height: 15px;" id="del_working_hours"><label
                            style="font: bolder;">&nbsp;Working Hours: <b>08:00 - 16:30hrs</b></label><br
                            style="line-height: 0px;"> </div>
                    <div><input type='checkbox' onclick="collection_hours(this)" name="del_after_hours"
                            style="width: 15px; height: 15px;" id="del_after_hours"><label style="font: bolder;"
                            id="del_after_hours_label">&nbsp;After Hours</label> </div>
                    <p class="wpcargo-col-md-5" 0 id="time_note"></p>
                </div>
            </td>
        </div>



        <?php do_action('wpc_after_shipment_details_metabox', $post->ID); ?>
    </div>
    <?php do_action('wpc_after_shipment_details_table', $post->ID); ?>
</div>