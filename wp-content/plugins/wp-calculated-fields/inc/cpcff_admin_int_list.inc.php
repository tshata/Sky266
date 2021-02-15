<?php
if ( !is_admin() )
{
	print 'Direct access not allowed.';
    exit;
}

$_GET['u'] = (isset($_GET['u'])) ? intval(@$_GET['u']) : 0;
$_GET['c'] = (isset($_GET['c'])) ? intval(@$_GET['c']) : 0;
$_GET['d'] = (isset($_GET['d'])) ? intval(@$_GET['d']) : 0;

global $wpdb;
$cpcff_main = CPCFF_MAIN::instance();

$message = "";

if (isset($_GET['a']) && $_GET['a'] == '1')
{
	check_admin_referer( 'cff-add-form', '_cpcff_nonce' );
	$cpcff_main->create_form((isset($_GET["name"])) ? sanitize_text_field(stripcslashes($_GET["name"])) : '');
    $message = __( "Item added", 'wp-calculated-fields' );
}
else if (isset($_GET['u']) && $_GET['u'] != '')
{
	check_admin_referer( 'cff-update-form', '_cpcff_nonce' );
	$cpcff_main->get_form($_GET['u'])->update_name((isset($_GET["name"])) ? sanitize_text_field(stripcslashes($_GET["name"])) : '');
    $message = __( "Item updated", 'wp-calculated-fields' );
}
else if (isset($_GET['d']) && $_GET['d'] != '')
{
	check_admin_referer( 'cff-delete-form', '_cpcff_nonce' );
	$cpcff_main->delete_form($_GET['d']);
	$message = __( "Item deleted", 'wp-calculated-fields' );
} else if (isset($_GET['c']) && $_GET['c'] != '')
{
	check_admin_referer( 'cff-clone-form', '_cpcff_nonce' );
	if($cpcff_main->clone_form(@intval($_GET['c'])) !== false) $message = __( "Item duplicated/cloned", 'wp-calculated-fields' );
	else $message = __( "Duplicate/Clone Error, the form cannot be cloned", 'wp-calculated-fields' );
} else if (isset($_GET['ac']) && $_GET['ac'] == 'st')
{
	check_admin_referer( 'cff-update-general-settings', '_cpcff_nonce' );
    update_option( 'CP_CFF_LOAD_SCRIPTS', 			  		(isset($_GET["scr"]) && $_GET["scr"]=="1"? "0":"1")  );
    update_option( 'CP_CALCULATEDFIELDSF_USE_CACHE',  		(isset($_GET["jsc"]) && $_GET["jsc"]=="1" ? 1 : 0)  );
    update_option( 'CP_CALCULATEDFIELDSF_OPTIMIZATION_PLUGIN',(isset($_GET["optm"]) && $_GET["optm"]=="1" ? 1 : 0)  );
    update_option( 'CP_CALCULATEDFIELDSF_EXCLUDE_CRAWLERS', (isset($_GET["ecr"]) && $_GET["ecr"]=="1" ? 1 : 0)  );
    update_option( 'CP_CALCULATEDFIELDSF_DIRECT_FORM_ACCESS',(isset($_GET["df"]) && $_GET["df"]=="1" ? 1 : 0)  );
    update_option( 'CP_CALCULATEDFIELDSF_AMP', 				(isset($_GET["amp"]) && $_GET["amp"]=="1" ? 1 : 0)  );

	$public_js_path = CP_CALCULATEDFIELDSF_BASE_PATH.'/js/cache/all.js';
	try{
		if( get_option( 'CP_CALCULATEDFIELDSF_USE_CACHE', CP_CALCULATEDFIELDSF_USE_CACHE ) == false )
		{
			if( file_exists( $public_js_path ) )
			{
				unlink( $public_js_path );
			}
		}
		else
		{
			if(!file_exists($public_js_path))
			{
				wp_remote_get(CPCFF_AUXILIARY::wp_url().((strpos(CPCFF_AUXILIARY::wp_url(),'?') === false) ? '/?' : '&').'cp_cff_resources=public&min=1', array('sslverify' => false));
			}
		}
	}catch( Exception $err ){}

    if ($_GET["chs"] != '')
    {
        $target_charset = $_GET["chs"];
		if( !in_array($target_charset, array('utf8_general_ci', 'utf8mb4_general_ci', 'latin1_swedish_ci')) ) $target_charset = 'utf8_general_ci';

        $tables = array( $wpdb->prefix.CP_CALCULATEDFIELDSF_FORMS_TABLE, $wpdb->prefix.CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME_NO_PREFIX );
        foreach ($tables as $tab)
        {
            $myrows = $wpdb->get_results( "DESCRIBE {$tab}" );
            foreach ($myrows as $item)
	        {
	            $name = $item->Field;
		        $type = $item->Type;
		        if (preg_match("/^varchar\((\d+)\)$/i", $type, $mat) || !strcasecmp($type, "CHAR") || !strcasecmp($type, "TEXT") || !strcasecmp($type, "MEDIUMTEXT"))
		        {
	                $wpdb->query("ALTER TABLE {$tab} CHANGE {$name} {$name} {$type} COLLATE {$target_charset}");
	            }
	        }
        }
    }
    $message = __( "Troubleshoot settings updated", 'wp-calculated-fields' );
}


