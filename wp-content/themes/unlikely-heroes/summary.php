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
	<div class="row main-content">
		<div class="col-md-10 col-md-offset-1">
			<div class="row">
				<div class="col-md-8">
					<div class="video-container">
						<?php echo the_project_video($id); ?>
					</div>
					<?php echo $content->long_description; ?>
				</div>
				<div class="col-md-4">

					<?php echo the_permalink(); ?>

					<?php echo $summary->name; ?>

					<div class="ign-summary-image" style="background-image: url(<?php echo $summary->image_url; ?>)"></div>

					<?php //echo $summary->short_description; ?>

					<?php echo $summary->currency_code.number_format(apply_filters('id_funds_raised', $summary->total, $id), 2, '.', ','); ?> <?php _e('RAISED', 'fivehundred'); ?>



					<div class="progress">
						<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo number_format(apply_filters('id_percentage_raised', $summary->percentage, $id, $summary->goal)).'%'; ?>">
							<span class="sr-only">60% Complete</span>
						</div>
					</div>

					<div class="ign-product-supporters" style="clear: both;">
						<strong><?php echo number_format(apply_filters('id_number_pledges', $hDeck->pledges, $id), 2, '.', ','); ?></strong>
						<div class="ign-supporters">
							<?php _e('Supporters', 'fivehundred'); ?>
						</div>
					</div>

					<?php if (isset($summary->show_dates) && $summary->show_dates == true) { ?>

					<?php echo $summary->days_left; ?></strong>
					<?php echo ($summary->days_left < 0 ? '<span> '.__('Days Left', 'fivehundred').'</span>' : '<span> '.__('Days Left', 'fivehundred').'</span>');?>

					<?php } ?>
					<br>
					<?php _e('Learn More', 'fivehundred'); ?>

					<div class="ign-supportnow" data-projectid="<?php echo $project_id; ?>">
							<?php if ($hDeck->end_type == 'closed' && $hDeck->days_left <= 0) {?>
								<a href=""><?php _e('Project Closed', 'fivehundred'); ?></a>
						<?php }else {?>
							<?php if (empty($permalinks) || $permalinks == '') { ?>
								<a href="<?php echo get_permalink($id); ?>&amp;purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>"><?php _e('Support Now', 'fivehundred'); ?></a>
							<?php }
							else { ?>
						 		<a href="<?php echo get_permalink($id); ?>?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>"><?php _e('Support Now', 'fivehundred'); ?></a>
							<?php } ?>
						<?php }?>
						</div>
				</div>
			</a> 
		</div>
		<h1><?php //echo $content->name; ?> </h1>
		<?php //echo do_shortcode( '[project_page_widget product="'.$project_id.'"]') ?>
	</div>
</div>

<?php //echo do_shortcode( '[project_page_content product="'.$project_id.'"]') ?>
<?php //echo do_shortcode( '[project_page_widget product="'.$project_id.'"]') ?>
</div>
</div>
</div>


<?php get_footer(); ?>
