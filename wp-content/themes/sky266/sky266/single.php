<?php
/**
 * The template for displaying all single posts and attachments
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
                    	<?php
                    		// Start the loop.
                    		while ( have_posts() ) : the_post();

                    			// Include the single post content template.
                    			get_template_part( 'template-parts/content', 'single' );

                    			// If comments are open or we have at least one comment, load up the comment template.
                    			if ( comments_open() || get_comments_number() ) {
                    				comments_template();
                    			}

                    			if ( is_singular( 'attachment' ) ) {
                    				// Parent post navigation.
                    				the_post_navigation( array(
                    					'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'twentysixteen' ),
                    				) );
                    			} elseif ( is_singular( 'post' ) ) {
                    				// Previous/next post navigation.
                    				the_post_navigation( array(
                    					'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentysixteen' ) . '</span> ' .
                    						'<span class="screen-reader-text">' . __( 'Next post:', 'twentysixteen' ) . '</span> ' .
                    						'<span class="post-title">%title</span>',
                    					'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentysixteen' ) . '</span> ' .
                    						'<span class="screen-reader-text">' . __( 'Previous post:', 'twentysixteen' ) . '</span> ' .
                    						'<span class="post-title">%title</span>',
                    				) );
                    			}

                    			// End of the loop.
                    		endwhile;
                    		?>
                  </div><!-- /.single-service-content -->

                </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.container -->
          </section>
          <!-- Single-Service-End-->


<?php get_footer(); ?>