if ($message) echo "<div id='setting-error-settings_updated' class='updated settings-error'><p><strong>".$message."</strong></p></div>";

?>
<div class="wrap">
<h1><?php _e( 'Wordpress Calculated Fields', 'wp-calculated-fields' ); ?></h1>

<script type="text/javascript">
 function cp_addItem()
 {
    var calname = document.getElementById("cp_itemname").value;
	document.location = 'admin.php?page=cp_wp_calculated_fields&a=1&r='+Math.random()+'&name='+encodeURIComponent(calname)+'&_cpcff_nonce=<?php echo wp_create_nonce( 'cff-add-form' ); ?>';
 }

 function cp_addItem_keyup( e )
 {
    e.which = e.which || e.keyCode;
    if(e.which == 13) {
        var calname = document.getElementById("cp_itemname").value;
        document.location = 'admin.php?page=cp_wp_calculated_fields&a=1&r='+Math.random()+'&name='+encodeURIComponent(calname)+'&_cpcff_nonce=<?php echo wp_create_nonce( 'cff-add-form' ); ?>';
    }
 }

 function cp_updateItem(id)
 {
    var calname = document.getElementById("calname_"+id).value;
    document.location = 'admin.php?page=cp_wp_calculated_fields&u='+id+'&r='+Math.random()+'&name='+encodeURIComponent(calname)+'&_cpcff_nonce=<?php echo wp_create_nonce( 'cff-update-form' ); ?>';
 }

 function cp_cloneItem(id)
 {
    document.location = 'admin.php?page=cp_wp_calculated_fields&c='+id+'&r='+Math.random()+'&_cpcff_nonce=<?php echo wp_create_nonce( 'cff-clone-form' ); ?>';
 }

 function cp_manageSettings(id)
 {
    document.location = 'admin.php?page=cp_wp_calculated_fields&cal='+id+'&r='+Math.random()+'&_cpcff_nonce=<?php echo wp_create_nonce( 'cff-form-settings' ); ?>';
 }

 function cp_viewMessages(id)
 {
    alert('Not available in this version. Check other versions at: '+"\n\n"+'https://cff.dwbooster.com/download');
 }

 function cp_BookingsList(id)
 {
    document.location = 'admin.php?page=cp_wp_calculated_fields&cal='+id+'&list=1&r='+Math.random();
 }

 function cp_deleteItem(id)
 {
    if (confirm('<?php _e( 'Are you sure that you want to delete this item?', 'wp-calculated-fields' ); ?>'))
    {
        document.location = 'admin.php?page=cp_wp_calculated_fields&d='+id+'&r='+Math.random()+'&_cpcff_nonce=<?php echo wp_create_nonce( 'cff-delete-form' ); ?>';
    }
 }

 function cp_updateConfig()
 {
    if (confirm('<?php _e( 'Are you sure that you want to update these settings?', 'wp-calculated-fields' ); ?>'))
    {
        var scr = document.getElementById("ccscriptload").value,
			chs = document.getElementById("cccharsets").value,
			jsc = (document.getElementById("ccjscache").checked) ? 1 : 0,
			optm = (document.getElementById("ccoptimizationplugin").checked) ? 1 : 0,
			df  = (document.getElementById("ccdirectform").checked) ? 1 : 0,
			amp = (document.getElementById("ccampform").checked) ? 1 : 0,
			ecr = (document.getElementById("ccexcludecrawler").checked) ? 1 : 0;

        document.location = 'admin.php?page=cp_wp_calculated_fields&ecr='+ecr+'&ac=st&scr='+scr+'&chs='+chs+'&jsc='+jsc+'&optm='+optm+'&df='+df+'&amp='+amp+'&r='+Math.random()+'&_cpcff_nonce=<?php echo wp_create_nonce( 'cff-update-general-settings' ); ?>';
    }
 }
