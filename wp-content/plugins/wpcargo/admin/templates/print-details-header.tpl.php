<?php
$shipment_id	= $shipment_detail->ID;
$booking_reference	= wpcargo_get_postmeta($shipment_id, 'booking_reference' );
$shipment_status = wpcargo_get_postmeta($shipment_id, 'wpcargo_status' );
$url_barcode	= WPCARGO_PLUGIN_URL."/includes/barcode.php?codetype=Code128&size=60&text=" . $booking_reference . "";
?>

<?php
     $wpcargo_invoice = get_post_meta($shipment_id, 'wpcargo_invoice', true);
     $payment_history = get_post_meta($shipment_id, 'wpcargo_payment_history', true);
     $invoice = (is_array(unserialize($wpcargo_invoice))) ? (float)get_total_price($wpcargo_invoice) : 0;
     $amount_paid = 0;
     if(is_array(unserialize($payment_history))) {foreach(unserialize($payment_history) AS $key=>$values){
           $amount_paid+=(float)str_replace(",","",$values['amount']);
      } }
     $amount_due  = $invoice - $amount_paid;

?>

<style>
  .st{
    margin-left: 60px;
    text-align: left;
  }
 .st p{
    font-size:11px; margin:0px;  line-height: 2px;
 }
 .st p i{
    font-size:18px; line-height: 2px;
 }
 .st p .dashicons-yes{
     color:green;
 }
 .st p .dashicons-no{
     color:red;
 }

</style>
<div id="wpcargo-print-layout" style="overflow: hidden; background: #F0F0F0;">
	<div class="print-tn one-half first">
		<h2><?php echo $booking_reference; ?></h2>
		<img src="<?php echo $url_barcode; ?>" alt="<?php echo $booking_reference;?>" />

	</div>
	<div class="one-half">
      <div class="print-logo" style="width: 60%; float: right;">
        <p style="background: #9EFF9E; text-align: center; display: none; width: 250px;" id="msg"></p>
        <p><b style="font-size: 20px; font-weight: 900;">STATUS: </b><?php echo $shipment_status; ?> &nbsp;&nbsp;&nbsp;
        <?php  if($shipment_status == "Draft" || $shipment_status == "Complete") { ?>

        <?php } else { ?>
                 <a class="button button-primary" type="button" onclick="wpcargo_state_update()"><span class="dashicons dashicons-dashboard"></span> <?php echo apply_filters( 'wpcargo_state_update_label', esc_html__( 'Update State', 'wpcargo') ); ?></a> </p>
        <?php } ?>
        <div class="st"><?php
             $status_breakdown = status_breakdown($shipment_status);
             $wpcargo_shipments_update = maybe_unserialize( get_post_meta( $shipment_id, 'wpcargo_shipments_update', true ) );
             if(!empty($status_breakdown))
              foreach($status_breakdown as $sub_status){
                 echo (in_array_r($sub_status,$wpcargo_shipments_update)) ? '<p><i class="dashicons dashicons-yes"></i>'.$sub_status.'</p>' : '<p><i class="dashicons dashicons-no"></i>'.$sub_status.'</p>';
              }
             $amount_due  = shipment_amount_due($shipment_id);
           //state for finance clearance
           if($shipment_status == "Active")
              echo (is_array(unserialize($wpcargo_invoice)) && $amount_due==0)? '<p id="finance_cleared"><i class="dashicons dashicons-yes"></i>Finance Cleared</p>' : '<p><i class="dashicons dashicons-no"></i>Finance Cleared</p>';
        ?></div>
		<?php $options = get_option('wpcargo_option_settings');  ?>
		<img src="<?php echo $options['settings_shipment_ship_logo']; ?>">
	</div> </div>
</div>