<?php
if ( !defined('CP_AUTH_INCLUDE') )
{
	print 'Direct access not allowed.';
	exit;
}

// Required scripts
require_once CP_CALCULATEDFIELDSF_BASE_PATH.'/inc/cpcff_templates.inc.php';

// Corrects a conflict with W3 Total Cache
if( function_exists( 'w3_instance' ) )
{
	try
	{
		$w3_config = w3_instance( 'W3_Config' );
		$w3_config->set( 'minify.html.enable', false );
	}
	catch( Exception $err )
	{

	}
}

add_filter( 'style_loader_tag', array('CPCFF_AUXILIARY', 'complete_link_tag') );

wp_enqueue_style( 'cpcff_stylepublic', plugins_url('/css/stylepublic.css', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH));
wp_enqueue_style( 'cpcff_jquery_ui'  , plugins_url('/css/cupertino/jquery-ui-1.8.20.custom.css', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH));

$cpcff_main = CPCFF_MAIN::instance();
$form_obj = $cpcff_main->get_form($id);

$form_data = $form_obj->get_option('form_structure', CP_CALCULATEDFIELDSF_DEFAULT_form_structure);
if( !empty( $form_data ) )
{
	if( isset( $form_data[ 1 ] ) && isset( $form_data[ 1 ][ 0 ] ) )
	{
		if(!empty( $form_data[ 1 ][ 0 ]->formtemplate ))
		{
			CPCFF_TEMPLATES::enqueue_template_resources($form_data[ 1 ][ 0 ]->formtemplate);
		}

		if(!empty($form_data[ 1 ][ 0 ]->customstyles))
		{
			print '<style>'.$form_data[ 1 ][ 0 ]->customstyles.'</style>';
		}
	}
	$form_data[ 1 ][ 'formid' ]="cp_calculatedfieldsf_pform_".CPCFF_MAIN::$form_counter;
?>
<form name="<?php echo $form_data[ 1 ][ 'formid' ]; ?>" id="<?php echo $form_data[ 1 ][ 'formid' ]; ?>" action="?" method="post" enctype="multipart/form-data" class="<?php
	if(!empty($form_data[1][0]) && !empty($form_data[1][0]->persistence)) echo ' persist-form';
	if(!empty($atts) && !empty($atts['class'])) echo ' '.esc_attr($atts['class']);
?>"><input type="hidden" name="cp_calculatedfieldsf_id" value="<?php echo $id; ?>" /><pre style="display:none !important;"><!--noptimize--><script>form_structure_<?php echo CPCFF_MAIN::$form_counter; ?>=<?php print str_replace( array( "\n", "\r" ), " ", ((version_compare(CP_CFF_PHPVERSION,"5.3.0")>=0)?json_encode($form_data, JSON_HEX_QUOT|JSON_HEX_TAG):json_encode($form_data)) ); ?>;</script><!--/noptimize--></pre>
<div id="fbuilder">
  <div id="fbuilder_<?php echo CPCFF_MAIN::$form_counter; ?>">
      <div id="formheader_<?php echo CPCFF_MAIN::$form_counter; ?>"></div>
      <div id="fieldlist_<?php echo CPCFF_MAIN::$form_counter; ?>"></div>
  </div>
</div>
<div class="clearer"></div>
</form>
<?php
}
?>