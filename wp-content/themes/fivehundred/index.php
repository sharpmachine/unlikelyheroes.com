<?php get_header(); ?>
<div id="container">
<div id="site-description">
	<h1><?php the_title(); ?></h1>
</div>
<div id="content">
	<?php //get_template_part( 'nav', 'above' ); ?>
<?php while ( have_posts() ) : the_post() ?>
	<?php get_template_part( 'entry' ); ?>
<?php comments_template(); ?>
<?php endwhile; ?>
	<?php get_template_part( 'nav', 'below' ); ?>
</div>
<?php get_sidebar(); ?>
<div class="clear"></div>
</div>
<?php get_footer(); ?>