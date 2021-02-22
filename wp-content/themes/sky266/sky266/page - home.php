<?php
/**
*Template Name: homepage
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
              <!-- services start -->
          <section class="service-wrap">
            <!-- Controls will work for desktop and large devices -->
            <div id="outer_control" class="slider-control hidden-sm hidden-xs">
              <a class="left carousel-control" href="#service-carousel" role="button" data-slide="prev">
                  <span class="flaticon-previous11" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
              </a>
              <a class="right carousel-control" href="#service-carousel" role="button" data-slide="next">
                  <span class="flaticon-next15" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
              </a>
            </div><!--/.slider-control -->
            <div class="container">
                   <div class="row">
                    	<div id="welcom" class="col-md-4 col-sm-12">
                    	  <div class="service-left-box">
                          	<div class="section-heading">
                              <h2 class="section-title">Welcome to Sky266</h2>
                              <span class="section-sub">"Courier Without Borders"</span>
                            </div> <!--section-header-->

                            <div class="service-intro">
                            	 <p>This is Margueritte McAllister, headmistress of the Spencer School for Girls in Wildwood, New Jersey. Is Mr. or Mrs. Lando home? Yes, this is she. Mrs. Lando,  as you know, we pride ourselves on turning troubled girls into healthy, productive young women. But if they are not here, there is very little we can do. Now, Mathilda left school without permission nearly two weeks ago.</p>
                            </div><!--/.service-intro-->
                          </div><!-- /.service-left-box -->
                       </div><!-- /.col -->
                       <div id="values" class="col-md-8 col-sm-12">
                      		<div id="service-carousel" class="carousel slide">
                      			<!-- Controls
                      			will work for small devices -->
                              <div id="inner_control" class="slider-control ">
                                <a class="left carousel-control" href="#service-carousel" role="button" data-slide="prev">
                                    <span class="flaticon-previous11" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#service-carousel" role="button" data-slide="next">
                                    <span class="flaticon-next15" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                              </div><!--/.slider-control -->

                              <!-- Wrapper for slides -->
                          	<div class="carousel-inner" role="listbox">
                                 <?php
                                        $our_values =  query_posts( "post_type=our_values");
                                        $i=0;
                                     foreach ( $our_values as $value) : setup_postdata($value);
                                   ?>   <?php $post_id= $value->ID ;
                                              $wpblog_fetrdimg = wp_get_attachment_url( get_post_thumbnail_id($value->ID) );
                                              $link = get_permalink($post_id);
                                              $post_title = apply_filters( 'the_title' , $value->post_title );
                                              //$post_content = apply_filters( 'the_content' , $value->post_content);
                                           $active = ($i==0) ? " active": "";
                                        if($i%2==0) echo'<div class="item '.$active.'"> <div class="row">';
                                  ?>
                                    	  <div class="col-sm-6 col-xs-12">
                                        		<div class="service-content">
                                          		<h3><?php echo $post_title; ?></h3>
                                          		<div class="service-thumb">
                                          			<img class="img-responsive" src="<?php echo $wpblog_fetrdimg;?>" alt="image"/>
                                          		</div>
                                          		<a class="btn btn-primary" href="about-us">Read More<i class="fa fa-long-arrow-right"></i></a>
                                        		</div><!-- /.service-content -->
                                      	</div><!--/.col -->

                                  <?php  $i++;
                                         if($i%2==0) echo'</div></div>';
                                         endforeach;  ?>
                              </div>
                            </div><!-- /.carousel -->
                       </div><!--/.col-->
                      </div><!-- /.row -->
                  </div><!-- /.container -->
              </section>
              <!-- services end -->
              <!-- featuer-wrap start -->
              <section class="feature-wrap section-padding" data-stellar-background-ratio="0.5">

                <div class="container center">
                	<div class="row">
                		<div class="col-xs-12">
                			<div class="feature-content" >
                				<h2>Providing cost-effective<br><span>And reliable Courier Services
                					</span>
                				</h2>
                				<div>
                  				<p>Etiam non augue in tortor facilisis porttitor at sit amet justo. Sed blandit tempor hendrerit. Suspendisse quis tincidunt nisl. Nulla bibendum purus elit, ut hendrerit orci porttitor id. Donec egestas dapibus massa, et tempor nulla ultricies quis. Donec mattis, metus vel pharetra pulvinar, nunc leo consectetur purus, sit amet tincidunt dui lorem ac elit. Vivamus nulla nisl, sodales eu rutrum sit amet, viverra eu eros. Proin sollicitudin congue augue, eget condimentum purus dictum sit amet. Aenean et tempor augue.</p>
                				</div>
                				<a href="<?php echo esc_url( home_url( 'index.php/request-quote' ) ); ?>" class="btn btn-primary quote-btn btn-lg">Get a Quote <i class="fa fa-long-arrow-right"></i></a>

                			</div><!--/.feature-content-->
                		</div><!--/.col-->

                	</div><!-- /.row -->
                </div><!-- .container -->
              </section>
              <!-- featuer-wrap end -->

              <!-- About-us-wrap -->
              <section class="about-us-wrap">
              	<div class="container">
              		<div class="row">
              			<div class="col-sm-4 col-xs-12">
              				<div class="about-us-content">
              					<h3>Who we are</h3>
              					<div class="about-content-block">
                					<span>Distinctively Orchestrate Standardized</span>
                					<p>Progressively architect prospective imperatives through competitive communities. Professionally productize user strategic theme areas.</p>
              					</div>
              					<br>
              					<div class="about-content-block">
                					<span>Synergistically extend open source</span>
                					<p>Progressively architect prospective imperatives through competitive communities. Professionally productize user strategic theme areas.</p>
              					</div>
              				</div><!-- /about-us-content -->
              			</div><!--/.col-->

              			<div class="col-sm-4 col-xs-12">
              				<div class="about-us-content">
              					<h3>What we do</h3>
              					<div class="about-content-block">
                					<span>Distinctively Orchestrate Standardized</span>
                					<p>Progressively architect prospective imperatives through competitive communities. Professionally productize user strategic theme areas.Professionally productize user strategic theme areas.</p>
              					</div>
              					<br>
              					<div class="about-content-block">
                					<span>Synergistically extend open source e-business.</span>
                					<p>Progressively architect prospective imperatives through competitive communities. Professionally productize user strategic theme areas.</p>
              					</div>
              				</div><!-- /about-us-content -->
              			</div><!-- /.col-->

              			<div class="col-sm-4 col-xs-12">
              				<div class="about-us-content capabilities">
              					<div class="about-content-block">
                					<h3>Our Capabilities</h3>
                					<ul>
                						<li>Airport service</li>
                						<li>24 hours service in 7 days a week</li>
                						<li>Priority delivery service</li>
                						<li>Senior discounts</li>
                						<li>Corporate accounts available</li>
                					</ul>
              					</div>

              				</div><!-- /about-us-content -->
              			</div><!--/.col-->
              		</div><!-- /.row -->
              	</div><!-- /.container -->

              </section>
              <!-- /About-us-wrap -->

               <!-- portfolio-section start -->
          <section class="fleets-wrap">
                <div class="container">
                    <div class="section-heading">
                        <h2 class="section-title">Our Services</h2>
                    </div> <!--section-heading-->
                </div><!--/.container-->

                <div class="container-fluid">
                	<div class="no-padding">
                      <div class="row">
                        	<div class="col-md-12">
                    <div class="owl-carousel fleet-carousel">

                           <?php
                                  $services =  query_posts( "post_type=services");
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
                                  	<p><a class="btn btn-primary" href="<?php echo $link; ?>">Read More<i class="fa fa-long-arrow-right"></i></a></p>
                                	</div><!-- owl-item-content -->
                                </div><!-- /item -->

                            <?php $i++;   endforeach;  ?>

                    </div><!--/.owl-carousel-->

                    <!-- owl-carousel-control -->
                        <div class="fleet-carousel-navigation
                            slider-control">
                            <span class="prev left"><i class="flaticon-previous11"></i></span>
                            <span class="next right"><i class="flaticon-next15"></i></span>
                        </div>

                        	</div><!-- /.col-md-12 -->

                      </div><!-- /.row -->
                    </div><!--/.no-padding-->
                  </div><!-- /.container-fluid -->
              </section>
              <!-- fleets-wrap end -->

<?php get_footer(); ?>

