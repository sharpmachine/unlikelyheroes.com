<?php 
/*
	Template Name: Blog
*/
get_header(); ?>

<div id="blog-landing" class="page">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<?php get_template_part( 'loop', 'page' ); ?>
				
				<?php rewind_posts(); ?>
				
				<?php get_template_part( 'loop', 'blog-landing' ); ?>
				
			</div>
			<div class="col-lg-4">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</div><!-- .page -->

<?php get_footer(); ?>
