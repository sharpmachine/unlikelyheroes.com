<?php get_header(); ?>
<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<span><?php the_title(); ?></span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 main-content">
			<div class="">
				<form action="<?php bloginfo('url'); ?>/wp-login.php" method="POST" id="payment-form" name="reg-form" data-regkey="<?php echo (isset($reg_key) ? $reg_key : ''); ?>">
					<div id="logged-input" class="no">
						<div class="row">
							<div class="form-group col-md-6">
								<label><?php _e('First Name', 'memberdeck'); ?></label>
								<input type="text" size="20" class="first-name required form-control" name="first-name" value="<?php echo (isset($user_firstname) ? $user_firstname : ''); ?>"/>
							</div>
							<div class="form-group col-md-6">
								<label><?php _e('Last Name', 'memberdeck'); ?></label>
								<input type="text" size="20" class="last-name required form-control" name="last-name" value="<?php echo (isset($user_lastname) ? $user_lastname : ''); ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label><?php _e('Email Address', 'memberdeck'); ?></label>
							<input type="email" size="20" class="email required form-control" name="email" value="<?php echo (isset($email) ? $email : ''); ?>"/>
						</div>
						<div class="row">
							<div class="form-group col-md-6">
								<label><?php _e('Password', 'memberdeck'); ?></label>
								<input type="password" size="20" class="pw required form-control" name="pw"/>
							</div>
							<div class="form-group col-md-6">
								<label><?php _e('Re-enter Password', 'memberdeck'); ?></label>
								<input type="password" size="20" class="cpw required form-control" name="cpw"/>
							</div>
						</div>
						<?php echo do_action('md_register_extrafields'); ?>
						<div class="payment-errors"></div>
						<div class="text-center">
							<button type="submit" id="id-reg-submit" class="submit-button btn btn-lg"><?php _e('Complete Registration', 'memberdeck'); ?></button>
							<input type="hidden" name="redirect_to" value="<?php bloginfo('url'); ?>/dashboard" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
	<?php get_footer(); ?>
