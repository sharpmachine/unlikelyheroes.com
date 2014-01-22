<!-- <div class="memberdeck"> -->
<?php include_once MD_PATH.'templates/_mdProfileTabs.php'; ?>
<div class="tab-content">
	<?php echo (isset($error) ? '<p class="error">'.$error.'</p>' : ''); ?>
	<?php echo (isset($success) ? '<p class="success">'.$success.'</p>' : ''); ?>
	<form action="?edit-profile=<?php echo (isset($current_user->ID) ? $current_user->ID : ''); ?>&amp;edited=1" method="POST" id="edit-profile" name="edit-profile">
		
		<div class="">
			<div id="logged-input" class="">
				<h3 class="text-center"><?php _e('Profile Information', 'memberdeck'); ?></h3>

				<div class="form-row quarter hidden">
					<label for="nicename"><?php _e('Display Name *', 'memberdeck'); ?></label>
					<input type="text" size="20" class="nicename" name="nicename" value="<?php echo (isset($nicename) ? $nicename : ''); ?>"/>
				</div>
				<div class="form-row quarter hidden">
					<label for="first-name"><?php _e('First Name', 'memberdeck'); ?></label>
					<input type="text" size="20" class="first-name" name="first-name" value="<?php echo (isset($user_firstname) ? $user_firstname : ''); ?>"/>
				</div>
				<div class="form-row half hidden">
					<label for="last-name"><?php _e('Last Name', 'memberdeck'); ?></label>
					<input type="text" size="20" class="last-name" name="last-name" value="<?php echo (isset($user_lastname) ? $user_lastname : ''); ?>"/>
				</div>
				<div class="form-group">
					<label for="email"><?php _e('Email Address', 'memberdeck'); ?></label>
					<input type="email" size="20" class="email form-control" name="email" value="<?php echo (isset($email) ? $email : ''); ?>"/>
				</div>
				<div class="form-row half hidden">
					<label for="url"><?php _e('Website URL *', 'memberdeck'); ?></label>
					<input type="url" size="20" class="url" name="url" value="<?php echo (isset($url) ? $url : ''); ?>"/>
				</div>

				<div class="form-row half hidden">
					<label for="description"><?php _e('Bio *', 'memberdeck'); ?></label>
					<textarea row="10" class="description" name="description"><?php echo (isset($description) ? $description : ''); ?></textarea>
				</div>
				<div class="form-row half hidden">
					<label for="twitter"><?php _e('Twitter URL *', 'memberdeck'); ?></label>
					<input type="twitter" size="20" class="twitter" name="twitter" value="<?php echo (isset($twitter) ? $twitter : ''); ?>"/>
					<label for="facebook"><?php _e('Facebook URL *', 'memberdeck'); ?></label>
					<input type="facebook" size="20" class="facebook" name="facebook" value="<?php echo (isset($facebook) ? $facebook : ''); ?>"/>
					<label for="google"><?php _e('Google URL *', 'memberdeck'); ?></label>
					<input type="google" size="20" class="google" name="google" value="<?php echo (isset($google) ? $google : ''); ?>"/>
				</div>
				<p class="inline">
					<h4><?php _e('Change Password', 'memberdeck'); ?></h4>
					<strong><?php _e('Note:', 'memberdeck'); ?></strong> <?php _e('changing your password will clear login cookies. You will need to login again after saving.', 'memberdeck'); ?>
				</p>
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="pw"><?php _e('Password', 'memberdeck'); ?></label>
						<input type="password" size="20" class="pw form-control" name="pw"/>
					</div>
					<div class="form-group col-sm-6">
						<label for="cpw"><?php _e('Re-enter Password', 'memberdeck'); ?></label>
						<input type="password" size="20" class="cpw form-control" name="cpw"/>
					</div>
				</div>
				<div class="hidden">
				<?php echo do_action('md_profile_extrafields'); ?>
				<h4><?php _e('Instant Checkout Settings', 'memberdeck'); ?></h4>
				<p>	<?php _e('With instant checkout enabled, you can pay with your credit card without re-entering information. To enable, simply use your credit card to checkout once, and then select &lsquo;enable instant checkout&rsquo; from this screen, and click \'Update Profile\' below.', 'memberdeck'); ?><br><br>
					<?php _e('Your credit card information is never stored on our servers, and is always processed securely.', 'memberdeck'); ?>
				</p>
				<?php if (isset($customer_id)) { ?>
				<p class="form-check">
					<input type="checkbox" class="instant_checkout" name="instant_checkout" <?php echo (isset($instant_checkout) && $instant_checkout == 1 ? 'checked="checked"' : ''); ?> value="1"/>
					&nbsp;
					<label for="instant_checkout"><?php _e('Enable Instant Checkout', 'memberdeck'); ?></label>
				</p>
				<?php do_action('md_profile_extrasettings'); ?>
				<?php } ?>
				</div>
				<div class="text-center">
					<br>
					<button type="submit" id="edit-profile-submit" class="submit-button btn" name="edit-profile-submit"><?php _e('Update Profile', 'memberdeck'); ?></button>
				</div>
			</div>
		</div>
	</form>
	
</div>