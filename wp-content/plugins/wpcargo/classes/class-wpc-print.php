<?php
if (!defined('ABSPATH')){
	exit; // Exit if accessed directly
}
class WPCargo_Print {
	public function __construct(){
		add_action( 'wpcargo_print_btn', array($this, 'wpcargo_print_results') );
	}
	function wpcargo_print_results() {
	  $post_id = isset($_GET['id']) ? $_GET['id']: '';
		?>
		<script>
			function wpcargo_print(wpcargo_class) {
				var printContents = document.getElementById(wpcargo_class).innerHTML;
				var originalContents = document.body.innerHTML;
				document.body.innerHTML = printContents;
				window.print();
				document.body.innerHTML = originalContents;
				location.reload(true);
			}
		</script>
		<style>
			a:link:after, a:visited:after {
				content: "";
			}
			.noprint {
				display: none !important;
			}
			a:link:after, a:visited:after {
				display: none;
				content: "";
			}
		</style>
		<?php if( is_admin() ): ?>
			<div class="wpcargo-print-btn">

            <a class="button button-primary" id="duplicate" type="button" href="<?php echo wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ); ?>"><span class="dashicons dashicons-edit"></span> <?php echo apply_filters( 'wpcargo_edit_shipment_label', esc_html__( 'Clone', 'wpcargo') ); ?></a>


            <?php
                  $wpcargo_shipments_update = get_post_meta( $post_id, 'wpcargo_shipments_update', true );
                  if( strpos($wpcargo_shipments_update, "Collection Successful") == false && strpos($wpcargo_shipments_update, "Delivery Successful") == false)
                    { ?>
				      <a class="button button-primary" id="edit" type="button" href="<?php echo "post.php?post=".$post_id."&action=edit"; ?>"><span class="dashicons dashicons-edit"></span> <?php echo apply_filters( 'wpcargo_edit_shipment_label', esc_html__( 'Edit', 'wpcargo') ); ?></a>
                   <?php } ?>
                <a class="button button-primary" type="button" onclick="wpcargo_print('wpcargo-result-print')"><span class="dashicons dashicons-media-spreadsheet"></span> <?php echo apply_filters( 'wpcargo_print_invoice_label', esc_html__( 'Print', 'wpcargo') ); ?></a>
			</div>
		<?php else: ?>
			<div class="wpcargo-print-btn">
				<a class="wpcargo-print wpcargo-btn wpcargo-btn-sm wpcargo-btn-primary" type="button" onclick="wpcargo_print('wpcargo-result-print')"><span class="fa fa-print"></span> <?php echo apply_filters( 'wpcargo_print_invoice_label', esc_html__( 'Print', 'wpcargo') ); ?></a>
			</div>
		<?php endif; ?>
		<?php
	}
}
$wpcargo_print = new WPCargo_Print();