<?php
  $msg ="";
  global $wpdb;
  if(isset($_POST['submit']) && !empty($_POST['item_name'])){
    		// sanitize form values
          $item_name  = sanitize_text_field( $_POST["item_name"] );
          $display_name  = sanitize_text_field( $_POST["display_name"] );
          $item_type  = sanitize_text_field( $_POST["item_type"] );
          $item_description  = sanitize_text_field( $_POST["item_description"] );
          $item_unit = sanitize_text_field( $_POST["item_unit"] );
          $item_price = sanitize_text_field( $_POST["item_price"] );
          $is_route_item = sanitize_text_field( $_POST["is_route_item"] );
          $is_private_item = sanitize_text_field( $_POST["is_private_item"] );
          $is_percentage = sanitize_text_field( $_POST["is_percentage"] );   
          $setting = array(
                			'item_name' => $item_name,
                            'display_name' => $display_name,
                            'item_type' => $item_type,
                			'item_description' => $item_description,
                			'item_unit' => str_replace(',','',$item_unit),
                			'is_route_item' => $is_route_item,
                			'is_private_item' => $is_private_item,
                			'item_price' => str_replace(',','',$item_price),
                			'is_percentage' => $is_percentage,
                		);

          $results = get_settings_items();
          $meta_data = unserialize($results->meta_data);
          $row_index = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $item_name));
          $meta_data[$row_index] = $setting;
          $wpdb->update(
            		'other_settings',
                	array(
                			'meta_data' => serialize($meta_data),
                		),
                    array(
            			'meta_key' => 'items'
            		)
            	);
          $msg = "Item Saved";
        }
 else if(isset($_POST['submit']) && empty($_POST['item_name'])) $msg = "Cannot save empty item";

?>
<style>
#dataTable{
    background-color: #dbf5e0;  width:100%;
}
#dataTable th{
  border-bottom: 3px solid;
  padding: 5px;
}
#dataTable td{
  border-bottom: 2px solid;
  padding: 5px;
}
.form-table th{
  width:150px;
}
.invalid{
  background:red;
}

</style>

