<?php
	global $post, $wpcargo;
	$wpc_driver_args  	= array( 'role' => 'wpcargo_driver', 'orderby' => 'user_nicename', 'order' => 'ASC' );
	$wpc_drivers 		= get_users($wpc_driver_args);
	$wpc_client_args  	= array( 'role' => 'wpcargo_client', 'orderby' => 'user_nicename', 'order' => 'ASC' );
	$wpc_client 		= get_users($wpc_client_args);
	$wpc_employee_args  = array( 'role' => 'wpcargo_employee', 'orderby' => 'user_nicename', 'order' => 'ASC' );
	$wpc_employee 		= get_users($wpc_employee_args);
	$wpc_administrator_args  	= array( 'role' => 'administrator', 'orderby' => 'user_nicename', 'order' => 'ASC' );
	$wpc_administrator  = get_users($wpc_administrator_args);
?>
<div id="shipment-designation">
	<div class="section-wrapper">
		<div class="label-section"><strong><label><?php esc_html_e('Client','wpcargo'); ?></label></strong></div>
		<div class="select-section">
			<select name="registered_shipper" class="mdb-select mt-0 form-control browser-default" id="registered_client">
				<option value=""><?php esc_html_e('-- Select Client --','wpcargo'); ?></option>
				<?php if( !empty( $wpc_client ) ): ?>
					<?php foreach( $wpc_client as $client ): ?>
						<option value="<?php echo $client->ID; ?>" <?php selected( get_post_meta( $post->ID, 'registered_shipper', TRUE ), $client->ID ); ?>><?php echo $wpcargo->user_fullname( $client->ID ); ?></option>
					<?php endforeach; ?>	
				<?php  endif; ?>
			</select>
		</div>
	</div>
	<div class="section-wrapper">
		<div class="label-section"><strong><label><?php esc_html_e('Driver Name','wpcargo' ); ?></label></strong></div>
		<div class="select-section">
			<?php
				if( !empty( $wpc_drivers ) ) {
					$assigned_driver = $post->allocated_driver;
					?>
					<select name="allocated_driver">
						<option value=""><?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
						<?php foreach ($wpc_drivers as $driver): ?>
							<option value="<?php esc_html_e(sanitize_text_field($driver->ID)); ?>" <?php selected( $assigned_driver, $driver->ID ); ?> ><?php echo $wpcargo->user_fullname( $driver->ID ); ?></option>
						<?php endforeach; ?>
					</select><?php
				}
			?>
			<?php if( empty( $wpc_drivers ) ) : ?>
				<span class="meta-box error">
					<?php esc_html_e('No drivers found, please add drivers ', 'wpcargo' ); ?>
					<a href="<?php echo admin_url().'/user-new.php'; ?>">
						<?php esc_html__('here.', 'wpcargo' ); ?>
					</a>
					<?php esc_html__(' Make sure the role assign is "Driver".', 'wpcargo' ); ?>
				</span>
			<?php endif; ?>
		</div>
	</div>
	<!--div class="section-wrapper">
		<div class="label-section"><label><strong><?php esc_html_e('Employee','wpcargo'); ?></strong></label></div>
		<div class="select-section">
			<select name="wpcargo_employee" class="mdb-select mt-0 form-control browser-default" id="wpcargo_employee">
			<option value=""><?php esc_html_e('-- Select Employee --','wpcargo'); ?></option>
			<?php if( !empty( $wpc_employee ) ): ?>
				<?php foreach( $wpc_employee as $employee ): ?>
					<option value="<?php echo $employee->ID; ?>" <?php selected( get_post_meta( $post->ID, 'wpcargo_employee', TRUE ), $employee->ID ); ?>><?php echo $wpcargo->user_fullname( $employee->ID ); ?></option>
				<?php endforeach; ?>	
			<?php  endif; ?>	                
			</select>
		</div>
	</div-->
	<?php do_action('wpc_after_shipment_designation', $post->ID); ?>
</div>