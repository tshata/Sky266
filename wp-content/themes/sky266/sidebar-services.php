<?php
/**
 * The template for the content bottom widget areas on posts and pages
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
// If we get this far, we have widgets. Let's do this.
?>
<aside id="content-bottom-widgets" class="content-bottom-widgets" role="complementary">

                  <div class="sidebar-wrapper">
                      <div class="widget">
                          <h2 class="widget-title">Services</h2>
                            <ul class="service-list widget-arrow-list">
                           <?php
                                  $services =  query_posts( "post_type=services");
                                  $i=0;
                               foreach ( $services as $service) : setup_postdata($service);
                             ?>   <?php $post_id= $service->ID ;
                                        $link = get_permalink($post_id);
                                        $post_title = apply_filters( 'the_title' , $service->post_title );
                            ?>
                                <li><a href="<?php echo $link; ?>"><?php echo $post_title; ?> </a></li>
                            <?php $i++;   endforeach;
                            wp_reset_query(); ?>
                            </ul>

                      </div><!-- /.widget -->

                  </div>
</aside><!-- .content-bottom-widgets -->
