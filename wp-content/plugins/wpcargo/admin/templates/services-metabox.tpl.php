
<?php
     $tax_invoice = (!empty(get_post_meta($post->ID, 'tax_invoice', true))) ? 'checked' : '';
     $no_tax_invoice = (!empty(get_post_meta($post->ID, 'no_tax_invoice', true))) ? 'checked' : '';
     $upload_tax_invoice = (!empty(get_post_meta($post->ID, 'upload_tax_invoice', true))) ? 'checked' : '';
     $bring_tax_invoice_box = (!empty(get_post_meta($post->ID, 'bring_tax_invoice_box', true))) ? 'checked' : '';
     $sent_tax_invoice_box = (!empty(get_post_meta($post->ID, 'sent_tax_invoice_box', true))) ? 'checked' : '';
     $collect_tax_invoice_box = (!empty(get_post_meta($post->ID, 'collect_tax_invoice_box', true))) ? 'checked' : '';
     $receipt = (!empty(get_post_meta($post->ID, 'receipt', true))) ? 'checked' : '';
     $no_receipt = (!empty(get_post_meta($post->ID, 'no_receipt', true))) ? 'checked' : '';
     $upload_receipt = (!empty(get_post_meta($post->ID, 'upload_receipt', true))) ? 'checked' : '';
     $bring_receipt_box = (!empty(get_post_meta($post->ID, 'bring_receipt_box', true))) ? 'checked' : '';
     $sent_receipt_box = (!empty(get_post_meta($post->ID, 'sent_receipt_box', true))) ? 'checked' : '';
     $collect_receipt_box = (!empty(get_post_meta($post->ID, 'collect_receipt_box', true))) ? 'checked' : '';
     $clearance  = (!empty(get_post_meta($post->ID, 'clearance', true))) ? 'checked' : '';
     $noclearance  = (!empty(get_post_meta($post->ID, 'noclearance', true))) ? 'checked' : '';

?>


