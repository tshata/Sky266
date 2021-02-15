<?php
  $msg ="";
  global $wpdb;
  if(isset($_POST['submit']) && $_POST['current_form']=='new_trip_form'){
    		// sanitize form values
       	    $trip_name  = sanitize_text_field( $_POST["trip_name"] );
    		$trip_date  = sanitize_text_field( $_POST["trip_date"] );
            $trip_time  = sanitize_text_field( $_POST["trip_time"] );
       	    $trip_type  = sanitize_text_field( $_POST["trip_type"] );
       	    $repeat_times  = (int)sanitize_text_field( $_POST["repeat_times"] );

          //generate trip routes
            $j=0; $routes_data; $routes_ids="";
            while(isset($_POST["route_name"][$j]) && $_POST["route_name"][$j]!="")  {
                $route_id = sanitize_text_field( $_POST["route_id"][$j] );
                $routes_data[] = array(
                                          'route_id' => $route_id,
                                          'route_name' => sanitize_text_field( $_POST["route_name"][$j] ),
                                          'late_cut_off' => date('Y-m-d H:i:s', strtotime(sanitize_text_field( $_POST["late_threshold_date"][$j] )." ".sanitize_text_field( $_POST["late_threshold_time"][$j] ))),
                                          'final_cut_off' => date('Y-m-d H:i:s', strtotime(sanitize_text_field( $_POST["cut_off_date"][$j] )." ".sanitize_text_field( $_POST["cut_off_time"][$j] ))),
                                   );
                $routes_ids .= ($j==0) ? $route_id : ",".$route_id;
            $j++; }
            $repeat_times = ($repeat_times>1)? $repeat_times : 1;
            $trip_date = date(str_replace("/","-",$trip_date));
            for($j=0; $j<$repeat_times; $j++) {

               	$wpdb->insert(
            		'trips',
                	array(
                			'id' => '',
                			'trip_name' => $trip_name,
                			'routes_ids' => $routes_ids,
                			'routes_data' => serialize($routes_data),
                			'trip_date' => $trip_date." ".$trip_time,
                            'driver'  => '',
                            'status'  => 'Upcoming',
                		)
            	 );
                if($trip_type == "Weekly"){
                       $trip_date = date('Y-m-d H:i:s', strtotime('+1 week', strtotime($trip_date)));
                       foreach($routes_data AS $key => $route_data) {
                               $routes_data[$key]['late_cut_off'] = date('Y-m-d H:i:s', strtotime('+1 week', strtotime($route_data['late_cut_off'])));
                               $routes_data[$key]['final_cut_off'] = date('Y-m-d H:i:s', strtotime('+1 week', strtotime($route_data['final_cut_off'])));
                       }
                 }
                else if($trip_type == "Montly"){
                       $trip_date = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($trip_date)));

                       foreach($routes_data AS $key => $route_data) {
                               $routes_data[$key]['late_cut_off'] = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($route_data['late_cut_off'])));
                               $routes_data[$key]['final_cut_off'] = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($route_data['final_cut_off'])));
                       }
                }
            }
            $msg .= $repeat_times." trips Successfully added";
        }

  if(isset($_POST['submit']) && $_POST['current_form']=='edit_trip_form'){
    		// sanitize form values
       	    $trip_name  = sanitize_text_field( $_POST["e_trip_name"] );
       	    $trip_id  = sanitize_text_field( $_POST["e_trip_id"] );
    		$trip_date  = sanitize_text_field( $_POST["e_trip_date"] )." ".sanitize_text_field( $_POST["e_trip_time"] );
            $j=0; $routes_data; $routes_ids=""; 
            while(isset($_POST["e_route_name"][$j]))  {  //&& $_POST["e_route_name"][$j]!=""
                $route_id = sanitize_text_field( $_POST["e_route_id"][$j] );
                $routes_data[] = array(
                                          'route_id' => $route_id,
                                          'route_name' => sanitize_text_field( $_POST["e_route_name"][$j] ),
                                          'late_cut_off' => sanitize_text_field( $_POST["e_late_threshold_date"][$j] )." ".sanitize_text_field( $_POST["e_late_threshold_time"][$j] ),
                                          'final_cut_off' => sanitize_text_field( $_POST["e_cut_off_date"][$j] )." ".sanitize_text_field( $_POST["e_cut_off_time"][$j] ),
                                   );
                $routes_ids .= ($j==0) ? $route_id : ",".$route_id;
            $j++; }
            $wpdb->update(
                  'trips',
                   array(
                			'trip_name' => $trip_name,
                			'routes_ids' => $routes_ids,
                			'routes_data' => serialize($routes_data),
                			'trip_date' => $trip_date,
                		),
                   array(
            			'id' => $trip_id
            		)
               );
            $msg .= "Trip Successfully Saved";
        }
