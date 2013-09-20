
<?php 
/*
	Template Name: Home
* 
* If Front page displays settings are set via A Static Page, 
* this file needs to be named page-home.php, otherwise just call it home.php
*/
get_header(); ?>

<div class="page">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h1>Bootstrap starter theme 3.0</h1>
				<p>Use this theme as a way to quick start any new project.<br> All you get is this message and a barebones HTML document.</p>

				<?php query_posts('showposts=3'); ?>
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'loop', 'post' ) ?>
				<?php endwhile; else: ?>
					<p>No posts found</p>
				<?php endif; ?>
			</div>
		</div>
	</div>	
</div><!-- .page -->

<?php get_footer(); ?>
