<div class="wpc-mp-wrap">
	<table id="wpcargo-package-table" class="wpc-multiple-package wpc-repeater">
		<thead>
			<tr>
                <th style="width: 14%;"><?php echo "Piece Type"; ?></th>
                <th style="width: 6%;"><?php echo "Qty."; ?></th>
                <th style="width: 33%;"><?php echo "Description"; ?></th>
                <th style="width: 3%;"></th>
                <th style="width: 6%;"><?php echo "Length (cm)"; ?></th>
                <th style="width: 6%;"><?php echo "Width (cm)"; ?></th>
                <th style="width: 6%;"><?php echo "Height (cm)"; ?></th>
                <th style="width: 7%;"><?php echo "Actual Weight(kg)"; ?></th>
                <th style="width: 9%;">&nbsp;</th>
                <th style="width: 9%;">&nbsp;</th>
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
                          <?php echo ($i==4)? '<td></td>' :'';  ?>
  						<td>
  							<?php
  							$package_data = array_key_exists( $field_key, $data_value ) ? $data_value[$field_key] : '' ;
  							$package_data = is_array( $package_data ) ? implode(',', $package_data ) : $package_data;
  							echo wpcargo_field_generator( $field_value, $field_key, $package_data );
  							?>
  						</td>
  					<?php $i++;  endforeach; ?>
  					<td style="line-height: 16px;"> <center>
                          <input type="checkbox" onclick="toggle_dimentions(this)" style="width: 14px; height: 14px;" />Unknown
                      </center></td>
  					<td style="line-height: 16px;"> <center>
                          <a style="color: red; cursor: pointer;" data-repeater-delete> <?php esc_html_e('Delete Row','wpcargo'); ?></a>
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
                    <?php echo ($i==4)? '<td></td>' :'';  ?>
					<td>
						<?php echo wpcargo_field_generator( $field_value, $field_key ); ?>
					</td>
					<?php $i++;  endforeach; ?>
					<td style="line-height: 16px;"> <center>
                        <input type="checkbox" onclick="toggle_dimentions(this)" style="width: 14px; height: 14px;" />Unknown
                    </center></td>
					<td style="line-height: 16px;"> <center>
                        <a style="color: red; cursor: pointer;" data-repeater-delete> <?php esc_html_e('Delete Row','wpcargo'); ?></a>
                    </center></td>
					<!--td><input data-repeater-delete type="button" class="wpc-delete" value="<?php esc_html_e('Delete','wpcargo'); ?>"/></td-->
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
    <input type="hidden" id="package-weight" name="package-weight" value="<?php echo wpcargo_get_postmeta($shipment->ID, 'package-weight', true); ?>">
	<?php do_action('wpcargo_after_package_totals', $shipment ); ?>
</div>
<script>
jQuery(document).ready(function ($) {
	'use strict';
	$('#wpcargo-package-table').repeater({
		show: function () {
			$(this).slideDown();
		},
		hide: function (deleteElement) {
			if( confirm('<?php esc_html_e( 'Are you sure you want to delete this element?', 'wpcargo' ); ?>') ) {
				$(this).slideUp(deleteElement);
			}
		}
	});
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
    //find the biggest parcels weight
    $('#package_volumetric').bind('DOMSubtreeModified', function(){
        var weight =  (parseFloat($("#package_volumetric").html()) > parseFloat($("#package_actual_weight").html())) ? parseFloat($("#package_volumetric").html()) : parseFloat($("#package_actual_weight").html());
        $("#package-weight").val(weight);
     });

});
</script>