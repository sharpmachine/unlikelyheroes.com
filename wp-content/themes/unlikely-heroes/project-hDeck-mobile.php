<?php
global $post;
$id = $post->ID;
$hDeck = the_project_hDeck($id);
$project_id = get_post_meta($id, 'ign_project_id', true);
$permalinks = get_option('permalink_structure');
$summary = the_project_summary($id);
do_action('fh_hDeck_before');
?>

<div class="ign-supportnow" data-projectid="<?php echo $project_id; ?>">
	<?php if ($hDeck->end_type == 'closed' && $hDeck->days_left <= 0) {?>
	<a href=""><?php _e('Project Closed', 'fivehundred'); ?></a>
	<?php }else {?>
	<?php if (function_exists('is_id_licensed') && is_id_licensed()) { ?>
	<?php if (empty($permalinks) || $permalinks == '') { ?>
	<a href="<?php the_permalink(); ?>?purchaseform=500&amp;mdid_checkout=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-lg btn-block"><?php _e('Support Now', 'fivehundred'); ?></a>
	<?php }
	else { ?>
	<a href="<?php the_permalink(); ?>?purchaseform=500&amp;mdid_checkout=<?php echo (isset($project_id) ? $project_id : ''); ?>" class="btn btn-lg btn-block"><?php _e('Support Now', 'fivehundred'); ?></a>
	<?php } ?>
	<?php } ?>
	<?php }?>
</div>