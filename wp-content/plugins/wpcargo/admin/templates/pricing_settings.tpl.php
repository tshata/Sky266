<?php
  $msg ="";
  if(isset($_POST['submit'])){
          global $wpdb;
    		// sanitize form values
       	    $o_country_select  = sanitize_text_field( $_POST["o_country_select"] );
       	    $d_country_select    = sanitize_text_field( $_POST["d_country_select"] );
    		$o_city_select  = sanitize_text_field( $_POST["o_city_select"] );
    		$d_city_select = sanitize_text_field( $_POST["d_city_select"] );
            $kgs_pricing_items = general_pricing_items("kgs");
            $cbms_pricing_items = general_pricing_items("cbms");
            $road_kg_costs  = "";
            $results = get_settings_items();
            $items = unserialize($results->meta_data);
            if(isset($_POST["road_pricing"])){
                $road_kg_costs  = array();
                   foreach($kgs_pricing_items as $input=>$data){
                      $road_kg_costs[$input] = sanitize_text_field( str_replace(',','',$_POST["road_".$input] ));
                }
                $road_item_costs  = array();
                if(!empty($items)) {
                  foreach ( $items as $key => $item_data ) {
                    $road_item_costs[$key] = (isset($_POST["road_".$key])) ? sanitize_text_field(str_replace(',','',$_POST["road_".$key])) : sanitize_text_field(str_replace(',','',$item_data['item_price']));
                }}
            }
            if(isset($_POST["air_pricing"])){
               $air_kg_costs  = array();
               foreach($kgs_pricing_items as $input=>$data){
                  $air_kg_costs[$input] = sanitize_text_field( str_replace(',','',$_POST["air_".$input] ));
               }
                $air_item_costs  = array();
                if(!empty($items)) {
                  foreach ( $items as $key => $item_data ) {
                    $air_item_costs[$key] = (isset($_POST['air_'.$key])) ? sanitize_text_field(str_replace(',','',$_POST['air_'.$key])) : sanitize_text_field(str_replace(',','',$item_data['item_price']));
                }}
            }
            if(isset($_POST["ocean_pricing"])){
                $ocean_cbm_costs  = array();
                foreach($cbms_pricing_items as $input=>$data){
                  $ocean_cbm_costs[$input] = sanitize_text_field( str_replace(',','',$_POST["ocean_".$input] ));
                }
                $ocean_item_costs  = array();
                if(!empty($items)) {
                  foreach ( $items as $key => $item_data ) {
                    $ocean_item_costs[$key] = (isset($_POST['ocean_'.$key])) ? sanitize_text_field(str_replace(',','',$_POST['ocean_'.$key])) : sanitize_text_field(str_replace(',','',$item_data['item_price']));
                }}                                                              
            }
            if($_POST['current_form']=='new_route_form') {
               	$wpdb->insert(
            		'routes',
                	array(
                			'id' => '',
                			'origin_country' => $o_country_select,
                			'origin_city' => $o_city_select,
                			'dest_country' => $d_country_select,
                			'dest_city' => $d_city_select,
                            'road_costs'  => serialize($road_kg_costs ),
                            'air_costs'  => serialize($air_kg_costs),
                            'ocean_costs'  => serialize($ocean_cbm_costs),
                            'road_item_costs'  => serialize($road_item_costs),
                            'air_item_costs'  => serialize($air_item_costs),
                            'ocean_item_costs'  => serialize($ocean_item_costs),
                            //'other_costs'  => serialize($other_costs),
                		)
            	); }
             else if($_POST['current_form']=='edit_route_form')  {
                 $route_id = sanitize_text_field( $_POST["route_id"] );
                 $wpdb->update(
            		'routes',
                	array(
                            'road_costs'  => serialize($road_kg_costs ),
                            'air_costs'  => serialize($air_kg_costs),
                            'ocean_costs'  => serialize($ocean_cbm_costs),
                            'road_item_costs'  => serialize($road_item_costs),
                            'air_item_costs'  => serialize($air_item_costs),
                            'ocean_item_costs'  => serialize($ocean_item_costs),
                            //'other_costs'  => serialize($other_costs),
                		),
                    array(
            			'id' => $route_id
            		)
            	);
             }
            $msg = "Route Successfully Saved";
        }
?>
<style>
.viewTable{
    background-color: #dbf5e0; width:100%;
}
.viewTable th{
  border-bottom: 3px solid;
  padding: 5px;
}
.viewTable td{
  border-bottom: 2px solid;
  padding: 5px;
}
#new_route_form td{
  padding: 0px;
}
#new_route_form table label{
  font-weight: 550;
}

</style>

