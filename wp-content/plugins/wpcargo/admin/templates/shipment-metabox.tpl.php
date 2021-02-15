
<div id="shipment-details" class="one-half">
  <?php $screen = get_current_screen();  ?>
  <input type="hidden" id="screen" value="<?php echo $screen->action;?>">
  <input type='hidden' style="width: 500px;" name="service_items" id="service_items" value="<?php echo get_post_meta($post->ID, 'service_items', true);?>">
  <input type="hidden" id="route_abrs" name="route_abrs" value="<?php echo get_post_meta($post->ID, 'route_abrs', true);?>">
  <input type="hidden" id="booking_reference" name="booking_reference" value="<?php echo get_post_meta($post->ID, 'booking_reference', true);?>">
  <input type="hidden" id="status" name="status" value="<?php echo get_post_meta($post->ID, 'wpcargo_status', true); ?>">
	<h1><?php echo apply_filters('wpc_shipment_details_label', esc_html__('2. Shipment Details', 'wpcargo' ) ); ?></h1>
	<?php                            
    do_action('wpc_before_shipment_details_table', $post->ID); ?>
	<table class="wpcargo form-table">
		<?php do_action('wpc_before_shipment_details_metabox', $post->ID); ?>
        <!--tr>
			<th><label class="form-label"><?php esc_html_e('Type of Package:','wpcargo'); ?></label></th>
			<td>
  			   <select id="item_type" name="item_type">
				  <option value="kg" <?php echo ( "kg" == get_post_meta($post->ID, 'item_type', true)) ? 'selected' : '' ; ?>>General Package</option>
                  <option value="docs" <?php echo ( "docs" == get_post_meta($post->ID, 'item_type', true)) ? 'selected' : '' ; ?>>Documents</option>
			   </select>
			</td>
        </tr-->

        <tr>
			<th><label class="form-label"><?php esc_html_e('Origin:','wpcargo'); ?></label></th>
			<td>
  			   <?php $countries = wpc_get_countries_cities("GROUP BY country_name");
				     if( !empty($countries) ){ ?>
					<select id="org_1" name="wpcargo_origin_field" onchange="schedule_selector(this)" style="width: 45%;">
						<option value=""><?php esc_html_e('-- Select Country --', 'wpcargo' ); ?></option>
						<?php foreach ( $countries as $country) { ?>
                            <?php if($country->is_origin) { ?>
							<option value="<?php echo trim($country->country_name); ?>" <?php echo ( trim($country->country_name) == get_post_meta($post->ID, 'wpcargo_origin_field', true)) ? 'selected' : '' ; ?> ><?php echo trim($country->country_name); ?></option>
						<?php } } ?>
					</select>
				<?php } ?>
                <select id="org_1_1" name="wpcargo_origin_city_field" onchange="schedule_selector(this)" style="width: 45%;">
						<option data-value="" data-moreinfo="" selected value=""><?php esc_html_e('-- Select City --', 'wpcargo' ); ?></option>
						<?php if(!empty(get_post_meta($post->ID, 'wpcargo_origin_city_field', true))){
                              $cities = wpc_get_countries_cities("ORDER BY country_name",get_post_meta($post->ID, 'wpcargo_origin_field', true));
						      foreach ( $cities as $city ) { ?>
                          <?php if($city->is_origin) { ?>
							<option data-value="<?php echo trim($city->city_abr); ?>" data-city_id="<?php echo trim($city->id); ?>"  data-moreinfo="<?php echo trim($city->has_deport).','.trim($city->has_collection).','.trim($city->has_delivery);?>" value="<?php echo trim($city->city_name); ?>" <?php echo ( trim($city->city_name) == get_post_meta($post->ID, 'wpcargo_origin_city_field', true)) ? 'selected' : '' ; ?> ><?php echo trim($city->city_name); ?></option>
						<?php } } } ?>
				 </select>
                 <input name="org_1_1_other" style="width: 90%; display: none;" value="" placeholder="Type city name here"/>
			</td>
        </tr>
		<tr>
			<th><label class="form-label"><?php esc_html_e('Destination Country:','wpcargo'); ?></label></th>
			<td>
  			   <?php
				     if( !empty($countries) ){ ?>
				 <select id="dest_1" name="wpcargo_destination" onchange="schedule_selector(this)" style="width: 45%;">
      				<option value=""><?php esc_html_e('-- Select Country --', 'wpcargo' ); ?></option>
      				<?php foreach ( $countries as $country) { ?>
                                <?php if($country->is_destination) { ?>
      					<option value="<?php echo trim($country->country_name); ?>" <?php echo ( trim($country->country_name) == get_post_meta($post->ID, 'wpcargo_destination', true)) ? 'selected' : '' ; ?> ><?php echo trim($country->country_name); ?></option>
      				<?php } } ?>
				  </select>
				<?php } ?>
                 <select id="dest_1_1" name="wpcargo_destination_city" class="" onchange="schedule_selector(this)" style="width: 45%;">
						<option data-value="" data-moreinfo="" selected value=""><?php esc_html_e('-- Select City --', 'wpcargo' ); ?></option>
						<?php if(!empty(get_post_meta($post->ID, 'wpcargo_destination_city', true))){
                              $cities = wpc_get_countries_cities("ORDER BY country_name",get_post_meta($post->ID, 'wpcargo_destination', true));
                              foreach ( $cities as $city ) { ?>
                          <?php if($city->is_destination) { ?>
							<option value="<?php echo trim($city->city_name); ?>" data-value="<?php echo trim($city->city_abr); ?>" data-city_id="<?php echo trim($city->id); ?>" data-moreinfo="<?php echo trim($city->has_deport).','.trim($city->has_collection).','.trim($city->has_delivery);?>" <?php echo ( trim($city->city_name) == get_post_meta($post->ID, 'wpcargo_destination_city', true)) ? 'selected' : '' ; ?> ><?php echo trim($city->city_name); ?></option>
						<?php } } } ?>
				 </select>
                 <input name="dest_1_1_other" style="width: 90%; display: none;" value="" placeholder="Type city name here"/>
			</td>
		</tr>
        <tr>
            <th><label class="form-label" for="label">Mode of Transport:</label></th>
            <td><select id="transport_mode" required name="transport_mode">
               <?php echo "<option value=''>-- Select One --</option>"; ?>
                           <option value='Road' <?php echo (get_post_meta($post->ID, "transport_mode", true)=="Road") ? 'selected' : ''; ?>>Road Freight</option>
                           <option value='Air' <?php echo (get_post_meta($post->ID, "transport_mode", true)=="Air") ? 'selected' : ''; ?>>Air Freight</option>
                           <option value='Ocean' <?php echo (get_post_meta($post->ID, "transport_mode", true)=="Ocean") ? 'selected' : ''; ?>>Ocean Freight</option>
        	</select></td>
        </tr>
        <tr>
            <th><label class="form-label" for="label">Select service:</label></th>
            <td><select id="service_type" required name="service_type" onchange="collection_toggle(this)">
                 <option value=''>-- Select One --</option>
                 <option value='Door to Depo' <?php echo (get_post_meta($post->ID, "service_type", true)=="Door to Depo") ? 'selected' : ''; ?>>Door to Depo</option>
                 <option value='Door to Door' <?php echo (get_post_meta($post->ID, "service_type", true)=="Door to Door") ? 'selected' : ''; ?>>Door to Door</option>
                 <option value='Depo to Depo' <?php echo (get_post_meta($post->ID, "service_type", true)=="Depo to Depo") ? 'selected' : ''; ?>>Depo to Depo</option>
                 <option value='Depo to Door' <?php echo (get_post_meta($post->ID, "service_type", true)=="Depo to Door") ? 'selected' : ''; ?>>Depo to Door</option>

        	</select></td>
        </tr>
        <tr>
            <th><label class="form-label" for="label">Goods Description </label></th>
            <td><textarea rows="4" name="goods_description" required id="goods_description" style="overflow: hidden; word-wrap: break-word;" placeholder="type description and quantities of goods/items here"><?php echo get_post_meta($post->ID, 'goods_description', true); ?></textarea> </td>
        </tr>
		<?php do_action('wpc_after_shipment_details_metabox', $post->ID); ?>
	</table>
	<?php
    do_action('wpc_after_shipment_details_table', $post->ID); ?>
</div>

