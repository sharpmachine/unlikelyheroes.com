<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php get_template_part( 'loop', 'post' ) ?>
<?php endwhile; ?>
	<?php bootstrap_pagination(); ?>
<?php else: ?>
	<p>No posts found</p>
<?php endif; ?>