<div class="one-half first" id="shipment-details">
	<h1>4(b). Border Taxes & Clearance</h1> <hr/>
	<?php do_action('wpc_before_shipment_details_table', $post->ID); ?>
	<table class="wpcargo form-table" id="taxes_clearance">
		<?php do_action('wpc_before_shipment_details_metabox', $post->ID); ?>
        <tr>
           <td>
             <label class="form-label" for="label"><b>Has Tax Invoice</b> </label>
             <table style="width:100%;">
                  <tr><td><p>
                            <input style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" type='checkbox' onclick="documents_switch(this)" <?php echo $tax_invoice; ?>  name="tax_invoice" id="tax_invoice" data-group='tax_invoice'><label style="font: bolder;"> Yes</label><span>&nbsp;&nbsp;</span>
                            <input style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" type='checkbox' onclick="documents_switch(this)" <?php echo $no_tax_invoice; ?> name="no_tax_invoice" id="no_tax_invoice" data-group='tax_invoice'> <label style="font: bolder;">No</label>
                  </p></td></tr>
                  <tr id="tax_invoice_yes_more"><td>
                             <i class="wpcargo-col-md-12" style="font-size: 11px; display: block;"><b>Please select one option from bellow</b></i>
                             <input style="height:15px; width:15px; margin-left: 20px;" type="checkbox" <?php echo $upload_tax_invoice; ?> name="upload_tax_invoice_box" id="upload_tax_invoice" data-id="upload" data-group="tax_invoice_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Upload your tax invoice</label>
                             <input class="notvalidate" style="width:100%; margin-left: 50px; display:none;" type="file" id="upload_tax_invoice_file" name="tax_invoice_file">
                             <br><input style="margin-left: 20px; height:15px; width:15px;" type="checkbox" <?php echo $bring_tax_invoice_box; ?> name="bring_tax_invoice_box" data-group="tax_invoice_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Or bring it to our office</label>
                             <br><input style="margin-left: 20px; height:15px; width:15px;" type="checkbox" <?php echo $sent_tax_invoice_box; ?> name="sent_tax_invoice_box" data-group="tax_invoice_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Or sent it via whatsapp</label>
                             <br><input style="margin-left: 20px; height:15px; width:15px;" type="checkbox" <?php echo $collect_tax_invoice_box; ?> name="collect_tax_invoice_box" data-group="tax_invoice_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Or we will collect it from your supplier</label>
                  </td></tr>
                </table>
            </td>
        </tr>
        <tr id="doc2">
           <td> <label class="form-label" for="label"><b>Has Cash Receipt</b> </label>
             <table style="width:100%;">
                  <tr><td><p>
                            <input style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" <?php echo $receipt; ?> type='checkbox' onclick="documents_switch(this)" name="receipt" id="receipt" data-group='receipt'><label style="font: bolder;"> Yes</label><span>&nbsp;&nbsp;</span>
                            <input style="width: 20px; height: 20px; margin: 3px 3px 3px 3px;" <?php echo $no_receipt; ?> type='checkbox' onclick="documents_switch(this)" name="no_receipt" id="no_receipt" data-group='receipt'> <label style="font: bolder;">No</label>
                  </p></td></tr>
                  <tr id="receipt_yes_more"><td>
                             <i class="wpcargo-col-md-12" style="font-size: 11px; display: block;"><b>Please select one option from bellow</b></i>
                             <input style="height:15px; width:15px; margin-left: 20px;" <?php echo $upload_receipt; ?> type="checkbox" name="upload_receipt_box" id="upload_receipt" data-id="upload" data-group="receipt_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Upload your receipt</label>
                             <input class="notvalidate" style="width:100%; margin-left: 50px; display:none;" type="file" id="upload_receipt_file" name="receipt_file">
                             <br><input style="margin-left: 20px; height:15px; width:15px;" <?php echo $bring_receipt_box; ?> type="checkbox" name="bring_receipt_box" data-group="receipt_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Or bring it to our office</label>
                             <br><input style="margin-left: 20px; height:15px; width:15px;" <?php echo $sent_receipt_box; ?> type="checkbox" name="sent_receipt_box" data-group="receipt_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Or sent it via whatsapp</label>
                             <br><input style="margin-left: 20px; height:15px; width:15px;" <?php echo $collect_receipt_box; ?> type="checkbox" name="collect_receipt_box" data-group="receipt_yes" onchange="toggle_fileupload(this)"> <label style="font: bolder;">Or we will collect it from your supplier</label>
                   </td></tr>
                  <tr id="receipt_yes_more"><td>
                             <div style="margin-left: 20px;"><label style="font: bolder;">How much is the total cost for the parcels: </label>
                             <input class="notvalidate" style="width:200px;" type="text" name="parcels_cost" id="parcels_cost" value="<?php echo get_post_meta($post->ID, 'parcels_cost', true); ?>" placeholder="M"> </div>
                  </td></tr>
                </table>
            </td>
        </tr>

		<?php do_action('wpc_after_shipment_details_metabox', $post->ID); ?>
	</table>
	<?php  do_action('wpc_after_shipment_details_table', $post->ID); ?>
</div>
<div class="one-half" id="shipment-details">
	<h1><?php echo apply_filters('wpc_shipment_details_label', esc_html__('4(a). Other Details', 'wpcargo' ) ); ?></h1> <hr/>
	<?php do_action('wpc_before_shipment_details_table', $post->ID); ?>
	<table class="wpcargo form-table">
		<?php do_action('wpc_before_shipment_details_metabox', $post->ID); ?>
        <tr>
           <th><label class="form-label" for="label"><b>Other services:</b> </label></th>
           <td>
              <?php
                $results = get_settings_items();
                $items = unserialize($results->meta_data);
                $i=0;
                $excepts = array("customsdeclarationfee","bordertaxes");
                if(!empty($items)) {
                 foreach ( $items as $key => $item_data ) {
                   if($item_data['is_route_item']=="" && $item_data['is_private_item']=="" && $item_data['item_type']=="Income" && !in_array($key,$excepts) ) {
                     ?>
                     <p><input style="margin-left: 20px; height:15px; width:15px;" type="checkbox" onclick="select_service(this)" <?php echo (!empty(get_post_meta($post->ID, $key, true))) ? 'checked' : ''; ?> name="<?php echo $key;?>" value="<?php echo $key;?>"> <label style="font: bolder;"><?php echo $item_data['display_name']; ?></label></p>
                   <?php } $i++;
               } }
             ?>
          </td>
        </tr>

		<?php do_action('wpc_after_shipment_details_metabox', $post->ID); ?>
	</table>
	<?php do_action('wpc_after_shipment_details_table', $post->ID); ?>
</div>