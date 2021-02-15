jQuery(document).ready(function ($) {
	var AJAXURL 				= wpcargoAJAXHandler.ajax_url;
	var vat_percentage 			= wpcargoAJAXHandler.vat_percentage;
	var deleteElementMessage 	= wpcargoAJAXHandler.deleteElementMessage;
	var autoFillPlaceholder 	= wpcargoAJAXHandler.autoFillPlaceholder;
	var wpcargoDateFormat 		= wpcargoAJAXHandler.date_format;
	var wpcargoTimeFormat 	 	= wpcargoAJAXHandler.time_format;
	var wpcargoDateTimeFormat 	= wpcargoAJAXHandler.datetime_format;
	
	$("#shipment-history .status_updated-name").attr('readonly', true);
	$(".wpcargo-datepicker, .wpcargo-timepicker, .wpcargo-datetimepicker").attr("autocomplete", "off");
	
	$(".wpcargo-datepicker").datetimepicker({
		timepicker:false,
		format:wpcargoDateFormat
	});

	$(".wpcargo-timepicker").datetimepicker({
		datepicker:false,
		format:wpcargoTimeFormat
	});

	$(".wpcargo-datetimepicker").datetimepicker({
		format:wpcargoDateTimeFormat
	});
	$('.misc-pub-section.wpc-status-section, #shipment-bulk-update').on('change', 'select.wpcargo_status', function( e ){
		e.preventDefault();
		var status = $(this).val();
		if( status ){
			$('.wpc-status-section .date').prop('required',true);
			$('.wpc-status-section .time').prop('required',true);
			$('.wpc-status-section .status_location').prop('required',true);
			$('.wpc-status-section .remarks').prop('required',true);
		}else{
			$('.wpc-status-section .date').prop('required',false);
			$('.wpc-status-section .time').prop('required',false);
			$('.wpc-status-section .status_location').prop('required',false);
			$('.wpc-status-section .remarks').prop('required',false);
		}
	});
});


function mutually_exclusive_checkboxes(){
          $('input[type=checkbox]').click(function () {
            var state = $(this)[0].checked,
                g = $(this).data('group');
             $(this).siblings()
                   .each(function () {
           $(this)[0].checked = g==$(this).data('group')&&state ? false : $(this)[0].checked;
                   });
        });
}
function close_modal(){
     $('#myModal').hide();
     //window.location['reload']();
}

function address_fields_toggle(elm){
     if(elm.value == "Residential Address"){
          $(elm).closest("table").find('.business').hide();
          $(elm).closest("table").find('.residential').show();
      }
      else if(elm.value == "Business Address"){
          $(elm).closest("table").find('.residential').hide();
          $(elm).closest("table").find('.business').show();
      }
      else {
          $(elm).closest("table").find('.residential').hide();
          $(elm).closest("table").find('.business').hide();
      }
}
function documents_switch(elm){
           var item = elm.dataset.group;
           var more = '';
           if(elm.checked && elm.id =="tax_invoice") {
               $("#doc2").hide();
               $('#tax_invoice_yes_more').show();
           }
           else if(elm.checked && elm.id=="no_tax_invoice"){
                 $("#doc2 input:checkbox").prop('checked', false);
                 $("#tax_invoice_yes_more input:checkbox").prop('checked', false);
                 $("#doc2").show();
                 $('#tax_invoice_yes_more').hide();
                 $('#receipt_yes_more').hide();
               }
           else if(elm.checked && elm.id =="receipt") {
               $('#receipt_yes_more').show();
           }
          else if(elm.checked && elm.id=="no_receipt"){
               $('#receipt_yes_more').hide();
               $("#receipt_yes_more input:checkbox").prop('checked', false);
           }
           else {
               $('#tax_invoice_yes_more').hide();
               if(elm.id =="no_tax_invoice") $("#doc2").hide();
             }

        mutually_exclusive_checkboxes();

}
function toggle_fileupload(elm){
      if(elm.checked && elm.getAttribute("data-id")=="upload") $("#"+elm.id+"_file").show();
      else  $('#'+elm.parentNode.id+' input[type="file"]').hide();
}
function collection_toggle(elm){
     switch(elm.value) {
        case "Door to Depo":
          $("#col_time").show();
          $("#del_time").hide();
          $("#del_after_hours").prop('checked', false);
          $("#wpcargo_shipper_address_type").removeAttr('disabled');
          $("#wpcargo_delivery_address_type").attr('disabled', 'disabled');
          add_remove_services("collectionfee","add");
          add_remove_services("deliveryfee","remove");
          break;
        case "Door to Door":
          $("#col_time").show();
          $("#del_time").show();
          $("#wpcargo_shipper_address_type").removeAttr('disabled');
          $("#wpcargo_delivery_address_type").removeAttr('disabled');
          add_remove_services("collectionfee","add");
          add_remove_services("deliveryfee","add");
          break;
        case "Depo to Door":
          $("#col_time").hide();
          $("#col_after_hours").prop('checked', false);
          $("#del_time").show();
          $('#del_after_hours_label').html("After Hours");
          $("#del_after_hours").prop('checked', false);
          $("#wpcargo_shipper_address_type").attr('disabled', 'disabled');
          $("#wpcargo_delivery_address_type").removeAttr('disabled');
          add_remove_services("collectionfee","remove");
          add_remove_services("deliveryfee","add");
          break;
        case "Depo to Depo":
          $("#col_time").hide();
          $("#col_after_hours").prop('checked', false);
          $("#del_time").hide();
          $("#del_after_hours").prop('checked', false);
          $("#wpcargo_shipper_address_type").attr('disabled', 'disabled');
          $("#wpcargo_delivery_address_type").attr('disabled', 'disabled');
          add_remove_services("collectionfee","remove");
          add_remove_services("deliveryfee","remove");
        default:
          $("#col_time").hide();
          $("#del_time").hide();
          $("#col_after_hours").prop('checked', false);
          $("#del_after_hours").prop('checked', false);
          $("#wpcargo_delivery_address_type").attr('disabled', 'disabled');
          $("#wpcargo_shipper_address_type").attr('disabled', 'disabled');
          add_remove_services("collectionfee","remove");
          add_remove_services("deliveryfee","remove");
     }
 }

