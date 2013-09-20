<?php 
/*
* This is only used is Front page displays settings are set via A static page.
*/
get_header(); ?>

<div id="blog-landing" class="page">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			<h2>Blog</h2>
				<?php get_template_part( 'loop', 'blog' ); ?>
			</div>
		</div>
	</div>
</div><!-- .page -->

<?php get_footer(); ?>
