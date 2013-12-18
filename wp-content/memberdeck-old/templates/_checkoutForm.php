<div class="memberdeck">
	<form action="" method="POST" id="payment-form" data-currency-code="<?php echo $pp_currency; ?>" data-product="<?php echo (isset($product_id) ? $product_id : ''); ?>" data-type="<?php echo (isset($type) ? $type : ''); ?>" <?php echo (isset($type) && $type == 'recurring' ? 'data-recurring="'.$recurring.'"' : ''); ?> data-free="<?php echo ($level_price == '' || $level_price == 0 ? 'free' : 'premium'); ?>" data-txn-type="<?php echo (isset($txn_type) ? $txn_type : 'capture'); ?>" data-scpk="<?php echo (isset($sc_pubkey) ? $sc_pubkey : ''); ?>" data-claimedpp="<?php echo (isset($claimed_paypal) ? $claimed_paypal : ''); ?>" <?php echo ($es == 1 || $eb == 1 ? 'style="display: none;"' : ''); ?>>
		<h3 class="checkout-header"><?php echo (isset($level_name) ? $level_name : ''); ?> <?php _e('Checkout', 'memberdeck'); ?></h3>
		<?php if (!is_user_logged_in()) { ?>
		<div id="logged-input" class="no">
			<div class="form-row third">
				<label for="first-name"><?php _e('First Name', 'memberdeck'); ?></label>
				<input type="text" size="20" class="first-name required" name="first-name"/>
			</div>
			<div class="form-row twothird">
				<label for="last-name"><?php _e('Last Name', 'memberdeck'); ?></label>
				<input type="text" size="20" class="last-name required" name="last-name"/>
			</div>
			<div class="form-row">
				<label for="email"><?php _e('Email Address', 'memberdeck'); ?></label>
				<input type="email" pattern="[^ @]*@[^ @]*" size="20" class="email required" name="email"/>
			</div>
			<div class="form-row">
				<label for="pw"><?php _e('Password', 'memberdeck'); ?></label>
				<input type="password" size="20" class="pw required" name="pw"/>
			</div>
			<div class="form-row">
				<label for="cpw"><?php _e('Re-enter Password', 'memberdeck'); ?></label>
				<input type="password" size="20" class="cpw required" name="cpw"/>
			</div>
		</div>
		<?php }
		else { ?>
		<div id="logged-input" class="yes">
			<div class="form-row third" style="display: none;">
				<label for="first-name"><?php _e('First Name', 'memberdeck'); ?></label>
				<input type="text" size="20" class="first-name required" name="first-name" value="<?php echo (isset($fname) ? $fname : ''); ?>"/>
			</div>
			<div class="form-row twothird" style="display: none;">
				<label for="last-name"><?php _e('Last Name', 'memberdeck'); ?></label>
				<input type="text" size="20" class="last-name required" name="last-name" value="<?php echo (isset($lname) ? $lname : ''); ?>"/>
			</div>
			<div class="form-row" style="display: none;">
				<label for="email"><?php _e('Email Address', 'memberdeck'); ?></label>
				<input type="email" pattern="[^ @]*@[^ @]*" size="20" class="email required" name="email" value="<?php echo (isset($email) ? $email : ''); ?>"/>
			</div>
		</div>
		<?php } ?>
		<div id="extra_fields" class="form-row">
		<?php echo do_action('md_purchase_extrafields'); ?>
		</div>
		<?php if ($level_price !== '' && $level_price > 0) { ?>
		<div class="payment-type-selector">
			<?php if ($epp == 1) { ?>
			<a id="pay-with-paypal" class="pay_selector" href="#">
				<span><?php _e('Pay with Paypal', 'memberdeck'); ?></span>
			</a>
			<?php } ?>
			<?php if ($es == 1) { ?>
			<a id="pay-with-stripe" class="pay_selector" href="#">
				<span><?php _e('Pay with Credit Card', 'memberdeck'); ?></span>
			</a>
			<?php } ?>
			<?php if ($eb == 1) { ?>
			<a id="pay-with-balanced" class="pay_selector" href="#">
				<span><?php _e('Pay with Credit Card', 'memberdeck'); ?></span>
			</a>
			<?php } ?>
		</div>
		<?php } ?>
		<div id="stripe-input" data-idset="<?php echo (isset($instant_checkout) && $instant_checkout == true ? true : false); ?>" style="display:none;">
			<div class="form-row">
				<label><?php _e('Card Number', 'memberdeck'); ?> <span class="cards"><img src="https://ignitiondeck.com/id/wp-content/themes/id2/images/creditcards-full2.png" alt="<?php _e('Credit Cards Accepted', 'memberdeck'); ?>" /></span></label>
				<input type="text" size="20" autocomplete="off" class="card-number required"/>
			</div>
			<div class="form-row half">
				<label><?php _e('CVC', 'memberdeck'); ?></label>
				<input type="text" size="4" autocomplete="off" class="card-cvc required"/>
			</div>
			<div class="form-row half date">
				<label><?php _e('Expiration (MM/YYYY)', 'memberdeck'); ?></label>
				<input type="text" size="2" class="card-expiry-month"/><span> / </span><input type="text" size="4" class="card-expiry-year required"/>
			</div>
		</div>
		<?php if ($level_price == '' || $level_price == 0) {?>
		<div id="finaldescFree" class="finaldesc"><p><?php _e('This is a free product. Click continue to add it to your account', 'memberdeck'); ?>.</p></div>
		<?php } ?>
		<div id="finaldescPayPal" class="finaldesc" style="display:none; word-wrap: none;"><p><?php _e('You will be redirected to Paypal to complete your payment of', 'memberdeck'); ?> <span class="currency-symbol"><?php echo $pp_symbol; ?></span><?php echo (isset($level_price) ? $level_price : ''); ?>. <?php _e('Once complete, check your email for registration information', 'memberdeck'); ?>.</p></div>
		<div id="finaldescStripe" class="finaldesc" style="display:none;"><?php _e('Your Credit Card will be billed', 'memberdeck'); ?> <span class="currency-symbol"><?php echo $pp_symbol; ?></span><?php echo (isset($level_price) ? $level_price : ''); ?> <?php echo (isset($type) && $type == 'recurring' ? $recurring : ''); ?> <?php echo (isset($customer_id) ? __('using the card on file', 'memberdeck') : ''); ?> <?php _e('and will appear on your statement as', 'memberdeck'); ?>: <em><?php echo (isset($coname) ? $coname : ''); ?></em>.</div>
		<span class="payment-errors"></span>
		<button type="submit" id="id-main-submit" class="submit-button"><?php _e('Submit Payment', 'memberdeck'); ?></button>
	</form>
	<?php echo (isset($id_disclaimer) ? '<p>'.$id_disclaimer.'</p>' : ''); ?>
</div>
<!-- 
    The easiest way to indicate that the form requires JavaScript is to show
    the form with JavaScript (otherwise it will not render). You can add a
    helpful message in a noscript to indicate that users should enable JS.
-->
<script>if (window.Stripe) jQuery("#payment-form").show()</script>
<noscript><p><?php _e('JavaScript is required for the registration form', 'memberdeck'); ?>.</p></noscript>
<div id="ppload"></div>
<?php if ($eb == 1) {
	echo '<script>balanced.init("'.$burl.'"); jQuery("#payment-form").show()</script>';
} ?>