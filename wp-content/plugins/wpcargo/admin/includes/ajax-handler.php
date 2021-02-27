<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$current_shipment_id;
function view_shipment_details_callback(){
	$shipment_id 	= $_POST['shipmentID'];
	$shipment 		= new stdClass;
	$shipment->ID 	= $shipment_id;
	$shipment->post_title = get_the_title( $shipment_id );
    $current_shipment_id = $shipment_id;
	ob_start();
	?>
<div id="wpcargo-result">
    <div id="wpcargo-result-wrapper" class="wpcargo-wrap-details container">
        <?php
			do_action('wpcargo_before_track_details', $shipment );
			do_action('wpcargo_track_header_details', $shipment );
			do_action('wpcargo_track_shipper_details', $shipment );
			do_action('wpcargo_before_shipment_details', $shipment );
			do_action('wpcargo_track_shipment_details', $shipment );+
			do_action('wpcargo_after_track_details', $shipment );
			do_action('wpcargo_after_package_details', $shipment );
			if( wpcargo_package_settings()->frontend_enable ){
				do_action('wpcargo_after_package_totals', $shipment );
			}
			?>
    </div>
</div>
<?php
	$output = ob_get_clean();
	echo $output;
	wp_die();
}
add_action('wp_ajax_view_shipment_details', 'view_shipment_details_callback' );
add_action('wp_ajax_nopriv_view_shipment_details', 'view_shipment_details_callback' );

/**
 * Ajax Callback
 */
