<?php
$dash = get_option('md_dash_settings');
if (!empty($dash)) {
	$dash = unserialize($dash);
	if (isset($durl)) {
		$durl = $dash['durl'];
	}
	else {
		$durl = home_url('/dashboard');
	}
}
?>
<ul class="dashboardmenu">
	<li><a href="<?php echo $durl; ?>"><?php _e('My Dashboard', 'memberdeck'); ?></a></li>
	<li><a href="<?php echo (isset($current_user) ? the_permalink().'?edit-profile='.$current_user->ID : ''); ?>"><?php echo (isset($current_user) ? __('My Profile', 'memberdeck') : ''); ?></a></li>
	<!-- <li class="help"><a href="#"><i class="icon-question-sign"></i></a></li> -->
	<?php do_action('md_profile_extratabs'); ?>
</ul>