
<?php
/**
*Template Name: KnowledgeHub
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
    						  <li class="active">Knowledge Hub</li>
    						</ol>
    					</div>
    				</section><!--/.page-title-section -->

          <!--about-us-intro-wrap  -->
          <section class="about-us-intro-wrap section-padding">

                          <?php if ( have_posts() ) : the_post(); ?>
            <div class="container">
              <div class="row">
              </div><!-- /.row -->
            </div><!-- /.container -->
                      </section>
                      <?php
                         endif;
                       ?>

      <br><br<br><br>


<?php get_footer(); ?>