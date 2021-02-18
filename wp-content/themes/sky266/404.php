<?php
/**
 * The template for displaying 404 pages (not found)
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
				  <li class="active">
					 404 error
                  </li>
				</ol>
			</div>
		</section><!--/.page-title-section -->

 <!-- Single-Service-Start -->
          <section class="single-service-wrap">
            <div class="container">
              <div class="row" style="margin-bottom: 40px;">
                <div class="col-sm-3">
                   <?php get_sidebar( 'services' ); ?>
                </div><!-- /.col -->
                <div class="col-sm-9">
                  <div class="single-service-content">
                     <header class="page-header">
					    <h2 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentysixteen' ); ?></h2>
    				</header><!-- .page-header -->
    				<div class="page-content">
    					<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentysixteen' ); ?></p>

    					<?php get_search_form(); ?>
    				</div><!-- .page-content -->
                  </div><!-- /.single-service-content -->

                </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.container -->
          </section>
          <!-- Single-Service-End-->


<?php get_footer(); ?>



