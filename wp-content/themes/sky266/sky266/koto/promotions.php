
<!--
Template Name: promotions
Author: Mphanya
-->
<?php get_header(); ?>


<section id="bodySection">
	<div id="sectionTwo">
		<div class="container">
		<div class="row">
			<div class="span12">
            <div class="intro">
			   <h4> Our Promotions:</h4>
      </div>

            <div class="row-fluid" style="text-align:center">
            <?php   $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $services =  query_posts( "post_type=promotions&posts_per_page=9&paged=$paged");
                    $i=0;
                 foreach ( $services as $service) : setup_postdata($service);
               ?>   <?php $post_id= $service->ID ;
                          $post_image= get_one_image($service->post_content,$post_id);
                          $link = get_permalink($post_id);
                ?>

			<div class="span4 mspan6 sspan6" id="projects">
				<div class="well well-small" id="<?php echo $post_id; ?>">
                        <h4> <a href="<?php echo $link; ?>">  <?php echo apply_filters( 'the_title' , $service->post_title ); ?>   </a>    </h4>
                            <a href="<?php echo $link; ?>" >
                               <figure class="post-thumbnail" id="img<?php echo $post_id; ?>">
                                 <img src="<?php  echo $post_image; ?>" style="width:100%;" >
    						</figure>
							  </a>
             </div>
			</div>
            <?php $i++;   endforeach;  ?>

                <div class="clear"></div>
           </div>
				<div class="pagination pull-right">
				  <ul>
					<li><?php echo get_previous_posts_link( 'Previous Page' ); ?></li>
					<li><a href="#">...</a></li>
					<li><?php echo get_next_posts_link( 'See More Promotions', $the_query->max_num_pages ); ?></li>
				  </ul>
				</div>

            <?php wp_reset_postdata(); ?>
			<!-- ========================= -->
		</div>
			</div>

			</div>

	</div>
</section>
                <div class="clear-fix"><br><br><br></div>

<?php get_footer(); ?>

