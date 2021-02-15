<?php
    global $wpdb;
	$shipment_id 		= $shipment_detail->ID;
    $wpcargo_price_estimates	= get_post_meta( $shipment_id, 'wpcargo_price_estimates', true );
	$shipper_name		= wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_name' );
	$shipper_address	= wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_address' );
	$shipper_phone		= wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_phone' );
	$shipper_email		= wpcargo_get_postmeta($shipment_id, 'wpcargo_shipper_email' );
	$receiver_name		= wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_name' );
	$receiver_address	= wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_address' );
	$receiver_phone		= wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_phone' );
	$receiver_email		= wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_email' );
    $whatsapp_1  = (!empty(wpcargo_get_postmeta($shipment_id, 'whatsapp_1', true))) ? 'checked' : '';
    $whatsapp_2  = (!empty(wpcargo_get_postmeta($shipment_id, 'whatsapp_2', true))) ? 'checked' : '';
    $shipment_trip_id  = wpcargo_get_postmeta($shipment_id, 'shipment_trip_id', true);
    $trips = $wpdb->get_results( "SELECT * FROM trips WHERE id = '$shipment_trip_id'");
    foreach($trips as $trip){  $trip_date  = date_format(date_create($trip->trip_date),"d-F-Y");   }
?>
<style>
 .first label {
   font-weight: bolder;
 }
 .one-half .first {
   width: 40%;
 }
 .one-third{
     padding-left: 0px;
 }

 .link{
   cursor: pointer;
 }

