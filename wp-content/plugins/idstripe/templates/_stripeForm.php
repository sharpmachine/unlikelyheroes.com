<?php
/** This is the Stripe Purchase Form **/
?>
<?php // Will not work if others create payment gateways ?>
<div id="stripe-input" style="display:none;" data-ppoff="<?php echo (isset($pp_off) ? $pp_off : ''); ?>" data-pubkey="<?php echo (isset($pub_key) ? $pub_key : ''); ?>" data-productid="<?php echo (isset($product_id) ? $product_id : ''); ?>" data-ty-url="<?php echo (isset($ty_url) ? $ty_url : site_url()); ?>" data-ccode="<?php echo (isset($cCode) ? $cCode : '$'); ?>">
	<li class="form-row idfield">
		<label class="idfield_label"><?php _e('Card Number', 'idstripe'); ?> <span class="required-mark"><?php _e('Required', 'idstripe'); ?></span></label>
		<input type="text" size="20" autocomplete="off" class="card-number required"/>
	</li>
	<li id="cvc" class="form-row third idfield">
		<label class="idfield_label"><?php _e('CVC', 'idstripe'); ?></label><br/>
		<input type="text" size="4" autocomplete="off" class="card-cvc required"/>
	</li>
	<li id="date" class="form-row twothird date idfield">
		<label class="idfield_label"><?php _e('Expiration (MM/YYYY)', 'idstripe'); ?> <span class="required-mark"><?php _e('Required', 'idstripe'); ?></span></label><br/>
		<input type="text" size="2" class="card-expiry-month"/>
		<span> / </span>
		<input type="text" size="4" class="card-expiry-year required"/>
	</li>
</div>
<noscript><p><?php _e('JavaScript is required for the registration form.', 'idstripe'); ?></p></noscript>