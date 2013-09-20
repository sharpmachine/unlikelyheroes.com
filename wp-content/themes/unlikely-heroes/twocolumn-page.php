<?php
/**
 * Template Name: Two column, with sidebar
 *
 * A custom page template with sidebar.
 *
 */

get_header(); ?>

<div id="page-sidebar" class="page">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<?php get_template_part( 'loop', 'page' ); ?>
			</div>
			<div class="col-lg-4">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</div><!-- #page -->

<?php get_footer(); ?>