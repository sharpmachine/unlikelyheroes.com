<?php get_header(); ?>

<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<span>The Latest</span>
				</h1>
			</div>
		</div>
	</div>
</div>

<?php
	/* Queue the first post, that way we know
	 * what date we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
	?>

	<div class="container">
		<div class="row main-content">
			<div class="col-lg-8 col-lg-offset-2">
			<?php
			/* Since we called the_post() above, we need to
			 * rewind the loop back to the beginning that way
			 * we can run the loop properly, in full.
			 */
			rewind_posts();

			/* Run the loop for the archives page to output the posts.
			 * If you want to overload this in a child theme then include a file
			 * called loop-archive.php and that will be used instead.
			 */
			 // get_template_part( 'loop', 'archive' );
			if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'loop', 'post' ) ?>
		<?php endwhile; ?>
		<?php bootstrap_pagination(); ?>
		<?php else: ?>
			<p>No posts found</p>
		<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