?>
<style>



.viewTable{
    background-color: #dbf5e0; width:100%;
}
.viewTable th{
  padding: 0px;
  text-align: center;
}
.viewTable td{
  padding: 5px;
}
#group_items_list{
  background: white;
  border-top: 2px solid;
  width: 100%;
}
#group_items_list td{
   padding: 5px;
  border-bottom: 2px solid;
}
#group_items_list th{
   padding: 5px;
   border-bottom: 3px solid;
}

#new_route_form td{
  padding: 3px;
}
#prices_table p{
  line-height: 0px;
  padding: 0px;
}

input[type=date]::-webkit-clear-button,
input[type=date]::-webkit-inner-spin-button,
input[type=date]::-webkit-calendar-picker-indicator {
     margin-left: 0px;
}
input[type=time]::-webkit-clear-button,
input[type=time]::-webkit-inner-spin-button,
input[type=time]::-webkit-calendar-picker-indicator {
     display:none;
}

</style>

<form method="post" action="" id="trips-admin-form" class="trips-admin-form" style="display: block;overflow: hidden;clear: both;">
 <div id="shipment-details">
  <input type="text" hidden="hidden" id="screen" value="">
   <div id="tags-wrapper" style="width:90%;">
    <h1><?php echo "Trips Settings" ?>
        <a style="float: right;" class="button" href="#" id="new_trip" onclick="switch_links(this,'','')">Add New Trip</a></h1>
       <div>
         <table class="viewTable">
             <tr>
               <th style=" text-align: left;width: 14%;"><?php esc_html_e('Trip Name', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 14%;"><?php esc_html_e('Covered Routes', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 9%;"><?php esc_html_e('Late Cut-off', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 9%;"><?php esc_html_e('Final Cut-off', 'wpcargo'); ?></th>
             </tr>
             <?php
            $trips = $wpdb->get_results( "SELECT * FROM trips GROUP BY trip_name ORDER BY trip_date ASC ");
            $i =0;

            foreach ( $trips as $trip ) {
                 $trip_id = $trip->id;

                 $trip_routes =  unserialize($trip->routes_data);

                 if(!empty($trip_routes)) {
                 $e=0; foreach ( $trip_routes as $trip_route ) {
                 ?>
                 <tr id="<?php echo $trip_id; ?>">

                   <?php if($e==0) { ?>
                       <td style='border-top:2px solid;'>
                         <?php echo $trip->trip_name; ?>
                         <br><span>
                             <!--input class='button' type='submit' id="submit" name='submit' value='View'-->
                             <a href="#" onclick="switch_links(this,<?php echo $i; ?>,<?php echo $trip_id; ?>)" id="view_trip" data-value="<?php echo $trip->trip_name; ?>" >View Trip</a>
                         </span>
                       </td>
                  <?php } else echo "<td></td>";?>
                   <td style=" text-align: left; border-top:1px solid;"><?php  echo $trip_route['route_name']; ?></td>
                   <td style=" text-align: left;border-top:1px solid;"><?php echo date_format(date_create($trip_route['late_cut_off']),'d-M-Y H:i'); ?></td>
                   <td style=" text-align: left;border-top:1px solid;"><?php echo date_format(date_create($trip_route['final_cut_off']),'d-M-Y H:i'); ?></td>

                 </tr>
                 <?php $e++; $i++; }
               }   }
             ?>
           </table>
        </div>
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
          <div style=" display: none;" id="trip_form">
          </div>
          <div style=" display: none;" id="trip_report_div">
          </div>

   </div>
   <div class="modal-footer">   <p id="dd"></p>
       <input type="hidden" id="action" name="action" value="trips_admin_form_save_action">
       <input type="hidden" id="current_form" name="current_form" value="trip_report">
       <input class='button' type='submit' id="submit" name='submit' value='Save'>
       <a class="button" onclick="trips_admin_form_save(this)">Save1</a>
       <a class="button" onclick="close_modal()">Close</a>
   </div>
  </div>
 </div>


 </div>
</form>

<script>

function remove_route(btn){
     var row = $(btn).closest("tr");
     row.fadeOut('slow', function(){
        this.remove();
      });
}
function route_select(elm){
     var row = $(elm).closest("tr");
     $(row).find('#route_name').val($(row).find('#route_id option:selected' ).text());
}
function add_row(){
        var item_row = '<tr id="new_row">'+$('#trip_form #group_items_list #fields_row').html()+'<tr>';
        $('#trip_form #group_items_list').append(item_row);
        $('#trip_form #group_items_list #new_row #route_name').val("");
        $('#trip_form #group_items_list #new_row #late_threshold_date').val("");
        $('#trip_form #group_items_list #new_row #late_threshold_time').val("");
        $('#trip_form #group_items_list #new_row #cut_off_date').val("");
        $('#trip_form #group_items_list #new_row #cut_off_time').val("");
        $('#trip_form #group_items_list #new_row').attr("id","fields_row");

}
function switch_links(btn,row_index,item_id){
  if(btn.id=="trip_report") {
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'trip_report_action',
                  index : item_id,
              },
              success:function(data) {
                  //$('#new_trip_form').hide();
                  $('#trip_form').hide();
                  $('#trip_report_div').show();
                  $('#current_form').val('trip_report');
                  $( '#trip_report_div' ).html( data );
                  $('#myModal #submit').hide();
                  $('#myModal').show();
              },
              error: function(errorThrown){
                  $( '#trip_report_div' ).html('Error retrieving data. Please try again.');
              }

          });
    }
  else if(btn.id=="view_trip") {
        var selected_trip = btn.getAttribute('data-value');
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'trip_single_action',
                  index : item_id,
                  selected_trip : selected_trip,
              },
              success:function(data) {
                  $( '#tags-wrapper' ).html( data );
              },
              error: function(errorThrown){
                  $( '#tags-wrapper' ).html('Error retrieving data. Please try again.');
              }

          });
  }
  else if(btn.id=="edit_trip" || btn.id=="new_trip") {
       var action = 'add_trip_action';
       var current_form = 'new_trip_form';
       if(btn.id=="edit_trip"){ action ='edit_trip_action';  current_form = 'edit_trip_form';  }
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : action,
                  index : item_id,
              },
              success:function(data) {
                  $('#trip_form').show();
                  $('#trip_report_div').hide();
                  $('#current_form').val(current_form);
                  $('#trip_form' ).html(data);
                  $('#myModal #submit').show();
                  $('#myModal').show();
              },
              error: function(errorThrown){
                  $( '#trip_form' ).html('Error retrieving data. Please try again.');
              }

          });

  }
  else if(btn.id=="delete_trip") {

      var r = confirm("Are you sure you want to delete this?");
      if (r == true) {
         $.ajax({
                url: wpcargoAJAXHandler.ajax_url,
                type:"POST",
                data: {
                    action    : 'delete_trip_action',
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
  else if(btn.id=="trip_status_update") {
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'trip_status_update_action',
                  trip_id : item_id,
                  row_index : row_index,
                  shipment_status : $(btn).closest('td').find('#status_text').text(),
              },
              success:function(data) {
                  $('#trip_form' ).html( data );
                  $('#current_form').val("trip_status_update");
                  $('#trip_report_div').hide();
                  $('#trip_form').show();
                  $('#myModal').show();
              },
              error: function(errorThrown){
                  $( '#trip_form' ).html('Error retrieving data. Please try again.');
              }

          });
    }
}
function toggle_trip_repeat(elm){
  if(elm.value=="Weekly" || elm.value=="Monthly") {
      $("#repeat_times_tr").show();
  }
  else {
      $("#repeat_times_tr").hide();
  }
}

function trips_admin_form_save(btn){
     var trip_id = $("#trip_id").val();

     $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: $("#trips-admin-form").serializeArray(),
          success:function(data) {

            //////////////////
            if($('#current_form').val()=="trip_status_update"){     alert(trip_id);
              //$("#tags-wrapper .viewTable").find("tr[id='"+item_id+"']")
                  //$("tr#item_id").find('td:eq('+i+') input').val("");
               }


             /* $('#trip_form' ).html( data );
              $('#current_form').val("trip_status_update");
              $('#trip_report_div').hide();
              $('#trip_form').show(); */
              $('#myModal').hide();
          },
          error: function(errorThrown){
              //$( '#trip_form' ).html('Error retrieving data. Please try again.');
          }

      });
}

</script>

