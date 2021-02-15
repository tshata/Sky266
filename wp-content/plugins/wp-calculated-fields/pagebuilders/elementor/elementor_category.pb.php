<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Register the categories
Plugin::$instance->elements_manager->add_category(
	'wp-calculated-fields-cat',
	array(
		'title'=>'Wordpress Calculated Fields',
		'icon' => 'fa fa-plug'
	),
	2 // position
);
