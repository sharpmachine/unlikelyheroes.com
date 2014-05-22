<?php
// This gets us the data we need for the featured section
	$options = get_option('fivehundred_featured');
	if ($options) {
		$featured_proj = $options['project_id'];
		$featured_post = $options['post_id'];
		$summary = the_project_summary($featured_post);
		$pledges_count = get_backer_total($featured_post);
		//$tthumb = plugins_url('ignitiondeck/products/timthumb.php', 'ignitiondeck');
	}
	do_action('fh_hDeck_before');
?>
<div class="featured-wrap">
	<a href="<?php echo get_permalink($featured_post); ?>">
		<div class="featured-image" style="background-image: url(<?php echo $summary->image_url; ?>)">&nbsp;</div>
	</a>
	<div class="featured-info">
		<div class="featured-border">
			<h3><?php echo $summary->name; ?></h3>
			<p><?php echo $summary->short_description ;?></p>
			<div class="featured-inner">
				<div class="featured-item">
					<strong><?php _e('Goal',  'fivehundred'); ?>: </strong><span><?php echo $summary->currency_code.number_format($summary->goal, 2, '.', ','); ?></span>
				</div>
				<div class="featured-item">
					<strong><?php _e('Raised',  'fivehundred'); ?>: </strong><span><?php echo $summary->currency_code.number_format(apply_filters('id_funds_raised', $summary->total, $id), 2, '.', ','); ?></span>
				</div>
				<div class="featured-item">
					<strong><?php _e('Supporters',  'fivehundred'); ?>: </strong><span><?php echo number_format(apply_filters('id_number_pledges', $pledges_count, $featured_post)); ?></span>
				</div>
				<div class="featured-item">
					<strong><?php _e('Days Left',  'fivehundred'); ?>: </strong><span><?php echo $summary->days_left; ?></span>
				</div>
				<div class="featured-item">
					<div class="ign-progress-wrapper" style="clear: both;">
						<!-- end progress-percentage -->
						<div style="width: <?php echo apply_filters('id_percentage_raised', $summary->percentage, $id, $summary->goal); ?>%" class="ign-progress-bar">
						
						</div>
						<!-- end progress bar -->
					</div>
				</div>
			</div>
			<a class="featured-button" href="<?php echo get_permalink($featured_post); ?>">
				<span><?php _e('Learn More', 'fivehundred'); ?></span>
			</a>
		</div>
	</div>
</div>