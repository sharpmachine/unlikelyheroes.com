<div class="wrap memberdeck">
	<div class="icon32" id="icon-options-general"></div><h2 class="title"><?php _e('Stripe Connect', 'memberdeck'); ?></h2>
	<div class="help">
		<a href="http://forums.ignitiondeck.com" alt="IgnitionDeck Support" title="IgnitionDeck Support" target="_blank"><button class="button button-large"><?php _e('Support', 'memberdeck'); ?></button></a>
		<a href="http://docs.ignitiondeck.com" alt="IgnitionDeck Documentation" title="IgnitionDeck Documentation" target="_blank"><button class="button button-large"><?php _e('Documentation', 'memberdeck'); ?></button></a>
	</div>
	<br style="clear: both;"/>
	<div class="postbox-container" style="width:60%; margin-right: 5%">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Application Settings', 'memberdeck'); ?></span></h3>
					<div class="inside">
						<form method="POST" action="" id="idsc_settings" name="idsc_settings">
							<div class="form-input">
								<label for="client_id"><?php _e('Production Client ID', 'memberdeck'); ?></label>
								<div>
									<input type="text" name="client_id" id="client_id" value="<?php echo (isset($client_id) ? $client_id : ''); ?>"/>
								</div>
							</div>
							<div class="form-input">
								<label for="dev_client_id"><?php _e('Development Client ID', 'memberdeck'); ?></label>
								<div>
									<input type="text" name="dev_client_id" id="dev_client_id" value="<?php echo (isset($dev_client_id) ? $dev_client_id : ''); ?>"/>
								</div>
							</div>
							<div class="form-select">
								<label for="fee_type"><?php _e('Fee Type', 'memberdeck'); ?></label>
								<div>
									<select name="fee_type" id="fee_type">
										<option value="flat" <?php echo (isset($fee_type) && $fee_type == 'flat' ? 'selected="selected"' : ''); ?>><?php _e('Flat Fee (in cents)', 'memberdeck'); ?></option>
										<option value="percentage" <?php echo (isset($fee_type) && $fee_type == 'percentage' ? 'selected="selected"' : ''); ?>><?php _e('Percentage', 'memberdeck'); ?></option>
									</select>
								</div>
							</div>
							<div class="form-input">
								<label for="app_fee"><?php _e('Application Fee', 'memberdeck'); ?></label>
								<div>
									<input type="text" name="app_fee" id="app_fee" value="<?php echo (isset($app_fee) ? $app_fee : ''); ?>"/>
								</div>
							</div>
							<div class="form-check">
								<label for="dev_mode"><?php _e('Enable Development Mode', 'memberdeck'); ?></label>
								<input type="checkbox" name="dev_mode" id="dev_mode" <?php echo (isset($dev_mode) && $dev_mode == 1 ? 'checked="checked"' : ''); ?>/>
							</div>
							<div class="submit">
								<input type="submit" name="sc_submit" id="submit" class="button"/>
							</div>
						</form>
					</div>
				</div>
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Application Settings', 'memberdeck'); ?></span></h3>
					<div class="inside">
						<!--<p><strong>Level Specific URL:</strong></p>
						<div id="projects">
							<select id="edit-level" name="edit-level">
								<option>-- <?php _e('Select MemberDeck Level', 'memberdeck'); ?> --</option>
							</select>
						</div>-->
						<!--<p><strong id="your-url"><?php _e('Your URL', 'memberdeck'); ?>:</strong></p>
						<p id="the-url">https://connect.stripe.com/oauth/authorize?response_type=code&amp;scope=read_write&amp;client_id=<?php echo $link_id; ?></p>-->
						<p><strong><?php _e('Button Code', 'memberdeck'); ?></strong></p>
						<p><?php _e('The Stripe connect code is automatically generated on the necessary pages, but you can also place it manually. In doing so, ensure the user is logged in first.', 'memberdeck'); ?></p>
						<select id="button-style" name="button-style">
							<option value="stripe-connect"><?php _e('Blue on Light', 'memberdeck'); ?></option>
							<option value="stripe-connect dark"><?php _e('Blue on Dark', 'memberdeck'); ?></option>
							<option value="stripe-connect light-blue"><?php _e('Light on Light', 'memberdeck'); ?></option>
							<option value="stripe-connect light-blue dark"><?php _e('Light on Dark', 'memberdeck'); ?></option>
						</select>
						<p id="button-display">
							<a class="stripe-connect"><span><?php _e('Connect with Stripe', 'memberdeck'); ?></span></a>
						</p>
						<p>
							<textarea id="button-code"><a href="https://connect.stripe.com/oauth/authorize?response_type=code&amp;client_id=<?php echo $link_id; ?>" class="stripe-connect"><span><?php _e('Connect with Stripe', 'memberdeck'); ?></span></a></textarea>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Begin Sidebar -->
	<div class="postbox-container" style="width:35%;">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Using Stripe Connect', 'memberdeck'); ?></span></h3>
					<div class="inside">
						<p><?php _e('Stripe Connect enables site owners to process transactions via Stripe Connect user accounts, and if desired, to charge a fee for doing so.', 'memberdeck'); ?></p>
						<p><?php _e('In order to use <a href="https://stripe.com/connect" target="_blank">Stripe Connect</a>, you will need a <a href="http://stripe.com" target="_blank">Stripe account</a> with an application created via the dashboard.', 'memberdeck'); ?></p>	
						<p><?php _e('When creating your Stripe Connect application, ensure that your URL\'s display as follows: ', 'memberdeck'); ?></p>
						<p><strong><?php _e('http://yourdomain.com/[dashboard-link]?payment_settings=1&ipn_handler=sc_return', 'memberdeck'); ?></strong></p>
					</div>
				</div>
			</div>
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span>About This Plugin</span></h3>
					<div class="inside">
						<p>Stripe and Stripe Connect for IgnitionDeck are designed and developed by <a href="http://virtuousgiant.com">Virtuous Giant</a>.</p>
						<p>Get in touch with us on <a href="https://www.facebook.com/virtuousgiant">Facebook</a>, Twitter <a href="http://twitter.com/virtuousgiant">@virtuousgiant</a>, or App.net <a href="http://alpha.app.net/vg">@VG</a>.</p>
					</div>
				</div>
			</div>
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span>Links</span></h3>
					<div class="inside">
						<ul>
							<li>IgnitionDeck: <a href="http://ignitiondeck.com/?r=idstripe">Crowdfunding for Wordpress</a></li>
							<li>MemberDeck: <a href="http://memberdeck.com/?r=idstripe">Membership Management for WordPress</a></li>
							<li>Retweet for Discount: <a href="http://RetweetforDiscount.com/?r=idstripe">Retweet for Discount</a></li>
							<li>Virtuous Giant: <a href="http://VirtuousGiant.com/?r=idstripe">Virtuous Giant</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Sidebar -->
</div>