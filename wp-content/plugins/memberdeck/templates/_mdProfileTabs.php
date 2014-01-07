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
<!-- <ul class="dashboardmenu"> -->
<div class="logout-button text-right">
	<a href="<?php echo wp_logout_url( home_url() ); ?>" class="btn btn-lg">Logout</a> 
</div>

	<ul class="nav nav-tabs">
	<li><a href="<?php echo $durl; ?>"><?php _e('My Dashboard', 'memberdeck'); ?></a></li>
	<li><a href="<?php echo (isset($current_user) ? the_permalink().'?edit-profile='.$current_user->ID : ''); ?>"><?php echo (isset($current_user) ? __('My Profile', 'memberdeck') : ''); ?></a></li>
	<li></li>
	<?php do_action('md_profile_extratabs'); ?>
</ul>