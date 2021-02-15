<?php
/*
Plugin Name: Form Plugin
Description: Simple Form  that saves into the database
Version: 1.0
Author: Tech Corp
Author URI: http://tech-corp.co.ls
*/

 function create_plugin_database_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'employees';
	$sql = "CREATE TABLE $table_name (
		employee_id mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
		employee_name varchar(50) NOT NULL,
		employee_title varchar(50) NOT NULL,
		PRIMARY KEY  (employee_id)
		);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

register_activation_hook( __FILE__, 'create_plugin_database_table' );



function html_form_code() {
  /*	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
	echo 'Your Name (required) <br/>';
	echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Email (required) <br/>';
	echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Subject (required) <br/>';
	echo '<input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your Message (required) <br/>';
	echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<p><input type="submit" name="cf-submitted" value="Send"></p>';
	echo '</form>'; */ ?>

                <form id="contactForm" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="POST">
                  	<div class="row">
                  	 <div class="col-sm-6 col-xs-12">
                    	<div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                          <label for="name">Name*</label>
                          <input id="name" name="name" type="text" class="form-control"  required="" placeholder="">
                        </div>
                     	</div><!-- /.col -->
                     	<div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                          <label for="email">E-mail*</label>
                          <input id="email" name="email" type="email" class="form-control" required="" placeholder="">
                        </div>
                      </div><!-- /.col -->
                    	<div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                          <label for="name">Organisation</label>
                          <input id="organisation" name="organisation" type="text" class="form-control"  placeholder="">
                        </div>
                     	</div><!-- /.col -->
                      <div class="col-sm-6 col-xs-12">
                        <div class="form-group">
                          <label for="url">Phone</label>
                          <input id="url" name="url" type="text" class="form-control" placeholder="">
                        </div>
                      </div><!-- /.col -->
                    </div><!-- /.row -->
                    <div class="col-sm-6 col-xs-12">
                        <div class="col-xs-12">
                          <div class="form-group">
                              <label>Message</label>
                              <textarea id="message" name="message" class="form-control" rows="6" required="" placeholder=""></textarea>
                          </div>
                    	</div><!-- /.row -->

                    </div>
                    </div>

                  	<div class="form-group submit-btn">
                 	 	<button type="submit" class="btn btn-primary">Submit Form</button>
                  	</div>
                </form>
    <?php
}

function save_form_data() {

     global $wpdb;

	// if the submit button is clicked, send the email
  	if ( isset( $_POST['cf-submitted'] ) ) {
		// sanitize form values
   	    $name    = sanitize_text_field( $_POST["cf-name"] );
		$email   = sanitize_email( $_POST["cf-email"] );
		$subject = sanitize_text_field( $_POST["cf-subject"] );
		$message = esc_textarea( $_POST["cf-message"] );

    	$table_name = $wpdb->prefix . 'employees';

    	$wpdb->insert(
    		$table_name,
    		array(
    			'employee_id' => '',
    			'employee_name' => $name,
    			'employee_title' => $message,
    		)
    	);
	}
}

function form_shortcodes() {
	ob_start();
	save_form_data();
	html_form_code();

	return ob_get_clean();
}

add_shortcode( 'form_plugin', 'form_shortcodes' );



?>