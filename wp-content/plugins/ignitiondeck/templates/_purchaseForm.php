<?php
/**
 * The Template for displaying purchase form.
 */
?>
<?php // this is paypal code that could be removed if disabled ?>
<?php 
if (isset($level)) {
	$level_invalid = getLevelLimitReached($project_id, $post_id, $level);
	if ($level_invalid) {
		$level = 0;
	}
}
?>

<script src="https://www.paypalobjects.com/js/external/dg.js"></script>

<h3 class="text-center">You're about to support <?php echo (isset($purchase_form->the_project) ? stripslashes($purchase_form->the_project->product_name) : '');?>.</h3>


<div id="<?php echo $purchase_form->form_id; ?>-pay-form">
	<form action="" method="post" name="form_pay" id="form_pay" data-postid="<?php echo (isset($purchase_form->post_id) ? absint($purchase_form->post_id) : ''); ?>" data-projectid="<?php echo (isset($_GET['prodid']) ? absint($_GET['prodid']) : ''); ?>" data-level="<?php echo (isset($level) ? absint($level) : ''); ?>" data-projectType="<?php echo (isset($purchase_form->project_type) ? $purchase_form->project_type : ''); ?>" data-currency="<?php echo (isset($purchase_form->currencyCodeValue) ? $purchase_form->currencyCodeValue : ''); ?>">
		<div class="row">
			<div class="col-md-12">
				<input type="hidden" name="project_id" value="<?php echo ($purchase_form->project_id); ?>" />


				

				<div class="notification"></div>
				<div id="message-container" <?php echo (!isset($_SESSION['paypal_errors_content']) || $_SESSION['paypal_errors_content'] == "" ? 'style="display: none;"' : ''); ?>>
					<div class="notification error">
						<a href="#" class="close-notification" title="Hide Notification" rel="tooltip">x</a>
						<p><strong><?php echo $tr_Payment_Error; ?>: </strong><span id="paypal-error-message"><?php echo (isset($_SESSION['paypal_errors_content']) ? $_SESSION['paypal_errors_content'] : ''); ?></span></p>
					</div>
				</div>
				<?php
				if (isset($_SESSION['paypal_errors_content'])) {
					unset($_SESSION['paypal_errors_content']);
				}
				?>
				<?php if(isset($purchase_form->form_settings['first_name']['status'])):?>

<hr>
<div class="form-group">
	<h4 class="text-center">Reward</h4>
	<?php 
	if (isset($level) && $level > 0) {
		if ($level == 1) {
			$is_level_invalid = getLevelLimitReached($project_id, $post_id, 1);
			$meta_title = $purchase_form->the_project->ign_product_title;
			$meta_price = get_post_meta( $post_id, $name="ign_product_price", true );
			$meta_desc = $purchase_form->the_project->product_details;
		}
		else {
			$is_level_invalid = getLevelLimitReached($project_id, $post_id, $level);
			$meta_title = get_post_meta( $post_id, $name="ign_product_level_".($level)."_title", true );
			$meta_price = get_post_meta( $post_id, $name="ign_product_level_".($level)."_price", true );
			$meta_desc = html_entity_decode(get_post_meta( $post_id, $name="ign_product_level_".($level)."_desc", true ));
		}
	} 
	else if (isset($purchase_form->project_type) && $purchase_form->project_type !== "pwyw") { ?>
	<label for="level_select">Choose your reward:</label>
	<select name="level_select" id="level_select">
		<?php foreach ($purchase_form->level_data as $level_item) {
			if ($level_item->is_level_invalid) { ?>
			<option value="<?php echo $level_item->id; ?>" data-description="<?php echo html_entity_decode(isset($level_item->meta_desc) ? $level_item->meta_desc : ''); ?>" data-price="<?php echo number_format($level_item->meta_price, 2, '.', ','); ?>" disabled="disabled"><?php echo ($level_item->meta_title !== "" ? $level_item->meta_title.": " : $tr_Level." 1:"); ?><?php echo '<span class="id-buy-form-currency">'.$purchase_form->cCode.'</span>'; ?><?php echo number_format($level_item->meta_price, 2, '.', ','); ?> -- <?php echo $tr_Sold_Out; ?></option>
			<?php 
		} else { ?>
		<option value="<?php echo $level_item->id; ?>" data-description="<?php echo html_entity_decode(isset($level_item->meta_desc) ? $level_item->meta_desc : ''); ?>" data-price="<?php echo (isset($level_item->meta_price) ? number_format($level_item->meta_price, 2, '.', ',') : '');?>"><?php echo ($level_item->meta_title !== "" ? $level_item->meta_title.": " : $tr_Level." 1:"); ?><?php echo '<span class="id-buy-form-currency">'.$purchase_form->cCode.'</span>'; ?><?php echo number_format($level_item->meta_price, 2, '.', ',');?></option>
		<?php 
	} 
} ?>
</select>

<?php 
}
else { ?>
<label for="price_entry"><?php echo $tr_Price_Entry; ?>:</label>
<input type="text" name="price_entry" id="price_entry" value=""/>
<input type="hidden" name="level_select" id="level_select" value="1"/>
<?php }	?>


	<div class="id-checkout-level-desc" desc="$">
		<strong>
			<?php echo (isset($purchase_form->project_type) && $purchase_form->project_type !== "pwyw" ? $tr_Level.': ' : ''); ?>
		</strong>
		<?php if (isset($level) && $level >= 1) {
			echo (isset($meta_desc) ? $meta_desc : '');
		}
		else {
			echo (isset($purchase_form->the_project) ? $purchase_form->the_project->product_details : '');
		} ?>
	</div>

<input type="hidden" name="price" value="<?php 
if (isset($level) && $level >= 1) {
	echo (isset($meta_price) ? $meta_price : '');
}
else {
	echo (isset($purchase_form->project_type) && $purchase_form->project_type !== "pwyw" ? $purchase_form->the_project->product_price : '');
} ?>" />
<input type="hidden" name="quantity" />
<input type="hidden" name="project_type" id="project_type" value="<?php echo (isset($purchase_form->project_type) ? $purchase_form->project_type : 'level-based'); ?>"/>
<input type="hidden" name="level" value="<?php echo (isset($level) && $level >= 1 ? $level : ''); ?>"/>

<div class="ign-checkout-price">
	<label for="price"><?php echo $tr_Total_Contribution; ?>: </label>
	<span class="id-buy-form-currency"><?php echo (isset($purchase_form->cCode) ? $purchase_form->cCode : ''); ?></span>
	<span class="preorder-form-product-price">
		<?php 
		if (isset($level) && $level >= 1) {
			echo (isset($meta_price) ? $meta_price : '');
		}
		else {
			echo (isset($purchase_form->the_project) ? $purchase_form->the_project->product_price : '');
		} ?>
	</div>
</div>
<hr>


				<div class="row">
					<h4 class="text-center"><?php echo $tr_Payment_Information; ?></h4>
					<div class="col-md-6">

						<label for="first_name"><?php echo $tr_First_Name;  ?>:
							<?php if(isset($purchase_form->form_settings['first_name']['mandatory'])): ?>
							<span class="required-mark">*<span>
							<?php endif; ?></label>
							<input type="text" name="first_name" class="<?php echo (isset($purchase_form->form_settings['first_name']['mandatory']))?'required':''; ?> form-control" id="first_name" />

						</div>
					<?php endif; ?>
					<?php if(isset($purchase_form->form_settings['last_name']['status'])):?>
					<div class="col-md-6">

						<label for="last_name"><?php echo $tr_Last_Name; ?>:
							<?php if(isset($purchase_form->form_settings['last_name']['mandatory'])): ?>
							<span class="required-mark">*</span>
						<?php endif; ?></label>
						<input type="text" name="last_name" class="<?php echo (isset($purchase_form->form_settings['last_name']['mandatory']))?'required':''; ?> form-control" id="last_name" />

					</div>
				</div>
			<?php endif; ?>
			<?php if(isset($purchase_form->form_settings['email']['status'])):?>
			<div class="row">
				<div class="col-md-12">
					<label for="email"><?php echo $tr_Email; ?>:
						<?php if(isset($purchase_form->form_settings['email']['mandatory'])): ?>
						<span class="required-mark">*</span>
					<?php endif; ?></label>
					<input type="email" name="email" class="<?php echo (isset($purchase_form->form_settings['email']['mandatory']))?'required':''; ?> email form-control" id="email" />
				</div>
			</div>
		<?php endif; ?>
		<?php if(isset($purchase_form->form_settings['address']['status'])):?>
		<div class="row">
			<div class="col-md-12">
				<label for="address"><?php echo $tr_Address; ?>:
					<?php if(isset($purchase_form->form_settings['address']['mandatory'])): ?>
					<span class="required-mark">*</span>
				<?php endif; ?></label>
				<input type="text" name="address" class="<?php echo (isset($purchase_form->form_settings['address']['mandatory']))?'required':''; ?> form-control" id="address" />
			</div>
		</div>
	<?php endif; ?>
	<?php if(isset($purchase_form->form_settings['city']['status'])):?>
	<div class="row">
		<div class="col-md-4">
			<label for="city"><?php echo $tr_City; ?>:
				<?php if(isset($purchase_form->form_settings['city']['mandatory'])): ?>
				<span class="required-mark">*</span>
			<?php endif; ?></label>
			<input type="text" name="city" id="city" class="<?php echo (isset($purchase_form->form_settings['city']['mandatory']))?'required':''; ?> form-control"/>
		</div>
	<?php endif; ?>
	<?php if(isset($purchase_form->form_settings['state']['status'])):?>
	<div class="col-md-4">
		<label for="state"><?php echo $tr_State; ?>:
			<?php if(isset($purchase_form->form_settings['state']['mandatory'])): ?>
			<span class="required-mark">*</span>
		<?php endif; ?></label>
		<input type="text" name="state" id="state" class="<?php echo (isset($purchase_form->form_settings['state']['mandatory']))?'required':''; ?> form-control" />
	</div>
