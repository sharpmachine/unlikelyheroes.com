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


	<div class="jumbotron jumbotron-home">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h1>
						To the 27 million people trapped in slavery
						<span>We are coming for you</span>
					</h1>
				</div>
			</div>
		</div>
	</div>

	<div class="section section-gray text-center">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-2 col-md-8">
					<p class="lead">
						<?php the_field('cta_inspiring_paragraph', 'options'); ?>
					</p>
					<div class="hidden-xs">
						<a href="<?php bloginfo('url' ); ?>/get-involved" class="btn btn-teal btn-lg"><?php the_field('cta_long_button_label', 'options'); ?></a>
					</div>
					<div class="visible-xs">
						<a href="<?php bloginfo('url' ); ?>/get-involved" class="btn btn-teal btn-lg"><?php the_field('cta_short_button_label', 'options'); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="text-center mobile-updates visible-xs">
		<span>Lastest Update:</span>
	<?php $args = array( 'post_type' => 'lastest_updates', 'posts_per_page' => 1); ?>
	<?php $latest_updates = new WP_Query( $args ); ?>

	<?php if ( $latest_updates->have_posts() ) : ?>

	<?php while ( $latest_updates->have_posts() ) : $latest_updates->the_post(); ?>
	<?php the_short_title(50); ?> <a href="<?php the_permalink(); ?>" class="read-more">Read More &#8594;</a>
<?php endwhile; ?>

<?php wp_reset_postdata(); ?>

<?php else:  ?>
	<?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; ?>
</div>

<div class="section section-dark-gray text-center">
	<div class="container">
		<div class="row">
			<div class="col-md-offset-2 col-md-8">
				<h2 class="text-center"><?php the_field('featured_video_video_title', 'options'); ?></h2>
				<div class="video-container">
					<?php the_field('featued_video_embed_code', 'options'); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container">

		<?php if( have_rows('heroic_events', 'option') ): ?>

	<div class="section heroic-events boxes-one">
		<h2 class="text-center">Heroic Events</h2>
		<div class="row past-events">

		<?php while ( have_rows('heroic_events', 'option') ) : the_row(); ?>
		
			<?php 
		$attachment_id = get_sub_field('event_photo', 'option');
		$size = "thumbnail-box";
		$image = wp_get_attachment_image_src( $attachment_id, $size );
	?>

			<div class="col-sm-6 col-md-3 box-one">
				<div class="box-one-inner">
					<a href="<?php the_sub_field('event_page_link', 'option'); ?>">
						<div class="box-one-img">
							<img src="<?php echo $image[0]; ?>" class="img-responsive" />
						</div>
						<h3><?php the_sub_field('event_name', 'option'); ?></h3>
						<p><?php the_sub_field('event_description', 'option'); ?></p>
					</a>
				</div>
			</div>

			<?php endwhile; ?>
			<div class="col-sm-6 col-md-3 box-one hidden">
				<div class="box-one-inner">
					<a href="#">
						<div class="box-one-img box-one-action">
							<img src="<?php bloginfo('template_directory'); ?>/img/township.jpg" alt="" class="img-responsive">
							<div class="arrow"></div>
						</div>
						<h3>All Events</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit harum optio saepe quam nostrum! Perferendis, eveniet unde tempora molestias ut.</p>
					</a>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div id="content" class="section heroic-projects boxes-one">
			<h2 class="entry-title text-center"><?php _e('Heroic Campaigns', 'fivehundred'); ?></h2>
			<div id="project-grid" class="row">
				<?php 
				if (is_front_page()) {
					get_template_part('loop', 'project');
					get_template_part('create','campaign');
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
					<?php } ?>
				</div>
			</div>
		</div>
	</div><!-- END: .container -->
	
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
			<?php } ?>
			<?php get_footer(); ?>