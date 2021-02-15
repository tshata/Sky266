<?php
if (!defined('ABSPATH')){
	exit; // Exit if accessed directly
}
class WPCargo_Scripts{
	function __construct(){
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'wp_print_styles', array( $this, 'dequeue_scripts' ), 100 );
	}
	function frontend_scripts(){
		global $post;
		$page_url = get_the_permalink( );
		// Styles
        wp_register_style('wpcargo-dataTables', WPCARGO_PLUGIN_URL . 'assets/css/jquery.dataTables.min.css', array(), WPCARGO_VERSION );
	   	wp_enqueue_style( 'wpcargo-dataTables');  
	  	wp_register_style('wpcargo-custom-bootstrap-styles', WPCARGO_PLUGIN_URL . 'assets/css/main.css', array(), WPCARGO_VERSION );
		wp_register_style('wpcargo-fontawesome-styles', WPCARGO_PLUGIN_URL . 'assets/css/fontawesome.min.css', array(), WPCARGO_VERSION );
		wp_register_style('wpcargo-styles', WPCARGO_PLUGIN_URL . 'assets/css/wpcargo-style.css', array(), WPCARGO_VERSION );
		wp_register_style('wpcargo-datetimepicker', WPCARGO_PLUGIN_URL . 'admin/assets/css/jquery.datetimepicker.min.css', array(), WPCARGO_VERSION );
		wp_register_style('wpcargo-ship', WPCARGO_PLUGIN_URL . 'assets/css/ship.css', array(), WPCARGO_VERSION );
        wp_register_style('wpcargo-modal-css', WPCARGO_PLUGIN_URL . 'assets/css/modal.css', array(), WPCARGO_VERSION );
		wp_enqueue_style('wpcargo-custom-bootstrap-styles');
		wp_enqueue_style('wpcargo-fontawesome-styles');
		wp_enqueue_style('wpcargo-styles');
		wp_enqueue_style( 'wpcargo-datetimepicker');
		wp_enqueue_style( 'wpcargo-ship');
		wp_enqueue_style( 'wpcargo-modal-css' );

		wp_register_style('wpcargo-wpc-mp-admin', WPCARGO_PLUGIN_URL . 'admin/assets/css/wpc-mp-admin.css', array(), WPCARGO_VERSION );
		wp_enqueue_style( 'wpcargo-wpc-mp-admin');

		// Scripts
		$translation_array = array(
			'ajax_url'  => admin_url( 'admin-ajax.php' ),
			'pageURL' 	=> $page_url
		);
		wp_register_script( 'wpcargo-jquery-js', WPCARGO_PLUGIN_URL.'assets/js/jquery.js', array( 'jquery' ), WPCARGO_VERSION, false );
		wp_register_script( 'wpcargo-js', WPCARGO_PLUGIN_URL.'assets/js/wpcargo.js', array( 'jquery' ), WPCARGO_VERSION, false );
		wp_register_script( 'wpcargo-datetimepicker', WPCARGO_PLUGIN_URL . 'admin/assets/js/jquery.datetimepicker.full.min.js', array( 'jquery' ), WPCARGO_VERSION, false );
	    wp_localize_script( 'wpcargo-js', 'wpcargoAJAXHandler', $translation_array );
		wp_enqueue_script( 'jquery');
		wp_enqueue_script( 'wpcargo-jquery-js');
		wp_enqueue_script( 'wpcargo-js');
		wp_enqueue_script( 'wpcargo-datetimepicker' );

        wp_register_script( 'wpcargo-ship-js', WPCARGO_PLUGIN_URL.'assets/js/ship.js', array( 'jquery' ), WPCARGO_VERSION, true );
        wp_localize_script( 'wpcargo-ship-js', 'my_ajaxurl', admin_url( 'admin-ajax.php' ) );
		wp_enqueue_script( 'wpcargo-ship-js');


        wp_register_script( 'wpcargo-dataTables-js', WPCARGO_PLUGIN_URL.'assets/js/jquery.dataTables.min.js', array( 'jquery' ), WPCARGO_VERSION, false );
        wp_enqueue_script( 'wpcargo-dataTables-js');


        wp_register_script( 'wpcargo-repeater-js', WPCARGO_PLUGIN_URL.'admin/assets/js/jquery.repeater.js', array( 'jquery' ), WPCARGO_VERSION, false );
		wp_enqueue_script( 'wpcargo-repeater-js');
	}
	function dequeue_scripts(){
		// Dequeue Import / Export Add on Style
        wp_dequeue_style('wpc_import_export_css');
	}
}
new WPCargo_Scripts;
add_action('wp_head', function(){
	$options 		= get_option('wpcargo_option_settings');
	$baseColor 		= '#00A924';
	if( $options ){
		if( array_key_exists('wpcargo_base_color', $options) ){
			$baseColor = ( $options['wpcargo_base_color'] ) ? $options['wpcargo_base_color'] : $baseColor ;
		}
	}
	?>
	<style type="text/css">
		:root {
		  --wpcargo: <?php echo $baseColor; ?>;
		}
	</style>
	<?php
});