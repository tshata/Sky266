<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<!-- footer-widget-section start -->
<section class="footer-widget-section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="footer-widget">
                    <h3>Quick links</h3>
                    <ul class="quick-links">
                        <li><a href="">Upcoming Trips</a></li>
                        <li><a href="">Cargo tracking</a></li>
                        <li><a href="">Countries we work in</a></li>
                    </ul>
                </div><!-- /.footer-widget -->
            </div><!-- /.col-sm-4 -->
            <div class="col-sm-3">
                <div class="footer-widget">
                    <h3>Help & Support</h3>
                    <ul class="quick-links">
                        <li><a href="">Courier Guidance</a></li>
                        <li><a href="">Sample Documents</a></li>
                        <li><a href="">News</a></li>
                    </ul>
                </div><!-- /.footer-widget -->
            </div><!-- /.col-sm-4 -->
            <div class="col-sm-3">
                <div class="footer-widget">
                    <h3>Where to find us</h3>

                    <address>
                        Old AME Church,<br>
                        Next to Main Maseru Circle,<br>
                        Maseru, Lesotho
                    </address>


                </div><!-- /.footer-widget -->
            </div><!-- /.col-sm-4 -->
            <div class="col-sm-3">
                <div class="footer-widget">
                    <h3>Stay in Touch</h3>
                    <p>info@sky266.co.ls</p>
                    <p>bookings@sky266.co.ls</p>
                    <p> (+266) 57555325 / 62555325

                    </p>
                    <ul class="social-links list-inline">
                        <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a class="google-plus" href="#"><i class="fa fa-google-plus"></i></a></li>
                        <li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div><!-- /.footer-widget -->
            </div><!-- /.col-md-4 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /.cta-section -->
<!-- footer-widget-section end -->

<!-- copyright-section start -->
<footer class="copyright-section">
    <div class="container">
        <div class="footer-menu">
        </div>

        <div class="copyright-info">
            <span>Copyright&reg;2020 <a href="https://www.sky266.co.ls">Sky266</a>. All Rights Reserved. Designed by <a
                    href="https://www.tech-corp.co.ls">Tech-Corp</a></span>
        </div>
    </div><!-- /.container -->
</footer>
<!-- copyright-section end -->
</div> <!-- .st-content -->
</div> <!-- .st-pusher -->



<!-- OFFCANVAS MENU -->
<div class="offcanvas-menu offcanvas-effect">
    <div class="offcanvas-wrap">
        <div class="off-canvas-header">
            <button type="button" class="close" aria-hidden="true" data-toggle="offcanvas"
                id="off-canvas-close-btn">&times;</button>
        </div>
        <ul id="offcanvasMenu" class="list-unstyled visible-xs visible-sm">
            <?php  $pagename = $wp_query->post->post_title; ?>
            <li class="<?php echo ($pagename=="")? "active" : ""; ?>"><a
                    href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
            <li class="<?php echo ($pagename=="About Us")? "active" : ""; ?>"><a
                    href="<?php echo esc_url( home_url( 'index.php/about-us' ) ); ?>">About Us</a></li>
            <li class="dropdown <?php echo ($pagename=="Our Services")? "active" : ""; ?>"><a href="#">Services <b
                        class="caret"></b></a><span>|</span>
                <!-- submenu-wrapper -->
                <div class="submenu-wrapper submenu-wrapper-topbottom">
                    <div class="submenu-inner  submenu-inner-topbottom">
                        <ul class="dropdown-menu">
                            <?php
                                                                         $services_menu =  query_posts( "post_type=services");
                                                                         foreach ( $services_menu as $service_menu) : setup_postdata($service_menu);
                                                                         ?> <?php $post_id= $service_menu->ID ;
                                                                                  $link = get_permalink($post_id);
                                                                                  $post_title = apply_filters( 'the_title' , $service_menu->post_title );
                                                                          ?>
                            <li class="<?php echo ($pagename==$post_title)? "active" : ""; ?>"><a
                                    href="<?php echo esc_url($link); ?>"><?php echo $post_title; ?></a></li>
                            <?php endforeach;
                                                                      wp_reset_query(); ?>
                        </ul>
                    </div>
                </div>
                <!-- /submenu-wrapper -->
            </li>
            <!-- /Services -->
            <li class="<?php echo ($pagename=="Knowledge Hub")? "active" : ""; ?>"><a
                    href="<?php echo esc_url( home_url( 'index.php/knowledge-hub' ) ); ?>">Knowledge
                    Hub</a><span>|</span></li>
            <li class="<?php echo ($pagename=="Gallery")? "active" : ""; ?>"><a
                    href="<?php echo esc_url( home_url( 'index.php/our-gallery' ) ); ?>">Gallery</a><span>|</span></li>
            <li class="<?php echo ($pagename=="Contact")? "active" : ""; ?>"><a
                    href="<?php echo esc_url( home_url( 'index.php/contact' ) ); ?>">Contact</a></li>
            <li class="<?php echo ($pagename=="Request Quote")? "active" : ""; ?>"><a
                    href="<?php echo esc_url( home_url( 'index.php/request-quote' ) ); ?>">Request Quote</a></li>
            <li class="<?php echo ($pagename=="Book Now")? "active" : ""; ?>"><a
                    href="<?php echo esc_url( home_url( 'index.php/request-quote' ) ); ?>">Book Now</a></li>
            <?php if( !is_user_logged_in()){ ?> <li><a
                    href="<?php echo esc_url( home_url( 'index.php/my-account' ) ); ?>">Login<i
                        class="fa fa-key"></i></a></li> <?php } ?>
            <?php if( is_user_logged_in() ){ ?>
            <li class="dropdown"><a href="#">Profile</a>
                <!-- submenu-wrapper -->
                <div class="submenu-wrapper submenu-wrapper-topbottom">
                    <div class="submenu-inner  submenu-inner-topbottom">
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo esc_url( home_url( 'index.php/my-account' ) ); ?>">Dashboard<i
                                        class="fa fa-f"></i></a></li>
                            <li><a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">Logout<i
                                        class="fa fa-key"></i></a></li>
                        </ul>
                    </div>
                </div>
                <!-- /submenu-wrapper -->
            </li>
            <?php } ?>
        </ul>
        <div class="offcanvas-widgets hidden-sm hidden-xs">
            <div id="twitterWidget">
                <h2>Tweeter feed</h2>
                <div class="twitter-widget"></div>
            </div>
            <div class="newsletter-widget">
                <h2>Stay in Touch</h2>
                <p>Enter your email address to receive news &amp; offers from us</p>

                <form class="newsletter-form">
                    <div class="form-group">
                        <label class="sr-only" for="InputEmail1">Email address</label>
                        <input type="email" class="form-control" id="InputEmail2" placeholder="Your email address">
                        <button type="submit" class="btn">Send &nbsp;<i class="fa fa-angle-right"></i></button>
                    </div>
                </form>

            </div><!-- newsletter-widget -->
        </div>

    </div>
</div><!-- .offcanvas-menu -->
</div><!-- /st-container -->


<!-- Preloader -->
<div id="preloader">
    <div id="status">
        <div class="status-mes"></div>
    </div>
</div>

<?php wp_footer(); ?>
</body>

</html>