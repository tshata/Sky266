<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */ ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
    <head>
    	<meta charset="<?php bloginfo( 'charset' ); ?>">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<link rel="profile" href="http://gmpg.org/xfn/11">
    	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
    	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    	<?php endif; ?>
    	<?php wp_head(); ?>
        <!---------Method one: Bubble chat - Start-------------->
         <!--script src="https://fobi.io/embed.js"></script>
       <div id="embed-fobi"  data-fobi-id="83dyUj5" data-bot-title= "Chat" data-hd-bg= "#2980B9" data-hd-ti-clr= "#FFF" data-ct-pm= "#2980B9" data-ct-sc= "#FFF" data-ct-bot-img= "https://fobi.io/head.png" data-btn-offset= "Right: 30px" data-cb-offset= "Right: 30px" data-btn-img= "https://fobi.io/icon.png" data-btn-bg= "#2980B9" data-cb-height= "400px" data-cb-width= "330px" ></div>
     <!--------Method one: Bubble chat - End ---------------->
    

    </head>

	<body id="page-top" <?php body_class(); ?> >
		<div id="st-container" class="st-container">
		    <div class="st-pusher">
	        	<div class="st-content">
					<header class="header">
				  		<div class="container mainnav">
                           <div id="logo_div" class="col-sm-2 col-xs-12">
			  					<div class="logo">
			  						<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri() . '/img/logo.jpg';?>" alt=" " style="width:200px; "></a></h1>
			  					</div>
                            </div>
                            <div id="nav_div" class="col-sm-7 col-xs-12">
                            <nav class="navbar navbar-default" role="navigation">
                                 <?php  $pagename = $wp_query->post->post_title;?>
									<!-- offcanvas-trigger -->
				                        <button type="button" class="navbar-toggle collapsed" >
				                          <span class="sr-only">Toggle navigation</span>
				                          <i class="fa fa-bars"></i>
				                        </button>

									<!-- Collect the nav links, forms, and other content for toggling -->
									<div class="collapse navbar-collapse navbar-collapse">
										<!-- Collect the nav links, forms, and other content for toggling -->
										<ul class="nav navbar-nav hidden-sm">
											<li class="<?php echo ($pagename=="")? "active" : ""; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>|</span></li>
											<li class="<?php echo ($pagename=="About Us")? "active" : ""; ?>"><a href="<?php echo esc_url( home_url( 'index.php/about-us' ) ); ?>">About Us</a><span>|</span></li>
	                                        <!-- Services -->
    									    <li class="dropdown <?php echo ($pagename=="Our Services")? "active" : ""; ?>"><a href="<?php echo esc_url( home_url( 'index.php/our-services' ) ); ?>">Services <b class="caret"></b></a><span>|</span>
    									        <!-- submenu-wrapper -->
    									        <div class="submenu-wrapper submenu-wrapper-topbottom">
    									            <div class="submenu-inner  submenu-inner-topbottom">
    									                <ul class="dropdown-menu">
                                                            <?php
                                                                 $services_menu =  query_posts( "post_type=services");
                                                                 foreach ( $services_menu as $service_menu) : setup_postdata($service_menu);
                                                                 ?>   <?php $post_id= $service_menu->ID ;
                                                                          $link = get_permalink($post_id);
                                                                          $post_title = apply_filters( 'the_title' , $service_menu->post_title );
                                                                  ?>
    									                        <li class="<?php echo ($pagename==$post_title)? "active" : ""; ?>"><a href="<?php echo esc_url($link); ?>"><?php echo $post_title; ?></a></li>
                                                              <?php endforeach;
                                                              wp_reset_query(); ?>
    									                </ul>
    									            </div>
    									        </div>
    									        <!-- /submenu-wrapper -->
    									    </li>
											<!-- /Services -->
                                            <li class="<?php echo ($pagename=="Knowledge Hub")? "active" : ""; ?>"><a href="<?php echo esc_url( home_url( 'index.php/knowledge-hub' ) ); ?>">Knowledge Hub</a><span>|</span></li>
                                            <li class="<?php echo ($pagename=="Gallery")? "active" : ""; ?>"><a href="<?php echo esc_url( home_url( 'index.php/our-gallery' ) ); ?>">Gallery</a><span>|</span></li>
                                            <li class="<?php echo ($pagename=="Contact")? "active" : ""; ?>"><a href="<?php echo esc_url( home_url( 'index.php/contact' ) ); ?>">Contact</a></li>

										</ul>

									</div><!-- /.navbar-collapse -->


							  </nav> </div>
                              <div id="other_div" class="col-sm-3 hidden-ms hidden-xs">
                                 <div class="right-links">
			  						<ul class="list-inline">

                    <?php if( !is_user_logged_in()){ ?> 
                    <li><a class="btn btn-primary" href="<?php echo esc_url( home_url( 'index.php/register' ) ); ?>">Register<i class="fa"></i></a>
                    <?php } ?>
                    <?php if( !is_user_logged_in()){ ?> 
                    <li><a class="btn btn-primary" href="<?php echo esc_url( home_url( 'index.php/my-account' ) ); ?>">Login<i class="fa fa-key"></i></a></li>
                    </li> <?php } ?>
                                        
                    <li><a class="btn btn-primary" href="<?php echo esc_url( home_url( 'index.php/request-quote' ) ); ?>">Quote/Book</a></li>
			  							
                           
                    <?php if( is_user_logged_in() ){ ?>
                    <li class="dropdown"><a class="btn btn-primary" href="<?php echo esc_url( home_url( 'index.php/my-account' ) ); ?>">Profile<b class="caret"></b></a>

    									        <!-- submenu-wrapper -->
    									        <div class="submenu-wrapper submenu-wrapper-topbottom">
    									            <div class="submenu-inner  submenu-inner-topbottom">
    									                <ul class="dropdown-menu" style="background: white;">
    									                   <li><a href="<?php echo esc_url( home_url( 'index.php/my-account' ) ); ?>">Dashboard<i class="fa fa-f"></i></a></li>
                                                           <li><a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">Logout<i class="fa fa-key"></i></a></li>
                                                        </ul>
    									            </div>
    									        </div>
    									        <!-- /submenu-wrapper -->
    									    </li>
                                        <?php } ?>
			  						</ul>
                                 </div>
                            </div>
						</div><!-- /.container -->
					</header>
                    <?php
                      $pagename = get_query_var('pagename');
                      $excluded_pages = array("request-quote","my-account","register","about","tracking","contact","KnowledgeHub","profile","login", "password-reset", " ");
                      if(!in_array($pagename, $excluded_pages)) {
                    ?>
                    <div id="main-carousel" class="carousel slide hero-slide" data-ride="carousel">
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                                <img src="<?php echo get_template_directory_uri(). '/img/slider/slide-11.jpg';?>" alt="Hero Slide"  >
                                <!--Slide Image-->
                            </div>
                            <!--.item-->

                             <div class="item">
                              <img src="<?php echo get_template_directory_uri(). '/img/slider/slide-12.jpg';?>" alt="Hero Slide">
                                <!--Slide Image-->
                            </div>
                            <!--.item-->
                             <div class="item">
                              <img src="<?php echo get_template_directory_uri(). '/img/slider/slide-13.jpg';?>" alt="Hero Slide">
                                <!--Slide Image-->
                            </div>
                            <!--.item-->

                            <div class="item">
                              <img src="<?php echo get_template_directory_uri(). '/img/slider/slide-14.jpg';?>" alt="Hero Slide">
                                <!--Slide Image-->
                            </div>
                            <!--.item-->

                        </div>
                        <!--.carousel-inner-->

                        <!-- Controls -->
                      <a class="left carousel-control" href="#main-carousel" role="button" data-slide="prev">
                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                            <span class="sr-only">Previous</span>
                      </a>
                      <a class="right carousel-control" href="#main-carousel" role="button" data-slide="next">
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                            <span class="sr-only">Next</span>
                      </a>

                    <div class="slidecaption" >
                            <div class="container">
                                <div class="carousel-caption">
                                    <div class="wpcargo-track wpcargo">
                                    	<form method="post" name="wpcargo-track-form" action="<?php echo esc_url( home_url( 'index.php/track-form' ) ); ?>">
                                    		<?php wp_nonce_field( 'wpcargo_track_shipment_action', 'track_shipment_nonce' ); ?>
                                    		<table id="wpcargo-track-table" class="track_form_table">
                                    			<tr class="track_form_tr">
                                    				<th class="track_form_th" colspan="2"><h4><?php echo apply_filters('wpcargo_tn_form_title', esc_html__('TRACK YOUR PARCEL', 'wpcargo') ); ?></h4></th>
                                    			</tr>
                                    			<tr class="track_form_tr">
                                    				<?php do_action('wpcargo_add_form_fields'); ?>
                                    				<td class="track_form_td"><input class="input_track_num" type="text" name="wpcargo_tracking_number" value="" autocomplete="off" placeholder="<?php echo apply_filters('wpcargo_tn_placeholder', esc_html__('Enter the Tracking No. (e.g: 12345)', 'wpcargo' ) ); ?>" required></td>
                                    				<td class="track_form_td submit-track"><input id="submit_wpcargo" class="wpcargo-btn wpcargo-btn-primary" name="wpcargo-submit" type="submit" value="<?php echo apply_filters('wpcargo_tn_submit_val', esc_html__( 'TRACK RESULT', 'wpcargo' ) ); ?>"></td>
                                    			</tr>
                                    		</table>
                                    	</form>
                                    </div>
                                </div>
                                <!--.carousel-caption-->
                            </div>
                            <!--.container-->

                    </div>

                  </div>
                    <!-- #main-carousel-->
                 <?php } ?>

