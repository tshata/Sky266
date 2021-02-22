
window.sessionStorage;
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    //document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    //document.getElementById("nextBtn").innerHTML = "Next";
    document.getElementById("nextBtn").setAttribute("onclick","nextPrev(1)");
  }
  // ... and run a function that displays the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n,Btn="") {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  //if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;

  // if you have reached the end of the form... :
  //if (currentTab >= x.length) {
    //...the form gets submitted:
    //...the form gets submitted:
 if (currentTab >= x.length) {
    //...the form gets submitted:
    if(Btn=="Pay") $("#submit_btn").val("Pay Now");
    else  $("#submit_btn").val("Pay Later");
    document.getElementById("regForm").submit();
    $('.wpcargo-loading').show();
    return false;
  }

  document.getElementById("nextBtn").innerHTML = "Next";
  document.getElementById("nextBtn").style.display = "inline";
  document.getElementById("payBtn").style.display = "none";
  $(".shipping-form").css("width", "700px");
  $("#nextBtn").attr("disabled", false);
  $("#closeBtn").hide();
  $("#savePdf").hide();

 if(x[currentTab].id == "package_tab") {
        $(".shipping-form").css("width", "1000px");
        if($("#org_1").val() != $("#dest_1").val()) {add_remove_services("customsdeclarationfee","add"); add_remove_services("bordertaxes","add"); }
        else if($("#org_1").val() == $("#dest_1").val()) {add_remove_services("customsdeclarationfee","remove"); add_remove_services("bordertaxes","remove"); }
   }
 if(x[currentTab].id == "collection_tab") {
    var weight =  (parseFloat($("#package_volumetric").html()) > parseFloat($("#package_actual_weight").html())) ? parseFloat($("#package_volumetric").html()) : parseFloat($("#package_actual_weight").html());
    $("#package-weight").val(weight);
    var package_cbm =  parseFloat($("#package_cbm").text());
    $("#total_package-cbm").val(package_cbm);

     var o_country =  $("#org_1").val();
     var d_country =  $("#dest_1").val();
     if(o_country != d_country) {
         $("#clearance").prop('checked', true);
     }
     else {
         $("#noclearance").prop('checked', true);
     }
 }
  /////////////Pricing tab///////////////
  if(x[currentTab].id == "price-estimates"){
    $('.wpcargo-loading').show();
      $.ajax({
            url: my_ajaxurl,
            type:"POST",
            data: {
                action     : 'price_estimates_action', // load function hooked to: "wp_ajax_*" action hook
                o_country : $("#org_1").val(),
                o_city : $("#org_1_1").val(),
                d_country : $("#dest_1").val(),
                d_city : $("#dest_1_1").val(),
                package_desc : $("#goods_description").val(),
                package_weight  : $("#wpcargo_package_weight").val(),
                package_cbm : $("#wpcargo_package_cbm").val(),
                service_items : $('#service_items').val(),
                transport_mode : $('#transport_mode').val(),
            },
            success:function(data) {
                $( '#price_tables' ).html( data ); // add HTML results to empty div
            },
            error: function(errorThrown){
                $( '#price_tables' ).html('<p>Error retrieving data. Please try again.</p>');
            },
            complete: function(){
              $('.wpcargo-loading').hide();
            }
        });

    // buttons

    if(document.getElementById("trip_1").checked == false && document.getElementById("trip_2").checked == false){
         $("#nextBtn").css("background","grey");
         $("#nextBtn").attr("disabled", true);

      }
    else $("#nextBtn").attr("disabled", false);
      $("#price_tables").html(price_tables);
     document.getElementById("nextBtn").innerHTML = "Book Now";
     $("#closeBtn").show();
     $("#savePdf").show();


  }
  /////////////collection-details tab///////////////
  if(x[currentTab].id == "collection-details"){
        if($("#service_type").val() =="Depo to Door" || $("#service_type").val() =="Depo to Depo"){
           nextPrev(n); return false;
        }
        else if($('#collection-details #wpcargo_shipper_address_type').val()=="")  {
              $('#collection-details .residential').hide();
              $('#collection-details .business').hide();
          }
  }
   /////////////delivery-details tab///////////////
  if(x[currentTab].id == "delivery-details"){
        if($("#service_type").val() =="Door to Depo" || $("#service_type").val() =="Depo to Depo"){
           nextPrev(n); return false;
        }
        else if($('#delivery-details #wpcargo_shipper_address_type').val()=="")  {
              $('#delivery-details .delivery-residential').hide();
              $('#delivery-details .delivery-business').hide();
          }
  }
   /////////////documents tab///////////////
   if(x[currentTab].id == "documents"){
         if($("#org_1").val() == $("#dest_1").val()){
           nextPrev(n); return false;
         }
  }
   /////////////collection instructions tab///////////////
   if(x[currentTab].id == "collection-instructions"){
        $("#nextBtn").css("background","grey");
         $("#nextBtn").attr("disabled", true);
  }

  /////////////summerty tab///////////////
  if(x[currentTab].id == "summary"){
        //Collection Details
        document.getElementById("regForm").style.width = "90%";
        $("#summary #label_info_shipper").html($("#wpcargo_shipper_address_type").val() + ", " + $("#wpcargo_shipper_estate").val() + ", " + $("#wpcargo_shipper_bussiness").val() + ", " + $("#wpcargo_shipper_address").val());
        $("#summary #label_info_person").html($("#wpcargo_shipper_name").val());
        $("#summary #label_info_main_contacts").html($("#wpcargo_shipper_phone_1").val());
        $("#summary #label_info_alt_contacts").html($("#wpcargo_shipper_phone_2").val());
        $("#summary #label_info_reference").html($("#collection_reference").val());
        $("#summary #label_info_instructions").html($("#collection_instructions").val());

        //Delivery Details
        $("#summary #label_info_daddress").html($("#wpcargo_delivery_address_type").val() + ", " + $("#wpcargo_delivery_estate").val() + ", " + $("#wpcargo_delivery_bussiness").val() +  ", " + $("#wpcargo_delivery_address").val());
        $("#summary #label_info_dperson").html($("#wpcargo_delivery_name").val());
        $("#summary #label_info_dmain_contacts").html($("#wpcargo_delivery_phone_1").val());
        $("#summary #label_info_dalt_contacts").html($("#wpcargo_delivery_phone_2").val());
        $("#summary #label_info_dreference").html($("#delivery_reference").val());
       // $("#summary #label_info_dinstructions").html($("#collection_instructions").val());

        //Shipper Details
        $("#summary #label_info_company").html($("#wpcargo_receiver_company").val());
        $("#summary #label_info_name").html($("#wpcargo_receiver_fname").val());
        $("#summary #label_info_surname").html($("#wpcargo_receiver_sname").val());
        $("#summary #label_info_M_contacts").html($("#wpcargo_receiver_phone_1").val());
        $("#summary #label_info_alternative").html($("#wpcargo_receiver_phone_2").val());
        $("#summary #label_info_email").html($("wpcargo_receiver_email").val());
        $("#summary #label_info_origin").html($("#org_1").val()+", "+$("#org_1_1").val());
        $("#summary #label_info_dest").html($("#dest_1").val()+", "+$("#dest_1_1").val());
        $("#summary #label_info_mode").html($("#transport_mode").val());
        $("#summary #label_info_service").html($("#service_type").val());

        var collection = ($("#collection").is(":checked")) ? "Package to be collected from "+$("#wpcargo_shipper_address_type").val() : "Package to be delivered to our warehouse";
        var clearance = ($("#clearance").is(":checked")) ? "Package to be cleared at the border" : "No clearance to be done";
        $("#summary #label_info_collection").html(collection);
        $("#summary #label_info_clearance").html(clearance);
        var selected_trip = ($("#trip_1").prop('checked')==true) ? $('#trip_1_div').html() : $('#trip_2_div').html();
        $("#price_info").html(selected_trip);
        var route_abrs = $("#route_abrs").val().split("-");
        var post_name = $("#post_name").val().split("-");
        $("#booking_reference b").html(route_abrs[0]+"-"+post_name[1]+"-"+route_abrs[1]);


          //package details
        $("#summary #label_info_special").html($('#service_items').val().split(" "));
        $("#summary #label_info_est_weight").html($("#wpcargo_package_weight").val() + " kg");
        $("#summary #label_info_est_cbm").html($("#wpcargo_package_cbm").val() + " cbm");
        $("#summary #label_info_goods_desc").html($("#goods_description").val());

        /**
        $('#packages_table > tbody').html("");
        var rowcount = $("#wpcargo-package-table tr").length;
        var i = 1;
        $('#wpcargo-package-table tr').each(function(row, tr){
            if(i != 1 && i != rowcount) {
                    var markup = "<tr>
                                 "<td>"+$(tr).find('td:eq(0) input').val()+"</td>"
                                 +"<td></td>"
                                 +"<td>"+$(tr).find('td:eq(1) input').val()+"</td></tr>";
                    $("#packages_table").append(markup);
                }
          i++;
        });      **/
     //hide next buttons

     document.getElementById("nextBtn").innerHTML = "Pay Later";
     document.getElementById("payBtn").style.display = "inline";



  }


  // Otherwise, display the correct tab:
  showTab(currentTab);
}
/*
function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "" && !y[i].classList.contains("notvalidate") ) {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false:
      valid = false;
    }
  }
  y = x[currentTab].getElementsByTagName("select");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "" && !y[i].classList.contains("notvalidate")) {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false:
      valid = false;
    }
  }
  //validate the checkboxes in collection tab
  if(x[currentTab].id == "collection_tab"){
         if($("#collection").prop('checked')==false && $("#nocollect").prop('checked')==false){ alert("You have to select an option to proceed."); valid = false;}
    }
  //validate the checkboxes in documents tab
  if(x[currentTab].id == "documents"){
         if($("#tax_invoice").prop('checked')==false && $("#no_tax_invoice").prop('checked')==false){ alert("You have to select an option to proceed."); valid = false;}

    }


  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}   */

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class to the current step:
  x[n].className += " active";
}