<form method="post" action="" class="pricing-admin-form" style="display: block;overflow: hidden;clear: both;">
 <div id="shipment-details">
   <input type="text" hidden="hidden" id="screen" value="">
  <div id="tags-wrapper" style="width:90%;">
    <h1><?php echo "Pricing" ?>
        <a style="float: right;" class="button" href="#" id="new_route" onclick="switch_links(this)">Add New Route</a></h1>
       <div>
        <?php
        ?>
           <table class="viewTable" id="dataTable">
            <thead>
             <tr>
               <th style="width: 25%; text-align: left;"><?php esc_html_e('Origin', 'wpcargo'); ?></th>
               <th style="width: 25%; text-align: left;"><?php esc_html_e('Destination', 'wpcargo'); ?></th>
               <th style="width: 25%; text-align: left;"><?php esc_html_e('Actions', 'wpcargo'); ?></th>
             </tr>
             </thead>
             <tbody>
             <?php
               $routes = wpc_get_prices();
               $i=0;
               foreach ( $routes as $route ) {
                 ?>
                 <tr id="<?php echo $route->id;?>">
                   <td><?php echo $route->origin_country." - ".$route->origin_city; ?> </td>
                   <td><?php echo $route->dest_country." - ".$route->dest_city; ?></td>
                   <td>
                      <a href="#" id="view_prices" onclick="switch_links(this,<?php echo $i; ?>,<?php echo $route->id; ?>)">View</a>&nbsp;|&nbsp;
                      <a href="#" id="edit_route" onclick="switch_links(this,<?php echo $i; ?>,<?php echo $route->id; ?>)">Edit</a>&nbsp;|&nbsp;
                      <a href="#" style="color: red;" id="delete_route" onclick="switch_links(this,<?php echo $i; ?>,<?php echo $route->id; ?>)">Del</a>
                   </td>
                 </tr>
                 <?php  $i++;
               }
             ?>
            </tbody>
           </table>
        </div>
       <input type="hidden" name="current_form" id="current_form" value="new_route_form" />
   </div> <!-- tags-wrapper -->
   <!-- The Modal -->
  <div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <span class="close" onclick="close_modal()">&times;</span>
        <h2>Modal Header</h2>
      </div>
      <div class="modal-body">
        <div style="display: none;" id="new_route_form">
         <h1>Adding New Route</h1>
         <?php if(!empty($msg)) echo "<p style='background-color: #dbf5e0; padding: 6px; width:100%;'>".$msg."</p>"; ?>
      	 <!--table class="form-table">
      		<tr>
      			<th scope="row"><?php esc_html_e( 'Original Country', 'wpcargo' ) ; ?></th>
      			<th scope="row"><?php esc_html_e( 'Destination Country', 'wpcargo' ) ; ?></th>

            </tr>
            <tr>
                <td>
      				<select id="org_1" name='o_country_select' onchange="trip_selector(this)">
      					<option value="">--Select Country--</option>
      					<?php $countries = wpc_get_countries_cities("GROUP BY country_name");
                              foreach ( $countries as $country ) { ?>
      						    <?php if($country->is_origin) echo "<option>".$country->country_name."</option>";
      					 } ?>
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
      						    <?php if($country->is_destination) echo "<option>".$country->country_name."</option>";
      					 } ?>
      				</select>
                    <p class="description">
                       <?php esc_html_e('Select city from list','wpcargo'); ?>
                     </p>
                  </td>
            </tr>
            <tr>
      			<th scope="row"><?php esc_html_e( 'Original City', 'wpcargo' ) ; ?></th>
                <th scope="row"><?php esc_html_e( 'Destination City', 'wpcargo' ) ; ?></th>
            </tr>
            <tr>
      		   <td>
      				<select id="org_1_1" name='o_city_select' onchange="trip_selector(this)">
      					<option value="">--Select City--</option>
      					<?php $countries = wpc_get_countries_cities("ORDER BY country_name");
                              foreach ( $countries as $country ) { ?>
      						    <?php if($country->is_origin) echo "<option>".$country->city_name."</option>";
      					 } ?>
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
      						    <?php if($country->is_destination) echo "<option>".$country->city_name."</option>";
      					 } ?>
      				</select>
                    <p class="description">
                       <?php esc_html_e('Select city from list','wpcargo'); ?>
                     </p>
                  </td>
            </tr>
            <tr><td colspan="2"><h2>Costs by weight</h2>
               <table id="kgs">
                 <?php $general_pricing_items = general_pricing_items(); $i=0;
                       foreach($general_pricing_items as $input=>$label){   if($i%6==0) echo"<tr>";
                          echo '<td><label>'.$label.'</label><input type="text" name="'.$input.'" value=""></td>';
                           if(($i+1)%6==0) echo"</tr>";
                   $i++;} ?>
               </table>
              </td>
            </tr>
            <tr><td colspan="2"><h2>Other Costs</h2>
               <table>
                 <?php $results = get_settings_items(); $i=0;
                       $items = unserialize($results->meta_data);
                       foreach( $items as $key => $item_data ){
                          if(isset($item_data['is_route_item']) && $item_data['is_route_item'] == 1) {  if($i%6==0) echo"<tr>";?>
                          <td><label><?php echo $item_data["item_name"]; ?></label>
                              <input type="text" name="<?php echo $key;?>" value="<?php echo number_format((float)$item_data['item_price'], 2, '.', ',');?>">
                          </td>
                       <?php if(($i+1)%6==0) echo"</tr>"; $i++; }
                  } ?>
               </table>
              </td>
            </tr>
      	</table>
        <!--center><br><input id="submit_btn" name="submit" type="submit" value="Save Route"></center-->
      </div>
      <div style="display: none;" id="prices_view_div" >
     </div>
   </div>
   <div class="modal-footer">   <p id="dd"></p>
       <input class='button' type='submit' id="submit" name='submit' value='Save'>
       <a class="button" onclick="close_modal()">Close</a>
   </div>
  </div>
 </div>
 </div>
