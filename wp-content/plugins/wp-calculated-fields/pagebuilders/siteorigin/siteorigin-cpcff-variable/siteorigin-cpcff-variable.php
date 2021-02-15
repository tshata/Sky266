<?php
/*
Widget Name: Wordpress Calculated Fields Variable Shortcode
Description: Insert a javascript generator shortcode on page.
Documentation: https://cff.dwbooster.com/documentation#javascript-variables
*/

class SiteOrigin_CFF_Variable_Shortcode extends SiteOrigin_Widget
{
	function __construct()
	{
		parent::__construct(
			'siteorigin-cff-variable-shortcode',
			__('Wordpress Calculated Fields, Variable Shortcode', 'wp-calculated-fields'),
			array(
				'description' 	=> __('Shortcode to generate a javascript variable from the url parameters (GET or POST), session variables, cookies, or define it directly', 'wp-calculated-fields'),
				'panels_groups' => array('wp-calculated-fields'),
				'help'        	=> 'https://cff.dwbooster.com/documentation#javascript-variables',
			),
			array(),
			array(
				'name' => array(
					'type' => 'text',
					'label' => __( 'Variable name', 'wp-calculated-fields' ),
					'default' => '',
				),
				'from' => array(
					'type' => 'select',
					'label' => __('Generate variable from', 'wp-calculated-fields' ),
					'default' => '',
					'options' => array(
						'' => __( 'Any source', 'wp-calculated-fields' ),
						'from="get"' => __( 'GET parameter', 'wp-calculated-fields' ),
						'from="post"' => __( 'POST parameter', 'wp-calculated-fields' ),
						'from="session"' => __( 'Session variable', 'wp-calculated-fields' ),
						'from="cookie"' => __( 'Cookie', 'wp-calculated-fields' ),
					)
				),
				'default_value' => array(
					'type' => 'text',
					'label' => __( 'Default value (used when variables are generated from a source)', 'wp-calculated-fields' ),
					'default' => '',
				),
				'value' => array(
					'type' => 'text',
					'label' => __( 'Value (value of the variable when it is generated directly)', 'wp-calculated-fields' ),
					'default' => '',
				),
			),
			plugin_dir_path(__FILE__)
		);
	} // End __construct

	function get_template_name($instance)
	{
        return 'siteorigin-cff-variable-shortcode';
    } // End get_template_name

    function get_style_name($instance)
	{
        return '';
    } // End get_style_name

} // End Class SiteOrigin_CFF_Variable_Shortcode

// Registering the widget
siteorigin_widget_register('siteorigin-cff-variable-shortcode', __FILE__, 'SiteOrigin_CFF_Variable_Shortcode');