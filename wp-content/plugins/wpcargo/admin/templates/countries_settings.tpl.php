<?php
  $msg ="";
  if(isset($_POST['submit'])){
          global $wpdb;
    		// sanitize form values
          $country_select  = sanitize_text_field( $_POST["country_select"] );
          $country_name  = sanitize_text_field( $_POST["country_name"] );
          $city_name  = sanitize_text_field( $_POST["city_name"] );
          $city_abr = sanitize_text_field( $_POST["abr_input"] );
          $has_deport = sanitize_text_field( $_POST["has_deport"] );
          $is_origin = sanitize_text_field( $_POST["is_origin"] );
          $is_destination = sanitize_text_field( $_POST["is_destination"] );
          $has_collection = sanitize_text_field( $_POST["has_collection"] );
          $has_delivery = sanitize_text_field( $_POST["has_delivery"] );
          $country_depot = sanitize_text_field( $_POST["country_depot"] );
          if ($country_select!="new_country") $country_name = $country_select;

          if($_POST['current_form']=='new_city_form') {
               	$wpdb->insert(
            		'countries_cities',
                	array(
                			'id' => '',
                			'country_name' => $country_name,
                			'city_name' => $city_name,
                			'city_abr' => $city_abr,
                			'has_deport' => $has_deport,
                			'is_origin' => $is_origin,
                			'is_destination' => $is_destination,
                			'has_collection' => $has_collection,
                			'has_delivery' => $has_delivery,
                			'country_depot' => $country_depot,
                		)
            	); }
          else if($_POST['current_form']=='edit_city_form')  {
                 $city_id = sanitize_text_field( $_POST["city_id"] );
                 $wpdb->update(
            		'countries_cities',
                	array(
                			'country_name' => $country_name,
                			'city_name' => $city_name,
                			'city_abr' => $city_abr,
                			'has_deport' => $has_deport,
                			'is_origin' => $is_origin,
                			'is_destination' => $is_destination,
                			'has_collection' => $has_collection,
                			'has_delivery' => $has_delivery,
                			'country_depot' => $country_depot,
                		),
                    array(
            			'id' => $city_id
            		)
            	);
             }
            $msg = "Data Successfully Saved";
        }

?>
<style>
#dataTable{
    background-color: #dbf5e0;  width:100%;
}
#dataTable th{
  border-bottom: 3px solid;
  padding: 5px;
}
#dataTable td{
  border-bottom: 2px solid;
  padding: 5px;
}


</style>

