<?php
require_once CP_CALCULATEDFIELDSF_BASE_PATH.'/pagebuilders/beaverbuilder/cffvar/cffvar/cffvar.php';

FLBuilder::register_module(
	'CFFVarBeaver',
	array(
		'cff-var-tab' => array(
			'title'	=> __('Generate variable', 'wp-calculated-fields'),
			'sections' => array(
				'cff-var-section' => array(
					'title' => __('Variable attributes', 'wp-calculated-fields'),
					'fields'	=> array(
						'var_name' => array(
							'type' => 'text',
							'label' => __('Enter the variable name', 'wp-calculated-fields'),
							'required' => true,
						),
						'default_value' => array(
							'type' => 'text',
							'label' => __('Enter the default value', 'wp-calculated-fields')
						),
						'from' => array(
							'type' => 'select',
							'label' => __('Generate variable from', 'wp-calculated-fields'),
							'options' => array(
								'directly' => __('Directly', 'wp-calculated-fields'),
								'get' => __('GET parameters', 'wp-calculated-fields'),
								'post' => __('POST parameters', 'wp-calculated-fields'),
								'session' => __('SESSION variables', 'wp-calculated-fields'),
								'cookies' => __('COOKIES variables', 'wp-calculated-fields'),
							)
						),
					)
				)
			)
		)
	)
);