<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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
                <?php the_archive_title(); ?>
            </li>
        </ol>
    </div>
</section>
<!--/.page-title-section -->

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
                    <?php
                			// Start the Loop.
                			while ( have_posts() ) : the_post();

                				/*
                				 * Include the Post-Format-specific template for the content.
                				 * If you want to override this in a child theme, then include a file
                				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                				 */
                				get_template_part( 'template-parts/content', get_post_format() );

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