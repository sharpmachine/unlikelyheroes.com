<div class="wrap">
	<div class="icon32" id="icon-options-general"></div><h2><?php _e('Stripe Connect', 'idstripe'); ?></h2>
	<div class="postbox-container" style="width:60%; margin-right: 5%">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Application Settings', 'idstripe'); ?></span></h3>
					<div class="inside">
						<form method="POST" action="" id="idsc_settings" name="idsc_settings">
							<div class="form-input">
								<label for="client_id"><?php _e('Production Client ID', 'idstripe'); ?></label>
								<div>
									<input type="text" name="client_id" id="client_id" value="<?php echo (isset($client_id) ? $client_id : ''); ?>"/>
								</div>
							</div>
							<div class="form-input">
								<label for="dev_client_id"><?php _e('Development Client ID', 'idstripe'); ?></label>
								<div>
									<input type="text" name="dev_client_id" id="dev_client_id" value="<?php echo (isset($dev_client_id) ? $dev_client_id : ''); ?>"/>
								</div>
							</div>
							<div class="form-select">
								<label for="fee_type"><?php _e('Fee Type', 'idstripe'); ?></label>
								<div>
									<select name="fee_type" id="fee_type">
										<option value="flat" <?php echo (isset($fee_type) && $fee_type == 'flat' ? 'selected="selected"' : ''); ?>><?php _e('Flat Fee (in cents)', 'idstripe'); ?></option>
										<option value="percentage" <?php echo (isset($fee_type) && $fee_type == 'percentage' ? 'selected="selected"' : ''); ?>><?php _e('Percentage', 'idstripe'); ?></option>
									</select>
								</div>
							</div>
							<div class="form-input">
								<label for="app_fee"><?php _e('Application Fee', 'idstripe'); ?></label>
								<div>
									<input type="text" name="app_fee" id="app_fee" value="<?php echo (isset($app_fee) ? $app_fee : ''); ?>"/>
								</div>
							</div>
							<div class="form-check">
								<label for="dev_mode"><?php _e('Enable Development Mode', 'idstripe'); ?></label>
								<input type="checkbox" name="dev_mode" id="dev_mode" <?php echo (isset($dev_mode) && $dev_mode == 1 ? 'checked="checked"' : ''); ?>/>
							</div>
							<div class="submit">
								<input type="submit" name="submit" id="submit" class="button"/>
							</div>
						</form>
					</div>
				</div>
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Application Settings', 'idstripe'); ?></span></h3>
					<div class="inside">
						<p><strong><?php _e('Project Specific URL:', 'idstripe'); ?></strong></p>
						<div id="projects">
							<select id="project-list" name="project-list">
								<option>-- <?php _e('Select Project', 'idstripe'); ?> --</option>
							</select>
						</div>
						<p><strong id="your-url"><?php _e('Your URL:', 'idstripe'); ?></strong></p>
						<p id="the-url">https://connect.stripe.com/oauth/authorize?response_type=code&amp;scope=read_write&amp;client_id=<?php echo $link_id; ?></p>
						<p><strong><?php _e('Button Code', 'idstripe'); ?></strong></p>
						<select id="button-style" name="button-style">
							<option value="stripe-connect"><?php _e('Blue on Light', 'idstripe'); ?></option>
							<option value="stripe-connect dark"><?php _e('Blue on Dark', 'idstripe'); ?></option>
							<option value="stripe-connect light-blue"><?php _e('Light on Light', 'idstripe'); ?></option>
							<option value="stripe-connect light-blue dark"><?php _e('Light on Dark', 'idstripe'); ?></option>
						</select>
						<p id="button-display">
							<a href="https://connect.stripe.com/oauth/authorize?response_type=code&amp;scope=read_write&amp;client_id=<?php echo $link_id; ?>" class="stripe-connect"><span><?php _e('Connect with Stripe', 'idstripe'); ?></span></a>
						</p>
						<p>
							<textarea id="button-code"><a href="https://connect.stripe.com/oauth/authorize?response_type=code&amp;client_id=<?php echo $link_id; ?>" class="stripe-connect"><span><?php _e('Connect with Stripe', 'idstripe'); ?></span></a></textarea>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Begin Sidebar -->
	<div class="postbox-container" style="width:25%;">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Using Stripe Connect', 'idstripe'); ?></span></h3>
					<div class="inside">
						<p><?php _e('Stripe Connect enables site owners to process transactions via Stripe Connect user accounts, and if desired, to charge a fee for doing so.', 'idstripe'); ?></p>
						<p><?php _e('In order to use', 'idstripe'); ?> <a href="https://stripe.com/connect" target="_blank"><?php _e('Stripe Connect', 'idstripe'); ?></a>, <?php _e('you will need a', 'idstripe'); ?> <a href="http://stripe.com" target="_blank"><?php _e('Stripe account', 'idstripe'); ?></a> <?php _e('with an application created via the dashboard.', 'idstripe'); ?></p>	
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