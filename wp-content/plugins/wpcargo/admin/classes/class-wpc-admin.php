<?php
class WPC_Admin{
    	public static function add_user_role() {
	 add_role('wpcargo_agent', 'Agent', array(
			'read' => true,
			'create_posts' => false,
			'edit_posts' => true,
			'edit_others_posts' => true,
			'edit_published_posts' => true,
			'delete_posts' => false
		));

		add_role('wpc_bookings_cordinator', 'Bookings Cordinator', array(
			'read' => true,
			'create_posts' => true,
			'edit_posts' => true,
			'edit_others_posts' => true,
			'edit_published_posts' => true,
			'delete_posts' => true
		));
		add_role('wpcargo_manager', 'Manager', array(
			'read' => true,
			'create_posts' => true,
			'edit_posts' => true,
			'edit_others_posts' => true,
			'edit_published_posts' => true,
			'delete_posts' => true
		));

		add_role('wpcargo_driver', 'Driver', array(
			'read' => true,
			'edit_others_posts' => true,
			'edit_published_posts' => true,
		));

		add_role('wpcargo_client', 'Client', array(
	        'read' => true,
			'edit_posts' => true,
			'edit_others_posts' => false,
			'edit_published_posts' => false,
			'delete_posts' => false
	    ));   
     }
	public static function remove_user_role() {
		remove_role( 'wpcargo_agent' );
		remove_role( 'wpc_bookings_cordinator' );
		remove_role( 'wpcargo_manager' );
		remove_role( 'wpcargo_client' );
		remove_role( 'wpcargo_driver' );
    }
	public static function add_wpc_custom_pages() {
		if (get_page_by_title('Track') == NULL) {
			$form    = array(
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_author' => 1,
				'post_date' => date('Y-m-d H:i:s'),
				'post_name' => 'track-form',
				'post_status' => 'publish',
				'post_title' => 'Track',
				'post_type' => 'page',
				'post_content' => '[wpcargo_trackform]'
			);
			$trackf  = wp_insert_post($form, false);
		}
		if (get_page_by_title('Account') == NULL) {
			$form    = array(
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_author' => 1,
				'post_date' => date('Y-m-d H:i:s'),
				'post_name' => 'my-account',
				'post_status' => 'publish',
				'post_title' => 'Account',
				'post_type' => 'page',
				'post_content' => '[wpcargo_account]'
			);
			$account  = wp_insert_post($form, false);
		}
		if (get_page_by_title('Request Quote') == NULL) {
			$form    = array(
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_author' => 1,
				'post_date' => date('Y-m-d H:i:s'),
				'post_name' => 'request-quote',
				'post_status' => 'publish',
				'post_title' => 'Request Quote',
				'post_type' => 'page',
				'post_content' => '[wpcargo_quotes]'
			);
			$quote  = wp_insert_post($form, false);
		}
	 }
	 static function wpcargo_load_textdomain() {
		 load_plugin_textdomain( 'wpcargo', false, '/wpcargo/languages' );
	 }
}