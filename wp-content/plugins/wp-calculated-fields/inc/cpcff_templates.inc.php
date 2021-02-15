<?php
/**
 * Templates operations: CPCFF_TEMPLATES class
 *
 * Implements the access to forms templates.
 *
 * @package CFF.
 * @since 1.0.175
 */

if(!class_exists('CPCFF_TEMPLATES'))
{
	class CPCFF_TEMPLATES
	{
		/**
		 * Flag to know if the templates directory was checked and the templates loaded
		 * Metaclass property.
		 *
		 * @var bool $_are_templates_loaded default false
		 */
		private static $_are_templates_loaded = false;

		/**
		 * Templates list
		 * Metaclass property.
		 *
		 * @var array $_templates_list default empty array
		 */
		private static $_templates_list = array();

		/**
		 * List of templates whose resources were enqueued or sent to the browser
		 * Metaclass property.
		 *
		 * @var array $_enqueued_list default empty array
		 */
		private static $_enqueued_list = array();

		/**
		 * Loads the templates list
		 *
		 * Walks through the "templates" directory for reading and parsing the corresponding config.ini files to identify the
		 * the styles files, the javascript files, the templates' thumbnails, and the prefixes to identify the templates
		 * and grouping the CSS rules to apply for the templates.
		 *
		 * @return array of associative arrays with the templates attributess.
		 */
		public static function load_templates()
		{
			if( !self::$_are_templates_loaded )
			{
				self::$_are_templates_loaded = true;
				$tpls_dir = dir( CP_CALCULATEDFIELDSF_BASE_PATH.'/templates' );
				while( false !== ( $entry = $tpls_dir->read() ) )
				{
					if (
						$entry != '.' &&
						$entry != '..' &&
						is_dir( $tpls_dir->path.'/'.$entry ) &&
						file_exists( $tpls_dir->path.'/'.$entry.'/config.ini' )
					)
					{
						if(
							( $ini_array = parse_ini_file( $tpls_dir->path.'/'.$entry.'/config.ini' ) ) != false ||
							( $ini_array = parse_ini_string( file_get_contents( $tpls_dir->path.'/'.$entry.'/config.ini' ) ) ) != false
						)
						{
							if(
								isset($ini_array[ 'prefix' ]) &&
								($prefix = trim($ini_array[ 'prefix' ])) != ''
							)
							{
								// Url to the CSS file
								if( !empty( $ini_array[ 'file' ] ) ) $ini_array[ 'file' ] = plugins_url( 'templates/'.$entry.'/'.$ini_array[ 'file' ], CP_CALCULATEDFIELDSF_MAIN_FILE_PATH );

								// Url to the JS file
								if( !empty( $ini_array[ 'js' ] ) ) $ini_array[ 'js' ] = plugins_url( 'templates/'.$entry.'/'.$ini_array[ 'js' ], CP_CALCULATEDFIELDSF_MAIN_FILE_PATH );

								// Url to the thumbnail file
								if( !empty( $ini_array[ 'thumbnail' ] ) ) $ini_array[ 'thumbnail' ] = plugins_url( 'templates/'.$entry.'/'.$ini_array[ 'thumbnail' ], CP_CALCULATEDFIELDSF_MAIN_FILE_PATH );

								// Required attribute to identify the template
								// and it is used as super-class-name for grouping styles to apply for the template
								self::$_templates_list[ $prefix ] = $ini_array;
							}
						}
					}
				}
			}

			return self::$_templates_list;
		} // End load_templates

		/**
		 * Enqueues or sends to the browser the template's resources (CSS and JS files)
		 *
		 * Loads the templates if they have not been loaded previously.
		 * Checks if the resources of current template were enqueued previously to prevent to load the resources by duplicated.
		 * Checks if the resources should be enqueued, or sent to the browser directly.
		 *
		 * @return void.
		 */
		public static function enqueue_template_resources( $template )
		{
			$template = trim($template);
			$templates = self::load_templates();

			if(in_array($template, self::$_enqueued_list)) return;
			self::$_enqueued_list[] = $template; // The template's resources were enqueued

			if(!empty($templates[$template]))
			{
				$template_info = $templates[$template];

				if(
					!empty($template_info['file']) &&
					($css = trim($template_info['file'])) != ''
				)
				{
					if($GLOBALS['CP_CALCULATEDFIELDSF_DEFAULT_DEFER_SCRIPTS_LOADING'])
					{
						 wp_enqueue_style( 'cpcff_template_css'.$template,  $css, array(), CP_CALCULATEDFIELDSF_VERSION );
					}
					else
					{
						$css .= (strpos($css,'?') === false) ? '?' : '&';
						print '<link href="'.esc_attr(esc_url($css)).'ver='.CP_CALCULATEDFIELDSF_VERSION.'" type="text/css" rel="stylesheet" property="stylesheet" />';
					}
				}

				if(
					!empty($template_info['js']) &&
					($js = trim($template_info['js'])) != ''
				)
				{
					if($GLOBALS['CP_CALCULATEDFIELDSF_DEFAULT_DEFER_SCRIPTS_LOADING'])
					{
						 wp_enqueue_script( 'cpcff_template_js'.$template,  $js, array(), CP_CALCULATEDFIELDSF_VERSION );
					}
					else
					{
						$js .= (strpos($js,'?') === false) ? '?' : '&';
						print '<script src="'.esc_attr(esc_url($js)).'ver='.CP_CALCULATEDFIELDSF_VERSION.'"></script>';
					}
				}
			}
		} // End enqueue_template_resources

	} // End CPCFF_TEMPLATES
}
?>