<form method="post" action="" class="items-admin-form" style="display: block;overflow: hidden;clear: both;">
<div id="shipment-details">
  <div id="tags-wrapper"  style="width:90%;">
  <h1><?php echo "Items " ?> <?php esc_html_e('Settings', 'wpcargo')."--".$is_private_item; ?>
        <a style="float: right;" class="button" href="#" id="new_item" onclick="switch_links(this)">Add New Item</a></h1>
       <div>
        <?php
        ?>
           <table id="dataTable">
            <thead>
             <tr>
               <th style="width: 15%; text-align: left;"><?php esc_html_e('Item Name', 'wpcargo'); ?></th>
               <th style="width: 15%; text-align: left;"><?php esc_html_e('Display Name', 'wpcargo'); ?></th>
               <th style="width: 15%; text-align: left;"><?php esc_html_e('Type of Item', 'wpcargo'); ?></th>
               <th style="width: 30%; text-align: left;"><?php esc_html_e('Item Description', 'wpcargo'); ?></th>
               <th style="width: 10%; text-align: left;"><?php esc_html_e('Unit of Measure', 'wpcargo'); ?></th>
               <th style="width: 10%; text-align: left;"><?php esc_html_e('Category', 'wpcargo'); ?></th>
               <th style="width: 10%; text-align: left;"><?php esc_html_e('Category', 'wpcargo'); ?></th>
               <th style="width: 10%; text-align: left;"><?php esc_html_e('Unit Price', 'wpcargo'); ?></th>
               <th style="width: 10%; text-align: left;">Actions</th>
             </tr>
            </thead>
            <tbody>
             <?php
                $results = get_settings_items();
                $items = unserialize($results->meta_data);
                $i=0;
                if(!empty($items)) {
                 foreach ( $items as $key => $item_data ) {
                 ?>
                 <tr id="<?php echo $key; ?>">
                   <td><?php echo $item_data['item_name']; ?></td>
                   <td><?php echo $item_data['display_name']; ?></td>
                   <td><?php echo ($item_data['item_type']=="Income") ? "Service": "Discount"; ?></td>
                   <td><?php echo $item_data['item_description']; ?></td>
                   <td><?php echo $item_data['item_unit']; ?></td>
                   <?php if( isset($item_data['is_route_item']) && $item_data['is_route_item'] == 1) echo "<td>Route Item</td>";
                         else echo "<td>General</td>"; ?>
                   <?php if( isset($item_data['is_private_item']) && $item_data['is_private_item'] == 1) echo "<td>Private Item</td>";
                         else echo "<td>Public Item</td>"; ?>
                   <td><?php echo ($item_data['is_percentage'])? $item_data['item_price']."%" : "M".number_format((float)$item_data['item_price'], 2, '.', ','); ?></td>
                   <td>
                      <a href="#" id="edit_item" onclick="switch_links(this,<?php echo $i; ?>,'<?php echo $key; ?>')">Edit</a>
                      <?php if(!in_array($key,array("customsdeclarationfee", "bordertaxes", "collectionfee", "deliveryfee", "latebookingfee"))){   ?>
                      &nbsp;|&nbsp;<a href="#" style="color: red;" id="delete_item" onclick="switch_links(this,<?php echo $i; ?>,'<?php echo $key; ?>')">Del</a> <?php } ?>
                   </td>
                 </tr>
                 <?php  $i++;
               } }
             ?>
             </tbody>
           </table>
        </div>
       <input type="hidden" name="current_form" id="current_form" value="new_item_form" />
   </div> <!-- tags-wrapper -->
   <!-- The Modal -->
  <div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <span class="close" onclick="close_modal()">&times;</span>
        <h2>Modal Header</h2>
      </div>
      <div class="modal-body">
       <div id="form-fields-wrapper">
       <h1>Adding New Item</h1>
              <?php if(!empty($msg)) echo "<p style='background-color: #dbf5e0; padding: 6px; width:100%;'>".$msg."</p>"; ?>
      	<table class="form-table">
           <tr>
             <th scope="row"><?php esc_html_e('Type of Item', 'wpcargo'); ?>:</th>
             <td>
               <select id="item_type" name="item_type" style="width:100%;">
                   <option value="">Select One</option>
                   <option>Expenditure</option>
                   <option>Income</option>
               </select>
             </td>
           </tr>
      	   <tr>
      			<th scope="row"><?php esc_html_e( 'Item Name', 'wpcargo' ) ; ?></th>
      			<td> <input type="text" placeholder="Item Name" required name="item_name" id="item_name" style="width:80%;"/> </td>
           </tr>
      	   <tr>
      			<th scope="row"><?php esc_html_e( 'Display Name', 'wpcargo' ) ; ?></th>
      			<td> <input type="text" placeholder="Display Name" required name="display_name" id="display_name" style="width:80%;"/> </td>
           </tr>
      	   <tr scope="row">
      			<th scope="row"><?php esc_html_e( 'Item Description', 'wpcargo' ) ; ?></th>
      			<td>
                    <textarea id="item_description" name="item_description" placeholder="Item Description" style="width:80%;"></textarea>
      			</td>
      	   </tr>
           <tr>
             <th scope="row"><?php esc_html_e('Unit of Measure', 'wpcargo'); ?>:</th>
             <td><input type="text" placeholder="unit" id="item_unit" name="item_unit" value=""  style="width:80%;"> </td>
           </tr>
      	   <tr scope="row">
      			<th scope="row"><?php esc_html_e( 'Is Route Item?', 'wpcargo' ); ?></th>
      			<td>
                    <p style="font-size: 10px;"><input type="checkbox" id="is_route_item" name="is_route_item" onclick="toggle_price(this)" value="1"> ( <?php esc_html_e( 'Check if this cost varies per route.', 'wpcargo' ) ; ?> )</p>
      			</td>
      	   </tr>
      	   <tr scope="row">
      			<th scope="row"><?php esc_html_e( 'Is Private Item?', 'wpcargo' ) ; ?></th>
      			<td>
                    <p style="font-size: 10px;"><input type="checkbox" id="is_private_item" name="is_private_item" value="1"> ( <?php esc_html_e( 'Is this a hidden item?', 'wpcargo' ) ; ?> )</p>
      			</td>
      	   </tr>
           <tr>
             <th scope="row"><?php esc_html_e('Price of Item', 'wpcargo'); ?>:</th>
             <td> <input type="text" placeholder="Amount / Percentage" id="item_price" name="item_price" value="" style="width: 50%" >
                  <input type="checkbox" name="is_percentage" id="is_percentage">Is percentage
             </td>
           </tr>
      	</table>
      </div>
   </div>
   <div class="modal-footer">   <p id="dd"></p>
       <input class='button' type='submit' id="submit" name='submit' value='Save'>
       <a class="button" onclick="close_modal()">Close</a>
   </div>
  </div>
 </div>
 </div> 
