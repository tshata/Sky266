<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function wpcargo_email_footer_divider_callback(){
    $footer_image       = WPCARGO_PLUGIN_URL.'admin/assets/images/wpc-email-footer.png';
    ob_start();
    ?>
    <div class="wpc-footer-devider">
        <img src="<?php echo $footer_image; ?>" style="width:100%;" />
    </div>
    <?php
    echo ob_get_clean();
}
function wpcargo_fields_option_settings_group_callback( $options ){
    require_once( WPCARGO_PLUGIN_PATH.'admin/templates/settings-fields-option.tpl.php' );
}
function wpcargo_plugins_loaded_hook_callback(){
    add_action( 'wpcargo_fields_option_settings_group', 'wpcargo_fields_option_settings_group_callback', 10, 1 );
    add_action( 'wpcargo_email_footer_divider', 'wpcargo_email_footer_divider_callback' );
    if( get_option('shmap_active') && !empty( trim( get_option('shmap_api')  ) ) ){
        add_action('before_wpcargo_shipment_history', 'wpcargo_shipment_history_map_callback', 10, 1 );
    } 
}
add_action( 'plugins_loaded', 'wpcargo_plugins_loaded_hook_callback' );

function wpcargo_activation_setup_settings(){
	// Set up packages settings
	$package_settings = array(
		'wpc_mp_enable_admin' 			=> 1,
		'wpc_mp_enable_frontend' 		=> 1,
		'wpc_mp_enable_dimension_unit' 	=> 1,
		'wpc_mp_dimension_unit' 		=> 'cm',
		'wpc_mp_weight_unit' 			=> 'kg',
		'wpc_mp_piece_type' 			=> 'Pallet, Carton, Crate, Loose, Others',
	);
	if( !empty( get_option( 'wpc_mp_settings' )  ) ){
		$package_settings = array(
			'wpc_mp_enable_admin' 			=> wpcargo_package_settings()->admin_enable,
			'wpc_mp_enable_frontend' 		=> wpcargo_package_settings()->frontend_enable,
			'wpc_mp_enable_dimension_unit' 	=> wpcargo_package_settings()->dim_unit_enable,
			'wpc_mp_dimension_unit' 		=> wpcargo_package_settings()->dim_unit,
			'wpc_mp_weight_unit' 			=> wpcargo_package_settings()->weight_unit,
			'wpc_mp_piece_type' 			=> implode(",", wpcargo_package_settings()->peice_types ),
		);
	}
	update_option( 'wpc_mp_settings', $package_settings );
	// General Settings
	$general_settings = array(
		'settings_shipment_type' 			=> 'Air Freight, International Shipping, Truckload, Van Move',
		'settings_shipment_wpcargo_mode' 	=> 'Sea Transport, Land Shipping, Air Freight',
		'settings_shipment_status' 			=> implode(",", wpcargo_defualt_status() ),
		'settings_shipment_country' 		=> wpcargo_country_list(),
		'settings_shipment_wpcargo_carrier' => 'DHL, USPS, FedEx',
		'settings_shipment_wpcargo_payment_mode' => 'CASH, Cheque, BACS',
		'settings_shipment_ship_logo' 		=> '',
		'settings_barcode_checkbox'			=> 1,
		'wpcargo_title_prefix_action'		=> 'on',
		'wpcargo_title_prefix'				=> 'WPC',
		'wpcargo_base_color'				=> '#01ba7c',
		'wpcargo_tax'						=> 12,
		'wpcargo_invoice_display_history'	=> 'on',
		'wpcargo_edit_history_role'			=> array( 'administrator', 'wpc_shipment_manager', ),
		'wpcargo_email_employee'			=> false,
		'wpcargo_email_agent'				=> false,
		'wpcargo_email_client'				=> false,
	);
	if( !empty( get_option( 'wpcargo_option_settings' )  ) ){
		$gen_settings 		= get_option( 'wpcargo_option_settings' );
		$barcode_checkbox 	= array_key_exists('settings_barcode_checkbox', $gen_settings ) ? 1 : false;
		$prefix_action 		= array_key_exists('wpcargo_title_prefix_action', $gen_settings ) ? 'on' : false;
		$display_history 	= array_key_exists('wpcargo_invoice_display_history', $gen_settings ) ? 'on' : false;
		$email_employee 	= array_key_exists('wpcargo_email_employee', $gen_settings ) ? 'on' : false;
		$email_agent		= array_key_exists('wpcargo_email_agent', $gen_settings ) ? 'on' : false;
		$email_client		= array_key_exists('wpcargo_email_client', $gen_settings ) ? 'on' : false;
		$general_settings 	= array(
			'settings_shipment_type' 			=> $gen_settings['settings_shipment_type'],
			'settings_shipment_wpcargo_mode' 	=> $gen_settings['settings_shipment_wpcargo_mode'],
			'settings_shipment_status' 			=> $gen_settings['settings_shipment_status'],
			'settings_shipment_country' 		=> $gen_settings['settings_shipment_country'],
			'settings_shipment_wpcargo_carrier' => $gen_settings['settings_shipment_wpcargo_carrier'],
			'settings_shipment_wpcargo_payment_mode' => $gen_settings['settings_shipment_wpcargo_payment_mode'],
			'settings_shipment_ship_logo' 		=> $gen_settings['settings_shipment_ship_logo'],
			'settings_barcode_checkbox'			=>  $barcode_checkbox,
			'wpcargo_title_prefix_action'		=> $prefix_action,
			'wpcargo_title_prefix'				=> $gen_settings['wpcargo_title_prefix'],
			'wpcargo_base_color'				=> $gen_settings['wpcargo_base_color'],
			'wpcargo_tax'						=> $gen_settings['wpcargo_tax'],
			'wpcargo_invoice_display_history'	=> $display_history,
			'wpcargo_edit_history_role'			=> $gen_settings['wpcargo_edit_history_role'],
			'wpcargo_email_employee'			=> $gen_settings['wpcargo_email_employee'],
			'wpcargo_email_agent'				=> $gen_settings['wpcargo_email_agent'],
			'wpcargo_email_client'				=> $gen_settings['wpcargo_email_client'],
		);
	}
	update_option( 'wpcargo_option_settings', $general_settings );
	
	if( empty( get_option('wpcargo_title_suffix') ) ){
		update_option( 'wpcargo_title_suffix', '-CARGO' );
	}
	// Client email settings
	$client_mail_settings = array(
		'wpcargo_active_mail'	=> 1,
		'wpcargo_mail_to'		=> '{wpcargo_shipper_email}',
		'wpcargo_mail_subject' 	=> 'Shipment Notification # {wpcargo_tracking_number}',
		'wpcargo_mail_message' 	=>'',
		'wpcargo_mail_footer'	=> ''
	);
	if( !empty( get_option( 'wpcargo_mail_settings' ) ) ){
		$c_mail_settings 	= get_option( 'wpcargo_mail_settings' );
		$c_active_mail 		= array_key_exists( 'wpcargo_active_mail', $c_mail_settings ) ? $c_mail_settings['wpcargo_active_mail'] : false;
		$client_mail_settings = array(
			'wpcargo_active_mail'	=> $c_active_mail,
			'wpcargo_mail_to'		=> $c_mail_settings['wpcargo_mail_to'],
			'wpcargo_mail_subject' 	=> $c_mail_settings['wpcargo_mail_subject'],
			'wpcargo_mail_message' 	=> $c_mail_settings['wpcargo_mail_message'],
			'wpcargo_mail_footer'	=> $c_mail_settings['wpcargo_mail_footer']
		);
	}
	update_option( 'wpcargo_mail_settings', $client_mail_settings );
	if( empty( get_option( 'wpcargo_admin_mail_active' ) ) ){
		update_option( 'wpcargo_admin_mail_active', 1 );
	}
	if( empty( get_option( 'wpcargo_admin_mail_to' ) ) ){
		update_option( 'wpcargo_admin_mail_to', get_bloginfo('admin_email') );
	}
	if( empty( get_option( 'wpcargo_admin_mail_subject' ) ) ){
		update_option( 'wpcargo_admin_mail_subject', 'Shipment Notification # {wpcargo_tracking_number}' );
	}
}
register_activation_hook( WPCARGO_FILE_DIR, 'wpcargo_activation_setup_settings' );
function wpcargo_track_shipment_history_details( $shipment ) {
    global $wpdb, $wpcargo;
    $settings   = $wpcargo->settings;
    $date_format = $wpcargo->date_format;
    $time_format = $wpcargo->time_format;
    if( !empty( $settings ) ){
        if( !array_key_exists( 'wpcargo_invoice_display_history', $settings ) ){
            return false;
        }
    }
    $shmap_active 	= get_option('shmap_active');
    $shmap_api      = trim( get_option('shmap_api') );
	if( $shmap_active && !empty( $shmap_api ) ){
		?>
		<div id="shmap-wrapper" style="margin: 12px 0;">
		    <div id="wpcargo-shmap" style="height: 320px;"></div>
		</div>
		<?php
	}
    require_once( WPCARGO_PLUGIN_PATH.'templates/result-shipment-history.tpl.php');
}
add_action('wpcargo_after_track_details', 'wpcargo_track_shipment_history_details', 10, 1);
/*
 * Hooks for Custom Field Add ons
 */