<?php endif; ?>
<?php if(isset($purchase_form->form_settings['zip']['status'])):?>
	<div class="col-md-4">
		<label for="zip"><?php echo $tr_Zip; ?>:
			<?php if(isset($purchase_form->form_settings['zip']['mandatory'])): ?>
			<span class="required-mark">*</span>
		<?php endif; ?></label>
		<input type="text" name="zip" id="zip" class="<?php echo (isset($purchase_form->form_settings['zip']['mandatory']))?'required':''; ?> form-control" />
	</div>
</div>
<?php endif; ?>
<?php if(isset($purchase_form->form_settings['country']['status'])):?>
	<div class="row">
		<div class="col-md-12">
			<label for="country"><?php echo $tr_Country; ?>:
				<?php if(isset($purchase_form->form_settings['country']['mandatory'])): ?>
				<span class="required-mark">*</span>
			<?php endif; ?></label>
			<input type="text" name="country" id="country" class="<?php echo (isset($purchase_form->form_settings['country']['mandatory']))?'required':''; ?> form-control" />
		</div>
	</div>
<?php endif; ?>
<?php $output = null; ?>


<hr>


	<h4 class="text-center">Pay By:</h4>
	<div id="payment-choices" class="payment-type-selector donation-payment-buttons text-center">
		<button class="main-btn btn" type="submit" value="Pay with Paypal" name="submitPaymentPopup" id=""><span class="icon-paypal"></span> Paypal</button>
		<?php $pay_choices = '<a id="pay-with-paypal" class="pay-choice btn hidden" href="#"><span class="icon-paypal"></span> Paypal</a> <span class="or">Or</span>'; ?>
		<?php echo apply_filters('id_pay_choices', $pay_choices, $project_id); ?>
	</div>
<br>
	<div class="ign-checkout-price">
	<label for="price"><?php echo $tr_Total_Contribution; ?>: </label>
	<span class="id-buy-form-currency"><?php echo (isset($purchase_form->cCode) ? $purchase_form->cCode : ''); ?></span>
	<span class="preorder-form-product-price">
		<?php 
		if (isset($level) && $level >= 1) {
			echo (isset($meta_price) ? $meta_price : '');
		}
		else {
			echo (isset($purchase_form->the_project) ? $purchase_form->the_project->product_price : '');
		} ?>
	</div>
	<?php // echo apply_filters('id_purchaseform_extrafields', $output); ?>



	<div id="stripe-input" style="display:none;" data-ppoff="<?php echo (isset($pp_off) ? $pp_off : ''); ?>" data-pubkey="<?php echo (isset($pub_key) ? $pub_key : ''); ?>" data-productid="<?php echo (isset($product_id) ? $product_id : ''); ?>" data-ty-url="<?php echo (isset($ty_url) ? $ty_url : site_url()); ?>" data-ccode="<?php echo (isset($cCode) ? $cCode : '$'); ?>">
		<div class="row">
			<div class="col-md-12">
				<label><?php _e('Card Number', 'idstripe'); ?> <span class="required-mark"><?php _e('*', 'idstripe'); ?></span></label>
				<input type="text" size="20" autocomplete="off" class="card-number required form-control"/>
			</div>
		</div>
		<div class="row">
			<div id="cvc" class="col-md-4">
				<label><?php _e('CVC', 'idstripe'); ?></label>
				<input type="text" autocomplete="off" class="card-cvc required form-control"/>
			</div>
			<div id="date">
				<div class="col-md-4">
					<label><?php _e('Expiration', 'idstripe'); ?> <span class="required-mark"><?php _e('*', 'idstripe'); ?></span></label>
					<input type="text" class="card-expiry-month form-control" placeholder="mm" />
				</div>
				<div class="col-md-4">
					<input type="text" class="card-expiry-year required form-control" placeholder="yyyy" />
				</div>
			</div>
		</div>

		<div class="ign-checkout-button text-center">
			<input class="main-btn btn" type="submit" value="<?php echo $tr_Make_Payment; ?>" name="<?php echo $purchase_form->submit_btn_name ?>" id="button_pay_purchase"/>
		</div>
	</div>
	<noscript><p><?php _e('JavaScript is required for the registration form.', 'idstripe'); ?></p></noscript>
</div>
</div>
</form>
</div>