</form>

<script>

jQuery(document).ready(function ($) {
    var table = $('#dataTable').DataTable({stateSave: true});
});

function switch_links(btn,row_index,item_id){
  if(btn.id=="new_item") {
       $("#current_form").val("new_item_form");
       $("#form-fields-wrapper h1").html("Editing Item");
       $('#item_name').attr('readonly',false);
       $("#form-fields-wrapper").find(':input').each(function() {
        switch(this.type) {
            case 'text':
                $(this).val('');
                break;
            case 'checkbox':
                this.checked = false;
          }
        });
       $("#form-fields-wrapper").find('select').each(function() {
             $(this).val('');
        });
     //$('#myModal #submit').value('Save Changes');
     //$('#myModal #submit').show();
     $('#myModal').show();
  }
  else if(btn.id=="edit_item") {
       $("#current_form").val("edit_item_form");
       $("#form-fields-wrapper h1").html("Editing Item");
       var item = $("#dataTable tr:eq("+(row_index+1)+")" );
       $('#item_name').val($(item).find('td:eq(0)').text());
       $('#item_name').attr('readonly',true);
       $('#display_name').val($(item).find('td:eq(1)').text());
       $('#item_type').val(($(item).find('td:eq(2)').text()=="Service")?"Income":"Expenditure");
       $('#item_description').val($(item).find('td:eq(3)').text());
       $('#item_unit').val($(item).find('td:eq(4)').text());
       var box_val1 = ($(item).find('td:eq(5)').text()=="Route Item") ? true: false;
       var box_val2 = ($(item).find('td:eq(6)').text()=="Private Item") ? true: false;
       $('#item_price').val($(item).find('td:eq(7)').text().replace('M', '').replace('%', ''));
       var is_percentage = ($(item).find('td:eq(7)').text().indexOf('%') > -1)? true : false;
       $('#is_percentage').prop("checked",is_percentage);
       $("#is_route_item").prop("checked", box_val1);
       $("#is_private_item").prop("checked", box_val2);
       $('#item_price').prop("readonly", box_val1);
       //$('#myModal #submit').value('Save Changes');
       //$('#myModal #submit').show();
       $('#myModal').show();
  }
  else if(btn.id=="delete_item") {
      var r = confirm("Are you sure you want to delete this?");
      if (r == true) {
         $.ajax({
                url: wpcargoAJAXHandler.ajax_url,
                type:"POST",
                data: {
                    action    : 'delete_item_action',
                    index : item_id,
                },
                success:function(data) {
                     $('#dataTable tr[id="'+item_id+'"]').each(function() {
                          $(this).fadeOut('slow', function(){
                            this.remove();
                             });
                     });
                },
                error: function(errorThrown){
                    alert('Error deleting. Please try again.');
                }

            });

      } else {

      }
  }
  else alert("none");
}

function toggle_price(elm){
  if(elm.checked){ $('#item_price').val(""); $('#item_price').prop("readonly", true); }
  else $('#item_price').prop("readonly", false);
}



</script>