<?php
/*
 * Plugin Name: Courier-Plugin
 * Plugin URI: http://tech-corp.co.ls/
 * Description: This is a WordPress plug-in designed to provide ideal technology solution for your Cargo and Courier Operations. Whether you are an exporter, freight forwarder, importer, supplier, customs broker, overseas agent, or warehouse operator, Courier-Plugin helps you to increase the visibility, efficiency, and quality services of your cargo and shipment business.
 * Author: <a href="http://www.tech-corp.co.ls/">Tech-Corp</a>
 * Text Domain: courier_plugin
 * Domain Path: /languages
 * Version: 1.0.0
 */
/*
	Courier-Plugin - Track and Trace Plugin
*/
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
	
	
//* Defined constant
define( 'WPCARGO_TEXTDOMAIN', 'wpcargo' );
define( 'WPCARGO_VERSION', '1.0.0' );
define( 'WPCARGO_FILE_DIR', __FILE__  );
define( 'WPCARGO_PLUGIN_URL', plugin_dir_url( WPCARGO_FILE_DIR ) );
define( 'WPCARGO_PLUGIN_PATH', plugin_dir_path( WPCARGO_FILE_DIR ) );
//** Include files
//** Admin
require_once( WPCARGO_PLUGIN_PATH.'admin/wpc-admin.php' );
require_once( WPCARGO_PLUGIN_PATH.'admin/classes/class-wpcargo.php' );
//** Frontend  
require_once( WPCARGO_PLUGIN_PATH.'/includes/packages.php' );
require_once( WPCARGO_PLUGIN_PATH.'/classes/class-wpc-scripts.php' );
require_once( WPCARGO_PLUGIN_PATH.'/classes/class-wpc-shortcode.php' );
require_once( WPCARGO_PLUGIN_PATH.'/classes/class-wpc-print.php' );
//** Load text Domain
add_action( 'plugins_loaded', array( 'WPC_Admin','wpcargo_load_textdomain' ) );
//** Run when plugin installation
//** Add user role
register_activation_hook( WPCARGO_FILE_DIR, array( 'WPC_Admin', 'add_user_role' ) );
register_deactivation_hook( WPCARGO_FILE_DIR, array( 'WPC_Admin', 'remove_user_role' ) );
//** Create track page
register_activation_hook( WPCARGO_FILE_DIR, array( 'WPC_Admin', 'add_wpc_custom_pages' ) );