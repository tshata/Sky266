
	<h4><?php esc_html_e('Shipment List', 'wpcargo' ); ?></h4>
	<div id="shipment-list">
		<table id="dataTable" class="display table wpcargo-table-responsive-md wpcargo-table" style="width:100%;">
           <thead>
				<tr>
                    <th><?php esc_html_e('Tracking Number', 'wpcargo'); ?></th>
					<th><?php esc_html_e('From', 'wpcargo'); ?></th>
                    <th><?php esc_html_e('To', 'wpcargo'); ?></th>
					<th><?php esc_html_e('Booking By ', 'wpcargo'); ?></th>
                    <th><?php esc_html_e('Booking Date', 'wpcargo'); ?></th>
                    <th><?php esc_html_e('Booking Type', 'wpcargo'); ?></th>
                    <th><?php esc_html_e('Allocated Driver', 'wpcargo'); ?></th>
                    <th><?php esc_html_e('Status', 'wpcargo'); ?></th>
					<th><?php esc_html_e('Actions', 'wpcargo'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
					if ( $shipment_query->have_posts() ) :
						while ( $shipment_query->have_posts() ) : $shipment_query->the_post();
							//$shipperID = wpcargo_get_postmeta( get_the_ID(), 'registered_shipper'  );
							?>
							<tr>
								<td><?php echo get_the_title(); ?></td>
								<td><?php echo wpcargo_get_postmeta( get_the_ID(), 'wpcargo_origin_field' )." ".wpcargo_get_postmeta( get_the_ID(), 'wpcargo_origin_city_field' ); ?></td>
								<td><?php echo wpcargo_get_postmeta( get_the_ID(), 'wpcargo_destination' )." ".wpcargo_get_postmeta( get_the_ID(), 'wpcargo_destination_city' ); ?></td>
								<td><?php echo wpcargo_get_postmeta( get_the_ID(), 'wpcargo_receiver_fname' )." ".wpcargo_get_postmeta( get_the_ID(), 'wpcargo_receiver_sname' ); ?></td>
								<td><?php echo get_the_date('j M Y G:i' ); ?></td>
								<td><?php echo wpcargo_get_postmeta( get_the_ID(), 'booking_type' ); ?></td>
								<td><?php echo wpcargo_get_postmeta( get_the_ID(), 'allocated_driver' ); ?></td>
								<td><?php echo wpcargo_get_postmeta( get_the_ID(), 'wpcargo_status' ); ?></td>
								<td><a href="#wpcargo-account" data-id="<?php echo get_the_ID(); ?>" id="shipments_single" ><?php esc_html_e( 'View Details', 'textdomain' ); ?></a></td>
							</tr>
							<?php
						endwhile;
						else :
						?>
						<tr>
							<td colspan="<?php echo !in_array( 'administrator', $user_info->roles ) ? 6 : 5 ; ?>"><?php esc_html_e('No shipment found!', 'wpcargo' ); ?></td>
						</tr>
					<?php
					endif;
				?>
			</tbody>
		</table>
		<?php echo wpcargo_pagination( array( 'custom_query' => $shipment_query ) ); ?>
	</div><!-- list-container -->