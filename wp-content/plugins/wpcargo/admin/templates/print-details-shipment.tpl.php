<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$shipment_id 					 = $shipment_detail->ID;
$wpcargo_shipper_address_type    = wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_address_type', true);
$wpcargo_delivery_address_type    = wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_address_type', true);
$wpcargo_shipments_update = get_post_meta( $shipment_id, 'wpcargo_shipments_update', true );
$collection_schedule_id = wpcargo_get_postmeta($shipment_id, 'collection_schedule_id', true);
$delivery_schedule_id = wpcargo_get_postmeta($shipment_id, 'delivery_schedule_id', true);

$col_after_hours = (!empty(wpcargo_get_postmeta($shipment_id, 'col_after_hours', true))) ? 'Yes' : '';
$collection_time_max = (!empty(wpcargo_get_postmeta($shipment_id, 'collection_time_max', true))) ? wpcargo_get_postmeta($shipment_id, 'collection_time_max', true) : '23:59';
$del_after_hours = (!empty(wpcargo_get_postmeta($shipment_id, 'del_after_hours', true))) ? 'Yes' : '';
$delivery_time_max = (!empty(wpcargo_get_postmeta($shipment_id, 'delivery_time_max', true))) ? wpcargo_get_postmeta($shipment_id, 'delivery_time_max', true) : '23:59';
$tax_invoice = (!empty(wpcargo_get_postmeta($shipment_id, 'tax_invoice', true))) ? 'Yes' : '';
$receipt  = (!empty(wpcargo_get_postmeta($shipment_id, 'receipt', true))) ? 'Yes' : '';
$message  = (!empty(wpcargo_get_postmeta($shipment_id, 'upload_tax_invoice', true))) ? 'Client will upload tax invoice.' : '';
$message .= (!empty(wpcargo_get_postmeta($shipment_id, 'bring_tax_invoice_box', true))) ? 'Client will bring tax invoice to office.' : '';
$message .= (!empty(wpcargo_get_postmeta($shipment_id, 'sent_tax_invoice_box', true))) ? 'Client will sent tax invoice to us.' : '';
$message .= (!empty(wpcargo_get_postmeta($shipment_id, 'collect_tax_invoice_box', true))) ? 'We have to collect tax invoice with parcels.' : '';
$message .= (!empty(wpcargo_get_postmeta($shipment_id, 'upload_receipt', true))) ? 'Client will upload receipt.' : '';
$message .= (!empty(wpcargo_get_postmeta($shipment_id, 'bring_receipt_box', true))) ? 'Client will bring receipt to office.' : '';
$message .= (!empty(wpcargo_get_postmeta($shipment_id, 'sent_receipt_box', true))) ? 'Client will sent receipt to us.' : '';
$message .= (!empty(wpcargo_get_postmeta($shipment_id, 'collect_receipt_box', true))) ? 'We have to collect receipt with parcels.' : '';

?>
<style>
  #collection_date_block, #delivery_date_block{
      width: 100%;
      padding-bottom: 15px;
  }
  #collection_date_block span, #delivery_date_block span{
      margin-left: 15px;
  }
  #collection_date_block b, #delivery_date_block b{
      font-weight:700;
  }

</style>

