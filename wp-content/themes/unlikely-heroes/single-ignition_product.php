<?php
	global $post;
	$id = $post->ID;
	$content = the_project_content($id);
	$project_id = get_post_meta($id, 'ign_project_id', true);
	$summary = the_project_summary($id);
	$hDeck = the_project_hDeck($id);
	do_action('fh_project_summary_before');
?>
<?php get_header(); ?>

<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<span>Support a campaign </span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row main-content single-campaign">
		<div class="col-md-10 col-md-offset-1">
			<div class="row">
				<div class="col-md-7">
					<div class="video-container" style="background-image: url(<?php echo $summary->image_url; ?>)">
						<?php echo the_project_video($id); ?>
					</div>
					<?php get_template_part('project', 'social'); ?>
					<?php echo $content->long_description; ?>
					<?php //echo $summary->image_url; ?>
				</div>
				<div class="col-md-5">
					<h3><?php echo $summary->name; ?></h3>

					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 50<?php //echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>%">
							<span class="sr-only"><?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>% Complete</span>
						</div>
					</div>

					<div class="money-raised">
						<span><?php echo $hDeck->currency_code; ?><?php echo number_format(apply_filters('id_funds_raised', $hDeck->total, $id), 2, '.', ','); ?></span>
						Raised
					</div>

					

					<div class="campaign-goal">
						<?php echo $hDeck->currency_code; ?><?php echo number_format($hDeck->goal, 2, '.', ','); ?> Goal
					</div>

					<div class="number-of-supporters">
						<span><?php echo number_format(apply_filters('id_number_pledges', $hDeck->pledges, $id)); ?></span>
						Supporters
					</div>

					<?php if (isset($hDeck->show_dates) && $hDeck->show_dates == true) { ?>
					
					<div class="days-left"><?php echo $hDeck->days_left; ?> Days Left</div>

					<?php } ?>


					<div class="ign-supportnow" data-projectid="<?php echo $project_id; ?>">
						<?php if ($hDeck->end_type == 'closed' && $hDeck->days_left <= 0) {?>
						<a href=""><?php _e('Project Closed', 'fivehundred'); ?></a>
						<?php }else {?>
						<?php if (empty($permalinks) || $permalinks == '') { ?>
						<a href="<?php echo get_permalink($id); ?>&amp;purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-lg btn-block">Support Now</a>
						<?php }
						else { ?>
						<a href="<?php echo get_permalink($id); ?>?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-lg btn-block">Support Now</a>
						<?php } ?>
						<?php }?>
					</div>

				</div>

			</div>

		</div>
	</div>
</div>


<?php get_footer(); ?>
