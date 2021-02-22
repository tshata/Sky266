<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<style>

#quote_div .view{
  border: solid 2px #000000;
}
#quote_div .view >tbody{
  border: solid 1px #000000;
}
#quote_div .view >thead{
  border: solid 1 #000000;
}
#quote_div .view >tbody td input{
  width: 65%;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

a:link:after, a:visited:after {
  content: "";
}
.noprint {
  display: none !important;
}
a:link:after, a:visited:after {
  display: none;
  content: "";
}

</style>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <form id="modal_form" class="modal-content">
    <div class="modal-header">
      <span class="close" onclick="close_this_modal()">&times;</span>
      <h2>Modal Header</h2>
    </div>
    <div class="modal-body">
       <?php   $shipment_id = $shipment_detail->ID;   ?>
        <p style="background: #D3F8D3;" id="modalmsg"></p>
        <div class="wpc-mp-wrap" id="packages_div" style="display: none">
        	<table id="wpcargo-package-table" class="wpc-multiple-package wpc-repeater">
        		<thead>
        			<tr>
                        <th style="width: 18%;"><?php echo "Piece Type"; ?></th>
                        <th style="width: 9%;"><?php echo "Qty."; ?></th>
                        <th class="no-border-left no-border-right no-border-top no-border-bottom" style="width: 2%;"></th>
                        <th style="width: 11%;"><?php echo "Actual Weight (kg)"; ?></th>
                        <th class="no-border-left no-border-right no-border-top no-border-bottom" style="width: 1px;"></th>
                        <th style="width: 11%;"><?php echo "Cubic Metres (cbm)"; ?></th>
                        <th class="no-border-left no-border-right no-border-top no-border-bottom" style="width: 1px;"></th>
                        <th style="width: 9%;"><?php echo "Length (cm)"; ?></th>
                        <th style="width: 9%;"><?php echo "Width (cm)"; ?></th>
                        <th style="width: 9%;"><?php echo "Height (cm)"; ?></th>
                        <th class="no-border-left no-border-right no-border-top no-border-bottom" style="width: 1px;"></th>
                        <th class="no-border-left no-border-right no-border-top no-border-bottom" style="width: 9%;">&nbsp;</th>
                        <th class="no-border-left no-border-right no-border-top no-border-bottom" style="width: 4%;">&nbsp;</th>
        			</tr>
        		</thead>
        		<tbody data-repeater-list="<?php echo WPCARGO_PACKAGE_POSTMETA; ?>">
        			<?php if(!empty(wpcargo_get_package_data( $shipment_id ))): ?>
        				<?php foreach ( wpcargo_get_package_data( $shipment_id ) as $data_key => $data_value): ?>
        				<tr data-repeater-item class="wpc-mp-tr">
          					<?php $i=1;  foreach ( wpcargo_package_fields() as $field_key => $field_value): ?>
          						<?php
          						if( in_array( $field_key, wpcargo_package_dim_meta() ) && !wpcargo_package_settings()->dim_unit_enable ){
          							continue;
          						}
          						?>
                                 <?php echo ($i==3 ||$i==4||$i==5||$i==8)? '<td></td>' :'';  ?>
          						<td>
          							<?php
          							$package_data = array_key_exists( $field_key, $data_value ) ? $data_value[$field_key] : '' ;
          							$package_data = is_array( $package_data ) ? implode(',', $package_data ) : $package_data;
          							echo wpcargo_field_generator( $field_value, $field_key, $package_data );
          							?>
          						</td>
          					<?php $i++;  endforeach; ?>
          					<td style="line-height: 16px;"> <center>
                                  <a style="color: red; cursor: pointer;" data-repeater-delete> <?php esc_html_e('Del','wpcargo'); ?></a>
                              </center></td>
                		 </tr>
        				<?php endforeach; ?>
        			<?php else: ?>
        				<tr data-repeater-item class="wpc-mp-tr">
        					<?php $i=1;  foreach ( wpcargo_package_fields() as $field_key => $field_value): ?>
        						<?php
        						if( in_array( $field_key, wpcargo_package_dim_meta() ) && !wpcargo_package_settings()->dim_unit_enable ){
        							continue;
        						}
        						?>
                            <?php echo ($i==3 ||$i==4||$i==5||$i==8)? '<td></td>' :'';  ?>
        					<td>
        						<?php echo wpcargo_field_generator( $field_value, $field_key ); ?>
        					</td>
        					<?php $i++;  endforeach; ?>
        					<td style="line-height: 16px;"> <center>
                                <a style="color: red; cursor: pointer;" data-repeater-delete> <?php esc_html_e('Del','wpcargo'); ?></a>
                            </center></td>
        				</tr>
        			<?php endif; ?>
        		</tbody>
        		<tfoot>
        			<?php do_action('wpcargo_after_package_table_row', $shipment_detail); ?>
        			<tr class="wpc-computation">
        				<td colspan="6"><input data-repeater-create type="button" class="wpc-add" value="<?php esc_html_e('Add Package','wpcargo'); ?>"/></td>
        			</tr>
        		</tfoot>
      	</table>
           <?php do_action('wpcargo_after_package_totals', $shipment_detail ); ?>
        <br><br>
        </div>
        <div class="wpc-mp-wrap" id="quote_div" style="display: none; padding: 20px;">
        </div>
        <div class="wpc-mp-wrap" id="state_div" style="display: none; padding: 20px;">
        <?php
              $shipment_status = wpcargo_get_postmeta($shipment_id, 'wpcargo_status' );
        	  if( !empty( $shipment_status ) ){
                   echo "<p> Current Shipment Status:&nbsp;<b>".$shipment_status."</b><br></p>";
                    echo '<p><label>Select New State: &nbsp;</label>
        			     <select name="wpcargo_status" onchange="status_form(this)">';
        				echo '<option value="">'.esc_html__('-- All Status --', 'wpcargo').'</option>';
                            $status_breakdown = status_breakdown($shipment_status);
                            $wpcargo_shipments_update = maybe_unserialize( get_post_meta( $shipment_id, 'wpcargo_shipments_update', true ) );
                            foreach($status_breakdown as $sub_status){
                               if( !in_array_r($sub_status,$wpcargo_shipments_update) && $sub_status!="Invoicing" && $sub_status!="Finance Cleared" ) echo '<option value="'.trim($sub_status).'" >'.trim($sub_status).'</option>';
                             }
        			echo '</select></p>';
        		}
             ?>
             <div id="state_div_extra"></div>
        </div>
    </div>
    <div class="modal-footer">   <p id="dd"></p>
         <input type="hidden" name="action" id="action" value="shipment_modal_action">
         <input type="hidden" name="current_form" id="current_form" value="">
         <input type="hidden" id="package-weight" name="package-weight" value="<?php echo wpcargo_get_postmeta($shipment_id, 'package-weight', true); ?>">
         <input type="hidden" id="total_package-cbm" name="total_package-cbm" value="<?php echo wpcargo_get_postmeta($shipment_id, 'total_package-cbm', true); ?>">
         <input type="hidden" id="transport_mode" name="transport_mode" value="<?php echo wpcargo_get_postmeta($shipment_id, 'transport_mode', true); ?>">
         <input type="hidden" id="item_type" name="item_type" value="<?php echo (wpcargo_get_postmeta($shipment_id, 'item_type', true))? wpcargo_get_postmeta($shipment_id, 'item_type', true) : 'kg'; ?>">
         <input type="hidden" name="post_id" id="post_id" value="<?php echo $shipment_id; ?>">
         <a class="button" id="back_btn" onclick="close_this_modal()">Close</a>
         <a class="button" id="print_btn" style="float:right;" type="button" onclick="receipt_print()"><span class="fa fa-print"></span>Print</a>
         <a class="button" id="submit_btn" style="float:right;" onclick="save_modal(this)">Save changes</a>
         <a style="float: right" class="button" id="invoice_btn" onclick="save_invoice(this)">Create Invoice</a>

    </div>
  </form>

