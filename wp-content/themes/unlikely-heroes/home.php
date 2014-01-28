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

	
	<div class="section boxes-one">
		<h2 class="text-center">Heroic Events</h2>
		<div class="row">

		<?php while ( have_rows('heroic_events', 'option') ) : the_row(); ?>
		
			<?php 
		$attachment_id = get_sub_field('event_photo', 'option');
		$size = "single-thumb";
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

	<?php else: ?>
	<div class="section boxes-one">
		<h2 class="text-center">Heroic Campaigns</h2>
		<div class="row">
			
			<?php $args = array( 'post_type' => 'ignition_product','category_name' => 'featured' ,'posts_per_page' => 3); ?>
			<?php $all_campaigns = new WP_Query( $args ); ?>

			<?php if ( $all_campaigns->have_posts() ) : ?>
				<?php while ( $all_campaigns->have_posts() ) : $all_campaigns->the_post(); ?>
					<?php get_template_part('campaign','listing'); ?>
				<?php endwhile; ?>
					<?php get_template_part('create','campaign'); ?>
					<?php wp_reset_postdata(); ?>
						<div class="clearfix"></div>
						<div class="text-center">
							<a href="<?php bloginfo('url'); ?>/campaigns" class="btn btn-lg">All Campaigns</a>
						</div>

			<?php else:  ?>
				<h3 class="text-center"><?php _e( 'No campaigns yet.  Create one now!' ); ?></h3>
				<?php get_template_part('create','campaign'); ?>
				<?php get_template_part('create','campaign'); ?>
				<?php get_template_part('create','campaign'); ?>
				<?php get_template_part('create','campaign'); ?>
			<?php endif; ?>
			
		</div>
	</div>
	<?php endif; ?>
</div><!-- END: container -->
<?php get_footer(); ?>
