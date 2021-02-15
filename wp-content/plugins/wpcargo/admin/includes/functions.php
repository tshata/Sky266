<?php
if (!defined('ABSPATH')){
    exit; // Exit if accessed directly
}
function wpcargo_include_template( $file_name, $shipment ){
    $custom_template_path   = get_stylesheet_directory().'/wpcargo/'.$file_name.'.php';
    if( file_exists( $custom_template_path ) ){
        $template_path = $custom_template_path;
    }else{
        $template_path  = WPCARGO_PLUGIN_PATH.'templates/'.$file_name.'.php';
    }
    include_once( $template_path );
}
function wpcargo_admin_include_template( $file_name, $shipment ){
    $custom_template_path   = get_stylesheet_directory().'/wpcargo/admin/'.$file_name.'.php';
    if( file_exists( $custom_template_path ) ){
        $template_path = $custom_template_path;
    }else{
        $template_path  = WPCARGO_PLUGIN_PATH.'admin/templates/'.$file_name.'.php';
    }
    include_once( $template_path ); 
}
function wpcargo_trackform_shipment_number( $shipment_number ) {
    global $wpdb;
    $shipment_number = esc_sql( $shipment_number );
    $sql = apply_filters( 'wpcargo_trackform_shipment_number_query', "SELECT `ID` FROM `{$wpdb->prefix}posts` WHERE post_title = '{$shipment_number}' AND `post_status` = 'publish' AND `post_type` = 'wpcargo_shipment' LIMIT 1", $shipment_number );
    $results = $wpdb->get_var($sql);
    return $results;
}
function wpcargo_get_postmeta( $post_id = '' , $metakey = '', $type = '' ){
	global $wpcargo;
    $result = '';
    if( !empty( $post_id ) && !empty( $metakey ) ){
        $result                    = maybe_unserialize( get_post_meta( $post_id, $metakey, true) );
        if( is_array( $result ) ){
            $result = array_filter( array_map( 'trim', $result ) );
            if( !empty( $result ) ){
                $result = implode(', ',$result);
            } 
            if( $type == 'url' ){
                $url_data = array_values( maybe_unserialize( get_post_meta( $post_id, $metakey, true) ) );
                $target   = count( $url_data ) > 2 ? '_blank' : '' ;
                $url      = $url_data[1] ? $url_data[1] : '#' ;
                $label    = $url_data[0];
                $result   = '<a href="'.$url.'" target="'.$target.'">'.$label.'</a>';
            }       
        }
    }
    return $result;
}
function wpcargo_to_slug( $string = '' ){
    $string = strtolower( preg_replace('/\s+/', '_', trim( $string ) ) );
    return substr( preg_replace('/[^A-Za-z0-9_\-]/', '', $string ), 0, 60 );
}
function wpcargo_html_value( $string, $htmltag = 'span', $attr = 'class' ){
    $string    = trim( $string );
    $attrvalue = strtolower( str_replace(" ", '-', $string ) );
    $attrvalue = preg_replace("/[^A-Za-z0-9 -]/", '', $attrvalue);
    return '<'.$htmltag.' '.$attr.' ="'.$attrvalue.'" >'.$string.'</'.$htmltag.'>';
}
function wpcargo_user_roles_list(){
    $wpcargo_user_roles_list = apply_filters( 'wpcargo_user_roles_list', array(
        'administrator', 'wpcargo_manager', 'wpc_bookings_cordinator', 'wpcargo_driver', 'wpcargo_client', 'wpcargo_agent'
    ) );
    return $wpcargo_user_roles_list;
}
function wpcargo_has_registered_shipper(){
    global $wpdb;
    $sql = "SELECT tbl2.meta_value FROM `{$wpdb->prefix}posts` AS tbl1 INNER JOIN `{$wpdb->prefix}postmeta` AS tbl2 ON tbl1.ID = tbl2.post_id WHERE tbl1.post_status LIKE 'publish' AND tbl1.post_type LIKE 'wpcargo_shipment' AND tbl2.meta_key LIKE 'registered_shipper' AND ( tbl2.meta_value IS NOT NULL AND tbl2.meta_value <> '' ) GROUP BY tbl2.meta_value";
    $result = $wpdb->get_col($sql);
    return $result;
}
function wpcargo_email_shortcodes_list(){
    $tags = array(
        '{wpcargo_tracking_number}' => esc_html__('Tracking Number','wpcargo'),
        '{wpcargo_shipper_email}'   => esc_html__('Shipper Email','wpcargo'),
        '{wpcargo_receiver_email}'  => esc_html__('Receiver Email','wpcargo'),
        '{wpcargo_shipper_phone}'   => esc_html__('Shipper Phone','wpcargo'),
        '{wpcargo_receiver_phone}'  => esc_html__('Receiver Phone','wpcargo'),
        '{admin_email}'             => esc_html__('Admin Email','wpcargo'),
        '{wpcargo_shipper_name}'    => esc_html__('Name of the Shipper','wpcargo'),
        '{wpcargo_receiver_name}'   => esc_html__('Name of the Receiver','wpcargo'),
        '{status}'                  => esc_html__('Shipment Status','wpcargo'),
        '{site_name}'               => esc_html__('Website Name','wpcargo'),
        '{site_url}'                => esc_html__('Website URL','wpcargo'),
    );
    $tags   = apply_filters( 'wpc_email_meta_tags', $tags );
    return $tags;
}
function wpcargo_defualt_status(){
    $status = array(
        esc_html__( 'Pending', 'wpcargo' ),
        esc_html__( 'Picked up', 'wpcargo' ),
        esc_html__( 'On Hold', 'wpcargo' ),
        esc_html__( 'Out for delivery', 'wpcargo' ),
        esc_html__( 'In Transit', 'wpcargo' ),
        esc_html__( 'Enroute', 'wpcargo' ),
        esc_html__( 'Cancelled', 'wpcargo' ),
        esc_html__( 'Delivered', 'wpcargo' ),
        esc_html__( 'Returned', 'wpcargo' )
    );
    return apply_filters( 'wpcargo_defualt_status', $status );
}
function wpcargo_field_generator( $field_data, $field_meta, $value = '', $class='' ){
	$required = $field_data['required'] == 'true' ? 'required' : '';
	if( $field_data['field'] == 'textarea' ){
		$field = '<textarea class="'.$class.'" name="'.$field_meta.'" '.$required.'>'.$value.'</textarea>';
	}elseif( $field_data['field'] == 'select' ){
		$field = '<select class="'.$class.'" name="'.$field_meta.'" '.$required.'>';
		$field .= '<option value="">'.esc_html__('-- Select Type --','wpcargo').'</option>';
		if( !empty( $field_data['options'] ) ){
			foreach ( $field_data['options'] as $_value) {
				$field .= '<option value="'.trim($_value).'" '.selected( $value, trim($_value), false ).'>'.trim($_value).'</option>';
			}
		}
		$field .= '</select>';
	}elseif( $field_data['field'] == 'radio' ){
		if( !empty( $field_data['options'] ) ){
			$field = '';
			foreach ( $field_data['options'] as $_value) {
				$field .= '<p><input class="'.$class.'" id="'.$field_meta.'_'.$_value.'" type="'.$field_data['field'].'" name="'.$field_meta.'" value="'.$_value.'" '.$required.'>';
				$field .= '<label for="'.$field_meta.'_'.$_value.'">'.$_value.'</label></p>';
			}
		}
	}elseif( $field_data['field'] == 'checkbox' ){
		$field .= '<p><input class="'.$class.'" id="'.$field_meta.'" onclick="toggle_dimentions(this,3,9)" style="width: 14px; height: 14px;" type="checkbox"'.(($value=="on")?"checked":"").' name="'.$field_meta.'" value="on" '.$required.'>';
		$field .= '<label for="'.$field_meta.'">'.$field_data['label'].'</label></p>';

	}elseif( $field_data['field'] == 'number' ){
		$field = '<input class="'.$class.'" type="'.$field_data['field'].'" name="'.$field_meta.'" value="'.$value.'" '.$required.' step="0.01">';
	}else{
		$field = '<input class="'.$class.'" type="'.$field_data['field'].'" name="'.$field_meta.'" value="'.$value.'" '.$required.'>';
	}
	return $field;
}
function wpcargo_email_replace_shortcodes_list( $post_id ){
    $delimiter = array("{", "}");
    $replace_shortcodes = array();
    if( !empty( wpcargo_email_shortcodes_list() ) ){
        foreach ( wpcargo_email_shortcodes_list() as $shortcode => $shortcode_label ) {
            $shortcode = trim( str_replace( $delimiter, '', $shortcode ) );
            if( $shortcode == 'wpcargo_tracking_number' ){
                $replace_shortcodes[] = get_the_title($post_id);
            }elseif( $shortcode == 'admin_email' ){
                $replace_shortcodes[] = apply_filters( 'wpcargo_admin_notification_email_address', get_option('admin_email') );
            }elseif( $shortcode == 'site_name' ){
                $replace_shortcodes[] = get_bloginfo('name');
            }elseif( $shortcode == 'site_url' ){
                $replace_shortcodes[] = get_bloginfo('url');
            }elseif( $shortcode == 'status' ){
                $replace_shortcodes[] = get_post_meta( $post_id, 'wpcargo_status', true );
            }else{
                $meta_value = maybe_unserialize( get_post_meta( $post_id, $shortcode, true ) );
                if( is_array( $meta_value ) ){
                    $meta_value = implode(', ',$meta_value );
                }
                $replace_shortcodes[] = $meta_value;
            }
        }
    }
    return $replace_shortcodes;
}  
function wpcargo_shipper_meta_filter(){
    return apply_filters( 'wpcargo_shipper_meta_filter', 'wpcargo_shipper_name');
} 
function wpcargo_shipper_label_filter(){
    return apply_filters( 'wpcargo_shipper_label_filter', esc_html__('Shipper Name', 'wpcargo' ) );
} 
function wpcargo_receiver_meta_filter(){
    return apply_filters( 'wpcargo_receiver_meta_filter', 'wpcargo_receiver_name' );
} 
function wpcargo_receiver_label_filter(){
    return apply_filters( 'wpcargo_receiver_label_filter', esc_html__('Receiver Name', 'wpcargo' ) );
} 
function wpcargo_default_client_email_body(){
    ob_start();
    ?>
    <p>Dear {wpcargo_shipper_name},</p>
    <p style="font-size: 1em;margin:.5em 0px;line-height: initial;">We are pleased to inform you that your shipment has now cleared customs and is now {status}.</p>
    <br />
    <h4 style="font-size: 1.2em;">Tracking Information</h4>
    <p style="font-size: 1em;margin:.5em 0px;line-height: initial;">Tracking Number - {wpcargo_tracking_number}</p>
    <p style="font-size: 1em;margin:.5em 0px;line-height: initial;">Latest International Scan: Customs status updated</p>
    <p style="font-size: 1em;margin:.5em 0px;line-height: initial;">We hope this meets with your approval. Please do not hesitate to get in touch if we can be of any further assistance.</p>
    <br />
    <p style="font-size: 1em;margin:.5em 0px;line-height: initial;">Yours sincerely</p>
    <p style="font-size: 1em;margin:.5em 0px;line-height: initial;"><a href="{site_url}">{site_name}</a></p>
    <?php
    $output = ob_get_clean();
    return $output;
}
function wpcargo_default_admin_email_body(){
    ob_start();
    ?>
    <p>Dear Admin,</p>
    <p>Shipment number {wpcargo_tracking_number} has been updated to {status}.</p>
    <br />
    <p>Yours sincerely</p>
    <p><a href="{site_url}">{site_name}</a></p>
    <?php
    $output = ob_get_clean();
    return $output;
}
function wpcargo_default_email_footer(){
    ob_start();
    ?>
    <div class="wpc-contact-info" style="margin-top: 10px;">
        <p style="font-size: 1em;margin:.5em 0px;line-height: initial;">Your Address Here...</p>
        <p style="font-size: 1em;margin:.5em 0px;line-height: initial;">Email: <a href="mailto:{admin_email}">{admin_email}</a> - Web: <a href="{site_url}">{site_name}</a></p>
        <p style="font-size: 1em;margin:.5em 0px;line-height: initial;">Phone: <a href="tel:">Your Phone Number Here</a>, <a href="tel:">Your Phone Number Here</a></p>
    </div>
    <div class="wpc-contact-bottom" style="margin-top: 2em; padding: 1em; border-top: 1px solid #000;">
        <p style="font-size: 1em;margin:.5em 0px;line-height: initial;">This message is intended solely for the use of the individual or organisation to whom it is addressed. It may contain privileged or confidential information. If you have received this message in error, please notify the originator immediately. If you are not the intended recipient, you should not use, copy, alter or disclose the contents of this message. All information or opinions expressed in this message and/or any attachments are those of the author and are not necessarily those of {site_name} or its affiliates. {site_name} accepts no responsibility for loss or damage arising from its use, including damage from virus.</p>
    </div>
    <?php
    $output = ob_get_clean();
    return $output;
}
function wpcargo_email_body_container( $email_body = '', $email_footer = '' ){
    global $wpcargo;
    $default_logo       = WPCARGO_PLUGIN_URL.'admin/assets/images/wpcargo-logo-email.png';
    $brand_logo         = !empty( $wpcargo->logo ) ? $wpcargo->logo : $default_logo;
    ob_start();
    ?>
    <div class="wpc-email-notification-wrap" style="width: 100%; font-family: sans-serif;">
        <div class="wpc-email-notification" style="padding: 3em; background: #efefef;">
            <div class="wpc-email-template" style="background: #fff; width: 95%; margin: 0 auto;">
                <div class="wpc-email-notification-logo" style="padding: 2em 2em 0px 2em;">
                    <table width="100%" style="max-width:210px;"><tr><td><img src="<?php echo $brand_logo; ?>" width="100%"/></td></tr></table>
                </div>
                <div class="wpc-email-notification-content" style="padding: 2em 2em 1em 2em; font-size: 18px;">
                    <?php echo $email_body; ?>
                </div>
                <div class="wpc-email-notification-footer" style="font-size: 10px; text-align: center; margin: 0 auto;">
                    <?php do_action( 'wpcargo_email_footer_divider' ); ?>
                    <?php echo $email_footer; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $output = ob_get_clean();
    return $output;
}
function wpcargo_send_email_notificatio( $post_id, $status = '' ){
    wpcargo_client_mail_notification( $post_id, $status );
    wpcargo_admin_mail_notification( $post_id, $status );
}
function wpcargo_client_mail_notification( $post_id, $status = '' ){
    global $wpcargo;
    $wpcargo_mail_domain = !empty( trim( get_option('wpcargo_mail_domain') ) ) ? get_option('wpcargo_mail_domain') : get_option( 'admin_email' ) ;
    if ( $wpcargo->client_mail_active ) {
        $old_status     = get_post_meta($post_id, 'wpcargo_status', true);
        $str_find       = array_keys( wpcargo_email_shortcodes_list() );
        $str_replce     = wpcargo_email_replace_shortcodes_list( $post_id );
        $mail_content   = $wpcargo->client_mail_body;
        $mail_footer    = $wpcargo->client_mail_footer;
        $headers        = array();
        $headers[]      = 'From: ' . get_bloginfo('name') .' <'.$wpcargo_mail_domain.'>';
        if( $wpcargo->mail_cc ){
            $headers[]      = 'cc: '.str_replace($str_find, $str_replce, $wpcargo->mail_cc )."\r\n";
        }
        if( $wpcargo->mail_bcc ){
            $headers[]      = 'Bcc: '.str_replace($str_find, $str_replce, $wpcargo->mail_bcc )."\r\n";
        }
        $subject        = str_replace($str_find, $str_replce, $wpcargo->client_mail_subject );
        $send_to        = str_replace($str_find, $str_replce, $wpcargo->client_mail_to );
        $message        = str_replace($str_find, $str_replce, wpcargo_email_body_container( $mail_content, $mail_footer ) );     
        if( empty( $wpcargo->mail_status ) ){
            wp_mail( $send_to, $subject, $message, $headers );
        }elseif( !empty( $wpcargo->mail_status ) && in_array( $status, $wpcargo->mail_status) ){
            wp_mail( $send_to, $subject, $message, $headers );
        }   
    }
}
function wpcargo_admin_mail_notification( $post_id, $status = ''){
    global $wpcargo;
    $wpcargo_mail_domain = !empty( trim( get_option('wpcargo_admin_mail_domain') ) ) ? get_option('wpcargo_admin_mail_domain') : get_option( 'admin_email' ) ;
    if ( $wpcargo->admin_mail_active ) {
        $str_find       = array_keys( wpcargo_email_shortcodes_list() );
        $str_replce     = wpcargo_email_replace_shortcodes_list( $post_id );
        $mail_content   = $wpcargo->admin_mail_body;
        $mail_footer    = $wpcargo->admin_mail_footer;
        $headers        = array();
        $headers[]      = 'From: ' . get_bloginfo('name') .' <'.$wpcargo_mail_domain.'>';
        $subject        = str_replace($str_find, $str_replce, $wpcargo->admin_mail_subject );
        $send_to        = str_replace($str_find, $str_replce, $wpcargo->admin_mail_to );
        $message        = str_replace($str_find, $str_replce, wpcargo_email_body_container( $mail_content, $mail_footer ) );      
        if( empty( $wpcargo->admin_mail_status ) ){
            wp_mail( $send_to, $subject, $message, $headers );
        }elseif( !empty( $wpcargo->admin_mail_status ) && in_array( $status, $wpcargo->admin_mail_status) ){
            wp_mail( $send_to, $subject, $message, $headers );
        }   
    }
}
function wpcargo_pagination( $args = array() ) {
    $defaults = array(
        'range'           => 4,
        'custom_query'    => FALSE,
        'previous_string' => esc_html__( 'Previous', 'wpcargo' ),
        'next_string'     => esc_html__( 'Next', 'wpcargo' ),
        'before_output'   => '<div id="wpcargo-pagination-wrapper"><nav class="wpcargo-pagination post-nav" aria-label="'.esc_html__('Shipments', 'wpcargo').'"><ul class="wpcargo-pagination pg-blue justify-content-center">',
        'after_output'    => '</ul></nav</div>'
    );
    $args = wp_parse_args( 
        $args, 
        apply_filters( 'wpcargo_pagination_defaults', $defaults )
    );    
    $args['range'] = (int) $args['range'] - 1;
    if ( !$args['custom_query'] )
        $args['custom_query'] = @$GLOBALS['wp_query'];
    $count = (int) $args['custom_query']->max_num_pages;
    $page  = intval( get_query_var( 'paged' ) );
    $ceil  = ceil( $args['range'] / 2 );    
    if ( $count <= 1 )
        return FALSE;    
    if ( !$page )
        $page = 1;    
    if ( $count > $args['range'] ) {
        if ( $page <= $args['range'] ) {
            $min = 1;
            $max = $args['range'] + 1;
        } elseif ( $page >= ($count - $ceil) ) {
            $min = $count - $args['range'];
            $max = $count;
        } elseif ( $page >= $args['range'] && $page < ($count - $ceil) ) {
            $min = $page - $ceil;
            $max = $page + $ceil;
        }
    } else {
        $min = 1;
        $max = $count;
    }    
    $echo = '';
    $previous = intval($page) - 1;
    $previous = esc_attr( get_pagenum_link($previous) );    
    $firstpage = esc_attr( get_pagenum_link(1) );
    if ( $firstpage && (1 != $page) )
        $echo .= '<li class="previous wpcargo-page-item"><a class="wpcargo-page-link waves-effect waves-effect" href="' . $firstpage . '">' . esc_html__( 'First', 'wpcargo' ) . '</a></li>';
    if ( $previous && (1 != $page) )
        $echo .= '<li class="wpcargo-page-item" ><a class="wpcargo-page-link waves-effect waves-effect" href="' . $previous . '" title="' . esc_html__( 'previous', 'wpcargo') . '">' . $args['previous_string'] . '</a></li>';
    if ( !empty($min) && !empty($max) ) {
        for( $i = $min; $i <= $max; $i++ ) {
            if ($page == $i) {
                $echo .= '<li class="wpcargo-page-item active"><span class="wpcargo-page-link waves-effect waves-effect">' . str_pad( (int)$i, 2, '0', STR_PAD_LEFT ) . '</span></li>';
            } else {
                $echo .= sprintf( '<li class="wpcargo-page-item"><a class="wpcargo-page-link waves-effect waves-effect" href="%s">%002d</a></li>', esc_attr( get_pagenum_link($i) ), $i );
            }
        }
    }    
    $next = intval($page) + 1;
    $next = esc_attr( get_pagenum_link($next) );
    if ($next && ($count != $page) )
        $echo .= '<li class="wpcargo-page-item"><a class="wpcargo-page-link waves-effect waves-effect" href="' . $next . '" title="' . esc_html__( 'next', 'wpcargo') . '">' . $args['next_string'] . '</a></li>';
    $lastpage = esc_attr( get_pagenum_link($count) );
    if ( $lastpage ) {
        $echo .= '<li class="next wpcargo-page-item"><a class="wpcargo-page-link waves-effect waves-effect" href="' . $lastpage . '">' . esc_html__( 'Last', 'wpcargo' ) . '</a></li>';
    }
    if ( isset($echo) ){
        echo $args['before_output'] . $echo . $args['after_output'];
    }
}
if( !function_exists( 'wpcargo_country_list' )){
    function wpcargo_country_list(){
        return "Afghanistan, Albania, Algeria, American Samoa, Andorra, Angola, Anguilla, Antigua & Barbuda, Argentina, Armenia, Aruba, Australia, Austria, Azerbaijan, Bahamas, The, Bahrain, Bangladesh, Barbados, Belarus, Belgium, Belize, Benin, Bermuda, Bhutan, Bolivia, Bosnia & Herzegovina, Botswana, Brazil, British Virgin Is., Brunei, Bulgaria, Burkina Faso, Burma, Burundi, Cambodia, Cameroon, Canada, Cape Verde, Cayman Islands, Central African Rep., Chad, Chile, China, Colombia, Comoros, Congo, Dem. Rep., Congo, Repub. of the, Cook Islands, Costa Rica, Cote d'Ivoire, Croatia, Cuba, Cyprus, Czech Republic, Denmark, Djibouti, Dominica, Dominican Republic, East Timor, Ecuador, Egypt, El Salvador, Equatorial Guinea, Eritrea, Estonia, Ethiopia, Faroe Islands, Fiji, Finland, France, French Guiana, French Polynesia, Gabon, Gambia, The, Gaza Strip, Georgia, Germany, Ghana, Gibraltar, Greece, Greenland, Grenada, Guadeloupe, Guam, Guatemala, Guernsey, Guinea, Guinea-Bissau, Guyana, Haiti, Honduras, Hong Kong, Hungary, Iceland, India, Indonesia, Iran, Iraq, Ireland, Isle of Man, Israel, Italy, Jamaica, Japan, Jersey, Jordan, Kazakhstan, Kenya, Kiribati, Korea, North, Korea, South, Kuwait, Kyrgyzstan, Laos, Latvia, Lebanon, Lesotho, Liberia, Libya, Liechtenstein, Lithuania, Luxembourg, Macau, Macedonia, Madagascar, Malawi, Malaysia, Maldives, Mali, Malta, Marshall Islands, Martinique, Mauritania, Mauritius, Mayotte, Mexico, Micronesia, Fed. St., Moldova, Monaco, Mongolia, Montserrat, Morocco, Mozambique, Namibia, Nauru, Nepal, Netherlands, Netherlands Antilles, New Caledonia, New Zealand, Nicaragua, Niger, Nigeria, N. Mariana Islands, Norway, Oman, Pakistan, Palau, Panama, Papua New Guinea, Paraguay, Peru, Philippines, Poland, Portugal, Puerto Rico, Qatar, Reunion, Romania, Russia, Rwanda, Saint Helena, Saint Kitts & Nevis, Saint Lucia, St Pierre & Miquelon, Saint Vincent and the Grenadines, Samoa, San Marino, Sao Tome & Principe, Saudi Arabia, Senegal, Serbia, Seychelles, Sierra Leone, Singapore, Slovakia, Slovenia, Solomon Islands, Somalia, South Africa, Spain, Sri Lanka, Sudan, Suriname, Swaziland, Sweden, Switzerland, Syria, Taiwan, Tajikistan, Tanzania, Thailand, Togo, Tonga, Trinidad & Tobago, Tunisia, Turkey, Turkmenistan, Turks & Caicos Is, Tuvalu, Uganda, Ukraine, United Arab Emirates, United Kingdom, United States, Uruguay, Uzbekistan, Vanuatu, Venezuela, Vietnam, Virgin Islands, Wallis and Futuna, West Bank, Western Sahara, Yemen, Zambia, Zimbabwe";
    }
}
function wpcargo_map_script( $callback ){
	$shmap_api = get_option('shmap_api');
	return '<script async defer src="https://maps.googleapis.com/maps/api/js?libraries=geometry,places,visualization&key='.$shmap_api.'&callback='.$callback.'"></script>';
}
function wpcargo_brand_name(){
	return apply_filters('wpcargo_brand_name', esc_html__('Settings', 'wpcargo' ) );
}
function wpcargo_general_settings_label(){
	return apply_filters('wpcargo_general_settings_label', esc_html__('General Settings', 'wpcargo' ) );
}
function wpcargo_client_email_settings_label(){
	return apply_filters('wpcargo_email_settings_label', esc_html__('Client Email Settings', 'wpcargo' ) );
}
function wpcargo_admin_email_settings_label(){
    return apply_filters('wpcargo_admin_email_settings_label', esc_html__('Admin Email Settings', 'wpcargo' ) );
}
function wpcargo_shipment_settings_label(){
	return apply_filters('wpcargo_shipment_settings_label', esc_html__('Shipment Settings', 'wpcargo' ) );
}
function wpcargo_report_settings_label(){
	return apply_filters('wpcargo_report_settings_label', esc_html__('Reports', 'wpcargo' ) );
}
function wpcargo_map_settings_label(){
	return apply_filters('wpcargo_map_settings_label', esc_html__('Map Settings', 'wpcargo' ) );
}
function wpcargo_print_layout_label(){
	return apply_filters('wpcargo_print_layout_label', esc_html__('Print Layout', 'wpcargo' ) );
}
function wpcargo_shipment_label(){
	return apply_filters('wpcargo_shipment_label', esc_html__('Shipment Label', 'wpcargo' ) );
}
function wpcargo_shipment_details_label(){
    return apply_filters('wpcargo_shipment_details_label', esc_html__('Shipment Details', 'wpcargo' ) );
}
function wpcargo_history_fields(){
	global $wpcargo;
    $history_fields = array(
        'date' => array(
            'label' => esc_html__('Date', 'wpcargo'),
            'field' => 'text',
            'required' => 'false',
            'options' => array()
        ),
        'time' => array(
            'label' => esc_html__('Time', 'wpcargo'),
            'field' => 'text',
            'required' => 'false',
            'options' => array()
        ),
        'location' => array(
            'label' => esc_html__('Location', 'wpcargo'),
            'field' => 'text',
            'required' => 'false',
            'options' => array()
        ),
        'status' => array(
            'label' => esc_html__('Status', 'wpcargo'),
            'field' => 'select',
            'required' => 'false',
            'options' => $wpcargo->status
        ),
        'updated-name' => array(
            'label' => esc_html__('Updated By', 'wpcargo'),
            'field' => 'text',
            'required' => 'false',
            'options' => array()
        ),
        'remarks' => array(
            'label' => esc_html__('Remarks', 'wpcargo'),
            'field' => 'textarea',
            'required' => 'false',
            'options' => array()
        ),
    );
    return apply_filters( 'wpcargo_history_fields', $history_fields );
}
function wpcargo_default_shipment_info(){
	$shipment_info = array(
						'wpcargo_type_of_shipment'	=> esc_html__('Type of Shipment', 'wpcargo'),
						'wpcargo_courier'			=> esc_html__('Courier', 'wpcargo'),
						'wpcargo_carrier_ref_number'	=> esc_html__('Carrier Reference No.', 'wpcargo'),
						'wpcargo_mode_field'			=> esc_html__('Mode', 'wpcargo'),
						'wpcargo_carrier_field'			=> esc_html__('Carrier', 'wpcargo'),
						'wpcargo_packages'				=> esc_html__('Packages', 'wpcargo'),
						'wpcargo_product'				=> esc_html__('Product', 'wpcargo'),
						'wpcargo_weight'				=> esc_html__('Weight', 'wpcargo'),
						'wpcargo_qty'					=> esc_html__('Quantity', 'wpcargo'),
						'wpcargo_total_freight'			=> esc_html__('Total Freight', 'wpcargo'),
						'payment_wpcargo_mode_field'	=> esc_html__('Payment oode', 'wpcargo'),
						'wpcargo_origin_field'			=> esc_html__('Origin', 'wpcargo'),
						'wpcargo_pickup_date_picker'	=> esc_html__('Pickup Date', 'wpcargo'),
						'wpcargo_destination'			=> esc_html__('Destination', 'wpcargo'),
						'wpcargo_departure_time_picker' => esc_html__('Departure Time', 'wpcargo'),
						'wpcargo_pickup_time_picker'	=> esc_html__('Pickup Time', 'wpcargo'),
						'wpcargo_expected_delivery_date_picker' => esc_html__('Expected Delivery Date', 'wpcargo'),
					);
	return apply_filters( 'wpcargo_default_shipment_info', $history_fields );
}
function wpcargo_assign_shipment_email( $post_id, $user_id, $designation ){
    global  $wpcargo;
    $user_info      = get_userdata( $user_id );
    // Check if user exist 
    if( !$user_info ){
        return false;
    }
	$str_find       = array_keys( wpcargo_email_shortcodes_list() );
	$str_replce     = wpcargo_email_replace_shortcodes_list( $post_id );
	$wpcargo_mail_domain = !empty( trim( get_option('wpcargo_admin_mail_domain') ) ) ? get_option('wpcargo_admin_mail_domain') : get_option( 'admin_email' ) ;
    $user_email     = $user_info->user_email;
	$headers        = array('Content-Type: text/html; charset=UTF-8');
    $headers[]      = esc_html__('From: ', 'wpcargo' ) . get_bloginfo('name') .' <'.$wpcargo_mail_domain.'>';
    $mail_footer    = $wpcargo->client_mail_footer;
	ob_start();
		?>
		<p><?php esc_html_e( 'Dear', 'wpcargo' ); ?> <?php echo $wpcargo->user_fullname( $user_id ); ?>,</p>
        <p><?php echo esc_html__( 'Shipment number ', 'wpcargo' ).get_the_title( $post_id ).esc_html__( ' has been assigned to you.', 'wpcargo' ); ?></p>
		<?php
	$mail_content = ob_get_clean();
    $message        = str_replace($str_find, $str_replce, wpcargo_email_body_container( $mail_content, $mail_footer ) ); 
    $subject        = esc_html__( 'Assign Shipment Notification', 'wpcargo' ).' ['.$designation.']';
	wp_mail( $user_email, $subject, $message, $headers );
}
function wpc_can_send_email_agent(){
	$gen_settings = get_option( 'wpcargo_option_settings' );
	$email_agent = !array_key_exists('wpcargo_email_agent', $gen_settings ) ? true : false;
	return $email_agent;
}
function wpc_can_send_email_employee(){
	$gen_settings = get_option( 'wpcargo_option_settings' );
	$email_employee = !array_key_exists('wpcargo_email_employee', $gen_settings ) ? true : false;
	return $email_employee;
}
function wpc_can_send_email_client(){
	$gen_settings = get_option( 'wpcargo_option_settings' );
	$email_client = !array_key_exists('wpcargo_email_client', $gen_settings ) ? true : false;
	return $email_client;
}
function wpc_get_coming_trips($origin_country="",$origin_city="",$dest_country="",$dest_city=""){
    global $wpdb;
    $trips = $wpdb->get_results("SELECT * FROM trips WHERE routes_data LIKE '%\"".$origin_city."-".$dest_city."\"%' OR routes_data LIKE '%\"".$dest_city."-".$origin_city."\"%' ORDER BY trip_date ASC ");
     $c=0;
      $rtn=array();
      foreach($trips as $trip){
       $rs = unserialize($trip->routes_data); //unserialize('a:3:{i:0;a:4:{s:8:"route_id";s:1:"4";s:10:"route_name";s:19:"Johannesburg-Maseru";s:12:"late_cut_off";s:19:"2020-09-09 17:00:00";s:13:"final_cut_off";s:19:"2020-09-10 12:00:00";}i:1;a:4:{s:8:"route_id";s:1:"2";s:10:"route_name";s:19:"Bloemfontein-Maseru";s:12:"late_cut_off";s:19:"2020-09-10 17:00:00";s:13:"final_cut_off";s:19:"2020-09-11 12:00:00";}i:2;a:4:{s:8:"route_id";s:1:"3";s:10:"route_name";s:16:"Ladybrand-Maseru";s:12:"late_cut_off";s:19:"2020-09-11 12:00:00";s:13:"final_cut_off";s:19:"2020-09-11 17:00:00";}}');
       $now = date('Y-m-d H:i:s');
       foreach($rs AS $ss){
         if(($ss["route_name"]==$origin_city."-".$dest_city || $ss["route_name"]==$dest_city."-".$origin_city) && $ss["final_cut_off"] >= $now ){
           $rtn[] = $trip;
           $c++;
         }}
        if($c==2) break;
        }

    return $rtn;
}
function wpc_get_trip($trip_id){
    global $wpdb;
    $trip = $wpdb->get_results( "SELECT * FROM routes JOIN trips ON routes.id IN(trips.routes_ids) WHERE id = '$trip_id'");
    return $trip;
}
function trip_route_dates($o_city,$d_city,$trip_routes){
    $dates = array();
    foreach($trip_routes AS $route){
        if(trim($route['route_name'])==$o_city."-".$d_city || trim($route['route_name'])==$d_city."-".$o_city){
          $dates = $route; break;
       }
    }
    return($dates);
}