</div>
<script>
  jQuery(document).ready(function ($) {
	'use strict';
	$('#wpcargo-package-table').repeater({
		show: function () {
			$(this).slideDown();
            $(this).find('input').attr("disabled", false);
		},
		hide: function (deleteElement) {
			if( confirm('Are you sure you want to delete this element?') ) {
				$(this).slideUp(deleteElement);
			}
		}
	});
    $('body').append('<div class="wpcargo-loading">Loading...</div>');
    $('.wpcargo-loading').hide();
    address_fields_toggle(document.getElementById("wpcargo_shipper_address_type"));
    colletion_toggle(document.getElementById("service_type"));
    if($("#tax_invoice").prop('checked')==true){ documents_switch(document.getElementById("tax_invoice"));}
    else if($("#no_tax_invoice").prop('checked')==true){
        $('#tax_invoice_yes_more').hide();
        if($("#receipt").prop('checked')==true){ documents_switch(document.getElementById("receipt"));}
        else if($("#no_receipt").prop('checked')==true){ documents_switch(document.getElementById("no_receipt"));}
      }
    else {
       $('#tax_invoice_yes_more').hide();
       $("#doc2").hide();
      }

  });

  function close_this_modal(){
     //$('#myModal').hide();
     window.location['reload']();
  }

  function re_calculate(elm){
    var currentRow = $(elm).closest("tr");
    var itemtype = $(elm).closest("tr").attr("data-type");
    var old_val = parseFloat(currentRow.find("td:eq(4)").text().replace('M', '').replace(':', '').replace(',', '').replace('X', '').replace('-', '')); old_val = (old_val !="")? old_val : 0;
    var new_val = (elm.value!="") ? parseFloat(currentRow.find("td:eq(2)").find("#price").val())*parseFloat(currentRow.find("td:eq(3)").find("#qty").val()) : 0;
    currentRow.find("td:eq(2)").find("span").text((currentRow.find("td:eq(2)").find("#price").val()!="") ? formatMoney(parseFloat(currentRow.find("td:eq(2)").find("#price").val()), 2, ".", ",") : 0);
    currentRow.find("td:eq(3)").find("span").text((currentRow.find("td:eq(3)").find("#qty").val()!="") ? parseFloat(currentRow.find("td:eq(3)").find("#qty").val()) : 0);
    var total = parseFloat ($("#total").text().replace(',', ''));
    var op_sign = (itemtype=="Expenditure") ? "-": "";
    currentRow.find("td:eq(4)").html(op_sign+'M '+formatMoney(new_val, 2, ".", ",")+'<a style="color:red" href="#" onclick="remove_row(this)"> X</a>'); //update row total with new value
    var newtotal = (itemtype=="Expenditure") ? formatMoney(total-new_val+old_val, 2, ".", ",") : formatMoney(total+new_val-old_val, 2, ".", ",");
    $("#total").text(newtotal);  //update quote total with new total value
  }
  function add_row(){
     var label = $("#item_select").val();
     if(label=="Select One") return false;
     var key = $("#item_select :selected").attr("data-key");
     var unit = $("#item_select :selected").attr("data-unit");
     var itemtype = $("#item_select :selected").attr("data-type");
     var value = ($("#item_select :selected").attr("data-value")!="") ? $("#item_select :selected").attr("data-value") : 0;
     var op_sign = (itemtype=="Expenditure") ? "-": "";
     var newtotal = (itemtype=="Expenditure") ? formatMoney(parseFloat($("#total").text().replace(',', ''))-parseFloat(value), 2, ".", ",") : formatMoney(parseFloat($("#total").text().replace(',', ''))+parseFloat(value), 2, ".", ",");
     $("#total").text(newtotal);
     var newrow = '<tr style="background:#E8E8E8;" data-type="'+itemtype+'"><td>'+label+'</td><td>'+unit+'</td><td>'+op_sign+'M <input data-value="'+value+'" id="price" name="price" value="'+value+'" onkeyup="re_calculate(this)" ></td><td><input id="qty" name="qty" onkeyup="re_calculate(this)" data-value="1" value="1"></td><td>'+op_sign+'M '+value+'<a style="color:red" href="#" onclick="remove_row(this)"> X</a></td></tr>';
     $('#quote_div tbody tr:last').after(newrow);
  }
 function toggle_payment_fields(elm){
    var selected_option = $(elm).val();
    if(selected_option== "Account"){
      $("#payment_reference_row").hide();
      $("#payment_amount_row").hide();
    }
    else{
      $("#payment_reference_row").show();
      $("#payment_amount_row").show();
    }
  }
  function edit_quote(){
     $('#submit_btn').show();
     $('#quote_div #onedit').show();
     $('#quote_div table input').show();
     $('#quote_div table span').hide();
     $('#quote_div table a').show();
     $('#invoice_btn').hide();
     $('#print_btn').hide();
  }
  function remove_row(elm){
     var r = confirm("Are you sure you want to delete this?");
      if (r == true) {
        $(elm).closest("tr").fadeOut('slow', function(){
             this.remove();
        });
        var row_total = parseFloat($(elm).closest("tr").find("td:eq(4)").text().replace('M', '').replace(':', '').replace(',', '').replace('X', '').replace('-', ''));
        var itemtype = $(elm).closest("tr").attr("data-type");
        var total = (itemtype=="Expenditure") ? formatMoney(parseFloat($("#total").text().replace(',', ''))+row_total, 2, ".", ",") : formatMoney(parseFloat($("#total").text().replace(',', ''))-row_total, 2, ".", ",");
        $("#total").text(total);
      }
  }
  function wpcargo_revise_packages(){
     $('#packages_div').show();
     $('#submit_btn').show();
     $('#packages_div #package-weight-info').show();
     $('#invoice_btn').hide();
     $('#state_div').hide();
     $('#quote_div').hide();
     $('#print_btn').hide();
     $('.modal-header h2').text("Packages Revision");
     $('#current_form').val("revise_packages");
     $('.modal-content').css('width','70%');
     $('#myModal').show();
  }
  function wpcargo_state_update(){
     $('#state_div').show();
     $('#quote_div').hide();
     $('#invoice_btn').hide();
     $('#submit_btn').show();
     $('#packages_div').hide();
     $('#print_btn').hide();
     $('.modal-header h2').text("Update Shipment State");
     $('#current_form').val("state_update");
     $('.modal-content').css({ "width" : "30%", "min-width" : "400px"});
     $('#myModal').show();
  }
  function save_modal(elm){
     $('.wpcargo-loading').show();
     var weight =  (parseFloat($("#packages_div #package_volumetric").html()) > parseFloat($("#packages_div #package_actual_weight").html())) ? parseFloat($("#packages_div #package_volumetric").html()) : parseFloat($("#packages_div #package_actual_weight").html());
     $("#package-weight").val(weight);
     $("#total_package-cbm").val(parseFloat($("#packages_div #package_cbm").html()));
     //return false;
     var data = $('#modal_form').serializeArray();
     if($('#current_form').val()=="revise_quote") {
       var table_data = {};
       $("#quote_div table tbody tr").each(function () {
                    var key_data = {};
                    var self = $(this);
                    var key = self.find("td:eq(0)").text().trim().replace(/ /g,'').toLowerCase();
                    key_data['unit'] = self.find("td:eq(1)").text().replace('M', '').replace(':', '').replace(',', '').replace('X', '').trim();
                    key_data['price'] = self.find("td:eq(2) #price").val().replace('M', '').replace(':', '').replace(',', '').replace('X', '').trim();
                    key_data['qty'] = self.find("td:eq(3) #qty").val().replace('M', '').replace(':', '').replace(',', '').replace('X', '').trim();
                    key_data['total'] = self.find("td:eq(4)").text().replace('M', '').replace(':', '').replace(',', '').replace('-', '').replace('X', '').trim();
                    table_data[key]= key_data;
                });
        data = {
           action    : $('#action').val(),
           current_form : $('#current_form').val(),
           post_id : $('#post_id').val(),
           table_data : table_data
        };
    }
    $.ajax({
            url: wpcargoAJAXHandler.ajax_url,
            type:"POST",
            data: data,
            success:function(data) {
                if($('#current_form').val()=="revise_quote") {
                    $('#modalmsg').html(data).css('display','block');
                    setTimeout(function() {  $('#modalmsg').fadeOut('slow'); }, 3000);
                    $("#print-shipper-info #quote1").show();
                    $("#print-shipper-info #quote2").text($("#total").text());
                    $('#submit_btn').hide();
                    $('#quote_div #onedit').hide();
                    $('#quote_div table input').hide();
                    $('#quote_div table span').show();
                    $('#quote_div table a').hide();
                    if($('#current_form').val()=="revise_quote"){$('#invoice_btn').text("Save Invoice"); $('#invoice_btn').show(); $('#invoice_btn').text("Create Invoice"); }
                }
                else{
                   $('#msg').html(data).css('display','block');
                   $('#myModal').hide();
                   window.location['reload']();
                }
              $('.wpcargo-loading').hide();
            },
            error: function(errorThrown){
                $('.wpcargo-loading').hide();
                alert('Error excecuting task. Please try again.');
            }
        });
 }

