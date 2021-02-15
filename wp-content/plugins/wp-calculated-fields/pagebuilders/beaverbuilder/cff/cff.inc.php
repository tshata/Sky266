<?php
require_once CP_CALCULATEDFIELDSF_BASE_PATH.'/pagebuilders/beaverbuilder/cff/cff/cff.php';

// Get the forms list
global $wpdb;
$options = array();
$default = '';

$rows = $wpdb->get_results( "SELECT id, form_name FROM ".$wpdb->prefix.CP_CALCULATEDFIELDSF_FORMS_TABLE );
foreach ($rows as $item)
{
	$options[$item->id] = $item->form_name;
	if(empty($default)) $default = $item->id;
}

FLBuilder::register_module(
	'CFFBeaver',
	array(
		'cff-form-tab' => array(
			'title'	=> __('Select the form and enter the additional attributes', 'wp-calculated-fields'),
			'sections' => array(
				'cff-form-section' => array(
					'title' => __('Form information', 'wp-calculated-fields'),
					'fields'	=> array(
						'form_id' => array(
							'type' => 'select',
							'label' => __('Select form', 'wp-calculated-fields'),
							'options' => $options,
							'default' => $default,
						),
						'class_name' => array(
							'type' => 'text',
							'label' => __('Class name', 'wp-calculated-fields')
						),
						'attributes' => array(
							'type' => 'text',
							'label' => __('Additional attributes', 'wp-calculated-fields')
						),
					)
				)
			)
		)
	)
);
