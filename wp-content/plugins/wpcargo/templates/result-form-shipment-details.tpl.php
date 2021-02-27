<?php
	$shipment_origin  					= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_origin_field' );
	$shipment_origin_city  					= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_origin_city_field' );
	$wpcargo_status   					= get_post_meta( $shipment->ID, 'wpcargo_status', true);
	$shipment_destination  				= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_destination' );
	$shipment_destination_city			= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_destination_city' );
	$type_of_shipment  					= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_type_of_shipment' );
	$shipment_weight  					= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_weight' );
	$shipment_courier  					= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_courier' );
	$shipment_carrier_ref_number  		= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_carrier_ref_number' );
	$shipment_product  					= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_product' );
	$shipment_qty  						= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_qty' );
	$shipment_payment_mode  			= wpcargo_get_postmeta( $shipment->ID, 'payment_wpcargo_mode_field' );
	$shipment_total_freight  			= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_total_freight' );
	$shipment_mode  					= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_mode_field' );
	$departure_time  			        = wpcargo_get_postmeta( $shipment->ID, 'wpcargo_departure_time_picker' );
	$trip	                            = wpcargo_get_postmeta( $shipment->ID, 'wpcargo_trip');
	$shipment_comments  				= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_comments' );
	$shipment_packages  				= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_packages' );
	$shipment_carrier  					= wpcargo_get_postmeta( $shipment->ID, 'wpcargo_carrier_field' );
	$pickup_date  				        = wpcargo_get_postmeta( $shipment->ID, 'wpcargo_pickup_date_picker', 'date' );
	$pickup_time  				        = wpcargo_get_postmeta( $shipment->ID, 'wpcargo_pickup_time_picker' );
    $collection = (wpcargo_get_postmeta( $shipment->ID, 'wpcargo_collection' )==1) ? "Package to be collected at:" : "Package to be delivered to our warehouse";
    $clearance = (wpcargo_get_postmeta( $shipment->ID, 'wpcargo_clearance' )==1) ? "Package to be cleared at the border" : "No clearance to be done";
    $collection_address="";
    if(wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_address_type')=="Bussiness Address")
             $collection_address = wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_city').", ".wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_area')
             .", ".wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_street').", ".wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_comlex')
             .", ".wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_bussiness').", Shop No.".wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_shop')
             .", Office No.".wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_office');
    if(wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_address_type')=="Residential Address")
             $collection_address = wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_city').", ".wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_area')
             .", <br>".wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_street').", ".wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_comlex')
             .", <br> House No.".wpcargo_get_postmeta( $shipment->ID, 'wpcargo_shipper_house');

    if(wpcargo_get_postmeta( $shipment->ID, 'after_hours')==1 ) $collection_time = "16:30 - ".wpcargo_get_postmeta( $shipment->ID, 'collection_time_max');
    if(wpcargo_get_postmeta( $shipment->ID, 'working_hours')==1 ) $collection_time = "08:00 - 16:30hrs";

?>

<div id="shipment-info" class="wpcargo-row detail-section" style="margin-bottom:40px;">
    <div class="wpcargo-col-md-12">
        <p id="shipment-information-header" class="header-title">
            <strong><?php echo apply_filters('result_shipment_information', esc_html__('Shipment Information', 'wpcargo')); ?></strong>
        </p>
    </div>
    <div class="wpcargo-col-md-4">
        <p class="wpcargo-label">
            <?php apply_filters( 'wpc_multiple_package_header', esc_html_e( 'Clearance:', 'wpcargo' ) ); ?></p>
        <p class="wpcargo-label-info" id="label_info_clearance" style="font-size: 13px; "><?php echo $clearance; ?></p>
    </div>
    <div class="wpcargo-col-md-4">
        <p class="wpcargo-label">
            <?php apply_filters( 'wpc_multiple_package_header', esc_html_e( 'Collection:', 'wpcargo' ) ); ?></p>
        <p class="wpcargo-label-info">
            <?php echo $collection; ?><br>
            <?php echo $collection_address; ?></p>
    </div>
    <div class="wpcargo-col-md-4">
        <p class="wpcargo-label"><?php esc_html_e('Trip:', 'wpcargo'); ?></p>
        <p class="wpcargo-label-info"><?php echo $shipment_origin." (".$shipment_origin_city.")"; ?><br>
            <?php echo $shipment_destination." (".$shipment_destination_city.")"; ?><br>
            <?php echo $trip; ?></p>
    </div>
    <div class="wpcargo-col-md-4">
        <p class="wpcargo-label"><?php esc_html_e('Documents:', 'wpcargo'); ?> </p>
        <p class="wpcargo-label-info">
            <?php echo "Documents To collect"; ?>
            <?php echo "Uploaded Documents"; ?></p>
    </div>
    <div class="wpcargo-col-md-4">
        <p class="wpcargo-label"><?php esc_html_e('Best Collection Time:', 'wpcargo'); ?> </p>
        <p class="wpcargo-label-info"><?php echo $collection_time; ?></p>
    </div>
    <div class="wpcargo-col-md-4">
        <p class="wpcargo-label"><?php esc_html_e('Total Freight Cost:', 'wpcargo'); ?> </p>
        <p class="wpcargo-label-info">M<?php echo $shipment_total_freight; ?></p>
    </div>
</div>


<!--div id="shipment-info" class="wpcargo-row detail-section">
    <div class="wpcargo-col-md-12">
    <p id="shipment-information-header" class="header-title"><strong><?php echo apply_filters('result_shipment_information', esc_html__('Shipment Information', 'wpcargo')); ?></strong></p></div>
	<div class="wpcargo-col-md-4">
    	<p class="wpcargo-label"><?php esc_html_e('Origin:', 'wpcargo') . ''; ?></p>
        <p class="wpcargo-label-info"><?php echo $shipment_origin.", ".$shipment_origin_city; ?></p>
    </div>
    <div class="wpcargo-col-md-4">
    	<p class="wpcargo-label"><?php  esc_html_e('Destination:', 'wpcargo'); ?></p>
        <p class="wpcargo-label-info"><?php echo $shipment_destination.", ".$shipment_destination_city; ?></p>
    </div>
    <div class="wpcargo-col-md-4">
    	<p class="wpcargo-label"><?php esc_html_e('Total Freight Cost:', 'wpcargo'); ?> </p>
        <p class="wpcargo-label-info">M<?php echo $shipment_total_freight; ?></p>
    </div>
    <div class="wpcargo-col-md-4">
        <p class="wpcargo-label"><?php apply_filters( 'wpc_multiple_package_header', esc_html_e( 'Collection and Clearance:', 'wpcargo' ) ); ?></p>
            <i><b><p class="wpcargo-label-info" id="label_info_collection" style="font-size: 13px;  margin-bottom: 0px;"><?php echo $collection; ?></p>
            <p class="wpcargo-label-info" id="label_info_clearance" style="font-size: 13px; "><?php echo $clearance; ?></p></b></i>
    </div>
    <div class="wpcargo-col-md-4">
    	<p class="wpcargo-label"><?php esc_html_e('Comments:', 'wpcargo'); ?> </p>
        <p class="wpcargo-label-info"><?php echo $shipment_comments; ?></p>
    </div>
    <div class="wpcargo-col-md-4">
    	<p class="wpcargo-label"><?php esc_html_e('Trip:', 'wpcargo'); ?></p>
        <p class="wpcargo-label-info"><?php echo $trip; ?></p>
    </div>

</div-->