</script>
<div id="normal-sortables" class="meta-box-sortables">
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Form List / Items List', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside" style="overflow-x:auto;">
  <table cellspacing="10" class="cff-custom-table cff-forms-list">
	<thead>
	   <tr>
		<th align="left"><?php _e( 'ID', 'wp-calculated-fields' ); ?></th><th align="left"><?php _e( 'Form Name', 'wp-calculated-fields' ); ?></th><th align="center"><?php _e( 'Options', 'wp-calculated-fields' ); ?></th><th align="left"><?php _e( 'Shortcode', 'wp-calculated-fields' ); ?></th>
	   </tr>
	</thead>
	<tbody>
<?php
  $myrows = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix.CP_CALCULATEDFIELDSF_FORMS_TABLE." ORDER BY id ASC" );
  foreach ($myrows as $item)
  {
?>
   <tr>
    <td nowrap><?php echo $item->id; ?></td>
    <td nowrap><input type="text" name="calname_<?php echo $item->id; ?>" id="calname_<?php echo $item->id; ?>" value="<?php echo esc_attr($item->form_name); ?>" /></td>

    <td nowrap>
		<input type="button" name="calupdate_<?php echo $item->id; ?>" value="<?php esc_attr_e( 'Update', 'wp-calculated-fields' ); ?>" onclick="cp_updateItem(<?php echo $item->id; ?>);" />
		<input type="button" name="calmanage_<?php echo $item->id; ?>" value="<?php esc_attr_e( 'Settings', 'wp-calculated-fields' ); ?>" onclick="cp_manageSettings(<?php echo $item->id; ?>);" />
		<input type="button" name="calmanage_<?php echo $item->id; ?>" value="<?php esc_attr_e( 'Messages', 'wp-calculated-fields' ); ?>" onclick="cp_viewMessages(<?php echo $item->id; ?>);" />
		<input type="button" name="calclone_<?php echo $item->id; ?>" value="<?php esc_attr_e( 'Clone', 'wp-calculated-fields' ); ?>" onclick="cp_cloneItem(<?php echo $item->id; ?>);" />
		<input type="button" name="caldelete_<?php echo $item->id; ?>" value="<?php esc_attr_e( 'Delete', 'wp-calculated-fields' ); ?>" onclick="cp_deleteItem(<?php echo $item->id; ?>);" />
    </td>
    <td nowrap>[CP_CALCULATED_FIELDS id="<?php echo $item->id; ?>"]</td>
   </tr>
<?php
   }
?>
	</tbody>
  </table>
  </div>
 </div>

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'New Form', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside">

    <form name="additem">
      <?php _e( 'Item Name', 'wp-calculated-fields' ); ?>:<br />
      <input type="text" name="cp_itemname" id="cp_itemname"  value="" onkeyup="cp_addItem_keyup( event );" /> <input type="button" onclick="cp_addItem();" name="gobtn" value="<?php esc_attr_e( 'Add', 'wp-calculated-fields' ); ?>" />
      <br /><br />
    </form>
  </div>
 </div>
