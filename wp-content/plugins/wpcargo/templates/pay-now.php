 <style>
#payment_details ::placeholder {/* Chrome, Firefox, Opera, Safari 10.1+ */
  color: black;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color: black;
}

::-ms-input-placeholder { /* Microsoft Edge */
  color: black;
}
#payment_items {
    width: 700px;
}
select{
    margin-top:-.3em;
    float:right;
    width: 70%;
    background:white;
    color: black;
}

</style>
<?php

  $shipment_id = $_POST["post_ID"];
  $wpcargo_price_estimates = $_POST["wpcargo_price_estimates"];
  $wpcargo_price_estimates = stripslashes($wpcargo_price_estimates);
?>


 <div id="payment_items" class="shipping-form"> <!-- form step tree -->

       <div class="wpcargo-row" style="padding:10px;">
        <div class="wpcargo-col-md-d14" style="background:dimgray; padding:1em;font-size:1.2em;color:white;width:50%;">
         <h5><strong>ESTIMATE</strong></h5>
        <!--p class="wpcargo-label" style="margin-bottom: 0px;"><strong><?php apply_filters( 'wpc_multiple_package_header', esc_html_e( 'Price Estimates', 'wpcargo' ) ); ?></strong></p-->
        <div id="price" style="font-size: 12px;">
          <table  style="width:85%;">
            <?php
            $settings_items = unserialize(get_settings_items()->meta_data);
            $wpcargo_price_estimates = unserialize($wpcargo_price_estimates);
            $total = 0;
            foreach($wpcargo_price_estimates AS $key => $row){
                $op_sign = ($settings_items[$key]["item_type"]=="Expenditure") ? "-": "";
                 $item_name = ($key=="freight")? "Freight" : $settings_items[$key]["item_name"];
                 echo '<tr style="border:solid 1px;">
                         <td style="padding:5px;">'.$item_name.'</td>
                         <td style="padding:5px;">'.$op_sign.'M '.number_format((float)$row["total"], 2, '.', ',').'</td>
                       <tr>';

                  $total = ($op_sign=="-")? $total-(float)$row["total"] : $total+(float)$row["total"];


               $total_price+=$price_estimates_arr[$key]["total"];
            }// finally display table
            echo '<tr style="border:solid 1px;"> <td style="padding:5px;"><b>Total</b></td> <td style="padding:5px;"><b>M '.number_format((float)$total, 2, '.', ',').'</b></td> <tr>';
           ?>
           </table>

        <div id="price_tables" class="wpcargo-row">
      </div>
        </div>
    </div>
       <div class="wpcargo-col-md-6" style="padding-left: 4px;">
        <div style="margin-left:.3em;" id="payment_boxes">

             <?php
               $route_prices_results = wpc_get_route_prices($_POST["wpcargo_origin_field"],$_POST["wpcargo_origin_city_field"],$_POST["wpcargo_destination"],$_POST["wpcargo_destination_city"]);
               $trans_mode = $_POST["transport_mode"];
               $route_item_prices = ($trans_mode=="Ocean")? unserialize($route_prices_results->ocean_item_costs) : (($trans_mode=="Air")? unserialize($route_prices_results->air_item_costs): unserialize($route_prices_results->road_item_costs) );
               $bookingfee = $route_item_prices["booking_fee"];

             ?>


               <p>To Activate this Booking, Please  pay a minimum amount of <span ><strong>M <?php echo number_format((float)$bookingfee, 2, '.', ','); ?></strong></span></p><br>
               <input type="hidden" id="bookingFee" value="<?php echo number_format((float)$bookingfee, 2, '.', ','); ?>" />
              <input style="width: 15px; height: 15px;" type='checkbox' name="mobileMpesa"  id="mobileMpesa" onclick="payments_toggle(this)" data-group='payments'><span >Mpesa</span><br>
              <input style="width: 15px; height: 15px;" type='checkbox' name="mobileEco"  id="mobileEco" onclick="payments_toggle(this)" data-group='payments'><span >Ecocash </span><br>
              <input style="width: 15px; height: 15px;" type='checkbox' name="bank" id="bank" onclick="payments_toggle(this)" data-group='payments'><span >Bank EFT/Deposit</span><br>
              <input style="width: 15px; height: 15px;" type='checkbox' name="cash" id="other" onclick="payments_toggle(this)" data-group='payments'><span >Other</span><br><br><br>
             <!-- <label><input style="width: 15px; height: 15px;" type='checkbox' name="bank" id="bank" onclick="payments_toggle(this)" data-group='payments'><label >Card</label></label><br>-->
            </div>
             <p></p>

         <input type="hidden" id="post_id" value="<?php echo sanitize_text_field( $_SESSION["current_id"]); ?>">
             <br>
            <br><br>
            <button style="float:right;" type="button" id="nextBtn" >Pay</button>
            <button style="float:right;margin-right:.4em;" type="button" id="close" >Close</button>
        </div>
        <br>
   <p id="info" style="<?php echo (empty($wpcargo_payment_history))? 'display:none;': ''; ?>"><?php echo "You have paid M".number_format((float)$amount_paid, 2, '.', '')." of the Total amount of M".number_format((float)$total_price, 2, '.', '')." Remaining Balance is M".number_format((float)($total_price-$amount_paid), 2, '.', '');?>.</p>
   <?php wpcargo_include_template( 'pay_methods', $shipment );
         //do_action('wpcargo_after_package_details', $shipment );
   ?>
    </div>
  </div>