</style>
<div id="print-shipper-info" style="overflow: hidden;">
  <div class="four-fifths first">
    <div class="one-half first">
    	<p id="print-receiver-header" class="header-title"><strong><?php echo apply_filters('result_receiver_address', esc_html__('Shipper Details', 'wpcargo')); ?></strong></p>
		<div>
		  <?php if(wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_company', true)!="") { ?>
              <div class="one-half first"><label><?php esc_html_e('Company Name:', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_company', true); ?></div>
          <?php } ?>
              <div class="one-half first"><label><?php esc_html_e('Client\'s Firstname:', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_fname', true); ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Client\'s Surname:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_sname', true); ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Main Phone Number:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_phone_1', true); ?><img style="width: 30px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>" title="Has whatsapp" alt="Whatsapp"></div>
              <div class="one-half first"><label> <?php esc_html_e('Alternative Phone:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_phone_2', true); ?><img style="width: 30px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>" title="Has whatsapp" alt="Whatsapp"></div>
              <div class="one-half first"><label> <?php esc_html_e('Email:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_email', true); ?></div>
        </div>
	</div>
	<div class="one-half">
    	<p id="print-shipper-header" class="header-title"><strong><?php echo apply_filters('result_shipper_address', esc_html__('Shipment Details', 'wpcargo')); ?></strong></p>
		<div id="print-shipment-info">
              <div class="one-half first"><label><?php esc_html_e('Type of Package:', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php if(wpcargo_get_postmeta($shipment_id, 'item_type', true)=="meds") echo "Medication"; else if(wpcargo_get_postmeta($shipment_id, 'item_type', true)=="docs") echo "Documents"; else echo "General Package"; ?></div>
              <div class="one-half first"><label><?php esc_html_e('Origin Country:', 'wpcargo'); ?></label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_origin_field', true); ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Origin City:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_origin_city_field', true); ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Destination Country:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_destination', true); ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Destination City:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php echo wpcargo_get_postmeta($shipment_id, 'wpcargo_destination_city', true); ?></div>
              <div class="one-half first"><label> <?php esc_html_e('Booking Method:', 'wpcargo'); ?> </label></div>
              <div class="one-half"><?php
              $author = (wpcargo_get_postmeta($shipment_id, 'booking_type', true)=="Online")? wpcargo_get_postmeta( $shipment_id, 'wpcargo_receiver_fname', true)." ".wpcargo_get_postmeta( $shipment_id, 'wpcargo_receiver_fname', true)
                         : get_the_author_meta( 'display_name' , get_post_field ('post_author', $shipment_id) );
              echo wpcargo_get_postmeta($shipment_id, 'booking_type', true).", ".$author; ?></div>
        </div>
	  </div>
	</div>
	<div class="one-fifth" style="background: #BDBDBD; padding-bottom: 5px; border-top: 12px solid white;">
		<p id="print-shipper-header" class="header-title" >&nbsp;<strong><?php echo apply_filters('result_shipper_address', esc_html__('Accounts / Finances', 'wpcargo')); ?></strong>&nbsp;&nbsp;
        </p>
       <div id="print-shipment-info">
        <table style="font-size: 12px; width:100%;">
           <?php
                 $old_prices = get_post_meta($shipment_id, 'wpcargo_price_estimates-old', true);
                 $current_prices = get_post_meta($shipment_id, 'wpcargo_price_estimates', true);
                 $wpcargo_invoice = get_post_meta($shipment_id, 'wpcargo_invoice', true);
                 $payment_history = get_post_meta($shipment_id, 'wpcargo_payment_history', true);
                 $first = (is_array(unserialize($old_prices))) ? (float)get_total_price($old_prices) : 0;
                 $final = (is_array(unserialize($current_prices))) ? (float)get_total_price($current_prices) : 0;
                 $invoice = (is_array(unserialize($wpcargo_invoice))) ? (float)get_total_price($wpcargo_invoice) : 0;
                 $amount_paid = 0;
                 if(is_array(unserialize($payment_history))) {
                     foreach(unserialize($payment_history) AS $key=>$values){
                         if($values["approval"] == "0" ){
                           $amount_paid = 0;
                         }
                         else {
                           $amount_paid+=(float)str_replace(",","",$values['amount']);
                         }

                  } }
                 $amount_due  = $invoice - $amount_paid;

           ?>
           <!--tr id="quote1"><td><label>Initial Quotation : </label></td><td>M<?php echo number_format((float)$first, 2, '.', ','); ?>&nbsp;<a class="link" id="old_quote" type="button" onclick="quote_more(this,<?php echo $shipment_id;?>,'old_wpcargo_price_estimates')"> >>view>></a></td></tr>
           <tr><td><label>Final Quotation : </label></td><td>M <span id="quote2"><?php echo number_format((float)$final, 2, '.', ','); ?></span>&nbsp;<a class="link" id="new_quote" type="button" onclick="quote_more(this,<?php echo $shipment_id;?>,'wpcargo_price_estimates')"> >>view>></a></td></tr>
           <tr><td><label>Invoiced Amount : </label></td><td>M <span id="invoice"><?php echo number_format((float)$invoice, 2, '.', ','); ?></span>&nbsp;<?php if(!empty($wpcargo_invoice)){?> <a class="link" id="invoice" type="button" onclick="quote_more(this,<?php echo $shipment_id;?>,'wpcargo_invoice')"> >>view>> </a><?php } else {?> <a class="link" id="new_invoice" type="button" onclick="quote_more(this,<?php echo $shipment_id;?>,'wpcargo_invoice')"> Create Invoice </a><?php }?></td></tr>
           <tr><td><label>Payments : </label></td><td>M <span id="amount_paid"><?php echo number_format((float)$amount_paid, 2, '.', ','); ?></span>&nbsp;<a class="link" type="button" onclick="payment_more(<?php echo $shipment_id;?>)"> >>view>></a></td></tr>
           <tr><td><br><hr/></td><td><br><hr/></td></tr-->
           <span hidden id="quote2_inf"><?php echo number_format((float)$final, 2, '.', ','); ?></span>
           <span hidden id="invoice_inf"><?php echo number_format((float)$invoice, 2, '.', ','); ?></span>
           <span hidden id="amount_paid_inf"><?php echo number_format((float)$amount_paid, 2, '.', ','); ?></span>
           <tr><td colspan="3"><a class="button button-primary" style="width: 90%; text-align: center; margin: 5px 0;" id="quotes" type="button" data-quote1="<?php echo $first; ?>" data-quote2="<?php echo $final; ?>" data-shipment_id = "<?php echo $shipment_id;?>" onclick="quotations(this)">Quotations</a> </td></tr>
           <tr><td colspan="3"><a class="button button-primary" style="width: 90%; text-align: center; margin: 5px 0;" id="<?php echo (!empty($wpcargo_invoice))? 'invoice':'new_invoice'; ?>" type="button" onclick="quote_more(this,<?php echo $shipment_id;?>,'wpcargo_invoice')">Invoice</a> </td></tr>
           <tr><td colspan="3"><a class="button button-primary" style="width: 90%; text-align: center; margin: 5px 0;" onclick="payment_more(<?php echo $shipment_id;?>)">Payments</a> </td></tr>
           <tr><td colspan="3"><a class="button button-primary" style="width: 90%; text-align: center; margin: 5px 0;" onclick="statement(<?php echo $shipment_id;?>)">Statement</a> </td></tr>

           <tr><td colspan="3"><br></td></tr><tr><td colspan="3"><br></td></tr><tr><td colspan="3"><br></td></tr>

           <tr><td><label style="font-size: 15px;">Amount Due : </label></td><td><b  style="font-size: 15px; font-weight: 700"> M <span id="amount_due"><?php echo number_format((float)$amount_due, 2, '.', ','); ?></span></b></td></tr>
         </table>
       </div>
	</div>
</div>