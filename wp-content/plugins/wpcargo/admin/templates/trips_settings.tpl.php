<?php
  global $wpdb;
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
#group_items {
    background: #EEEEEE;
}
#group_items #selected_drivers_list td{
   padding: 2px;
}


</style>

<form method="post" action="" id="trips-admin-form" class="trips-admin-form" style="display: block;overflow: hidden;clear: both;">
 <div id="shipment-details">

  <input type="text" hidden="hidden" id="screen" value="">
   <div id="tags-wrapper" style="width:90%;">
    <h1><?php echo "Trips Settings" ?>
        <a style="float: right;" class="button" href="#" id="new_trip" onclick="switch_links(this,'','')">Add New Trip</a></h1>
       <div>
         <table class="viewTable" id="trips_table_list">
           <thead>
             <tr>
               <th style=" text-align: left;width: 14%;"><?php esc_html_e('Trip Name', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 14%;"><?php esc_html_e('Covered Cities', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 9%;"><?php esc_html_e('Trip Date', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 9%;"><?php esc_html_e('Drivers', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 9%;"><?php esc_html_e('Status', 'wpcargo'); ?></th>
             </tr></thead>
            <tbody>
             <?php

            $trips = $wpdb->get_results( "SELECT * FROM trips ORDER BY trip_date ASC ");
            $i =0;

            foreach ( $trips as $trip ) {
                 $trip_id = $trip->id;
                 ?>
                 <tr id="<?php echo $trip_id; ?>">

                   <td style='border-top:1px solid;'>
                     <?php echo $trip->trip_name; ?>
                     <br><span>
                         <?php  $acts = array();
                            $acts[] = '<a href="#" id="trip_report" onclick="switch_links(this,'.$i.','.$trip_id.')">Report</a>';
                            if($trip->status!="Closed") $acts[] = '<a href="#" id="edit_trip" onclick="switch_links(this,'.$i.','.$trip_id.')">Edit</a>';
                            if($trip->status!="Closed" && $trip->status!="Terminated") $acts[] = '<a href="#" style="color: red;" data-trip_name="'.$trip->trip_name.'" id="terminate_trip" onclick="switch_links(this,'.$i.','.$trip_id.')">Terminate</a>';
                            if($trip->status=="Upcoming") $acts[] = '<a href="#" style="color: red;" id="delete_trip" onclick="switch_links(this,'.$i.','.$trip_id.')">Del</a>';
                          $acts = implode("&nbsp;|&nbsp;",$acts);
                          print($acts);
                          ?>
                      </span>
                   </td>
                   <td style=" text-align: left; border-top:1px solid;"><?php
                        $selected_schedules = unserialize($trip->city_schedules);
                        $cities=array();
                        if(is_array($selected_schedules)){
                          foreach($selected_schedules AS $selected_schedule){
                              //generate list of cities
                              $schedule_city = $wpdb->get_results( "SELECT id, city_name FROM `countries_cities` WHERE id = '".$selected_schedule['schedule_city']."'");
                              $cities[] = $schedule_city[0]->city_name;
                        }}
                        echo implode(",",$cities); ?>
                   </td>
                   <td style=" text-align: left; border-top:1px solid;">
                        <span style="display:none"><?php echo strtotime($trip->trip_date); ?> </span>
                        <?php  echo date_format(date_create($trip->trip_date),"d-F-Y"); ?>
                   </td>
                   <td style='border-top:1px solid;'><?php echo str_replace(",", ", ", unserialize($trip->drivers)); ?> </td>
                   <td style=" text-align: left;border-top:1px solid;"><?php  echo $trip->status; ?></td>

                 </tr>
                 <?php $i++;
               }
             ?>
           </tbody>
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
   <div class="modal-footer">
       <input type="hidden" id="action" name="action" value="trips_admin_form_save_action">
       <input type="hidden" id="current_form" name="current_form" value="trip_report">
       <!--input class='button' type='submit' id="submit" name='submit' value='Save'-->
       <a class="button" id="save_button" onclick="trips_admin_form_save(this)">Save</a>
       <a class="button" onclick="close_modal()">Close</a>
   </div>
  </div>
 </div>


 </div>
</form>

<script>

 //trips table datatable
 jQuery(document).ready(function ($) {
            var table = $('#trips_table_list').DataTable({"order": [[2,"desc"]]});
  });

function remove_schedule(btn){
     var row = $(btn).closest("tr");
     row.fadeOut('slow', function(){
        this.remove();
      });
}
function add_row(){
        var item_row = '<tr id="new_row">'+$('#trip_form #group_items_list #fields_row').html()+'<tr>';
        $('#trip_form #group_items_list').append(item_row);
        $('#trip_form #group_items_list #new_row select').val("");
        $('#trip_form #group_items_list #new_row input').val("");
        $('#trip_form #group_items_list #new_row').attr("id","fields_row");

}
function switch_links(btn,row_index,item_id){
  $('body').append('<div class="wpcargo-loading">Loading...</div>');
  $('#action').val("trips_admin_form_save_action");
  $('#save_button').show();
  if(btn.id=="trip_report") {
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'trip_report_action',
                  index : item_id,
              },
              success:function(data) {
                  $('#trip_form').hide();
                  $('#trip_report_div').show();
                  $('#current_form').val('trip_report');
                  $('#trip_report_div' ).html( data );
                  $('#save_button').hide();
                  $('.wpcargo-loading').hide();
                  $('#myModal').show();
              },
              error: function(errorThrown){
                  $( '#trip_report_div' ).html('Error retrieving data. Please try again.');
              }

          });
   }
  else if(btn.id=="edit_trip" || btn.id=="new_trip") {
       var action = 'trip_form_action';
       var current_form = 'new_trip_form';
       if(btn.id=="edit_trip"){ current_form = 'edit_trip_form';  }
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : action,
                  index : item_id,
                  current_form : current_form,
              },
              success:function(data) {
                  $('#trip_form').show();
                  $('#trip_report_div').hide();
                  $('#current_form').val(current_form);
                  $('#trip_form' ).html(data);
                  $('#myModal #submit').show();
                  $('.wpcargo-loading').hide();
                  $('#myModal').show();
              },
              error: function(errorThrown){
                  $( '#trip_form' ).html('Error retrieving data. Please try again.');
              }

          });

  }
  else if(btn.id=="delete_trip") {
      $('.wpcargo-loading').hide();
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
                  $('.wpcargo-loading').hide();
                  $('#myModal').show();
              },
              error: function(errorThrown){
                  $( '#trip_form' ).html('Error retrieving data. Please try again.');
              }

          });
    }

  else if(btn.id=="terminate_trip") {
      var trip_name = btn.getAttribute('data-trip_name');
      var data = '<p><b>Are you sure you want to terminate this trip? </b></p> '
                +'<input type="checkbox" id="yes" name="yes" onclick="toggle_yes_no(this)">Yes  &nbsp;&nbsp;&nbsp;<input type="checkbox" id="no" name="no" onclick="toggle_yes_no(this)">No'
                +'<div id="reason" style="display:none;"><br><label style="width:60%;"><b>Reasons for Terminating this trip: </b><br></label> '
                +'<textarea style="width:60%;" type="text" id="comments" name="comments" value="" placeholder="Type reasons here..."></textarea></div>'
                +'<input type="hidden" id="trip_name" name="trip_name" value="'+trip_name+'">'
                +'<input style="width:60%;" type="hidden" id="trip_id" name="trip_id" value="'+item_id+'"><br><br>';

        $('#trip_form' ).html( data );
        $('#current_form').val("terminate_schedule_form");
        $('#action').val("terminate_trip_action");
        $('#trip_report_div').hide();
        $('#trip_form').show();
        $('.wpcargo-loading').hide();
        $('#save_button').hide();
        $('#myModal').show();


    }
}

