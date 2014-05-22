<?php
global $post;
$id = $post->ID;
$levels = the_levels($id);
$type = get_post_meta($id, 'ign_project_type', true);
$end_type = get_post_meta($id, 'ign_end_type', true);
$project_id = get_post_meta($id, 'ign_project_id', true);
$project = new ID_Project($project_id);
$days_left = $project->days_left();
$permalink_structure = get_option('permalink_structure');
if (empty($permalink_structure)) {
	$url_suffix = '&';
}
else {
	$url_suffix = '?';
}
$url = get_permalink($id).$url_suffix.'purchaseform=500&prodid='.$project_id;//getPurchaseURLfromType($project_id, 'purchaseform');
$custom_order = get_post_meta($id, 'custom_level_order', true);
if ($custom_order) {
	usort($levels, 'fh_level_sort');
}

foreach ($levels as $level) {
	$level_invalid = getLevelLimitReached($project_id, $id, $level['id']);
	if (!function_exists('is_id_licensed') || !is_id_licensed()) {
		$level_invalid = 1;
	}

	?>
	<?php if (empty($type) || $type == 'level-based') {
		if ($end_type == 'closed' && $days_left <= '0') { ?>
			<a class="level-binding">
		<?php
		} 
		else {
		?>
			<a class="level-binding" <?php echo (isset($level_invalid) && $level_invalid ? '' : 'href="'.$url.'&level='.$level['id'].'"'); ?>>
	<?php 
		}
	} ?>
		<div class="level-group">
			<div class="ign-level-title">
				<span> <?php echo $level['title'] ?></span>
				<div class="level-price">
					<?php if ($type !== 'pwyw') { ?>
						<?php echo $level['currency_code']; ?><?php echo $level['price'] ?>
					<?php } ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="ign-level-desc">
				<?php echo $level['description'] ?>
			</div>
		
			<?php if ($level['limit'] !== '' && $level['limit'] > 0) { ?>
			<div class="ign-level-counts">
				<span><?php _e('Limit', 'fivehundred'); ?>: <?php echo $level['sold'] ?> of <?php echo $level['limit'] ?> <?php _e('taken', 'fivehundred'); ?>.</span>
			</div>
			<?php } ?>
		</div>
	<?php if (empty($type) || $type == 'level-based') { ?>
		</a>
	<?php } ?>
<?php } ?>