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
                                    'wpcargo_price_estimates',
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
       //$collection = (!empty($_POST["collection"]))? "1" : "0";
       //update_post_meta($post_id, 'wpcargo_collection', $collection );
       //$clearance = (!empty($_POST["clearance"]))? "1" : "0";
       //update_post_meta($post_id, 'wpcargo_clearance', $clearance );
       //update_post_meta( $post_id, 'wpcargo_mode_field', 'Land Freight');
       $wpcargo_price_estimates = trim( sanitize_text_field( $_POST['wpcargo_price_estimates'] ) );
       $wpcargo_price_estimates = stripslashes($wpcargo_price_estimates);
       update_post_meta($post_id, 'wpcargo_price_estimates', $wpcargo_price_estimates );
       //$wpcargo_comments = trim( sanitize_text_field( $_POST['wpcargo_comments'] ) );
       //update_post_meta( $post_id, 'wpcargo_comments', $wpcargo_comments );

       //now set history and status for shipment
        $wpcargo_status 	= "Pending";
        $status 	        = "New Booking";
        $status_location 	= "";
        $status_time 		=  date('H:i');
        $status_remarks 	=  "New booking placed";
        $status_date 		= date('Y-m-d');
        $apply_to_shipment 	= true;
        $wpcargo_shipments_update = maybe_unserialize( get_post_meta( $post_id, 'wpcargo_shipments_update', true ) );
        // Make sure that it is set.
        $new_history = array(
            'date' => $status_date,
            'time' => $status_time,
            'location' => $status_location,
            'updated-name' => (!empty($current_user->ID))?$current_user->display_name : "Online Client",
            'updated-by' => $current_user->ID,
            'remarks'	=> $status_remarks,
            'status'    => $status
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
        $post_name_arr = explode("-",sanitize_text_field( $_POST["post_name"]));
        $route_abrs_arr = explode("-",sanitize_text_field( $_POST["route_abrs"]));
        $booking_reference  = $route_abrs_arr[0]."-".$post_name_arr[1]."-".$route_abrs_arr[1];
        update_post_meta( $post_id, 'booking_type', 'Online' ); //booking_type
        update_post_meta( $post_id, 'booking_reference', $booking_reference ); //booking reference
        update_post_meta( $post_id, 'wpcargo_price_estimates-old', trim( sanitize_text_field( $_POST['wpcargo_price_estimates'] ) ));
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
          'post_status' => 'pending',
          'comment_status' => 'closed',
          'ping_status' => 'closed',
          'post_name' => $post_name,
          'post_parent' => '0',
          'post_type' => 'wpcargo_shipment',

    );
    $post_id = wp_insert_post($new_post);    //save shipment
    save_metabox( $post_id );
    $_SESSION["current_id"] = $post_id;
    $_POST['post_ID'] = $post_id;
}

    // if the submit button is clicked, send the email
   	if ( isset( $_POST['wpc_metabox_nonce'] ) && $_POST['wpc_metabox_nonce']=="Pay Later" ) {
           shipment_save( $post_id ); //saving shipment
           //header("Location: Home");
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
        <div class="tab" id="titlewrap" style="width:700px;">
            <input name="post_name" hidden id="post_name" value="<?php echo $wpcargo->create_shipment_number(); ?>">
            <?php  wpcargo_include_template( 'shipment-form', $shipment );  ?>
        </div>
        <div class="tab" id="package_tab">
            <h2>Package details</h2>
            <hr style="border: 1px solid #000;" /><br>
            <div>
                <label class="form-label" for="label">Goods Description and Quantities: </label> <br
                    style="line-height: 2px;">
                <textarea rows="4" name="goods_description" id="goods_description"
                    style="overflow: hidden; word-wrap: break-word; resize:none; width:50%;margin-left:.7em;color:white;"
                    placeholder="--type description and quantities of goods/items here--"></textarea>
            </div>
            <?php require_once( WPCARGO_PLUGIN_PATH.'admin/templates/package-metabox.tpl.php' );  ?>
            <!--  <?php wpcargo_include_template('package-details.tpl', $shipment); ?>    -->
        </div>
        <div class="tab" id="collection_tab">
            <h2><?php echo apply_filters('wpc_shipment_details_label', esc_html__('Special services', 'wpcargo' ) ); ?>
            </h2>
            <hr style="border: 1px solid black;" /><br>
            <div>
                <div style="margin-left: 50px;">

                    <?php
                $results = get_settings_items();
                $items = unserialize($results->meta_data);
                $i=0;
                $excepts = array("customsdeclarationfee","bordertaxes");
                if(!empty($items)) {
                 foreach ( $items as $key => $item_data ) {
                   if($item_data['is_route_item']=="" && $item_data['is_private_item']=="" && $item_data['item_type']=="Income" && !in_array($key,$excepts) ) {
                     ?>
                    <p><input style="margin-left: 20px; height:15px; width:15px;" type="checkbox"
                            onclick="select_service(this)"
                            <?php echo (!empty(get_post_meta($post->ID, $key, true))) ? 'checked' : ''; ?>
                            name="<?php echo $key;?>" value="<?php echo $key;?>"> <label
                            style="font: bolder;"><?php echo $item_data['display_name']; ?></label></p>
                    <?php } $i++;
               } }
             ?>
                </div>
            </div>
            <input type='hidden' style="width: 500px;" name="service_items" id="service_items">
            <input type='checkbox' hidden="hidden" style="width: 15px; height: 15px;" name="clearance" id="clearance"
                data-group='clearance'> <!-- Yes -->
            <input type='checkbox' hidden="hidden" style="width: 15px; height: 15px;" name="noclearance"
                id="noclearance" data-group='clearance'> <!-- No -->

        </div>
        <div class="tab" id="price-estimates">
            <h2><?php echo apply_filters('wpc_receiver_details_label',esc_html__('Price Estimates', 'wpcargo' ) ); ?>
            </h2>
            <hr style="border: 1px solid black;" /><br>
            <div id="price_tables" class="wpcargo-row">
            </div>
            <div class="wpcargo-row">
                <label class="wpcargo-col-md-6">
                    <center><input id="trip_1" name="trip_1" style="width: 15px; height: 15px;" type="checkbox"
                            onclick="trip_select(this)" data-group='trip_select'>&nbsp;Select Collection date 1</center>
                </label>
                <label class="wpcargo-col-md-6">
                    <center><input id="trip_2" name="trip_2" style="width: 15px; height: 15px;" type="checkbox"
                            onclick="trip_select(this)" data-group='trip_select'>&nbsp;Select Collection date 2</center>
                </label>
            </div><br><br>
            <input type="hidden" id="wpcargo_price_estimates" name="wpcargo_price_estimates" value="">
            <input style="display: none;" type="checkbox" id="is_late_booking" name="is_late_booking">
        </div>
        <div class="tab" id="receiver-details">
            <h2><?php echo apply_filters('wpc_receiver_details_label',esc_html__('Shippers Details', 'wpcargo' ) ); ?><span
                    style="font-size: .6em;font-style: italic;margin-left:.7em;"> (Details of the Company or Person
                    making a booking)</span></h2>
            <hr style="border: 1px solid black;" /><br>
            <?php do_action('wpc_before_receiver_details_table', $post->ID); ?>
            <?php do_action('wpc_before_receiver_details_metabox', $post->ID); ?>
            <div class="wpcargo form-table wpcargo-row">
                <div class="wpcargo-col-md-6" style="margin-bottom: 1.5em;">
                    <div><label class="form-label"> <?php esc_html_e('Company Name', 'wpcargo'); ?> <span
                                style="font-size: .87em;font-style: italic;"> (If available)</span></label></div>
                    <div><input type="text" style="width: 100%;" placeholder="Company" id="wpcargo_receiver_company"
                            name="wpcargo_receiver_company"
                            value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_company', true); ?>" /></div>
                </div>
                <div class="wpcargo-col-md-6">
                    <div><label class="form-label"> <?php esc_html_e('Main Phone Number', 'wpcargo'); ?> </label><span
                            style="font-size: .87em;font-style: italic;"> (tick if number has WhatsApp)</span></div>
                    <input type="text" class="wpcargo-col-md-7" id="wpcargo_receiver_phone_1"
                        name="wpcargo_receiver_phone_1"
                        value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_phone_1', true); ?>" />
                    <input class="wpcargo-col-md-5" style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;"
                        type='checkbox' name="whatsapp_1" id="whatsapp_1"><label style="font: bolder;"><img
                            style="width: 30px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>"
                            title="Has whatsapp" alt="Whatsapp"></label>
                </div>
                <div class="wpcargo-col-md-6" style="margin-bottom: 1.5em;">
                    <div><label class="form-label"> <?php esc_html_e('First Name', 'wpcargo'); ?> </label></div>
                    <div><input type="text" style="width: 100%;" placeholder="First Name" id="wpcargo_receiver_fname"
                            name="wpcargo_receiver_fname"
                            value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_fname', true); ?>" /></div>
                </div>
                <div class="wpcargo-col-md-6">
                    <div><label class="form-label"> <?php esc_html_e('Alternative Phone Number', 'wpcargo'); ?> </label>
                    </div>
                    <input type="text" class="notvalidate wpcargo-col-md-7" id="wpcargo_receiver_phone_2"
                        name="wpcargo_receiver_phone_2"
                        value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_phone_2', true); ?>" />
                    <input class="wpcargo-col-md-5" style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;"
                        type='checkbox' name="whatsapp_2" id="whatsapp_2"><label style="font: bolder;"><img
                            style="width: 30px;" src="<?php echo WPCARGO_PLUGIN_URL."assets/images/whatsapp.png"; ?>"
                            title="Has whatsapp" alt="Whatsapp"></label>
                </div>
                <div class="wpcargo-col-md-6">
                    <div><label class="form-label"> <?php esc_html_e(' Surname', 'wpcargo'); ?> </label></div>
                    <div><input type="text" style="width: 100%;" placeholder="Last Name" id="wpcargo_receiver_sname"
                            name="wpcargo_receiver_sname"
                            value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_sname', true); ?>" /> </div>
                </div>
                <div class="wpcargo-col-md-6">
                    <div><label class="form-label"> <?php esc_html_e('Email', 'wpcargo'); ?> </label></div>
                    <div><input type="email" style="width: 90%;" class="notvalidate" id="wpcargo_receiver_email"
                            name="wpcargo_receiver_email"
                            value="<?php echo get_post_meta($post->ID, 'wpcargo_receiver_email', true); ?>" /> </div>
                </div>
            </div>
            <?php do_action('wpc_after_receiver_details_metabox', $post->ID); ?>
            <?php do_action('wpc_after_receiver_details_table', $post->ID); ?>
        </div>
        <div class="tab" id="collection-details">
            <?php wpcargo_include_template( 'collection-details', $shipment );
   ?>
        </div>
        <div class="tab" id="delivery-details">
            <?php wpcargo_include_template( 'delivery-details', $shipment );
   ?>
        </div>
        <div class="tab" id="documents">
            <h2><?php echo apply_filters('wpc_receiver_details_label',esc_html__('Documents', 'wpcargo' ) ); ?></h2>
            <hr style="border: 1px solid black;" /><br>
            <div>
                <i class="wpcargo-col-md-12 form-label" style="display: block;"><b>1. Do you have Tax Invoice?</b></i>
                <div style=" margin-left: 60px;">
                    <input style="width: 15px; height: 15px;" type='checkbox' onclick="documents_switch(this)"
                        name="tax_invoice" id="tax_invoice" data-group='tax_invoice'><label style="font: bolder;">
                        Yes</label> <span>&nbsp;&nbsp;</span>
                    <input style="width: 15px; height: 15px;" type='checkbox' onclick="documents_switch(this)"
                        name="no_tax_invoice" id="no_tax_invoice" data-group='tax_invoice'> <label
                        style="font: bolder;">No</label>
                    <p id="tax_invoice_more" style="width: 60%;"> </p>
                </div>
                <div id="doc2" style="display: none;">
                    <i class="wpcargo-col-md-12 form-label" style="display: block;"><b>2. Do you have Cash
                            Receipt?</b></i>
                    <div style=" margin-left: 60px;">
                        <input style="width: 15px; height: 15px;" type='checkbox' onclick="documents_switch(this)"
                            name="receipt" id="receipt" data-group='receipt'><label style="font: bolder;">
                            Yes</label><span>&nbsp;&nbsp;</span>
                        <input style="width: 15px; height: 15px;" type='checkbox' onclick="documents_switch(this)"
                            name="no_receipt" id="no_receipt" data-group='receipt'> <label
                            style="font: bolder;">No</label>
                        <p id="receipt_more" style="width: 60%;"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab" id="collection-instructions">
            <h2><?php echo apply_filters('wpc_receiver_details_label',esc_html__('Terms and Conditions', 'wpcargo' ) ); ?>
            </h2>
            <hr style="border: 1px solid black;" />
            <div class="wpcargo-row">
                <div class="wpcargo-col-md-6">
                    <div style="margin-left: 10px;" id="agree_boxes">
                        <label><input style="width: 15px; height: 15px;" type='checkbox'
                                onclick="checkAgreeboxes()"><label style="font: bolder;">&nbsp;Agree
                                Box</label></label><br>
                        <label><input style="width: 15px; height: 15px;" type='checkbox' onclick="checkAgreeboxes()">
                            <label style="font: bolder;">&nbsp;Agree Box</label></label><br>
                        <label><input style="width: 15px; height: 15px;" type='checkbox' onclick="checkAgreeboxes()">
                            <label style="font: bolder;">&nbsp;Agree Box</label></label><br>
                        <label><input style="width: 15px; height: 15px;" type='checkbox' onclick="checkAgreeboxes()">
                            <label style="font: bolder;">&nbsp;Agree Box</label></label>
                    </div>
                </div> <br><br>
            </div>
        </div>
        <div class="tab" id="summary">
            <?php wpcargo_include_template( 'quotation', $shipment );
         //do_action('wpcargo_after_package_details', $shipment );
   ?>

        </div>
        <!--<div class="tab" id="submit_payments">

   <?php wpcargo_include_template( 'submit', $shipment );
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
            <div><br>
                <center>
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Back</button>
                    <button type="button" id="closeBtn" style="display: none;" onclick="close_this()">Close</button>
                    <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                    <button type="button" id="payBtn" style="display: none; background: green;"
                        onclick="nextPrev(1,'Pay')">Pay Now</button>
                </center>
            </div>
        </div>
    </div>
</form>

<?php } ?>

<script>
function close_this() {
    window.location.href = "<?php echo esc_url( home_url( '/' ) ); ?>";
}

function save_Pdf() {
    alert("saving pdf");
}
</script>