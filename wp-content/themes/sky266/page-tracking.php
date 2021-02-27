<?php
/**
*Template Name:Tracking
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
    <div class="container">
        <div class="page-header"><br>
            <ol class="breadcrumb">
                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                <li class="active"><?php the_title();?></li>
            </ol>
        </div>
    </div>
</section>
<!--/.page-title-section -->
<div id="primary" class="content-area"> <br><br>
    <main id="main" class="site-main" role="main">
        <?php
		// Start the loop.
		while ( have_posts() ) : the_post();   ?>

        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <div style="padding: 20px;">
                            <p><?php the_content(); ?></p>
                        </div>
                        <!--/.about-us-intro-content  -->

                    </div>
                    <!--/.col-->
                </div><!-- /.row -->
            </div><!-- /.container -->
            <?php	endwhile;
		?>

    </main><!-- .site-main -->
    <br><br>
</div><!-- .content-area -->


<?php get_footer(); ?>