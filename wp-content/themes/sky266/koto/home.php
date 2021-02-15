
<!--
Template Name: home
Author: Mphanya
-->

<?php get_header(); ?>
<!-- Sectionone ends ======================================== -->
<section id="bodySection">
<div class="container">
<div class="row-fluid">
<div class="span9 mspan12">

      <!--div class="intro">
				<p><?php  $page = get_page_by_title( 'About Us' );   $content = apply_filters( 'the_exept' , $page->post_content );  $link = get_permalink($page->ID);
                echo wp_trim_words( display_content_without_img($content), 120,ALE_excerpt_more($link) ); ?></p>
                <h4> Listed below are the sectors within Southline Group</h4>
      </div-->

            <div class="row-fluid" style="text-align:center">
            <?php
                         $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                         $services =  query_posts( "post_type=sectors&posts_per_page=9&paged=$paged");
                         $i=0;
                 foreach ( $services as $service) : setup_postdata($service);
               ?>   <?php $post_id= $service->ID ;
                          $post_image= get_one_image($service->post_content,$post_id);
                          $link = get_permalink($post_id);

                ?>

			<div class="span4 mspan4 sspan6">
				<div class="well well-small" id="<?php echo $post_id; ?>">
                        <h4> <a href="<?php echo $link; ?>">  <?php echo apply_filters( 'the_title' , $service->post_title ); ?>   </a>    </h4>
                            <a href="<?php echo $link; ?>" >
                               <figure class="post-thumbnail" id="img<?php echo $post_id; ?>">
                                 <img src="<?php  echo $post_image; ?>">
    						</figure>
							  </a>
							<div class="entry-content" id="<?php echo "div".$post_id; ?>">
                            <div class="entry-header">
								<p class="ent"><?php echo wp_trim_words(display_content_without_img( apply_filters( 'the_content' , $service->post_content )),40,ALE_excerpt_more($link)); ?></p>

                                <div class="btn-toolbar">
                    			  <div class="btn-group toolTipgroup">
                    				<a class="btn" href="#" data-placement="right" data-original-title="send email"><i class="icon-envelope"></i></a>
                    				<a class="btn" href="#" data-placement="top" data-original-title="do you like?"><i class="icon-thumbs-up"></i></a>
                    				<a class="btn" href="#" data-placement="top" data-original-title="dont like?"><i class="icon-thumbs-down"></i></a>
                    				<a class="btn" href="#" data-placement="top" data-original-title="share"><i class="icon-link"></i></a>
                    				<a class="btn" href="portfolio.html" data-placement="left" data-original-title="browse"><i class="icon-globe"></i></a>
                    			  </div>
                    			</div>
							</div>
					  </div>
             </div>
			</div>
            <?php $i++;   endforeach;  ?>

                <div class="clear"></div>
           </div>
				<div class="pagination pull-right">
				  <ul>
					<li><?php echo get_previous_posts_link( 'Previous Page' ); ?></li>
					<li><a href="#">...</a></li>
					<li><?php echo get_next_posts_link( 'See More Sectors', $the_query->max_num_pages ); ?></li>
				  </ul>
				</div>

            <?php wp_reset_postdata(); ?>

</div>
<?php get_sidebar(); ?>
</div>

</div>

</section>
<?php get_footer(); ?>
