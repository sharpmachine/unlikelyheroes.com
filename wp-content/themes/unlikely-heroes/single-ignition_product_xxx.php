<?php
global $post;
$id = $post->ID;
$project_id = get_post_meta($id, 'ign_project_id', true);
$content = the_project_content($id);
$levels = the_levels($id);
$summary = the_project_summary($id);
$hDeck = the_project_hDeck($id);
$type = get_post_meta($id, 'ign_project_type', true);
	$url = '../../campaign-support/?purchaseform=500&prodid='.$project_id; //getPurchaseURLfromType($project_id, 'purchaseform');
	$custom_order = get_post_meta($id, 'custom_level_order', true);
	if ($custom_order) {
		usort($levels, 'fh_level_sort');
	}
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

				<?php if (isset($_GET['cc_success']) && $_GET['cc_success'] == 1): ?>
					<div class="alert alert-success">
						<h4 class="text-center">You've successfully supported "<?php echo $summary->name; ?>"</h4>
					</div>
				<?php endif; ?>

				<div class="row">
					<div class="col-xs-12 col-md-5 pull-right">
						<h3><?php echo $summary->name; ?></h3>
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>%">
								<span class="sr-only"><?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>% Complete</span>
							</div>
						</div>

						<div class="money-raised">
							<span><?php echo $hDeck->currency_code; ?><?php echo number_format(apply_filters('id_funds_raised', $hDeck->total, $id)); ?></span>
							Raised
						</div>

						<div class="campaign-goal">
							<?php echo $hDeck->currency_code; ?><?php echo number_format($hDeck->goal); ?> Goal
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
							<a href="<?php echo site_url('/campaign-support/', 'https'); ?>?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-lg btn-block">Support Now</a>
							<?php }
							else { ?>
							<a href="<?php echo site_url('/campaign-support/', 'https'); ?>?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-lg btn-block">Support Now</a>
							<?php } ?>
							<?php }?>
						</div>


						<div class="ign-supportnow" data-projectid="<?php echo $project_id; ?>">
							<?php if ($hDeck->end_type == 'closed' && $hDeck->days_left <= 0) {?>
							<a href=""><?php _e('Project Closed', 'fivehundred'); ?></a>
							<?php }else {?>
							<?php if (function_exists('is_id_licensed') && is_id_licensed()) { ?>
								<?php if (empty($permalinks) || $permalinks == '') { ?>
									<a href="<?php the_permalink(); ?>?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>"><?php _e('Support Now', 'fivehundred'); ?></a>
								<?php }
								else { ?>
							 		<a href="<?php the_permalink(); ?>?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>"><?php _e('Support Now', 'fivehundred'); ?></a>
								<?php } ?>
							<?php } ?>
						<?php }?>
						</div>



						<!-- START: .levels -->
						<?php
						foreach ($levels as $level) {
							$level_invalid = getLevelLimitReached($project_id, $id, $level['id']);
							?>
							<?php if (empty($type) || $type == 'level-based') { ?>
							<?php } ?>
							<div class="level-group">
								<div class="ign-level-title">
									<span> <?php echo $level['title'] ?></span>
									<div class="level-price">
										<?php if ($type !== 'pwyw') { ?>
										<?php echo $level['currency_code']; ?><?php echo $level['price'] ?>
										<?php } ?>
									</div>
								</div>
								<div class="ign-level-desc">
									<?php echo $level['description'] ?>
								</div>
								<?php if ($level['limit'] !== '' && $level['limit'] > 0) { ?>
								<div class="ign-level-counts">
									<span><small><?php _e('Limit', 'fivehundred'); ?>: <?php echo $level['sold'] ?> of <?php echo $level['limit'] ?> <?php _e('taken', 'fivehundred'); ?>.</small></span>
								</div>
								<?php } ?>
							</div>
							<?php if (empty($type) || $type == 'level-based') { ?>

							<?php } ?>
							<?php } ?>
							<!-- END: .levels -->
							<hr class="visible-sm visible-xs">
						</div>

						<div class="col-xs-12 col-md-7 pull-left">

							<div class="video-container" style="background-image: url(<?php echo $summary->image_url; ?>)">
								<?php echo the_project_video($id); ?>
							</div>
							<?php get_template_part('project', 'social'); ?>

							<div class="project-content">

								<?php if (the_project_video($id)):?>
									<img src="<?php echo $summary->image_url; ?>" class="img-responsive" alt="<?php echo $summary->name; ?>">
									<br>
								<?php endif; ?>
								
								<?php echo $content->long_description; ?>
							</div>
							<div class="visible-xs">
								<div class="ign-supportnow" data-projectid="<?php echo $project_id; ?>">
							<?php if ($hDeck->end_type == 'closed' && $hDeck->days_left <= 0) {?>
							<a href=""><?php _e('Project Closed', 'fivehundred'); ?></a>
							<?php }else {?>
							<?php if (empty($permalinks) || $permalinks == '') { ?>
							<a href="<?php bloginfo('url'); ?>/campaign-support/?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-lg btn-block">Support Now</a>
							<?php }
							else { ?>
							<a href="<?php bloginfo('url'); ?>/campaign-support/?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-lg btn-block">Support Now</a>
							<?php } ?>
							<?php }?>
						</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<?php get_footer(); ?>