<form method="post" action="" class="countries-admin-form" style="display: block;overflow: hidden;clear: both;">
 <div id="shipment-details">
  <div id="tags-wrapper" style="width:90%;">
     <h1><?php echo "Countries & Cities" ?> <?php esc_html_e('Settings', 'wpcargo'); ?>
     <a style="float: right;" class="button" href="#" id="new_city" onclick="switch_links(this)">Add New City</a></h1>
     <div>
        <?php
        ?>
           <table id="dataTable">
              <thead>
               <tr>
                 <th style="width: 20%; text-align: left;"><?php esc_html_e('Country', 'wpcargo'); ?></th>
                 <th style="width: 20%; text-align: left;"><?php esc_html_e('City', 'wpcargo'); ?></th>
                 <th style="width: 9%; text-align: left;"><?php esc_html_e('Abbreviation', 'wpcargo'); ?></th>
                 <th style="width: 6%; text-align: left;"><?php esc_html_e('Depot', 'wpcargo'); ?></th>
                 <th style="width: 6%; text-align: left;"><?php esc_html_e('Origin', 'wpcargo'); ?></th>
                 <th style="width: 6%; text-align: left;"><?php esc_html_e('Destination', 'wpcargo'); ?></th>
                 <th style="width: 6%; text-align: left;"><?php esc_html_e('Collections', 'wpcargo'); ?></th>
                 <th style="width: 6%; text-align: left;"><?php esc_html_e('Deliveries', 'wpcargo'); ?></th>
                 <th style="width: 6%; text-align: left;"><?php esc_html_e('Country Depot', 'wpcargo'); ?></th>
                 <th style="width: 15%; text-align: left;">Actions</th>
               </tr>
             </thead>
            <tbody>
             <?php
               $countries = wpc_get_countries_cities("ORDER BY country_name");
                $i=0;
               foreach ( $countries as $country ) {  ?>
                 <tr id="<?php echo $country->id; ?>">
                   <td><?php echo $country->country_name; ?></td>
                   <td><?php echo $country->city_name; ?></td>
                   <td><?php echo $country->city_abr; ?></td>
                   <td><?php echo ($country->has_deport)?"Yes" : "No"; ?></td>
                   <td><?php echo ($country->is_origin)?"Yes" : "No"; ?></td>
                   <td><?php echo ($country->is_destination)?"Yes" : "No"; ?></td>
                   <td><?php echo ($country->has_collection)?"Yes" : "No"; ?></td>
                   <td><?php echo ($country->has_delivery)?"Yes" : "No"; ?></td>
                   <td><?php echo ($country->country_depot)?"Yes" : "No"; ?></td>
                   <td>
                      <a href="#" id="edit_city" onclick="switch_links(this,<?php echo $i; ?>,<?php echo $country->id; ?>)">Edit</a>&nbsp;|&nbsp;
                      <a href="#" style="color: red;" id="delete_city" onclick="switch_links(this,<?php echo $i; ?>,<?php echo $country->id; ?>)">Del</a>
                   </td>
                 </tr>
                 <?php  $i++;
               }
             ?>
              </tbody>
           </table>
        </div>
       <input type="hidden" name="current_form" id="current_form" value="new_city_form" />
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
         <div id="form-fields-wrapper">
                  <?php if(!empty($msg)) echo "<p style='background-color: #dbf5e0; padding: 6px; width:100%;'>".$msg."</p>"; ?>
          	 <div class="one-half first">
                  <table class="form-table">
              		<tr>
              			<td><?php esc_html_e( 'Select Country', 'wpcargo' ) ; ?>
                            <input type="hidden" name="city_id" id="city_id"/>
              				<select id="country_select" name='country_select' onchange="toggle_country_input(this)" required>
              					<option value="">--Select Country--</option>
                                <option value="new_country">--Add New--</option>
              					<?php
                                   $options = wpc_get_countries_cities("Group BY country_name");
                                foreach ( $options as $option ) { ?>
              						<option> <?php echo $option->country_name; ?> </option>
              					<?php } ?>
              				</select>
                          </td>
                   </tr>
              	   <tr id="country_name_input" style="display: none;">
              			<td><?php esc_html_e( 'Name of Country', 'wpcargo' ) ; ?>
                            <input type="text" placeholder="eg. Lesotho" id="country_name" name="country_name" value="" >
                          </td>
                   </tr>
                   <tr>
                     <td>
                       <?php esc_html_e('Name of City', 'wpcargo'); ?>:
                       <input type="text" placeholder="eg. Maseru" id="city_name" name="city_name" value="" required >
                       </td>
                   </tr>
                   <tr>
                     <td>
                       <?php esc_html_e('City Abbreviation', 'wpcargo'); ?>:
                       <input type="text" placeholder="eg. MSU" name="abr_input" id="city_abr" value="" required >
                       </td>
                   </tr>
                  </table>
          	  </div>
              <div class="one-half">
                <table class="form-table">
            		<tr>
            		  <td> <input type="checkbox" id="has_deport" name="has_deport" value="1">
                          <b style="font-size: 12px;"><?php esc_html_e( 'City has a warehouse.', 'wpcargo' ) ; ?></b>
                      </td>
            		</tr>
            		<tr>
            			<td><input type="checkbox" id="is_origin" name="is_origin" value="1">
                            <b style="font-size: 12px;"><?php esc_html_e( 'City can be origin.', 'wpcargo' ) ; ?></b>
            			</td>
            		</tr>
            		<tr>
            			<td> <input type="checkbox" id="is_destination" name="is_destination" value="1">
                             <b style="font-size: 12px;"><?php esc_html_e( 'City can be destination.', 'wpcargo' ) ; ?> </b>
            			</td>
            		</tr>
            		<tr>
            			<td><input type="checkbox" id="has_collection" name="has_collection" value="1">
                            <b style="font-size: 12px;"><?php esc_html_e( 'Can do collections in this city.', 'wpcargo' ) ; ?></b>
            			</td>
            		</tr>
            		<tr>
            			<td>  <input type="checkbox" id="has_delivery" name="has_delivery" value="1">
                              <b style="font-size: 12px;"><?php esc_html_e( 'Can do deliveries in this city.', 'wpcargo' ) ; ?></b>
            			</td>
            		</tr>
            		<tr>
            			<td>  <input type="checkbox" id="country_depot" name="country_depot" value="1">
                              <b style="font-size: 12px;"><?php esc_html_e( 'Is country port.', 'wpcargo' ) ; ?></b>
            			</td>
            		</tr>
            	 </table>
          	  </div>
              <div class="clear"></div>
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
  if(btn.id=="new_city") {
       $("#current_form").val("new_city_form");
       $(".modal-header h2").html("Adding New City");
       $("#form-fields-wrapper").find(':input').each(function() {
        switch(this.type) {
            case 'text':
                $(this).val('');
                break;
            case 'checkbox':
                this.checked = false;
          }
        });
       $("#form-fields-wrapper").find('select').each(function() {
             $(this).val('');
        });
       $('#myModal').show();
  }
  else if(btn.id=="edit_city") {
       $(".modal-header h2").html("Editing City Details");
       $("#current_form").val("edit_city_form");
       var countries = <?php echo json_encode($countries); ?>;
       $('#city_id').val(item_id);
       $('#country_select').val(countries[row_index].country_name);
       $('#city_name').val(countries[row_index].city_name);
       $('#city_abr').val(countries[row_index].city_abr);
       var box_val = false;
       box_val = (countries[row_index].has_deport==1) ? true: false;  $("#has_deport").prop("checked", box_val);
       box_val = (countries[row_index].is_origin==1) ? true: false;  $("#is_origin").prop("checked", box_val);
       box_val = (countries[row_index].is_destination==1) ? true: false;  $("#is_destination").prop("checked", box_val);
       box_val = (countries[row_index].has_collection==1) ? true: false;  $("#has_collection").prop("checked", box_val);
       box_val = (countries[row_index].has_delivery==1) ? true: false;  $("#has_delivery").prop("checked", box_val);
       box_val = (countries[row_index].country_depot==1) ? true: false;  $("#country_depot").prop("checked", box_val);
       $('#myModal').show();
  }
  else if(btn.id=="delete_city") {
      var r = confirm("Are you sure you want to delete this?");
      if (r == true) {
         $.ajax({
                url: wpcargoAJAXHandler.ajax_url,
                type:"POST",
                data: {
                    action    : 'delete_city_action',
                    index : item_id,
                },
                success:function(data) {
                     $('#dataTable tr[id="'+item_id+'"]').each(function() {
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
</script>