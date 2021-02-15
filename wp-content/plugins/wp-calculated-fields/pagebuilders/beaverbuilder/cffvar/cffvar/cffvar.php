<?php
class CFFVarBeaver extends FLBuilderModule {
    public function __construct()
    {
		$modules_dir = dirname(__FILE__).'/';
		$modules_url = plugins_url( '/', __FILE__ ).'/';

        parent::__construct(array(
            'name'            => __( 'Create variable', 'wp-calculated-fields' ),
            'description'     => __( 'Create javascript variable', 'fl-builder' ),
            'group'           => __( 'Wordpress Calculated Fields', 'wp-calculated-fields' ),
            'category'        => __( 'Wordpress Calculated Fields', 'wp-calculated-fields' ),
            'dir'             => $modules_dir,
            'url'             => $modules_url,
            'partial_refresh' => true,
        ));

		$this->add_css('cff-var', $this->url . 'css/cffvar.css');
    }
}