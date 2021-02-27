<?php
/**
*Template Name: services
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

 get_header(); ?>
<!-- Page-title -->
<section class="page-title-section" style=" padding: 0px;">
    <div class="container"> <br>
        <ol class="breadcrumb">
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
            <li class="active">Our Services</li>
        </ol>
    </div>
</section>
<!--/.page-title-section -->

<!-- Single-Service-Start -->
<section class="single-service-wrap">
    <div class="container">
        <div class="row" style="margin-bottom: 40px;">
            <div class="col-sm-3">
                <div class="sidebar-wrapper">
                    <div class="widget">
                        <h2 class="widget-title">Services</h2>
                        <ul class="service-list widget-arrow-list">
                            <?php
                                  $services =  query_posts( "post_type=services");
                                  $i=0;
                               foreach ( $services as $service) : setup_postdata($service);
                             ?> <?php $post_id= $service->ID ;
                                     if($i==0){ // set defalt service to show
                                        $wpblog_fetrdimg = wp_get_attachment_url( get_post_thumbnail_id($service->ID) );
                                        $link = get_permalink($post_id);
                                        $post_title = apply_filters( 'the_title' , $service->post_title );
                                        $post_content = apply_filters( 'the_content' , $service->post_content);
                                      }
                            ?>
                            <li><a href="<?php echo get_permalink($post_id); ?>"><?php echo apply_filters( 'the_title' , $service->post_title ); ?>
                                </a></li>
                            <?php $i++;   endforeach;
                            wp_reset_query();
                             ?>
                        </ul>

                    </div><!-- /.widget -->

                </div>
            </div><!-- /.col -->
            <div class="col-sm-9">
                <div class="single-service-content">
                    <div class="single-service-thumb">
                        <img src="<?php echo $wpblog_fetrdimg;?>" height="550px;" width="100%;" alt="image" />
                    </div>
                    <h2><?php echo $post_title; ?></h2>
                    <p><?php echo $post_content; ?></p>
                </div><!-- /.single-service-content -->

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>
<!-- Single-Service-End-->


<?php get_footer(); ?>