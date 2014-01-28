<?php
global $post;
$id = $post->ID;
$summary = the_project_summary($id);
do_action('fh_project_summary_before');
?>
<div class="ign-project-summary col-sm-6 col-md-3 box-one campaign-summary">
	<div class="box-one-inner">
		<a href="<?php echo the_permalink(); ?>">
			<div class="box-one-img">
				<img src="<?php echo $summary->image_url; ?>" class="img-responsive" alt="<?php the_title(); ?>">
			</div>
			<div class="title"><h3><?php echo $summary->name; ?></h3></div>
			<div class="ign-summary-container">
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>%">
						<span class="sr-only"><?php echo number_format(apply_filters('id_percentage_raised', $hDeck->percentage, $id, $hDeck->goal)); ?>% Complete</span>
					</div>
				</div>
				<div class="money-raised">
					<span><?php echo $summary->currency_code.number_format(apply_filters('id_funds_raised', $summary->total, $id), 0, '.', ','); ?></span>
					Raised
				</div>
				<?php if (isset($summary->show_dates) && $summary->show_dates == true) { ?>
				<div class="ign-summary-days days-left">
					<strong><?php echo $summary->days_left; ?></strong>
					<?php echo ($summary->days_left < 0 ? '<span> '.__('Days Left', 'fivehundred').'</span>' : '<span> '.__('Days Left', 'fivehundred').'</span>');?>
				</div>
				<?php } ?>
				<?php echo $summary->short_description; ?>
			</div>
		</a> 
	</div>
</div>