function quotations(btn){
 var shipment_id = $(btn).attr("data-shipment_id");
 $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: {
              action    : 'quotations_list_action',
              shipment_id : shipment_id,
              quote1 : $(btn).attr("data-quote1"),
              quote2 : $(btn).attr("data-quote2"),
          },
          success:function(data) {
              $('#quote_div' ).html(data);
              $('.modal-header h2').text("Quotations");
              $('#quote_div').show();
              $('#state_div').hide();
              $('#submit_btn').hide();
              $('#invoice_btn').hide();
              $('#packages_div').hide();
              $('#print_btn').hide();
              $('#myModal').show();
              $('.wpcargo-loading').hide();
          },
          error: function(errorThrown){
              $( '#quote_div' ).html('Error retrieving data. Please try again.');
          }
      });
}
function quote_more(clicked_btn,post_id,option){
  $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: {
              action    : 'quote_more_action',
              option : option,
              post_id : post_id,
          },
          success:function(data) {
              $('#quote_div' ).html(data);
              $('#quote_div').show();
              $('#state_div').hide();
              $('#print_btn').show();
              $('#submit_btn').hide();
              $('#back_btn').attr("onclick","close_this_modal()");
              $('#back_btn').text("Close");
              if(option=="wpcargo_invoice" && clicked_btn.id=="new_invoice"){ $('#invoice_btn').show(); $('#invoice_btn').text("Create Invoice"); $('#print_btn').hide();}
              else $('#invoice_btn').hide();
              $('#packages_div').hide();
              if(option=="old_wpcargo_price_estimates") $('.modal-header h2').text("Initial Quotation");
              if(option=="wpcargo_price_estimates") $('.modal-header h2').text("Final Quotation");
              if(option=="wpcargo_invoice") $('.modal-header h2').text("Invoice");
              $('#current_form').val("revise_quote");
              $('.modal-content').css({ "width" : "794px"});
              $('#myModal').show();
              $('.wpcargo-loading').hide();
          },
          error: function(errorThrown){
              $( '#quote_div' ).html('Error retrieving data. Please try again.');
          }
      });
}
function edit_invoice(){
     $('#submit_btn').show();
     $('#submit_btn').attr("onclick","save_invoice()");
     $('#quote_div #onedit').show();
     $('#quote_div table input').show();
     $('#quote_div table span').hide();
     $('#quote_div table a').show();
     $('#invoice_btn').hide();
     $('#print_btn').hide();
  }
