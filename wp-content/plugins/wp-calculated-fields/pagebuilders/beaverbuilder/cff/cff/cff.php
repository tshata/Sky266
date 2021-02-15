<?php
class CFFBeaver extends FLBuilderModule {
    public function __construct()
    {
		$modules_dir = dirname(__FILE__).'/';
		$modules_url = plugins_url( '/', __FILE__ ).'/';

        parent::__construct(array(
            'name'            => __( 'Wordpress Calculated Fields', 'wp-calculated-fields' ),
            'description'     => __( 'Inserts a form', 'fl-builder' ),
            'group'           => __( 'Wordpress Calculated Fields', 'wp-calculated-fields' ),
            'category'        => __( 'Wordpress Calculated Fields', 'wp-calculated-fields' ),
            'dir'             => $modules_dir,
            'url'             => $modules_url,
            'partial_refresh' => true
        ));

		$this->add_js('cff-beaver-form', $this->url . 'js/settings.js', array('jquery'));
    }
}