function price_estimates_action_callback(){
    global $wpdb;
    $o_country = isset( $_POST['o_country'] ) ? $_POST['o_country'] : 'N/A';
    $o_city = isset( $_POST['o_city'] ) ? $_POST['o_city'] : 'N/A';
    $d_country = isset( $_POST['d_country'] ) ? $_POST['d_country'] : 'N/A';
    $d_city = isset( $_POST['d_city'] ) ? $_POST['d_city'] : 'N/A';
    $package_weight = isset( $_POST['package_weight'] ) ? $_POST['package_weight'] : 0;
    $package_cbm = isset( $_POST['package_cbm'] ) ? $_POST['package_cbm'] : 0;
    $trans_mode = $_POST['transport_mode'];

     ///////////////
    //$selected_schedule = ($element == "collection_schedule_id") ? $collection_schedule_id : $delivery_schedule_id;
    //$city  = ($element == "collection_schedule_id") ? get_post_meta($post_id, 'wpcargo_origin_city_field', true) : get_post_meta($post_id, 'wpcargo_destination_city', true);
    $qr = $wpdb->get_results("SELECT id FROM countries_cities WHERE city_name = '$o_city' ");
    $schedule_city = $qr[0]->id;
    $schedules = $wpdb->get_results("SELECT * FROM collection_schedules WHERE schedule_city = '$schedule_city' AND status!='Closed' AND status!='Terminated' ORDER BY schedule_date ASC ");
     //$trips = wpc_get_coming_trips($o_country,$o_city,$d_country,$d_city);
     if(!empty($schedules))                        // Checking if trips have some values or not
      {  $i=1;
         //get prices
         $route_prices_results = wpc_get_route_prices($o_country,$o_city,$d_country,$d_city);
         $route_weight_prices = ($trans_mode=="Ocean")? unserialize($route_prices_results->ocean_costs) : (($trans_mode=="Air")? unserialize($route_prices_results->air_costs): unserialize($route_prices_results->road_costs) );
         $route_item_prices = ($trans_mode=="Ocean")? unserialize($route_prices_results->ocean_item_costs) : (($trans_mode=="Air")? unserialize($route_prices_results->air_item_costs): unserialize($route_prices_results->road_item_costs) );
         $freight_unit = ($trans_mode=="Ocean")? "cbm" : (($trans_mode=="Air")? "kg": "kg" );
         $package_qty = ($trans_mode=="Ocean")? $package_cbm : $package_weight;
         /////////////////
         $shipment_trips_options ='';
        foreach($schedules as $schedule){
              $schedule_date  = date_format(date_create($schedule->schedule_date),"d-F-Y");
              $current_time = date();
              $schedule_late_cut_off = $schedule->late_cut_off;
              $wpcargo_price_estimates = array();
              $freight_costs = generate_freight_cost($package_qty,$route_weight_prices,$freight_unit);
              $total_price =$freight_costs["total_cost"];
              $shipment_trips_options .=
                  '<div id="trip_'.$i.'_div" class="wpcargo-col-md-6"><p class="wpcargo-label" style="margin-bottom: 10px;"><strong>Collection Date'.$i.' ('.$schedule_date.') Estimates</strong></p>
                    <table style="font-size: 12px; width:100%;border:1.8px solid white;">';
              $shipment_trips_options .=  '<tr><td>'.$trans_mode.' Freight ('.$package_qty.$freight_unit.'):</td><td id="freight">M'.$freight_costs["total_cost"].'</td></tr>';

              $wpcargo_price_estimates['freight'] = array('unit'=>$freight_unit,'price'=>$freight_costs["unit_cost"],'qty'=>$freight_costs["qty"],'total'=>$freight_costs["total_cost"] );
              $service_items = explode(",",$_POST['service_items']);
              $items = unserialize(get_settings_items()->meta_data);
              foreach($service_items as $key){ $value=(isset($route_item_prices[$key])) ? $route_item_prices[$key] : 0;
                      if($key=="collectionfee" && $route_prices_results->origin_city."-".$route_prices_results->dest_city == $d_city."-".$o_city  ) {
                              $shipment_trips_options .=  '<tr><td>'.$items[$key]["item_name"].':</td><td id="'.$key.'">M'.$route_item_prices["deliveryfee"].'</td></tr>';
                              $wpcargo_price_estimates["collectionfee"] = array('unit'=>$items["deliveryfee"]['item_unit'],'price'=>$route_item_prices["deliveryfee"],'qty'=>1,'total'=>$route_item_prices["deliveryfee"] );
                              $total_price +=$route_item_prices["deliveryfee"];}
                      else if($key=="deliveryfee" && $route_prices_results->origin_city."-".$route_prices_results->dest_city == $d_city."-".$o_city  ){
                              $shipment_trips_options .=  '<tr><td>'.$items[$key]["item_name"].':</td><td id="'.$key.'">M'.$route_item_prices["collectionfee"].'</td></tr>';
                              $wpcargo_price_estimates["deliveryfee"] = array('unit'=>$items["collectionfee"]['item_unit'],'price'=>$route_item_prices["collectionfee"],'qty'=>1,'total'=>$route_item_prices["collectionfee"] );
                              $total_price +=$route_item_prices["collectionfee"];}
                      else{
                              $shipment_trips_options .=  '<tr><td>'.$items[$key]["item_name"].':</td><td id="'.$key.'">M'.$value.'</td></tr>';
                              $wpcargo_price_estimates[$key] = array('unit'=>$items[$key]['item_unit'],'price'=>$value,'qty'=>1,'total'=>$value );
                              $total_price +=$value;}

              }
              if( new DateTime($current_time,new DateTimeZone('Africa/Maseru')) > new DateTime($schedule_late_cut_off,new DateTimeZone('Africa/Maseru'))){
                    $shipment_trips_options .=  '<tr><td>'.$items["latebookingfee"]["item_name"].':</td><td id="latebookingfee">M'.$route_item_prices["latebookingfee"].'</td></tr>';
                    $wpcargo_price_estimates["latebookingfee"] = array('unit'=>$items["latebookingfee"]['item_unit'],'price'=>$route_item_prices["latebookingfee"],'qty'=>1,'total'=>$route_item_prices["latebookingfee"] );
                    $total_price +=$route_item_prices["latebookingfee"];
               }
              $shipment_trips_options .=  '
                    <tr><td style="border-top: solid 2px;"><b>Total</b></td><td style="border-top: solid 2px; border-bottom: double 2px;" id="label_info_tt_c"><b>M'.$total_price.'</b></td></tr>
                   </table>
                       <input type="hidden" id="collection_schedule_id" name="collection_schedule_id" value="'.$schedule->id.'">
                       <input type="hidden" id="delivery_schedule_id" name="delivery_schedule_id" value="">
                       <p style="display:none;"  id="trip_prices">'. serialize($wpcargo_price_estimates) .'</p>
                   </div>';
              if($i==2) break;
              $i++;
          }
     }

    echo $shipment_trips_options;

    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_price_estimates_action', 'price_estimates_action_callback' );
add_action( 'wp_ajax_nopriv_price_estimates_action', 'price_estimates_action_callback' );

/**
 * Ajax Callback
 */
/*
function price_estimates_action_callback(){
    $o_country = isset( $_POST['o_country'] ) ? $_POST['o_country'] : 'N/A';
    $o_city = isset( $_POST['o_city'] ) ? $_POST['o_city'] : 'N/A';
    $d_country = isset( $_POST['d_country'] ) ? $_POST['d_country'] : 'N/A';
    $d_city = isset( $_POST['d_city'] ) ? $_POST['d_city'] : 'N/A';
    $package_weight = isset( $_POST['package_weight'] ) ? $_POST['package_weight'] : 0;
    $package_cbm = isset( $_POST['package_cbm'] ) ? $_POST['package_cbm'] : 0;
    $trans_mode = $_POST['transport_mode'];

     ///////////////

     global $wpdb;
     $trips = wpc_get_coming_trips($o_country,$o_city,$d_country,$d_city);
     if(!empty($trips))                        // Checking if trips have some values or not
      {  $i=1;
         //get prices
         $route_prices_results = wpc_get_route_prices($o_country,$o_city,$d_country,$d_city);
         $route_weight_prices = ($trans_mode=="Ocean")? unserialize($route_prices_results->ocean_costs) : (($trans_mode=="Air")? unserialize($route_prices_results->air_costs): unserialize($route_prices_results->road_costs) );
         $route_item_prices = ($trans_mode=="Ocean")? unserialize($route_prices_results->ocean_item_costs) : (($trans_mode=="Air")? unserialize($route_prices_results->air_item_costs): unserialize($route_prices_results->road_item_costs) );
         $freight_unit = ($trans_mode=="Ocean")? "cbm" : (($trans_mode=="Air")? "kg": "kg" );
         $package_qty = ($trans_mode=="Ocean")? $package_cbm : $package_weight;
         /////////////////
         $shipment_trips_options ='';
         foreach($trips as $trip){      //echo "--".$row->trip_date."==".$row->doc_costs."---".$row->kg_costs;
              $trip_date  = date_format(date_create($trip->trip_date),"d-F-Y");

              $wpcargo_price_estimates = array();
             /* $late_threshold_time = new DateTime($trip->late_threshold_time,new DateTimeZone('Africa/Maseru'));
              $late_booking  = ($late_threshold_time <= $current_datetime) ? $trip->late_charge : 0;  //late charge calculation
              $kg_costs = unserialize($trip->kg_costs);
              $price_p_kg = $kg_costs[$weight_range];
              $collection_fee = ($collection==1) ? $trip->collection_fee : 0;
              $clearance_fee = ($o_country==$d_country) ? 0 : 150;
              $border_taxes = 0;
              $warehousing = ($collection==1) ? 0 : $trip->warehousing;
              $delivery_fee = $trip->delivery_fee;
              $discount = ($after_hrs==1) ? 25 : 0;  //late charge calculation     */
              /*

              $freight_costs = generate_freight_cost($package_qty,$route_weight_prices,$freight_unit);
              $total_price =$freight_costs["total_cost"];
              $shipment_trips_options .=
                  '<div id="trip_'.$i.'_div" class="wpcargo-col-md-6"><p class="wpcargo-label" style="margin-bottom: 10px;"><strong>Trip'.$i.' ('.$trip_date.') Estimates</strong></p>
                    <table style="font-size: 12px; width:100%;">';
              $shipment_trips_options .=  '<tr><td>'.$trans_mode.' Freight ('.$package_qty.$freight_unit.'):</td><td id="freight">M'.$freight_costs["total_cost"].'</td></tr>';
              $wpcargo_price_estimates['freight'] = array('unit'=>$freight_unit,'price'=>$freight_costs["unit_cost"],'qty'=>$freight_costs["qty"],'total'=>$freight_costs["total_cost"] );
              $service_items = explode(",",$_POST['service_items']);
              $items = unserialize(get_settings_items()->meta_data);
              foreach($route_item_prices as $key => $value){ if($value =="" )$value=0;
                      if($key=="latebookingfee") {
                         $current_time = date();
                         $trip_route_dates = (!empty($trip->routes_data)) ? trip_route_dates($o_city,$d_city,unserialize($trip->routes_data)) : "";
                         if( new DateTime($current_time,new DateTimeZone('Africa/Maseru')) > new DateTime($trip_route_dates['late_cut_off'],new DateTimeZone('Africa/Maseru'))){
                              $shipment_trips_options .=  '<tr><td>'.$items[$key]["item_name"].':</td><td id="'.$key.'">M'.$value.'</td></tr>';
                              $wpcargo_price_estimates[$key] = array('unit'=>$items[$key]['item_unit'],'price'=>$value,'qty'=>1,'total'=>$value );
                              $total_price +=$value;
                         }
                      }
                      else if(!in_array($key,$service_items));
                      else {
                          if($key=="collectionfee" && $route_prices_results->origin_city."-".$route_prices_results->dest_city == $d_city."-".$o_city  ) {
                              $shipment_trips_options .=  '<tr><td>'.$items[$key]["item_name"].':</td><td id="'.$key.'">M'.$route_item_prices["deliveryfee"].'</td></tr>';
                              $wpcargo_price_estimates["collectionfee"] = array('unit'=>$items["deliveryfee"]['item_unit'],'price'=>$route_item_prices["deliveryfee"],'qty'=>1,'total'=>$route_item_prices["deliveryfee"] );
                              $total_price +=$route_item_prices["deliveryfee"];}
                          else if($key=="deliveryfee" && $route_prices_results->origin_city."-".$route_prices_results->dest_city == $d_city."-".$o_city  ){
                              $shipment_trips_options .=  '<tr><td>'.$items[$key]["item_name"].':</td><td id="'.$key.'">M'.$route_item_prices["collectionfee"].'</td></tr>';
                              $wpcargo_price_estimates["deliveryfee"] = array('unit'=>$items["collectionfee"]['item_unit'],'price'=>$route_item_prices["collectionfee"],'qty'=>1,'total'=>$route_item_prices["collectionfee"] );
                              $total_price +=$route_item_prices["collectionfee"];}
                          else{
                              $shipment_trips_options .=  '<tr><td>'.$items[$key]["item_name"].':</td><td id="'.$key.'">M'.$value.'</td></tr>';
                              $wpcargo_price_estimates[$key] = array('unit'=>$items[$key]['item_unit'],'price'=>$value,'qty'=>1,'total'=>$value );
                              $total_price +=$value;}
                      }
              }
              $shipment_trips_options .=  '
                    <tr><td style="border-top: solid 2px;"><b>Total</b></td><td style="border-top: solid 2px; border-bottom: double 2px;" id="label_info_tt_c"><b>M'.$total_price.'</b></td></tr>
                   </table>
                       <p style="display:none;" id="trip_id">'.$trip->id.'</p>
                       <p style="display:none;"  id="trip_prices">'.serialize($wpcargo_price_estimates).'</p>
                   </div>';
              $i++;
          }
     }

    echo $shipment_trips_options;
    wp_die(); // required. to end AJAX request.
}

add_action( 'wp_ajax_price_estimates_action', 'price_estimates_action_callback' );
add_action( 'wp_ajax_nopriv_price_estimates_action', 'price_estimates_action_callback' );
    */
/**
 * Ajax Callback
 */
function trip_selector_action_callback(){
    global $wpdb;
    $o_country = isset( $_POST['o_country'] ) ? $_POST['o_country'] : '';
    $o_city = isset( $_POST['o_city'] ) ? $_POST['o_city'] : '';
    $d_country = isset( $_POST['d_country'] ) ? $_POST['d_country'] : '';
    $d_city = isset( $_POST['d_city'] ) ? $_POST['d_city'] : '';
    $selected = isset( $_POST['selected'] ) ? $_POST['selected'] : '';
    $options ="<option value='' data-moreinfo=''>-- Select One --</option>";
    $modes = array();
    if($selected == "org_1" || $selected == "dest_1"){
        $country = ($selected == "org_1")? $o_country : $d_country;
        $cities = wpc_get_countries_cities("ORDER BY city_name ASC",$country);
        foreach($cities as $city){
            if($city->is_origin) $options .="<option value='".$city->city_name."' data-value='".$city->city_abr."'  data-moreinfo='".$city->has_deport.",".$city->has_collection.",".$city->has_delivery."'>".$city->city_name."</option>";
         }
       $options .="<option data-value='other' data-moreinfo='' value='Other'>Other</option>";
     }
    else if(!empty($o_country) && !empty($o_city) && !empty($d_country) && !empty($d_city)){
        $trips = wpc_get_coming_trips($o_country,$o_city,$d_country,$d_city);
        if(!empty($trips)) {
            foreach($trips as $trip){
                  $trip_route_dates = (!empty($trip->routes_data)) ? trip_route_dates($o_city,$d_city,unserialize($trip->routes_data)) : "";
                  $options .="<option data-value='".$trip_route_dates['late_cut_off']."' value='".$trip->id."'>".$trip->trip_name."(".date_format(date_create($trip->trip_date),'d-F-Y').")</option>";
              }
        }
        else{
            $maintrips = $wpdb->get_results("SELECT * FROM trips WHERE routes_data LIKE '%".$origin_city."%' OR routes_data LIKE '%".$dest_city."%' GROUP BY trip_name ");

            foreach($maintrips as $maintrip){
              $trip_name = $maintrip->trip_name;
              $trips = $wpdb->get_results("SELECT * FROM trips WHERE trip_name = '$trip_name' ORDER BY trip_date ASC ");

              $c=0;
              foreach($trips as $trip){
                if($trip->status != "Closed") {
                  $trip_route_dates = (!empty($trip->routes_data)) ? trip_route_dates($o_city,$d_city,unserialize($trip->routes_data)) : "";
                  $options .="<option data-value='".$trip_route_dates['late_cut_off']."' value='".$trip->id."'>".$trip->trip_name."(".date_format(date_create($trip->trip_date),'d-F-Y').")</option>";
                  $c++;
                }
                if($c==2) break;
                }
             }
        }

        $route = wpc_get_route_prices($o_country,$o_city,$d_country,$d_city);
        if(is_array(unserialize($route->road_costs))) $modes[0]="Road";
        if(is_array(unserialize($route->air_costs))) $modes[1]="Air";
        if(is_array(unserialize($route->ocean_costs))) $modes[2]="Ocean";
    }
    echo json_encode(array($options,implode(",",$modes)));
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_trip_selector_action', 'trip_selector_action_callback' );
add_action( 'wp_ajax_nopriv_trip_selector_action', 'trip_selector_action_callback' );

/**
 * Ajax Callback
 */
function schedule_selector_action_callback(){
    global $wpdb;
    $o_country = isset( $_POST['o_country'] ) ? $_POST['o_country'] : '';
    $o_city = isset( $_POST['o_city'] ) ? $_POST['o_city'] : '';
    $o_city_id = isset( $_POST['o_city_id'] ) ? $_POST['o_city_id'] : '';
    $d_country = isset( $_POST['d_country'] ) ? $_POST['d_country'] : '';
    $d_city = isset( $_POST['d_city'] ) ? $_POST['d_city'] : '';
    $d_city_id = isset( $_POST['d_city_id'] ) ? $_POST['d_city_id'] : '';
    $selected = isset( $_POST['selected'] ) ? $_POST['selected'] : '';
    $options ="<option value='' data-city_id='' data-moreinfo=''>-- Select One --</option>";
    $modes = array();
    $today = date('Y-m-d');
    if($selected == "org_1" || $selected == "dest_1"){
        $country = ($selected == "org_1")? $o_country : $d_country;
        $cities = wpc_get_countries_cities("ORDER BY city_name ASC",$country);
        foreach($cities as $city){
            if($city->is_origin) $options .="<option value='".$city->city_name."' data-city_id='".$city->id."' data-value='".$city->city_abr."'  data-moreinfo='".$city->has_deport.",".$city->has_collection.",".$city->has_delivery."'>".$city->city_name."</option>";
         }
       $options .="<option data-value='other' data-city_id='' data-moreinfo='' value='Other'>Other</option>";
     }
    else if($selected == "org_1_1" || $selected == "dest_1_1"){
        $schedule_city = ($selected == "org_1_1")? $o_city_id : $d_city_id;
        //display new dates
        $schedules = $wpdb->get_results("SELECT * FROM collection_schedules WHERE schedule_city = '$schedule_city' AND status!='Closed' AND status!='Terminated' ORDER BY schedule_date ASC ");
        foreach($schedules as $schedule){
          $final_cut_off = $schedule->final_cut_off;
          if($final_cut_off <= $current_datetime && ($schedule->status!='Closed' && $schedule->status!='Terminated') ){
               update_schedule_status($schedule);
          }
          $options .="<option data-value='".$schedule->late_cut_off."' data-schedule_date='".$schedule->schedule_date."' value='".$schedule->id."'>".date_format(date_create($schedule->schedule_date),'d-M-Y')."(".$schedule->schedule_name.")</option>";
        }
        //display old dates
        //if ( in_array( 'administrator', (array) $current_user->roles ) || in_array( 'wpcargo_manager', (array) $current_user->roles ) ) {
        //if selected city is a depot, auto pick a trip date.
        $city = $wpdb->get_results("SELECT * FROM countries_cities WHERE id='$schedule_city'");
        $auto_select = ($city[0]->country_depot == 1)? "selected" : "";
        $oldschedules = $wpdb->get_results("SELECT * FROM collection_schedules WHERE schedule_city = '$schedule_city' AND status!='Terminated' AND schedule_date < '$today' ORDER BY schedule_date DESC Limit 4 ");
        $options .="<option disabled='disabled'>Past Dates</option>";
        foreach($oldschedules as $schedule){
          $final_cut_off = $schedule->final_cut_off;
          $option_label = ($city[0]->country_depot == 1)? $city[0]->city_name." Daily" : date_format(date_create($schedule->schedule_date),'d-M-Y')."(".$schedule->schedule_name.")";
          if($final_cut_off <= $current_datetime && ($schedule->status!='Closed' && $schedule->status!='Terminated') ){
               update_schedule_status($schedule);
          }
          $options .="<option $auto_select data-value='".$schedule->late_cut_off."' data-schedule_date='".$schedule->schedule_date."' value='".$schedule->id."'>".$option_label."</option>";
        }
         // }
        $route = wpc_get_route_prices($o_country,$o_city,$d_country,$d_city);
        if(is_array(unserialize($route->road_costs))) $modes[0]="Road";
        if(is_array(unserialize($route->air_costs))) $modes[1]="Air";
        if(is_array(unserialize($route->ocean_costs))) $modes[2]="Ocean";
    }
    echo json_encode(array($options,implode(",",$modes)));
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_schedule_selector_action', 'schedule_selector_action_callback' );
add_action( 'wp_ajax_nopriv_schedule_selector_action', 'schedule_selector_action_callback' );
/**
 * Ajax Callback
 */
function delete_city_action_callback(){
     global $wpdb;
     $index = $_POST['index'];
     $wpdb->delete(
        'countries_cities',
        array(
            'id' => $index
        )
     );
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_delete_city_action', 'delete_city_action_callback' );
add_action( 'wp_ajax_nopriv_delete_city_action', 'delete_city_action_callback' );

/**
 * Ajax Callback
 */
function view_prices_action_callback(){
     global $wpdb;
     $index = $_POST['index'];
     $routes = $wpdb->get_results( "SELECT * FROM routes WHERE id = '$index' ");
     if(!empty($routes))                        // Checking if trips have some values or not
      {
           $prices = $routes[0];
           $trips_table ="<hr/><h1 style='width: 100%;'>(".$prices->origin_city." - ".$prices->dest_city.") Route Prices</h1><hr/>";
           $results = get_settings_items();
           $items = unserialize($results->meta_data);
           $road_kg_costs = unserialize($prices->road_costs);
           if(is_array($road_kg_costs)){
             $trips_table.="
             <div style='width:45%; display:inline-block; vertical-align: top; margin-bottom:5px;'>
              <table class='viewTable'>";
             $kg_pricing_items = general_pricing_items("kgs");
             $trips_table.="<tr><td colspan='2'><h2>Road Freight Prices</h2></tr>";
             foreach($road_kg_costs as $key=>$value){
                    $color = ($kg_pricing_items[$key]['price_type']=="closed") ? 'color:red;' : '';
                    $trips_table.="<tr style='".$color."'><td>".$kg_pricing_items[$key]['label']."</td><td>M".number_format((float)$value, 2, '.', ',')."</td></tr>";
               }
             $trips_table.="</table> </div>";
             /////////////////////////////////////
             $road_item_costs = unserialize($prices->road_item_costs);
             $trips_table.="
             <div style='width:45%; display:inline-block; vertical-align: top; margin-bottom:5px;'>
               <table class='viewTable'>
                <tr><td colspan='2'><h2>Other Road Freight Prices</h2></tr>";
             //$trips_table.="<tr><td>Booking Fee</td><td>M".number_format((float)$road_item_costs['booking_fee'], 2, '.', ',')."</td></tr>";
             foreach($road_item_costs as $key=>$value){
                    $trips_table.="<tr><td>".$items[$key]['item_name']."</td><td>M".number_format((float)$value, 2, '.', ',')."</td></tr>";
               }
              $trips_table.="</table></div> ";
             ///////////////////////////////////

           }
           $air_kg_costs = unserialize($prices->air_costs);
           if(is_array($air_kg_costs)){
             $trips_table.="
             <div style='width:45%; display:inline-block; vertical-align: top; margin-bottom:5px;'>
              <table class='viewTable'>";
             $general_pricing_items = general_pricing_items("kgs");
             $trips_table.="<tr><td colspan='2'><h2>Air Freight Prices</h2></tr>";
             foreach($air_kg_costs as $key=>$value){
                    $color = ($general_pricing_items[$key]['price_type']=="closed") ? 'color:red;' : '';
                    $trips_table.="<tr style='".$color."'><td>".$general_pricing_items[$key]['label']."</td><td>M".number_format((float)$value, 2, '.', ',')."</td></tr>";
               }
             $trips_table.="</table> </div>";
             /////////////////////////////////////
             $air_item_costs = unserialize($prices->air_item_costs);
             $trips_table.="
             <div style='width:45%; display:inline-block; vertical-align: top; margin-bottom:5px;'>
               <table class='viewTable'>
                <tr><td colspan='2'><h2>Other Air Freight Prices</h2></tr>";
             //$trips_table.="<tr><td>Booking Fee</td><td>M".number_format((float)$air_item_costs['booking_fee'], 2, '.', ',')."</td></tr>";
             foreach($air_item_costs as $key=>$value){
                    $trips_table.="<tr><td>".$items[$key]['item_name']."</td><td>M".number_format((float)$value, 2, '.', ',')."</td></tr>";
               }
              $trips_table.="</table></div> ";
             ///////////////////////////////////
           }
           $ocean_cbm_costs = unserialize($prices->ocean_costs);
           if(is_array($ocean_cbm_costs)){
             $trips_table.="
             <div style='width:45%; display:inline-block; vertical-align: top; margin-bottom:5px;'>
              <table class='viewTable'>";
             $general_pricing_items = general_pricing_items("cbms");
             $trips_table.="<tr><td colspan='2'><h2>Ocean Freight Prices</h2></tr>";
             foreach($ocean_cbm_costs as $key=>$value){
                    $color = ($general_pricing_items[$key]['price_type']=="closed") ? 'color:red;' : '';
                    $trips_table.="<tr style='".$color."'><td>".$general_pricing_items[$key]['label']."</td><td>M".number_format((float)$value, 2, '.', ',')."</td></tr>";
               }
             $trips_table.="</table> </div>";
             /////////////////////////////////////
             $ocean_item_costs = unserialize($prices->ocean_item_costs);
             $trips_table.="
             <div style='width:45%; display:inline-block; vertical-align: top; margin-bottom:5px;'>
               <table class='viewTable'>
                <tr><td colspan='2'><h2>Other Ocean Freight Prices</h2></tr>";
             //$trips_table.="<tr><td>Booking Fee</td><td>M".number_format((float)$ocean_item_costs['booking_fee'], 2, '.', ',')."</td></tr>";
             foreach($ocean_item_costs as $key=>$value){
                    $trips_table.="<tr><td>".$items[$key]['item_name']."</td><td>M".number_format((float)$value, 2, '.', ',')."</td></tr>";
               }
              $trips_table.="</table></div> ";
             ///////////////////////////////////
           }
     }
     echo $trips_table;
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_view_prices_action', 'view_prices_action_callback' );
add_action( 'wp_ajax_nopriv_view_prices_action', 'view_prices_action_callback' );
/**
 * Ajax Callback
 */
function route_form_action_callback(){
     global $wpdb;
     $index = $_POST['index'];
     $routes = $wpdb->get_results( "SELECT * FROM routes WHERE id = '$index' ");
     $prices = $routes[0];  ?>
<?php echo ($index!='') ? '<h1 style="width: 100%;">Editing ('.$prices->origin_city.'-'.$prices->dest_city.') Prices</h1>' : '<h1>Adding New Route</h1>';  ?>
<?php if(!empty($msg)) echo "<p style='background-color: #dbf5e0; padding: 6px; width:100%;'>".$msg."</p>"; ?>
<input type="hidden" id='route_id' name='route_id' value="<?php echo $prices->id; ?>">
<table class="form-table">
    <tr <?php echo($index!='') ?'hidden' :''; ?>>
        <th scope="row"><?php esc_html_e( 'Original Country', 'wpcargo' ) ; ?></th>
        <th scope="row"><?php esc_html_e( 'Destination Country', 'wpcargo' ) ; ?></th>

    </tr>
    <tr <?php echo($index!='') ?'hidden' :''; ?>>
        <td>
            <select id="org_1" name='o_country_select' onchange="trip_selector(this)">
                <option value="">--Select Country--</option>
                <?php $countries = wpc_get_countries_cities("GROUP BY country_name");
                          foreach ( $countries as $country ) { ?>
                <?php if($country->is_origin) {?> <option
                    <?php echo ( trim($country->country_name) == $prices->origin_country) ? 'selected' : '' ?>>
                    <?php echo $country->country_name;?></option>
                <?php } } ?>
            </select>
            <p class="description">
                <?php esc_html_e('Select country from list','wpcargo'); ?>
            </p>
        </td>
        <td>
            <select id="dest_1" name='d_country_select' onchange="trip_selector(this)">
                <option value="">--Select Country--</option>
                <?php $countries = wpc_get_countries_cities("GROUP BY country_name");
                          foreach ( $countries as $country ) { ?>
                <?php if($country->is_destination) {?> <option
                    <?php echo ( trim($country->country_name) == $prices->dest_country) ? 'selected' : '' ?>>
                    <?php echo $country->country_name;?></option>
                <?php } } ?>
            </select>
            <p class="description">
                <?php esc_html_e('Select city from list','wpcargo'); ?>
            </p>
        </td>
    </tr>
    <tr <?php echo($index!='') ?'hidden' :''; ?>>
        <th scope="row"><?php esc_html_e( 'Original City', 'wpcargo' ) ; ?></th>
        <th scope="row"><?php esc_html_e( 'Destination City', 'wpcargo' ) ; ?></th>
    </tr>
    <tr <?php echo($index!='') ?'hidden' :''; ?>>
        <td>
            <select id="org_1_1" name='o_city_select' onchange="trip_selector(this)">
                <option value="">--Select City--</option>
                <?php $countries = wpc_get_countries_cities("ORDER BY country_name");
                          foreach ( $countries as $country ) { ?>
                <?php if($country->is_origin) {?> <option
                    <?php echo ( trim($country->city_name) == $prices->origin_city) ? 'selected' : '' ?>>
                    <?php echo $country->city_name;?></option>
                <?php } } ?>
            </select>
            <p class="description">
                <?php esc_html_e('Select city from list','wpcargo'); ?>
            </p>
        </td>
        <td>
            <select id="dest_1_1" name='d_city_select' onchange="trip_selector(this)">
                <option value="">--Select Country--</option>
                <?php $countries = wpc_get_countries_cities("ORDER BY country_name");
                          foreach ( $countries as $country ) { ?>
                <?php if($country->is_destination) {?> <option
                    <?php echo ( trim($country->city_name) == $prices->dest_city) ? 'selected' : '' ?>>
                    <?php echo $country->city_name;?></option>
                <?php } } ?>
            </select>
            <p class="description">
                <?php esc_html_e('Select city from list','wpcargo'); ?>
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2"><br>
            <?php   $general_pricing_items = general_pricing_items("kgs"); $i=0;
                   $road_kg_costs = unserialize($prices->road_costs);
           ?>
            <label><input style="width:20px; height:20px" type="checkbox"
                    <?php echo (is_array($road_kg_costs))? "checked" : ""; ?> name="road_pricing"
                    onclick="toggle_price_fields(this)"> Road Freight Prices</label>
            <div id="road_pricing" style=" <?php echo (!is_array($road_kg_costs))? "display: none;" : ""; ?>">
                <table>
                    <?php
                     foreach($general_pricing_items as $input=>$data){   if($i%6==0) echo"<tr>";
                        $color = ($data['price_type']=="closed") ? 'color:red;' : '';
                        echo '<td style="'.$color.'"><label>'.$data['label'].'</label><input type="text" name="road_'.$input.'" value="'.number_format((float)$road_kg_costs[$input], 2, '.', ',').'"></td>';
                         if(($i+1)%6==0) echo"</tr>";
                 $i++;} ?>
                </table>
                <label>Other Items Prices</label>
                <table>
                    <tr><?php  $i=1;
                     $route_item_costs = unserialize($prices->road_item_costs);
                  ?>
                        <?php $results = get_settings_items();
                     $items = unserialize($results->meta_data);
                     foreach( $items as $key => $item_data ){
                        if(isset($item_data['is_route_item']) && $item_data['is_route_item'] == 1) {  if($i%6==0) echo"<tr>";?>
                        <td><label><?php echo $item_data["item_name"]; ?></label>
                            <input type="text" name="<?php echo 'road_'.$key;?>"
                                value="<?php echo (isset($route_item_costs[$key])) ? number_format((float)$route_item_costs[$key], 2, '.', ',') : number_format((float)$item_data['item_price'], 2, '.', ',');?>">
                        </td>
                        <?php if(($i+1)%6==0) echo"</tr>"; $i++; }
                } ?>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2"><br>
            <?php $general_pricing_items = general_pricing_items("cbms"); $i=0;
                   $ocean_cbm_costs = unserialize($prices->ocean_costs);
            ?>
            <label><input style="width:20px; height:20px" type="checkbox"
                    <?php echo (is_array($ocean_cbm_costs))? "checked" : ""; ?> name="ocean_pricing"
                    onclick="toggle_price_fields(this)"> Ocean Freight Prices</label>
            <div id="ocean_pricing" style="<?php echo (!is_array($ocean_cbm_costs))? "display: none;" : ""; ?>">
                <table>
                    <?php foreach($general_pricing_items as $input=>$data){   if($i%6==0) echo"<tr>";
                        $color = ($data['price_type']=="closed") ? 'color:red;' : '';
                         echo '<td style="'.$color.'"><label>'.$data['label'].'</label><input type="text" name="ocean_'.$input.'" value="'.number_format((float)$ocean_cbm_costs[$input], 2, '.', ',').'"></td>';
                         if(($i+1)%6==0) echo"</tr>";
                 $i++;} ?>
                </table>
                <label>Other Items Prices</label>
                <table>
                    <tr><?php  $i=1;
                     $route_item_costs = unserialize($prices->ocean_item_costs);
                  ?>
                        <?php $results = get_settings_items();
                     $items = unserialize($results->meta_data);
                     foreach( $items as $key => $item_data ){
                        if(isset($item_data['is_route_item']) && $item_data['is_route_item'] == 1) {  if($i%6==0) echo"<tr>";?>
                        <td><label><?php echo $item_data["item_name"]; ?></label>
                            <input type="text" name="<?php echo 'ocean_'.$key;?>"
                                value="<?php echo (isset($route_item_costs[$key])) ? number_format((float)$route_item_costs[$key], 2, '.', ',') : number_format((float)$item_data['item_price'], 2, '.', ',');?>">
                        </td>
                        <?php if(($i+1)%6==0) echo"</tr>"; $i++; }
                } ?>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2"><br>
            <?php $general_pricing_items = general_pricing_items("kgs"); $i=0;
                   $air_kg_costs = unserialize($prices->air_costs);
           ?>
            <label><input style="width:20px; height:20px" type="checkbox"
                    <?php echo (is_array($air_kg_costs))? "checked" : ""; ?> name="air_pricing"
                    onclick="toggle_price_fields(this)"> Air Freight Prices</label>
            <div id="air_pricing" style="<?php echo (!is_array($air_kg_costs))? "display: none;" : ""; ?>">
                <table>
                    <?php foreach($general_pricing_items as $input=>$data){   if($i%6==0) echo"<tr>";
                        $color = ($data['price_type']=="closed") ? 'color:red;' : '';
                        echo '<td style="'.$color.'"><label>'.$data['label'].'</label><input type="text" name="air_'.$input.'" value="'.number_format((float)$air_kg_costs[$input], 2, '.', ',').'"></td>';
                         if(($i+1)%6==0) echo"</tr>";
                 $i++;} ?>
                </table>
                <label>Other Items Prices</label>
                <table>
                    <tr><?php  $i=1;
                     $route_item_costs = unserialize($prices->air_item_costs);
                  ?>
                        <?php $results = get_settings_items();
                     $items = unserialize($results->meta_data);
                     foreach( $items as $key => $item_data ){
                        if(isset($item_data['is_route_item']) && $item_data['is_route_item'] == 1) {  if($i%6==0) echo"<tr>";?>
                        <td><label><?php echo $item_data["item_name"]; ?></label>
                            <input type="text" name="<?php echo 'air_'.$key;?>"
                                value="<?php echo (isset($route_item_costs[$key])) ? number_format((float)$route_item_costs[$key], 2, '.', ',') : number_format((float)$item_data['item_price'], 2, '.', ',');?>">
                        </td>
                        <?php if(($i+1)%6==0) echo"</tr>"; $i++; }
                } ?>
                </table>
            </div>
        </td>
    </tr>
</table>
<!--center><br><input id="submit_btn" name="submit" type="submit" value="Save Changes"></center-->


<?php wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_route_form_action', 'route_form_action_callback' );
add_action( 'wp_ajax_nopriv_route_form_action', 'route_form_action_callback' );
/**
 * Ajax Callback
 */
function delete_route_action_callback(){
     global $wpdb;
     $index = $_POST['index'];
     $wpdb->delete(
        'routes',
        array(
            'id' => $index
        )
     );
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_delete_route_action', 'delete_route_action_callback' );
add_action( 'wp_ajax_nopriv_delete_route_action', 'delete_route_action_callback' );

/**
 * Ajax Callback
 */
function delete_item_action_callback(){
     global $wpdb;
     $index = $_POST['index'];
     $results = get_settings_items();
     $meta_data = unserialize($results->meta_data);
     unset($meta_data[$index]);
     $wpdb->update(
      		'other_settings',
          	array(
          			'meta_data' => serialize($meta_data),
          		),
              array(
      			'meta_key' => 'items'
      		)
      	);
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_delete_item_action', 'delete_item_action_callback' );
add_action( 'wp_ajax_nopriv_delete_item_action', 'delete_item_action_callback' );

/**
 * Ajax Callback
 */
function shipment_modal_action_callback(){
     global $wpdb;
     $current_user = wp_get_current_user();
     $current_form = $_POST['current_form'];
     $post_id = $_POST['post_id'];
     if($current_form == "save_invoice"){
         $_POST=filter_var_array($_POST);
         $data = $_POST["table_data"];
         $booking_reference = get_post_meta( $post_id, 'booking_reference', true );
         $exploded_booking_reference = explode("-",$booking_reference);
         $invoice_no = "INV-".$exploded_booking_reference[1];
         update_post_meta( $post_id, 'wpcargo_invoice', maybe_serialize($data));
        //do this only id invoice does not already exists
        if(get_post_meta( $post_id, 'wpcargo_invoice_no', true )==""){
           update_post_meta( $post_id, 'wpcargo_invoice_no', $invoice_no);
           update_post_meta( $post_id, 'wpcargo_invoice_date', date('Y-m-d H:i'));
            $current_form = "state_update";
            $_POST['wpcargo_status'] = "Invoicing";
            $_POST['remarks'] = "Invoice Created";
         }
        $msg = "Invoice Created";
     }
     if($current_form == "state_update"){
         //now set history and status for shipment
            $remarks = sanitize_text_field(trim($_POST['remarks']));
    	    $wpcargo_status 	= sanitize_text_field(trim($_POST['wpcargo_status']));
            $msg = wpcargo_shipment_status_update($post_id,$current_user,$remarks,$wpcargo_status);
     }
     if($current_form == "revise_quote"){
         $_POST=filter_var_array($_POST);
         $data = $_POST["table_data"];
         if(empty(get_post_meta( $post_id, 'wpcargo_price_estimates-old', true ))){
            $wpcargo_price_estimates =  unserialize(get_post_meta( $post_id, 'wpcargo_price_estimates', true ));
            update_post_meta( $post_id, 'wpcargo_price_estimates-old', maybe_serialize( $wpcargo_price_estimates ));
          }
          update_post_meta( $post_id, 'wpcargo_price_estimates', maybe_serialize($data));
          $msg = "Quotation updated";
     }
     if($current_form == "revise_packages"){
        $old_packages = get_post_meta( $post_id, 'wpc-multiple-package', true );
        if(empty(get_post_meta( $post_id, 'wpc-multiple-package-old', true ))) update_post_meta($post_id, 'wpc-multiple-package-old', $old_packages);
        $new_packages = maybe_serialize($_POST['wpc-multiple-package']);
        update_post_meta($post_id, 'wpc-multiple-package', $new_packages);
        $route_prices_results = wpc_get_route_prices(get_post_meta( $post_id, 'wpcargo_origin_field', true ),get_post_meta( $post_id, 'wpcargo_origin_city_field', true ),get_post_meta( $post_id, 'wpcargo_destination', true ),get_post_meta( $post_id, 'wpcargo_destination_city', true ));
        $route_prices = ($_POST['transport_mode']=="Ocean")? unserialize($route_prices_results->ocean_costs) : (($_POST['transport_mode']=="Air")? unserialize($route_prices_results->air_costs): unserialize($route_prices_results->road_costs) );
        $package_qty = ($_POST['transport_mode']=="Ocean")? sanitize_text_field($_POST['total_package-cbm']) : sanitize_text_field($_POST['package-weight']);
        $wpcargo_price_estimates =  unserialize(get_post_meta( $post_id, 'wpcargo_price_estimates', true ));
        if(empty(get_post_meta( $post_id, 'wpcargo_price_estimates-old', true ))){
            update_post_meta( $post_id, 'wpcargo_price_estimates-old', maybe_serialize( $wpcargo_price_estimates ));  }
        $freight_costs = generate_freight_cost($package_qty,$route_prices,$_POST['item_type']);
        $freight = $wpcargo_price_estimates["freight"];  $freight["qty"] = $freight_costs["qty"]; $freight["price"] = $freight_costs["unit_cost"]; $freight["total"] = $freight_costs["total_cost"];
        $wpcargo_price_estimates["freight"] = $freight;
        update_post_meta( $post_id, 'wpcargo_price_estimates', maybe_serialize( $wpcargo_price_estimates )); //save selected pricing items for this booking
        if(!empty(get_post_meta( $post_id, 'wpcargo_invoice', true ))){
            update_post_meta( $post_id, 'wpcargo_invoice', maybe_serialize( $wpcargo_price_estimates ));
          }
        update_post_meta( $post_id, 'total_package-cbm', sanitize_text_field(trim($_POST['total_package-cbm'])));
        update_post_meta( $post_id, 'package-weight', sanitize_text_field(trim($_POST['package-weight'])));
        $msg = "Packages Updated";
     }
     echo $msg;
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_shipment_modal_action', 'shipment_modal_action_callback' );
add_action( 'wp_ajax_nopriv_shipment_modal_action', 'shipment_modal_action_callback' );

/**
 * Ajax Callback
 */
function quotations_list_action_callback(){   ?>
<style>
#list_view table tbody .row td,
#list_view table thead .row td {
    border-right: 2px solid;
    border-bottom: 2px solid;
}

#list_view table tbody .row td:nth-child(5),
#list_view table thead .row td:nth-child(5) {
    border-right: none;
    text-align: center;
}
</style>
<?php
       $shipment_id =  sanitize_text_field(trim($_POST['shipment_id']));
       $quote1_val =  sanitize_text_field(trim($_POST['quote1']));
       $quote2_val =  sanitize_text_field(trim($_POST['quote2']));
       $booking_reference = get_post_meta( $shipment_id, 'booking_reference', true );
       $shipment_code_r = explode("-",$booking_reference);
       $shipment_code = $shipment_code_r[1];  ?>
<div id="list_view">
    <table class="view" style="font-size: 12px; width:100%; line-height:30px;">
        <thead>
            <tr class="row">
                <td style="width:15%;font-weight:700;">#</td>
                <td style="width:20%; font-weight:700;">Quote #</td>
                <td style="width:25%;font-weight:700;">Quotation</td>
                <td style="width:15%; font-weight:700;">Amount</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <tr class="row">
                <td>1</td>
                <td><?php echo "Qt".$shipment_code."-1"; ?></td>
                <td>New Quotation</td>
                <td>M <?php echo $quote1_val; ?></td>
                <td><a href="#" class="button button-primary" id="old_quote" type="button"
                        onclick="quote_more(this,'<?php echo $shipment_id; ?>','old_wpcargo_price_estimates')">View</a>
                </td>
            </tr>
            <tr class="row">
                <td>2</td>
                <td><?php echo "Qt".$shipment_code."-2"; ?></td>
                <td>Final Quotation</td>
                <td>M <?php echo $quote2_val; ?></td>
                <td><a href="#" class="button button-primary" id="new_quote" type="button"
                        onclick="quote_more(this,'<?php echo $shipment_id; ?>','wpcargo_price_estimates')">View</a></td>
            </tr>
            <tr>
                <td colspan="5"><br></td>
            </tr>
        </tbody>
    </table>
</div>
<?php
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_quotations_list_action', 'quotations_list_action_callback' );
add_action( 'wp_ajax_nopriv_quotations_list_action', 'quotations_list_action_callback' );

/**
 * Ajax Callback
 */
function quote_more_action_callback(){
       $shipment_id =  sanitize_text_field(trim($_POST['post_id']));
       $option =  sanitize_text_field(trim($_POST['option']));
       $route_prices_results = wpc_get_route_prices(get_post_meta( $shipment_id, 'wpcargo_origin_field', true ),get_post_meta( $shipment_id, 'wpcargo_origin_city_field', true ),get_post_meta( $shipment_id, 'wpcargo_destination', true ),get_post_meta( $shipment_id, 'wpcargo_destination_city', true ));
       $route_prices = unserialize($route_prices_results->item_costs);
       $settings_items = unserialize(get_settings_items()->meta_data);
       $invoice_details = get_post_meta( $shipment_id, 'wpcargo_invoice', true );
       $booking_reference =  get_post_meta( $shipment_id, 'booking_reference', true );
       $exploded_booking_reference = explode("-",$booking_reference);
       $wpcargo_receiver_company =  get_post_meta( $shipment_id, 'wpcargo_receiver_company', true );
       $url_barcode	= WPCARGO_PLUGIN_URL."/includes/barcode.php?codetype=Code128&size=60&text=" . $booking_reference . "";
       if($option=="old_wpcargo_price_estimates"){   //if clicked button is old quote
          $price_estimates = unserialize(get_post_meta( $shipment_id, 'wpcargo_price_estimates-old', true ));
          $info = array("item_name"=>"QUOTATION", "item_no"=>"QT-".$exploded_booking_reference[1]."-1", "item_date"=>get_the_date("j F Y",$shipment_id));
       }
       else if($option=="wpcargo_price_estimates" ){  //otherwise show current quote
         $price_estimates = unserialize(get_post_meta( $shipment_id, 'wpcargo_price_estimates', true ));
         $info = array("item_name"=>"QUOTATION", "item_no"=>"QT-".$exploded_booking_reference[1]."-2", "item_date"=>get_the_date("j F Y",$shipment_id));
         if(empty($invoice_details)) echo '<p><a id="edit_quote_btn" style="float:right; margin-top:-20px; margin-bottom:0px;" href="#" onclick="edit_quote()">Edit Quote</a></p>';
       }
       else if($option=="wpcargo_invoice"){  //if clicked button is invoice more
         if(!empty($invoice_details) ) $price_estimates = unserialize($invoice_details);
         else if(empty($invoice_details) ) $price_estimates = unserialize(get_post_meta( $shipment_id, 'wpcargo_price_estimates', true ));   //if clicked button is invoice more
         if(wpcargo_get_postmeta($shipment_id, 'wpcargo_status' ) != "Complete") echo '<p><a id="edit_invoice_btn" style="float:right; margin-top:-20px; margin-bottom:0px;" href="#" onclick="edit_invoice()">Edit Invoice</a></p>';
         $info = array("item_name"=>"INVOICE", "item_no"=>get_post_meta( $shipment_id, 'wpcargo_invoice_no', true ), "item_date"=>get_post_meta( $shipment_id, 'wpcargo_invoice_date', true ));
       }
     echo '<div id="receipt_display">';
     $booking_type = get_post_meta( $shipment_id, 'booking_type', true);
     $shipper = get_shipper_name($shipment_id);
     $author = ($booking_type=="Online")? $shipper : get_the_author_meta('display_name',get_post_field('post_author',$shipment_id));
     $booking_by = $author."(".$booking_type.")";

     $packages = count(wpcargo_get_package_data($shipment_id));
     $actual_weight = wpcargo_package_actual_weight( $shipment_id );
     $dm_weight = wpcargo_package_volumetric( $shipment_id );
      ?>
<div style="border-bottom: 4px solid;">
    <div style="width:69%; display:inline-block; height:120px;"> <img
            src='<?php echo WPCARGO_PLUGIN_URL."assets/images/receipt_head.png";?>' width="100%;" height="120px;"></div>
    <div class="print-tn"
        style="width:29%; display:inline-block; text-align:center; height:100px; vertical-align:top; padding-top:13px;">
        <?php echo $booking_reference;?><img src="<?php echo $url_barcode;?>" alt="<?php echo $booking_reference;?>"
            width="100%" height="70px;" /> </div>
</div>
<div class="print-body">
    <p>
    <div style="width:49%; display:inline-block; vertical-align:top;">
        <p class="black_bg" style="width:94%;"><b><?php echo $info['item_name']; ?></b></p>
        <div style=" border: solid 1px; width:90%; padding: 0 10px;">
            <p>
            <div style="width:22%; display:inline-block;"><b>Name: </b></div>
            <div style="width:78%; border-bottom:1px solid; display:inline-block;"> <?php echo $shipper;?></div>
            </p>
            <p>
            <div style="width:22%; display:inline-block;">&nbsp;</div>
            <div style="width:78%; border-bottom:1px solid; display:inline-block;"><br></div>
            </p>
            <p>
            <div style="width:22%; display:inline-block;"><b>Tel/Cel: </b></div>
            <div style="width:78%; border-bottom:1px solid; display:inline-block;">
                <?php echo get_post_meta( $shipment_id, 'wpcargo_receiver_phone_1', true );?></div>
            </p>
            <p>
            <div style="width:22%; display:inline-block;"><b>Email: </b></div>
            <div style="width:78%; border-bottom:1px solid; display:inline-block;">
                <?php echo get_post_meta( $shipment_id, 'wpcargo_receiver_email', true );?></div>
            </p>
        </div>
    </div>
    <div style="width:49%; display:inline-block; vertical-align:top;">
        <p>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Date :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">
            &nbsp;<?php echo date_format(date_create($info['item_date']),"d-M-Y");?></div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Invoice No. :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $info['item_no'];?>
        </div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Booking Ref :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $booking_reference; ?>
        </div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Route :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">
            &nbsp;<?php echo get_post_meta( $shipment_id, 'wpcargo_origin_city_field', true )."-".get_post_meta( $shipment_id, 'wpcargo_destination_city', true ); ?>
        </div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Packages :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $packages; ?></div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Actual Weight :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $actual_weight; ?></div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>DM Weight :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $dm_weight; ?></div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Booking By :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $booking_by; ?></div>
        </p>
    </div>
    </p>
    <?php
       echo'
        <table class="grid_table" style="font-size: 14px; width:100%;">
        <thead><tr><td style="width:50%; font-size:18px; font-weight:700;">DESCRIPTION</td><td style="width:8%; font-size:18px; font-weight:700;">UNIT</td><td style="width:19%; font-size:18px; font-weight:700;">UNIT PRICE</td><td style="width:8%; font-size:18px; font-weight:700;">QTY</td><td style="width:15%; font-size:18px; font-weight:700;">AMOUNT</td></tr></thead>
              <tbody>
                <tr><td id="freight">Freight</td><td>'.$price_estimates["freight"]["unit"].'</td><td>';
                    echo 'M <span>'.number_format($price_estimates["freight"]["price"], 2, '.', ',').'</span>';
                    echo '<input style="display:none;" id="price" name="price" onkeyup="re_calculate(this)" value="'.number_format((float)$price_estimates["freight"]["price"], 2, '.', ',').'" ></td>
                          <td><span>'.$price_estimates["freight"]["qty"].'</span><input style="display:none;" readonly id="qty" name="qty" data-value="'.$price_estimates["freight"]["qty"].'" onkeyup="re_calculate(this)" value="'.$price_estimates["freight"]["qty"].'"></td>';
                    echo ($price_estimates["freight"]["total"]=="TBC")?'<td> M 0.00</td>' : '<td> M '.number_format($price_estimates["freight"]["total"], 2, '.', ',').'</td></tr>';
           $total = (float)$price_estimates["freight"]["total"];
           foreach($price_estimates AS $key => $value){
               if($key !="freight") {
                 $unit_price = number_format($price_estimates[$key]["price"], 2, '.', ',');
                 $total_cost = number_format($price_estimates[$key]["total"], 2, '.', ',');
                 $op_sign = ($settings_items[$key]["item_type"]=="Expenditure") ? "-": "";
                 echo '<tr data-type="'.$settings_items[$key]["item_type"].'">
                       <td>'.$settings_items[$key]["item_name"].'</td>
                       <td>'.$price_estimates[$key]["unit"].'</td>
                       <td>'.$op_sign.'M <span>';
                 echo ($price_estimates[$key]["price"]=="" ||$price_estimates[$key]["price"]=="TBC")?'0.00':$unit_price;
                 echo '</span><input style="display:none;" data-value="'.number_format($price_estimates[$key]["price"], 2, '.', ',').'" id="price" onkeyup="re_calculate(this)" name="price" value="'.number_format($price_estimates[$key]["price"], 2, '.', ',').'" ></td><td><span>'.$price_estimates[$key]["qty"].'</span><input style="display:none;" id="qty" name="qty" data-value="'.$price_estimates[$key]["qty"].'" onkeyup="re_calculate(this)" value="'.$price_estimates[$key]["qty"].'"></td><td> '.$op_sign.'M ';
                 echo ($price_estimates[$key]["total"]=="" ||$price_estimates[$key]["price"]=="TBC")?'0.00' : $total_cost.' <a style="color:red; display:none;" href="#" onclick="remove_row(this)"> X</a></td></tr>';
                 $total = ($op_sign=="-")? $total-(float)$price_estimates[$key]["total"] : $total+(float)$price_estimates[$key]["total"]; }
           }
       echo'</tbody><tfoot>
             <tr><td><br></td><td><br></td><td><br></td><td><br></td></tr>
             <tr><td colspan="2"></td><td colspan="2" style="border-top: solid 2px; text-align:center; font-size:14px;background: #D3D3D3;"><b>TOTAL AMOUNT</b></td><td style="border-top: solid 2px; border-bottom: double 2px; font-size:14px; background: #D3D3D3;"> M <b id="total">'.number_format($total, 2, '.', ',').'</b></td></tr>
             <tr id="onedit" style="display:none;"><td colspan="2">Add Another Item: <select id="item_select">';
             echo'<option data-key="" data-unit="" data-value="">Select One</option>
                  <option data-key="other" data-unit="" data-value="">Other</option>';
       foreach ( $settings_items as $key => $item_data ){
                if($key=="collectionfee" && $route_prices_results->origin_city."-".$route_prices_results->dest_city == get_post_meta( $shipment_id, 'wpcargo_destination_city', true )."-".get_post_meta( $shipment_id, 'wpcargo_origin_city_field', true )  )
                        $price = ($route_prices["deliveryfee"]=="")?$settings_items["deliveryfee"]['item_price'] : $route_prices["deliveryfee"];
                    else if($key=="deliveryfee" && $route_prices_results->origin_city."-".$route_prices_results->dest_city == get_post_meta( $shipment_id, 'wpcargo_destination_city', true )."-".get_post_meta( $shipment_id, 'wpcargo_origin_city_field', true )  )
                        $price = ($route_prices["collectionfee"]=="")?$settings_items["collectionfee"]['item_price'] : $route_prices["collectionfee"];
                    else
                        $price = ($route_prices[$key]=="")?$item_data['item_price'] : $route_prices[$key];
                echo'<option data-key="'.$key.'" data-type="'.$settings_items[$key]['item_type'].'" data-unit="'.$item_data['item_unit'].'" data-value="'.$price.'">'.$item_data['item_name'].'</option>'; }
       echo'</select><a class="button" href="#" onclick="add_row()">Add</a> </td></tr>';
       echo'</tfoot></table>';
    ?>
</div>
<div>
    <p>
    <div style="width:59%; display:inline-block; vertical-align:top; margin-right:4%;">
        <p style="border-top: solid 3px; margin-top: 25px; padding: 20px 2px;">Unless on the basis of an account, an
            invoice must be fully settled upon collection or delivery of goods to the final destination. proof of
            payment must be emailed to: <b>accounts@sky266.co.ls</b></p>
    </div>
    <div style="width:35%; display:inline-block; vertical-align:top;">
        <p><b>STANDARD LESOTHO BANK</b><br>
            <b style="margin-left: 18px;">NAME: </b> Sky-Corp t/a Sky266<br>
            <b style="margin-left: 18px;">ACCOUNT #: </b> 9080007532411<br>
            <b style="margin-left: 18px;">BRANCH: </b> Tower (062067)<br>
            <b>MPESA: </b> 57555325<br>
            <b>ECOCASH: </b> 62555325
        </p>
    </div>
    </p>
</div>
<?php
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_quote_more_action', 'quote_more_action_callback' );
add_action( 'wp_ajax_nopriv_quote_more_action', 'quote_more_action_callback' );
/**
 * Ajax Callback
 */
 function payment_more_action_callback(){   ?>
<style>
#list_view table tbody .row td,
#list_view table thead .row td {
    border-right: 2px solid;
    border-bottom: 2px solid;
}

#list_view table tbody .row td:nth-child(5),
#list_view table thead .row td:nth-child(5) {
    border-right: none;
}
</style>
<?php  $shipment_id =  sanitize_text_field(trim($_POST['post_id']));
        $payment_history = unserialize(get_post_meta( $shipment_id, 'wpcargo_payment_history', true ));
        $received_from = get_shipper_name($shipment_id);
        $total = 0;
        echo '<div id="list_view"> <a href="#" class="button button-primary" type="button" style="float:right; margin-top:-20px; margin-bottom:10px;" onclick="make_payment()">Receive Payment</a>';
        if(is_array($payment_history)) {
            echo' <table class="view" style="font-size: 12px; width:100%; line-height:30px;">
             <thead style="font-size: 14px; font-weight:700; padding-bottom:12px;">
                 <tr class="row"><td>Date</td><td>Receipt#</td><td>Method</td><td>Amount</td><td></td></tr></thead>
                   <tbody>';
                foreach($payment_history AS $key => $values){
                     if($values["approval"] == "0" ){
                          echo $key;
                          echo '<tr class="row"><td id="date">'.date_format(date_create($key),"d-M-Y, H:i").'</td><td>'.$values["receipt_no"].'</td><td id="revised_method" >'.$values["method"].'</td><td id="revised_amount" >M '.$values["amount"].'</td><td><a href="#" data-row_id="'.$key.'" data-shipment_id="'.$shipment_id.'" class="button button-primary" type="button" onclick="revised_online_payments()">Approve</a></td></tr>';


                          //$total+=(float)str_replace(",","",$values['amount']);
                        }
                    else{
                        echo '<tr class="row"><td>'.date_format(date_create($key),"d-M-Y, H:i").'</td><td>'.$values["receipt_no"].'</td><td>'.$values["method"].'</td><td>M '.$values["amount"].'</td><td><a href="#" data-row_id="'.$key.'" data-shipment_id="'.$shipment_id.'" class="button button-primary" type="button" onclick="payment_singleview(this)">View</a></td></tr>';
                        $total+=(float)str_replace(",","",$values['amount']);
                     }
                }
            echo'
                <tr><td colspan="5"><br></td></tr>
                </tbody><tfoot>
                  <tr><td colspan="3" style="font-size:14px; text-align:right; padding-right:25px;"><b>Total Paid</b></td><td colspan="2" style="border-top: solid 2px; border-bottom: solid 2px; font-size:14px;"><b id="total">&nbsp;&nbsp;M '.number_format($total, 2, '.', ',').'</b></td></tr>
                </tfoot></table>';
        }
        else echo "<br>no payment data";
        echo '</div>
              <div style="display:none;" id="payment_form">
              <p style="color:red;" id=msg_box></p>';
        echo '<table class="wpcargo form-table" style="width:90%" >
                   <tr>
                     <th><label>Payment Date : </label></th>
                     <td><input style="width:100%;" type="date" required="required" id="payment_date" name="payment_date" value="'.date("Y-m-d").'"></td>
                   </tr>
                   <tr>
                     <th><label>Method of Payment : </label></th>
                     <td style="width:60%;"> <select style="width:100%;" required="required" id="payment_method" name="payment_method" onchange="toggle_payment_fields(this)">
                          <option value="">Select one</option><option>Standard Bank</option><option>FNB Lesotho</option><option>NedBank Lesotho</option>
                          <option value="Mpesa">Mpesa</option><option value="Ecocash">Ecocash</option>
                          <option>Cash</option><option>Account</option><option>Other</option></select></td>
                   </tr>
                   <tr>
                     <th><label>Received From : </label></th>
                     <td><input style="width:100%;" readonly type="text" required id="received_from" name="received_from" placeholder="" value="'.$received_from.'"></td>
                   </tr>
                   <tr id="payment_reference_row" style="display:none;">
                     <th><label>Payment Reference : </label></th>
                     <td><input style="width:100%;" type="text" required="required" id="payment_reference" name="payment_reference" placeholder="Type here" value=""></td>
                   </tr>
                   <tr id="payment_amount_row" style="display:none;">
                     <th><label>Amount Paid : </label></th>
                     <td>M&nbsp;<input style="width:90%;" type="text" required id="payment_amount" name="payment_amount" value="" placeholder="0.00"/></td>
                   </tr>
                 </table> ';
        echo "</div>";


				echo '<div style="display:none;" id="payment_revision_form">
							<p style="color:red;" id=msg_box></p>';
							foreach($payment_history AS $key => $values){
									 if($values["approval"] == "0" ){
												echo '<h2>Reciept no: '.$values["receipt_no"].'</h2>';
				echo '<table class="wpcargo form-table" style="width:90%" >
									 <tr>
										 <th><label>Payment Date : </label></th>
										 <td><input style="width:100%;" readonly type="text" required="required" id="payment_datef" name="payment_date" value="'.date_format(date_create($key),"d-M-Y, H:i").'"></td>
									 </tr>
									 <tr>
										 <th><label>Method of Payment : </label></th>
										 <td style="width:60%;"> <select style="width:100%;" required="required" id="payment_methodfd" name="payment_method" onchange="toggle_payment_fields(this)">
													<option value="'.$values["method"].'">'.$values["method"].'</option><option>Standard Bank</option><option>FNB Lesotho</option><option>NedBank Lesotho</option>
													<option value="Mpesa">Mpesa</option><option value="Ecocash">Ecocash</option>
													<option>Cash</option><option>Account</option><option>Other</option></select></td>
									 </tr>
									 <tr>
										 <th><label>Received From : </label></th>
										 <td><input style="width:100%;" readonly type="text" required id="received_fromf" name="received_from" placeholder="" value="'.$received_from.'"></td>
									 </tr>
									 <tr id="payment_reference_row" >
										 <th><label>Payment Reference : </label></th>
										 <td><input style="width:100%;" type="text" required="required" id="payment_referencef" name="payment_reference"  value="'.$values["reference"].'"></td>
									 </tr>
									 <tr id="payment_amount_row" >
										 <th><label>Amount Paid : </label></th>
										 <td>M&nbsp;<input style="width:90%;" type="text" required id="payment_amountf" name="payment_amount" value="'.$values["amount"].'" placeholder="0.00"/></td>
									 </tr>
								 </table> ';
							 }
					}
				echo "</div>";

     wp_die(); // required. to end AJAX request.
 }
 /* Load Ajax Callback to "wp_ajax_*" Action Hook */
 add_action( 'wp_ajax_payment_more_action', 'payment_more_action_callback' );
 add_action( 'wp_ajax_nopriv_payment_more_action', 'payment_more_action_callback' );

/**
 * Ajax Callback
 */
function payment_singleview_action_callback(){
       $shipment_id =  sanitize_text_field(trim($_POST['shipment_id']));
       $booking_reference =  get_post_meta( $shipment_id, 'booking_reference', true );
       $wpcargo_receiver_company =  get_post_meta( $shipment_id, 'wpcargo_receiver_company', true );
       $received_from = (!empty($wpcargo_receiver_company))? $wpcargo_receiver_company : wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_fname', true)." ".wpcargo_get_postmeta($shipment_id, 'wpcargo_receiver_sname', true);
       $row_id =  sanitize_text_field(trim($_POST['row_id']));
       $payment_history = unserialize(get_post_meta( $shipment_id, 'wpcargo_payment_history', true ));
       $payment_receipt = $payment_history[$row_id];
       $url_barcode	= WPCARGO_PLUGIN_URL."/includes/barcode.php?codetype=Code128&size=60&text=" . $booking_reference . "";
       echo '<div id="receipt_display">';
       if(is_array($payment_receipt)) {  ?>
<div style="border-bottom: 4px solid;">
    <div style="width:69%; display:inline-block; height:120px;"> <img
            src='<?php echo WPCARGO_PLUGIN_URL."assets/images/receipt_head.png";?>' width="100%;" height="120px;"></div>
    <div class="print-tn"
        style="width:29%; display:inline-block; text-align:center; height:100px; vertical-align:top; padding-top:13px;">
        <?php echo $booking_reference;?><img src="<?php echo $url_barcode;?>" alt="<?php echo $booking_reference;?>"
            width="100%" height="70px;" /> </div>
</div>
<div class="print-body">
    <p>
    <div style="width:66%; display:inline-block;"> <b><span style="background:black; padding:5px; color:white;">PAYMENT
                RECEIPT</span> No. :</b><span style="color:red;"> <?php echo $payment_receipt["receipt_no"];?></span>
    </div>
    <div style="width:33%; display:inline-block;"> <b>Date :</b>
        <?php echo date_format(date_create($row_id),"d-M-Y, H:i");?></div>
    </p>
    <p>
    <div style="display:inline-block; width:25%;"><b>Received From : </b></div>
    <div style="display:inline-block; width:75%; border-bottom:1px solid; text-align:center;">
        <?php echo $payment_receipt["received_from"];?></div>
    </p>
    <p>
    <div style="display:inline-block; width:22%;"><b>Amount of : </b> </div>
    <div style="display:inline-block; width:78%; border-bottom:1px solid;">
        <?php echo numberTowords($payment_receipt["amount"]); ?></div>
    </p>
    <p>
    <div style="display:inline-block; width:74%; border-bottom:1px solid; text-align:center;">&nbsp;&nbsp;</div>
    <div style="border:1px solid black; width:24%; height:25px; text-align:center;display:inline-block;"><b>&nbsp;M </b>
        <?php echo number_format((float)$payment_receipt["amount"], 2, ".", ",");?></div>
    </p>
    <p>
    <div style="display:inline-block; width:31%;"><b>Booking Reference : </b> </div>
    <div style="display:inline-block; width:69%; text-align:center; margin-top:5px; border-bottom:1px solid;">
        <?php echo $booking_reference;?></div>
    </p>
    <p>
    <div style="display:inline-block; width:32%;"><b>Payment Reference : </b> </div>
    <div style="display:inline-block; width:68%; text-align:center; margin-top:5px; border-bottom:1px solid;">
        <?php echo $payment_receipt["reference"];?></div>
    </p>
    <p>
    <div style="display:inline-block; width:30%;"><b>Payment Method : </b> </div>
    <div style="display:inline-block; width:70%; text-align:center; margin-top:5px; border-bottom:1px solid;">
        <?php echo $payment_receipt["method"];?></div>
    </p>
    <p>
    <div style="display:inline-block; width:35%;"><b>Payment Received By : </b> </div>
    <div style="display:inline-block; width:65%; text-align:center; margin-top:5px; border-bottom:1px solid;">
        <?php echo $payment_receipt["received_by"];?></div>
    </p>
    <p>
        <center><b>*** Thank you for your business ***</b></center>
    </p>
</div>
<div>
    <img src='<?php echo WPCARGO_PLUGIN_URL."assets/images/receipt_footer.png";?>' width="100%;" height="250px;">
</div>
<?php   }
       else echo "<br>no payment data";
       echo '</div>';

    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_payment_singleview_action', 'payment_singleview_action_callback' );
add_action( 'wp_ajax_nopriv_payment_singleview_action', 'payment_singleview_action_callback' );

/**
 * Ajax Callback
 */
 function save_payment_action_callback(){
        $_POST=filter_var_array($_POST);
        $current_user = wp_get_current_user();
        $received_by = $current_user->display_name;
        $shipment_id =  sanitize_text_field($_POST['post_id']);
        $payment_time = date('H:i');
        $payment_date = sanitize_text_field($_POST['payment_date'])." ".$payment_time;
        $payment_method = sanitize_text_field($_POST["payment_method"]);
        $payment_reference = sanitize_text_field($_POST["payment_reference"]);
        $received_from = sanitize_text_field($_POST["received_from"]);
        $booking_reference = get_post_meta( $shipment_id, 'booking_reference', true );
        $msg = wpcargo_shipment_status_update($shipment_id,$current_user,"Payment Approved","Payment Approval");
        if($payment_method!="Account"){
            $payment_amount = sanitize_text_field($_POST["payment_amount"]);
            $payment_history = unserialize(get_post_meta( $shipment_id, 'wpcargo_payment_history', true ));
            $payment_no = (!is_array($payment_history))? 1 : count($payment_history)+1;
            $exploded_booking_reference = explode("-",$booking_reference);
            $receipt_no = "PAY-".$exploded_booking_reference[1]."-".$payment_no;
            $payment = array("method"=>$payment_method,"reference"=>$payment_reference,"received_from"=>$received_from,"receipt_no"=>$receipt_no,"amount"=>$payment_amount,"received_by"=>$received_by, "approval" => 1);
            $payment_history[$payment_date] = $payment;
            update_post_meta( $shipment_id, 'wpcargo_payment_history', maybe_serialize($payment_history));
            $msg="";
            //display current receipt
            //to load / display receipt
            $_POST['row_id'] = $payment_date;
            $_POST['shipment_id'] = $shipment_id;
            payment_singleview_action_callback();
       }
       else echo "Payment Approved by Account".
     wp_die(); // required. to end AJAX request.
 }
 /* Load Ajax Callback to "wp_ajax_*" Action Hook */
 add_action( 'wp_ajax_save_payment_action', 'save_payment_action_callback');
 add_action( 'wp_ajax_nopriv_save_payment_action', 'save_payment_action_callback');

/* Approve Client/Online Booking Payment */
function approve_payment_action_callback(){
  $_POST = filter_var_array($_POST);
  $current_user = wp_get_current_user();
  $received_by = $current_user->display_name;
  $shipment_id = sanitize_text_field($_POST['post_id']);
  $payment_date = sanitize_text_field($_POST['payment_date']);
	$pay_method = sanitize_text_field($_POST['revised_method']);
	$pay_amount = sanitize_text_field($_POST['revised_amount']);
	$received_from = sanitize_text_field($_POST['received_from']);
	$payment_reference = sanitize_text_field($_POST['payment_reference']);

  $payment_time = date('H:i');
  $payment_timestamp = $payment_date." ".$payment_time;
  $payment_history = unserialize(get_post_meta($shipment_id, 'wpcargo_payment_history', true ));
  $booking_reference = get_post_meta( $shipment_id, 'booking_reference', true );
  $payment_no = 1;
  $exploded_booking_reference = explode("-",$booking_reference);
  $receipt_no = "PAY-".$exploded_booking_reference[1]."-".$payment_no;
  $msg = wpcargo_shipment_status_update($shipment_id,$current_user,"Payment Approved","Payment Approval");


  foreach($payment_history AS $key => $values){

      if($values["approval"] == "0"){
			$reciept_no = $values["receipt_no"];
      $payment = array("method"=>$pay_method,"reference"=>$payment_reference,"received_from"=>$received_from,"receipt_no"=>$receipt_no,"amount"=>$pay_amount,"received_by"=>$received_by,"approval" => 1);
      $payment_history[$key] = $payment;
      update_post_meta( $shipment_id, 'wpcargo_payment_history', maybe_serialize($payment_history));


      }


  }

}
add_action( 'wp_ajax_approve_payment_action_callback', 'approve_payment_action_callback' );
add_action( 'wp_ajax_nopriv_approve_payment_action_callback', 'approve_payment_action_callback' );

/**
 * Ajax Callback
 */
function statement_more_action_callback(){
       $shipment_id =  sanitize_text_field(trim($_POST['post_id']));
       $payment_history = unserialize(get_post_meta( $shipment_id, 'wpcargo_payment_history', true ));
       $received_from = get_shipper_name($shipment_id);
       $settings_items = unserialize(get_settings_items()->meta_data);
       $wpcargo_invoice_date = get_post_meta( $shipment_id, 'wpcargo_invoice_date', true );
       $wpcargo_invoice_no = get_post_meta( $shipment_id, 'wpcargo_invoice_no', true );
       $invoice_details = unserialize(get_post_meta( $shipment_id, 'wpcargo_invoice', true ));
       //print_r($invoice_details);
       $invoice_amount = 0;
       foreach($invoice_details AS $key => $value){
             $op_sign = ($settings_items[$key]["item_type"]=="Expenditure") ? "-": "+";
             $invoice_amount = ($op_sign=="-")? $invoice_amount-(float)$invoice_details[$key]["total"] : $invoice_amount+(float)$invoice_details[$key]["total"];
       }
       $balance = 0;
       $booking_reference =  get_post_meta( $shipment_id, 'booking_reference', true );
       $wpcargo_receiver_company =  get_post_meta( $shipment_id, 'wpcargo_receiver_company', true );
       $url_barcode	= WPCARGO_PLUGIN_URL."/includes/barcode.php?codetype=Code128&size=60&text=" . $booking_reference . "";

     echo '<div id="receipt_display">';
     $booking_type = get_post_meta( $shipment_id, 'booking_type', true);
     $shipper = get_shipper_name($shipment_id);
     $author = ($booking_type=="Online")? $shipper : get_the_author_meta('display_name',get_post_field('post_author',$shipment_id));
     $booking_by = $author."(".$booking_type.")";

     $packages = count(wpcargo_get_package_data($shipment_id));
     $actual_weight = wpcargo_package_actual_weight( $shipment_id );
     $dm_weight = wpcargo_package_volumetric( $shipment_id );
      ?>
<div style="border-bottom: 4px solid;">
    <div style="width:69%; display:inline-block; height:120px;"> <img
            src='<?php echo WPCARGO_PLUGIN_URL."assets/images/receipt_head.png";?>' width="100%;" height="120px;"></div>
    <div class="print-tn"
        style="width:29%; display:inline-block; text-align:center; height:100px; vertical-align:top; padding-top:13px;">
        <?php echo $booking_reference;?><img src="<?php echo $url_barcode;?>" alt="<?php echo $booking_reference;?>"
            width="100%" height="70px;" /> </div>
</div>
<div class="print-body">
    <p>
    <div style="width:49%; display:inline-block; vertical-align:top;">
        <p class="black_bg" style="width:94%;"><b><?php echo $info['item_name']; ?></b></p>
        <div style=" border: solid 1px; width:90%; padding: 0 10px;">
            <p>
            <div style="width:22%; display:inline-block;"><b>Name: </b></div>
            <div style="width:78%; border-bottom:1px solid; display:inline-block;"> <?php echo $shipper;?></div>
            </p>
            <p>
            <div style="width:22%; display:inline-block;">&nbsp;</div>
            <div style="width:78%; border-bottom:1px solid; display:inline-block;"><br></div>
            </p>
            <p>
            <div style="width:22%; display:inline-block;"><b>Tel/Cel: </b></div>
            <div style="width:78%; border-bottom:1px solid; display:inline-block;">
                <?php echo get_post_meta( $shipment_id, 'wpcargo_receiver_phone_1', true );?></div>
            </p>
            <p>
            <div style="width:22%; display:inline-block;"><b>Email: </b></div>
            <div style="width:78%; border-bottom:1px solid; display:inline-block;">
                <?php echo get_post_meta( $shipment_id, 'wpcargo_receiver_email', true );?></div>
            </p>
        </div>
    </div>
    <div style="width:49%; display:inline-block; vertical-align:top;">
        <p>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Date :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">
            &nbsp;<?php echo date_format(date_create($info['item_date']),"d-M-Y");?></div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Booking Ref :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $booking_reference; ?>
        </div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Route :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">
            &nbsp;<?php echo get_post_meta( $shipment_id, 'route_abrs', true ); ?></div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Packages :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $packages; ?></div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Actual Weight :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $actual_weight; ?></div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>DM Weight :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $dm_weight; ?></div>
        <div style="width:35%; display:inline-block; text-align: right;"><b>Booking By :&nbsp;</b></div>
        <div style="width:65%; border-bottom:1px solid; display:inline-block;">&nbsp;<?php echo $booking_by; ?></div>
        </p>
    </div>
    </p>
    <?php
       echo'
        <table class="grid_table" style="font-size: 14px; width:100%;">
        <thead><tr><td style="font-size:18px; font-weight:700;">DATE</td><td style="font-size:18px; font-weight:700;">TRANSACTION TYPE</td><td style="font-size:18px; font-weight:700;">REFERENCE</td><td style="font-size:18px; font-weight:700;">AMOUNT</td><td style="font-size:18px; font-weight:700;">BALANCE</td></tr></thead>
        <tbody>';
               //start listing table items
               $invoice_displayed_flag=0;
               if(is_array($payment_history)) {
                   foreach($payment_history AS $key => $values){
                      //display invoice on condition
                      if(!empty($wpcargo_invoice_no) && $invoice_displayed_flag==0 && ($wpcargo_invoice_date < $key) ){
                        $balance+=(float)str_replace(",","",$invoice_amount);
                        echo '<tr class="row"><td>'.date_format(date_create($wpcargo_invoice_date),"d-M-Y , H :i").'</td><td>Invoice</td><td>'.$wpcargo_invoice_no.'</td><td>M '.number_format($invoice_amount, 2, ".", ",").'</td><td>M '.number_format($balance, 2, ".", ",").'</td></tr>';
                        $invoice_displayed_flag=1;}
                      //display payment
                      $balance-=(float)str_replace(",","",$values['amount']);
                      echo '<tr class="row"><td>'.date_format(date_create($key),"d-M-Y , H :i").'</td><td> Payment</td><td>'.$values["receipt_no"].'</td><td>M '.number_format($values["amount"], 2, ".", ",").'</td><td>M '.number_format($balance, 2, ".", ",").'</td></tr>';
               }  }
               // just display invoice if it exists and it is not yet displayed
               if(!empty($wpcargo_invoice_no) && $invoice_displayed_flag==0) {
                    $balance+=(float)str_replace(",","",$invoice_amount);
                    echo '<tr class="row"><td>'.date_format(date_create($wpcargo_invoice_date),"d-M-Y , H :i").'</td><td>Invoice</td><td>'.$wpcargo_invoice_no.'</td><td>M '.number_format($invoice_amount, 2, ".", ",").'</td><td>M '.number_format($balance, 2, ".", ",").'</td></tr>';
               }
              //end of table list
          echo'
             </tbody><tfoot>
               <tr><td><br></td><td><br></td><td><br></td><td><br></td></tr>
               <tr><td colspan="2"></td><td colspan="2" style="border-top: solid 2px; text-align:center; font-size:14px;background: #D3D3D3;"><b>BALANCE</b></td><td style="border-top: solid 2px; border-bottom: double 2px; font-size:14px; background: #D3D3D3;"> M <b id="total">'.number_format($balance, 2, '.', ',').'</b></td></tr>
            </tfoot></table>';
              ?>

</div>
<div>
    <p>
    <div style="width:59%; display:inline-block; vertical-align:top; margin-right:4%;">
        <p style="border-top: solid 3px; margin-top: 25px; padding: 20px 2px;">Unless on the basis of an account, an
            invoice must be fully settled upon collection or delivery of goods to the final destination. proof of
            payment must be emailed to: <b>accounts@sky266.co.ls</b></p>
    </div>
    <div style="width:35%; display:inline-block; vertical-align:top;">
        <p><b>STANDARD LESOTHO BANK</b><br>
            <b style="margin-left: 18px;">NAME: </b> Sky-Corp t/a Sky266<br>
            <b style="margin-left: 18px;">ACCOUNT #: </b> 9080007532411<br>
            <b style="margin-left: 18px;">BRANCH: </b> Tower (062067)<br>
            <b>MPESA: </b> 57555325<br>
            <b>ECOCASH: </b> 62555325
        </p>
    </div>
    </p>
</div>
<?php  wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_statement_more_action', 'statement_more_action_callback' );
add_action( 'wp_ajax_nopriv_statement_more_action', 'statement_more_action_callback' );


/***********************************
            Trips
************************************/
/**
 * Ajax Callback
 */
function trip_form_action_callback(){
     global $wpdb;
     $index = ($_POST['index']!="") ? $_POST['index'] : -9;
     $current_form = sanitize_text_field(trim($_POST['current_form']));
     if($current_form == "new_trip_form"){ $heading = "<h1>Adding New Trip</h1>"; }
     if($current_form == "edit_trip_form"){ $heading = '<h1>Editing Trip Details</h1>'; }
     $trips = $wpdb->get_results( "SELECT * FROM trips WHERE id = '$index' ");
     foreach ( $trips as $trip ) {
          $trip_name = $trip->trip_name;
          $trip_datetime = $trip->trip_date;
          $selected_schedules = $trip->city_schedules;
        }
     echo $heading;
        ?><table class="form-table" style='border:solid 1px gray;'>
    <input type="hidden" id="trip_id" name="trip_id" value="<?php echo $index; ?>">
    <tr>
        <th style="width:20%;">Trip Name</th>
        <td style="width:70%;"><input style="width:50%;" type='text' id="trip_name" name='trip_name'
                value='<?php echo $trip_name; ?>' required placeholder="Trip Name"></td>
    </tr>
    <?php $temp = explode(' ',$trip_datetime); $trip_date = $temp[0]; $trip_time = $temp[1];  ?>
    <tr>
        <th style="width:20%;">Trip Date</th>
        <td style="width:80%;"><input style="width:50%;" type='date' id='trip_date' name='trip_date'
                value='<?php echo $trip_date;?>'><input type='time' id='trip_time' name='trip_time'
                value='<?php echo $trip_time;?>' hidden></td>
    </tr>
    <tr>
        <th style="width:20%;">Driver</th>
        <td style="width:50%;">

            <div id="group_items" style="width:50%;">
                <div>
                    <select class="form-control" name="item_id" id="item_id" onchange="items_grouper(this);"
                        style="width: 100%;">
                        <option value="">Select Item</option>
                        <?php
                                                     $drivers = get_users( [ 'role__in' => [ 'wpcargo_driver' ] ] );
                                                     foreach ( $drivers as $driver ) { $rw_id = str_replace(" ", "", esc_html($driver->display_name));
                                                          echo '<option value="'.esc_html($driver->display_name).'" id="'.$rw_id.'" >'.esc_html( $driver->display_name ).'</option>';
                                                      }
                                                  ?>
                    </select> <br>
                </div>

                <table id="selected_drivers_list">
                    <?php
                                          $selected_drivers_text = unserialize($trip->drivers);
                                          if(!empty($selected_drivers_text)) {
                                             $selected_drivers = explode(",",$selected_drivers_text);
                                             foreach($selected_drivers AS $selected_driver){
                                               $rw_id = str_replace(" ", "", $selected_driver);
                                               echo '<tr id="'.$rw_id.'"><td>'.$selected_driver.'&nbsp;<a href="#" style="color:red;" id="'.$rw_id.'" onclick="remove_item(this)" >X</a></td></tr>';
                                               }
                                          }
                                       ?>
                </table>
                <input type="hidden" id="selected_drivers" name="selected_drivers"
                    value="<?php echo $selected_drivers_text; ?>">
                <br>
            </div>


        </td>
    </tr>
    <tr>
        <td colspan="2">
            <label for="sales_rep"><strong>COVERED CITIES</strong></label>
            <table id="group_items_list">
                <?php
                          $selected_schedules_r = unserialize($selected_schedules);
                       if(is_array($selected_schedules_r)) {
                          foreach($selected_schedules_r AS $selected_schedule)  {
                        ?>

                <tr id="fields_row">
                    <td><input type='hidden' id='selected_schedule' name='selected_schedule[]' value=''>
                        <select id='schedule_city' name='schedule_city[]' onchange="schedule_city_select(this)">
                            <option value="">--Select City--</option>
                            <?php
                                        $schedule_cities = $wpdb->get_results( "SELECT id, city_name FROM `countries_cities`");
                                        foreach($schedule_cities AS $schedule_city){   ?>
                            <option value="<?php echo $schedule_city->id ?>"
                                <?php echo ($schedule_city->id == $selected_schedule['schedule_city'] )? "selected": ""; ?>>
                                <?php echo $schedule_city->city_name; ?></option>
                            <?php  } ?>
                        </select>
                    </td>
                    <td>
                        <select id='schedule_id' name='schedule_id[]'>
                            <option value="">--Select Schedule--</option>
                            <?php
                                        $current_datetime = date('Y-m-d H:i:s');
                                        $schedule_id = $selected_schedule['schedule_id'];
                                        $selected_schedule_details = $wpdb->get_results("SELECT * FROM collection_schedules WHERE id = '$schedule_id'");
                                        $city = $wpdb->get_results("SELECT * FROM countries_cities WHERE id='".$selected_schedule_details[0]->schedule_city."'");
                                        $option_label = ($city[0]->country_depot == 1)? $city[0]->city_name." Daily" : $selected_schedule_details[0]->schedule_name."(".date_format(date_create($selected_schedule_details[0]->schedule_date),'d-F-Y').")";
                                        if($selected_schedule_details[0]->final_cut_off <= $current_datetime)
                                            echo "<option id='".$selected_schedule_details[0]->schedule_city."' value='".$selected_schedule_details[0]->id."' selected >".$option_label."</option>";

                                        //$schedules = $wpdb->get_results( "SELECT * FROM collection_schedules WHERE status!='Closed' AND status!='Terminated' ORDER BY schedule_date ASC ");
                                        $schedules = $wpdb->get_results("SELECT *
                                             FROM (
                                               select *,
                                                      row_number() over (partition by tbl1.schedule_city order by tbl1.schedule_date asc) as rn
                                               From collection_schedules AS tbl1 WHERE status!='Closed' AND status!='Terminated'
                                              ) as tbl1
                                             where tbl1.rn <=2
                                          ");
                                        foreach($schedules AS $schedule){
                                          $final_cut_off = $schedule->final_cut_off;
                                          if($final_cut_off <= $current_datetime){
                                               update_schedule_status($schedule);
                                          }  ?>
                            <option id="<?php echo $schedule->schedule_city; ?>" value="<?php echo $schedule->id; ?>"
                                <?php echo ($schedule->id == $selected_schedule['schedule_id'] )? "selected": ""; ?>>
                                <?php echo $schedule->schedule_name."(".date_format(date_create($schedule->schedule_date),'d-F-Y').")"; ?>
                            </option>
                            <?php }  ?>
                            <option id="optionslabel" value="" disabled="disabled">Old Schedules</option>
                            <?php
                                      //if ( in_array( 'administrator', (array) $current_user->roles ) || in_array( 'wpcargo_manager', (array) $current_user->roles ) ) {
                                        // $oldschedules = $wpdb->get_results("SELECT * FROM collection_schedules WHERE status LIKE 'Closed' ORDER BY schedule_date DESC Limit 15 ");
                                        $oldschedules = $wpdb->get_results("SELECT *
                                             FROM (
                                               select *,
                                                      row_number() over (partition by tbl1.schedule_city order by tbl1.schedule_date desc) as rn
                                               From collection_schedules AS tbl1 WHERE status LIKE 'Closed'
                                              ) as tbl1
                                             where tbl1.rn <=2
                                          ");
                                        foreach($oldschedules AS $schedule){
                                          $current_datetime = date('Y-m-d H:i:s');
                                          $final_cut_off = $schedule->final_cut_off;
                                          $city = $wpdb->get_results("SELECT * FROM countries_cities WHERE id='".$schedule->schedule_city."'");
                                          $option_label = ($city[0]->country_depot == 1)? $city[0]->city_name." Daily" : $schedule->schedule_name."(".date_format(date_create($schedule->schedule_date),'d-F-Y').")";
                                          if($final_cut_off <= $current_datetime){
                                              update_schedule_status($schedule);
                                          }
                                           ?>
                            <option id="<?php echo $schedule->schedule_city; ?>" value="<?php echo $schedule->id; ?>">
                                <?php echo $option_label; ?></option>
                            <?php }
                                         //}
                                       ?>
                        </select>
                    </td>
                    <td><a href="#" style="color: red; text-decoration: none;" onclick="remove_schedule(this)">X</a>
                    </td>
                </tr>

                <?php } }
                       else { ?>
                <tr id="fields_row">
                    <td><input type='hidden' id='selected_schedule' name='selected_schedule[]' value=''>
                        <select id='schedule_city' name='schedule_city[]' onchange="schedule_city_select(this)">
                            <option value="">--Select City--</option>
                            <?php
                                        $schedule_cities = $wpdb->get_results( "SELECT id, city_name FROM `countries_cities`");
                                        foreach($schedule_cities AS $schedule_city){   ?>
                            <option value="<?php echo $schedule_city->id ?>"><?php echo $schedule_city->city_name ?>
                            </option>
                            <?php  } ?>
                        </select>
                    </td>
                    <td>
                        <select id='schedule_id' name='schedule_id[]'>
                            <option value="">--Select Schedule--</option>
                            <?php
                                        //get and display upcoming and active schedules
                                         $schedules = $wpdb->get_results("SELECT *
                                             FROM (
                                               select *,
                                                      row_number() over (partition by tbl1.schedule_city order by tbl1.schedule_date asc) as rn
                                               From collection_schedules AS tbl1 WHERE status!='Closed' AND status!='Terminated'
                                              ) as tbl1
                                             where tbl1.rn <=2
                                          ");
                                        foreach($schedules AS $schedule){
                                          $current_datetime = date('Y-m-d H:i:s');
                                          $final_cut_off = $schedule->final_cut_off;
                                          if($final_cut_off <= $current_datetime){
                                              update_schedule_status($schedule);
                                          }
                                           ?>
                            <option id="<?php echo $schedule->schedule_city; ?>" value="<?php echo $schedule->id; ?>">
                                <?php echo $schedule->schedule_name."(".date_format(date_create($schedule->schedule_date),'d-F-Y').")"; ?>
                            </option>
                            <?php }  ?>
                            <option id="optionlabel" value="" disabled="disabled">Old Schedules</option>
                            <?php
                                      //if ( in_array( 'administrator', (array) $current_user->roles ) || in_array( 'wpcargo_manager', (array) $current_user->roles ) ) {
                                        // $oldschedules = $wpdb->get_results("SELECT * FROM collection_schedules WHERE status LIKE 'Closed' ORDER BY schedule_date DESC Limit 15 ");
                                       //get and display closed schedules
                                       $oldschedules = $wpdb->get_results("SELECT *
                                             FROM (
                                               select *,
                                                      row_number() over (partition by tbl1.schedule_city order by tbl1.schedule_date desc) as rn
                                               From collection_schedules AS tbl1 WHERE status LIKE 'Closed'
                                              ) as tbl1
                                             where tbl1.rn <=2
                                          ");

                                      //}
                                        foreach($oldschedules AS $schedule){
                                          $current_datetime = date('Y-m-d H:i:s');
                                          $final_cut_off = $schedule->final_cut_off;
                                          $city = $wpdb->get_results("SELECT * FROM countries_cities WHERE id='".$schedule->schedule_city."'");
                                          $option_label = ($city[0]->country_depot == 1)? $city[0]->city_name." Daily" : $schedule->schedule_name."(".date_format(date_create($schedule->schedule_date),'d-F-Y').")";
                                          if($final_cut_off <= $current_datetime){
                                              update_schedule_status($schedule);
                                          }
                                           ?>
                            <option id="<?php echo $schedule->schedule_city; ?>" value="<?php echo $schedule->id; ?>">
                                <?php echo $option_label; ?></option>
                            <?php }

                                       ?>
                        </select>
                    </td>
                    <td><a href="#" style="color: red; text-decoration: none;" onclick="remove_schedule(this)">X</a>
                    </td>
                </tr>
                <?php }  ?>
            </table>
            <a href="#" onclick="add_row()">Add Row</a>
        </td>
    </tr>
    <!--tr><td colspan='2'><center><input class='button' type='submit' id="submit" name='submit' value='Save'></td></tr-->
</table>
<?php
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_trip_form_action', 'trip_form_action_callback' );
add_action( 'wp_ajax_nopriv_trip_form_action', 'trip_form_action_callback' );


/**
 * Ajax Callback
 */
function trip_report_action_callback(){
     global $wpdb;
     $index = sanitize_text_field(trim($_POST['index']));
     $trips = $wpdb->get_results( "SELECT * FROM trips WHERE id = '$index' ");
     foreach ( $trips as $trip ) {
     echo '<h1>Trip Report</h1>';
        ?><table class="report-table" style='border:solid 1px gray; width: 95%; padding:20px; '>
    <tr>
        <td style="width:15%; font-weight: 700;">Trip Name :</td>
        <td style="width:70%;"><?php echo $trip->trip_name; ?></td>
    </tr>
    <?php $temp = explode(' ',$trip->trip_date); $trip_date = $temp[0]; $trip_time = $temp[1];  ?>
    <tr>
        <td style="width:15%; font-weight: 700;">Trip Date :</td>
        <td style="width:70%;"><?php echo date_format(date_create($trip_date." ".$trip_time),'d-M-Y');?></td>
    </tr>
    <tr>
        <td style="width:15%; font-weight: 700;">Allocated Driver :</td>
        <td style="width:70%;"><?php
                   $selected_drivers = unserialize($trip->drivers);
                   echo (!empty($selected_drivers)) ? $selected_drivers : "None";
                   ?></td>
    </tr>
    <tr>
        <td style="width:15%; font-weight: 700;">Trip Status :</td>
        <td style="width:70%;"><?php echo $trip->status;?></td>
    </tr>
    <tr>
        <td colspan="2"> <br><br>
            <label style="font-weight: 700;" for="sales_rep">COVERED CITIES</label>
            <hr />
            <table style="width: 90%;">
                <tr>
                    <th style="width:20%; text-align: left;">City Name</th>
                    <th style="width:30%; text-align: left;">Schedule</th>
                    <th style="width:10%; text-align: left;">Bookings</th>
                    <th style="width:10%; text-align: left;">Collections Amount</th>
                    <th style="width:10%; text-align: left;">Status</th>
                </tr>

                <?php $selected_schedules = unserialize($trip->city_schedules);
                            $cities=array();
                            $trip_bookings_no = 0;
                            $trip_expected_amount = 0;
                            if(is_array($selected_schedules)){
                              foreach($selected_schedules AS $selected_schedule){
                                  $schedule_expected_amount = 0;
                                  $schedules = $wpdb->get_results( "SELECT * FROM countries_cities JOIN collection_schedules WHERE collection_schedules.id = '".$selected_schedule['schedule_id']."' AND countries_cities.id= '".$selected_schedule['schedule_city']."'");
                                  foreach($schedules AS $schedule){
                                     $schedule_id = $selected_schedule['schedule_id'];
                                     $bookings = schedule_summary($schedule_id,$trip->id);
                                     $trip_expected_amount+=(float)$bookings['invoices_total_amount'];
                                     $trip_bookings_no+=$bookings['num_of_posts'];
                                   ?>
                <tr>
                    <td><?php  echo $schedule->city_name;?></td>
                    <td><?php echo $schedule->schedule_name."(".date_format(date_create($schedule->schedule_date),'d-M-Y').")"; ?>
                    </td>
                    <td><?php echo $bookings['num_of_posts']; ?></td>
                    <td><?php echo "M ".number_format((float)$bookings['invoices_total_amount'], 2, '.', ','); ?></td>
                    <td
                        style='<?php echo ($schedule->status=="Active")?"background-color: Green; color:white;" :(($schedule->status=="Upcoming")?"background-color: orange; color:white;":"background-color: black; color:white" ); ?>'>
                        <?php echo $schedule->status; ?></td>
                </tr>
                <?php } }}  ?>
                <tr>
                    <td colspan="5" style="border-bottom: solid 1px;"><br></td>
                </tr>
                <tr>
                    <td colspan="2"><b><b>Trip Totals</b></b></td>
                    <td><b><?php echo $trip_bookings_no; ?></b></td>
                    <td><b><?php echo "M ".number_format((float)$trip_expected_amount, 2, '.', ','); ?></b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php   }
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_trip_report_action', 'trip_report_action_callback' );
add_action( 'wp_ajax_nopriv_trip_report_action', 'trip_report_action_callback' );
/**
 * Ajax Callback
 */
function delete_trip_action_callback(){
     global $wpdb;
     $index = sanitize_text_field(trim($_POST['index']));
     $wpdb->delete(
        'trips',
        array(
            'id' => $index
        )
     );
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_delete_trip_action', 'delete_trip_action_callback' );
add_action( 'wp_ajax_nopriv_delete_trip_action', 'delete_trip_action_callback' );
/**
 * Ajax Callback
 */
function terminate_trip_action_callback(){
     global $wpdb;
     $trip_id = sanitize_text_field(trim($_POST['trip_id']));
     $trip_name = sanitize_text_field(trim($_POST['trip_name']));
     $comments = sanitize_text_field(trim($_POST['comments']));
     $wpdb->update(
      'trips',
       array(
    			'status' => 'Terminated',
                'comments' => $comments,
    		),
       array(
			'id' => $trip_id
		)
      );
      echo $trip_name." Terminated!";
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_terminate_trip_action', 'terminate_trip_action_callback' );
add_action( 'wp_ajax_nopriv_terminate_trip_action', 'terminate_trip_action_callback' );


/**
 * Ajax Callback
 */
function trip_status_update_action_callback(){
     $shipment_status = sanitize_text_field($_POST['shipment_status']);
   ?>
<p>Current Status: <?php echo "<b>".$shipment_status."</b>"; ?></p>
<input type="hidden" id="trip_id" name="trip_id" value="<?php echo sanitize_text_field($_POST['trip_id']); ?>">
<input type="hidden" id="row_index" name="row_index" value="<?php echo sanitize_text_field($_POST['row_index']); ?>">
<label class="form-label"><?php esc_html_e('Select New Status:','wpcargo'); ?></label>
<select id="new_shipment_status" required name="new_shipment_status">
    <option value="">-- Select One --</option>
    <?php  $arr = array("Upcoming","Active","Closed");
             foreach($arr AS $status){  ?>
    <option <?php echo ( $shipment_status == $status) ? 'hidden' : '' ; ?>><?php echo $status; ?></option>
    <?php    }
       ?>
</select>
<br><br>
<?php wp_die(); // required. to end AJAX request.
}
add_action( 'wp_ajax_trip_status_update_action', 'trip_status_update_action_callback' );
add_action( 'wp_ajax_nopriv_trip_status_update_action', 'trip_status_update_action_callback' );

/**
 * Ajax Callback
 */
function trips_admin_form_save_action_callback(){
     global $wpdb;
     $trip_id = sanitize_text_field($_POST['trip_id']);
     $trip_name = sanitize_text_field($_POST['trip_name']);
     $trip_date = sanitize_text_field( $_POST["trip_date"] );
     $selected_drivers = sanitize_text_field($_POST['selected_drivers']);
     //$driver = sanitize_text_field($_POST['driver']);
     $current_form = sanitize_text_field( $_POST["current_form"] );

    // sanitize form values
        $i=0; $selected_schedules;
        while(isset($_POST["schedule_city"][$i]) && $_POST["schedule_city"][$i]!="" && isset($_POST["schedule_id"][$i]) && $_POST["schedule_id"][$i]!="")  {
            $schedule_city = sanitize_text_field( $_POST["schedule_city"][$i] );
            $schedule_id = sanitize_text_field( $_POST["schedule_id"][$i] );
            $schedule_item = array('schedule_city'=>$schedule_city, 'schedule_id'=>$schedule_id);
            $selected_schedules[] = $schedule_item;
        $i++; }
        $selected_schedules = serialize($selected_schedules);

    //save form inputs
      if($current_form=="new_trip_form"){
              $wpdb->insert(
            		'trips',
                	array(
                			'id' => '',
                			'trip_name' => $trip_name,
                			'routes_ids' => '',
                			'routes_data' => 'routes_data',
                			'trip_date' => $trip_date,
                			'drivers' => serialize($selected_drivers),
                            'city_schedules' => $selected_schedules,
                            'status'  => 'Upcoming',
                            'comments' => '',
                            'extra_info' => '',
                		)
            	);
            }
          else if($current_form=="edit_trip_form"){
                $wpdb->update(
                      'trips',
                	   array(
                			'trip_name' => $trip_name,
                			'trip_date' => $trip_date,
                			'drivers' => serialize($selected_drivers),
                            'city_schedules' => $selected_schedules,
                    	 ),
                       array(
                			'id' => $trip_id
                		)
                 );
           }
      echo "Trip details Successfully Saved ";


    //print_r((json_decode(stripslashes($trips_admin_form), true)));
    //$shipment_id = sanitize_text_field($_POST['shipment_id']);
    //update_post_meta( $shipment_id, 'shipment_trip_id', $selected_trip);
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_trips_admin_form_save_action', 'trips_admin_form_save_action_callback' );
add_action( 'wp_ajax_nopriv_trips_admin_form_save_action', 'trips_admin_form_save_action_callback' );


/***********************************
            Schedules
************************************/
/**
 * Ajax Callback
 */
function schedule_form_action_callback(){
     global $wpdb;
     $schedule_id = sanitize_text_field(trim($_POST['index']));
     $current_form = sanitize_text_field(trim($_POST['current_form']));
     if($current_form == "new_schedule_form"){ $heading = "<h1>Adding New Schedule</h1>"; }
     if($current_form == "edit_schedule_form"){ $heading = '<h1>Editing Schedule Details</h1>'; }
     $schedules = $wpdb->get_results( "SELECT * FROM collection_schedules WHERE id = '$schedule_id' ");
     foreach ( $schedules as $schedule ) {
          $schedule_city = $schedule->schedule_city;
          $schedule_name = $schedule->schedule_name;
          $schedule_datetime = $schedule->schedule_date;
          $late_cut_off_datetime = $schedule->late_cut_off;
          $final_cut_off_datetime = $schedule->final_cut_off;
        }
     echo $heading;
        ?><table class="form-table" style='border:solid 1px gray;'>
    <input type="hidden" id="schedule_id" name="schedule_id" value="<?php echo $schedule_id; ?>">
    <tr>
        <th style="width:20%;">Schedule Name </th>
        <td style="width:70%;"><input style="width:50%;" type='text' id="schedule_name" name='schedule_name'
                value='<?php echo $schedule_name; ?>' placeholder="Name of Collection Schedule"></td>
    </tr>
    <tr>
        <th style="width:20%;">Schedule City</th>
        <td style="width:80%;">
            <select style="width:50%;" id="schedule_city" name="schedule_city">
                <option value="">--Select City--</option>
                <?php $cities = $wpdb->get_results( "SELECT id,city_name FROM countries_cities");
                          foreach ( $cities as $city ) { ?>
                <option value='<?php echo $city->id; ?>' <?php echo ($city->id==$schedule_city)? "selected": ""; ?>>
                    <?php echo $city->city_name; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <?php $temp = explode(' ',$schedule_datetime); $schedule_date = $temp[0]; $schedule_time = $temp[1];
                  $temp = explode(' ',$late_cut_off_datetime); $late_threshold_date =  $temp[0]; $late_threshold_time = $temp[1];
                  $temp = explode(' ',$final_cut_off_datetime); $cut_off_date = $temp[0]; $cut_off_time = $temp[1];
             ?>
    <tr>
        <th style="width:20%;">Scheduled Date</th>
        <td style="width:80%;"><input style="width:50%;" type='date' id='schedule_date' name='schedule_date'
                value='<?php echo $schedule_date;?>' required><input type='time' id='schedule_time' name='schedule_time'
                value='<?php echo $schedule_time;?>' hidden></td>
    </tr>
    <?php if($current_form == "new_schedule_form") {  ?>
    <tr>
        <th style="width:20%;">Repeat Method</th>
        <td style="width:80%;">
            <select style="width:50%;" id="schedule_type" name="schedule_type" onchange="toggle_schedule_repeat(this)">
                <option>Once Off</option>
                <!--option>Daily</option-->
                <option>Weekly</option>
                <option>Monthly</option>
            </select>
        </td>
    </tr>
    <?php } ?>
    <tr id="repeat_times_tr" style="display: none;">
        <th colspan="2">Repeat this schedule &nbsp;<input style="width:35%;" type='text' id="repeat_times"
                name="repeat_times" value="1"><b> times.</b></th>
    </tr>
    <tr>
        <th style="width:20%;">Cut Time</th>
        <td style="width:80%;"><input style="width:30%; padding: 0px;" type='date' id='late_threshold_date'
                name='late_threshold_date' value='<?php echo $late_threshold_date; ?>'><input
                style="width:20%; padding: 0px;" type='time' id='late_threshold_time' name='late_threshold_time'
                value='<?php echo $late_threshold_time; ?>'></td>
    </tr>
    <tr>
        <th style="width:20%;">Final Cut Time</th>
        <td style="width:80%;"><input style="width:30%; padding: 0px;" type='date' id='cut_off_date' name='cut_off_date'
                value='<?php echo $cut_off_date; ?>'><input style="width:20%; padding: 0px;" type='time'
                id='cut_off_time' name='cut_off_time' value='<?php echo $cut_off_time; ?>'> </td>
    </tr>

</table>
<?php
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_schedule_form_action', 'schedule_form_action_callback' );
add_action( 'wp_ajax_nopriv_schedule_form_action', 'schedule_form_action_callback' );

/**
 * Ajax Callback
 */
function schedule_admin_form_save_action_callback(){
   global $wpdb;
        // sanitize form values
        $schedule_id   = sanitize_text_field($_POST['schedule_id']);
        $current_form  = sanitize_text_field( $_POST["current_form"] );
        $schedule_name = sanitize_text_field( $_POST["schedule_name"] );
        $schedule_city = sanitize_text_field( $_POST["schedule_city"] );
        $schedule_date = sanitize_text_field( $_POST["schedule_date"] );
        $schedule_time = sanitize_text_field( $_POST["schedule_time"] );
        $schedule_type = sanitize_text_field( $_POST["schedule_type"] );
        $repeat_times  = (int)sanitize_text_field( $_POST["repeat_times"] );
        $late_cut_off  = date('Y-m-d H:i', strtotime(sanitize_text_field( $_POST["late_threshold_date"])." ".sanitize_text_field( $_POST["late_threshold_time"])));

        $final_cut_off = date('Y-m-d H:i', strtotime(sanitize_text_field( $_POST["cut_off_date"])." ".sanitize_text_field( $_POST["cut_off_time"])));
        $repeat_times = ($repeat_times>1)? $repeat_times : 1;
        $schedule_date = date(str_replace("/","-",$schedule_date));

        for($j=0; $j<$repeat_times; $j++) {
          if($current_form=="new_schedule_form"){
              $wpdb->insert(
            		'collection_schedules',
                	array(
                			'id' => '',
                			'schedule_name' => $schedule_name,
                			'schedule_city' => $schedule_city,
                			'schedule_date' => $schedule_date." ".$schedule_time,
                			'late_cut_off' => $late_cut_off,
                			'final_cut_off' => $final_cut_off,
                            'status'  => 'Upcoming',
                            'comments'  => '',
                		)
            	);
            }
            else if($current_form=="edit_schedule_form"){
                $wpdb->update(
                      'collection_schedules',
                       array(
                    			'schedule_name' => $schedule_name,
                    			'schedule_city' => $schedule_city,
                    			'schedule_date' => $schedule_date." ".$schedule_time,
                    			'late_cut_off' => $late_cut_off,
                    			'final_cut_off' => $final_cut_off,
                    		),
                       array(
                			'id' => $schedule_id
                		)
                 );
            }
            else if($current_form=="main_schedule_edit_form"){

                $schedule_name  = sanitize_text_field( $_POST["schedule_name"] );
                $schedule_city  = sanitize_text_field( $_POST["schedule_city"] );
                $j = 0;
                while(isset($_POST["schedule_id"][$j]) && $_POST["schedule_id"][$j]!=""){
                  $current_datetime = date('Y-m-d H:i:s');
                  $schedule_id = sanitize_text_field( $_POST["schedule_id"][$j] );
                  $schedule_date = sanitize_text_field( $_POST["schedule_date"][$j] )." ".sanitize_text_field( $_POST["schedule_time"][$j] );
                  $late_cut_off = sanitize_text_field( $_POST["late_threshold_date"][$j])." ".sanitize_text_field( $_POST["late_threshold_time"][$j]);
                  $final_cut_off = sanitize_text_field( $_POST["cut_off_date"][$j] )." ".sanitize_text_field( $_POST["cut_off_time"][$j] );

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
                  } else {

                     $wpdb->update(
                      'collection_schedules',
                       array(
                    			'schedule_name' => $schedule_name,
                    			'schedule_city' => $schedule_city,
                    			'schedule_date' => $schedule_date,
                    			'late_cut_off' => $late_cut_off,
                    			'final_cut_off' => $final_cut_off,
                    		),
                       array(
                			'id' => $schedule_id
                		)
                      );
                   }

               $j++; }
            }

            if($schedule_type == "Daily"){
              //make this increment as another incidence closes
              echo $late_cut_off."<br>";
               $schedule_date = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($schedule_date)));
               $late_cut_off = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($late_cut_off)));
               $final_cut_off = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($final_cut_off)));
             }
            else if($schedule_type == "Weekly"){
                   $schedule_date = date('Y-m-d H:i:s', strtotime('+1 week', strtotime($schedule_date)));
                   $late_cut_off = date('Y-m-d H:i:s', strtotime('+1 week', strtotime($late_cut_off)));
                   $final_cut_off = date('Y-m-d H:i:s', strtotime('+1 week', strtotime($final_cut_off)));

             }
            else if($schedule_type == "Monthly"){
                   $schedule_date = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($schedule_date)));
                   $late_cut_off = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($late_cut_off)));
                   $final_cut_off = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($final_cut_off)));
            }
        }
      $msg = "Collection Schedule Successfully Saved";

    //print_r((json_decode(stripslashes($trips_admin_form), true)));
    //$shipment_id = sanitize_text_field($_POST['shipment_id']);
    //update_post_meta( $shipment_id, 'shipment_trip_id', $selected_trip);
    echo $msg;
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_schedule_admin_form_save_action', 'schedule_admin_form_save_action_callback' );
add_action( 'wp_ajax_nopriv_schedule_admin_form_save_action', 'schedule_admin_form_save_action_callback' );

/**
 * Ajax Callback
 */
function schedule_report_action_callback(){
     global $wpdb;
     $schedule_id = sanitize_text_field(trim($_POST['index']));
     $schedules = $wpdb->get_results( "SELECT * FROM collection_schedules WHERE id = '$schedule_id' ");
     $settings_items = unserialize(get_settings_items()->meta_data);
     foreach ( $schedules as $schedule ) {
       $table_rows = "";  $expected_amount = 0;
       $number_collection = $amount_collection =0;
       $number_delivery = $amount_delivery =0;
       $schedule_id = $schedule->id;
       $bookings= $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}posts` AS tbl1 JOIN `{$wpdb->prefix}postmeta` AS tbl2 ON tbl1.ID = tbl2.post_id
                                       WHERE  (tbl1.post_status LIKE 'publish' OR tbl1.post_status LIKE 'archive')
                                       AND tbl1.post_type LIKE 'wpcargo_shipment'
                                       AND ((tbl2.meta_key LIKE 'collection_schedule_id' AND tbl2.meta_value LIKE '$schedule_id')
                                            OR (tbl2.meta_key LIKE 'delivery_schedule_id' AND tbl2.meta_value LIKE '$schedule_id'))");

      $bookings_no = count($bookings);
       $schedule_city = $wpdb->get_results( "SELECT city_name FROM `countries_cities` WHERE id = '".$schedule->schedule_city."'");
       foreach ( $bookings as $booking ) {
             $post_id = $booking->ID;
             $wpcargo_receiver_company = (!empty($wpcargo_receiver_companyname)) ? $wpcargo_receiver_companyname : $wpcargo_receiver_fname." ".$wpcargo_receiver_sname;
             $status = get_post_meta($post_id, 'wpcargo_status', true);
             $service_type = (get_post_meta($post_id, 'collection_schedule_id', true)==$schedule_id) ? "Collection": "Delivery";
             $client = (!empty(get_post_meta( $post_id, 'wpcargo_receiver_company', true)))? get_post_meta( $post_id, 'wpcargo_receiver_company', true) : get_post_meta($post_id, 'wpcargo_receiver_fname', true)." ".get_post_meta($post_id, 'wpcargo_receiver_sname', true);
             $color = ($status=="Active")?"background-color: Green; color:white;" :(($status=="Pending")?"background-color: orange; color:white;":"background-color: black; color:white;" );


             if($service_type=="Collection") $number_collection++;  //counting bookings for collection
             else if($service_type=="Delivery") $number_delivery++;  //counting bookkings for delivery

             $invoice = unserialize(get_post_meta($post_id, 'wpcargo_invoice', true));
            //$invoice = ($service_type=="Collection") ? unserialize(get_post_meta($post_id, 'wpcargo_invoice', true)) : "";
             if(is_array($invoice)){
                foreach($invoice as $key => $invoice_item){
                   $op_sign = ($settings_items[$key]["item_type"]=="Expenditure") ? "-": "";
                   $expected_amount= ($op_sign=="-")? $expected_amount-$invoice_item['total'] : $expected_amount+$invoice_item['total'];
                   if($service_type=="Collection") $amount_collection = ($op_sign=="-")? $amount_collection-$invoice_item['total'] : $amount_collection+$invoice_item['total'];   //money for collection bookings
                   else if($service_type=="Delivery") $amount_delivery = ($op_sign=="-")? $amount_delivery-$invoice_item['total'] : $amount_delivery+$invoice_item['total']; //money for delivery bookings
                }
             }
             $table_rows .= "<tr>
                                <td>".get_post_meta($post_id, 'booking_reference', true)."</td>
                                <td>".$client."</td>
                                <td>".$service_type."</td>
                                <td class='last'><p style='".$color.";' >".$status."<br>".get_latest_substatus(get_post_meta( $post_id, 'wpcargo_shipments_update', true ))."</p></td>
                             </tr> ";
          } ?>
<div>
    <div class=" wpcargo-row">
        <div class="wpcargo-col-md-5"><br>
            <table class="report-table">
                <tr>
                    <td colspan="2"
                        style="font-weight: 700; border: none; border-bottom: solid 1px; padding-bottom: 5px;">Schedule
                        Details</td>
                </tr>
                <tr>
                    <td style="width:45%; font-weight: 700;">Schedule Name :</td>
                    <td style="width:50%;"><?php echo $schedule->schedule_name; ?></td>
                </tr>
                <tr>
                    <td style="width:45%; font-weight: 700;">Schedule City :</td>
                    <td style="width:50%;"><?php echo $schedule_city[0]->city_name; ?></td>
                </tr>
                <?php $temp = explode(' ',$schedule->schedule_date); $schedule_date = $temp[0]; $schedule_time = $temp[1];  ?>
                <tr>
                    <td style="width:45%; font-weight: 700;">Schedule Date :</td>
                    <td style="width:50%;"><?php echo date_format(date_create($schedule_date),'d-M-Y');?></td>
                </tr>
                <tr>
                    <td style="width:45%; font-weight: 700;">Cut-off :</td>
                    <td style="width:50%;"><?php echo date_format(date_create($schedule->late_cut_off),'d-M-Y H:i'); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width:45%; font-weight: 700;">Final Cut-off :</td>
                    <td style="width:50%;"><?php echo date_format(date_create($schedule->final_cut_off),'d-M-Y H:i'); ?>
                    </td>
                </tr>
                <!--tr><td style="width:45%; font-weight: 700;">Total Bookings :</td> <td style="width:50%;"><?php echo $bookings_no;?></td></tr>
                <tr><td style="width:45%; font-weight: 700;">Total Cost :</td> <td style="width:50%;">M <?php echo number_format($expected_amount,2,",",".");?></td></tr-->
            </table>
            <br><br>

            <table class="report-table-bookings" style="width: 80%;">
                <tr>
                    <td colspan="3"
                        style="font-weight: 700; border: none; border-bottom: solid 1px; padding-bottom: 5px;">Summary
                    </td>
                </tr>
                <tr>
                    <td><b>Activity</b></td>
                    <td><b>Number</b></td>
                    <td><b>Amount</b></td>
                </tr>
                <tr>
                    <td>Collection</td>
                    <td><?php echo $number_collection;?></td>
                    <td>M <?php echo number_format($amount_collection,2,",",".");?></td>
                </tr>
                <tr>
                    <td>Delivery</td>
                    <td><?php echo $number_delivery;?></td>
                    <td>M <?php echo number_format($amount_delivery,2,",",".");?></td>
                </tr>
                <tr>
                    <td><b>Total</b></td>
                    <td><b><?php echo $bookings_no;?></b></td>
                    <td><b>M <?php echo number_format($expected_amount,2,",",".");?></b></td>
                </tr>
            </table>
        </div>
        <div class="wpcargo-col-md-6"><br>
            <table class="report-table-bookings">
                <tr>
                    <td colspan="4"
                        style="font-weight: 700; border: none; border-bottom: solid 1px; padding-bottom: 5px;">Bookings
                    </td>
                </tr>
                <tr>
                    <td style="width:30%; font-weight: 700;">Booking Reference</td>
                    <td style="width:30%; font-weight: 700;">Shipper</td>
                    <td style="width:20%; font-weight: 700;">Service Type</td>
                    <td class="last" style="width:20%; font-weight: 700;">Status</td>
                </tr>
                <?php echo $table_rows; ?>
            </table>
        </div>
    </div>
</div>
<?php   }
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_schedule_report_action', 'schedule_report_action_callback' );
add_action( 'wp_ajax_nopriv_schedule_report_action', 'schedule_report_action_callback' );
/**
 * Ajax Callback
 */
function delete_schedule_action_callback(){
     global $wpdb;
     $schedule_id = sanitize_text_field(trim($_POST['index']));
     //delete schedule
     $delete = $wpdb->delete(
        'collection_schedules',
        array(
            'id' => $schedule_id
        )
     );
     // if delete was success, then unset schedule date in bookings
     if($delete){
       $bookings =  schedule_summary($schedule_id);
       if(!empty($bookings["post_ids_list"])) {
         foreach ( $bookings["post_ids_list"] as $post_id ) {
              $field_name = ($schedule_id == get_post_meta($post_id, 'collection_schedule_id', true))? "collection_schedule_id" : "delivery_schedule_id";
              update_post_meta( $post_id, $field_name, "");
       }}
    }
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_delete_schedule_action', 'delete_schedule_action_callback' );
add_action( 'wp_ajax_nopriv_delete_schedule_action', 'delete_schedule_action_callback' );
/**
 * Ajax Callback
 */
function terminate_schedule_action_callback(){
     global $wpdb;
     $schedule_id = sanitize_text_field(trim($_POST['schedule_id']));
     $comments = sanitize_text_field(trim($_POST['comments']));
     $wpdb->update(
      'collection_schedules',
       array(
    			'status' => 'Terminated',
                'comments' => $comments,
    		),
       array(
			'id' => $schedule_id
		)
      );
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_terminate_schedule_action', 'terminate_schedule_action_callback' );
add_action( 'wp_ajax_nopriv_terminate_schedule_action', 'terminate_schedule_action_callback' );
/**
 * Ajax Callback
 */
function duplicate_schedule_action_callback(){
     global $wpdb;
    $schedule_id = sanitize_text_field(trim($_POST['schedule_id']));
    $repeat_times = sanitize_text_field(trim($_POST['duplicates_no']));
    $repeat_times = ($repeat_times>1)? $repeat_times : 1;
    //find schedule details
    $schedules = $wpdb->get_results( "SELECT * FROM collection_schedules WHERE id='$schedule_id'");
    $schedule_name = $schedules[0]->schedule_name;
    //find last schedule instance
    $schedule_instances = $wpdb->get_results("SELECT * FROM collection_schedules WHERE schedule_name='$schedule_name' ORDER BY schedule_date DESC");
    $schedule_city = $schedule_instances[0]->schedule_city;
    $schedule_date = $schedule_instances[0]->schedule_date;
    $late_cut_off = $schedule_instances[0]->late_cut_off;
    $final_cut_off = $schedule_instances[0]->final_cut_off;

    //Our dates
    $date1 = $schedule_instances[1]->schedule_date;
    $date2 = $schedule_instances[0]->schedule_date;
    $date1Timestamp = strtotime($date1);
    $date2Timestamp = strtotime($date2);
    $difference = $date2Timestamp - $date1Timestamp;
    $days = floor($difference / (60*60*24) );
    $incrementer = ($days >= 29) ? '+1 month' : '+'.$days.' day';
  for($j=0; $j<$repeat_times; $j++) {
        $diff = -1;
        while($diff < 1){
         $schedule_date = date('Y-m-d H:i:s', strtotime($incrementer, strtotime($schedule_date)));
         $late_cut_off = date('Y-m-d H:i:s', strtotime($incrementer, strtotime($late_cut_off)));
         $final_cut_off = date('Y-m-d H:i:s', strtotime($incrementer, strtotime($final_cut_off)));

         $latest_schedule_date = new DateTime($schedule_date);
         $current_date = new DateTime();
         $interval = $current_date->diff($latest_schedule_date);
         $diff = $interval->format('%R%a days');
        }
        $wpdb->insert(
        		'collection_schedules',
            	array(
            			'id' => '',
            			'schedule_name' => $schedule_name,
            			'schedule_city' => $schedule_city,
            			'schedule_date' => $schedule_date,
            			'late_cut_off' => $late_cut_off,
            			'final_cut_off' => $final_cut_off,
                        'status'  => 'Upcoming',
                        'comments'  => '',
            		)
        );
      }
      echo "Collection Schedule Multiplied";
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_duplicate_schedule_action', 'duplicate_schedule_action_callback' );
add_action( 'wp_ajax_nopriv_duplicate_schedule_action', 'duplicate_schedule_action_callback' );
/**
 * Ajax Callback
 */
function schedule_single_action_callback(){
    global $wpdb;
    $selected_schedule = sanitize_text_field(trim($_POST['selected_schedule'])); ?>
<h1>
    <a style="margin-right:40px;" class="button"
        href="<?php echo admin_url().'admin.php?page=collection-schedules'; ?>">Back</a>
    <?php echo $selected_schedule." Settings"; ?>
</h1>
<div>
    <table class="viewTable" id="single_schedule_table_list">
        <thead>
            <tr>
                <th style=" text-align: left;width: 14%;"><?php esc_html_e('Schedule Name', 'wpcargo'); ?></th>
                <th style=" text-align: left;width: 9%;"><?php esc_html_e('Date', 'wpcargo'); ?></th>
                <th style=" text-align: left;width: 9%;"><?php esc_html_e('Cut-off', 'wpcargo'); ?></th>
                <th style=" text-align: left;width: 9%;"><?php esc_html_e('Final Cut-off', 'wpcargo'); ?></th>
                <th style=" text-align: left;width: 7%;"><?php esc_html_e('Bookings', 'wpcargo'); ?></th>
                <th style=" text-align: left;width: 9%;"><?php esc_html_e('Status', 'wpcargo'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $schedules = $wpdb->get_results( "SELECT * FROM collection_schedules WHERE schedule_name='$selected_schedule' ORDER BY schedule_date DESC ");
            $i =0;

            foreach ( $schedules as $schedule ) {
                 $schedule_id = $schedule->id;
                 $bookings= $wpdb->get_var( "SELECT COUNT(id) FROM `{$wpdb->prefix}posts` AS tbl1 JOIN `{$wpdb->prefix}postmeta` AS tbl2 ON tbl1.ID = tbl2.post_id
                                             WHERE  (tbl1.post_status LIKE 'publish' OR tbl1.post_status LIKE 'archive')
                                             AND tbl1.post_type LIKE 'wpcargo_shipment'
                                             AND ((tbl2.meta_key LIKE 'collection_schedule_id' AND tbl2.meta_value LIKE '$schedule_id')
                                                  OR (tbl2.meta_key LIKE 'delivery_schedule_id' AND tbl2.meta_value LIKE '$schedule_id'))");
                 ?>
            <tr id="<?php echo $schedule_id; ?>">
                <td style='border-top:1px solid;'>
                    <?php echo $schedule->schedule_name; ?>
                    <br><span>
                        <?php  $acts = array();
                            $acts[] = '<a href="#" id="schedule_report" onclick="switch_links(this,'.$i.','.$schedule_id.')">View</a>';
                            if($schedule->status!="Closed") $acts[] = '<a href="#" id="edit_schedule" onclick="switch_links(this,'.$i.','.$schedule_id.')">Edit</a>';
                            if($schedule->status!="Closed" && $schedule->status!="Terminated") $acts[] = '<a href="#" style="color: red;" data-schedule_name="'.$schedule->schedule_name.'" id="terminate_schedule" onclick="switch_links(this,'.$i.','.$schedule_id.')">Terminate</a>';
                            if($schedule->status=="Upcoming") $acts[] = '<a href="#" style="color: red;" id="delete_schedule" onclick="switch_links(this,'.$i.','.$schedule_id.')">Delete</a>';
                          $acts = implode("&nbsp;|&nbsp;",$acts);
                          print($acts);
                          ?>
                    </span>
                </td>
                <td style='border-top:1px solid;'><span
                        style="display:none"><?php echo strtotime($schedule->schedule_date); ?> </span>
                    <?php echo date_format(date_create($schedule->schedule_date),'d-M-Y'); ?> </td>
                <td style="text-align: left;border-top:1px solid;">
                    <?php echo date_format(date_create($schedule->late_cut_off),'d-M-Y H:i'); ?></td>
                <td style="text-align: left;border-top:1px solid;">
                    <?php echo date_format(date_create($schedule->final_cut_off),'d-M-Y H:i'); ?></td>
                <td style='border-top:1px solid;'><?php echo $bookings; ?></td>
                <td
                    style='border-top:1px solid; <?php echo ($schedule->status=="Active")?"background-color: Green; color:white;" :(($schedule->status=="Upcoming")?"background-color: orange; color:white;":"background-color: black; color:white" ); ?>'>
                    <p id='status_text'><?php echo $schedule->status; ?></p>
                </td>

            </tr>
            <?php $i++; }
             ?>
        </tbody>
    </table>
</div>
<?php   wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_schedule_single_action', 'schedule_single_action_callback' );
add_action( 'wp_ajax_nopriv_schedule_single_action', 'schedule_single_action_callback' );
/**
 * Ajax Callback
 */
function main_schedule_edit_form_action_callback(){
     global $wpdb;

    $schedule_id = sanitize_text_field(trim($_POST['index']));
    $schedule_name = sanitize_text_field(trim($_POST['schedule_name']));
    $schedule_city = sanitize_text_field(trim($_POST['schedule_city']));   ?>

<h1><?php echo $schedule_name." Details Update"; ?></h1>
<div class="form-table">
    <div style="width:100%;"><label style="width:40%;"><b>Schedule Name: </b>&nbsp;</label> <input
            style="width:55%; display: inline-block;" type='text' id="schedule_name" name='schedule_name'
            value='<?php echo $schedule_name; ?>' placeholder="Name of Collection Schedule"></div>
    <div style="width:100%;"><label style="width:40%;"><b>Schedule City: </b>&nbsp;</label>
        <select style="width:55%;" id="schedule_city" name="schedule_city">
            <option value="">--Select City--</option>
            <?php $cities = $wpdb->get_results( "SELECT id,city_name FROM countries_cities");
                          foreach ( $cities as $city ) { ?>
            <option value='<?php echo $city->id; ?>' <?php echo ($city->id==$schedule_city)? "selected": ""; ?>>
                <?php echo $city->city_name; ?></option>
            <?php } ?>
        </select>
    </div>
    <br>
</div>
<label><b>Collection Schedule Instances to edit</b></label>
<table class="form-table" style='border:solid 1px gray;'>
    <tr>
        <td>Date</td>
        <td>Cut Time</td>
        <td>Final Cut Time</td>
    </tr>
    <?php
              $instant_schedules = $wpdb->get_results( "SELECT * FROM collection_schedules WHERE schedule_name = '$schedule_name' AND status = 'Upcoming' ORDER BY schedule_date ASC");
              foreach ( $instant_schedules as $instant_schedule ) {
                  $temp = explode(' ',$instant_schedule->schedule_date); $schedule_date = $temp[0]; $schedule_time = $temp[1];
                  $temp = explode(' ',$instant_schedule->late_cut_off); $late_threshold_date =  $temp[0]; $late_threshold_time = $temp[1];
                  $temp = explode(' ',$instant_schedule->final_cut_off); $cut_off_date = $temp[0]; $cut_off_time = $temp[1];
               ?>
    <input type="hidden" id="schedule_id" name="schedule_id[]" value="<?php echo $instant_schedule->id; ?>">
    <tr>
        <td><input type='date' id='schedule_date' name='schedule_date[]' value='<?php echo $schedule_date;?>'
                required><input type='time' id='schedule_time' name='schedule_time[]'
                value='<?php echo $schedule_time;?>' hidden></td>
        <td><input type='date' id='late_threshold_date' name='late_threshold_date[]'
                value='<?php echo $late_threshold_date; ?>'><input type='time' id='late_threshold_time'
                name='late_threshold_time[]' value='<?php echo $late_threshold_time; ?>'></td>
        <td><input type='date' id='cut_off_date' name='cut_off_date[]' value='<?php echo $cut_off_date; ?>'><input
                type='time' id='cut_off_time' name='cut_off_time[]' value='<?php echo $cut_off_time; ?>'></td>
    </tr>
    <?php  }
            ?>
</table>
<?php
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_main_schedule_edit_form_action', 'main_schedule_edit_form_action_callback' );
add_action( 'wp_ajax_nopriv_main_schedule_edit_form_action', 'main_schedule_edit_form_action_callback' );

/**
 * Ajax Callback
 */
function schedule_dates_action_callback(){
       global $wpdb;
       $current_user = wp_get_current_user();
       $post_id = sanitize_text_field(trim($_POST['post_id']));
       $element = sanitize_text_field(trim($_POST['element']));
       $current_datetime = date('Y-m-d H:i:s');
       $collection_schedule_id = get_post_meta($post_id, 'collection_schedule_id', true);
       $delivery_schedule_id = get_post_meta($post_id, 'delivery_schedule_id', true);
 ?>
<label class="form-label"><?php esc_html_e('Select Date:','wpcargo'); ?></label>
<input type="hidden" id="schedule_field_name" value="<?php echo $element; ?>">
<select id="shipment_trip_id" required name="shipment_trip_id">
    <option value="">-- Select One --</option>
    <?php
          $selected_schedule = ($element == "collection_schedule_id") ? $collection_schedule_id : $delivery_schedule_id;
          $city  = ($element == "collection_schedule_id") ? get_post_meta($post_id, 'wpcargo_origin_city_field', true) : get_post_meta($post_id, 'wpcargo_destination_city', true);
          $qr = $wpdb->get_results("SELECT id FROM countries_cities WHERE city_name = '$city' ");
          $schedule_city = $qr[0]->id;
          $today = date('Y-m-d');
          //display New Dates
          $schedules = $wpdb->get_results("SELECT * FROM collection_schedules WHERE schedule_city = '$schedule_city' AND status!='Terminated' AND schedule_date >= '$today' ORDER BY schedule_date ASC LIMIT 4 ");
          foreach($schedules as $schedule){
              $final_cut_off = $schedule->final_cut_off;
              if($final_cut_off <= $current_datetime && ($schedule->status!='Closed' && $schedule->status!='Terminated') ){
                    update_schedule_status($schedule);
                    }
              $selected = ($selected_schedule == $schedule->id) ? "selected" : "";
              echo "<option value='".$schedule->id."' ".$selected." data-tripdate='".date_format(date_create($schedule->schedule_date),'d-M-Y')."'>".date_format(date_create($schedule->schedule_date),'d-M-Y')."(".$schedule->schedule_name.")</option>";
          }
          //Display Old dates
          //if ( in_array( 'administrator', (array) $current_user->roles ) || in_array( 'wpcargo_manager', (array) $current_user->roles ) ) {
            $city = $wpdb->get_results("SELECT * FROM countries_cities WHERE id='$schedule_city'");
            $auto_select = ($city[0]->country_depot == 1)? "selected" : "";
            echo "<option disabled='disabled'>Past Dates</option>";
             $oldschedules = $wpdb->get_results("SELECT * FROM collection_schedules WHERE schedule_city = '$schedule_city' AND status!='Terminated' AND schedule_date < '$today' ORDER BY schedule_date DESC Limit 4 ");
             foreach($oldschedules as $schedule){
              $final_cut_off = $schedule->final_cut_off;
              $option_label = ($city[0]->country_depot == 1)? $city[0]->city_name." Daily" : date_format(date_create($schedule->schedule_date),'d-M-Y')."(".$schedule->schedule_name.")";
              if($final_cut_off <= $current_datetime && ($schedule->status!='Closed' && $schedule->status!='Terminated') ){
                    update_schedule_status($schedule);
                    }
              $selected = ($selected_schedule == $schedule->id) ? "selected" : "";
              echo "<option $auto_select value='".$schedule->id."' ".$selected." data-tripdate='".date_format(date_create($schedule->schedule_date),'d-M-Y')."'>".$option_label."</option>";
          }

         // }

       ?>
</select>
<br>
<?php  wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_schedule_dates_action', 'schedule_dates_action_callback' );
add_action( 'wp_ajax_nopriv_schedule_dates_action', 'schedule_dates_action_callback' );

/**
 * Ajax Callback
 */
function trip_assign_save_action_callback(){
    $selected_trip = sanitize_text_field(trim($_POST['selected_trip']));
    $shipment_id = sanitize_text_field(trim($_POST['shipment_id']));
    $schedule_field_name = sanitize_text_field(trim($_POST['schedule_field_name']));
    update_post_meta( $shipment_id, $schedule_field_name, $selected_trip);
    wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_trip_assign_save_action', 'trip_assign_save_action_callback' );
add_action( 'wp_ajax_nopriv_trip_assign_save_action', 'trip_assign_save_action_callback' );
/**
 * Ajax Callback
 */
 function client_save_payment_action_callback(){
        $_POST = filter_var_array($_POST);
        $shipment_id = sanitize_text_field($_POST['post_id']);
        $payment_date = sanitize_text_field($_POST['payment_date']);
        $payment_method = sanitize_text_field($_POST["payment_identifier"]);
        $payment_reference = sanitize_text_field($_POST["payment_reference"]);
        $payment_amount = sanitize_text_field($_POST["payment_amount"]);
        $payment_time = date('H:i');
        $payment_timestamp = $payment_date." ".$payment_time;
        //$receipt_no = sanitize_text_field($_POST["receipt_no"]);

        $booking_reference = get_post_meta( $shipment_id, 'booking_reference', true );
        //$payment_amount = sanitize_text_field($_POST["payment_amount"]);
        $payment_history = unserialize(get_post_meta( $shipment_id, 'wpcargo_payment_history', true ));
        $payment_no = (!is_array($payment_history))? 1 : count($payment_history)+1;
        $exploded_booking_reference = explode("-",$booking_reference);
        $receipt_no = "PAY-".$exploded_booking_reference[1]."-".$payment_no;
        $payment = array("method"=>$payment_method,"reference"=>$payment_reference,"received_from"=>$received_from,"receipt_no"=>$receipt_no,"amount"=>$payment_amount,"received_by"=>$received_by,"approval" => 0);
        $payment_history[$payment_timestamp] = $payment;
        update_post_meta( $shipment_id, 'wpcargo_payment_history', maybe_serialize($payment_history));
        echo "Your Payment of M".number_format((float)$payment_amount, 2, '.', '')." has been submited for review. \n Please Note down your Booking reference: ". $booking_reference;
        //$msg="";
        //display current receipt
        //to load / display receipt
        //$_POST['row_id'] = $payment_date;
        //$_POST['shipment_id'] = $shipment_id;
       // payment_singleview_action_callback();
			 $wpcargo_status 	= "Pending";
         $status 	        = "Payment Recieved";
         $status_location 	= "";
         $status_time 		=  date('H:i');
         $status_remarks 	=  "Online Payment Recieved";
         $status_date 		= date('Y-m-d');
         $apply_to_shipment 	= true;
         $wpcargo_shipments_update = maybe_unserialize( get_post_meta( $shipment_id, 'wpcargo_shipments_update', true ) );
         // Make sure that it is set.
         $new_history = array(
             'date' => $status_date,
             'time' => $status_time,
             'location' => $status_location,
             'updated-name' => (!empty($received_by))?$received_by->display_name : "Online Client",
             'updated-by' => $received_by,
             'remarks'	=> $status_remarks,
             'status'    => $status
         );

         if( !empty( $wpcargo_shipments_update ) ){
             if( $wpcargo_status ){
                 array_push($wpcargo_shipments_update, $new_history);
             }
             update_post_meta($shipment_id, 'wpcargo_shipments_update', maybe_serialize( $wpcargo_shipments_update ) );
         }
         $msg = " ";

     wp_die(); // required. to end AJAX request.
 }
 /* Load Ajax Callback to "wp_ajax_*" Action Hook */
 add_action( 'wp_ajax_client_save_payment_action', 'client_save_payment_action_callback' );
 add_action( 'wp_ajax_nopriv_client_save_payment_action', 'client_save_payment_action_callback' );

/**
 * Ajax Callback
 */
function driver_trip_singleview_action_callback(){
     global $wpdb;
     $trip_id = sanitize_text_field($_POST['trip_id']);
     $trips = $wpdb->get_results( "SELECT * FROM trips WHERE id='$trip_id'");
        ?>
<br>
<table>
    <tr>
        <td>Trip Name : </td>
        <td>&nbsp;<?php echo $trips[0]->trip_name; ?></td>
    </tr>
    <tr>
        <td>Trip Date : </td>
        <td>&nbsp;<?php echo date_format(date_create($trips[0]->trip_date),'d-M-Y'); ?></td>
    </tr>
    <tr>
        <td>Assigned Drivers : </td>
        <td>&nbsp;<?php echo unserialize($trips[0]->drivers); ?></td>
    </tr>
    <tr>
        <td>Status : </td>
        <td>&nbsp;<span id="trip_status"><?php echo $trips[0]->status; ?></span>&nbsp;&nbsp;

            <button id="trip_update_link" class="link"
                style='border:solid 1px white; color: white; <?php echo ($trips[0]->status=="Closed")?"display:none;" : "" ?> background:<?php echo ($trips[0]->status=="Upcoming")?"green" : "red"; ?>'
                onclick="update_trip_state(this);"
                id="<?php echo $trip_id; ?>"><?php echo ($trips[0]->status=="Upcoming")?"Activate Trip": "End Trip"; ?></button>
        </td>
    </tr>
</table>
<?php
            $trip_cities = unserialize($trips[0]->city_schedules);
            $i=0;
            if(is_array($trip_cities)) {   ?>
<div class=" wpcargo-row" style="padding-top: 20px;">
    <div class="wpcargo-col-md-1">
        <button class="back" id="back_btn" onclick="trip_singleview('back_btn');"> <?php echo "Back"; ?> </button>
    </div>
    <div class="wpcargo-col-md-11">
        <label class="header_label">
            <span><?php echo $trips[0]->trip_name; ?></span>
            <input type="hidden" name="trip_id" id="trip_id" value="<?php echo $trip_id; ?>">
        </label>
        <div> <br>
            <table class="datatable" id="bookings_table_list" style=" width: 100%; font-size: 14px;">
                <thead>
                    <tr class='border_bottom'>
                        <th>City</th>
                        <th>Schedule Date</th>
                        <th>Awaiting</th>
                        <th>Failed</th>
                        <th>Successful</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($trip_cities AS $trip_city){
                           $schedule_city_id = $trip_city['schedule_city'];
                           $schedule_city = $wpdb->get_results( "SELECT city_name FROM `countries_cities` WHERE id = '".$schedule_city_id."' ");
                           $schedule_id = $trip_city['schedule_id'];
                           $selected_schedule = $wpdb->get_results("SELECT * FROM collection_schedules WHERE id = '$schedule_id'");
                           //get summary of this schedule
                           $schedule_summary =  schedule_summary($schedule_id,$trip_id,"active");

                        ?>
                    <tr style="margin: 2px auto;" class='clickable-row border_bottom'
                        onclick="driver_city_singleview(this);" id="<?php echo $schedule_id; ?>"
                        data-schedule_city="<?php echo $schedule_city[0]->city_name; ?>">
                        <td><?php echo $schedule_city[0]->city_name; ?></td>
                        <td><?php echo date_format(date_create($selected_schedule[0]->schedule_date),'d-M-Y'); ?></td>
                        <td><?php echo $schedule_summary['num_of_awaiting']; ?></td>
                        <td><?php echo $schedule_summary['num_of_failed']; ?></td>
                        <td><?php echo $schedule_summary['num_of_complete']; ?></td>
                    </tr>
                    <!--button style="width: 90%; color: black; margin: 2px auto;" onclick="driver_city_singleview(this);" id="<?php echo $schedule_id; ?>" name="<?php echo $schedule_city[0]->city_name; ?>"> <?php echo $schedule_city[0]->city_name; ?></button-->

                    <?php $i++; } ?>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php }
          else echo "This trip has no cities defined";
    ?>

<?php
    wp_die(); // required. to end AJAX request.
}
add_action( 'wp_ajax_driver_trip_singleview_action', 'driver_trip_singleview_action_callback' );
add_action( 'wp_ajax_nopriv_driver_trip_singleview_action', 'driver_trip_singleview_action_callback' );

/**
 * Ajax Callback
 */
function driver_city_singleview_action_callback(){
     global $wpdb;
     $trip_id = sanitize_text_field($_POST['trip_id']);
     $schedule_id = sanitize_text_field($_POST['schedule_id']);
     $schedule_city = sanitize_text_field($_POST['schedule_city']);
    ?>
<div class=" wpcargo-row" style="padding-top: 20px;">
    <div class="wpcargo-col-md-1">
        <button class="back" id="back_btn" onclick="trip_singleview('<?php echo $trip_id; ?>');"> <?php echo "Back"; ?>
        </button>
    </div>
    <div class="wpcargo-col-md-11">
        <label class="header_label">
            <?php echo $schedule_city; ?> Bookings<span></span>
        </label>
        <input type="hidden" name="trip_id" id="trip_id" value="<?php echo $trip_id; ?>">
        <input type="hidden" name="schedule_city" id="schedule_city" value="<?php echo $schedule_city; ?>">
        <input type="hidden" name="schedule_id" id="schedule_id" value="<?php echo $schedule_id; ?>">

        <br>
        <table class="datatable" id="bookings_table_list" style=" width: 100%; font-size: 14px; margin-top: 20px;">
            <thead>
                <tr class='border_bottom'>
                    <th style="width: 30px;">#</th>
                    <th>Booking Reference</th>
                    <th>Activity</th>
                    <th class='collapse1'>Address Type</th>
                    <th class='addr'>Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php

          $bookings =  schedule_summary($schedule_id,$trip_id);
          $i=1;
          if(!empty($bookings["post_ids_list"])) {
           foreach ( $bookings["post_ids_list"] as $post_id ) {
            $status = get_post_meta($post_id, 'wpcargo_status', true);
            if($status=="Active" || $status=="Complete") {
              $collection_schedule_id = get_post_meta($post_id, 'collection_schedule_id', true);
              $delivery_schedule_id = get_post_meta($post_id, 'delivery_schedule_id', true);
              $wpcargo_origin_city_field = get_post_meta($post_id, 'wpcargo_origin_city_field', true);
              $wpcargo_destination_city = get_post_meta($post_id, 'wpcargo_destination_city', true);
              $activity = (get_post_meta($post_id, 'collection_schedule_id', true)==$schedule_id) ? "Collection": "Delivery";
              $client = (!empty(get_post_meta( $post_id, 'wpcargo_receiver_company',true)))? get_post_meta( $post_id, 'wpcargo_receiver_company', true) : get_post_meta($post_id, 'wpcargo_receiver_fname', true)." ".get_post_meta($post_id, 'wpcargo_receiver_sname', true);
              $wpcargo_shipments_update = get_post_meta( $post_id, 'wpcargo_shipments_update', true ); //get_latest_substatus(get_post_meta( $post_id, 'wpcargo_shipments_update', true ));

              if(strpos($wpcargo_shipments_update, $activity." Successful") != false){
                  $wpcargo_status = $activity." Successful"; }
              else if(strpos($wpcargo_shipments_update, $activity." Failed") != false){
                  $wpcargo_status = $activity." Failed";  }
              else
                  $wpcargo_status = "Awaiting ".$activity;


              $address_type = ($activity=="Collection") ? get_post_meta($post_id, 'wpcargo_shipper_address_type', true) : get_post_meta($post_id, 'wpcargo_delivery_address_type', true);
              $name = ($activity=="Collection")? "shipper":"delivery";
              $addr = ($address_type=="Business Address")? "bussiness":"estate";
              $full_address = (!empty(get_post_meta($post_id, 'wpcargo_'.$name.'_'.$addr, true)))
                  ? get_post_meta($post_id, 'wpcargo_'.$name.'_'.$addr, true).', <br>'.get_post_meta($post_id, 'wpcargo_'.$name.'_address', true)
                  : get_post_meta($post_id, 'wpcargo_'.$name.'_address', true);


              echo "<tr class='clickable-row border_bottom' id='".$post_id."' onclick='booking_singleview(this);'>";
                echo "<td>".$i."</td>";
                echo "<td> ".get_post_meta($post_id, 'booking_reference', true)."</td>";
                echo "<td>".$activity."</td>";
                echo "<td class='collapse1'>".$address_type."</td>";
                echo "<td class='addr'>".$full_address."</td>";
                $status_background = ($wpcargo_status=="Awaiting ".$activity)?"green" : (($wpcargo_status==$activity." Failed")?"red" : "gray");
                echo "<td style='background:".$status_background.";'>".$wpcargo_status."</td>";
              echo "</tr>";
          $i++; } } }
          if($i==1) echo "<tr><td colspan='2'>No bookings found</td></tr>";

        ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Booking Reference</th>
                    <th>Activity</th>
                    <th class='collapse1'>Address Type</th>
                    <th class='addr'>Address</th>
                    <th class='collapse1'>Status</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php wp_reset_postdata(); ?>
<?php
    wp_die(); // required. to end AJAX request.
}
add_action( 'wp_ajax_driver_city_singleview_action', 'driver_city_singleview_action_callback' );
add_action( 'wp_ajax_nopriv_driver_city_singleview_action', 'driver_city_singleview_action_callback' );

/**
 * Ajax Callback
 */
function driver_booking_singleview_action_callback(){
     global $wpdb;
     $trip_id = sanitize_text_field($_POST['trip_id']);
     $trips = $wpdb->get_results( "SELECT * FROM trips WHERE id='$trip_id'");
     $trip_status = $trips[0]->status;
     $post_id = sanitize_text_field($_POST['booking_id']);
     $schedule_id = sanitize_text_field($_POST['schedule_id']);
     $schedule_city = sanitize_text_field($_POST['schedule_city']);
     $activity = (wpcargo_get_postmeta($post_id, 'collection_schedule_id', true)==$schedule_id) ? "Collection": "Delivery";
     $selected_schedule = $wpdb->get_results("SELECT * FROM collection_schedules WHERE id = '$schedule_id'");
     $schedule_date = date_format(date_create($selected_schedule[0]->schedule_date),'d-F-Y');
     if($activity=="Collection") {
        $address_type = wpcargo_get_postmeta( $post_id, 'wpcargo_shipper_address_type', true);
        $estate = ($address_type=="Business Address") ? wpcargo_get_postmeta( $post_id, 'wpcargo_shipper_bussiness', true): wpcargo_get_postmeta( $post_id, 'wpcargo_shipper_estate', true);
        $address = wpcargo_get_postmeta( $post_id, 'wpcargo_shipper_address', true);
        $contact_person = wpcargo_get_postmeta( $post_id, 'wpcargo_shipper_name', true);
        $phone_no1 = wpcargo_get_postmeta( $post_id, 'wpcargo_shipper_phone_1', true);
        $phone_no2 = wpcargo_get_postmeta( $post_id, 'wpcargo_shipper_phone_2', true);
        $has_whatsapp1 = wpcargo_get_postmeta($post_id, 'wpcargo_shipper_whatsapp_1', true);
        $has_whatsapp2 = wpcargo_get_postmeta($post_id, 'wpcargo_shipper_whatsapp_2', true);
        $activity_reference = wpcargo_get_postmeta($post_id, 'collection_reference', true);
        $activity_instructions = wpcargo_get_postmeta($post_id, 'collection_instructions', true);
        $activity_times = (wpcargo_get_postmeta($post_id, 'col_after_hours', true)=="on")? "Any time" : "Working Hours";
     }
     else if($activity=="Delivery") {
        $address_type = wpcargo_get_postmeta( $post_id, 'wpcargo_delivery_address_type', true);
        $estate = ($address_type=="Business Address") ? wpcargo_get_postmeta( $post_id, 'wpcargo_delivery_bussiness', true): wpcargo_get_postmeta( $post_id, 'wpcargo_delivery_estate', true);
        $address = wpcargo_get_postmeta( $post_id, 'wpcargo_delivery_address', true);
        $contact_person = wpcargo_get_postmeta( $post_id, 'wpcargo_delivery_name', true);
        $phone_no1 = wpcargo_get_postmeta( $post_id, 'wpcargo_delivery_phone_1', true);
        $phone_no2 = wpcargo_get_postmeta( $post_id, 'wpcargo_delivery_phone_2', true);
        $has_whatsapp1 = wpcargo_get_postmeta($post_id, 'wpcargo_delivery_whatsapp_1', true);
        $has_whatsapp2 = wpcargo_get_postmeta($post_id, 'wpcargo_delivery_whatsapp_2', true);
        $activity_reference = wpcargo_get_postmeta($post_id, 'delivery_reference', true);
        $activity_instructions = wpcargo_get_postmeta($post_id, 'delivery_instructions', true);
        $activity_times = (wpcargo_get_postmeta($post_id, 'col_after_hours', true)=="on")? "Any time" : "Working Hours";
     }
    ?>
<div class=" wpcargo-row" style="padding-top: 20px;">
    <div class="wpcargo-col-md-1">
        <button class="back" onclick="driver_city_singleview(this);" id="<?php echo $schedule_id; ?>"
            data-schedule_city="<?php echo $schedule_city; ?>"> <?php echo "Back"; ?></button>
    </div>
    <div class="wpcargo-col-md-11">
        <div class=" wpcargo-row">
            <div class="wpcargo-col-md-10 header_label header_labels_div" style="margin-top: 10px;">
                <?php echo $schedule_city." Bookings (".wpcargo_get_postmeta($post_id, 'booking_reference', true).")";   ?>
                <span style="float: right; margin-right: 10px; text-transform: capitalize">
                    Status: <?php
                      $wpcargo_shipments_update = get_post_meta( $post_id, 'wpcargo_shipments_update', true );
                      if(strpos($wpcargo_shipments_update, $activity." Successful") != false){
                          $wpcargo_status = $activity." Successful"; }
                      else if(strpos($wpcargo_shipments_update, $activity." Failed") != false){
                          $wpcargo_status = $activity." Failed";  }
                      else
                          $wpcargo_status = "Awaiting ".$activity;
                echo $wpcargo_status; ?></span>
            </div>
            <div class="wpcargo-col-md-2" style="margin-top: 10px;">
                <?php if(($wpcargo_status == "Awaiting ".$activity || $wpcargo_status == "Awaiting ".$activity) && $trip_status =="Active"){ ?>
                <div id="success_btns">
                    <button class="link"
                        style="border:solid 1px white; color: white; margin-left: 15px; margin-top: 10px; background: green;"
                        onclick="update_shipment_state(this);" value="<?php echo $activity." Successful"; ?>"
                        id="<?php echo $post_id; ?>">Success</button>
                    <button class="link"
                        style="border:solid 1px white; color: white; margin-left: 15px; margin-top: 10px; background: red;"
                        onclick="update_shipment_state(this);" value="<?php echo $activity." Failed"; ?>"
                        id="<?php echo $post_id; ?>">Failed</button>
                </div>
                <?php } ?>
            </div>
            <input type="hidden" name="trip_id" id="trip_id" value="<?php echo $trip_id; ?>">
            <input type="hidden" name="schedule_city" id="schedule_city" value="<?php echo $schedule_city; ?>">
            <input type="hidden" name="schedule_id" id="schedule_id" value="<?php echo $schedule_id; ?>">

        </div>
        <br><br>
        <div class=" wpcargo-row" style=" width: 95%; background: #444444; padding: 20px;">
            <div class="wpcargo-col-md-4"><br>
                <h5 class="wpcargo-label" style="text-transform: uppercase; text-decoration: underline;">
                    <b><?php echo $activity." Details"; ?></b>
                </h5>
                <div class="wpcargo-label-info" id="label_info_receiver" style="font-size: 13px; ">
                    <b>Route: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;">
                        <?php echo get_post_meta( $post_id, 'wpcargo_origin_city_field', true)." to ".get_post_meta( $post_id, 'wpcargo_destination_city', true);?>
                    </p>
                    <b>Address Type: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;"><?php echo $address_type;?></p>
                    <b><?php echo $activity;?> Address: </b>
                    <?php  $name = ($activity=="Collection")? "shipper":"delivery";
                          $addr = ($address_type=="Business Address")? "bussiness":"estate";
                          $full_address = (!empty(get_post_meta($post_id, 'wpcargo_'.$name.'_'.$addr, true)))
                              ? get_post_meta($post_id, 'wpcargo_'.$name.'_'.$addr, true).', '.get_post_meta($post_id, 'wpcargo_'.$name.'_address', true)
                              : get_post_meta($post_id, 'wpcargo_'.$name.'_address', true);

                   ?>
                    <p style=" margin-left: 15px; margin-bottom: 10px;"><?php echo $full_address;?></p>
                    <b>Contact Person: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;"><?php echo $contact_person;?></p>
                    <b>Contacts: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;"><a
                            href="tel:<?php echo $phone_no1;;?>"><?php echo $phone_no1;;?></a>
                        <?php echo ($phone_no1!="" && $phone_no2!="")?" / " :""; ?>
                        <a href="tel:<?php echo $phone_no2;?>"><?php echo $phone_no2;?></a>
                    </p>
                </div>
            </div>
            <div class="wpcargo-col-md-4"><br>
                <h5 class="wpcargo-label" style="text-transform: uppercase; text-decoration: underline;">
                    <b><?php esc_html_e('Booking Details', 'wpcargo'); ?></b>
                </h5>
                <div class="wpcargo-label-info" id="label_info_shipper" style="font-size: 13px; ">
                    <b>Description: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;">
                        <?php echo wpcargo_get_postmeta($post_id, 'goods_description', true);?></p>
                    <b><?php echo $activity; ?> Reference: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;"><?php echo $activity_reference;?></p>
                    <b><?php echo $activity; ?> Instructions: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;"><?php echo $activity_instructions;?></p>
                    <b><?php echo $activity; ?> Times: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;"><?php echo $activity_times;?></p>
                </div>
            </div>
            <div class="wpcargo-col-md-4"><br>
                <h5 class="wpcargo-label" style="text-transform: uppercase; text-decoration: underline;">
                    <b><?php esc_html_e('Shipper Details', 'wpcargo'); ?></b>
                </h5>
                <div class="wpcargo-label-info" id="label_info_origin" style="font-size: 13px; margin-bottom: 0px;">
                    <b>Names: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;">
                        <?php echo(!empty(wpcargo_get_postmeta($post_id, 'wpcargo_receiver_company', true)))? wpcargo_get_postmeta($post_id, 'wpcargo_receiver_company', true) : wpcargo_get_postmeta($post_id, 'wpcargo_receiver_fname', true).", ".wpcargo_get_postmeta($post_id, 'wpcargo_receiver_sname', true);?>
                    </p>
                    <b>Contacts: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;"><a
                            href="tel:<?php echo wpcargo_get_postmeta($post_id, 'wpcargo_receiver_phone_1', true);?>"><?php echo wpcargo_get_postmeta($post_id, 'wpcargo_receiver_phone_1', true);?></a>
                        <a
                            href="tel:<?php echo wpcargo_get_postmeta($post_id, 'wpcargo_receiver_phone_2', true);?>"><?php echo wpcargo_get_postmeta($post_id, 'wpcargo_receiver_phone_2', true);?></a>
                    </p>
                </div><br>
                <h5 class="wpcargo-label" style="text-transform: uppercase; text-decoration: underline;">
                    <b><?php esc_html_e('Extra info', 'wpcargo'); ?></b>
                </h5>
                <div class="wpcargo-label-info" id="label_info_shipper" style="font-size: 13px; ">
                    <b>Booking By: </b>
                    <p style=" margin-left: 15px; margin-bottom: 10px;"><?php
                         $booking_type = get_post_meta( $post_id, 'booking_type', true);
                         $author = ($booking_type=="Online")? get_post_meta( $post_id, 'wpcargo_receiver_fname', true)." ".get_post_meta( $post_id, 'wpcargo_receiver_fname', true)
                         : get_the_author_meta( 'display_name' , get_post_field ('post_author', $post_id) );
                         echo $booking_type.", ".$author;
                         ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    wp_die(); // required. to end AJAX request.
}
add_action( 'wp_ajax_driver_booking_singleview_action', 'driver_booking_singleview_action_callback' );
add_action( 'wp_ajax_nopriv_driver_booking_singleview_action', 'driver_booking_singleview_action_callback' );
/**
 * Ajax Callback
 */
function driver_save_forms_action_callback(){
       global $wpdb;
       $_POST = filter_var_array($_POST);
       $trip_id = sanitize_text_field($_POST['trip_id']);
       $current_form = sanitize_text_field($_POST['current_form']);

       if($current_form == "activate_trip_form" || $current_form == "end_trip_form"){
          if($current_form == "activate_trip_form"){  $status = "Active";
             $extra_info = array('vehicle_reg' => sanitize_text_field($_POST['vehicle_reg']),
                                 'trailer_reg' => sanitize_text_field($_POST['trailer_reg']),
                                 'opening_mileage' => sanitize_text_field($_POST['opening_mileage']),
                                );
            }
          else if($current_form == "end_trip_form"){ $status = "Closed";
             $trip = $wpdb->get_results( "SELECT * FROM trips WHERE id = '$trip_id' ");
             $extra_info = unserialize($trip[0]->extra_info);
             $extra_info['closing_mileage'] = sanitize_text_field($_POST['closing_mileage']);
            }
          $wpdb->update(
                      'trips',
                	   array('status' =>$status,
                             'extra_info' => serialize($extra_info)),
                       array(
                			'id' => $trip_id
                		)
                 );
           echo "Trip State Changed";
        }
        if($current_form == "success_form" || $current_form == "failed_form"){
            $shipment_id = sanitize_text_field($_POST['booking_id']);
            $remarks = sanitize_text_field($_POST['remarks']);
            $reasons = (!empty($_POST['reasons']))? " - ".sanitize_text_field($_POST['reasons']):"";
            $failure_cause = (!empty($_POST['failure_cause']))? " (".sanitize_text_field($_POST['failure_cause']).")":"";
            $activity = ($remarks=="Collection Failed" || $remarks=="Collection Successful") ? "Collection" : "Delivery";
            $current_user = wp_get_current_user();
    	  	$new_history = array(
    			'date' => date('Y-m-d'),
    			'time' => date('H:i', time() + 2 * 60 * 60),
    			'location' => "",
    			'updated-name' => $current_user->display_name,
    			'updated-by' => $current_user->ID,
    			'remarks'	=> $remarks."".$failure_cause."".$reasons,
    			'status'    => $activity
    		);
           $wpcargo_shipments_update_history = maybe_unserialize( get_post_meta( $shipment_id, 'wpcargo_shipments_update', true ) );
		   $wpcargo_shipments_update_history[] = $new_history;
           if($remarks=="Collection Failed" || $remarks=="Delivery Failed"){
               if($remarks=="Collection Failed"){  // if collection failed, invoice this
                   $_POST=filter_var_array($_POST);
                   $data = unserialize(get_post_meta( $shipment_id, 'wpcargo_price_estimates', true ));
                   $booking_reference = get_post_meta( $shipment_id, 'booking_reference', true );
                   $exploded_booking_reference = explode("-",$booking_reference);
                   $invoice_no = "INV-".$exploded_booking_reference[1];
                   //reset all invoice elements
                   foreach($data as $key => $item){
                         if($key != "bookingfee") {
                             $item["price"] = 0.00;
                             $item["total"] = 0.00;
                             $newdata[$key] = $item;
                         }
                   }
                   if(sanitize_text_field($_POST['failure_cause'])=="Client Fault"){
                         $settingsitemresults = get_settings_items();
                         $settingsitems = unserialize($settingsitemresults->meta_data);
                         $item["price"] = $settingsitems['bookingfee']['item_price'];
                         $item["total"] = $settingsitems['bookingfee']['item_price'];
                         $newdata["bookingfee"] = $item;
                     }
                   //save invoice data
                   update_post_meta( $shipment_id, 'wpcargo_invoice', serialize($newdata));
                   update_post_meta( $shipment_id, 'wpcargo_invoice_no', $invoice_no);
                   update_post_meta( $shipment_id, 'wpcargo_invoice_date', date('Y-m-d H:i'));
                   //skip other stages
                   $wpcargo_shipments_update_history[] =  array( 'date' => date('Y-m-d'), 'time' => date('H:i', time() + 2 * 60 * 60),'location' => "", 'updated-name' => $current_user->display_name, 'updated-by' => $current_user->ID, 'remarks'	=> "Invoice created", 'status'    => "Invoicing");
                   $wpcargo_shipments_update_history[] =  array( 'date' => date('Y-m-d'), 'time' => date('H:i', time() + 2 * 60 * 60),'location' => "", 'updated-name' => $current_user->display_name, 'updated-by' => $current_user->ID, 'remarks'	=> "System automatic stage skip", 'status'    => "Cargo at Origin Depot");
                   $wpcargo_shipments_update_history[] =  array( 'date' => date('Y-m-d'), 'time' => date('H:i', time() + 2 * 60 * 60),'location' => "", 'updated-name' => $current_user->display_name, 'updated-by' => $current_user->ID, 'remarks'	=> "System automatic stage skip", 'status'    => "Cargo in Transit");
                   $wpcargo_shipments_update_history[] =  array( 'date' => date('Y-m-d'), 'time' => date('H:i', time() + 2 * 60 * 60),'location' => "", 'updated-name' => $current_user->display_name, 'updated-by' => $current_user->ID, 'remarks'	=> "System automatic stage skip", 'status'    => "Cargo at Destination Depot");
                   $wpcargo_shipments_update_history[] =  array( 'date' => date('Y-m-d'), 'time' => date('H:i', time() + 2 * 60 * 60),'location' => "", 'updated-name' => $current_user->display_name, 'updated-by' => $current_user->ID, 'remarks'	=> "System automatic stage skip", 'status'    => "Goods Inspection");
                   $wpcargo_shipments_update_history[] =  array( 'date' => date('Y-m-d'), 'time' => date('H:i', time() + 2 * 60 * 60),'location' => "", 'updated-name' => $current_user->display_name, 'updated-by' => $current_user->ID, 'remarks'	=> "System automatic stage skip", 'status'    => "Delivery");
               }
           }
           update_post_meta($shipment_id, 'wpcargo_shipments_update', serialize( $wpcargo_shipments_update_history ) );
        }

       wp_die(); // required. to end AJAX request.
}
/* Load Ajax Callback to "wp_ajax_*" Action Hook */
add_action( 'wp_ajax_driver_save_forms_action', 'driver_save_forms_action_callback' );
add_action( 'wp_ajax_nopriv_driver_save_forms_action', 'driver_save_forms_action_callback' );