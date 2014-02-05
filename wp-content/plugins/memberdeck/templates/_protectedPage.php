<div class="md-requiredlogin login">
	<h3><?php _e('This content is restricted to members only', 'memberdeck'); ?>.</h3>
	<p>Please <?php _e('login for access', 'memberdeck'); ?>.</p>
	<?php if (isset($_GET['login_failure']) && $_GET['login_failure'] == 1) {
		echo '<p class="error">Login failed</p>';
	} ?>
	<?php if (!is_user_logged_in()) { ?>
		<?php
		$dash = get_option('md_dash_settings');
		if (!empty($dash)) {
			$dash = unserialize($dash);
			$durl = $dash['durl'];
		}
		else {
			$durl = home_url('/');
		}
		$args = array('redirect' => $durl,
			'echo' => false);
		echo wp_login_form($args); ?>
	<?php } ?>
</div>