$(document).ready(function () {
       mutually_exclusive_checkboxes();
       $('#collection_instructions').autosize();

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
function address_fields_toggle(elm){
     var parent_div = (elm.id=="wpcargo_shipper_address_type")? "#collection-details" : "#delivery-details";
     if(elm.value == "Residential Address"){
          $(parent_div+' .business').hide();
          $(parent_div+' .residential').show();
      }
      else if(elm.value == "Business Address"){
          $(parent_div+' .residential').hide();
          $(parent_div+' .business').show();
      }
      else {
          $('.residential').hide();
          $('.business').hide();
      }
}
 function trip_select(elm){
    var TableData = {};
    var i=0;
    $("#"+elm.id+"_div tr").each(function(row, tr){
           var key = $(tr).find('td:eq(1)').attr("id");
           var val = $(tr).find('td:eq(1)').html().replace(/[^\d.-]/g, '');
           TableData[key] = val;
         i++;
      });
    $("#price-estimates #wpcargo_price_estimates").val($("#"+elm.id+"_div #trip_prices").text());
    if($("#"+elm.id+"_div #trip_prices").text().includes("latebookingfee") && elm.checked == true) $("#is_late_booking").prop("checked",true);
    else $("#is_late_booking").prop("checked",false);
  if(elm.checked == true){
       $("#nextBtn").attr("disabled", false);
       $("#nextBtn").css("background","#187CC9");
    }
  else {
    $("#nextBtn").attr("disabled", true);
    $("#nextBtn").css("background","grey");
  }
  if(elm.id == "trip_1")    $("#trip_2").prop('checked', false);
  else $("#trip_1").prop('checked', false);

 }
 function collection_toggle(elm){
     switch(elm.value) {
        case "Door to Depo":
          $("#col_time").show();
          $("#del_time").hide();
          $("#del_after_hours").prop('checked', false);
          add_remove_services("collectionfee","add");
          add_remove_services("deliveryfee","remove");
          break;
        case "Door to Door":
          $("#col_time").show();
          $("#del_time").show();
          add_remove_services("collectionfee","add");
          add_remove_services("deliveryfee","add");
          break;
        case "Depo to Door":
          $("#col_time").hide();
          $("#del_time").show();
          $("#col_after_hours").prop('checked', false);
          add_remove_services("collectionfee","remove");
          add_remove_services("deliveryfee","add");
          break;
        case "Depo to Depo":
          $("#col_time").hide();
          $("#del_time").hide();
          //$('#after_hours_label').html("After Hours");
          $("#del_after_hours").prop('checked', false);
          $("#col_after_hours").prop('checked', false);
          add_remove_services("collectionfee","remove");
          add_remove_services("deliveryfee","remove");
        default:
          $("#col_time").hide();
          $("#del_time").hide();
          $("#del_after_hours").prop('checked', false);
          $("#col_after_hours").prop('checked', false);
          add_remove_services("collectionfee","remove");
          add_remove_services("deliveryfee","remove");
     }
 }
 function collection_hours(elm){
           if(elm.checked && elm.id=="col_after_hours"){
               var extra_fields = "After Hours: <b>16:30 - </b><input type='time' name='collection_time_max' id='collection_time_max' value='23:59' style='width: 100px;'> hrs";
               $('#col_after_hours_label').html(extra_fields);
           }
           else if(elm.checked && elm.id=="del_after_hours"){
               var extra_fields = "After Hours: <b>16:30 - </b><input type='time' name='delivery_time_max' id='delivery_time_max' value='23:59' style='width: 100px;'> hrs";
               $('#del_after_hours_label').html(extra_fields);
           }
           else {$('#col_after_hours_label').html("After Hours");
                 $('#del_after_hours_label').html("After Hours"); }

 }
 function documents_switch(elm){
           var item = elm.dataset.group;
           var more = '';
           if(elm.checked && elm.id =="tax_invoice" || elm.checked && elm.id =="receipt" ) {
               if(elm.id =="tax_invoice") {$("#doc2").hide();}
               more += '<i class="wpcargo-col-md-12" style="font-size: 11px; display: block;"><b>Please select one option from bellow</b></i>';
               more += '<input style="height:15px; width:15px; margin-left: 20px;" type="checkbox" name="upload_'+item +'_box" id="upload_'+item +'" data-id="upload" data-group="'+item+'_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Upload your '+item +'</label>';
               more += '<input class="notvalidate" style="width:100%; margin-left: 50px; display:none;" type="file" id="upload_'+item +'_file" name="'+item +'_file">';
               more += '<br><input style="margin-left: 20px; height:15px; width:15px;" type="checkbox" name="bring_'+item +'_box" data-group="'+item+'_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Or bring it to our office</label>';
               more += '<br><input style="margin-left: 20px; height:15px; width:15px;" type="checkbox" name="sent_'+item +'_box" data-group="'+item+'_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Or sent it via whatsapp</label>';
               more += '<br><input style="margin-left: 20px; height:15px; width:15px;" type="checkbox" name="collect_'+item +'_box" data-group="'+item+'_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Or we will collect it from your supplier</label>';
               $('#'+item+'_more').html(more);
               $('#'+item+'_more').show();
           }
           else if(elm.checked && elm.id=="no_tax_invoice"){
                 $("#doc2 input:checkbox").prop('checked', false);
                 $("#doc2").show();
                 $("#tax_invoice_more").html(more);
                 $("#receipt_more").html(more);
               }
           else {
               if(elm.id=="no_tax_invoice") $("#doc2").hide();
               else $('#'+item+'_more').html("");
             }
          if(elm.checked && elm.id=="no_receipt" || elm.checked && elm.id=="receipt"){
               more += '<div style="margin-left: 20px;"><label style="font: bolder;">How much is the total cost for your parcels: </label>';
               more += '<input class="notvalidate" style="width:200px;" type="text" name="parcels_cost" id="parcels_cost" placeholder="M"> </div>';
               $('#receipt_more').html(more);
           }

        mutually_exclusive_checkboxes();

}
function toggle_fileupload(elm){
      if(elm.checked && elm.getAttribute("data-id")=="upload") $("#"+elm.id+"_file").show();
      else  $('#'+elm.parentNode.id+' input[type="file"]').hide();
}
function checkAgreeboxes(){
     var proceed = true;
    $('#agree_boxes').find(':checkbox').each(function(){
          if($(this).prop('checked')==false){
              proceed = false;
              $("#nextBtn").css("background","grey");
              }
    });
    if(proceed ==true) {  // now show next buttons
          //$("#nextBtn").show();
          $("#nextBtn").css("background","#187CC9");
          $("#nextBtn").attr("disabled", false);
         }
    else {  // now show next buttons
          //$("#nextBtn").hide();
          $("#nextBtn").css("background","grey");
          $("#nextBtn").attr("disabled", true);

         }
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

function toggle_services(){
  $("#service_type option").show();
  var org_details = $("#org_1_1 option:selected").data("moreinfo").split(",");
  var dest_details = $("#dest_1_1 option:selected").data("moreinfo").split(",");
  if(org_details[0]=="") { $("#service_type option[value='Depo to Depo']").hide(); $("#service_type option[value='Depo to Door']").hide(); }
  if(dest_details[0]=="") { $("#service_type option[value='Door to Depo']").hide(); $("#service_type option[value='Depo to Depo']").hide(); }
  if(org_details[1]=="") { $("#service_type option[value='Door to Depo']").hide(); $("#service_type option[value='Door to Door']").hide(); }
  if(dest_details[2]=="") { $("#service_type option[value='Depo to Door']").hide(); $("#service_type option[value='Door to Door']").hide(); }
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
              if(elm.id=="org_1" || elm.id=="dest_1") $('#shipment-details #'+elm.id+'_1').html( data[0] );
                else if(elm.id=="org_1_1" || elm.id=="dest_1_1"){
                    $("#route_abrs").val($("#org_1_1 option:selected").data("value")+"-"+$("#dest_1_1 option:selected").data("value"));
                    $('#shipment-details #shipment_trip_id' ).html( data[0] );
                    var modes = data[1].split(",");
                    if(modes.includes('Road')) { $("#shipment-details  #transport_mode option[value='Road']").show();}
                    if(modes.includes("Air")) { $("#shipment-details  #transport_mode option[value='Air']").show();}
                    if(modes.includes("Ocean")) { $("#shipment-details  #transport_mode option[value='Ocean']").show();}
                    elm.nextElementSibling.style.display = (elm.value=="Other") ? "Block": "None";
                    toggle_services();
                }
              $('.wpcargo-loading').hide();
             },
            error: function(errorThrown){
                $('.wpcargo-loading').hide();
                alert('<p>Error retrieving data. Please try again.</p>');
            }

        });
}

function close_modal(){
     $('#myModal').hide();
}
