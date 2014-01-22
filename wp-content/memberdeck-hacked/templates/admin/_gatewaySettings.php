<div class="wrap">
	<div class="icon32" id="icon-md"></div><h2><?php _e('MemberDeck Payment Gateways', 'memberdeck'); ?></h2>
	<div class="md-settings-container">
	<div class="postbox-container" style="width:65%; margin-right: 3%">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Default Gateway Settings', 'memberdeck'); ?></span></h3>
					<div class="inside">
						<form method="POST" action="" id="gateway-settings" name="gateway-settings">
							<div class="form-input inline" style="text-align: center; border: 1px solid #eee; background: #fefeff; padding: 5px;">
								<input type="checkbox" name="test" id="test" value="1" <?php echo (isset($test) && $test == 1 ? 'checked="checked"' : ''); ?>/>
								<label for="test"><?php _e('Enable Test Mode', 'memberdeck'); ?></label>
							</div>
							<div class="columns" style="width: 29%; padding-right: 2.5%; margin-right: 2.5%; border-right: 1px solid #C8D3DC;">
								<p>
									<img src="<?php echo plugins_url('/images/PayPal-Logo.png', dirname(dirname(__FILE__))); ?>">
								</p>
								<div class="form-input">
									<label for="pp-currency"><?php _e('Paypal Currency', 'memberdeck'); ?></label>
									<select id="pp-currency" name="pp-currency" data-selected="<?php echo (isset($pp_currency) ? $pp_currency : 'USD'); ?>">
									</select>
									<input type="hidden" name="pp-symbol" value="<?php echo (isset($pp_symbol) ? $pp_symbol : '$'); ?>"/>
								</div>
								<div class="form-input">
									<label for="pp-email"><?php _e('Paypal Email', 'memberdeck'); ?></label>
									<input type="text" name="pp-email" id="pp-email" value="<?php echo (isset($pp_email) ? $pp_email : ''); ?>"/>
								</div>
								<div class="form-input">
									<label for="test-email"><?php _e('Paypal Test Email', 'memberdeck'); ?></label>
									<input type="text" name="test-email" id="test-email" value="<?php echo (isset($test_email) ? $test_email : ''); ?>"/>
								</div>
								<div class="form-input">
									<label for="test-email"><?php _e('Paypal Return URL', 'memberdeck'); ?></label>
									<input type="text" name="paypal-redirect" id="paypal-redirect" value="<?php echo (isset($paypal_redirect) ? $paypal_redirect : ''); ?>"/>
								</div>
								<br/>
								<div class="form-input inline">
									<input type="checkbox" name="epp" id="epp" value="1" <?php echo (isset($epp) && $epp == 1 ? 'checked="checked"' : ''); ?>/>
									<label for="epp"><?php _e('Enable Paypal', 'memberdeck'); ?></label>
								</div>
								<?php if (function_exists('is_id_pro') && is_id_pro()) { ?>
								<div class="form-input inline">
									<input type="checkbox" name="epp_fes" id="epp_fes" value="1" <?php echo (isset($epp_fes) && $epp_fes == 1 ? 'checked="checked"' : ''); ?>/>
									<label for="epp_fes"><?php _e('Enable for Creators', 'memberdeck'); ?></label>
								</div>
								<?php } ?>
							</div>
							<div class="columns" style="width: 29%; padding-right: 2.5%; margin-right: 2.5%; border-right: 1px solid #C8D3DC;">
								<p>
									<img src="<?php echo plugins_url('/images/Stripe-Logo.png', dirname(dirname(__FILE__))); ?>">
								</p>
								<div class="form-input">
									<label for="stripe_currency"><?php _e('Stripe Currency', 'memberdeck'); ?></label>
									<select id="stripe_currency" name="stripe_currency">
										<option value="0"><?php _e('Choose Currency', 'idstripe'); ?></option>
										<option value="USD" <?php echo (isset($stripe_currency) && $stripe_currency == 'USD' ? 'selected="selected"' : ''); ?>><?php _e('USD', 'idstripe'); ?></option>
										<option value="CAD" <?php echo (isset($stripe_currency) && $stripe_currency == 'CAD' ? 'selected="selected"' : ''); ?>><?php _e('CAD', 'idstripe'); ?></option>
										<option value="GBP" <?php echo (isset($stripe_currency) && $stripe_currency == 'GBP' ? 'selected="selected"' : ''); ?>><?php _e('GBP', 'idstripe'); ?></option>
										<option value="EUR" <?php echo (isset($stripe_currency) && $stripe_currency == 'EUR' ? 'selected="selected"' : ''); ?>><?php _e('EUR', 'idstripe'); ?></option>
										<option value="AUD" <?php echo (isset($stripe_currency) && $stripe_currency == 'AUD' ? 'selected="selected"' : ''); ?>><?php _e('AUD (beta)', 'idstripe'); ?></option>
									</select>
								</div>
								<div class="form-input">
									<label for="pk"><?php _e('Stripe Publishable Key', 'memberdeck'); ?></label>
									<input type="text" name="pk" id="pk" value="<?php echo (isset($pk) ? $pk : ''); ?>"/>
								</div>
								<div class="form-input">
									<label for="sk"><?php _e('Stripe Secret Key', 'memberdeck'); ?></label>
									<input type="text" name="sk" id="sk" value="<?php echo (isset($sk) ? $sk : ''); ?>"/>
								</div>
								<div class="form-input">
									<label for="tpk"><?php _e('Stripe Publishable Key (Test)', 'memberdeck'); ?></label>
									<input type="text" name="tpk" id="tpk" value="<?php echo (isset($tpk) ? $tpk : ''); ?>"/>
								</div>
								<div class="form-input">
									<label for="tsk"><?php _e('Stripe Secret Key (Test)', 'memberdeck'); ?></label>
									<input type="text" name="tsk" id="tsk" value="<?php echo (isset($tsk) ? $tsk : ''); ?>"/>
								</div>
								<br/>
								<div class="form-input inline">
									<input type="checkbox" name="es" id="es" value="1" <?php echo (isset($es) && $es == 1 ? 'checked="checked"' : ''); ?> <?php echo (isset($eb) && $eb == 1 ? 'disabled="disabled"' : ''); ?>/>
									<label for="es"><?php _e('Enable Stripe', 'memberdeck'); ?></label>
								</div>
								<?php if (function_exists('is_id_pro') && is_id_pro()) { ?>
								<div class="form-input inline">
									<input type="checkbox" name="esc" id="esc" value="1" <?php echo (isset($esc) && $esc == 1 ? 'checked="checked"' : ''); ?>/>
									<label for="es"><?php _e('Enable Stripe Connect', 'memberdeck'); ?></label>
								</div>
								<?php } ?>
							</div>
							<div class="columns" style="width: 29%;">
								<p>
									<img src="<?php echo plugins_url('/images/balanced_logo.png', dirname(dirname(__FILE__))); ?>">
								</p>
								<div class="form-input">
									<label for="bk"><?php _e('Balanced API Key', 'memberdeck'); ?></label>
									<input type="text" name="bk" id="bk" value="<?php echo (isset($bk) ? $bk : ''); ?>"/>
								</div>
								<div class="form-input">
									<label for="burl"><?php _e('Balanced Marketplace URL', 'memberdeck'); ?></label>
									<input type="text" name="burl" id="burl" value="<?php echo (isset($burl) ? $burl : ''); ?>"/>
								</div>
								<div class="form-input">
									<label for="btk"><?php _e('Balanced Test Key', 'memberdeck'); ?></label>
									<input type="text" name="btk" id="btk" value="<?php echo (isset($btk) ? $btk : ''); ?>"/>
								</div>
								<div class="form-input">
									<label for="bturl"><?php _e('Balanced Test URL', 'memberdeck'); ?></label>
									<input type="text" name="bturl" id="bturl" value="<?php echo (isset($bturl) ? $bturl : ''); ?>"/>
								</div>
								<br/>
								<div class="form-input inline">
									<input type="checkbox" name="eb" id="eb" value="1" <?php echo (isset($eb) && $eb == 1 ? 'checked="checked"' : ''); ?> <?php echo (isset($es) && $es == 1 ? 'disabled="disabled"' : ''); ?>/>
									<label for="eb"><?php _e('Enable Balanced', 'memberdeck'); ?></label>
								</div>
								<?php if (function_exists('is_id_pro') && is_id_pro()) { ?>
								<!--<div class="form-input inline">
									<input type="checkbox" name="eb_fes" id="eb_fes" value="1" <?php echo (isset($eb_fes) && $eb_fes == 1 ? 'checked="checked"' : ''); ?>/>
									<label for="eb_fes"><?php _e('Enable for Creators', 'memberdeck'); ?></label>
								</div>-->
								<?php } ?>
							</div>
							<div class="submit">
								<input type="submit" name="gateway-submit" id="gateway-submit" class="button button-primary" value="<?php _e('Save Gateway Settings', 'memberdeck'); ?>" />
							</div>
						</form>
						<div id="charge-screen">
							<h3><?php _e('Process Pre-Authorizations', 'idstripe'); ?></h3>
							<div id="charge-confirm"></div>
							<p><span class="alert"><?php _e('Warning:</span> This will process all pending authorizations related to the selected campaign', 'memberdeck'); ?>.</p>
							<p><strong><?php _e('Customers will only be charged once', 'memberdeck'); ?>.</strong></p>
							<div id="projects">
								<select id="level-list" name="level-list">
								</select>
							</div>
							<div>
								<input type="submit" name="btnProcessPreauth" id="btnProcessPreauth" projid="btnProcessPreauth" value="Process Authorizations" class="button" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Begin Sidebar -->
	<div class="postbox-container" style="width:32%;">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox info">
					<h3 class="hndle"><span><?php _e('Gateway Installation Requirements', 'memberdeck'); ?></span></h3>
					<div class="inside">
						<strong><?php _e('Active Gateways', 'memberdeck'); ?>:</strong>
						<p><?php _e('Only one credit card gateway (Stripe, Balanced, etc) may be active at one time.', 'memberdeck'); ?></p>
						<strong><?php _e('Currencies', 'memberdeck'); ?>:</strong>
						<p><?php _e('All Balanced Payments transactions will be processed in USD. Stripe transactions may be processed in USD, Euros, or British Pounds.', 'memberdeck'); ?></p>
						<strong><?php _e('Recurring Payments', 'memberdeck'); ?>:</strong>
						<p><?php _e('Balanced does not yet support recurring payments. To use subscriptions, please enable Stripe or Paypal.', 'memberdeck'); ?></p>
						<strong><?php _e('Stripe Webhook URL', 'memberdeck'); ?>:</strong>
						<p><?php _e('In order to receive notifications of Stripe subscription payments, you\'ll need to create a production webhook URL with the following format', 'memberdeck'); ?>:</p>
						<p><strong>http://yourdomain.com/?memberdeck_notify=stripe</strong></p>
						<strong><?php _e('Dispute Notifications', 'memberdeck'); ?></strong>
						<p><?php _e('In order to properly handle Paypal dispute notifications, you must set your Paypal IPN URL to', 'memberdeck'); ?>:</p>
						<p><strong>http://yoursite.com/?memberdeck_notify=pp</strong></p>
						<p><strong><?php _e('Test Cards', 'memberdeck'); ?></strong></p>
						<p><a href="https://docs.balancedpayments.com/current/overview.html?language=bash#test-credit-card-numbers" target="_blank"><?php _e('Balanced Test Cards', 'memberdeck'); ?></a></p>
						<p><a href="https://stripe.com/docs/testing" target="_blank"><?php _e('Stripe Test Cards', 'memberdeck'); ?></a></p>
					</div>
				</div>
			</div>
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox info">
					<h3 class="hndle"><span>About This Plugin</span></h3>
					<div class="inside">
						<p>MemberDeck is designed and developed by <a href="http://virtuousgiant.com">Virtuous Giant</a>.</p>
						<p>Get in touch with us on <a href="https://www.facebook.com/virtuousgiant">Facebook</a>, Twitter <a href="http://twitter.com/virtuousgiant">@virtuousgiant</a>, or App.net <a href="http://alpha.app.net/vg">@VG</a>.</p>
					</div>
				</div>
			</div>
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox info">
					<h3 class="hndle"><span>Links</span></h3>
					<div class="inside">
						<ul>
							<li>MemberDeck: <a href="http://memberdeck.com?r=md">Membership Management for WordPress</a></li>
							<li>IgnitionDeck: <a href="http://ignitiondeck.com?r=md">Crowdfunding for Wordpress</a></li>
							<li>Retweet for Discount: <a href="http://RetweetforDiscount.com?r=md">Retweet for Discount</a></li>
							<li>Virtuous Giant: <a href="http://VirtuousGiant.com?r=md">Virtuous Giant</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Sidebar -->
</div>
</div>