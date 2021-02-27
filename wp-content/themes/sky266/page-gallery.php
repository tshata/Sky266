<?php
/**
*Template Name: Gallery
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
            <li class="active">Gallery</li>
        </ol>
    </div>
</section>
<!--/.page-title-section -->

<!-- team-wrap-->
<section class="team-wrap section-padding">
    <div class="container">
        <?php
                                      $our_values =  query_posts( "post_type=services");
                                      $i=0;
                                   foreach ( $our_values as $value) : setup_postdata($value);
                                 ?> <?php $post_id= $value->ID ;
                                            $wpblog_fetrdimg = wp_get_attachment_url( get_post_thumbnail_id($value->ID) );
                                      if($i%4==0) echo'<div class="row">';
                                ?>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="team-member">
                <div class="member-thumb">
                    <a class="img-link" href="<?php echo $wpblog_fetrdimg;?>">
                        <img class="img-responsive" src="<?php echo $wpblog_fetrdimg;?>" alt="gallery"></a>
                </div>
            </div><!-- /.team-member -->
        </div><!-- /.col -->
        <?php  $i++;
                                if($i%4==0) echo'</div>';
                               endforeach;  ?>
    </div><!-- /.row -->
    </div><!-- /.container -->
</section>
<!-- /.team-wrap -->
<br>
<br<br><br>


    <?php get_footer(); ?>