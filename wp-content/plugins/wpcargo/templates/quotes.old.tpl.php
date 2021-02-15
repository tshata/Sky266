
<?php


 global $shipment, $wpcargo;
 $current_user = wp_get_current_user();
 ob_start();

 //wp_nonce_field( 'wpc_metabox_action', 'wpc_metabox_nonce' );
 //wp_nonce_field( 'wpc_mp_inner_custom_box', 'wpc_mp_inner_custom_box_nonce' );


	function save_metabox( $post_id ) {


      $excluded_meta_keys = array(
									'wpc_metabox_nonce',
									'save',
									'_wpnonce',
									'_wp_http_referer',
									'user_ID',
									'action',
									'originalaction',
									'post_author',
									'post_type',
									'original_post_status',
									'referredby',
									'_wp_original_http_referer',
									'post_ID',
									'meta-box-order-nonce',
									'closedpostboxesnonce',
									'post_title',
									'hidden_post_status',
									'post_status',
									'hidden_post_password',
									'hidden_post_visibility',
									'visibility',
									'post_password',
									'original_publish',
									'original_publish',
									'status_date',
									'status_time',
									'status_location',
									'status_remarks',
									'wpcargo_status',
									'wpcargo_shipments_update',
                                    'trip_1',
                                    'trip_2'
								);
        //save all fields
	   	foreach( $_POST as $key => $value ) {
		   	if( in_array( $key, $excluded_meta_keys ) ) {
		  	   continue;
		 	}
			if( is_array( $value ) ) {
				$meta_value  = maybe_serialize( $value );
			} else {
				$meta_value  = sanitize_text_field( $value );
			}

		  	update_post_meta($post_id, $key, $meta_value);

		}
       //collection and clearing
       $collection = (!empty($_POST["collection"]))? "1" : "0";
       update_post_meta($post_id, 'wpcargo_collection', $collection );
       $clearance = (!empty($_POST["clearance"]))? "1" : "0";
       update_post_meta($post_id, 'wpcargo_clearance', $clearance );
       update_post_meta( $post_id, 'wpcargo_mode_field', 'Land Freight' );
       $wpcargo_price_estimates = trim( sanitize_text_field( $_POST['wpcargo_price_estimates'] ) );
       $wpcargo_comments = trim( sanitize_text_field( $_POST['wpcargo_comments'] ) );
       update_post_meta( $post_id, 'wpcargo_comments', $wpcargo_comments );

       //now set history and status for shipment
	    $wpcargo_status 	= "Pending";
		$status_location 	= "";
		$status_time 		=  date('H:i');
		$status_remarks 	=  "New order placed";
		$status_date 		= date('Y-m-d');
		$apply_to_shipment 	= true;
		$wpcargo_shipments_update = maybe_unserialize( get_post_meta( $post_id, 'wpcargo_shipments_update', true ) );
		// Make sure that it is set.
		$new_history = array(
			'date' => $status_date,
			'time' => $status_time,
			'location' => $status_location,
			'updated-name' => $current_user->display_name,
			'updated-by' => $current_user->ID,
			'remarks'	=> $status_remarks,
			'status'    => $wpcargo_status
		);
		if( $wpcargo_status ){
			update_post_meta($post_id, 'wpcargo_status', $wpcargo_status );
		}
		if( !empty( $wpcargo_shipments_update ) ){
			if( $wpcargo_status ){
				array_push($wpcargo_shipments_update, $new_history);
			}
			update_post_meta($post_id, 'wpcargo_shipments_update', maybe_serialize( $wpcargo_shipments_update ) );
		}else{
			if( !wp_is_post_revision( $post_id ) ){
				if( $wpcargo_status ){
					update_post_meta($post_id, 'wpcargo_shipments_update', maybe_serialize( array( $new_history ) ) );
				}
			}
		}
      //end of status saving

		$output = ob_get_clean();
		echo $output;

	}
  /*
** Bulk and Quick Save function
*/
function shipment_save( $post_id ) {
	global $wpcargo;
    $post_title = sanitize_text_field( $_POST["post_name"] );
	$post_name = strtolower($post_title);
    $new_post = array(
          'post_title' => $post_title,
          'post_content' => '',
          'post_status' => 'publish',
          'comment_status' => 'closed',
          'ping_status' => 'closed',
          'post_name' => $post_name,
          'post_parent' => '0',
          'post_type' => 'wpcargo_shipment',

    );

    $post_id = wp_insert_post($new_post);    //save shipment

    save_metabox( $post_id );

    //update_post_meta( $post_id, 'allocated_driver', '4' ); //assign to driver

    update_post_meta( $post_id, 'booking_type', 'Online' ); //booking_type
    update_post_meta( $post_id, 'booking_reference', sanitize_text_field( $_POST["post_name"] ) ); //booking reference


    /*
	if ( isset( $_REQUEST['wpcargo_agent'] ) && $_REQUEST['wpcargo_agent'] != '' ) {
	    $wpcargo_agent  = $_REQUEST['wpcargo_agent'];
		update_post_meta( $post_id, 'agent_fields', $wpcargo_agent );
	}    */
}

	// if the submit button is clicked, send the email
   	if ( isset( $_POST['wpc_metabox_nonce'] ) && $_POST['wpc_metabox_nonce']=="Pay Later" ) {
           shipment_save( $post_id ); //saving shipment
           $shipment_number = sanitize_text_field( $_POST["post_name"] );
           require_once( WPCARGO_PLUGIN_PATH.'templates/pay-later.php' );
  	}
    else if(isset( $_POST['wpc_metabox_nonce'] ) && $_POST['wpc_metabox_nonce']=="Pay Now"){
           shipment_save( $post_id ); //saving shipment
           wpcargo_include_template( 'pay-now', $shipment );
    }