function wpc_get_countries_cities($condition="",$country="",$city=""){
    global $wpdb;  $where="";
    if(!empty($country) && !empty($city)) $where = 'WHERE country_name like "'.$country.'" AND city_name like "'.$city.'"';
    else if(!empty($country)) $where = 'WHERE country_name like "'.$country.'"';
	$query = 'SELECT * FROM countries_cities '.$where.' '.$condition;
	$countries =  $wpdb->get_results( $query);
	return $countries;
}
//gets all routes
function wpc_get_prices(){
    global $wpdb;
    $where = "";
    $pricing = $wpdb->get_results( "SELECT * FROM routes ".$where);
    return $pricing;
}

function wpc_get_route_prices($origin_country="",$origin_city="",$dest_country="",$dest_city=""){
    global $wpdb;
    $where = " WHERE (origin_country = '$origin_country' AND origin_city = '$origin_city' AND dest_country = '$dest_country' AND dest_city = '$dest_city') OR (origin_country = '$dest_country' AND origin_city = '$dest_city' AND dest_country = '$origin_country' AND dest_city = '$origin_city') ";
    $pricing = $wpdb->get_results( "SELECT * FROM routes ".$where);
    return $pricing[0];
}
function get_driver_names($driver_id){
  $last_name = get_the_author_meta( 'last_name', $driver_id );
  $first_name = get_the_author_meta( 'first_name', $driver_id );
  $driver="";
  if( !empty( $last_name ) && !empty( $first_name ) ){
  	$driver = $first_name.' '.$last_name;
  }else{
  	$driver = get_the_author_meta( 'display_name', $driver_id);
  }
   return $driver;
}
function get_settings_items(){
  global $wpdb;
  $items = $wpdb->get_results( "SELECT * FROM other_settings WHERE meta_key='items'");
  return $items[0];
}
function general_pricing_items($elm="kgs"){
  if($elm=="kgs")
        $items = array(
                'docscost'=> array("label"=> esc_html__('Documents', 'wpcargo'),"price_type"=>"closed"),
                'kg5'=> array("label"=>esc_html__('0-5kg', 'wpcargo'),"price_type"=>"closed"),
                'kg10'=> array("label"=>esc_html__('6-10kg', 'wpcargo'),"price_type"=>"closed"),
                'kg25'=> array("label"=>esc_html__('11-25kg', 'wpcargo'),"price_type"=>"open"),
                'kg50'=> array("label"=>esc_html__('26-50kg', 'wpcargo'),"price_type"=>"open"),
                'kg100'=> array("label"=>esc_html__('51-100kg', 'wpcargo'),"price_type"=>"open"),
                'kg250'=> array("label"=>esc_html__('101-250kg', 'wpcargo'),"price_type"=>"open"),
                'kg500'=> array("label"=>esc_html__('251-500kg', 'wpcargo'),"price_type"=>"open"),
                'kg1000'=> array("label"=>esc_html__('500kg-1000kg', 'wpcargo'),"price_type"=>"open"),
                'kg2000'=> array("label"=>esc_html__('1-2ton', 'wpcargo'),"price_type"=>"closed"),
                'kg4000'=> array("label"=>esc_html__('2-4ton', 'wpcargo'),"price_type"=>"closed"),
                'kg8000'=> array("label"=>esc_html__('4-8ton', 'wpcargo'),"price_type"=>"closed"),
                'kg12000'=> array("label"=>esc_html__('8-12ton', 'wpcargo'),"price_type"=>"closed"),
                'kg16000'=> array("label"=>esc_html__('12-16ton', 'wpcargo'),"price_type"=>"closed"),
                'kg20000'=> array("label"=>esc_html__('16-20ton', 'wpcargo'),"price_type"=>"closed"),
        );
  else if($elm=="cbms")
        $items = array(
                'docscost'=> array("label"=>esc_html__('Documents', 'wpcargo'),"price_type"=>"closed"),
                'cbm1'=> array("label"=>esc_html__('0-1cbm', 'wpcargo'),"price_type"=>"closed"),
                'cbm3'=> array("label"=>esc_html__('1-3cbm', 'wpcargo'),"price_type"=>"open"),
                'cbm6'=> array("label"=>esc_html__('3-6cbm', 'wpcargo'),"price_type"=>"open"),
                'cbm10'=> array("label"=>esc_html__('6-10cbm', 'wpcargo'),"price_type"=>"open"),
                'cbm15'=> array("label"=>esc_html__('10-15cbm', 'wpcargo'),"price_type"=>"open"),
                'cbm20'=> array("label"=>esc_html__('15-20cbm', 'wpcargo'),"price_type"=>"open"),
                'cbm25'=> array("label"=>esc_html__('20-25cbm', 'wpcargo'),"price_type"=>"open"),
                'cbm30'=> array("label"=>esc_html__('FCL 20"', 'wpcargo'),"price_type"=>"closed"),
                'cbm40'=> array("label"=>esc_html__('FCL 40"', 'wpcargo'),"price_type"=>"closed")
        );
  return $items;
}
function generate_freight_cost($weight,$prices_array,$item_type="kg"){
  global $wpdb;
  if($item_type == "docs") {
       $costs = array('qty'=>1,'unit_cost'=>$prices_array[$item_type.'cost'],'total_cost'=>$prices_array[$item_type.'cost']);
  }
  else {
      $weight_range = "kg".$weight;
      foreach($prices_array AS $key=>$value){
          if(preg_replace('/[^0-9.]+/', '', $key)!="" && $weight <= preg_replace('/[^0-9.]+/', '', $key)){ $weight_range = $key; break; }
      }
      $unit_cost = $prices_array[$weight_range];
      $qty = $weight;
      $general_pricing_items = general_pricing_items(preg_replace('/\d/', '',$weight_range )."s");
      if($general_pricing_items[$weight_range]['price_type']=="closed"){ $qty = 1;}
      if($unit_cost=="") { $unit_cost=0;  }
      $total_cost = $qty * $unit_cost;
      $costs = array('qty'=>$qty,'unit_cost'=>$unit_cost,'total_cost'=>$total_cost);
  }
  return $costs;
}
function get_total_price($prices_array){
  $prices_array = maybe_unserialize($prices_array);
  $settings_items = unserialize(get_settings_items()->meta_data);
  $total =0;
  foreach($prices_array AS $key=>$values){
       $op_sign = ($settings_items[$key]["item_type"]=="Expenditure") ? "-": "";
       $total = ($op_sign=="-")? $total-(float)str_replace(",","",$values['total']) : $total+(float)str_replace(",","",$values['total']);
  }
  return $total;
}
function create_variable($item_name){
  return strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $item_name));
}
function status_breakdown($shipment_status){
      $status_array = array(
           'Pending' => array(
                          'Collection Address Approval',
                          'Payment Approval'
                         ),
           'Active' => array(
                          'Collection',
                          'Cargo at Origin Depot',
                          'Cargo in Transit',
                          'Cargo at Destination Depot',
                          'Goods Inspection',
                          'Invoicing',
                          'Delivery'
                         ),
           'Complete' => array()
          );
      $status_breakdown = $status_array[$shipment_status];
      return $status_breakdown;
}
function in_array_r($item , $array){
    return preg_match('/"'.preg_quote($item, '/').'"/i' , json_encode($array));
}
function get_latest_substatus($shipments_history){
    $sub_status = maybe_unserialize($shipments_history);
    if(!empty($sub_status)){
       $status = $sub_status[sizeof($sub_status)-1]['status'];
        if($status=="Pending") $status = "New booking placed";
        else if($status=="Draft") $status = "Booking saved as draft";
     }
   return $status;
}
function schedule_status(){
   global $wpdb;
   $schedules = $wpdb->get_results( "SELECT * FROM collection_schedules WHERE status!='Closed' AND status!='Terminated' ORDER BY schedule_name ASC, schedule_date ASC ");
   foreach($schedules AS $schedule){
      $current_datetime = date('Y-m-d H:i:s');
      $final_cut_off = $schedule->final_cut_off;
      if($final_cut_off <= $current_datetime){
           $wpdb->update(
                'collection_schedules',
                 array(
              			'status' => 'Closed',
              		),
                 array(
          			'id' => $schedule->id
          		)
           );
      } else {}
   }
}
function update_schedules_status(){
  global $wpdb;
  $current_date = date('Y-m-d');
  $schedules = $wpdb->get_results( "SELECT * FROM collection_schedules WHERE status != 'Closed' AND schedule_date <='$current_date' ");
  foreach ( $schedules as $schedule ) {
        update_schedule_status($schedule);
   }
}
function update_schedule_status($schedule){
  global $wpdb;
    $current_datetime = date('Y-m-d H:i:s');
    $late_cut_off = $schedule->late_cut_off;
    $final_cut_off = $schedule->final_cut_off;
    $schedule_date  = $schedule->schedule_date;
    if($final_cut_off <= $current_datetime){
         $wpdb->update(
              'collection_schedules',
               array(
            			'status' => 'Closed',
            		),
               array(
        			'id' => $schedule->id
        		)
         );
    }
    else if($schedule_date <= $current_datetime){
         $wpdb->update(
              'collection_schedules',
               array(
            			'status' => 'Active',
            		),
               array(
        			'id' => $schedule->id
        		)
         );
    }
}
function update_trip_status($trip){
  global $wpdb;
    $current_datetime = date('Y-m-d H:i:s');
    $trip_id = $trip->id;
    $selected_schedules = unserialize($trip->city_schedules);
    $close_trip = "true";
    if(is_array($selected_schedules)){
      foreach($selected_schedules AS $selected_schedule){
          $trip_schedule = $wpdb->get_results( "SELECT * FROM `collection_schedules` WHERE id = '".$selected_schedule['schedule_id']."'");
          $late_cut_off = $trip_schedule[0]->late_cut_off;
          $schedule_date  = $trip_schedule[0]->schedule_date;
          $final_cut_off = $trip_schedule[0]->final_cut_off;
          if($final_cut_off <= $current_datetime) {
                 $wpdb->update(
                      'collection_schedules',
                       array(
                    			'status' => 'Closed',
                    		),
                       array(
                			'id' => $trip_schedule[0]->id
                		)
                 );
            }
            else if($schedule_date <= $current_datetime){
                 $wpdb->update(
                      'collection_schedules',
                       array(
                    			'status' => 'Active',
                    		),
                       array(
                			'id' => $trip_schedule[0]->id
                		)
                 );
              $close_trip = "false";
            }
            else {
                 $wpdb->update(
                      'collection_schedules',
                       array(
                    			'status' => 'Upcoming',
                    		),
                       array(
                			'id' => $trip_schedule[0]->id
                		)
                 );
              $close_trip = "false";
            }
    }}
    if($close_trip == "true") {
           $wpdb->update(
                  'trips',
                   array(
                			'status' => 'Closed',
                		),
                   array(
            			'id' => $trip_id
            		)
             );
    }
}

