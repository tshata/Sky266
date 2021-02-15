<?php
/**
*Template Name: homepage
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

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
	   <div align="center" id="container" >
	<div id = "left-div">
		<div id="slider">
			<figure>
              <img src="<?php echo get_template_directory_uri().'/images/fifi.png'; ?>" alt="" height=""400" />
              <img src="<?php echo get_template_directory_uri().'/images/fifi.png'; ?>" alt="" height=""400" />
              <img src="<?php echo get_template_directory_uri().'/images/fifi.png'; ?>" alt="" height=""400" />
              <img src="<?php echo get_template_directory_uri().'/images/fifi.png'; ?>" alt="" height=""400" />
              <img src="<?php echo get_template_directory_uri().'/images/fifi.png'; ?>" alt="" height=""400" />

			</figure>
		</div>

		<div id = "hom">
				<h1>WELCOME TO SKY LOGISTICS WEBSITE</h1>
		</div>
		<div id = "tab">
			<table style="width:70%" align="center">
				<tr>
					<th><b>Services Overview</th>
					<th><b>Our Partners</th>
				</tr>
				<tr>
					<th>We aid with transportation that is normally used . <br>to ship large and heavy goods via the ocean or sea from China to Lesotho<br>
						It is cost effective<br>ideal for heavy and bulky <br>
						goods, it is scalable for long term <br>
						<br>and there is great flexibility <br>
					   flexibility in the cargo that’s transported.<br>
						Our service helps with transportinggoods or <br>
					    produce by aircraft from China to Lesotho<br>
					   With this kind of transportation goods are delivered on time<br>
						there are reduced damage </th>
					<th>Upgrade to a supporting browser </th>
				</tr>
			</table>
		</div>
	</div>


	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<!--?php get_sidebar(); ?>-->
<?php get_footer(); ?>
