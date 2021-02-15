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
.report-table-bookings td{
  border-bottom: 1px solid;
  border-right: 1px solid;
}
.report-table-bookings .last{
  border-right: none;
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

<form method="post" action="" id="schedules-admin-form" class="schedules-admin-form" style="display: block;overflow: hidden;clear: both;">
 <div id="shipment-details">
  <input type="text" hidden="hidden" id="screen" value="">
   <div id="tags-wrapper" style="width:90%;">
        <p id="msg" style="background: #00FFFF; margin: 20px auto; padding: 5px; width: 400px; display: none;"></p>
    <h1><?php echo "Collection Schedules" ?>
        <a style="float: right;" class="button" href="#" id="new_schedule" onclick="switch_links(this,'','')">Add New Schedule</a></h1>
       <div>
         <table class="viewTable" id="schedules_table_list">
           <thead>
             <tr>
               <th style=" text-align: left;width: 14%;"><?php esc_html_e('Schedule Name', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 14%;"><?php esc_html_e('City', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 14%;"><?php esc_html_e('Upcoming Schedules', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 14%;"><?php esc_html_e('Active Schedules', 'wpcargo'); ?></th>
               <th style=" text-align: left;width: 14%;"><?php esc_html_e('Closed Schedules', 'wpcargo'); ?></th>
             </tr></thead>
            <tbody>
             <?php

             //update/close unclosed schedules
             update_schedules_status();

            $schedules = $wpdb->get_results( "SELECT * FROM collection_schedules GROUP BY schedule_name ORDER BY schedule_city ASC, schedule_name ASC ");
            $i =0;
            foreach ( $schedules as $schedule ) {
                 $schedule_id = $schedule->id;
                 $schedule_name = $schedule->schedule_name;
                 $schedule_city = $wpdb->get_results( "SELECT city_name FROM `countries_cities` WHERE id = '".$schedule->schedule_city."' ");
                 $Upcoming = $wpdb->get_results( "SELECT COUNT( id ) AS count FROM `collection_schedules` WHERE schedule_name = '$schedule_name' AND status = 'Upcoming' ");
                 $Active = $wpdb->get_results( "SELECT COUNT( id ) AS count FROM `collection_schedules` WHERE schedule_name = '$schedule_name' AND status = 'Active' ");
                 $Closed = $wpdb->get_results( "SELECT COUNT( id ) AS count FROM `collection_schedules` WHERE schedule_name = '$schedule_name' AND status = 'Closed' ");

                 ?>
                 <tr id="<?php echo $schedule_id; ?>">
                   <td style='border-top:1px solid;'>
                     <?php echo $schedule_name; ?>
                     <br><span>
                         <!--a href="#" onclick="switch_links(this,<?php echo $i; ?>,<?php echo $schedule_id; ?>)" id="main_schedule_view" data-value="<?php echo $schedule->schedule_name; ?>" >View</a>&nbsp;|&nbsp; -->
                         <a href="#" onclick="schedules_single_view('<?php echo $schedule_name; ?>')">Expand</a>&nbsp;|&nbsp;
                         <a href="#" id="duplicate_schedule" data-schedule_name="<?php echo $schedule_name; ?>" onclick="switch_links(this,<?php echo $i; ?>,<?php echo $schedule_id; ?>)">Repeat</a>&nbsp;|&nbsp;
                         <a href="#" onclick="switch_links(this,<?php echo $i; ?>,<?php echo $schedule_id; ?>)" id="main_schedule_edit" data-schedule_name="<?php echo $schedule->schedule_name; ?>"  data-schedule_city="<?php echo $schedule->schedule_city; ?>" >Edit</a>
                     </span>
                   </td>
                   <td style=" text-align: left; border-top:1px solid;"><?php echo $schedule_city[0]->city_name; ?> </td>
                   <td style=" text-align: left; border-top:1px solid;"><?php echo $Upcoming[0]->count; ?></td>
                   <td style=" text-align: left; border-top:1px solid;"><?php echo $Active[0]->count; ?></td>
                   <td style=" text-align: left;border-top:1px solid;"><?php echo $Closed[0]->count; ?></td>

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
      </div>
      <div class="modal-footer">
         <input type="hidden" id="action" name="action" value="schedule_admin_form_save_action">
         <input type="hidden" id="current_form" name="current_form" value="schedule_report">
         <!--input class='button' type='submit' id="submit" name='submit' value='Save'-->
         <a class="button" id="save_button" onclick="schedules_admin_form_save(this)">Save</a>
         <a class="button" onclick="close_modal()">Close</a>
      </div>
    </div>
   </div>


 </div>
</form>

<script>
 //trips table datatable
 jQuery(document).ready(function ($) {
            var table = $('#schedules_table_list').DataTable({stateSave: true});
  });
function switch_links(btn,row_index,item_id){
     $('body').append('<div class="wpcargo-loading">Loading...</div>');
  if(btn.id=="schedule_report") {
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'schedule_report_action',
                  index : item_id,
              },
              success:function(data) {
                  $('#current_form').val('schedule_report');
                  $( '.modal-body' ).html(data);
                  $('#myModal h2' ).text("Schedule Report");
                  $('#myModal #save_button').hide();
                  $('.wpcargo-loading').hide();
                  $('#myModal').show();
              },
              error: function(errorThrown){
                  $( '.modal-body' ).html('Error retrieving data. Please try again.');
              }

          });
    }
  else if(btn.id=="main_schedule_view") {
        var selected_schedule = btn.getAttribute('data-value');
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'schedule_single_action',
                  index : item_id,
                  selected_schedule : selected_schedule,
              },
              success:function(data) {
                  $( '#tags-wrapper' ).html( data );
                  $('.wpcargo-loading').hide();
              },
              error: function(errorThrown){
                  $( '#tags-wrapper' ).html('Error retrieving data. Please try again.');
              }

          });
  }
  else if(btn.id=="main_schedule_edit") {
        var schedule_name = btn.getAttribute('data-schedule_name');
        var schedule_city = btn.getAttribute('data-schedule_city');
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'main_schedule_edit_form_action',
                  index : item_id,
                  schedule_name : schedule_name,
                  schedule_city : schedule_city,
                  current_form : 'main_schedule_edit_form',
              },
              success:function(data) {
                  $('#current_form').val('main_schedule_edit_form');
                  $('.modal-body' ).html(data);
                  $('#myModal h2' ).text("Schedule Edit Form");
                  $('#myModal #save_button').show();
                  $('#myModal').show();
                  $('.wpcargo-loading').hide();
              },
              error: function(errorThrown){
                  $( '.modal-body' ).html('Error retrieving data. Please try again.');
              }

          });
  }
  else if(btn.id=="edit_schedule" || btn.id=="new_schedule") {
       var action = 'schedule_form_action';
       var current_form = 'new_schedule_form';
       if(btn.id=="edit_schedule"){ current_form = 'edit_schedule_form';  }

       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : action,
                  index : item_id,
                  current_form : current_form,
              },
              success:function(data) {
                  $('#current_form').val(current_form);
                  $('#action').val("schedule_admin_form_save_action");
                  $('.modal-body' ).html(data);
                  $('#myModal h2' ).text("Schedule Form");
                  $('#myModal #save_button').show();
                  $('#myModal').show();
                  $('.wpcargo-loading').hide();
              },
              error: function(errorThrown){
                  $( '.modal-body' ).html('Error retrieving data. Please try again.');
              }

          });

  }
  else if(btn.id=="delete_schedule") {

      var r = confirm("Are you sure you want to delete this?");
      if (r == true) {
         $.ajax({
                url: wpcargoAJAXHandler.ajax_url,
                type:"POST",
                data: {
                    action    : 'delete_schedule_action',
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
      $('.wpcargo-loading').hide();
  }

  else if(btn.id=="duplicate_schedule") {
          var schedule_name = btn.getAttribute('data-schedule_name');
          var data = '<br><label style="width:40%;"><b>Repeat Times: </b>&nbsp;</label> '
                    +'<input style="width:56%;" type="text" id="duplicates_no" name="duplicates_no" value="" placeholder="number of new occurrences">'
                    +'<input type="hidden" id="schedule_name" name="schedule_name" value="'+schedule_name+'">'
                    +'<input style="width:56%;" type="hidden" id="schedule_id" name="schedule_id" value="'+item_id+'"><br><br>';
          $('.modal-body').html(data);
          $('#myModal h2').text("Schedule Multiplier");
          $('#current_form').val("duplicate_schedule_form");
          $('#action').val("duplicate_schedule_action");
          $('#myModal').show();
          $('.wpcargo-loading').hide();
  }
  else if(btn.id=="terminate_schedule") {
          var schedule_name = btn.getAttribute('data-schedule_name');
          var data = '<p><b>Are you sure you want to terminate this service schedule? </b></p> '
                    +'<input type="checkbox" id="yes" name="yes" onclick="toggle_yes_no(this)">Yes  &nbsp;&nbsp;&nbsp;<input type="checkbox" id="no" name="no" onclick="toggle_yes_no(this)">No'
                    +'<div id="reason" style="display:none;"><br><label style="width:60%;"><b>Reasons for Terminating this service schedule: </b><br></label> '
                    +'<textarea style="width:60%;" type="text" id="comments" name="comments" value="" placeholder="Type reasons here..."></textarea></div>'
                    +'<input type="hidden" id="schedule_name" name="schedule_name" value="'+schedule_name+'">'
                    +'<input style="width:60%;" type="hidden" id="schedule_id" name="schedule_id" value="'+item_id+'"><br><br>';
          $('.modal-body' ).html(data);
          $('#myModal h2' ).text("Schedule Terminating");
          $('#current_form').val("terminate_schedule_form");
          $('#action').val("terminate_schedule_action");
          $('#myModal').show();
          $('#save_button').hide();
          $('.wpcargo-loading').hide();
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
function toggle_schedule_repeat(elm){
  if(elm.value=="Weekly" || elm.value=="Monthly") {
      $("#repeat_times_tr").show();
  }
  else if(elm.value=="Daily") {
       //$('#repeat_times').val("5");
      // $("#repeat_times_tr").show();
  }
  else {
      $("#repeat_times_tr").hide();
  }
}
function schedules_single_view(schedule_name){
       $('body').append('<div class="wpcargo-loading">Loading...</div>');
        var selected_schedule = schedule_name;
       $.ajax({
              url: wpcargoAJAXHandler.ajax_url,
              type:"POST",
              data: {
                  action    : 'schedule_single_action',
                  selected_schedule : selected_schedule,
              },
              success:function(data) {
                  $( '#tags-wrapper' ).html( data );
                  $('.wpcargo-loading').hide();
                  var table = $('#single_schedule_table_list').DataTable({
                                "order": [[1,"desc"]]
                                });
              },
              error: function(errorThrown){
                  $( '#tags-wrapper' ).html('Error retrieving data. Please try again.');
              }

          });
}
function schedules_admin_form_save(btn){
     $('body').append('<div class="wpcargo-loading">Loading...</div>');
     $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: $("#schedules-admin-form").serializeArray(),
          success:function(data) {
              $('#msg').html(data);
              $('#msg').show();
              $('#myModal').hide();
              $('.wpcargo-loading').hide();
            //////////////////
            if($('#current_form').val()=="new_schedule_form"){
               schedules_single_view($('#schedule_name').val());
            }  
            else if($('#current_form').val()=="edit_schedule_form"){
               schedules_single_view($('#schedule_name').val());
            }
            else if($('#current_form').val()=="terminate_schedule_form"){
                var schedule_id = $('#schedule_id').val();
                schedules_single_view($('#schedule_name').val());
            }
            else if($('#current_form').val()=="duplicate_schedule_form"){
                var schedule_id = $('#schedule_id').val();
                schedules_single_view($('#schedule_name').val());
            }

          },
          error: function(errorThrown){
              $('#msg').html('Error retrieving data. Please try again.');
              $('#msg').show();
          }

      });
}


</script>

