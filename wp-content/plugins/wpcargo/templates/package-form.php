

<div class="wpc-mp-wrap">
  <h2><?php echo apply_filters('wpc_shipment_details_label', esc_html__('Parcel Details', 'wpcargo' ) ); ?></h2><hr style="border: 1px solid black;"/><br>

	<table id="wpcargo-package-table" class="wpc-multiple-package wpc-repeater">
		<thead>
			<tr>
                <th style="width: 18%;"><?php echo "Piece Type"; ?></th>
                <th style="width: 7%;"><?php echo "Qty."; ?></th>
                <th style="width: 3%; border: none; background: transparent;"></th>
                <th style="width: 11%;"><?php echo "Length (cm)"; ?></th>
                <th style="width: 11%;"><?php echo "Width df (cm)"; ?></th>
                <th style="width: 11%;"><?php echo "Height (cm)"; ?></th>
                <th style="width: 12%;"><?php echo "Actual Weight(kg)"; ?></th>
                <th style="width: 13%; border: none; background: transparent;">&nbsp;</th>
                <th style="width: 12%; border: none; background: transparent;">&nbsp;</th>
			</tr>
		</thead>
		<tbody data-repeater-list="<?php echo WPCARGO_PACKAGE_POSTMETA; ?>">
			<?php if(!empty(wpcargo_get_package_data( $shipment->ID ))): ?>
				<?php foreach ( wpcargo_get_package_data( $shipment->ID ) as $data_key => $data_value): ?>
				<tr data-repeater-item class="wpc-mp-tr">
					<?php foreach ( wpcargo_package_fields() as $field_key => $field_value): ?>
						<?php
						if( in_array( $field_key, wpcargo_package_dim_meta() ) && !wpcargo_package_settings()->dim_unit_enable ){
							continue;
						}
						?>
						<td>
							<?php
							$package_data = array_key_exists( $field_key, $data_value ) ? $data_value[$field_key] : '' ;
							$package_data = is_array( $package_data ) ? implode(',', $package_data ) : $package_data;
							echo wpcargo_field_generator( $field_value, $field_key, $package_data );
							?>
						</td>
					<?php endforeach; ?>
					<td><input data-repeater-delete type="button" class="wpc-delete" value="<?php esc_html_e('Delete','wpcargo'); ?>"/></td>

				</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr data-repeater-item class="wpc-mp-tr">
					<?php $i=1; foreach ( wpcargo_package_fields() as $field_key => $field_value): ?>
						<?php
						if( in_array( $field_key, wpcargo_package_dim_meta() ) && !wpcargo_package_settings()->dim_unit_enable ){
							continue;
						}
                        ?>
                    <?php echo ($i==4)? '<td></td>' :'';  ?>
					<td>
						<?php echo wpcargo_field_generator( $field_value, $field_key, '', $field_key."  notvalidate" ); ?>
					</td>
					<?php $i++; endforeach; ?>
					<td style="line-height: 16px;"> <center>
                        <input type="checkbox" onclick="toggle_dimentions(this)" style="width: 14px; height: 14px;" />Unknown
                    </center></td>
					<td style="line-height: 16px;"> <center>
                        <a style="color: red; cursor: pointer;" data-repeater-delete> <?php esc_html_e('Delete Row','wpcargo'); ?></a>
                    </center></td>
				</tr>
			<?php endif; ?>
		</tbody>   
		<tfoot>
			<?php do_action('wpcargo_after_package_table_row', $shipment); ?>
			<tr class="wpc-computation">
				<td colspan="3" style="border: none;"><input data-repeater-create type="button" class="wpc-add" value="<?php esc_html_e('Add Parcel','wpcargo'); ?>"/></td>
			</tr>
		</tfoot>
	</table>
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
});
</script>
<style>
.wpc-multiple-package th {

  line-height: 14px;
  text-align: center;
  padding: 5px 0;
  color: #000000;
  background-color: var( --wpcargo );
}
</style>
