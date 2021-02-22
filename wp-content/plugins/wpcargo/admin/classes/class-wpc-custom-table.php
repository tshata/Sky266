
<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
add_filter('manage_wpcargo_shipment_posts_columns' , 'set_default_wpcargo_columns');
function set_default_wpcargo_columns($columns) {
    $get_the_wpcargo_tbl = array(
		'cb' 					=> '<input type="checkbox" />',
		'booking_reference' 		=> __( apply_filters( 'wpc_admin_tbl_list_tracking_number', 'Booking Reference' ), 'wpcargo'),
		'tracking_number' 	=> __( apply_filters( 'wpc_admin_tbl_to', 'Tracking Number' ), 'wpcargo'),
		'wpcargo_origin' 		=> __( apply_filters( 'wpc_admin_tbl_list_from', 'From' ), 'wpcargo'),
		'wpcargo_destination' 	=> __( apply_filters( 'wpc_admin_tbl_to', 'To' ), 'wpcargo'),
		'shipper' 			=> __( apply_filters( 'wpc_admin_tbl_list_booking_by', 'Shipper' ), 'wpcargo'),
		'booking_by' 	    	=> __( apply_filters( 'wpc_admin_tbl_list_booking_type', 'Booking By' ), 'wpcargo'),
		'trip_date' 			=> __( apply_filters( 'wpc_admin_tbl_list_trip_date', 'Collection Date' ), 'wpcargo'),
		'wpcargo_status' 	    => __( apply_filters( 'wpc_admin_tbl_list_status', 'Status' ), 'wpcargo'),
        //'wpcargo_actions' 	=> __( apply_filters( 'wpc_admin_tbl_list_action', 'Actions' ), 'wpcargo'),
    );
    $get_the_wpcargo_tbl 		= apply_filters('default_wpcargo_columns', $get_the_wpcargo_tbl );
	return $get_the_wpcargo_tbl;
}
add_action( 'manage_wpcargo_shipment_posts_custom_column', 'manage_default_wpcargo_columns', 10, 2 );
function manage_default_wpcargo_columns( $column, $post_id ) {
	global $post, $wpcargo,$wpdb;
	switch( $column ) {
		case 'booking_reference' :
            $booking_reference = (!empty(get_post_meta( $post_id, 'booking_reference', true)))? get_post_meta( $post_id, 'booking_reference', true) : get_the_title();
			$title = "<a href='".admin_url()."edit.php?post_type=wpcargo_shipment&page=wpcargo-print-layout&id=".$post_id."'>".$booking_reference."</a>";
			echo $title;
			break;
		case 'tracking_number' :
			$tracking_number = get_post_meta( $post_id, 'tracking_number', true);
			echo $tracking_number;
			break;
		case 'wpcargo_origin' :
			$wpcargo_origin_field = get_post_meta( $post_id, 'wpcargo_origin_field', true);
			$wpcargo_origin_city_field = get_post_meta( $post_id, 'wpcargo_origin_city_field', true);
			echo $wpcargo_origin_field."<br>".$wpcargo_origin_city_field;
			break;
		case 'wpcargo_destination' :
			$wpcargo_destination = get_post_meta( $post_id, 'wpcargo_destination', true);
			$wpcargo_destination_city = get_post_meta( $post_id, 'wpcargo_destination_city', true);
			echo $wpcargo_destination."<br>".$wpcargo_destination_city;
			break;
		case 'shipper' :
			$wpcargo_receiver_fname = get_post_meta( $post_id, 'wpcargo_receiver_fname', true);
			$wpcargo_receiver_sname = get_post_meta( $post_id, 'wpcargo_receiver_sname', true);
			$wpcargo_receiver_companyname = get_post_meta( $post_id, 'wpcargo_receiver_company', true);
            $wpcargo_date_publish = date_format(date_create(get_the_date()),"d-M-Y");
			echo (!empty($wpcargo_receiver_companyname)) ? $wpcargo_receiver_companyname : $wpcargo_receiver_fname." ".$wpcargo_receiver_sname;
            echo "<br>".$wpcargo_date_publish;
			break;
		case 'booking_by' :
			$booking_type = get_post_meta( $post_id, 'booking_type', true);
            $author = ($booking_type=="Online")? get_post_meta( $post_id, 'wpcargo_receiver_fname', true)." ".get_post_meta( $post_id, 'wpcargo_receiver_fname', true) : get_the_author();
			echo $booking_type.", <br>".$author;
			break;
		case 'trip_date' :
            //$shipment_trip_id = $wpcargo->shipment_trip_id;
            $shipment_trip_id  = get_post_meta( $post_id, 'collection_schedule_id', true);
            $selected_schedule = $wpdb->get_results("SELECT * FROM collection_schedules WHERE id = '$shipment_trip_id'");
            $schedule_date = $selected_schedule[0]->schedule_date;
            $origin_city = get_post_meta( $post_id, 'wpcargo_origin_city_field', true);
            $city = $wpdb->get_results("SELECT * FROM countries_cities WHERE city_name='$origin_city'");
            $option_label = ($city[0]->country_depot == 1)? $city[0]->city_name." Daily" : date_format(date_create($schedule_date),'d-F-Y');
            echo ($schedule_date=="")? "<span id='assigned' style='color:red;'>Unassigned</span>" : $option_label;
           /* echo ($schedule_date=="")? "<span id='assigned' style='color:red;'>Unassigned</span>" : "<span id='assigned'>".date_format(date_create($schedule_date),'d-F-Y')."</span>";
            echo ($schedule_date=="")? "<br><span id='unassigned'><a style='cursor: pointer;' type='button' id='collection_schedule_id' onclick='form_assign_schedule(this,".$post_id.")'> >>assign date>></a></span>" : "<br><span id='unassigned'><a style='cursor: pointer;' type='button' id='collection_schedule_id' onclick='form_assign_schedule(this,".$post_id.")'> >>edit date>></a></span>";
            ?>

            <div id="myModal" class="modal">
              <!-- Modal content -->
              <div id="modal_formf" class="modal-content">
                <form>
                  <div class="modal-header">
                    <span class="close" onclick="close_modal()">&times;</span>
                    <h2>Service Date Selection</h2>
                  </div>
                  <div class="modal-body">
                    <div class="wpc-mp-wrap" id="quote_div" style="display: none; padding: 20px;">
                    </div>
                  </div>
                  <div class="modal-footer">
                       <input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>">
                       <a class="button" id="submit_btn" onclick="save_assign_trip()">Save changes</a>
                       <a class="button" onclick="close_modal()">Close</a>
                  </div>
                </form>
              </div>
            </div>
            <?php   */
			break;
		case 'wpcargo_status' :
			$wpcargo_status = get_post_meta( $post_id, 'wpcargo_status', true );
			echo $wpcargo_status;
            echo "<br>".get_latest_substatus(get_post_meta( $post_id, 'wpcargo_shipments_update', true ));
			break;
		case 'wpcargo_actions' :
			//echo '<a href="edit.php?post_type=wpcargo_shipment&page=wpcargo-print-layout&id='.get_the_ID().'" class="button button-secondary"><span class="dashicons dashicons-media-document"></span> Invoice</a><br/>';
			//echo '<a href="edit.php?post_type=wpcargo_shipment&page=wpcargo-print-label&id='.get_the_ID().'" class="button button-secondary"><span class="dashicons dashicons-tag"></span> Label</a>';
		break;
		default :
			break;
	}
}
add_filter( 'manage_edit-wpcargo_shipment_sortable_columns', 'set_custom_wpcargo_sortable_columns' );
function set_custom_wpcargo_sortable_columns( $columns ) {
	$columns['title'] 			= 'titles';
	$columns['booking_reference'] 	= 'booking_reference';
	$columns['wpcargo_origin'] 			= 'wpcargo_origin_field';
	$columns['wpcargo_destination'] 		= 'wpcargo_destination';
	$columns['booking_by'] 		= 'booking_by';
	$columns['booking_date'] 		= 'booking_date';
	$columns['booking_type'] 		= 'booking_type';
	$columns['trip_date'] 		= 'trip_date';
	$columns['wpcargo_status'] 		= 'wpcargo_status';
	return $columns;
}
add_action( 'pre_get_posts', 'wpcargo_custom_orderby' );
function wpcargo_custom_orderby( $query ) {
	if ( ! is_admin() )
	return;
	if(isset($_GET['post_type']) && $_GET['post_type'] == 'wpcargo_shipment') {
		$orderby = $query->get( 'orderby');
		if(!isset($_GET['orderby'])){
			$query->set( 'orderby', 'booking_date' );
			$query->set( 'order', 'DESC' );
		}
		if(!isset($_GET['post_status'])){
			$query->set( 'post_status', 'publish' );
		}
        /*if($_GET['post_status']=="archive"){
            $query->set( 'post_status', 'draft' );
        }
		/*if ( 'wpcargo_origin' == $orderby ) {
			$query->set( 'meta_key', 'wpcargo_origin' );
			$query->set( 'orderby', 'meta_value' );
		}*/
	}
}
/*
** Bulk and Quick Edit function
*/
add_action( 'quick_edit_custom_box', 'wpcargo_bulk_update_status', 10, 2 );
add_action( 'bulk_edit_custom_box', 'wpcargo_bulk_update_status', 10, 2 );
function wpcargo_bulk_update_status( $column_name,  $screen_post_type ){
	global $wpcargo;
	$shmap_active 	= get_option('shmap_active');
   	if( $screen_post_type == 'wpcargo_shipment'  ){
	    wp_nonce_field( 'wpcargo_bulk_update_action', 'wpcargo_bulk_update_nonce' );
	    if( $column_name == 'wpcargo_status' ){
	 		?>
		 	<fieldset id="shipment-bulk-update" class="inline-edit-col-left" style="border: 1px solid #ddd; margin-top: 6px; padding:8px;">
		 		<div class="inline-edit-col wpc-status-section">
					<div class="inline-edit-group wp-clearfix">
						<legend class="inline-edit-legend"><?php esc_html_e( 'Update Shipment Status', 'wpcargo' ) ?></legend>
						<p><input style="width:100%;" class="bulkdate" type="date" name="status_date" placeholder="<?php echo $wpcargo->date_format; ?>" autocomplete="off" /></p>
						<p><input style="width:100%;" class="bulktime" type="time" name="status_time" autocomplete="off" /></p>
						<p><input style="width:100%;" class="status_location" type="text" name="status_location" placeholder="<?php esc_html_e( 'Current City', 'wpcargo' ); ?>"  autocomplete="off" /></p>
						<?php if( !empty( $wpcargo->status ) ): ?>
					        <select style="width:100%;" class="wpcargo_status" name="wpcargo_status" >
					            <option value=""><?php esc_html_e( '--Select Status--', 'wpcargo' ) ?></option>
					            <?php
					                foreach( $wpcargo->status as $value ){
					                    ?><option value="<?php echo $value; ?>"><?php echo $value; ?></option><?php
					                }
					            ?>
					        </select>
					    <?php else: ?>
					        <p class="description"><?php esc_html_e( 'No Shipment Status Found.', 'wpcargo' ) ?> <a href="<?php echo admin_url('admin.php?page=wpcargo-settings'); ?>"><?php esc_html_e( 'Add Shipment Status', 'wpcargo' ) ?></a></p>
					    <?php endif; ?>
					    <p> <textarea style="width:100%;" class="remarks" name="status_remarks" placeholder="<?php esc_html_e( 'Remarks', 'wpcargo' ); ?>" ></textarea></p>
					</div>
				</div>
			</fieldset>
			<fieldset class="inline-edit-col-right">
		 		<div class="inline-edit-col">
					<div class="inline-edit-group wp-clearfix">
						<label class="inline-edit-status alignleft">
							<span class="title"><?php esc_html_e( 'Select Agent', 'wpcargo' ); ?></span>
							<select name="wpcargo_agent">
								<option value=""><?php esc_html_e( '— No Change —', 'wpcargo' ); ?></option>
								<?php
								if( !empty( $wpcargo->agents ) ){
							 		foreach ( $wpcargo->agents as $agentid => $agent_name ) {
							 			?><option value="<?php echo $agentid; ?>"><?php echo $agent_name; ?></option><?php
							 		}
							 	}
								?>
							</select>
						</label>
					</div>
				</div>
			</fieldset>
		 	<?php
		}
 	}
}


