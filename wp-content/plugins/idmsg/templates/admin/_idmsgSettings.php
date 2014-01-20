<div class="wrap">
	<div class="icon32" id="icon-options-general"></div><h2>IgnitionDeck Email Messaging Settings</h2>
	<div id="postbox-settings" class="postbox-container" style="width:65%; margin-right: 5%">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span>Button Settings</span></h3>
					<div class="inside">
						<form method="POST" action="" id="idmsg-settings" name="idmsg-settings">
							<div class="form-check">
								<input type="checkbox" name="confirmation-on" id="confirmation-on" value="1" <?php echo ($confirmation_on == 1 ? 'checked="checked"' : ''); ?>/>
								<label for="confirmation-on">Turn on confirmation emails? (This will send an email to blog owner, notifying of new pledges)</label>
							</div>
							<div class="form-check">
								<input type="checkbox" name="receipt-on" id="receipt-on" value="1" <?php echo ($receipt_on == 1 ? 'checked="checked"' : ''); ?>/>
								<label for="receipt-on">Turn on receipt emails? (This will send an email to supporter with pledge information)</label>
							</div>
							<div class="form-email">
								<label for="notification-email">Where would you like notification emails sent?</label><br/>
								<input type="email" name="notification-email" id="notification-email" value="<?php echo $notification_email; ?>"/>
							</div>
							<div class="form-email">
								<label for="from-email">From Email</label><br/>
								<input type="email" name="from-email" id="from-email" value="<?php echo $from_email; ?>"/>
							</div>
							<div class="form-area">
								<label for="receipt-msg">Custom Receipt Message. (Leave blank for default. 250 char limit, accepts HTML)</label>
								<p>Custom message area supports the following merge fields: {{NAME}}, {{AMOUNT}}, {{DATE}}.</p>
								<textarea name="receipt-msg" id="receipt-msg" cols="80" rows="10"><?php echo stripslashes($receipt_msg); ?></textarea>
							</div>
							<strong>Message Preview</strong><br/>
							<span class="word_preview"></span>
							<div class="submit">
								<input type="submit" name="submit" id="submit" class="button" value="Save Settings"/>
							</div>
						</form>
						<div id="send-test">
							<a href="#" id="test-link" name="test-link">Send Test</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="postbox-test" class="postbox-container" style="display: none; width:65%; margin-right: 5%">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span>Test Receipt</span></h3>
					<div class="inside">
						<p>*Ensure you've saved a custom message before testing.</p>
						<form method="POST" action="" id="idmsg-settings" name="idmsg-settings">
							<div class="form-email">
								<label for="test-email">Where Should We Send The Test?</label><br/>
								<input type="email" name="test-email" id="test-email" value=""/>
							</div>
							<div class="form-email">
								<label for="test-from">From Email</label><br/>
								<input type="email" name="test-from" id="test-from" value=""/>
							</div>
							<div class="submit">
								<input type="submit" name="send-test" id="send-test" class="button" value="Send Test"/>
							</div>
						</form>
						<div id="cancel-test">
							<a href="#" id="test-link" name="test-link">Go Back</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Begin Sidebar -->
	<div class="postbox-container" style="width:20%;">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span>About This Plugin</span></h3>
					<div class="inside">
						<p>Email Messaging for ID is designed and developed by <a href="http://virtuousgiant.com">Virtuous Giant</a>.</p>
						<p>Get in touch with us on <a href="https://www.facebook.com/virtuousgiant">Facebook</a>, Twitter <a href="http://twitter.com/virtuousgiant">@virtuousgiant</a>, or App.net <a href="http://alpha.app.net/vg">@VG</a>.</p>
					</div>
				</div>
			</div>
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span>Support this Plugin</span></h3>
					<div class="inside">
						<div style="text-align: center;">
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="AYQCM6XAX5BTL">
								<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
								<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span>Links</span></h3>
					<div class="inside">
						<ul>
							<li>IgnitionDeck - <a href="http://ignitiondeck.com">Crowdfunding for Wordpress</a></li>
							<li>Virtuous Giant - <a href="http://VirtuousGiant.com">Virtuous Giant</a></li>
							<li>Retweet for Discount - <a href="http://RetweetforDiscount.com">Retweet for Discount</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Sidebar -->
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#test-link").click(function() {
			jQuery("#postbox-settings").hide();
			jQuery("#postbox-test").show();
		});
		jQuery("#cancel-test").click(function() {
			jQuery("#postbox-settings").show();
			jQuery("#postbox-test").hide();
		});
		jQuery("#send-test").submit(function() {
			jQuery("#postbox-settings").show();
			jQuery("#postbox-test").hide();
		});
		var word = jQuery("#receipt-msg").val();
		jQuery(".word_preview").html(word);
		jQuery("#receipt-msg").keyup(function() {
			word=jQuery(this).val();
			jQuery(".word_preview").html(word);
			return false;
		});
	});
</script>
<script type="text/javascript">

</script>