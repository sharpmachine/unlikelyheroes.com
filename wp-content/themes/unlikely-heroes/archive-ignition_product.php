<?php get_header(); ?>

<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<span>Heroic Campaigns</span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="container">

	<div class="section boxes-one">
		<div class="row">

			<?php $args = array( 'post_type' => 'ignition_product','posts_per_page' => 2, 'paged' => $paged); ?>
			<?php $all_campaigns = new WP_Query( $args ); ?>

			<?php if ( $all_campaigns->have_posts() ) : ?>

			<?php while ( $all_campaigns->have_posts() ) : $all_campaigns->the_post(); ?>
			<?php
			$content = the_project_content($id);
			$project_id = get_post_meta($id, 'ign_project_id', true);
			$summary = the_project_summary($id);
			$hDeck = the_project_hDeck($id);
			do_action('fh_project_summary_before');
			?>

			<div class="col-sm-6 col-md-3 box-one">
				<div class="box-one-inner">
					<a href="<?php the_permalink(); ?>">
						<div class="box-one-img">
							<img src="<?php echo $summary->image_url; ?>" class="img-responsive" alt="<?php the_title(); ?>">
						</div>
						<h3><?php the_title(); ?></h3>
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 50<?php //echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>%">
								<span class="sr-only"><?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>% Complete</span>
							</div>
						</div>
						<div class="money-raised">
							<span><?php echo $hDeck->currency_code; ?><?php echo number_format(apply_filters('id_funds_raised', $hDeck->total, $id), 2, '.', ','); ?></span>
							Raised
						</div>
						<?php if (isset($hDeck->show_dates) && $hDeck->show_dates == true) { ?>

						<div class="days-left"><?php echo $hDeck->days_left; ?> Days Left</div>

						<?php } ?>
						<p><?php echo $summary->short_description; ?></p>
					</a>
				</div>
			</div>

		<?php endwhile; ?>

		<?php wp_reset_postdata(); ?>

		<?php else:  ?>
		<?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
		<?php endif; ?>

			<div class="col-sm-6 col-md-3 box-one">
				<div class="box-one-inner">
					<a href="<?php bloginfo('url'); ?>/dashboard/?create_project=1">
						<div class="box-one-img box-one-action">
							<img src="<?php bloginfo('template_directory'); ?>/img/township.jpg" alt="" class="img-responsive">
							<div class="plus"></div>
						</div>
						<h3>Create a campaign</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit harum optio saepe quam nostrum!  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit harum optio saepe quam nostrum! Perferendis, eveniet unde tempora.</p>
					</a>
				</div>
			</div>
		</div>
	</div>

	<?php echo bootstrap_pagination(); ?>

</div><!-- .container-->

<?php get_footer(); ?>