<div style="border:1px solid #F0AD4E;background:#FBE6CA;padding:10px;margin:10px 0;font-size:1.3em;">
<?php _e('For additional resources visit the plugin\'s', 'wp-calculated-fields')?> <a href="https://cff-bundles.dwbooster.com" target="_blank" style="font-weight:bold;"><?php _e('Marketplace', 'wp-calculated-fields'); ?></a>
</div>
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span><?php _e( 'Troubleshoot Area & General Settings', 'wp-calculated-fields' ); ?></span></h3>
  <div class="inside">
	<?php
		$compatibility_warnings = $cpcff_main->compatibility_warnings();
	    if(!empty($compatibility_warnings))
		{
			print '<div style="margin:10px 0; border:1px dashed #FF0000; padding:10px; color:red;">'.$compatibility_warnings.'</div>';
		}
	?>
    <form name="updatesettings">
		<div style="border:1px solid #DADADA; padding:10px;">
			<p><?php _e( '<strong>Important!</strong>: Use this area <strong>only</strong> if you are experiencing conflicts with third party plugins, with the theme scripts or with the character encoding.', 'wp-calculated-fields' ); ?></p>
			<?php _e( 'Script load method', 'wp-calculated-fields' ); ?>:<br />
			<select id="ccscriptload" name="ccscriptload">
			<option value="0" <?php if (get_option('CP_CFF_LOAD_SCRIPTS',"0") == "1") echo 'selected'; ?>><?php _e( 'Classic (Recommended)', 'wp-calculated-fields' ); ?></option>
			<option value="1" <?php if (get_option('CP_CFF_LOAD_SCRIPTS',"0") != "1") echo 'selected'; ?>><?php _e( 'Direct', 'wp-calculated-fields' ); ?></option>
			</select><br />
			<em><?php _e( '* Change the script load method if the form doesn\'t appear in the public website.', 'wp-calculated-fields' ); ?></em>
			<br /><br />
			<?php _e( 'Character encoding', 'wp-calculated-fields' ); ?>:<br />
			<select id="cccharsets" name="cccharsets">
			<option value=""><?php _e( 'Keep current charset (Recommended)', 'wp-calculated-fields' ); ?></option>
			<option value="utf8_general_ci">UTF-8 (<?php _e( 'try this first', 'wp-calculated-fields' ); ?>)</option>
			<option value="utf8mb4_general_ci">UTF-8mb4 (<?php _e( 'Only from MySQL 5.5', 'wp-calculated-fields' ); ?>)</option>
			<option value="latin1_swedish_ci">latin1_swedish_ci</option>
			</select><br />
			<em><?php _e( '* Update the charset if you are getting problems displaying special/non-latin characters. After updated you need to edit the special characters again.', 'wp-calculated-fields' ); ?></em>
		   <br /><br />
		   <?php _e( "There is active an optimization plugin in WordPress", 'wp-calculated-fields' ); ?>:<br />
		   <input type="checkbox" id="ccoptimizationplugin" name="ccoptimizationplugin" value="1" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_OPTIMIZATION_PLUGIN', CP_CALCULATEDFIELDSF_OPTIMIZATION_PLUGIN ) ) ? 'CHECKED' : ''; ?> /><em><?php _e('* Tick the checkbox if there is an optimization plugin active on the website, and the forms are not visible.', 'wp-calculated-fields'); ?></em>
		</div>
		<br />
	   <?php _e( 'Activate Javascript Cache', 'wp-calculated-fields' ); ?>: <input type="checkbox" name="ccjscache" id="ccjscache" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_USE_CACHE', CP_CALCULATEDFIELDSF_USE_CACHE ) ) ? 'CHECKED' : ''; ?> />
       <br /><br />
       <?php _e( 'Allows to access the forms directly', 'wp-calculated-fields' ); ?>: <input type="checkbox" name="ccdirectform" id="ccdirectform" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_DIRECT_FORM_ACCESS', CP_CALCULATEDFIELDSF_DIRECT_FORM_ACCESS ) ) ? 'CHECKED' : ''; ?> />
       <br /><br />
       <?php _e( 'Allows to access the forms from amp pages', 'wp-calculated-fields' ); ?>: <input type="checkbox" name="ccampform" id="ccampform" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_AMP', CP_CALCULATEDFIELDSF_AMP ) ) ? 'CHECKED' : ''; ?> />
       <br /><br />
       <?php _e( 'Do not load the forms with crawlers', 'wp-calculated-fields' ); ?>: <input type="checkbox" name="ccexcludecrawler" id="ccexcludecrawler" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_EXCLUDE_CRAWLERS', false ) ) ? 'CHECKED' : ''; ?> /><br /><i><?php _e( '* The forms are not loaded when website is being indexed by searchers.', 'wp-calculated-fields' ); ?></i>
       <br /><br />
       <input type="button" onclick="cp_updateConfig();" name="gobtn" value="<?php esc_attr_e( 'UPDATE', 'wp-calculated-fields' ); ?>" />
       <br />
    </form>

  </div>
 </div>
</div>
[<a href="https://cff.dwbooster.com/customization" target="_blank"><?php _e( 'Request Custom Modifications', 'wp-calculated-fields' ); ?></a>] | [<a href="https://cff.dwbooster.com/download" target="_blank"><?php _e( 'Upgrade', 'wp-calculated-fields' ); ?></a>] | [<a href="https://wordpress.org/support/plugin/wp-calculated-fields#new-post" target="_blank"><?php _e( 'Help', 'wp-calculated-fields' ); ?></a>]
</form>
</div>