else {
 //wp_nonce_field( 'wpc_mp_inner_custom_box', 'wpc_mp_inner_custom_box_nonce');
      ?>
<form id="regForm" class="shipping-form" method="post">
 <div>
<!-- One "tab" for each step in the form: -->
<div class="tab" id="titlewrap">
   <input name="post_name" hidden="hidden" id="title" value="<?php echo $wpcargo->create_shipment_number(); ?>" >
   <?php  wpcargo_include_template( 'shipment-form', $shipment );  ?>
</div>
<div class="tab" id="package_tab">
    <?php require_once( WPCARGO_PLUGIN_PATH.'admin/templates/package-metabox.tpl.php' );  ?>
</div>
<div class="tab" id="collection_tab">
    <h2><?php echo apply_filters('wpc_shipment_details_label', esc_html__('Collection Details', 'wpcargo' ) ); ?></h2><hr style="border: 1px solid black;"/><br>
     <div class="c_boxs" style="margin-bottom: 10px;">
            <label class="form-label" for="label">Select service:</label>&nbsp;&nbsp;&nbsp;&nbsp;
        	<select id="service_type" style="width: 200px;" name="service_type" onchange="colletion_toggle(this)">
        		<option value=""><?php esc_html_e('Select one', 'wpcargo' ); ?></option>
        		<option value="Door to Depo"><?php esc_html_e('Door to Depo', 'wpcargo' ); ?></option>
        		<option value="Door to Door"><?php esc_html_e('Door to Door', 'wpcargo' ); ?></option>
        		<option value="Depo to Depo"><?php esc_html_e('Depo to Depo', 'wpcargo' ); ?></option>
        		<option value="Depo to Door"><?php esc_html_e('Depo to Door', 'wpcargo' ); ?></option>
        	</select>
     </div>
     <div style="margin-bottom: 20px; margin-left: 50px; display: none;" id="col_time">
          <i style="font-size: 11px; display: block;"><b>Note that collection fee will be charged for this service</b></i>
           <label class="form-label" for="label">Collection Times: </label>&nbsp;&nbsp;&nbsp;
           <div style="margin-left: 50px;">
              <div><input type='checkbox' onclick="collection_hours(this)" disabled="disabled" checked="checked" name="working_hours" style="width: 15px; height: 15px;"  id="working_hours" ><label style="font: bolder;">&nbsp;Working Hours: <b>08:00 - 16:30hrs</b></label><br style="line-height: 0px;"> </div>
              <div><input type='checkbox' onclick="collection_hours(this)" name="after_hours" style="width: 15px; height: 15px;" id="after_hours"><label style="font: bolder;" id="after_hours_label">&nbsp;After Hours</label> </div>
               <p class="wpcargo-col-md-5" 0 id="time_note"></p>
           </div>
     </div>

     <input type='checkbox' hidden="hidden" style="width: 15px; height: 15px;" name="clearance" id="clearance" data-group='clearance'> <!-- Yes -->
     <input type='checkbox' hidden="hidden" style="width: 15px; height: 15px;" name="noclearance" id="noclearance" data-group='clearance'> <!-- No -->

</div>
<div class="tab" id="price-estimates">
      <h2><?php echo apply_filters('wpc_receiver_details_label',esc_html__('Price Estimates', 'wpcargo' ) ); ?></h2><hr style="border: 1px solid black;"/><br>
      <div id="price_tables" class="wpcargo-row">
      </div>
      <div class="wpcargo-row">
          <label class="wpcargo-col-md-6"><center><input id="trip_1" name="trip_1" style="width: 15px; height: 15px;" type="checkbox" onclick="trip_select(this)" data-group='trip_select'>&nbsp;Select Trip1</center></label>
          <label class="wpcargo-col-md-6"><center><input id="trip_2" name="trip_2" style="width: 15px; height: 15px;" type="checkbox" onclick="trip_select(this)" data-group='trip_select'>&nbsp;Select Trip2</center></label>
      </div><br><br>
      <input type="hidden" id="wpcargo_price_estimates" name="wpcargo_price_estimates">
      <input type="hidden" id="shipment_trip_id" name="shipment_trip_id">
</div>
<div class="tab" id="receiver-details">
     <h2><?php echo apply_filters('wpc_receiver_details_label',esc_html__('Your Details', 'wpcargo' ) ); ?></h2><hr style="border: 1px solid black;"/><br>
      <?php do_action('wpc_before_receiver_details_table', $post->ID); ?>
        <?php do_action('wpc_before_receiver_details_metabox', $post->ID); ?>
         <div class="wpcargo form-table wpcargo-row">
            <div class="wpcargo-col-md-6">
              <div><label class="form-label"> <?php esc_html_e('Your Firstname', 'wpcargo'); ?> </label></div>
              <div><input type="text" style="width: 100%;" placeholder="First Name" id="wpcargo_receiver_fname" name="wpcargo_receiver_fname" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_fname', true); ?>" /></div>
            </div>
            <div class="wpcargo-col-md-6">
              <div><label class="form-label"> <?php esc_html_e('Your Surname', 'wpcargo'); ?> </label></div>
              <div><input type="text" style="width: 100%;" placeholder="Last Name" id="wpcargo_receiver_sname" name="wpcargo_receiver_sname" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_sname', true); ?>" /> </div>
            </div>
            <div class="wpcargo-col-md-6"><br>
              <div><label class="form-label"> <?php esc_html_e('Your Main Phone Number', 'wpcargo'); ?> </label></div>
              <input type="text" class="wpcargo-col-md-7" id="wpcargo_receiver_phone_1" name="wpcargo_receiver_phone_1" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_phone_1', true); ?>"/>
              <input class="wpcargo-col-md-5" style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" type='checkbox' name="whatsapp_1" id="whatsapp_1"><label style="font: bolder;">has<img style="width: 30px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>" title="Has whatsapp" alt="Whatsapp"></label>
            </div>
            <div class="wpcargo-col-md-6"><br>
              <div><label class="form-label"> <?php esc_html_e('Your Alternative Phone Number', 'wpcargo'); ?> </label></div>
              <input type="text" class="notvalidate wpcargo-col-md-7" id="wpcargo_receiver_phone_2" name="wpcargo_receiver_phone_2" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_phone_2', true); ?>"/>
              <input class="wpcargo-col-md-5" style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" type='checkbox' name="whatsapp_2" id="whatsapp_2"><label style="font: bolder;">has<img style="width: 30px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>" title="Has whatsapp" alt="Whatsapp"></label>
            </div>
           <div class="wpcargo-col-md-12"><br>
              <div><label class="form-label"> <?php esc_html_e('Email', 'wpcargo'); ?> </label></div>
              <div><input type="email" style="width: 100%;" class="notvalidate" id="wpcargo_receiver_email" name="wpcargo_receiver_email" value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_email', true); ?>" /> </div>
           </div>
    </div>
      <?php do_action('wpc_after_receiver_details_metabox', $post->ID); ?>
      <?php do_action('wpc_after_receiver_details_table', $post->ID); ?>
</div>
<div class="tab" id="collection-details">
      <?php wpcargo_include_template( 'collection-details', $shipment );
   ?>
</div>
<div class="tab" id="documents">
      <h2><?php echo apply_filters('wpc_receiver_details_label',esc_html__('Documents', 'wpcargo' ) ); ?></h2><hr style="border: 1px solid black;"/><br>
     <div>
            <i class="wpcargo-col-md-12 form-label" style="display: block;"><b>1. Do you have Tax Invoice?</b></i>
            <div style=" margin-left: 60px;">
               <input style="width: 15px; height: 15px;" type='checkbox' onclick="documents_switch(this)" name="tax_invoice" id="tax_invoice" data-group='tax_invoice'><label style="font: bolder;"> Yes</label> <span>&nbsp;&nbsp;</span>
               <input style="width: 15px; height: 15px;" type='checkbox' onclick="documents_switch(this)" name="no_tax_invoice" id="no_tax_invoice" data-group='tax_invoice'> <label style="font: bolder;">No</label>
               <p id="tax_invoice_more" style="width: 50%;"> </p>
           </div>
           <div id="doc2" style="display: none;">
              <i class="wpcargo-col-md-12 form-label" style="display: block;"><b>2. Do you have Cash Receipt?</b></i>
              <div style=" margin-left: 60px;">
                 <input style="width: 15px; height: 15px;" type='checkbox' onclick="documents_switch(this)" name="receipt" id="receipt" data-group='receipt'><label style="font: bolder;"> Yes</label><span>&nbsp;&nbsp;</span>
                 <input style="width: 15px; height: 15px;" type='checkbox' onclick="documents_switch(this)" name="no_receipt" id="no_receipt" data-group='receipt'> <label style="font: bolder;">No</label>
                 <p id="receipt_more" style="width: 50%;"></p>
              </div>
           </div>
     </div>
</div>
<div class="tab" id="collection-instructions">
      <h2><?php echo apply_filters('wpc_receiver_details_label',esc_html__('Collection Instructions', 'wpcargo' ) ); ?></h2><hr style="border: 1px solid black;"/><br>
      <div>
              <label class="form-label"> <?php esc_html_e('Collection Reference', 'wpcargo'); ?> </label> <br style="line-height: 2px;">
              <input style="margin-left: 50px; width: 50%;" type="text" class="notvalidate" placeholder="Collection Reference" id="collection_reference" name="collection_reference" value="<?php echo get_post_meta($post->ID, 'collection_reference', true); ?>"size="25" />
      </div>
      <div> <br>
              <label class="form-label" for="label">Collection Instructions: </label> <br style="line-height: 2px;">
              <textarea rows="4" name="collection_instructions" id="collection_instructions" style="overflow: hidden; word-wrap: break-word; resize:none;  height: 180px; margin-left: 50px; width: 50%; " placeholder="type all instructions here"></textarea>
      </div>
      <div> <br>   <label class="form-label" for="label">Agree boxes: </label> <br style="line-height: 2px;">
          <div style="margin-left: 50px;" id="agree_boxes">
            <label><input style="width: 15px; height: 15px;" type='checkbox' onclick="checkAgreeboxes()"><label style="font: bolder;">&nbsp;Agree Box</label></label><br>
            <label><input style="width: 15px; height: 15px;" type='checkbox' onclick="checkAgreeboxes()"> <label style="font: bolder;">&nbsp;Agree Box</label></label><br>
            <label><input style="width: 15px; height: 15px;" type='checkbox' onclick="checkAgreeboxes()"> <label style="font: bolder;">&nbsp;Agree Box</label></label><br>
            <label><input style="width: 15px; height: 15px;" type='checkbox' onclick="checkAgreeboxes()"> <label style="font: bolder;">&nbsp;Agree Box</label></label>
          </div>
      </div> <br><br>
</div>
<div class="tab" id="summary">
   <?php wpcargo_include_template( 'quotation', $shipment );
         //do_action('wpcargo_after_package_details', $shipment );
   ?>

</div>
<!-- Circles which indicates the steps of the form: -->
<div style="text-align:center;"> <br>
  <span class="step"></span>
  <span class="step"></span>
  <span class="step"></span>
  <span class="step"></span>
  <span class="step"></span>
  <span class="step"></span>
  <span class="step"></span>
  <span class="step"></span>
  <span class="step"></span>
</div>
<div style="overflow:auto;">
  <input type="hidden" id="submit_btn" name="wpc_metabox_nonce" value="">
  <div><br> <center>
    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Back</button>
    <button type="button" id="closeBtn" style="display: none;" onclick="close_this()">Close</button>
    <button type="button" id="savePdf" style="display: none;" onclick="save_Pdf()">Save pdf</button>
    <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
    <button type="button" id="payBtn" style="display: none; background: green;" onclick="nextPrev(1,'Pay')">Pay Now</button> </center>
  </div>
</div>
</div>
</form>

<?php } ?>

<script>

function close_this(){
    window.location.href = "<?php echo esc_url( home_url( '/' ) ); ?>";
}

function save_Pdf(){
    alert("saving pdf");
}
</script>