function schedule_summary($schedule_id,$trip_id='',$cond='All'){
 global $wpdb;
    if($trip_id!=""){
         $schedules_list = implode("','",get_trip_schedules($trip_id));
         $bookings1 = $wpdb->get_results("SELECT ID FROM `{$wpdb->prefix}posts` AS tbl1
                                         JOIN `{$wpdb->prefix}postmeta` AS tbl2 ON tbl1.ID = tbl2.post_id
                                         JOIN `{$wpdb->prefix}postmeta` AS tbl3 ON tbl1.ID = tbl3.post_id
                                         WHERE (tbl1.post_status LIKE 'publish' OR tbl1.post_status LIKE 'archive')
                                         AND tbl1.post_type LIKE 'wpcargo_shipment'
                                         AND (tbl2.meta_key LIKE 'collection_schedule_id' AND tbl2.meta_value LIKE '$schedule_id')
                                         AND (tbl3.meta_key LIKE 'delivery_schedule_id' AND tbl3.meta_value IN ('$schedules_list'))
         ");

         $bookings2 = $wpdb->get_results( "SELECT ID FROM `{$wpdb->prefix}posts` AS tbl1
                                         JOIN `{$wpdb->prefix}postmeta` AS tbl2 ON tbl1.ID = tbl2.post_id
                                         JOIN `{$wpdb->prefix}postmeta` AS tbl3 ON tbl1.ID = tbl3.post_id
                                         WHERE (tbl1.post_status LIKE 'publish' OR tbl1.post_status LIKE 'archive')
                                         AND tbl1.post_type LIKE 'wpcargo_shipment'
                                         AND (tbl2.meta_key LIKE 'delivery_schedule_id' AND tbl2.meta_value LIKE '$schedule_id')
                                         AND (tbl3.meta_key LIKE 'collection_schedule_id' AND tbl3.meta_value IN ('$schedules_list'))
                             ");
         $bookings = array_merge($bookings1,$bookings2);
    }
    else {
         $bookings = $wpdb->get_results( "SELECT ID FROM `{$wpdb->prefix}posts` AS tbl1 JOIN `{$wpdb->prefix}postmeta` AS tbl2 ON tbl1.ID = tbl2.post_id
                                         WHERE (tbl1.post_status LIKE 'publish' OR tbl1.post_status LIKE 'archive')
                                         AND tbl1.post_type LIKE 'wpcargo_shipment'
                                         AND ((tbl2.meta_key LIKE 'collection_schedule_id' AND tbl2.meta_value LIKE '$schedule_id')
                                              OR (tbl2.meta_key LIKE 'delivery_schedule_id' AND tbl2.meta_value LIKE '$schedule_id'))
                                              ");
    }
   $total_posts = count($bookings);  //count returned rows
   $expected_amount =0; $post_ids = array();
   $num_of_awaiting = $num_of_failed = $num_of_complete = 0;
   foreach ( $bookings as $booking) {  //for calculating sum of invoices for collection schedule
     $post_id = $booking->ID;
     $wpcargo_status = get_post_meta($post_id, 'wpcargo_status', true);
     if($cond=='All' || $wpcargo_status=="Active" || $wpcargo_status=="Complete"){
        $post_ids[] = $post_id;
        $activity = (get_post_meta($post_id, 'collection_schedule_id', true)==$schedule_id) ? "Collection": "Delivery";
        $invoices = ($activity == "Collection") ? unserialize(get_post_meta($post_id, 'wpcargo_invoice', true)) : "";
        if(is_array($invoices)){
                foreach($invoices as $invoice){
                   $expected_amount+= $invoice['total'];
                }
        }
       $wpcargo_shipments_update = get_post_meta( $post_id, 'wpcargo_shipments_update', true );
       if(strpos($wpcargo_shipments_update, $activity." Successful") != false){
            $num_of_complete +=1; }
       else if(strpos($wpcargo_shipments_update, $activity." Failed") != false){
            $num_of_failed += 1;  }
       else {
              $num_of_awaiting  += 1;
            }
      }
   }
   return array("num_of_posts"=>$total_posts, "invoices_total_amount"=>$expected_amount, "post_ids_list"=>$post_ids, 'num_of_awaiting'=>$num_of_awaiting,'num_of_failed'=>$num_of_failed,'num_of_complete'=>$num_of_complete);
}

function get_trip_schedules($trip_id) {
       global $wpdb;
       $trips = $wpdb->get_results( "SELECT city_schedules FROM trips WHERE id='$trip_id'");
       $trip_schedules = unserialize($trips[0]->city_schedules);
       foreach($trip_schedules AS $trip_schedule){
               $schedules_list[] = $trip_schedule['schedule_id'];
          }
       $schedule_city = $wpdb->get_results( "SELECT id FROM `countries_cities` WHERE city_name = 'Maseru' ");
       $maseru_id = $schedule_city[0]->id;
       $selected_schedule = $wpdb->get_results("SELECT id FROM collection_schedules WHERE schedule_city = '$maseru_id'");
       $schedules_list[] = $selected_schedule[0]->id;
       //print_r($schedules_list);
      return $schedules_list;
}
function get_shipper_name($shipment_id){
       $company = get_post_meta( $shipment_id, 'wpcargo_receiver_company', true );
       $fname = get_post_meta( $shipment_id, 'wpcargo_receiver_fname', true );
       $sname = get_post_meta( $shipment_id, 'wpcargo_receiver_sname', true );
       $contact_person = (!empty($fname) && !empty($sname)) ? $fname.",".$sname : ((!empty($fname))? $fname: $sname);
       $received_from = (!empty($company))? $company : $contact_person;
       return $received_from;
}

// Create a function for converting the amount in words

function numberTowords(float $amount)

{

   $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;

   // Check if there is any number after decimal

   $amt_hundred = null;

   $count_length = strlen($num);

   $x = 0;

   $string = array();

   $change_words = array(0 => '', 1 => 'One', 2 => 'Two',

     3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',

     7 => 'Seven', 8 => 'Eight', 9 => 'Nine',

     10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',

     13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',

     16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',

     19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',

     40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',

     70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');

  $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');

  while( $x < $count_length ) {

       $get_divider = ($x == 2) ? 10 : 100;

       $amount = floor($num % $get_divider);

       $num = floor($num / $get_divider);

       $x += $get_divider == 10 ? 1 : 2;

       if ($amount) {

         $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;

         $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;

         $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.'

         '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. '

         '.$here_digits[$counter].$add_plural.' '.$amt_hundred;

         }else $string[] = null;

       }

   $implode_to_Rupees = implode('', array_reverse($string));

   $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . "

   " . $change_words[$amount_after_decimal % 10]) . ' Lisente' : '';

   return ($implode_to_Rupees ? $implode_to_Rupees . 'Maloti ' : '') . $get_paise;

}
function wpcargo_shipment_status_update($post_id,$current_user,$remarks,$status){

    // Make sure that it is set.
     $new_history = array(
    	'date' => date('Y-m-d'),
    	'time' => date('H:i', time() + 2 * 60 * 60),
    	'location' => "",
    	'updated-name' => $current_user->display_name,
    	'updated-by' => $current_user->ID,
    	'remarks'	=> $remarks,
    	'status'    => $status
     );
     //statuses record
     $wpcargo_shipments_update = maybe_unserialize( get_post_meta( $post_id, 'wpcargo_shipments_update', true ) );
     $wpcargo_shipments_update[] = $new_history;
     //start generate new status
      $shipment_history = $wpcargo_shipments_update; //get history of status updates
      $shipment_status = wpcargo_get_postmeta($post_id, 'wpcargo_status' ); //get current status
      $wpcargo_status = wpcargo_get_postmeta($post_id, 'wpcargo_status' );
      $status_breakdown = status_breakdown($shipment_status); //get sub statuses of current status
      $i=0;
      foreach($status_breakdown as $sub_status){
           if(!in_array_r($sub_status,$shipment_history)) break;
      $i++;}
      if(($i) == sizeof($status_breakdown)) {
         $amount_due  = shipment_amount_due($post_id);
         if($shipment_status=='Pending') $wpcargo_status =  'Active';
         else if($shipment_status=='Active' && $amount_due==0) {
                $wpcargo_status =  'Complete';
                wp_update_post( array(
                    'ID'           => $post_id,
                    'post_status'   => 'archive',
                ));
         }
      }
    else $wpcargo_status = $shipment_status;
      //end generate new status
    if(update_post_meta($post_id, 'wpcargo_shipments_update', maybe_serialize( $wpcargo_shipments_update) )) {
        update_post_meta($post_id, 'wpcargo_status', $wpcargo_status );
          $msg = "Status update was successful";
      }
   else $msg = "Updates Failed";

   return $msg;
 }

 function shipment_amount_due($shipment_id){
    $wpcargo_invoice = get_post_meta($shipment_id, 'wpcargo_invoice', true);
     $payment_history = get_post_meta($shipment_id, 'wpcargo_payment_history', true);
     $invoice = (is_array(unserialize($wpcargo_invoice))) ? (float)get_total_price($wpcargo_invoice) : 0;
     $amount_paid = 0;
     if(is_array(unserialize($payment_history))) {foreach(unserialize($payment_history) AS $key=>$values){
           $amount_paid+=(float)str_replace(",","",$values['amount']);
      } }
     $amount_due  = $invoice - $amount_paid;
  return $amount_due;
 }

