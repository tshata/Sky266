<h3><?php echo wpcargo_brand_name(); ?> <?php esc_html_e('User Profile', 'wpcargo' ); ?></h3>
<table class="form-table">
	<tr>
		<th><label for="wpc_user_timezone"><?php esc_html_e('Timezone', 'wpcargo' ); ?></label></th>
		<td>
			<select id="wpc_user_timezone" name="wpc_user_timezone" aria-describedby="timezone-description">
				<?php echo wp_timezone_choice( $tzstring, get_user_locale() ); ?>
			</select>
			<p class="description"><?php esc_html_e('Choose user timezone. This will override general settings timezone for user.', 'wpcargo' ); ?></p>
		</td>
	</tr>
	<tr>
		<th><?php esc_html_e('Company Logo', 'wpcargo'  ); ?></th>
		<td>
			<?php
				if( get_user_meta( $user->ID, 'company_logo', TRUE ) ){
					?><img style="vertical-align: middle;" width="120" height="120" src="<?php echo get_user_meta( $user->ID, 'company_logo', TRUE ); ?>" alt="Company Logo" /><?php
				}
			?>
			<input id="company_logo" class="update_account" type="text" name="company_logo" value="<?php echo ( get_user_meta( $user->ID, 'company_logo', TRUE ) ) ? get_user_meta( $user->ID, 'company_logo', TRUE ) : '' ; ?>" />
			<a id="choose-logo" class="button" style="vertical-align: middle;" ><?php esc_html_e('Upload Company Logo', 'wpcargo'  ); ?></a>
		</td>
	</tr>
	<tr>
		<th><label for="business_reg"><?php esc_html_e('Business Registration #', 'wpcargo' ); ?></label></th>
		<td>
			<input id="business_reg" class="update_account" type="text" name="business_reg" value="<?php echo ( get_user_meta( $user->ID, 'business_reg', TRUE ) ) ? get_user_meta( $user->ID, 'business_reg', TRUE ) : '' ; ?>" />
		</td>
	</tr>
    <tr>
		<th><label for="gst_account"><?php esc_html_e('GST Account #', 'wpcargo' ); ?></label></th>
		<td>
			<input id="gst_account" class="update_account" type="text" name="gst_account" value="<?php echo ( get_user_meta( $user->ID, 'gst_account', TRUE ) ) ? get_user_meta( $user->ID, 'gst_account', TRUE ) : '' ; ?>" />
		</td>
	</tr>
    <tr>
		<th><label for="min_notification"><?php esc_html_e('Minimum Notification Unit', 'wpcargo' ); ?></label></th>
		<td>
			<input id="min_notification" class="update_account" type="text" name="min_notification" value="<?php echo ( get_user_meta( $user->ID, 'min_notification', TRUE ) ) ? get_user_meta( $user->ID, 'min_notification', TRUE ) : '' ; ?>" />
		</td>
	</tr>
</table>
<?php //if ( function_exists( 'is_woocommerce_activated' ) ) : ?>
<h3><?php esc_html_e('WPCargo Profile Information', 'wpcargo' ); ?></h3>
<table class="form-table">
	<?php do_action('wpcargo_before_user_profile', $user ); ?>
	<tr>
		<th><label for="billing_company"><?php esc_html_e('Company Name', 'wpcargo' ); ?></label></th>
		<td>
			<input id="billing_company" class="update_account" type="text" name="billing_company" value="<?php echo ( get_user_meta( $user->ID, 'billing_company', TRUE ) ) ? get_user_meta( $user->ID, 'billing_company', TRUE ) : '' ; ?>" />
		</td>
	</tr>
	<tr>
		<th><?php esc_html_e('Company Address', 'wpcargo' ); ?></th>
	</tr>
	<tr>
		<th><?php esc_html_e('Address line 1', 'wpcargo' ); ?></th>
		<td>
			<input id="billing_address_1" class="update_account" type="text" name="billing_address_1" value="<?php echo ( get_user_meta( $user->ID, 'billing_address_1', TRUE ) ) ? get_user_meta( $user->ID, 'billing_address_1', TRUE ) : '' ; ?>" />
		</td>
	</tr>
	<tr>
		<th><?php esc_html_e('Address line 2', 'wpcargo' ); ?></th>
		<td>
			<input id="billing_address_2" class="update_account" type="text" name="billing_address_2" value="<?php echo ( get_user_meta( $user->ID, 'billing_address_2', TRUE ) ) ? get_user_meta( $user->ID, 'billing_address_2', TRUE ) : '' ; ?>" />
		</td>
	</tr>
	<tr>
		<th><?php esc_html_e('City', 'wpcargo' ); ?></th>
		<td>
			<input id="billing_city" class="update_account" type="text" name="billing_city" value="<?php echo ( get_user_meta( $user->ID, 'billing_city', TRUE ) ) ? get_user_meta( $user->ID, 'billing_city', TRUE ) : '' ; ?>" />
		</td>
	</tr>
	<tr>
		<th><?php esc_html_e('Postcode', 'wpcargo' ); ?></th>
		<td>
			<input id="billing_postcode" class="update_account" type="text" name="billing_postcode" value="<?php echo ( get_user_meta( $user->ID, 'billing_postcode', TRUE ) ) ? get_user_meta( $user->ID, 'billing_postcode', TRUE ) : '' ; ?>" />
		</td>
	</tr>
	<tr>
		<th><?php esc_html_e('Country', 'wpcargo'  ); ?></th>
		<td>
			<input id="billing_country" class="update_account" type="text" name="billing_country" value="<?php echo ( get_user_meta( $user->ID, 'billing_country', TRUE ) ) ? get_user_meta( $user->ID, 'billing_country', TRUE ) : '' ; ?>" />
		</td>
	</tr>
	<tr>
		<th><?php esc_html_e('Contact Number', 'wpcargo'  ); ?></th>
		<td>
			<input id="billing_phone" class="update_account" type="text" name="billing_phone" value="<?php echo ( get_user_meta( $user->ID, 'billing_phone', TRUE ) ) ? get_user_meta( $user->ID, 'billing_phone', TRUE ) : '' ; ?>" required />
		</td>
	</tr>
</table>
<?php //endif; ?>
<script>
	jQuery(document).ready(function($){
		// Uploading files
		var file_frame;
		  $('#choose-logo').live('click', function( event ){
			event.preventDefault();
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
			  file_frame.open();
			  return;
			}
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
			  title: $( this ).data( 'uploader_title' ),
			  button: {
				text: $( this ).data( 'uploader_button_text' ),
			  },
			  multiple: false  // Set to true to allow multiple files to be selected
			});
		
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
			  // We set multiple to false so only get one image from the uploader
			  attachment = file_frame.state().get('selection').first().toJSON();
		
			  // Do something with attachment.id and/or attachment.url here
			  $('#company_logo').val( attachment.url );
			  
			});
		
			// Finally, open the modal
			file_frame.open();
		  });
	});
</script>