function save_invoice(){
   var r = confirm("Are you sure you want to save this invoice?");
   if (r == true) {
        $('.wpcargo-loading').show();
        var table_data = {};
       $("#quote_div table tbody tr").each(function () {
                    var key_data = {};
                    var self = $(this);
                    var key = self.find("td:eq(0)").text().trim().replace(/ /g,'').toLowerCase();
                    key_data['unit'] = self.find("td:eq(1)").text().replace('M', '').replace(':', '').replace(',', '').replace('X', '').trim();
                    key_data['price'] = self.find("td:eq(2) span").text().replace('M', '').replace(':', '').replace(',', '').replace('X', '').trim();
                    key_data['qty'] = self.find("td:eq(3) span").text().replace('M', '').replace(':', '').replace(',', '').replace('X', '').trim();
                    key_data['total'] = self.find("td:eq(4)").text().replace('M', '').replace(':', '').replace(',', '').replace('-', '').replace('X', '').trim();
                    table_data[key]= key_data;
                });
        data = {
           action    : $('#action').val(),
           current_form : "save_invoice",
           post_id : $('#post_id').val(),
           table_data : table_data
        };

       $.ajax({
            url: wpcargoAJAXHandler.ajax_url,
            type:"POST",
            data: data,
            success:function(data) {
                    $('.wpcargo-loading').hide();
                    $('#print_btn').show();
                    $("#print-shipper-info #invoice_inf").text($("#total").text());
                    amount_paid = (parseFloat($("#print-shipper-info #amount_paid_inf").text().replace(',', ''))).toFixed(2);
                    $("#print-shipper-info #amount_due").text(formatMoney(parseFloat($("#total").text().replace(',', ''))-amount_paid, 2, ".", ","));
                    quote_more($('#invoice'),$('#post_id').val(),'wpcargo_invoice');
                    //window.location['reload']();
            },
            error: function(errorThrown){
                $('.wpcargo-loading').hide();
                alert('Error excecuting task. Please try again.');
            }
        });
   }
}
function revised_online_payments(){
	     $("#quote_div #payment_display").hide();
	     $("#quote_div #list_view").hide();
	     $("#quote_div #payment_revision_form").show();
	     $('#submit_btn').attr("onclick","approve_payment()");
	     $('#submit_btn').text("Approve Payment");
	     $('#submit_btn').show();
	     $('#print_btn').hide();

}
function approve_payment(){
		//revised_online_payments();
   var payment_method = $('#payment_methodfd').val();
   var confirm_pay = confirm("Do you want to continue with payment approval?");
   if(confirm_pay){
    $('.wpcargo-loading').show();
    $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: {
              action    : 'approve_payment_action_callback',
              post_id : $('#post_id').val(),
							revised_amount : $('#payment_amountf').val(),
							revised_method: payment_method,
              payment_date : $('#payment_datef').val(),
              payment_reference : $('#payment_referencef').val(),
              received_from : $('#received_fromf').val()
          },
          success:function(data) {
               window.location['reload']();
          },
          error: function(errorThrown){
              //$('.wpcargo-loading').hide();
              alert('Error saving data. Please try again.');
          }
      });
   }
  else return false
}
function payment_more(post_id){
  $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: {
              action    : 'payment_more_action',
              post_id : post_id,
          },
          success:function(data) {
              $('#quote_div' ).html(data);
              $('.wpcargo-loading').hide();
              $('#quote_div').show();
              $('#state_div').hide();
              $('#submit_btn').hide();
              $('#invoice_btn').hide();
              $('#print_btn').hide();
              $('#back_btn').attr("onclick","close_this_modal()");
              $('#back_btn').text("Close");
              $('#packages_div').hide();
              $('.modal-header h2').text("Payments");
              $('.modal-content').css({ "width" : "40%", "min-width" : "500px"});
              $('#myModal').show();
          },
          error: function(errorThrown){
              $( '#quote_div' ).html('Error retrieving data. Please try again.');
          }
      });
}
function statement(post_id){
  $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: {
              action    : 'statement_more_action',
              post_id : post_id,
          },
          success:function(data) {
              $('#quote_div' ).html(data);
              $('.wpcargo-loading').hide();
              $('#quote_div').show();
              $('#print_btn').show();
              $('#state_div').hide();
              $('#submit_btn').hide();
              $('#invoice_btn').hide();
              $('#back_btn').attr("onclick","close_this_modal()");
              $('#back_btn').text("Close");
              $('#packages_div').hide();
              $('.modal-header h2').text("Statement");
              $('.modal-content').css({ "width" : "794px"});
              $('#myModal').show();
          },
          error: function(errorThrown){
              $( '#quote_div' ).html('Error retrieving data. Please try again.');
          }
      });
}
function make_payment(){
     $("#quote_div #payment_display").hide();
     $("#quote_div #list_view").hide();
     $("#quote_div #payment_form").show();
     $('#submit_btn').attr("onclick","save_payment()");
     $('#submit_btn').text("Save Payment");
     $('#submit_btn').show();
     $('#print_btn').hide();
}
function payment_singleview(btn){
  var shipment_id = btn.getAttribute('data-shipment_id');
  $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: {
              action    : 'payment_singleview_action',
              shipment_id : shipment_id,
              row_id : btn.getAttribute('data-row_id'),
          },
          success:function(data) {
              $('#quote_div' ).html(data);
              $('.wpcargo-loading').hide();
              $('#quote_div').show();
              $('#state_div').hide();
              $('#submit_btn').hide();
              $('#invoice_btn').hide();
              $('#back_btn').attr("onclick","payment_more("+shipment_id+")");
              $('#back_btn').text("Back");
              $('#packages_div').hide();
              $('#print_btn').show();
              $('.modal-header h2').text("Receipt Details");
              $('.modal-content').css({ "width" : "559px", "height" : "100%;"});
              $('#myModal').show();
          },
          error: function(errorThrown){
              $( '#quote_div' ).html('Error retrieving data. Please try again.');
          }
      });
}
function save_payment(){
  var payment_method = $('#payment_method').val();
  if($('#payment_date').val() ==""){ $('#payment_form #msg_box').text("Date cannot be empty"); return false;}
  else if($('#payment_method').val() ==""){ $('#payment_form #msg_box').text("Select Payment Method"); return false;}
  else if($('#payment_reference').val() =="" && payment_method!="Account"){ $('#payment_form #msg_box').text("Payment reference cannot be empty"); return false;}
  else if($('#payment_amount').val() <= 0 && payment_method!="Account"){ $('#payment_form #msg_box').text("Payment Amount cannot be less than or equal to 0"); return false;}
  var confirm_pay = confirm("Are you sure you want to proceed with this transaction?");
  if(confirm_pay){
    $('.wpcargo-loading').show();
    $.ajax({
          url: wpcargoAJAXHandler.ajax_url,
          type:"POST",
          data: {
              action    : 'save_payment_action',
              post_id : $('#post_id').val(),
              payment_method : payment_method,
              payment_date : $('#payment_date').val(),
              payment_reference : $('#payment_reference').val(),
              received_from : $('#received_from').val(),
              payment_amount : $('#payment_amount').val()
          },
          success:function(data) {
              $('.wpcargo-loading').hide();
              $('#quote_div').show();
              $('#state_div').hide();
              $('#submit_btn').hide();
              $('#invoice_btn').hide();
              $('#packages_div').hide();
              $('.modal-header h2').text("Receipt Details");
              $('.modal-content').css({ "width" : "40%", "min-width" : "500px"});
              //used to auto update finances
              amount_paid = (parseFloat($("#print-shipper-info #amount_paid_inf").text().replace(',', ''))+parseFloat($('#payment_form #payment_amount').val().replace('M', '').replace(',', ''))).toFixed(2);
              $("#print-shipper-info #total").text(parseFloat(amount_paid).toFixed(2));
              $("#print-shipper-info #amount_paid_inf").text(formatMoney(parseFloat(amount_paid), 2, ".", ","));
              $("#print-shipper-info #amount_due").text(formatMoney((parseFloat($("#print-shipper-info #invoice_inf").text().replace(',', ''))-amount_paid), 2, ".", ","));
              $('#quote_div').html(data);
              $('#print_btn').show();

               //window.location['reload']();
          },
          error: function(errorThrown){
              $('.wpcargo-loading').hide();
              $( '#quote_div' ).html('Error saving data. Please try again.');
          }
      });
   }
  else return false;
}
function formatMoney(number, decPlaces, decSep, thouSep) {
      decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
      decSep = typeof decSep === "undefined" ? "." : decSep;
      thouSep = typeof thouSep === "undefined" ? "," : thouSep;
      var sign = number < 0 ? "-" : "";
      var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
      var j = (j = i.length) > 3 ? j % 3 : 0;
      return sign +
      	(j ? i.substr(0, j) + thouSep : "") +
      	i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
      	(decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}
function status_form(elm){
   $("#state_div #state_div_extra").html("");
   if(elm.value=="Payment Approval"){ $('.wpcargo-loading').show(); payment_more($('#post_id').val()); }
   else if(elm.value=="Invoicing") { $('.wpcargo-loading').show(); quote_more(elm,$('#post_id').val(),"wpcargo_price_estimates");}
   else if(elm.value=="Collection") { $("#state_div #state_div_extra").html("<label>"+elm.value+" Status:  &nbsp;</label><select name='remarks' value=''><option value='"+elm.value+" Successful'>Successful</option><option value='"+elm.value+" Failed'>Failed</option></select>"); }
   else if(elm.value=="Delivery") { $("#state_div #state_div_extra").html("<label>"+elm.value+" Status:  &nbsp;</label><select name='remarks' value=''><option value='"+elm.value+" Successful'>Successful</option><option value='"+elm.value+" Failed'>Failed</option></select>"); }
}

function receipt_print() {
  /*var printContents = document.getElementById("receipt_display").innerHTML;
  var originalContents = document.body.innerHTML;
  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
  location.reload(true);*/
  var tab = document.getElementById('receipt_display');
  var style = "<style>";
  style = style + "@page{margin:0;} ";
  style = style + ".black_bg{ background:black; padding:5px; color:white;} ";
  style = style + "#receipt_display table tbody .row td, table thead .row td{ border-right: 2px solid; border-bottom: 2px solid; }";
  style = style + "#receipt_display table tbody .row td:nth-child(5) , table thead .row td:nth-child(5){ border-right: none; }";
  style = style + "</style>";
  var win = window.open('','','height=994,width=859');
  win.document.write(style);
  win.document.write('<html><body><div style="margin:30px;">');
  win.document.write(tab.innerHTML);
  win.document.write('</div></body></html>');
  win.document.close();
  win.print();
  //win.close();
}


</script>
