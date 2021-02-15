<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$shipment_id 					 = $shipment_detail->ID;
$wpcargo_shipper_address_type    = wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_address_type', true);

$after_hours = (!empty(wpcargo_get_postmeta($shipment_id, 'after_hours', true))) ? 'Yes' : '';
$collection_time_max = (!empty(wpcargo_get_postmeta($shipment_id, 'collection_time_max', true))) ? wpcargo_get_postmeta($shipment_id, 'collection_time_max', true) : '23:59';
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


<div id="print-shipment-info" class="wpcargo-row print-section">
    <div class="one-half first">
		<p id="print-receiver-header" class="header-title"><strong><?php echo apply_filters('result_receiver_address', esc_html__('Collection Address', 'wpcargo')); ?></strong></p>
		<div class="wpcargo-row" >
              <div class="one-half first"><label><?php esc_html_e('Address Type :', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo $wpcargo_shipper_address_type; ?></div>
              <?php if($wpcargo_shipper_address_type=="Bussiness Address") echo " <div class='one-half first'><label>Business Name :</label></div>
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
		<p id="print-shipper-header" class="header-title"><strong><?php echo apply_filters('result_shipper_address', esc_html__('Collection Details', 'wpcargo')); ?></strong></p>
		<div style="width: " class="wpcargo-row" id="print-shipment-info">
              <div class="one-half first"><label><?php esc_html_e('Service Type :', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'service_type', true); ?></div>
              <div class="one-half first"><label><?php esc_html_e('Collection Times :', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo ($after_hours=="Yes")? "Working Hrs(<b>08:00 - 16:30hrs</b>)<br> AND After Hours(<b>16:30 - ".wpcargo_get_postmeta($shipment_id, 'collection_time_max', true)."</b>" : "Working Hrs(<b>08:00 - 16:30hrs</b>)"; ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Collection Reference :', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'collection_reference', true); ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Collection Instructions:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'collection_instructions', true); ?></div>

              <?php if($tax_invoice=="Yes") echo "<div class='one-half first'><label>Client has Tax Invoice?</label></div>
                                                <div class='one-half'>".$tax_invoice."</div>";
                    if($receipt=="Yes") echo "<div class='one-half first'><label>Client has Cash Receipt?</label></div>
                                                <div class='one-half'>".$receipt."</div>";
                    echo "<div class='one-half first'><label> Note:</label></div>
                    <div class='one-half'>".$message."</div>";




              ?>
        </div>
	</div>     
</div>