function schedule_selector(elm){
     $('body').append('<div class="wpcargo-loading">Loading...</div>');
     $("#shipment-details  #transport_mode option").hide();
     $("#shipment-details  #transport_mode ").val("");
     $("#shipment-details  #service_type ").val("");
     $("#shipment-details  #transport_mode option[value='']").show();
     $('.wpcargo-loading').show();
     $.ajax({
            url: wpcargoAJAXHandler.ajax_url,
            type:"POST",
            data: {
                action    : 'schedule_selector_action',
                o_country : $("#org_1").val(),
                o_city : $("#org_1_1").val(),
                o_city_id : $("#org_1_1 option:selected").data("city_id"),
                d_country : $("#dest_1").val(),
                d_city : $("#dest_1_1").val(),
                d_city_id : $("#dest_1_1 option:selected").data("city_id"),
                screen : $("#screen").val(),
                selected : elm.id
            },
            dataType : "json",
            success:function(data) {
                if(elm.id=="org_1" || elm.id=="dest_1"){
                            $('#shipment-details #'+elm.id+'_1').html( data[0] );
                            if($("#org_1").val() != $("#dest_1").val()) {add_remove_services("customsdeclarationfee","add"); add_remove_services("bordertaxes","add"); }
                            else if($("#org_1").val() == $("#dest_1").val()) {add_remove_services("customsdeclarationfee","remove"); add_remove_services("bordertaxes","remove"); }
                        }
                else if(elm.id=="org_1_1" || elm.id=="dest_1_1"){
                    $("#route_abrs").val($("#org_1_1 option:selected").data("value")+"-"+$("#dest_1_1 option:selected").data("value"));
                    var post_title = $( "#titlewrap #title" ).val().split("-");
                    $("#booking_reference").val($("#org_1_1 option:selected").data("value")+"-"+post_title[1]+"-"+$("#dest_1_1 option:selected").data("value"));
                    if(elm.id=="org_1_1") $('#shipper-details #collection_schedule_id' ).html( data[0] );
                    if(elm.id=="dest_1_1") $('#shipper-details #delivery_schedule_id' ).html( data[0] );
                    var modes = data[1].split(",");
                    if(modes.includes('Road')) { $("#shipment-details  #transport_mode option[value='Road']").show();}
                    if(modes.includes("Air")) { $("#shipment-details  #transport_mode option[value='Air']").show();}
                    if(modes.includes("Ocean")) { $("#shipment-details  #transport_mode option[value='Ocean']").show();}
                    if(!modes.includes("Ocean") && !modes.includes("Air") && !modes.includes('Road')) {$("#shipment-details  #transport_mode option").show();}
                    elm.nextElementSibling.style.display = (elm.value=="Other") ? "Block": "None";
                    toggle_services();
                    if($("#org_1_1").val()=="Other" || $("#dest_1_1").val()=="Other" ){
                      $("#shipment-details  #transport_mode option").show();
                      //$("#shipment_trip_id").prop('required',false);

                    }
                    else {
                      $("#transport_mode").prop('required',true);
                      //$("#shipment_trip_id").prop('required',true);
                    }
                }
              $('.wpcargo-loading').hide();
             },
            error: function(errorThrown){
                $('.wpcargo-loading').hide();
                alert('<p>Error retrieving data. Please try again.</p>');
            }

        });
}
function toggle_country_input(elm){
  if(elm.value=="new_country"){ $("#country_name_input").show(); $('#country_name_input #country_name').prop("required", true);  }
  else {$("#country_name_input").hide();  $('#country_name_input #country_name').prop("required", false);}
}
function toggle_services(){
  $("#service_type option").show();
  var org_details = $("#org_1_1 option:selected").data("moreinfo").split(",");
  var dest_details = $("#dest_1_1 option:selected").data("moreinfo").split(",");
  if(org_details[0]=="") { $("#service_type option[value='Depo to Depo']").hide(); $("#service_type option[value='Depo to Door']").hide(); }
  if(dest_details[0]=="") { $("#service_type option[value='Door to Depo']").hide(); $("#service_type option[value='Depo to Depo']").hide(); }
  if(org_details[1]=="") { $("#service_type option[value='Door to Depo']").hide(); $("#service_type option[value='Door to Door']").hide(); }
  if(dest_details[2]=="") { $("#service_type option[value='Depo to Door']").hide(); $("#service_type option[value='Door to Door']").hide(); }
  if($("#org_1").val()==$("#dest_1").val()) $("#taxes_clearance").hide();
  else  $("#taxes_clearance").show();
}

