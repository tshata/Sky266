
<?php
/**
*Template Name: About us
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
    						  <li class="active">About us</li>
    						</ol>
    					</div>
    				</section><!--/.page-title-section -->

					<!--about-us-intro-wrap  -->
					<section class="about-us-intro-wrap section-padding">

                          <?php if ( have_posts() ) : the_post(); ?>
						<div class="container">
							<div class="row" style="margin-bottom: 40px;">
								<div class="col-sm-4">
									<div class="about-thumb" >
                                        <?php the_post_thumbnail(); ?>
									</div>
								</div><!-- /.col -->

								<div class="col-sm-8">
									<div class="about-us-intro-content">
										<div class="section-heading">
											<h2 class="section-title">A few words about us</h2>
										</div>
                                        <p><?php the_content(); ?></p>
								   </div><!--/.about-us-intro-content  -->

								</div><!--/.col-->
							</div><!-- /.row -->

               <!-- portfolio-section start -->
          <section class="fleets-wrap" style="background: none;">
                <div class="container">
                    <div class="section-heading">
                        <h2 class="section-title">Our Core Values</h2>
                    </div> <!--section-heading-->
                </div><!--/.container-->

                <div class="container-fluid">
                	<div class="no-padding">
                      <div class="row">
                        	<div class="col-md-12">
                    <div class="owl-carousel fleet-carousel">

                           <?php
                                  $services =  query_posts( "post_type=our_values");
                                  $i=0;
                               foreach ( $services as $service) : setup_postdata($service);
                             ?>   <?php $post_id= $service->ID ;
                                        $wpblog_fetrdimg = wp_get_attachment_url( get_post_thumbnail_id($service->ID) );
                                        $link = get_permalink($post_id);
                                        $post_title = apply_filters( 'the_title' , $service->post_title );
                                        $post_content = apply_filters( 'the_content' , $service->post_content);
                            ?>
                                <div class="item">
                                	<div class="owl-item-thumb">
                                		<img src="<?php echo $wpblog_fetrdimg;?>" alt="">
                                		<div class="owl-item-overlay"></div>
                                  	<a class="img-link" href="<?php echo $wpblog_fetrdimg;?>"><img src="<?php echo get_template_directory_uri() . '/img/zoomin.png';?>" alt="+"/></a>
                                	</div><!-- owl-item-thumb -->
                                	<div class="owl-tem-content">
                                  	<h3><a href="#"><?php echo $post_title; ?></a></h3>
                                  	<p><?php echo $post_content; ?></p>
                                	</div><!-- owl-item-content -->
                                </div><!-- /item -->

                            <?php $i++;   endforeach;  ?>

                    </div><!--/.owl-carousel-->


                        	</div><!-- /.col-md-12 -->

                      </div><!-- /.row -->
                    </div><!--/.no-padding-->
                  </div><!-- /.container-fluid -->
              </section>
              <!-- fleets-wrap end -->
						</div><!-- /.container -->
                      </section>
                      <?php
                         endif;
                       ?>

      <br><br<br><br>


<?php get_footer(); ?>