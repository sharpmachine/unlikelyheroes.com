<li class="md-box half">
	<div class="md-profile">
	<?php if (!isset($message)) { ?>
	<a href="<?php echo (empty($check_creds) ? 'https://connect.stripe.com/oauth/authorize?response_type=code&amp;client_id='.$client_id.'&amp;scope=read_write&amp;state='.$user_id : ''); ?>" class="stripe-connect">
		<span><?php _e('Connect with Stripe', 'memberdeck'); ?><?php echo (!empty($check_creds) ? ' <i class="icon-ok"></i>' : ''); ?></span>
	</a>
	<?php } else { ?>
		<p><?php echo $message; ?></p>
	<?php } ?>
	</div>
</li>