/*
** Bulk and Quick Save function
*/
add_action( 'save_post', 'wpcargo_shipment_bulk_save' );
function wpcargo_shipment_bulk_save( $post_id ) {
	global $wpcargo;
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if( !isset( $_REQUEST["wpcargo_bulk_update_nonce"] ) ){
    	return;
    }
    if ( !wp_verify_nonce( $_REQUEST["wpcargo_bulk_update_nonce"], 'wpcargo_bulk_update_action' ) ){
        return;
    }
    $current_user = wp_get_current_user();
	if ( isset( $_REQUEST['wpcargo_status'] ) && $_REQUEST['wpcargo_status'] != '' ) {
	    $wpcargo_status 	= trim( sanitize_text_field( $_REQUEST['wpcargo_status'] ) );
		$status_location 	= trim( sanitize_text_field( $_REQUEST['status_location'] ) );
		$status_time 		= sanitize_text_field( $_REQUEST['status_time'] );
		$status_remarks 	= trim( sanitize_text_field( $_REQUEST['status_remarks'] ) );
		$status_date 		= trim( sanitize_text_field( $_REQUEST['status_date'] ) );
		$apply_to_shipment 	= ( isset($_REQUEST['apply_status']) ) ? true : false ;
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
		do_action( 'wpc_add_sms_shipment_history', $post_id );
		//require_once( WPCARGO_PLUGIN_PATH.'admin/templates/email-notification.tpl.php' );
	}
	if ( isset( $_REQUEST['wpcargo_agent'] ) && $_REQUEST['wpcargo_agent'] != '' ) {
	    $wpcargo_agent  = $_REQUEST['wpcargo_agent'];
		update_post_meta( $post_id, 'agent_fields', $wpcargo_agent );
	}
}

