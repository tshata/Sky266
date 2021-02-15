
<!--
Template Name: CSI
Author: Mphanya
-->
<?php get_header(); ?>


<section id="bodySection">
	<div id="sectionTwo">
		<div class="container">
		<div class="row">
			<div class="span12">
            <div class="intro">
			   	<p><?php  $page = get_page_by_title( 'Corporate Social Investment (CSI)' );   $content = apply_filters( 'the_content' , $page->post_content );
                echo strip_tags($content); ?></p>
                <h4> Corporate Social Investment (CSI) Projects:</h4>
            </div>

            <div class="row-fluid">
            <?php   $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $services =  query_posts( "post_type=post&posts_per_page=9&paged=$paged");
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
                                 <img src="<?php  echo $post_image; ?>">
    						</figure>
							  </a>
							<div class="entry-content" id="<?php echo "div".$post_id; ?>">
                            <div class="entry-header">
								<p class="ent"><?php echo wp_trim_words(display_content_without_img( apply_filters( 'the_content' , $service->post_content )),100,ALE_excerpt_more($link)); ?></p>
                                            
							</div>
					  </div>
             </div>
			</div>
            <?php $i++;  endforeach;  ?>

                <div class="clear"></div>
           </div>
				<div class="pagination pull-right">
				  <ul>
					<li><?php echo get_previous_posts_link( 'Previous Page' ); ?></li>
					<li><a href="#">...</a></li>
					<li><?php echo get_next_posts_link( 'See More Projects', $the_query->max_num_pages ); ?></li>
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


