<?php
/*
Template Name: Project Grid (Home)
*/
?>
<?php 
	global $post;
	$settings = get_option('fivehundred_theme_settings');
	$display_count = $settings['home_projects'];
	$num_projects = wp_count_posts('ignition_product');
	$num_projects_pub = $num_projects->publish;
	if ($display_count < $num_projects_pub) {
		$show_more = 1;
	}
	else {
		$show_more = 0;
	}
	$url = site_url('/');
	$tagline = get_bloginfo('description'); 
	if ($settings) {
		$twitter = $settings['twitter'];
		$fb = $settings['fb'];
		$google = $settings['google'];
		$li = $settings['li'];
		$via = $settings['twitter_via'];
		$fbname = $settings['fb_via'];
		$gname = $settings['g_via'];
		$liname = $settings['li_via'];
		$about_us = html_entity_decode($settings['about']);
	}
	else {
		$via = null;
		$fbname = null;
		$gname = null;
		$liname = null;
		$twitter = null;
		$fb = null;
		$google = null;
		$li = null;
		$about_us = null;
	}
	$options = get_option('fivehundred_featured');
?>
<?php if (isset($settings['home']) && !empty($settings['home'])) {
	get_header(); ?>
	<div id="container">
		<article id="content" class="ignition_project project-home">
			<?php get_template_part( 'project', 'content-home' ); ?>
		</article>
	<div class="clear"></div>
	</div>
<?php get_footer(); ?>
<?php } else if (is_home()) { ?>
	<?php get_header(); ?>
	<div id="container">
		<div class="ign-project-content ign-project-top"><?php if (dynamic_sidebar('home-top-content-widget-area')) : ?><?php endif; ?></div>
		<?php if (!empty($options)) {?>
		<div class="breakout-out">
			<div class="breakout-in">
				<?php get_template_part('project-featured'); ?>
			</div>
		</div>
		<?php } ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div id="content">
			<h2 class="entry-title"><?php _e('Featured Projects', 'fivehundred'); ?></h2>
				<?php get_template_part( 'nav', 'above-grid' ); ?>
				<div id="project-grid">
					<?php 
					if (is_front_page()) {
						get_template_part('loop', 'project');
					}
					else {
						$paged = (get_query_var('paged') ? get_query_var('paged') : 1);
						$query = new WP_Query(array('paged' => 'paged', 'posts_per_page' =>1, 'paged' => $paged));

						// Start the loop
						if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
							get_template_part('entry');
							endwhile;
							endif; 
						wp_reset_postdata();
						?>
						<div class="nav-previous"><?php next_posts_link(); ?></div>
						<div class="nav-next"><?php previous_posts_link(); ?></div>
					<?php } ?>
					
				</div>
				<div style="clear: both;"></div>
				<div  id="home-sharing">
				<ul>
					<?php echo ($twitter == 1 ? '<li class="twitter-btn"><a href="http://twitter.com/'.$via.'" target="_blank" ><span>'.__('Follow', 'fivehundred').'</span></a></li>' : ''); ?>
					<?php echo ($fb == 1 ? '<li class="facebook-btn"><a href="http://www.facebook.com/'.$fbname.'" target="_blank"><span>'.__('Like', 'fivehundred').'</span></a></li>' : ''); ?>
					<?php echo ($google == 1 ? '<li class="gplus-btn"><a href="https://plus.google.com/'.$gname.'" target="_blank"><span>'.__('+1', 'fivehundred').'</span></a></li>' : ''); ?>
					<?php echo ($li == 1 ? '<li class="linkedin-btn"><a href="http://linkedin.com/in/'.$liname.'" target="_blank"><span>'.__('Connect', 'fivehundred').'</span></a></li>' : ''); ?>
					<!-- prob want to get category here -->
					<?php echo ($show_more ? '<li class="ign-more-projects"><a href="'.get_post_type_archive_link("ignition_product").'">'. __('More', 'fivehundred').' <span>'.__('Projects', 'fivehundred').'</span></a></li>' : ''); ?>
				</ul>
				</div>
				<hr class="fancy" />
				<div id="ign-project-content" class="ign-project-content"><?php if (dynamic_sidebar('home-content-widget-area')) : ?></div>
				<?php endif; ?>
				<div id="about-us" class="entry-content">
					<div id="about"><?php echo $about_us; ?></div>
				</div>
				<div id="home-widget">
					<?php get_sidebar('home'); ?>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
<?php } else { ?>
	<?php get_header(); ?>
	<div id="container">
		<div id="site-description">
			<h1><?php bloginfo( 'description' ) ?></h1>
		</div>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div id="content">
				<?php if (have_posts()) {
					while (have_posts()) {
						the_post();
					}
				}
				the_content();
				?>
			</div>
		<div class="clear"></div>
	</div>
<?php } ?>
<?php get_footer(); ?>