<script>
   function payments_toggle(btn){
        var selected_trip = ($("#trip_1").prop('checked')==true) ? $('#trip_1_div').html() : $('#trip_2_div').html();
        $("#price").html(selected_trip);
          $("#payment_details").hide();
          $("#payment_boxes input").prop("checked",false);
          $("#payment_details input").val("");
          $("#payment_details").show();
          $("#"+btn.id).prop("checked",true);
          $("#nextBtn").css("background","#187CC9");
          $("#nextBtn").attr("disabled", false);
          if(btn.id == "mobileMpesa"){
              $("#payment_details .wpcargo-label #heading").text("Mpesa Payment Details");
              $("#payment_details #company_details").text("PAY TO: 57555325 (Ntsane Pheko)");
              $("#payment_details #payment_idenTifier").text("Phone Number Used:");
              $("#payment_details #payment_identifier").attr("placeholder","Phone Number Used");
              $("#payment_details #payment_reference").attr("placeholder","Reference");
              $("#payment_details #payment_date").attr("placeholder","Date of Payment");
              $("#payment_details #payment_amount").attr("placeholder","Amount Paid");
              $('#payment_items #payment_method').val("Mobile Money")
          }
          else if(btn.id == "mobileEco"){
              $("#payment_details .wpcargo-label #heading").text("Ecocash Payment Details");
              $("#payment_details #company_details").text("PAY TO: 62555325 (Ntsane Pheko)");
              $("#payment_details #payment_idenTifier").text("Phone Number Used:");
              $("#payment_details #payment_identifier").attr("placeholder","Phone Number Used");
              $("#payment_details #payment_reference").attr("placeholder","Reference");
              $("#payment_details #payment_date").attr("placeholder","Date of Payment");
              $("#payment_details #payment_amount").attr("placeholder","Amount Paid");
              $('#payment_items #payment_method').val("Mobile Money")
          }
          else if(btn.id == "other"){
              $("#payment_details .wpcargo-label #heading").text("Other Payment Details");
              $("#payment_details #payment_idenTifier").text("Payment Method:");
              $("#payment_details #company_details").text("State method used for payment");
              $("#payment_details #payment_identifier").attr("placeholder","Payment Method");
              $("#payment_details #payment_reference").attr("placeholder","Receipt Number");
              $("#payment_details #payment_date").attr("placeholder","Date of Payment");
              $("#payment_details #payment_amount").attr("placeholder","Amount Paid");
              $('#payment_items #payment_method').val("Cash");
          }
          else if(btn.id == "bank"){
              document.getElementById("bankSelect").style.display = "block";
              document.getElementById("otherMethods").style.display = "none";
              var bankNames = "PAY TO:  STANDARD LESOTHO BANK: 9080007532411" + "<br/>" + "FIRST NATIONAL BANK: "+ "<br/>" +"\tNEDBANK:";
              $("#payment_details .wpcargo-label #heading").text("Bank Payment Details");
              $("#payment_details #company_details").html(bankNames);
              $("#payment_details #payment_idenTifier").text("Bank Name:");
              //$("#payment_details #payment_identifier").attr("placeholder","Bank Name");
              $("#payment_details #payment_reference").attr("placeholder","Reference");
              $("#payment_details #payment_date").attr("placeholder","Date of Payment");
              $("#payment_details #payment_amount").attr("placeholder","Amount Paid");
              $('#payment_items #payment_method').val("Bank");
              
          }
   }
   $(document).ready(function () {
       mutually_exclusive_checkboxes();
    });
    document.getElementById("close").onclick = function(){
            window.location.href = "Home";
        };

 </script>
