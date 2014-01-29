<?php
$content = the_project_content($id);
$project_id = get_post_meta($id, 'ign_project_id', true);
$summary = the_project_summary($id);
$hDeck = the_project_hDeck($id);
do_action('fh_project_summary_before');
?>

<div class="col-sm-6 col-md-3 box-one campaign-summary">
	<div class="box-one-inner">
		<a href="<?php the_permalink(); ?>">
			<div class="box-one-img">
				<img src="<?php echo the_project_image_thumb($id, 1); ?>" class="img-responsive" alt="<?php the_title(); ?>">
			</div>
			<h3><?php the_short_title(40); ?></h3>
			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>%">
					<span class="sr-only"><?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>% Complete</span>
				</div>
			</div>
			<div class="money-raised">
				<span><?php echo $hDeck->currency_code; ?><?php echo number_format(apply_filters('id_funds_raised', $hDeck->total, $id)); ?></span>
				Raised
			</div>
			<?php if (isset($hDeck->show_dates) && $hDeck->show_dates == true) { ?>

			<div class="days-left"><?php echo $hDeck->days_left; ?> Days Left</div>

			<?php } ?>
			<?php echo $summary->short_description; ?>
		</a>
	</div>
</div>