function toggle_yes_no(btn){
   if(btn.id=="yes" && btn.checked){
       $("#no").prop("checked",false);
       $("#reason").show();
       $('#save_button').show();
   }
   else if(btn.id=="no" && btn.checked){
       $("#yes").prop("checked",false);
       $("#reason").hide();
       $('#save_button').hide();
   }
   else {
       $("#reason").hide();
       $('#save_button').hide();
   }
}
function schedule_city_select(btn){
   $(btn).closest('tr').find("#schedule_id option").show();
   var schedule_city = btn.value;
   $(btn).closest('tr').find("#schedule_id option[id!='"+schedule_city+"']").hide();
   $(btn).closest('tr').find("#schedule_id option[id='optionlabel']").show();
}
function trips_admin_form_save(btn){
     $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: $("#trips-admin-form").serializeArray(),
          success:function(data) {
            if($('#current_form').val()=="new_trip_form" || $('#current_form').val()=="edit_trip_form" || $('#current_form').val()=="terminate_schedule_form"){
                  alert(data);
                  location.reload(true);
               }
          },
          error: function(errorThrown){
              $( '#trip_form' ).html('Error saving data. Please try again.');
          }

      });
}

function items_grouper(item){
  if(item.value != ""){
        var drivername = item.value.trim();
        var TableData = new Array();
        $('#selected_drivers_list tr').each(function(row, tr){
                  TableData.push($(tr).find('td:eq(0)').text().replace('X', '').trim());
              });
         if(!TableData.includes(drivername)){
                var rw_id = item.value.replace(" ", "");
                var item_row = '<tr id="'+rw_id+'"><td>'+drivername+'&nbsp;<a href="#" style="color:red;" id="'+rw_id+'" onclick="remove_item(this)" >X</a></td></tr>';
                if($('#selected_drivers_list').val()=="") $('#selected_drivers_list').append(item_row);
                else  $('#selected_drivers_list').append("\n,"+item_row);
              }
        TableData = new Array();
        $('#selected_drivers_list tr').each(function(row, tr){
                  TableData.push($(tr).find('td:eq(0)').text().replace('X', '').trim());
              });
        $('#group_items #selected_drivers').val(TableData);
  }
}
function remove_item(item){
     $(item).closest('tr').remove();

        var TableData = new Array();
        $('#selected_drivers_list tr').each(function(row, tr){
                  TableData.push($(tr).find('td:eq(0)').text().replace('X', '').trim());
              });
        $('#group_items #selected_drivers').val(TableData);
}

</script>

