<?php

             //update/close unclosed schedules
            // update_schedules_status();

            $trips = $wpdb->get_results( "SELECT * FROM trips WHERE driver = '$user_id' ");   // AND status=='Active'
            $trip_id = $trips[0]->id;
	        echo "<b>".$trips[0]->trip_name." (".date_format(date_create($trips[0]->trip_date),'d-M-Y').")</b> &nbsp;&nbsp; Status: <b>".$trips[0]->status."</b> <br>";
            $trip_cities = unserialize($trips[0]->city_schedules);
            $i=0;
            ?>


<?php
            if(is_array($trip_cities)) {   ?>
<div class="panel-group" id="accordion" style="padding-left:3%;"> <br>
    <?php
              foreach($trip_cities AS $trip_city){
                        $schedule_city_id = $trip_city['schedule_city'];
                        $schedule_city = $wpdb->get_results( "SELECT city_name FROM `countries_cities` WHERE id = '".$schedule_city_id."' ");
                        $schedule_id = $trip_city['schedule_id'];
                     ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo 'accordion'.$i;?>">
                    <?php echo $schedule_city[0]->city_name; ?></a>
            </h4>
        </div>
        <div id="<?php echo 'accordion'.$i;?>" class="panel-collapse collapse">
            <div class="panel-body" style="color: black; padding-left: 40px;">
                <table id="bookings_table_list">
                    <tr>
                        <th>Booking Reference</th>
                        <th>Activity</th>
                        <th>Service Type</th>
                        <th>Address Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php

                               $bookings= $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}posts` AS tbl1 JOIN `{$wpdb->prefix}postmeta` AS tbl2 ON tbl1.ID = tbl2.post_id
                                                               WHERE tbl1.post_status LIKE 'publish' AND tbl1.post_type LIKE 'wpcargo_shipment'
                                                               AND ((tbl2.meta_key LIKE 'collection_schedule_id' AND tbl2.meta_value LIKE '$schedule_id')
                                                                    OR (tbl2.meta_key LIKE 'delivery_schedule_id' AND tbl2.meta_value LIKE '$schedule_id'))");

                               if(is_array($bookings))
                                  foreach ( $bookings as $booking ) {
                                    $post_id = $booking->ID;
                                    $activity = (get_post_meta($post_id, 'collection_schedule_id', true)==$schedule_id) ? "Collection": "Delivery";
                                    $client = (!empty(get_post_meta( $post_id, 'wpcargo_receiver_company', true)))? get_post_meta( $post_id, 'wpcargo_receiver_company', true) : get_post_meta($booking->ID, 'wpcargo_receiver_fname', true)." ".get_post_meta($booking->ID, 'wpcargo_receiver_sname', true);
                                    $wpcargo_shipments_update = get_post_meta( $post_id, 'wpcargo_shipments_update', true ); //get_latest_substatus(get_post_meta( $post_id, 'wpcargo_shipments_update', true ));
                                    $wpcargo_status = "Awaiting Collection";
                                    if(strpos($wpcargo_shipments_update, "Collection") != false){ //if collection was done before, find what happend
                                        $wpcargo_status = "Collection";
                                    }

                                    $address_type = ($activity=="Collection") ? get_post_meta($booking->ID, 'wpcargo_shipper_address_type', true) : get_post_meta($booking->ID, 'wpcargo_delivery_address_type', true);
                                    echo "<tr>";
                                      echo "<td>".get_post_meta($booking->ID, 'booking_reference', true)."</td>";
                                      echo "<td>".$activity."</td>";
                                      echo "<td>".get_post_meta( $post_id, 'service_type', true)."</td>";
                                      echo "<td>".$address_type."</td>";
                                      echo "<td>".$wpcargo_status."</td>";
                                      echo "<td> <button>More Details</button> | <button>Update Status</button></td>";
                                    echo "</tr>";
                                }
                                else echo "<tr><td colspan='2'>No bookings found</td></tr>";
                              ?>
                </table>
            </div>
        </div>
    </div>
    <?php $i++; } ?>
</div>
<?php }

           /* $schedules = $wpdb->get_results( "SELECT * FROM collection_schedules GROUP BY schedule_name ORDER BY schedule_city ASC, schedule_name ASC ");
            $i =0;
            foreach ( $schedules as $schedule ) {
                 $schedule_id = $schedule->id;
                 $schedule_name = $schedule->schedule_name;
                 $schedule_city = $wpdb->get_results( "SELECT city_name FROM `countries_cities` WHERE id = '".$schedule->schedule_city."' ");
                 $Upcoming = $wpdb->get_results( "SELECT COUNT( id ) AS count FROM `collection_schedules` WHERE schedule_name = '$schedule_name' AND status = 'Upcoming' ");
                 $Closed = $wpdb->get_results( "SELECT COUNT( id ) AS count FROM `collection_schedules` WHERE schedule_name = '$schedule_name' AND status = 'Closed' ");
            }   */

    ?>

<!--div id="shipment-list">
		<table id="dataTable" class="display table wpcargo-table-responsive-md wpcargo-table" style="width:100%;">
           <thead>
				<tr>
                    <th><?php esc_html_e('Tracking Number', 'wpcargo'); ?></th>
					<th><?php esc_html_e('From', 'wpcargo'); ?></th>
                    <th><?php esc_html_e('To', 'wpcargo'); ?></th>
                    <th><?php esc_html_e('Booking Date', 'wpcargo'); ?></th>
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
								<td><?php echo get_the_date('j M Y G:i' ); ?></td>
								<td><?php echo wpcargo_get_postmeta( get_the_ID(), 'wpcargo_status' ); ?></td>
								<td><a class="view-shipment" href="#" data-id="<?php echo get_the_ID(); ?>"><?php esc_html_e('View Details', 'wpcargo'); ?></a> </td>
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