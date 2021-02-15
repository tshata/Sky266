<?php

if ( !is_admin() )
{
    print 'Direct access not allowed.';
    exit;
}

// Required scripts
require_once CP_CALCULATEDFIELDSF_BASE_PATH.'/inc/cpcff_templates.inc.php';

check_admin_referer( 'cff-form-settings', '_cpcff_nonce' );

// Load resources
wp_enqueue_media();
if(function_exists('wp_enqueue_code_editor')) wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
wp_enqueue_style('cff-chosen-css', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css');
wp_enqueue_script('cff-chosen-js', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js',array("jquery"));

if (!defined('CP_CALCULATEDFIELDSF_ID'))
    define ('CP_CALCULATEDFIELDSF_ID',intval($_GET["cal"]));

$cpcff_main = CPCFF_MAIN::instance();
$form_obj = $cpcff_main->get_form(intval($_GET["cal"]));

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cpcff_revision_to_apply'] ) )
{
	$revision_id = @intval($_POST['cpcff_revision_to_apply']);
	if($revision_id)
	{
		$form_obj->apply_revision($revision_id);
	}
}

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['cp_calculatedfieldsf_post_options'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>".__( 'Settings saved', 'wp-calculated-fields' )."</strong></p></div>";

global $cpcff_default_texts_array;
$cpcff_texts_array = $form_obj->get_option( 'vs_all_texts', $cpcff_default_texts_array );
$cpcff_texts_array = CPCFF_AUXILIARY::array_replace_recursive(
        $cpcff_default_texts_array,
        ( is_string( $cpcff_texts_array ) && is_array( unserialize( $cpcff_texts_array ) ) )
            ? unserialize( $cpcff_texts_array )
            : ( ( is_array( $cpcff_texts_array ) ) ? $cpcff_texts_array : array() )
    );

?>
<div class="wrap">
<h1><?php
	print __( 'Wordpress Calculated Fields', 'wp-calculated-fields' ).' <span class="cff-form-name-shortcode">(<b>'.__('Form', 'wp-calculated-fields').' '.CP_CALCULATEDFIELDSF_ID.' - '.$form_obj->get_option( 'form_name', '').'</b>) Shortcode: [CP_CALCULATED_FIELDS id="'.CP_CALCULATEDFIELDSF_ID.'"]</span>';

	if(get_option('CP_CALCULATEDFIELDSF_DIRECT_FORM_ACCESS', CP_CALCULATEDFIELDSF_DIRECT_FORM_ACCESS))
	{
		$url = CPCFF_AUXILIARY::site_url();
		$url .= (strpos($url, '?') === false) ? '?'	: '&';
		$url .= 'cff-form='.CP_CALCULATEDFIELDSF_ID;
		print '<br><span style="font-size:14px;font-style:italic;">'.__('Direct form URL', 'wp-calculated-fields').': <a href="'.esc_attr($url).'" target="_blank">'.$url.'</a></span>';
	}
?></h1>
<input type="button" name="backbtn" value="<?php esc_attr_e( 'Back to items list...', 'wp-calculated-fields' ); ?>" onclick="document.location='admin.php?page=cp_wp_calculated_fields';">
<br /><br />

<form method="post" action="" name="cpformconf" class="cff_form_builder">
<input type="hidden" name="_cpcff_nonce" value="<?php echo wp_create_nonce( 'cff-form-settings' ); ?>" />
<input name="cp_calculatedfieldsf_post_options" type="hidden" value="1" />
<input name="cp_calculatedfieldsf_id" type="hidden" value="<?php echo CP_CALCULATEDFIELDSF_ID; ?>" />

<div id="normal-sortables" class="meta-box-sortables">

 <h2><?php _e( 'Form Settings', 'wp-calculated-fields' ); ?>:</h2>
 <hr />
 <div><?php _e( '* Different form styles available on the tab Form Settings &gt;&gt; Form Template', 'wp-calculated-fields' ); ?></div>
 <div id="metabox_basic_settings" class="postbox" >
  <div class="hndle">
	<h3 style="padding:5px;display:inline-block;"><span><?php _e( 'Form Builder', 'wp-calculated-fields' ); ?></span></h3>
	<div class="cff-revisions-container">
		<?php _e('Revisions','wp-calculated-fields'); ?>
		<select name="cff_revision_list">

			<?php
				print '<option value="0">'.esc_html(__('Select a revision', 'wp-calculated-fields')).'</option>';
				$revisions_obj = $form_obj->get_revisions();
				$revisions = $revisions_obj->revisions_list();
				foreach($revisions as $revision_id => $revision_data)
				{
					print '<option value="'.esc_attr($revision_id).'">'.esc_html($revision_data['time']).'</option>';
				}
			?>
		</select>
		<input type="button" name="cff_apply_revision" value="<?php print esc_attr('Load Revision', 'wp-calculated-fields'); ?>" class="button" style="float:none;" />
		<input type="button" name="previewbtn" id="previewbtn2" class="button-primary" value="<?php esc_attr_e( 'Preview', 'wp-calculated-fields' ); ?>" onclick="jQuery.fbuilder.preview( this );" title="Saves the form's structure only, and opens a preview windows" />
	</div>
  </div>
  <div class="inside">
	 <div class="form-builder-error-messages"><?php
        global $cff_structure_error;
        if( !empty( $cff_structure_error ) )
        {
            echo $cff_structure_error;
        }
     ?></div>
     <p style="border:1px solid #F0AD4E;background:#FBE6CA;padding:10px;"><span style="font-weight:bold;"><?php _e('If the form is not loading in the public website, go to the settings page of the plugin through the menu option: "Settings/Wordpress Calculated Fields", select the "Classic" option for the attribute: "Script load method", and press the "Update" button.','wp-calculated-fields'); ?></span><br /><?php _e( 'If you need also the form to be sent to the server side for processing (for example to deliver emails) then the <a href="https://cff.dwbooster.com/" target="_blank">Professional or Developer versions</a> of the plugin will be required.', 'wp-calculated-fields' ); ?></p>
	 <input type="hidden" name="form_structure" id="form_structure" value="<?php print esc_attr(preg_replace('/&quot;/i', '&amp;quot;', json_encode($form_obj->get_option( 'form_structure', CP_CALCULATEDFIELDSF_DEFAULT_form_structure )))); ?>" />
     <input type="hidden" name="templates" id="templates" value="<?php print esc_attr( json_encode( CPCFF_TEMPLATES::load_templates() ) ); ?>" />
     <link href="<?php echo plugins_url('/css/cupertino/jquery-ui-1.8.20.custom.css', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH); ?>" type="text/css" rel="stylesheet" property="stylesheet" />
	<pre style="display:none;">
	<script type="text/javascript">
		try
		{
			function calculatedFieldsFormReady()
			{
				/* Revisions code */
				$calculatedfieldsfQuery('[name="cff_apply_revision"]').click(
					function(){
						var revision = $calculatedfieldsfQuery('[name="cff_revision_list"]').val();
						if(revision*1)
						{
							result = window.confirm('<?php print esc_js(__('The action will load the revision selected, the data are not stored will be lose. Do you want continue?', 'wp-calculated-fields'));?>');
							if(result)
							{
								$calculatedfieldsfQuery('<form method="post" action="" name="cpformconf" class="cff_form_builder"><input type="hidden" name="_cpcff_nonce" value="<?php echo wp_create_nonce( 'cff-form-settings' ); ?>" /><input name="cp_calculatedfieldsf_id" type="hidden" value="<?php echo CP_CALCULATEDFIELDSF_ID; ?>" /><input type="hidden" name="cpcff_revision_to_apply" value="'+revision+'"></form>').appendTo('body').submit();
							}
						}
					}
				);

				// Form builder code

				var f;
				function run_fbuilder($)
				{
					f = $("#fbuilder").fbuilder();
					window['cff_form'] = f;
					f.fBuild.loadData( "form_structure", "templates" );
				};

				if(!('fbuilder' in $calculatedfieldsfQuery.fn))
				{
					$calculatedfieldsfQuery.getScript(
						location.protocol + '//' + location.host + location.pathname+'?page=cp_wp_calculated_fields&cp_cff_resources=admin',
						function(){run_fbuilder(fbuilderjQuery);}
					);
				}
				else
				{
					run_fbuilder($calculatedfieldsfQuery);
				}

				$calculatedfieldsfQuery(".itemForm").click(function() {
				   f.fBuild.addItem($calculatedfieldsfQuery(this).attr("id"));
				});

				jQuery("#metabox_basic_settings0,#metabox_basic_settings1,#metabox_basic_settings2,#metabox_basic_settings3,#metabox_basic_settings4")
				.click( function(){
				  if(confirm("<?php _e( 'These features aren\'t available in this version. Do you want to open the plugin\'s page to check other versions?', 'wp-calculated-fields' ); ?>"))
					  document.location = 'https://cff.dwbooster.com/';
				})
				.find('*')
				.prop('disabled', true);
			};
		}
		catch( err ){}
        try{$calculatedfieldsfQuery = jQuery.noConflict();} catch ( err ) {}
	    if (typeof $calculatedfieldsfQuery == 'undefined')
        {
			 if(window.addEventListener){
				window.addEventListener('load', function(){
					try{$calculatedfieldsfQuery = jQuery.noConflict();} catch ( err ) {return;}
					calculatedFieldsFormReady();
				});
			}else{
				window.attachEvent('onload', function(){
					try{$calculatedfieldsfQuery = jQuery.noConflict();} catch ( err ) {return;}
					calculatedFieldsFormReady();
				});
			}
	    }
		else
		{
			$calculatedfieldsfQuery(document).ready( calculatedFieldsFormReady );
		}
     </script>
	 </pre>
     <div style="background:#fafafa;" class="form-builder">

         <div class="column ctrlsColumn">
             <div id="tabs">
				<span class="ui-icon ui-icon-triangle-1-e expand-shrink"></span>
     			<ul>
     				<li><a href="#tabs-1"><?php _e( 'Add a Field', 'wp-calculated-fields' ); ?></a></li>
     				<li><a href="#tabs-2"><?php _e( 'Field Settings', 'wp-calculated-fields' ); ?></a></li>
     				<li><a href="#tabs-3"><?php _e( 'Form Settings', 'wp-calculated-fields' ); ?></a></li>
     			</ul>
     			<div id="tabs-1"></div>
     			<div id="tabs-2"></div>
     			<div id="tabs-3"></div>
     		</div>
         </div>
         <div class="columnr dashboardColumn padding10" id="fbuilder">
             <div id="formheader"></div>
             <div id="fieldlist"></div>
         </div>
         <div class="clearer"></div>

     </div>

  </div>
 </div>

 <p class="submit">
	<input type="submit" name="save" id="save" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'wp-calculated-fields' ); ?>"  title="Saves the form's structure and settings and creates a revision" />
	<input type="button" name="previewbtn" id="previewbtn" class="button-primary" value="<?php esc_attr_e( 'Preview', 'wp-calculated-fields' ); ?>" onclick="jQuery.fbuilder.preview( this );" title="Saves the form's structure only, and opens a preview windows" />
	| <input type="checkbox" name="cff-revisions-in-preview" <?php if(get_option('CP_CALCULATEDFIELDSF_REVISIONS_IN_PREVIEW', true)) print 'CHECKED'; ?> /><?php _e('Generate revisions in the form preview as well', 'wp-calculated-fields'); ?>
</p>

  <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Define Texts', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Previous button label (text)', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="vs_text_previousbtn" size="40" value="<?php $label = esc_attr($form_obj->get_option('vs_text_previousbtn', 'Previous')); echo ($label==''?'Previous':$label); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Next button label (text)', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="vs_text_nextbtn" size="40" value="<?php $label = esc_attr($form_obj->get_option('vs_text_nextbtn', 'Next')); echo ($label==''?'Next':$label); ?>" /></td>
        </tr>
        <tr valign="top">
        <td colspan="2">
        <?php _e( '- The styles can be applied into any of the CSS files of your theme or into the CSS file <em>"wp-calculated-fields\css\stylepublic.css"</em>.', 'wp-calculated-fields' ); ?><br />
        <?php _e( '- For general CSS styles modifications to the form and samples <a href="https://cff.dwbooster.com/faq#q82" target="_blank">check this FAQ</a>.', 'wp-calculated-fields' ); ?>
        </tr>
        <?php
         // Display all other text fields
         foreach( $cpcff_texts_array as $cpcff_text_index => $cpcff_text_attr )
         {
			if( $cpcff_text_index !== 'errors'  )
			{
				print '
				<tr valign="top">
					<th scope="row">'.$cpcff_text_attr[ 'label' ].':</th>
					<td><input type="text" name="cpcff_text_array['.$cpcff_text_index.'][text]" size="40" value="'. esc_attr( $cpcff_text_attr[ 'text' ] ).'" /></td>
				</tr>
				';
			}
         }
        ?>
     </table>
  </div>
 </div>

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Validation Settings', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( '"is required" text', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="vs_text_is_required" size="40" value="<?php echo esc_attr($form_obj->get_option('vs_text_is_required', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_is_required)); ?>" /></td>
        </tr>
         <tr valign="top">
        <th scope="row"><?php _e( '"is email" text', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="vs_text_is_email" size="70" value="<?php echo esc_attr($form_obj->get_option('vs_text_is_email', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_is_email)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"is valid captcha" text', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="cv_text_enter_valid_captcha" size="70" value="<?php echo esc_attr($form_obj->get_option('cv_text_enter_valid_captcha', CP_CALCULATEDFIELDSF_DEFAULT_cv_text_enter_valid_captcha)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( '"is valid date (mm/dd/yyyy)" text', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="vs_text_datemmddyyyy" size="70" value="<?php echo esc_attr($form_obj->get_option('vs_text_datemmddyyyy', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_datemmddyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"is valid date (dd/mm/yyyy)" text', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="vs_text_dateddmmyyyy" size="70" value="<?php echo esc_attr($form_obj->get_option('vs_text_dateddmmyyyy', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_dateddmmyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"is number" text', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="vs_text_number" size="70" value="<?php echo esc_attr($form_obj->get_option('vs_text_number', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_number)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"only digits" text', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="vs_text_digits" size="70" value="<?php echo esc_attr($form_obj->get_option('vs_text_digits', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_digits)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"under maximum" text', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="vs_text_max" size="70" value="<?php echo esc_attr($form_obj->get_option('vs_text_max', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_max)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( '"over minimum" text', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="vs_text_min" size="70" value="<?php echo esc_attr($form_obj->get_option('vs_text_min', CP_CALCULATEDFIELDSF_DEFAULT_vs_text_min)); ?>" /></td>
        </tr>
		<?php
		// Display all other text fields
		if( !empty( $cpcff_texts_array[ 'errors' ] ) )
		{
			foreach( $cpcff_texts_array[ 'errors' ] as $cpcff_text_index => $cpcff_text_attr )
			{
				print '
				<tr valign="top">
					<th scope="row">'.$cpcff_text_attr[ 'label' ].':</th>
					<td><input type="text" name="cpcff_text_array[errors]['.$cpcff_text_index.'][text]" size="40" value="'. esc_attr( $cpcff_text_attr[ 'text' ] ).'" /></td>
				</tr>
				';
			}
		}
        ?>
     </table>
  </div>
 </div>

<div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Note', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside">
   <?php _e( 'To insert this form in a post/page, use the dedicated icon', 'wp-calculated-fields' ); ?>
   <?php print '<a href="javascript:cp_calculatedfieldsf_insertForm();" title="'.esc_attr_e( 'Insert Wordpress Calculated Fields', 'wp-calculated-fields' ).'"><img hspace="5" src="'.plugins_url('/images/cp_form.gif', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH).'" alt="'.esc_attr_e( 'Insert Wordpress Calculated Fields', 'wp-calculated-fields' ).'" /></a>';     ?>
   <?php _e( 'which has been added to your Upload/Insert Menu, just below the title of your Post/Page.', 'wp-calculated-fields' ); ?>
   <br /><br />
  </div>
</div>

 <p class="submit">
	<input type="submit" name="save" id="save" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'wp-calculated-fields' ); ?>" title="Saves the form's structure and settings and creates a revision" />
</p>

 [<a href="https://cff.dwbooster.com/customization" target="_blank"><?php _e( 'Request Custom Modifications', 'wp-calculated-fields' ); ?></a>] | [<a href="https://wordpress.org/support/plugin/wp-calculated-fields#new-post" target="_blank"><?php _e( 'Help', 'wp-calculated-fields' ); ?></a>]

 <br /><br /><br />

 <h3><?php _e( 'The following settings are available only in the <a href="https://cff.dwbooster.com/">pro version</a>', 'wp-calculated-fields' ); ?>:</h3>

 <h2><?php _e( 'Form Processing and Payment Settings', 'wp-calculated-fields' ); ?>:</h2>
 <hr />

  <div id="metabox_basic_settings0" class="postbox" style="position:relative;">
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Payment Settings', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside">

    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Request cost', 'wp-calculated-fields' ); ?></th>
        <td><select name="request_cost" id="request_cost" ></select></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Currency', 'wp-calculated-fields' ); ?></th>
        <td><input type="text" name="currency" value="<?php echo esc_attr($form_obj->get_option('currency',CP_CALCULATEDFIELDSF_DEFAULT_CURRENCY)); ?>" /></td>
        </tr>

		<tr valign="top">
        <th scope="row"><?php _e( 'Base amount', 'wp-calculated-fields' ); ?>:</th>
        <td><input type="text" name="paypal_base_amount" value="<?php echo esc_attr($form_obj->get_option( 'paypal_base_amount', '0.01' ) ); ?>" /> <?php _e( 'Minimum amount to charge. If the final price is lesser than this number, the base amount will be applied.', 'wp-calculated-fields' ); ?>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Paypal product name', 'wp-calculated-fields' ); ?></th>
        <td><input type="text" name="paypal_product_name" size="50" value="<?php echo esc_attr($form_obj->get_option('paypal_product_name',CP_CALCULATEDFIELDSF_DEFAULT_PRODUCT_NAME)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Discount Codes', 'wp-calculated-fields' ); ?></th>
        <td>
           <div id="dex_nocodes_availmsg"><?php _e( 'This feature isn\'t available in this version.', 'wp-calculated-fields' ); ?></div>

           <br />
           <strong><?php _e( 'Add new discount code', 'wp-calculated-fields' ); ?>:</strong>
           <br />
           <nobr><?php _e( 'Code', 'wp-calculated-fields' ); ?>: <input type="text" name="dex_dc_code" id="dex_dc_code" value="" /></nobr> &nbsp; &nbsp; &nbsp;
           <nobr><?php _e( 'Discount', 'wp-calculated-fields' ); ?>: <input type="text" size="3" name="dex_dc_discount" id="dex_dc_discount"  value="25" /><select name="dex_dc_discounttype" id="dex_dc_discounttype">
                   <option value="0"><?php _e( 'Percent', 'wp-calculated-fields' ); ?></option>
                   <option value="1"><?php _e( 'Fixed Value', 'wp-calculated-fields' ); ?></option>
                 </select></nobr>
                    &nbsp; &nbsp;
           <nobr><?php _e( 'Valid until', 'wp-calculated-fields' ); ?>: <input type="text"  size="10" name="dex_dc_expires" id="dex_dc_expires" value="" /></nobr>&nbsp; &nbsp; &nbsp;
           <input type="button" name="dex_dc_subccode" id="dex_dc_subccode" value="<?php esc_attr_e( 'Add', 'wp-calculated-fields' ); ?>" onclick="alert('This feature ins\'t available in this version');" />
           <br />
           <em><?php _e( 'Note: Expiration date based in server time. Server time now is', 'wp-calculated-fields' ); ?> <?php echo date("Y-m-d H:i"); ?></em>
        </td>
        </tr>
     </table>
  </div>
</div>

 <div id="metabox_basic_settings1" class="postbox" style="position:relative;">
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Paypal Payment Configuration', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside">

    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Enable Paypal Payments?', 'wp-calculated-fields' ); ?></th>
        <td><select name="enable_paypal">
             <option value="0"><?php _e( 'No', 'wp-calculated-fields' ); ?></option>
            </select>
            <br /><em style="font-size:11px;"><?php _e( 'Note: If "Optional" is selected, a radiobutton will appear in the form to select if the payment will be made with PayPal or not.', 'wp-calculated-fields' ); ?></em>
            <div id="cff_paypal_options_label" style="margin-top:10px;background:#EEF5FB;border: 1px dotted #888888;padding:10px;width:260px;">
              <?php _e( 'Label for the "<strong>Pay with PayPal</strong>" option', 'wp-calculated-fields' ); ?>:<br />
              <input type="text" size="40" style="width:250px;" />
            </div></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Paypal Mode', 'wp-calculated-fields' ); ?></th>
        <td><select name="paypal_mode">
             <option value="production" <?php if ($form_obj->get_option('paypal_mode',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_MODE) != 'sandbox') echo 'selected'; ?>><?php _e( 'Production - real payments processed', 'wp-calculated-fields' ); ?></option>
             <option value="sandbox" <?php if ($form_obj->get_option('paypal_mode',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_MODE) == 'sandbox') echo 'selected'; ?>><?php _e( 'SandBox - PayPal testing sandbox area', 'wp-calculated-fields' ); ?></option>
            </select>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Paypal email', 'wp-calculated-fields' ); ?></th>
        <td><input type="text" name="paypal_email" size="40" value="<?php echo esc_attr($form_obj->get_option('paypal_email',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_EMAIL)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'A $0 amount to pay means', 'wp-calculated-fields' ); ?>:</th>
        <td><select name="paypal_zero_payment">
             <option value="0" <?php if ($form_obj->get_option('paypal_zero_payment',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_ZERO_PAYMENT) != '1') echo 'selected'; ?>><?php _e( 'Let the user enter any amount at PayPal (ex: for a donation)', 'wp-calculated-fields' ); ?></option>
             <option value="1" <?php if ($form_obj->get_option('paypal_zero_payment',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_ZERO_PAYMENT) == '1') echo 'selected'; ?>><?php _e( 'Don\'t require any payment. Form is submitted skiping the PayPal page.', 'wp-calculated-fields' ); ?></option>
            </select>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Paypal language', 'wp-calculated-fields' ); ?></th>
        <td><input type="text" name="paypal_language" value="<?php echo esc_attr($form_obj->get_option('paypal_language',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_LANGUAGE)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row"><?php _e( 'Payment frequency', 'wp-calculated-fields' ); ?></th>
        <td><select name="paypal_recurrent">
             <option value="0" <?php if ($form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '0' ||
                                         $form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == ''
                                        ) echo 'selected'; ?>><?php _e( 'One time payment (default option, user is billed only once)', 'wp-calculated-fields' ); ?></option>
             <option value="1" <?php if ($form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '1') echo 'selected'; ?>><?php _e( 'Bill the user every 1 month', 'wp-calculated-fields' ); ?></option>
             <option value="3" <?php if ($form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '3') echo 'selected'; ?>><?php _e( 'Bill the user every 3 months', 'wp-calculated-fields' ); ?></option>
             <option value="6" <?php if ($form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '6') echo 'selected'; ?>><?php _e( 'Bill the user every 6 months', 'wp-calculated-fields' ); ?></option>
             <option value="12" <?php if ($form_obj->get_option('paypal_recurrent',CP_CALCULATEDFIELDSF_DEFAULT_PAYPAL_RECURRENT) == '12') echo 'selected'; ?>><?php _e( 'Bill the user every 12 months', 'wp-calculated-fields' ); ?></option>
            </select>
        </td>
        </tr>
		<tr valign="top">
        <th scope="row"><?php _e( 'Paypal prompt buyers for shipping address', 'wp-calculated-fields' ); ?></th>
        <td>
			<?php $paypal_address = $form_obj->get_option('paypal_address', 1); ?>
			<select name="paypal_address">
				<option value="1" <?php if($paypal_address == 1) print 'SELECTED'; ?>><?php _e('Do not prompt for an address', 'wp-calculated-fields'); ?></option>
				<option value="0" <?php if($paypal_address == 0) print 'SELECTED'; ?>><?php _e('Prompt for an address, but do not require one', 'wp-calculated-fields'); ?></option>
				<option value="2" <?php if($paypal_address == 2) print 'SELECTED'; ?>><?php _e('Prompt for an address and require one', 'wp-calculated-fields'); ?></option>
			</select>
		</td>
        </tr>
     </table>

  </div>
 </div>

 <div id="metabox_basic_settings2" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Form Processing / Email Settings', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( '"From" email', 'wp-calculated-fields' ); ?></th>
        <td><input type="text" name="fp_from_email" size="40" value="<?php echo esc_attr($form_obj->get_option('fp_from_email', CP_CALCULATEDFIELDSF_DEFAULT_fp_from_email)); ?>" /><br><b><em>Ex: admin@<?php echo str_replace('www.','',$_SERVER["HTTP_HOST"]); ?></em></b><br><em><?php _e( 'This email is required if the "From fixed email address" option is selected, or it is enabled the email copy to the user.', 'wp-calculated-fields' ); ?></em></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Destination emails (comma separated)', 'wp-calculated-fields' ); ?></th>
        <td><input type="text" name="fp_destination_emails" size="40" value="<?php echo esc_attr($form_obj->get_option('fp_destination_emails', CP_CALCULATEDFIELDSF_DEFAULT_fp_destination_emails)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email subject', 'wp-calculated-fields' ); ?></th>
        <td><input type="text" name="fp_subject" size="70" value="<?php echo esc_attr($form_obj->get_option('fp_subject', CP_CALCULATEDFIELDSF_DEFAULT_fp_subject)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Include additional information?', 'wp-calculated-fields' ); ?></th>
        <td>
          <?php $option = $form_obj->get_option('fp_inc_additional_info', CP_CALCULATEDFIELDSF_DEFAULT_fp_inc_additional_info); ?>
          <select name="fp_inc_additional_info">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e( 'Yes', 'wp-calculated-fields' ); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e( 'No', 'wp-calculated-fields' ); ?></option>
          </select>&nbsp;<em><?php _e('If the "No" option is selected the plugin won\'t capture the IP address of users.','wp-calculated-fields'); ?></em>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Thank you page (after sending the message)', 'wp-calculated-fields' ); ?></th>
        <td><input type="text" name="fp_return_page" size="70" value="<?php echo esc_attr($form_obj->get_option('fp_return_page', CP_CALCULATEDFIELDSF_DEFAULT_fp_return_page)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email format?', 'wp-calculated-fields' ); ?></th>
        <td>
          <?php $option = $form_obj->get_option('fp_emailformat', CP_CALCULATEDFIELDSF_DEFAULT_email_format); ?>
          <select name="fp_emailformat">
           <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e( 'Plain Text (default)', 'wp-calculated-fields' ); ?></option>
           <option value="html"<?php if ($option == 'html') echo ' selected'; ?>><?php _e( 'HTML (use html in the textarea below)', 'wp-calculated-fields' ); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Message', 'wp-calculated-fields' ); ?></th>
        <td><textarea type="text" name="fp_message" rows="6" cols="80"><?php echo $form_obj->get_option('fp_message', CP_CALCULATEDFIELDSF_DEFAULT_fp_message); ?></textarea></td>
        </tr>
     </table>
  </div>
 </div>


 <div id="metabox_basic_settings3" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Email Copy to User', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Send confirmation/thank you message to user?', 'wp-calculated-fields' ); ?></th>
        <td>
          <?php $option = $form_obj->get_option('cu_enable_copy_to_user', CP_CALCULATEDFIELDSF_DEFAULT_cu_enable_copy_to_user); ?>
          <select name="cu_enable_copy_to_user">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e( 'Yes', 'wp-calculated-fields' ); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e( 'No', 'wp-calculated-fields' ); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email field on the form', 'wp-calculated-fields' ); ?></th>
        <td><select id="cu_user_email_field" name="cu_user_email_field" def="<?php echo esc_attr($form_obj->get_option('cu_user_email_field', CP_CALCULATEDFIELDSF_DEFAULT_cu_user_email_field)); ?>"></select></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email subject', 'wp-calculated-fields' ); ?></th>
        <td><input type="text" name="cu_subject" size="70" value="<?php echo esc_attr($form_obj->get_option('cu_subject', CP_CALCULATEDFIELDSF_DEFAULT_cu_subject)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Email format?', 'wp-calculated-fields' ); ?></th>
        <td>
          <?php $option = $form_obj->get_option('cu_emailformat', CP_CALCULATEDFIELDSF_DEFAULT_email_format); ?>
          <select name="cu_emailformat">
           <option value="text"<?php if ($option != 'html') echo ' selected'; ?>><?php _e( 'Plain Text (default)', 'wp-calculated-fields' ); ?></option>
           <option value="html"<?php if ($option == 'html') echo ' selected'; ?>><?php _e( 'HTML (use html in the textarea below)', 'wp-calculated-fields' ); ?></option>
          </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><?php _e( 'Message', 'wp-calculated-fields' ); ?></th>
        <td><textarea type="text" name="cu_message" rows="6" cols="80"><?php echo $form_obj->get_option('cu_message', CP_CALCULATEDFIELDSF_DEFAULT_cu_message); ?></textarea></td>
        </tr>
     </table>
  </div>
 </div>


 <div id="metabox_basic_settings4" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Captcha Verification', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e( 'Use Captcha Verification?', 'wp-calculated-fields' ); ?></th>
        <td colspan="5">
          <?php $option = $form_obj->get_option('cv_enable_captcha', CP_CALCULATEDFIELDSF_DEFAULT_cv_enable_captcha); ?>
          <select name="cv_enable_captcha">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>><?php _e( 'Yes', 'wp-calculated-fields' ); ?></option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>><?php _e( 'No', 'wp-calculated-fields' ); ?></option>
          </select>
        </td>
        </tr>

        <tr valign="top">
         <th scope="row"><?php _e( 'Width', 'wp-calculated-fields' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_width" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_width', CP_CALCULATEDFIELDSF_DEFAULT_cv_width)); ?>"  onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e( 'Height', 'wp-calculated-fields' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_height" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_height', CP_CALCULATEDFIELDSF_DEFAULT_cv_height)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e( 'Chars', 'wp-calculated-fields' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_chars" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_chars', CP_CALCULATEDFIELDSF_DEFAULT_cv_chars)); ?>" onblur="generateCaptcha();"  /></td>
        </tr>

        <tr valign="top">
         <th scope="row"><?php _e( 'Min font size', 'wp-calculated-fields' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_min_font_size" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_min_font_size', CP_CALCULATEDFIELDSF_DEFAULT_cv_min_font_size)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row"><?php _e( 'Max font size', 'wp-calculated-fields' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_max_font_size" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_max_font_size', CP_CALCULATEDFIELDSF_DEFAULT_cv_max_font_size)); ?>" onblur="generateCaptcha();"  /></td>
         <td colspan="2" rowspan="">
           <?php _e( 'Preview', 'wp-calculated-fields' ); ?>:<br />
             <br />
            <img src="<?php echo plugins_url('/captcha/captcha.php', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH); ?>"  id="captchaimg" alt="<?php esc_attr_e( 'security code', 'wp-calculated-fields' ); ?>" border="0"  />
         </td>
        </tr>


        <tr valign="top">
         <th scope="row"><?php _e( 'Noise', 'wp-calculated-fields' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_noise" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_noise', CP_CALCULATEDFIELDSF_DEFAULT_cv_noise)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row"><?php _e( 'Noise Length', 'wp-calculated-fields' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_noise_length" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_noise_length', CP_CALCULATEDFIELDSF_DEFAULT_cv_noise_length)); ?>" onblur="generateCaptcha();" /></td>
        </tr>

        <tr valign="top">
         <th scope="row"><?php _e( 'Background', 'wp-calculated-fields' ); ?>:</th>
         <td><input type="text" readonly=readonly name="cv_background" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_background', CP_CALCULATEDFIELDSF_DEFAULT_cv_background)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row">Border:</th>
         <td><input type="text" readonly=readonly name="cv_border" size="10" value="<?php echo esc_attr($form_obj->get_option('cv_border', CP_CALCULATEDFIELDSF_DEFAULT_cv_border)); ?>" onblur="generateCaptcha();" /></td>
        </tr>

        <tr valign="top">
         <th scope="row"><?php _e( 'Font', 'wp-calculated-fields' ); ?>:</th>
         <td>
            <select name="cv_font" onchange="generateCaptcha();" >
              <option value="font-1.ttf"<?php if ("font-1.ttf" == $form_obj->get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 1</option>
              <option value="font-2.ttf"<?php if ("font-2.ttf" == $form_obj->get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 2</option>
              <option value="font-3.ttf"<?php if ("font-3.ttf" == $form_obj->get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 3</option>
              <option value="font-4.ttf"<?php if ("font-4.ttf" == $form_obj->get_option('cv_font', CP_CALCULATEDFIELDSF_DEFAULT_cv_font)) echo " selected"; ?>>Font 4</option>
            </select>
         </td>
        </tr>
     </table>
  </div>
 </div>
 <?php
	do_action('cpcff_form_settings', CP_CALCULATEDFIELDSF_ID);
 ?>
</div>


<p class="submit">
	<input type="submit" name="save" id="save" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'wp-calculated-fields' ); ?>"  title="Saves the form's structure and settings" />
</p>

[<a href="https://cff.dwbooster.com/customization" target="_blank"><?php _e( 'Request Custom Modifications', 'wp-calculated-fields' ); ?></a>] | [<a href="https://wordpress.org/support/plugin/wp-calculated-fields#new-post" target="_blank"><?php _e( 'Help', 'wp-calculated-fields' ); ?></a>]
</form>
</div>
<script type="text/javascript">
	function generateCaptcha()
	{
	   var 	d=new Date(),
			f = document.cpformconf,
			qs = "?width="+f.cv_width.value;

	   qs += "&height="+f.cv_height.value;
	   qs += "&letter_count="+f.cv_chars.value;
	   qs += "&min_size="+f.cv_min_font_size.value;
	   qs += "&max_size="+f.cv_max_font_size.value;
	   qs += "&noise="+f.cv_noise.value;
	   qs += "&noiselength="+f.cv_noise_length.value;
	   qs += "&bcolor="+f.cv_background.value;
	   qs += "&border="+f.cv_border.value;
	   qs += "&font="+f.cv_font.options[f.cv_font.selectedIndex].value;
	   qs += "&rand="+d;

	   document.getElementById("captchaimg").src= "<?php echo plugins_url('/captcha/captcha.php', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH); ?>"+qs;
	}
	generateCaptcha();
</script>