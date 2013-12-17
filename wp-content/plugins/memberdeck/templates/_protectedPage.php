

<div class="row">
	<div class="col-md-6">
		<h3>Login</h3>
		<?php if (isset($_GET['login_failure']) && $_GET['login_failure'] == 1) {
		echo '<div class="alert alert-warning">Login failed</div>';
	} ?>
	<?php if (!is_user_logged_in()): ?>
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
			'echo' => false); ?>
	

	<form name="loginform" id="loginform" action="<?php bloginfo('url'); ?>/wp-login.php" method="post">
		<div class="form-group login-username">
			<label for="user_login">Username</label>
			<input type="text" name="log" id="user_login" class="form-control input" value="" size="20" />
		</div>
		<div class="form-group login-password">
			<label for="user_pass">Password</label>
			<input type="password" name="pwd" id="user_pass" class="form-control input" value="" size="20" />
		</div>
		<div class="checkbox login-remember">
			<label for="rememberme">
				<input name="rememberme" type="checkbox" id="rememberme" value="forever"> Remember me
			</label>
		</div>
		<div class="form-group login-submit">
			<input type="submit" name="wp-submit" id="wp-submit" class="button-primary btn btn-lg" value="Log In" />
			<input type="hidden" name="redirect_to" value="<?php bloginfo('url'); ?>/dashboard" />
		</div>
	</form>
	</div>
	<div class="col-md-6">
		<div class="well">
			<h3>Don't have an account yet?</h3>
			<a href="<?php bloginfo('url'); ?>/sign-up" class="btn btn-lg">Sign Up</a>
		</div>
	</div>
</div>

<?php endif; ?>