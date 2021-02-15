<?php
/**
 * The template for displaying search results pages
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
					 Search
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
                  <?php if ( have_posts() ) : ?>

              		  <header class="page-header">
              				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'twentysixteen' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
              			</header><!-- .page-header -->

              			<?php
              			// Start the loop.
              			while ( have_posts() ) : the_post();

              				/**
              				 * Run the loop for the search to output the results.
              				 * If you want to overload this in a child theme then include a file
              				 * called content-search.php and that will be used instead.
              				 */
              				get_template_part( 'template-parts/content', 'search' );

              			// End the loop.
              			endwhile;

              			// Previous/next page navigation.
              			the_posts_pagination( array(
              				'prev_text'          => __( 'Previous page', 'twentysixteen' ),
              				'next_text'          => __( 'Next page', 'twentysixteen' ),
              				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
              			) );

              		// If no content, include the "No posts found" template.
              		else :
              			get_template_part( 'template-parts/content', 'none' );

              		endif;
              		?>



                  </div><!-- /.single-service-content -->

                </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.container -->
          </section>
          <!-- Single-Service-End-->


<?php get_footer(); ?>
