<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class WPCargo_Admin_Settings{
	private $text_domain = 'wpcargo';
	function __construct(){
		add_action('admin_menu', array( $this, 'add_settings_menu' ), 10 );
		//call register settings function
		add_action( 'admin_init', array( $this,'register_wpcargo_option_settings') );
	}
	public function add_settings_menu(){
		global $wpcargo;
        add_menu_page(
			'Schedules',
			'Schedules',
			'manage_options',
			'collection-schedules',
			array( $this, 'add_collection_schedules_menu_callback' ),
			'dashicons-calendar-alt',
			6
		);
        add_menu_page(
			'Trips',
			'Trips',
			'manage_options',
			'trips-settings',
			array( $this, 'add_trips_settings_menu_callback' ),
			'dashicons-calendar-alt',
			6
		);
        add_menu_page(
			wpcargo_brand_name(),
			wpcargo_brand_name(),
			'manage_options',
			'wpcargo-settings',
			array( $this, 'add_settings_menu_callback' ),
			'dashicons-book-alt',
			7
		);
		add_submenu_page(
			'wpcargo-settings',
			wpcargo_general_settings_label(),
			wpcargo_general_settings_label(),
			'manage_options',
			'wpcargo-settings'
		);
        add_submenu_page(
			'wpcargo-settings',
			'Countries & Cities',
			'Countries & Cities',
			'manage_options',
			'cities-settings',
			array( $this, 'add_cities_settings_menu_callback' )
		);
        add_submenu_page(
			'wpcargo-settings',
			'Items',
			'Items',
			'manage_options',
			'items-settings',
			array( $this, 'add_items_settings_menu_callback' )
		);
        add_submenu_page(
			'wpcargo-settings',
			'Pricing',
			'Pricing',
			'manage_options',
			'pricing-settings',
			array( $this, 'add_pricing_settings_menu_callback' )
		);

	}
	function register_wpcargo_option_settings() {
		//register our settings
	   	register_setting( 'wpcargo_option_settings_group', 'wpcargo_option_settings' );
		register_setting( 'wpcargo_option_settings_group', 'wpcargo_page_settings' );
		register_setting( 'wpcargo_option_settings_group', 'wpcargo_label_header' );
		register_setting( 'wpcargo_option_settings_group', 'wpcargo_user_timezone' );
		register_setting( 'wpcargo_option_settings_group', 'wpcargo_title_numdigit' );
		register_setting( 'wpcargo_option_settings_group', 'wpcargo_title_suffix' );
		register_setting( 'wpcargo_option_settings_group', 'wpcargo_tax' );
	}
	public function add_settings_menu_callback() {
		global $wpcargo;
		$options 					= get_option('wpcargo_option_settings');
		$page_options 				= get_option('wpcargo_page_settings');
		$wpcargo_title_numdigit 	= $wpcargo->number_digit;
		$wpcargo_title_suffix 		= $wpcargo->suffix;
		$tax 	= $wpcargo->tax;
		?>
		<div class="wpcargo-settings">
		  <div class="wrap">
		    <h1><?php echo wpcargo_brand_name(); ?> <?php esc_html_e('Settings', 'wpcargo'); ?></h1>
		    <?php
				require_once( WPCARGO_PLUGIN_PATH.'admin/templates/admin-navigation.tpl.php' );
				require_once( WPCARGO_PLUGIN_PATH.'admin/templates/settings-option.tpl.php' );
			?>
			 </div>
		</div>



		<?php
	}
  function add_items_settings_menu_callback(){   ?>
		<div class="wpcargo-settings">
        <?php
				require_once( WPCARGO_PLUGIN_PATH.'admin/templates/admin-navigation.tpl.php' );
				require_once( WPCARGO_PLUGIN_PATH.'admin/templates/items_settings.tpl.php' );
			?>
		</div>
		<?php
  }
  function add_cities_settings_menu_callback(){
		?>
		<div class="wpcargo-settings">
        <?php
				require_once( WPCARGO_PLUGIN_PATH.'admin/templates/admin-navigation.tpl.php' );
				require_once( WPCARGO_PLUGIN_PATH.'admin/templates/countries_settings.tpl.php' );
			?>
		</div>
		<?php
  }
  function add_pricing_settings_menu_callback(){
		?>
		<div class="wpcargo-settings">
		    <?php
				require_once( WPCARGO_PLUGIN_PATH.'admin/templates/admin-navigation.tpl.php' );
				require_once( WPCARGO_PLUGIN_PATH.'admin/templates/pricing_settings.tpl.php' );
			?>
		</div>
		<?php
  }
  function add_trips_settings_menu_callback(){   ?>
		<div class="wpcargo-settings">
        <?php
				require_once( WPCARGO_PLUGIN_PATH.'admin/templates/trips_settings.tpl.php' );
			?>
		</div>
		<?php
  }

  function add_collection_schedules_menu_callback(){   ?>
		<div class="wpcargo-settings">
        <?php
				require_once( WPCARGO_PLUGIN_PATH.'admin/templates/schedules_settings.tpl.php' );
			?>
		</div>
		<?php
  }


}
new WPCargo_Admin_Settings;