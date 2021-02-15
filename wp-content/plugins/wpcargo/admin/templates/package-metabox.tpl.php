<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
 ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: white;
  opacity: 1; /* Firefox */
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color: white;
}

::-ms-input-placeholder { /* Microsoft Edge */
  color: white;
}
/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>


<div class="wpc-mp-wrap">
  <?php if(empty(wpcargo_get_package_data( $shipment->ID ))): ?>
    <br>
     <div>
       <label class="form-label" for="label">Total Weight Estimate (kg) </label> <br style="line-height: 2px;">
       <input id="wpcargo_package_weight" placeholder="--(kg)--" name="wpcargo_package_weight"  style="width:15%;margin-left:.7em;" type="number"  value="<?php echo wpcargo_get_postmeta($shipment->ID, 'wpcargo_package_weight', true); ?>"/>
     </div>
     <br>
     <div>
       <label class="form-label" for="label">Total Volume Estimate (cbm)</label> <br style="line-height: 2px;">
       <input id="wpcargo_package_cbm" placeholder="--(cbm)--" name="wpcargo_package_cbm"  style="width:15%;margin-left:.7em;" type="number" value="<?php echo wpcargo_get_postmeta($shipment->ID, 'wpcargo_package_cbm', true); ?>" />
     </div>
     <br><br>
        <p><strong>NOTE:</strong>
            <i>The weight estimate is very important for estimating  your courer charge </i>
        </p>
  <?php else : ?>
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
                <th style="width: 10%;"><?php echo "Length (cm)"; ?></th>
                <th style="width: 10%;"><?php echo "Width (cm)"; ?></th>
                <th style="width: 10%;"><?php echo "Height (cm)"; ?></th>
                <th class="no-border-left no-border-right no-border-top no-border-bottom" style="width: 1px;"></th>
                <th class="no-border-left no-border-right no-border-top no-border-bottom" style="width: 11%;">&nbsp;</th>
                <th class="no-border-left no-border-right no-border-top no-border-bottom" style="width: 7%;">&nbsp;</th>
			</tr>
		</thead>
		<tbody data-repeater-list="<?php echo WPCARGO_PACKAGE_POSTMETA; ?>">
			<?php if(!empty(wpcargo_get_package_data( $shipment->ID ))): ?>
				<?php foreach ( wpcargo_get_package_data( $shipment->ID ) as $data_key => $data_value): ?>
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
			<?php do_action('wpcargo_after_package_table_row', $shipment); ?>
			<tr class="wpc-computation">
				<td colspan="6"><input data-repeater-create type="button" class="wpc-add" value="<?php esc_html_e('Add Package','wpcargo'); ?>"/></td>
			</tr>
		</tfoot>
	</table>
    <?php do_action('wpcargo_after_package_totals', $shipment ); ?>
    <input type="hidden" id="package-weight" name="package-weight" value="<?php echo wpcargo_get_postmeta($shipment->ID, 'package-weight', true); ?>">
    <input type="hidden" id="total_package-cbm" name="total_package-cbm" value="<?php echo wpcargo_get_postmeta($shipment->ID, 'total_package-cbm', true); ?>">
    <input type="hidden" id="item_type" name="item_type" value="<?php echo (wpcargo_get_postmeta($shipment->ID, 'item_type', true))? wpcargo_get_postmeta($shipment->ID, 'item_type', true) : 'kg'; ?>">
   <?php endif; ?>
</div>
<script>
jQuery(document).ready(function ($) {
	'use strict';
   $('#wpcargo-package-table').repeater({
		show: function () {
			$(this).slideDown();
            $("#wpcargo-package-table select").on("change", function(event) {
              toggle_package_change($(this).val());
            } );
            $(this).find('input').attr("disabled", false); //show all row input fields
		},
		hide: function (deleteElement) {
			if( confirm('<?php esc_html_e( 'Are you sure you want to delete this element?', 'wpcargo' ); ?>') ) {
				$(this).slideUp(deleteElement);
			}
		}
	});
    ///////////////////////
    $("#wpcargo-package-table select").on("change", function(event) {
           toggle_package_change($(this).val());
        } );


    address_fields_toggle(document.getElementById("wpcargo_shipper_address_type"));   //toggles collection fields
    address_fields_toggle(document.getElementById("wpcargo_delivery_address_type"));  //toggles delivery fields
    toggle_services(); //toggles select service field based on origin/destination selected
    collection_toggle(document.getElementById("service_type"));  //toggles other services fields based on service type
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
    //find the biggest parcels weight
    $('#package_volumetric').bind('DOMSubtreeModified', function(){
        var weight =  (parseFloat($("#package_volumetric").html()) > parseFloat($("#package_actual_weight").html())) ? parseFloat($("#package_volumetric").html()) : parseFloat($("#package_actual_weight").html());
        $("#package-weight").val(weight);
     });
    $('#package_cbm').bind('DOMSubtreeModified', function(){
        var package_cbm =  parseFloat($("#package_cbm").text());
        $("#total_package-cbm").val(package_cbm);
     });
});
function toggle_package_change(val){
  $("#wpcargo-package-table select").each(function() {
     if($(this).val()!="Documents" && $(this).val()!="") { $("#item_type").val("kg"); return false;  }
     else $("#item_type").val("docs");
  });
}
</script>