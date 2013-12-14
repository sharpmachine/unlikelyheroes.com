<div class="wrap">
	<div class="postbox-container" style="width:95%; margin-right: 5%">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Stripe Settings', 'idstripe'); ?></span></h3>
					<div class="inside">
						<p><?php _e('You will need a <a href="http://www.stripe.com" target="_blank">Stripe Account</a> in order to continue. Please enter your credentials in order to continue and activate Stripe.', 'idstripe'); ?></p>
						<p><?php _e('If using sandbox mode, make sure to enter test credentials.', 'idstripe'); ?></p>
						<p><span class="note"><?php _e('Note, all Stripe transactions will be processed in the currency selected below. Use the default project settings to change level display.', 'idstripe'); ?></span></p>
						<form method="post" action="" enctype="multipart/form-data" id="formSubmit" name="formSubmit">
							<ul>
								<li>
									<label for="currency"><?php _e('Currency', 'idstripe'); ?></label>
									<div>
										<select id="currency" name="currency">
											<option value="0"><?php _e('Choose Currency', 'idstripe'); ?></option>
											<option value="USD"><?php _e('USD', 'idstripe'); ?></option>
											<option value="CAD"><?php _e('CAD', 'idstripe'); ?></option>
											<option value="GBP"><?php _e('GBP', 'idstripe'); ?></option>
											<option value="EUR"><?php _e('EUR', 'idstripe'); ?></option>
											<option value="AUD"><?php _e('AUD (beta)', 'idstripe'); ?></option>
										</select>
									</div>
								</li>
								<li>
									<label for="fund-type"><?php _e('Fund Type', 'idstripe'); ?></label>
									<div>
										<select id="fund-type" name="fund-type">
											<option id="capture" value="capture"><?php _e('Immediately Deliver Funds', 'idstripe'); ?></option>
											<option id="authorize" value="authorize"><?php _e('100% Threshold', 'idstripe'); ?></option>
										</select>
									</div>
								</li>
								<li>
									<label for="stripe_api_key"><?php _e('Live Secret Key', 'idstripe'); ?></label>
									<div><input type="text" value="<?php echo $payment_settings->api_key; ?>" id="stripe_api_key" name="stripe_api_key"></div>
								</li>
								<li>
									<label for="stripe_publishable_key"><?php _e('Live Publishable Key', 'idstripe'); ?></label>
									<div><input type="text" value="<?php echo $payment_settings->stripe_publishable_key; ?>" id="stripe_publishable_key" name="stripe_publishable_key"></div>
								</li>
								<li>
									<label for="stripe_api_key"><?php _e('Test Secret Key', 'idstripe'); ?></label>
									<div><input type="text" value="<?php echo $payment_settings->sandbox_api_key; ?>" id="stripe_api_key" name="sandbox_api_key"></div>
								</li>
								<li>
									<label for="stripe_publishable_key"><?php _e('Test Publishable Key', 'idstripe'); ?></label>
									<div><input type="text" value="<?php echo $payment_settings->sandbox_publishable_key; ?>" id="stripe_publishable_key" name="sandbox_publishable_key"></div>
								</li>
								<li>
									<p><?php _e('Click ', 'idstripe'); ?><a href="https://stripe.com/docs/testing" target="_blank"><?php _e('here', 'idstripe'); ?></a> <?php _e('for testing information', 'idstripe'); ?>.</p>
									<label for="sandbox-mode"><?php _e('Test Mode', 'idstripe'); ?></label>
									<div><input type="checkbox" value="sandbox" id="sandbox-mode" name="sandbox-mode" <?php echo ($payment_settings->sandbox_mode == 'sandbox' ? 'checked' : ''); ?>></div>
								</li>
								<li>
									<label for="disable-paypal"><?php _e('Disable Paypal', 'idstripe'); ?>?</label>
									<div><input type="checkbox" value="1" id="disable-pp" name="disable-pp" <?php echo ($payment_settings->disable_pp == 1 ? 'checked' : ''); ?>></div>
								</li>
								<li>
									<div><input type="submit" name="btnSaveStripe" id="btnSaveStripe" value="Save Settings" class="button-primary" /></div>
								</li>
							</ul>
						</form>
					</div>
				</div>
				<div class="postbox">
					<div class="inside">
						<h3 class="hndle"><span><?php _e('Process Project Authorizations', 'idstripe'); ?></span></h3>
						<div id="charge-screen">
							<p><?php _e('Use this screen to process transactions once your fund threshold has been reached. Please use this feature with care, and remember to clearly state terms on your website or campaign page', 'idstripe'); ?>.</p>
							<div id="charge-confirm"></div>
							<p><span class="alert"><?php _e('Warning:</span> This will process all pending authorizations related to the selected campaign', 'idstripe'); ?>.</p>
							<p><strong><?php _e('Customers will only be charged once', 'idstripe'); ?>.</strong></p>
							
							<div id="projects">
								<select id="project-list" name="project-list">
								</select>
							</div>
							<div>
								<input type="submit" name="btnProcessStripe" id="btnProcessStripe" projid="btnProcessStripe" value="Process Authorizations" class="button" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>