function toggle_dimentions(elm,begin=0,end=0){
      var row = $(elm).closest("tr");
      for(var i=begin; i<=end; i++){
         if((elm.getAttribute("type")=="checkbox" && elm.checked) || (elm.getAttribute("type")=="number" && elm.value!="") ) {
           row.find('td:eq('+i+') input').attr("disabled", true);
           row.find('td:eq('+i+') input').val("");
         }
         else { row.find('td:eq('+i+') input').attr("disabled", false); }
      }
}
function check_if_late(elm){
  var current_time = new Date();
  var schedule_date = new Date($(elm).find('option:selected').attr('data-schedule_date'));
  var late_cut_off = new Date($(elm).find('option:selected').attr('data-value'));
  if(current_time > late_cut_off){ $("#shipment-details #is_late_booking").prop('checked',true); add_remove_services("latebookingfee","add"); }
  else {$("#shipment-details #is_late_booking").prop('checked',false); add_remove_services("latebookingfee","remove");  }
  //user cannot select delivery date that is older than collection date
  $("#delivery_schedule_id option").each(function() {
      var option_schedule_date = new Date(this.getAttribute('data-schedule_date'));
      if( option_schedule_date < schedule_date) $(this).hide();
   });
}

function form_assign_schedule(btn,post_id){
  $('.wpcargo-loading').show();
  $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: {
              action  : 'schedule_dates_action',
              post_id : post_id,
              element : btn.id,
          },
          success:function(data) {
              $('#quote_div').html(data);
              $('.wpcargo-loading').hide();
              $('#quote_div').show();
              $('#state_div').hide();
              $('#invoice_btn').hide();
              $('#packages_div').hide();
              $('#submit_btn').attr("onclick","save_assign_trip()");
              $('.modal-header h2').text("Service Date Selection");
              $('.modal-content').css({ "width" : "40%", "min-width" : "500px"});
              $('#myModal').show();
          },
          error: function(errorThrown){
              $( '#quote_div' ).html('Error retrieving data. Please try again.');
          }
      });
}
function save_assign_trip(){
  $('.wpcargo-loading').show();
  $.ajax({
        url: wpcargoAJAXHandler.ajax_url,
        type:"POST",
        data: {
            action    : 'trip_assign_save_action',
            selected_trip:  $('#myModal #shipment_trip_id').val(),
            shipment_id: $('#post_id').val(),
            schedule_field_name : $('#schedule_field_name').val(),
        },
        success:function(data) {
             window.location['reload']();
         },
        error: function(errorThrown){
            $('.wpcargo-loading').hide();
            alert('Error retrieving data. Please try again.');
        }

    });
}
function select_service(elm){
  if(elm.checked) add_remove_services(elm.value,"add");
  else add_remove_services(elm.value,"remove");
}
function add_remove_services(elmvalue,action){
  var arr = $('#service_items').val().split(",");
  var idx = arr.indexOf(elmvalue);
  if (idx >= 0) {
    arr.splice(idx, 1);
  }
  if(action=="add"){
    if($('#service_items').val()=="") arr = [elmvalue];
    else arr.push(elmvalue);
  }
  $('#service_items').val(arr.join(","));
}