</form>

<script>

jQuery(document).ready(function ($) {
    var table = $('#dataTable').DataTable({stateSave: true});
});

function switch_links(btn,row_index,item_id){
  if(btn.id=="view_prices") {
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'view_prices_action',
                  index : item_id,
              },
              success:function(data) {
                  $('#prices_view_div' ).html(data);
                  $('#new_route_form').hide();
                  $('#prices_view_div').show();
                  $('#current_form').val('');
                  $('#myModal #submit').hide();
                  $('#myModal').show();
              },
              error: function(errorThrown){
                  $( '#prices_view_div' ).html('Error retrieving data. Please try again.');
              }
          });
    }
  else if(btn.id=="new_route") {
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'route_form_action',
                  index : item_id,
              },
              success:function(data) {
                  $('#new_route_form' ).html(data);
                  $('#new_route_form').show();
                  $('#prices_view_div').hide();
                  $('#current_form').val('new_route_form');
                  //$('#submit').value('Save Route');
                  $('#submit').show();
                  $('#myModal').show();
              },
              error: function(errorThrown){
                  $( '#new_route_form' ).html('Error retrieving data. Please try again.');
              }

          });
  }
  else if(btn.id=="edit_route") {
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'route_form_action',
                  index : item_id,
              },
              success:function(data) {
                  $('#new_route_form' ).html(data);
                  $('#new_route_form').show();
                  $('#prices_view_div').hide();
                  $('#current_form').val('edit_route_form');
                  //$('#myModal #submit').value('Save Changes');
                  $('#submit').show();
                  $('#myModal').show();
              },
              error: function(errorThrown){
                  $( '#new_route_form' ).html('Error retrieving data. Please try again.');
              }

          });
  }
  else if(btn.id=="delete_route") {
      var r = confirm("Are you sure you want to delete this?");
      if (r == true) {
         $.ajax({
                url: wpcargoAJAXHandler.ajax_url,
                type:"POST",
                data: {
                    action    : 'delete_route_action',
                    index : item_id,
                },
                success:function(data) {
                     $('.viewTable tr[id="'+item_id+'"]').each(function() {
                          $(this).fadeOut('slow', function(){
                            this.remove();
                             });
                     });
                },
                error: function(errorThrown){
                    alert('Error retrieving data. Please try again.');
                }

            });

      } else {

      }
  }
  else alert("none");

}
/////////////////////////
function close_this(){ $('#more_view #new_trip_form').hide(); }
///////////////////////////
function trip_selector(elm){
     $.ajax({
            url: wpcargoAJAXHandler.ajax_url,
            type:"POST",
            data: {
                action    : 'trip_selector_action',
                o_country : $("#org_1").val(),
                o_city : $("#org_1_1").val(),
                d_country : $("#dest_1").val(),
                d_city : $("#dest_1_1").val(),
                screen : $("#screen").val(),
                selected : elm.id
            },
            success:function(data) {
                if(elm.id=="org_1" || elm.id=="dest_1") $('#shipment-details #'+elm.id+'_1').html( data );
                else if(elm.id=="org_1_1" || elm.id=="dest_1_1"){
                    $('#shipment-details #shipment_trip_id' ).html( data );
                }
             },
            error: function(errorThrown){
                alert('<p>Error retrieving data. Please try again.</p>');
            }

        });
}
function toggle_price_fields(elm) {
            if ($(elm).is(":checked")) {
               $("#"+$(elm).attr("name")).show();
            } else {
              $("#"+$(elm).attr("name")).hide();
            }
        }

</script>