<div id="print-shipment-info" class="wpcargo-row print-section">
    <div class="one-half first" >
		<p id="print-receiver-header" class="header-title"><strong><?php echo apply_filters('result_receiver_address', esc_html__('Collection Details', 'wpcargo')); ?></strong></p>
        <p id="collection_date_block">
            <b> <?php esc_html_e('Collection Date : ', 'wpcargo'); ?> </b>
                    <?php $selected_schedule = $wpdb->get_results("SELECT * FROM collection_schedules WHERE id = '$collection_schedule_id'");
                    $schedule_date = $selected_schedule[0]->schedule_date;
                    $schedule_city = $selected_schedule[0]->schedule_city;
                    $city = $wpdb->get_results("SELECT * FROM countries_cities WHERE id='$schedule_city'");
                    $option_label = ($city[0]->country_depot == 1)? $city[0]->city_name." Daily" : date_format(date_create($schedule_date),'d-F-Y');
                    echo ($schedule_date=="")? "<span id='assigned' style='color:red;'>Unassigned</span>" : "<span id='assigned'>".$option_label."</span>";
                    if( strpos($wpcargo_shipments_update, "Collection Successful") == false && strpos($wpcargo_shipments_update, "Delivery Successful") == false)
                      echo ($schedule_date=="")? "<span id='unassigned'><a class='link' type='button' id='collection_schedule_id' onclick='form_assign_schedule(this,".$shipment_id.")'> >>assign date>></a></span>" : "<span id='unassigned'><a class='link' type='button' id='collection_schedule_id' onclick='form_assign_schedule(this,".$shipment_id.")'> >>edit date>></a></span>";
                ?>
        <br></p>
        <p>
            <?php echo ($wpcargo_shipper_address_type=="")? 'No Collection Address':''; ?>
        </p>
		<div style="<?php echo ($wpcargo_shipper_address_type=="")? 'display:none;':''; ?>" >
              <div class="one-half first"><label><?php esc_html_e('Address Type :', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo $wpcargo_shipper_address_type; ?></div>
              <?php if($wpcargo_shipper_address_type=="Business Address" || $wpcargo_shipper_address_type=="Bussiness Address") echo " <div class='one-half first'><label>Business Name :</label></div>
                           <div class='one-half'>".wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_bussiness', true)."</div>" ?>
              <?php if($wpcargo_shipper_address_type=="Residential Address") echo " <div class='one-half first'><label>Complex/Building/Estate Name :</label></div>
                           <div class='one-half'>".wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_estate', true)."</div> " ?>

              <div class="one-half first"><label> <?php esc_html_e('Full Address. :', 'wpcargo'); ?> </label></div>
              <div class="one-half">
              <?php
                 echo wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_address', true);
              /*if(wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_address', true)!="")  {
                   echo wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_address', true);
               } else {
                   echo 'Shop/Office No.: '.wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_shop', true).
                         '<br>House No: '.get_post_meta($post->ID, 'wpcargo_shipper_house', true).
                         '<br>Complex/Building/Mall Name: '.get_post_meta($post->ID, 'wpcargo_shipper_complex', true).
                         '<br>Complex/Building/Estate Name: '.get_post_meta($post->ID, 'wpcargo_shipper_estate', true).
                         '<br>Street No.: '.get_post_meta($post->ID, 'wpcargo_shipper_street_no', true).
                         '<br>Street Name: '.get_post_meta($post->ID, 'wpcargo_shipper_street', true).
                         '<br>Area/Suburb: '.get_post_meta($post->ID, 'wpcargo_shipper_area', true).
                         '<br>City: '.get_post_meta($post->ID, 'wpcargo_shipper_city', true);
                 }  */
              ?>
              </div>
              <div class="one-half first"><label> <?php esc_html_e('Contact Person :', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_name', true); ?></div>
              <?php if(wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_phone_1', true)!="") {
                         echo "<div class='one-half first'><label>Phone Number :</label></div>";
                         echo "<div class='one-half'>".wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_phone_1', true);
                         if(!empty(wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_whatsapp_1', true)))  echo "<img style='width: 30px;' src='".WPCARGO_PLUGIN_URL.'assets/images/whatsapp.png'."' title='Has whatsapp' alt='Whatsapp'>";
                         echo "</div>"; } ?>
              <?php if(wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_phone_2', true)!="") {
                         echo "<div class='one-half first'><label>Alternative Phone :</label></div>";
                         echo "<div class='one-half'>".wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_phone_2', true);
                         if(wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_whatsapp_2', true)!="")  echo "<img style='width: 30px;' src='".WPCARGO_PLUGIN_URL.'assets/images/whatsapp.png'."' title='Has whatsapp' alt='Whatsapp'>";
                         echo "</div>"; } ?>
              <div class="one-half first"><label> <?php esc_html_e('Email :', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_email', true); ?></div>
        </div>
	</div>

    <div class="one-half">
		<p id="print-receiver-header" class="header-title"><strong><?php echo apply_filters('result_delivery_address', esc_html__('Delivery Details', 'wpcargo')); ?></strong></p>
        <p id="delivery_date_block">
            <b> <?php esc_html_e('Delivery Date : ', 'wpcargo'); ?> </b>
                    <?php $selected_schedule = $wpdb->get_results("SELECT * FROM collection_schedules WHERE id = '$delivery_schedule_id'");
                    $schedule_date = $selected_schedule[0]->schedule_date;
                    $schedule_city = $selected_schedule[0]->schedule_city;
                    $city = $wpdb->get_results("SELECT * FROM countries_cities WHERE id='$schedule_city'");
                    $option_label = ($city[0]->country_depot == 1)? $city[0]->city_name." Daily" : date_format(date_create($schedule_date),'d-F-Y');
                    echo ($schedule_date=="")? "<span id='assigned' style='color:red;'>Unassigned</span>" : "<span id='assigned'>".$option_label."</span>";
                    if( strpos($wpcargo_shipments_update, "Collection Successful") == false && strpos($wpcargo_shipments_update, "Delivery Successful") == false)
                      echo ($schedule_date=="")? "<span id='unassigned'><a class='link' type='button' id='delivery_schedule_id' onclick='form_assign_schedule(this,".$shipment_id.")'> >>assign date>></a></span>" : "<span id='unassigned'><a class='link' type='button' id='delivery_schedule_id' onclick='form_assign_schedule(this,".$shipment_id.")'> >>edit date>></a></span>";
                ?>
        <br></p>
        <p>
            <?php echo ($wpcargo_delivery_address_type=="")? 'No Delivery Address':''; ?>
        </p>
		<div style="<?php echo ($wpcargo_delivery_address_type=="")? 'display:none;':''; ?>" >
              <div class="one-half first"><label><?php esc_html_e('Address Type :', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo $wpcargo_delivery_address_type; ?></div>
              <?php if($wpcargo_delivery_address_type=="Business Address" ||$wpcargo_delivery_address_type=="Bussiness Address") echo " <div class='one-half first'><label>Business Name :</label></div>
                           <div class='one-half'>".wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_bussiness', true)."</div>" ?>
              <?php if($wpcargo_delivery_address_type=="Residential Address") echo " <div class='one-half first'><label>Complex/Building/Estate Name :</label></div>
                           <div class='one-half'>".wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_estate', true)."</div> " ?>

              <div class="one-half first"><label> <?php esc_html_e('Full Address. :', 'wpcargo'); ?> </label></div>
              <div class="one-half">
              <?php
                 echo wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_address', true);
              ?>
              </div>
              <div class="one-half first"><label> <?php esc_html_e('Contact Person :', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_name', true); ?></div>
              <?php if(wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_phone_1', true)!="") {
                         echo "<div class='one-half first'><label>Phone Number :</label></div>";
                         echo "<div class='one-half'>".wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_phone_1', true);
                         if(!empty(wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_whatsapp_1', true)))  echo "<img style='width: 30px;' src='".WPCARGO_PLUGIN_URL.'assets/images/whatsapp.png'."' title='Has whatsapp' alt='Whatsapp'>";
                         echo "</div>"; } ?>
              <?php if(wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_phone_2', true)!="") {
                         echo "<div class='one-half first'><label>Alternative Phone :</label></div>";
                         echo "<div class='one-half'>".wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_phone_2', true);
                         if(wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_whatsapp_2', true)!="")  echo "<img style='width: 30px;' src='".WPCARGO_PLUGIN_URL.'assets/images/whatsapp.png'."' title='Has whatsapp' alt='Whatsapp'>";
                         echo "</div>"; } ?>
              <div class="one-half first"><label> <?php esc_html_e('Email :', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_delivery_email', true); ?></div>
        </div>
	</div>
	<div class="one-half first">
		<p id="print-shipper-header" class="header-title"><strong><?php echo apply_filters('result_shipper_address', esc_html__('Other Details', 'wpcargo')); ?></strong></p>
		<div id="print-shipment-info">
              <div class="one-half first"><label><?php esc_html_e('Service Type :', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'service_type', true); ?></div>
              <div class="one-half first"><label><?php esc_html_e('Collection Times :', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo ($col_after_hours=="Yes")? "Working Hrs(<b>08:00 - 16:30hrs</b>)<br> AND After Hours(<b>16:30 - ".wpcargo_get_postmeta($shipment_id, 'collection_time_max', true)."</b>" : "Working Hrs(<b>08:00 - 16:30hrs</b>)"; ?></div>
              <div class="one-half first"><label><?php esc_html_e('Delivery Times :', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo ($del_after_hours=="Yes")? "Working Hrs(<b>08:00 - 16:30hrs</b>)<br> AND After Hours(<b>16:30 - ".wpcargo_get_postmeta($shipment_id, 'delivery_time_max', true)."</b>" : "Working Hrs(<b>08:00 - 16:30hrs</b>)"; ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Collection Reference :', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'collection_reference', true); ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Collection Instructions:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'collection_instructions', true); ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Goods Description:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'goods_description', true); ?></div>
        </div>
	</div>
    <div class="one-half">
        <p id="print-shipper-header" class="header-title"><strong><?php echo apply_filters('result_shipper_address', esc_html__('Border Taxes & Clearance', 'wpcargo')); ?></strong></p>
          <div id="print-shipment-info">
              <div class='one-half first'><label>Client has Tax Invoice?</label></div>
                  <div class='one-half'><?php echo ($tax_invoice=="Yes") ? "Yes ":"No "; ?></div>
              <div class='one-half first'><label>Client has Cash Receipt?</label></div>
                  <div class='one-half'><?php echo ($receipt=="Yes")? "Yes " : "No "; ?> </div>
              <div class='one-half first'><label> Note:</label></div>
                  <div class='one-half'><?php echo $message; ?></div>
         </div>
    </div>
</div>