<?php
/**
 * Dazzling functions and definitions
 *
 * @package dazzling
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
  $content_width = 730; /* pixels */
}

/**
 * Set the content width for full width pages with no sidebar.
 */
function dazzling_content_width() {
  if ( is_page_template( 'page-fullwidth.php' ) || is_page_template( 'front-page.php' ) ) {
    global $content_width;
    $content_width = 1110; /* pixels */
  }
}
add_action( 'template_redirect', 'dazzling_content_width' );

if ( ! function_exists( 'dazzling_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function dazzling_setup() {

  /*
   * Make theme available for translation.
   * Translations can be filed in the /languages/ directory.
   * If you're building a theme based on Dazzling, use a find and replace
   * to change 'dazzling' to the name of your theme in all the template files
   */
  load_theme_textdomain( 'dazzling', get_template_directory() . '/languages' );

  // Add default posts and comments RSS feed links to head.
  add_theme_support( 'automatic-feed-links' );

  /*
   * Enable support for Post Thumbnails on posts and pages.
   *
   * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
   */
  add_theme_support( 'post-thumbnails' );

  add_image_size( 'dazzling-featured', 730, 410, true );
  add_image_size( 'tab-small', 60, 60 , true); // Small Thumbnail

  // This theme uses wp_nav_menu() in one location.
  register_nav_menus( array(
    'primary'      => __( 'Primary Menu', 'dazzling' ),
    'footer-links' => __( 'Footer Links', 'dazzling' ) // secondary menu in footer
  ) );

  // Enable support for Post Formats.
  add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

  // Setup the WordPress core custom background feature.
  add_theme_support( 'custom-background', apply_filters( 'dazzling_custom_background_args', array(
    'default-color' => 'ffffff',
    'default-image' => '',
  ) ) );

  /*
   * Let WordPress manage the document title.
   * By adding theme support, we declare that this theme does not use a
   * hard-coded <title> tag in the document head, and expect WordPress to
   * provide it for us.
   */
  add_theme_support( 'title-tag' );
}
endif; // dazzling_setup
add_action( 'after_setup_theme', 'dazzling_setup' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function dazzling_widgets_init() {
  register_sidebar( array(
    'name'          => __( 'Sidebar', 'dazzling' ),
    'id'            => 'sidebar-1',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ) );
  register_sidebar(array(
    'id'            => 'home-widget-1',
    'name'          => __( 'Homepage Widget 1', 'dazzling' ),
    'description'   => __( 'Displays on the Home Page', 'dazzling' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widgettitle">',
    'after_title'   => '</h3>',
  ));

  register_sidebar(array(
    'id'            => 'home-widget-2',
    'name'          =>  __( 'Homepage Widget 2', 'dazzling' ),
    'description'   => __( 'Displays on the Home Page', 'dazzling' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widgettitle">',
    'after_title'   => '</h3>',
  ));

  register_sidebar(array(
    'id'            => 'home-widget-3',
    'name'          =>  __( 'Homepage Widget 3', 'dazzling' ),
    'description'   =>  __( 'Displays on the Home Page', 'dazzling' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widgettitle">',
    'after_title'   => '</h3>',
  ));

  register_sidebar(array(
    'id'            => 'footer-widget-1',
    'name'          =>  __( 'Footer Widget 1', 'dazzling' ),
    'description'   =>  __( 'Used for footer widget area', 'dazzling' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widgettitle">',
    'after_title'   => '</h3>',
  ));

  register_sidebar(array(
    'id'            => 'footer-widget-2',
    'name'          =>  __( 'Footer Widget 2', 'dazzling' ),
    'description'   =>  __( 'Used for footer widget area', 'dazzling' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widgettitle">',
    'after_title'   => '</h3>',
  ));

  register_sidebar(array(
    'id'            => 'footer-widget-3',
    'name'          =>  __( 'Footer Widget 3', 'dazzling' ),
    'description'   =>  __( 'Used for footer widget area', 'dazzling' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widgettitle">',
    'after_title'   => '</h3>',
  ));


  register_widget( 'dazzling_social_widget' );
  register_widget( 'dazzling_popular_posts_widget' );
}
add_action( 'widgets_init', 'dazzling_widgets_init' );

include(get_template_directory() . "/inc/widgets/widget-popular-posts.php");
include(get_template_directory() . "/inc/widgets/widget-social.php");


/**
 * Enqueue scripts and styles.
 */
function dazzling_scripts() {

  wp_enqueue_style( 'dazzling-bootstrap', get_template_directory_uri() . '/inc/css/bootstrap.min.css' );

  wp_enqueue_style( 'dazzling-icons', get_template_directory_uri().'/inc/css/font-awesome.min.css' );

  if( ( is_home() || is_front_page() ) && of_get_option('dazzling_slider_checkbox') == 1 ) {
    wp_enqueue_style( 'flexslider-css', get_template_directory_uri().'/inc/css/flexslider.css' );
  }

  if ( class_exists( 'jigoshop' ) ) { // Jigoshop specific styles loaded only when plugin is installed
    wp_enqueue_style( 'jigoshop-css', get_template_directory_uri().'/inc/css/jigoshop.css' );
  }

  wp_enqueue_style( 'dazzling-style', get_stylesheet_uri() );

  wp_enqueue_script('twitterjs', 'http://platform.twitter.com/widgets.js', array('jquery') );
  wp_enqueue_script('dazzling-jqueryjs', get_template_directory_uri().'/inc/js/jquery-1.8.3.min.js', array('jquery') );
  wp_enqueue_script('dazzling-bootstrapjs', get_template_directory_uri().'/inc/js/bootstrap.min.js', array('jquery') );
  wp_enqueue_script('dazzling-tooltipjs', get_template_directory_uri().'/inc/js/bootstrap-tooltip.js', array('jquery') );
  wp_enqueue_script('dazzling-popoverjs', get_template_directory_uri().'/inc/js/bootstrap-popover.js', array('jquery') );
  wp_enqueue_script('dazzling-businessjs', get_template_directory_uri().'/inc/js/business_ltd_1.0.js', array('jquery') );

  if( ( is_home() || is_front_page() ) && of_get_option('dazzling_slider_checkbox') == 1 ) {
    wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/inc/js/flexslider.min.js', array('jquery'), '2.5.0', true );
  }

  //wp_enqueue_script( 'dazzling-main', get_template_directory_uri() . '/inc/js/main.js', array('jquery'), '1.5.4', true );

  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'dazzling_scripts' );

/**
 * Add HTML5 shiv and Respond.js for IE8 support of HTML5 elements and media queries
 */
function dazzling_ie_support_header() {
  echo '<!--[if lt IE 9]>'. "\n";
  echo '<script src="' . esc_url( get_template_directory_uri() . '/inc/js/html5shiv.min.js' ) . '"></script>'. "\n";
  echo '<script src="' . esc_url( get_template_directory_uri() . '/inc/js/respond.min.js' ) . '"></script>'. "\n";
  echo '<![endif]-->'. "\n";
}
add_action( 'wp_head', 'dazzling_ie_support_header', 11 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load custom nav walker
 */
require get_template_directory() . '/inc/navwalker.php';

if ( class_exists( 'woocommerce' ) ) {
/**
 * WooCommerce related functions
 */
require get_template_directory() . '/inc/woo-setup.php';
}

if ( class_exists( 'jigoshop' ) ) {
/**
 * Jigoshop related functions
 */
require get_template_directory() . '/inc/jigoshop-setup.php';
}

/**
 * Metabox file load
 */
require get_template_directory() . '/inc/metaboxes.php';

/**
 * Register Social Icon menu
 */
add_action( 'init', 'register_social_menu' );

function register_social_menu() {
  register_nav_menu( 'social-menu', _x( 'Social Menu', 'nav menu location', 'dazzling' ) );
}

/* Globals variables */
global $options_categories;
$options_categories = array();
$options_categories_obj = get_categories();
foreach ($options_categories_obj as $category) {
        $options_categories[$category->cat_ID] = $category->cat_name;
}

global $site_layout;
$site_layout = array('side-pull-left' => esc_html__('Right Sidebar', 'dazzling'),'side-pull-right' => esc_html__('Left Sidebar', 'dazzling'),'no-sidebar' => esc_html__('No Sidebar', 'dazzling'),'full-width' => esc_html__('Full Width', 'dazzling'));

// Typography Options
global $typography_options;
$typography_options = array(
        'sizes' => array( '6px' => '6px','10px' => '10px','12px' => '12px','14px' => '14px','15px' => '15px','16px' => '16px','18px'=> '18px','20px' => '20px','24px' => '24px','28px' => '28px','32px' => '32px','36px' => '36px','42px' => '42px','48px' => '48px' ),
        'faces' => array(
                'arial'          => 'Arial,Helvetica,sans-serif',
                'verdana'        => 'Verdana,Geneva,sans-serif',
                'trebuchet'      => 'Trebuchet,Helvetica,sans-serif',
                'georgia'        => 'Georgia,serif',
                'times'          => 'Times New Roman,Times, serif',
                'tahoma'         => 'Tahoma,Geneva,sans-serif',
                'Open Sans'      => 'Open Sans,sans-serif',
                'palatino'       => 'Palatino,serif',
                'helvetica'      => 'Helvetica,Arial,sans-serif',
                'helvetica-neue' => 'Helvetica Neue,Helvetica,Arial,sans-serif'
        ),
        'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
        'color'  => true
);

// Typography Defaults
global $typography_defaults;
$typography_defaults = array(
        'size'  => '14px',
        'face'  => 'helvetica-neue',
        'style' => 'normal',
        'color' => '#6B6B6B'
);

/**
 * Helper function to return the theme option value.
 * If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * Not in a class to support backwards compatibility in themes.
 */
if ( ! function_exists( 'of_get_option' ) ) :
function of_get_option( $name, $default = false ) {

  $option_name = '';
  // Get option settings from database
  $options = get_option( 'dazzling' );

  // Return specific option
  if ( isset( $options[$name] ) ) {
    return $options[$name];
  }

  return $default;
}
endif;


/****************** custom functions  ******************/

/**
 * Display the post content.
 *
 * @since 0.71
 *
 * @param string $more_link_text Optional. Content for when there is more text.
 * @param bool   $strip_teaser   Optional. Strip teaser content before the more text. Default is false.
 */
function display_content($words = 80, $content=null, $more_link_text = null, $strip_teaser = false) {
    if($content==null) $content = get_the_content( $more_link_text, $strip_teaser );
    $content = strip_shortcodes($content);
    $content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
    $content = ale_trim_words($content,$words,'...'.ALE_excerpt_more($more_link_text));
	return $content;
}
/**
 * Display the post content.
 *
 * @since 0.71
 *
 * @param string $more_link_text Optional. Content for when there is more text.
 * @param bool   $strip_teaser   Optional. Strip teaser content before the more text. Default is false.
 */
function display_content_without_readmore($words = 80, $content=null, $more_link_text = null, $strip_teaser = false) {
    if($content==null) $content = get_the_content( $more_link_text, $strip_teaser );
    $content = strip_shortcodes(wp_trim_words($content,$words));
	$content = apply_filters( 'the_content', $content );
    //$content = str_replace(']]>',']]>',$content) ;
	return $content;
}
/**
 * Display the post content.
 *
 * @since 0.71
 *
 * @param string $more_link_text Optional. Content for when there is more text.
 * @param bool   $strip_teaser   Optional. Strip teaser content before the more text. Default is false.
 */
function display_content_without_img($content=null) {
    if($content==null) $content = get_the_content();
    $content = apply_filters( 'the_content', $content );
    $content = str_replace( ']]>', ']]&gt;', $content );
    $content = preg_replace('/<img[^>]+./',' ',$content) ;
	return $content;
}

/**
 * Display the post image.
 *
 * @since 0.71
 *
 * @param string $more_link_text Optional. Content for when there is more text.
 * @param bool   $strip_teaser   Optional. Strip teaser content before the more text. Default is false.
 */
function catch_that_image($post=null) {
  if ($post==null) $post = get_the_content() ;
  $first_img = '';
  $new_img_tag = "";
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img[^>]+./',$post, $matches);
return $matches;
}

/**
 * Display the post image link.
 *
 * @since 0.71
 *
 * @param string $more_link_text Optional. Content for when there is more text.
 * @param bool   $strip_teaser   Optional. Strip teaser content before the more text. Default is false.
 */
function get_all_images($post=null) {
  if ($post==null) $post = get_the_content();
  $imgs = catch_that_image($post);
  $i=0;
  foreach($imgs[0] as $image){
    $info = explode('"',$image) ;
    $links[$i]=$info[3];
    $i++;
 }
 return $links;
}

function get_one_image($post_content=null,$post_id=null) {
  if ($post_content==null && $post_id==null) $post_content = get_the_content();
  $imgs = catch_that_image($post_content);
  $i=0;
  foreach($imgs[0] as $image){
    $info = explode('"',$image) ;
    $links[$i]=$info[3];
    $i++;
   }
   $post_image = $links[0];
  if(empty($post_image) && $post_id!=null)   $post_image = get_the_post_thumbnail_url ($post_id);
  if(empty($post_image))   $post_image    = get_template_directory_uri() . '/images/placeholder.jpg';
 return $post_image;
}

function category_posts_display($selected_cat) {

  $cat_id = get_cat_ID($selected_cat); // setup the cateogory ID
  query_posts( "cat=$cat_id&posts_per_page=10");   // create a custom wordpress query
  $cat_link = get_category_link($cat_id);
  $posts[0]['link'] = $cat_link;  //reserve the first (0) position for CATEGORY LINK
  $i=1;  //begin category POSTS at position (1).
  if (have_posts()) : while (have_posts()) : the_post();
             $posts[$i]['id'] = the_ID();
             $posts[$i]['name'] = $post_title = get_the_title();
             $imgs = catch_that_image();
             $posts[$i]['img'] = $post_img = $imgs[1][0];
             $posts[$i]['link'] = $post_link = get_permalink();
             $posts[$i]['content'] = $post_content = get_the_content();
             $i++;
            endwhile; ?>

           <?php endif;

      return $posts;
  }

function category_posts_retrieve($selected_cat,$num_posts=10) {

  $cat_id = get_cat_ID($selected_cat); // setup the cateogory ID
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $cat_posts = query_posts( "cat=$cat_id&posts_per_page=$num_posts&paged=$paged");   // create a custom wordpress query
  $cat_link = get_category_link($cat_id);
  $fnc_results['cat_link'] = $cat_link;
  $fnc_results['cat_posts'] = $cat_posts;

  return $fnc_results;

  }


function get_active_link($tab) {
    $link = home_url(add_query_arg('_', false));
    if( strpos($link,$tab)!=false ){
       echo 'class="active"';
    }
}

function get_the_url($key) {
    return get_site_url()."/index.php/".$key;
}

function ALE_excerpt_more($link=NULL) {
      if($link==NULL || $link=="") {
                global $post;
                return ' <a class="more" href="'. get_permalink($post->ID) .'">...Continue Reading.</a>';
     }
     else   return ' <a class="more" href="'. $link.'">...Continue Reading.</a>';
}
add_filter('excerpt_more', 'ALE_excerpt_more');
/**     add fields functions
add_action (  ' personal_options_update ' , ' my_save_extra_profile_fields ' );
add_action ( ' edit_user_profile_update ',' my_save_extra_profile_fields ' );
*************************************/

add_action( 'show_user_profile', 'yoursite_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'yoursite_extra_user_profile_fields' );
function yoursite_extra_user_profile_fields( $user ) {     ?>
  <h3><?php _e("Extra profile information","blank"); ?></h3>
  <table class="form-table">
    <tr>
      <th><label for="phone"><?php _e("Phone"); ?></label></th>
      <td>
        <input type="text" name="phone" id="phone" class="regular-text"
            value="<?php echo esc_attr(get_the_author_meta( 'phone', $user->ID ) ); ?>" />
    </td>
    </tr>
    <tr>
      <th><label for="phone"><?php _e("Reception E-mail"); ?></label></th>
      <td>
        <input type="text" name="e-address" id="e-address" class="regular-text"
            value="<?php echo esc_attr(get_the_author_meta( 'e-address', $user->ID ) ); ?>" />
    </td>
    </tr>
    <tr>
      <th><label for="gps"><?php _e("GPS Cordinates"); ?></label></th>
      <td>
        <input type="text" name="gps" id="gps" class="regular-text"
            value="<?php echo esc_attr(get_the_author_meta( 'gps', $user->ID ) ); ?>" />
    </td>
    </tr>
    <tr>
      <th><label for="phone"><?php _e("Address:"); ?></label></th>
      <td>
        <input type="textarea" name="address" id="address" class="regular-text"
            value="<?php echo esc_attr(get_the_author_meta( 'address', $user->ID ) ); ?>" />
    </td>
    </tr>
  </table>
<?php
}

add_action( 'personal_options_update','yoursite_save_extra_user_profile_fields');
add_action( 'edit_user_profile_update','yoursite_save_extra_user_profile_fields');

function yoursite_save_extra_user_profile_fields( $user_id ) {
  $saved = false;
  if ( current_user_can( 'edit_user',$user_id ) ) {
    update_user_meta( $user_id, 'phone',$_POST['phone'] );
    update_user_meta( $user_id, 'e-address',$_POST['e-address'] );
    update_user_meta( $user_id, 'address',$_POST['address'] );
    update_user_meta( $user_id, 'gps',$_POST['gps'] );
    $saved = true;
  }
  return true;
}

add_action( 'wp_ajax_button_click', 'user_clicked' );
function user_clicked() {
    update_user_meta( get_current_user_id(), 'clicked_link', 'yes' );
    wp_redirect( $_SERVER['HTTP_REFERER'] );
    exit();
}


/* custom post type for Sectors and custom fielts */
 // Sector Custom Post Type
add_action( 'init', 'create_sector' );
function create_sector() {
     //create post type = Sectors
    register_post_type( 'sectors',
        array(
            'labels' => array(
                'name' => 'Sectors',
                'singular_name' => 'Sector',
                'add_new' => 'Add New Sector',
                'add_new_item' => 'Add New Sector',
                'edit' => 'Edit',
                'edit_item' => 'Edit Sector',
                'new_item' => 'New Sector',
                'view' => 'View',
                'view_item' => 'View Sector',
                'search_items' => 'Search Sectors',
                'not_found' => 'No Sectors found',
                'not_found_in_trash' => 'No Sectors found in Trash',
                'parent' => 'Parent Sector'
            ),
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title','editor','thumbnail', ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-calendar',
            'has_archive' => true
        )
    );
   //create post type Projects
       register_post_type( 'projects',
        array(
            'labels' => array(
                'name' => 'Projects',
                'singular_name' => 'Project',
                'add_new' => 'Add New Project',
                'add_new_item' => 'Add New Project',
                'edit' => 'Edit',
                'edit_item' => 'Edit Project',
                'new_item' => 'New Project',
                'view' => 'View',
                'view_item' => 'View Project',
                'search_items' => 'Search Projects',
                'not_found' => 'No Projects found',
                'not_found_in_trash' => 'No Projects found in Trash',
                'parent' => 'Parent Project'
            ),
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title','editor','thumbnail', ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-clipboard',
            'has_archive' => true
        )
    );
  //create post type Promotions
       register_post_type( 'promotions',
        array(
            'labels' => array(
                'name' => 'Promotions',
                'singular_name' => 'Promotion',
                'add_new' => 'Add New Promotion',
                'add_new_item' => 'Add New Promotion',
                'edit' => 'Edit',
                'edit_item' => 'Edit Promotion',
                'new_item' => 'New Promotion',
                'view' => 'View',
                'view_item' => 'View Promotion',
                'search_items' => 'Search Promotions',
                'not_found' => 'No Promotions found',
                'not_found_in_trash' => 'No Promotions found in Trash',
                'parent' => 'Parent Promotion'
            ),
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title','editor','thumbnail', ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-clipboard',
            'has_archive' => true
        )
    );

}

// change posts label to CSI
function revcon_change_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'CSI';
    $submenu['edit.php'][5][0] = 'CSI';
    $submenu['edit.php'][10][0] = 'Add CSI';
    $submenu['edit.php'][16][0] = 'CSI Tags';
}
function revcon_change_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'CSI';
    $labels->singular_name = 'CSI';
    $labels->add_new = 'Add CSI';
    $labels->add_new_item = 'Add CSI';
    $labels->edit_item = 'Edit CSI';
    $labels->new_item = 'CSI';
    $labels->view_item = 'View CSI';
    $labels->search_items = 'Search CSI';
    $labels->not_found = 'No CSIs found';
    $labels->not_found_in_trash = 'No CSIs found in Trash';
    $labels->all_items = 'All CSIs';
    $labels->menu_name = 'CSI';
    $labels->name_admin_bar = 'CSI';
}

add_action( 'admin_menu', 'revcon_change_post_label' );
add_action( 'init', 'revcon_change_post_object' );



 /***********close add fields********/    ?>