<?php
// need to set this in admin
$paged = (get_query_var('paged')) ? get_query_var('paged') : 10;
$posts_per_page = get_option('posts_per_page');
if (empty($posts_per_page)) {
	$posts_per_page = 10;
}
$query = new WP_Query(array('paged' => 'paged', 'posts_per_page' =>$posts_per_page));
// Start the loop
if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
	<?php get_template_part('entry'); ?>
<?php endwhile; ?>
<?php endif; ?>
<?php next_posts_link(__('&laquo; Older Entries', 'fivehundred'), $query->max_num_pages) ?>
<?php previous_posts_link(__('Newer Entries &raquo;', 'fivehundred')); ?>
<?php wp_reset_postdata(); ?>