function wpcargo_add_display_client_accounts( $flags ){
    ?>
        <tr>
            <th><?php esc_html_e('Do you want to display it on account page?', 'wpcargo' ); ?></th>
            <td><input name="display_flags[]" value="account_page" type="checkbox"></td>
        </tr>
    <?php
}
add_action( 'wpc_cf_after_form_field_add', 'wpcargo_add_display_client_accounts' );
function wpcargo_edit_display_client_accounts( $flags ){
    ?>
        <tr>
            <th><?php esc_html_e('Do you want to display it on account page?', 'wpcargo' ); ?></th>
            <td><input name="display_flags[]" value="account_page" type="checkbox" <?php echo is_array($flags) && in_array( 'account_page', $flags) ? 'checked' : ''; ?> /></td>
        </tr>
    <?php
}
add_action( 'wpc_cf_after_form_field_edit', 'wpcargo_edit_display_client_accounts' );
add_action('wp_footer', function(){
    global $post;
    if ( is_a( $post, 'WP_Post' ) && ( has_shortcode( $post->post_content, 'wpcargo_account') ) ) {
		?>
		<!-- The Modal -->
		<div id="view-shipment-modal" class="wpcargo-modal">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<span class="close">&times;</span>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"></div>
			</div>
		</div>
		<?php
    }
});
// Plugin row Hook
add_filter( 'plugin_row_meta', 'wpcargo_plugin_row_meta', 10, 2 );
function wpcargo_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, 'wpcargo.php' ) !== false ) {
        $new_links = array(
            'settings' => '<a href="'.admin_url('admin.php?page=wpcargo-settings').'">'.esc_html__('Settings', 'wpcargo').'</a>',
            );
        $links = array_merge( $links, $new_links );
    }
    return $links;
}
add_action( 'quick_edit_custom_box', 'wpcargo_bulk_update_registered_shipper_custom_box', 10, 2 );
add_action( 'bulk_edit_custom_box', 'wpcargo_bulk_update_registered_shipper_custom_box', 10, 2 );
function wpcargo_bulk_update_registered_shipper_custom_box( $column_name,  $screen_post_type ){
    global $wpcargo;
    $shmap_active   = get_option('shmap_active');
    if( $screen_post_type == 'wpcargo_shipment'  ){
        wp_nonce_field( 'reg_shipper_bulk_update_action', 'reg_shipper_bulk_update_nonce' );
        $user_args = array(
            'meta_key' => 'first_name',
            'orderby'  => 'meta_value',
         );
        $all_users = get_users( $user_args );
        if( $column_name == 'registered_shipper' ){
            ?>
            <fieldset class="inline-edit-col-right">
                <div class="inline-edit-col">
                    <div class="inline-edit-group wp-clearfix">
                        <label class="inline-edit-registered_shipper">
                            <span class="title"><?php esc_html_e( 'Registered Shipper', 'wpcargo' ); ?></span>
                            <select name="registered_shipper">
                                <option value=""><?php esc_html_e( '— No Change —', 'wpcargo' ); ?></option>
                                <?php
                                foreach($all_users as $user){
                                    $user_fullname = apply_filters( 'wpcargo_registered_shipper_option_label', $wpcargo->user_fullname( $user->ID ), $user->ID );
                                    echo '<option value="'.trim($user->ID).'" >'.$user_fullname.'</option>';
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
function wpcargo_shipment_registered_shipper_custom_box_bulk_save( $post_id ) {
    global $wpcargo;
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if( !isset( $_REQUEST["reg_shipper_bulk_update_nonce"] ) ){
        return;
    }
    if ( !wp_verify_nonce( $_REQUEST["reg_shipper_bulk_update_nonce"], 'reg_shipper_bulk_update_action' ) ){
        return;
    }
    $current_user = wp_get_current_user();
    if ( isset( $_REQUEST['registered_shipper'] ) && $_REQUEST['registered_shipper'] != '' ) {
        update_post_meta( $post_id, 'registered_shipper', abs($_REQUEST['registered_shipper']) );
    }
}
add_action( 'save_post', 'wpcargo_shipment_registered_shipper_custom_box_bulk_save' );
// Run this hook when plugin is deactivated
function wpcargo_detect_plugin_deactivation(  $plugin, $network_activation ) {
    if( 'wpcargo-client-accounts-addons/wpcargo-client-accounts.php' == $plugin  ){
        add_role('wpcargo_client', 'WPCargo Client', array(
            'read' => true, // True allows that capability
        ));
    }
}
add_action( 'deactivated_plugin', 'wpcargo_detect_plugin_deactivation', 10, 2 );
// Shipment History Map
function wpcargo_shipment_history_map_callback( $shipment_id ){
	global $post, $wpcargo;
    $shmap_api      = get_option('shmap_api');
    $shmap_longitude = !empty(get_option('shmap_longitude') ) ? get_option('shmap_longitude') : -87.65;
    $shmap_latitude = !empty(get_option('shmap_latitude') )  ? get_option('shmap_latitude') : 41.85;
    $shmap_country_restrict      = get_option('shmap_country_restrict');
    $shmap_active   = get_option('shmap_active');
    $shmap_type     = get_option('shmap_type') ? get_option('shmap_type') : 'terrain' ;
    $shmap_zoom     = get_option('shmap_zoom') ? get_option('shmap_zoom') : 15 ;
    $maplabels      = apply_filters('wpcargo_map_labels', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890' );
        $history    = $wpcargo->history( $shipment_id );
        $history_location = array();
        if( !empty( $history  ) ){
            foreach ( $history as $value ) {
                if( empty( $value['location'] ) ){
                    continue;
                }
                $history_location[] = $value['location'];
            }
        }
        $addressLocations   = $history_location;
        $shipment_origin    = array_shift( $history_location );
        $shipment_destination   = array_pop( $history_location );
        ?>
        <script>
            /*
            ** Google map Script Auto Complete address
            */
            var placeSearch, autocomplete, map, geocoder;
            var componentForm = {
                street_number: 'short_name',
                route: 'long_name',
                locality: 'long_name',
                administrative_area_level_1: 'short_name',
                country: 'long_name',
                postal_code: 'short_name'
            };
            var labels = '<?php echo $maplabels; ?>';
            var labelIndex = 0;
            function wpcSHinitMap() {
                geocoder = new google.maps.Geocoder();
                getPlace_dynamic();
				var map = new google.maps.Map( document.getElementById('wpcargo-shmap'), {
				  zoom: <?php echo $shmap_zoom; ?>,
				  center: {lat: <?php echo $shmap_latitude; ?>, lng: <?php echo $shmap_longitude; ?>},
				  mapTypeId: '<?php echo $shmap_type; ?>',
				});
				/*  Map script
				**  Initialize Shipment Locations
				*/
				var shipmentAddress = <?php echo json_encode( $addressLocations ); ?>;
				var shipmentData    = <?php echo json_encode( $history ); ?>;
				var flightPlanCoordinates = [];
				for (var i = 0; i < shipmentAddress.length; i++ ) {
					codeAddress( geocoder, map, shipmentAddress[i], flightPlanCoordinates, i, shipmentData );
				}
				var demoformat = [
				  {lat: 10.2976348, lng: 123.89349070000003},
				  {lat: 3.139003, lng: 101.68685499999992},
				  {lat: 14.5995124, lng: 120.9842195}
				];
				//console.log( flightPlanCoordinates );
				var flightPath = new google.maps.Polyline({
				  path: flightPlanCoordinates,
				  geodesic: true,
				  strokeColor: '#FF0000',
				  strokeOpacity: 1.0,
				  strokeWeight: 2
				});
				flightPath.setMap(map);
            }
            function getPlace_dynamic() {
                 var defaultBounds = new google.maps.LatLngBounds(
                     new google.maps.LatLng(-33.8902, 151.1759),
                     new google.maps.LatLng(-33.8474, 151.2631)
                 );
                 var input = document.getElementsByClassName('status_location');
                 var options = {
                     bounds: defaultBounds,
                     types: ['geocode'],
					 <?php if( !empty( $shmap_country_restrict ) ): ?>
					 	componentRestrictions: {country: "<?php echo $shmap_country_restrict; ?>"}
					 <?php endif; ?>
                 };
                 for (i = 0; i < input.length; i++) {
                     autocomplete = new google.maps.places.Autocomplete(input[i], options);
                 }
            }
            function codeAddress( geocoder, map, address, flightPlanCoordinates, index, shipmentData ) {
                var wpclabelColor   = '<?php echo ( get_option('shmap_label_color') ) ? get_option('shmap_label_color') : '#fff' ;  ?>';
                var wpclabelSize    = '<?php echo ( get_option('shmap_label_size') ) ? get_option('shmap_label_size').'px' : '18px' ;  ?>';
                var wpcMapMarker    = '<?php echo ( get_option('shmap_marker') ) ? get_option('shmap_marker') : WPCARGO_PLUGIN_URL.'/admin/assets/images/wpcmarker.png' ;  ?>';
                geocoder.geocode({'address': address}, function(results, status) {
                    if (status === 'OK') {
                        var geolatlng = { lat: results[0].geometry.location.lat(),  lng: results[0].geometry.location.lng() };
                        flightPlanCoordinates[index] = geolatlng;
                        map.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                            map: map,
                            label: {text: labels[index % labels.length], color: wpclabelColor, fontSize: wpclabelSize },
                            position: results[0].geometry.location,
                            icon: wpcMapMarker
                        });
                        /*
                        ** Marker Information window
                        */
                        // shipmentData
                        var sAddressDate = shipmentData[index].date;
                        var sAddresstime = shipmentData[index].time;
                        var sAddresslocation = shipmentData[index].location;
                        var sAddressstatus = shipmentData[index].status;
                        var shipemtnInfo = '<strong><?php esc_html_e('Date', 'wpcargo'); ?>:</strong> '+sAddressDate+' '+sAddresstime+'</br>'+
                                           '<strong><?php esc_html_e('Location', 'wpcargo'); ?>:</strong> '+sAddresslocation+'</br>'+
                                           '<strong><?php esc_html_e('Status', 'wpcargo'); ?>:</strong> '+sAddressstatus;
                        var infowindow = new google.maps.InfoWindow({
                          content: shipemtnInfo
                        });
                        marker.addListener('click', function() {
                          infowindow.open(map, marker);
                        });
                    } else {
                        //alert('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }
        </script>
        <?php
		echo wpcargo_map_script( 'wpcSHinitMap' );
}
function wpcargo_track_shipment_status_result( $shimpment_details ){
		$shipment_status = get_post_meta( $shimpment_details->ID, 'wpcargo_status', true );
		?>
		<div id="shipment-status" class="wpcargo-row" style="text-align:center;">
			<p id="result-status-header"><?php echo apply_filters( 'wpcargo_track_shipment_status_result_title', esc_html__( 'Shipment Status: ', 'wpcargo' ) ); ?><?php echo $shipment_status; ?></p>
		</div>
		<?php
}
add_action( 'wpcargo_before_shipment_details', 'wpcargo_track_shipment_status_result', 10, 1 );

function remove_my_post_metaboxes() {
   	global $wpcargo;
		$screen = get_current_screen();
		if( $screen->post_type != 'wpcargo_shipment' ){
			return false;
		}
    remove_meta_box( 'authordiv','post','normal' ); // Author Metabox
    remove_meta_box( 'commentstatusdiv','post','normal' ); // Comments Status Metabox
    remove_meta_box( 'commentsdiv','post','normal' ); // Comments Metabox
    remove_meta_box( 'postcustom','post','normal' ); // Custom Fields Metabox
    remove_meta_box( 'postexcerpt','post','normal' ); // Excerpt Metabox
    remove_meta_box( 'revisionsdiv','post','normal' ); // Revisions Metabox
    remove_meta_box( 'slugdiv','post','normal' ); // Slug Metabox
    remove_meta_box( 'trackbacksdiv','post','normal' ); // Trackback Metabox
}
add_action('admin_menu','remove_my_post_metaboxes');

//change text for post publish
add_filter( 'gettext', 'change_publish_button', 10, 2 );
function change_publish_button( $translation, $text ) {
    if ( $text == 'Publish' )
        return 'Save Booking';
    return $translation;
}

// Register Custom Post Status for closed bookings
function register_custom_post_status(){
    register_post_status( 'archive', array(
        'label'                     => _x( 'Archives', 'post' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Archives <span class="count">(%s)</span>', 'Archives <span class="count">(%s)</span>' ),
    ) );
    register_post_status( 'pending', array(
        'label'                     => _x( 'Pending', 'post' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Online Pending Bookings  <span class="count">(%s)</span>', 'Online Pending Bookings <span class="count">(%s)</span>' ),
    ) );
}
add_action( 'init', 'register_custom_post_status' );
//Here's my custom CSS that removes the back link in a function
function my_login_page_remove_back_to_link() { ?>
        <style type="text/css">
                body.login div#login p#backtoblog {
                    display: none;
                }

        </style>
<?php }

//This loads the function above on the login page
add_action( 'login_enqueue_scripts', 'my_login_page_remove_back_to_link' );

add_action( 'wp_login_failed', 'custom_login_failed' );
function custom_login_failed( $username )
{
    $referrer = wp_get_referer();

    if ( $referrer && ! strstr($referrer, 'wp-login') && ! strstr($referrer,'wp-admin') )
    {
        wp_redirect( add_query_arg('login', 'failed', $referrer) );
        exit;
    }
}
add_filter( 'authenticate', 'custom_authenticate_username_password', 30, 3);
function custom_authenticate_username_password( $user, $username, $password )
{
    if ( is_a($user, 'WP_User') ) { return $user; }

    if ( empty($username) || empty($password) )
    {
        $error = new WP_Error();
        $user  = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));

        return $error;
    }
}
function trip_driver_deleted( $user_id ) {
    global $wpdb;
    $user_obj = get_userdata( $user_id );
    $display_name = $user_obj->display_name;
    $trips = $wpdb->get_results( "SELECT * FROM trips WHERE drivers Like '%$display_name%' AND status!='Closed' AND status!='Terminated'");
    if(is_array($trips))
     foreach($trips AS $trip){
          $trip_drivers = explode(",",unserialize($trip->drivers));
          if (($key = array_search($display_name, $trip_drivers)) !== false) {
                unset($trip_drivers[$key]);
           }

          $wpdb->update(
              'trips',
               array(
            			'drivers' => serialize(implode(",",$trip_drivers)),
            		),
               array(
        			'id' => $trip->id
        		)
          );
     }
}
add_action( 'delete_user', 'trip_driver_deleted' );

/*
 * Function for post duplication. Dups appear as drafts. User is redirected to the edit screen
 */
function rd_duplicate_post_as_draft(){
  global $wpdb;
  if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
    wp_die('No post to duplicate has been supplied!');
  }

  /*
   * Nonce verification
   */
  if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
    return;

  /*
   * get the original post id
   */
  $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
  /*
   * and all the original post data then
   */
  $post = get_post( $post_id );

  /*
   * if you don't want current user to be the new post author,
   * then change next couple of lines to this: $new_post_author = $post->post_author;
   */
  $current_user = wp_get_current_user();
  $new_post_author = $current_user->ID;

  /*
   * if post data exists, create the post duplicate
   */
  if (isset( $post ) && $post != null) {

    /*
     * new post data array
     */
    $args = array(
      'comment_status' => $post->comment_status,
      'ping_status'    => $post->ping_status,
      'post_author'    => $new_post_author,
      'post_content'   => $post->post_content,
      'post_excerpt'   => $post->post_excerpt,
      'post_name'      => $post->post_name,
      'post_parent'    => $post->post_parent,
      'post_password'  => $post->post_password,
      'post_status'    => 'draft',
      'post_title'     => $post->post_title,
      'post_type'      => $post->post_type,
      'to_ping'        => $post->to_ping,
      'menu_order'     => $post->menu_order
    );

    /*
     * insert the post by wp_insert_post() function
     */
    $new_post_id = wp_insert_post( $args );

    /*
     * get all current post terms ad set them to the new post draft
     */
    $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
    foreach ($taxonomies as $taxonomy) {
      $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
      wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
    }

    /*
     * duplicate all post meta just in two SQL queries
     */
    $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
    if (count($post_meta_infos)!=0) {
      $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
      foreach ($post_meta_infos as $meta_info) {
        $meta_key = $meta_info->meta_key;
        if( $meta_key == '_wp_old_slug' ) continue;
        $meta_value = addslashes($meta_info->meta_value);
        $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
      }
      $sql_query.= implode(" UNION ALL ", $sql_query_sel);
      $wpdb->query($sql_query);
    }


    /*
     * finally, redirect to the edit post screen for the new draft
     */
    wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
    exit;
  } else {
    wp_die('Post creation failed, could not find original post: ' . $post_id);
  }
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );

/*
 * Add the duplicate link to action list for post_row_actions
 */
function rd_duplicate_post_link( $actions, $post ) {
  if (current_user_can('edit_posts')) {
    $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Clone this item" rel="permalink">Clone</a>';
  }
  return $actions;
}

add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );

