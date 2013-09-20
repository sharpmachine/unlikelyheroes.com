<?php get_header(); ?>

<div id="single-post" class="page">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<?php get_template_part( 'loop', 'single' ); ?>
			</div>
			<div class="col-lg-4">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</div><!-- #page -->

<?php get_footer(); ?>