add_filter( 'post_row_actions', 'modify_list_row_actions', 10, 2 );

function modify_list_row_actions( $actions, $post ) {
    // Check for your post type.
    if ( $post->post_type == "wpcargo_shipment" ) {
        $trash = $actions['trash'];
        $edit = $actions['edit'];
        $duplicate = $actions['duplicate'];
        $new_actions = array();  //reset actions
        $wpcargo_shipments_update = get_post_meta( $post->ID, 'wpcargo_shipments_update', true );
        if( strpos($wpcargo_shipments_update, "Collection Successful") == false && strpos($wpcargo_shipments_update, "Delivery Successful") == false)
        {
           $new_actions['edit']=$edit;
        }
        if( get_post_meta( $post->ID, 'wpcargo_status', true )!="Complete")
        {
           $new_actions['trash']=str_replace("Trash","Terminate",$trash);
        }
        $new_actions['duplicate']=$duplicate;
        //$new_actions['untrash']=$actions['untrash'];
    }

    return $new_actions;
}

// hide views in post list table (e.g 'All','Published','Trash','Drafts')
add_filter('views_edit-wpcargo_shipment', 'my_table_view');
function my_table_view( $views ) {
    $newviews=array();
    $newviews['publish'] = str_replace("Published","Current Bookings",$views['publish']);
    $newviews['trash'] =  str_replace("Trash","Terminated Bookings",$views['trash']);//$views['trash'];
    $newviews['draft'] = $views['draft'];
    $newviews['archive'] = $views['archive'];
    $newviews['pending'] = $views['pending'];
    //$newviews['archive'] = "<a href='edit.php?post_status=archive&post_type=wpcargo_shipment'>Archives</a>(".wp_count_posts($post_type = 'wpcargo_shipment')->archive.")";
    return $newviews;

}
// remove bul actions
add_filter('bulk_actions-edit-wpcargo_shipment','register_my_bulk_actions');
function register_my_bulk_actions($bulk_actions){
   //$bulk_actions['trash'] = str_replace("Move to Trash","Terminate",$bulk_actions['trash']);
   return array();
}

