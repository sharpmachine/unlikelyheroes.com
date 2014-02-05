<?php
global $post;
$id = $post->ID;
$hDeck = the_project_hDeck($id);
$project_id = get_post_meta($id, 'ign_project_id', true);
$permalinks = get_option('permalink_structure');
$summary = the_project_summary($id);
do_action('fh_hDeck_before');
?>
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
	<?php if (function_exists('is_id_licensed') && is_id_licensed()) { ?>
	<?php if (empty($permalinks) || $permalinks == '') { ?>
	<a href="<?php the_permalink(); ?>?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-lg btn-block"><?php _e('Support Now', 'fivehundred'); ?></a>
	<?php }
	else { ?>
	<a href="<?php the_permalink(); ?>?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-lg btn-block"><?php _e('Support Now', 'fivehundred'); ?></a>
	<?php } ?>
	<?php } ?>
	<?php }?>
</div>

<?php get_template_part( 'project', 'sidebar' ); ?>