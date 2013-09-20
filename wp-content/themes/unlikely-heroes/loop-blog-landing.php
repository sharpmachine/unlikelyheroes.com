<?php 
/*
* Modified loop is using the home.php/blog.php method
*/ 
?>

<?php
	$temp = $wp_query;
	$wp_query = null;
	$wp_query = new WP_Query();
	$wp_query->query('&paged='.$paged);
	while ($wp_query->have_posts()) : $wp_query->the_post();
?>

	<?php get_template_part( 'loop', 'post' ) ?>
	
<?php endwhile; ?>

<?php bootstrap_pagination(); ?>
<?php $wp_query = null; $wp_query = $temp;?>
