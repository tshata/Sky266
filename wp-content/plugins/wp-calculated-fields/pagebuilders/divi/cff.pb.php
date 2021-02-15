<?php
class CFF_DIVI extends ET_Builder_Module
{

	public $slug = 'cff_divi';
	public $vb_support = 'on';

	public function init()
	{
		$this->name = esc_html__('Wordpress Calculated Fields', 'wp-calculated-fields');
		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Form', 'wp-calculated-fields' ),
				),
			),
		);
	}

	public function get_fields()
	{
		global $wpdb;
		$options = array();
		$default = '';

		$rows = $wpdb->get_results( "SELECT id, form_name FROM ".$wpdb->prefix.CP_CALCULATEDFIELDSF_FORMS_TABLE );
		foreach ($rows as $item)
		{
			$options[$item->id] = $item->form_name;
			if(empty($default)) $default = $item->id;
		}

		return array(
			'cff_form_id'     => array(
				'label'           => esc_html__( 'Select form', 'wp-calculated-fields' ),
				'type'            => 'select',
				'options'		  => $options,
				'default'		  => $default,
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Select the form.', 'wp-calculated-fields' ),
				'toggle_slug'     => 'main_content',
			),
			'cff_class_name'     => array(
				'label'           => esc_html__( 'Class name', 'wp-calculated-fields' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
			),
			'cff_attributes'     => array(
				'label'           => esc_html__( 'Additional attributes', 'wp-calculated-fields' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => 'attr1="value attr1" attr1="value attr1"',
				'toggle_slug'     => 'main_content',
			),
		);
	}

	public function render($unprocessed_props, $content = null, $render_slug)
	{
		$output = '';
		$form = @intval($this->props['cff_form_id']);
		if(!empty($form))
		{
			$output = '[CP_CALCULATED_FIELDS id="'.$form.'"';
			$class_name = sanitize_text_field($this->props['cff_class_name']);
			if(!empty($class_name)) $output .= ' class="'.esc_attr($class_name).'"';

			$attributes = sanitize_text_field($this->props['cff_attributes']);
			if(!empty($attributes)) $output .= ' '.$attributes;

			$output .= ']';
		}
		return do_shortcode($output);
	}
}

new CFF_DIVI;
