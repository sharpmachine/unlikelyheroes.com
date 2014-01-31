<?php get_header(); ?>
<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<?php if (!is_user_logged_in() && (isset($_GET['action']) && $_GET['action'] == register)): ?>
					<span>Create Account</span>
					<?php else: ?>
					<span>My Campaign</span>
					<?php endif; ?>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row main-content">
		<?php if (is_user_logged_in()): ?>
		<div class="col-xs-12">
			<div class="pull-right">
				<a href="<?php echo wp_logout_url(home_url('/dashboard')); ?>" class="btn btn-lg">Logout</a>
			</div>
			<div class="clearfix"></div>
			<br>
			<?php get_template_part( 'loop', 'page' ); ?>
		</div>
	<?php endif; ?>
	<?php if (!is_user_logged_in() && (!isset($_GET['action']) || $_GET['action'] !='register')): ?>
	<div class="col-sm-6 col-md-4 col-md-offset-2">
		<h3 class="text-center">Log in</h3>
		<?php get_template_part( 'loop', 'page' ); ?>
	</div>
	<hr class="visible-xs">
	<div class="col-sm-6  col-md-4 text-center">
		<h3>Create Account</h3>
		<div class="box-border sign-up-box">
			<a href="<?php bloginfo('url'); ?>/dashboard/?action=register" class="btn btn-lg">Sign Up</a>
		</div>
	</div>
<?php endif; ?>

<?php if (!is_user_logged_in() && (isset($_GET['action']) && $_GET['action'] == register)): ?>
	<div class="col-md-6 col-md-offset-3">
		<?php get_template_part( 'loop', 'page' ); ?>
	</div>
<?php endif; ?>

</div>
</div>

<?php get_footer(); ?>
