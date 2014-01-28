<?php
global $post;
$id = $post->ID;
$summary = the_project_summary($id);
do_action('fh_project_summary_before');
?>
<div class="ign-project-summary">
	<a href="<?php echo the_permalink(); ?>">
	<div class="title"><h3><?php echo $summary->name; ?></h3></div>
	<div class="ign-summary-container">
		<div class="ign-summary-image" style="background-image: url(<?php echo $summary->image_url; ?>)"></div>
		<span class="ign-summary-desc"><?php echo $summary->short_description; ?></span>
		<div class="ign-progress-wrapper">
			<div class="ign-progress-raised"><?php echo $summary->currency_code.number_format(apply_filters('id_funds_raised', $summary->total, $id), 2, '.', ','); ?> <?php _e('RAISED', 'fivehundred'); ?></div>
			<div class="ign-progress-bar" style="width: <?php echo number_format(apply_filters('id_percentage_raised', $summary->percentage, $id, $summary->goal)).'%'; ?>"></div>
			<div class="ign-progress-percentage"><?php echo number_format(apply_filters('id_percentage_raised', $summary->percentage, $id, $summary->goal)).'%'; ?></div>
		</div>
		<?php if (isset($summary->show_dates) && $summary->show_dates == true) { ?>
		<div class="ign-summary-days">
			<strong><?php echo $summary->days_left; ?></strong>
			<?php echo ($summary->days_left < 0 ? '<span> '.__('Days Left', 'fivehundred').'</span>' : '<span> '.__('Days Left', 'fivehundred').'</span>');?>
		</div>
		<?php } ?>
		<div class="ign-summary-learnmore"><?php _e('Learn More', 'fivehundred'); ?></div>
	</div>
	</a> 
</div>