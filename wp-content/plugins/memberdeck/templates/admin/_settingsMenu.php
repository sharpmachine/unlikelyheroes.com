<div class="wrap memberdeck">
	<div class="icon32" id="icon-md"></div><h2 class="title"><?php _e('MemberDeck Settings', 'memberdeck'); ?></h2>
	<div class="help">
		<a href="http://forums.ignitiondeck.com" alt="IgnitionDeck Support" title="IgnitionDeck Support" target="_blank"><button class="button button-large"><?php _e('Support', 'memberdeck'); ?></button></a>
		<a href="http://docs.ignitiondeck.com" alt="IgnitionDeck Documentation" title="IgnitionDeck Documentation" target="_blank"><button class="button button-large"><?php _e('Documentation', 'memberdeck'); ?></button></a>
	</div>
	<div class="md-settings-container">
		<div class="postbox-container" style="width:49%; margin-right: 2%">
			<div class="metabox-holder">
				<div class="meta-box-sortables" style="min-height:0;">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e('Product Settings', 'memberdeck'); ?></span> <a id="product-settings" class="md_help_link">help</a></h3>
						<div id="product-settings-help" class="info inside" style="display: none;">
							<strong><?php _e('Create New Product', 'memberdeck'); ?></strong>: <?php _e('To create a new product, leave <em>Edit Product</em> dropdown on <em>Choose Product</em>, then fill in all of your desired info.', 'memberdeck'); ?><br><br>
							<strong><?php _e('Product Price', 'memberdeck'); ?></strong>: <?php _e('The price your customers will pay for this Product. This price is attached to the Product itself, and if anyone already owns this Product when you change the price, records show they own the Product and not what they Paid.', 'memberdeck'); ?><br><br>
							<strong><?php _e('Credit Value', 'memberdeck'); ?></strong>: <?php _e('This is the Credit Price of the Product. If your product is available as a Download purchasable from the Dashboard, then your users will user their Credits to purchase this Product for this many Credits.', 'memberdeck'); ?><br><br>
							<strong><?php _e('Transaction Type', 'memberdeck'); ?></strong>: <?php _e('Select <em>Order</em> if this is a regular transaction.  Select <em>Pre-Order</em> if you are taking pre-orders for this product and will charge all orders on a certain date in the future.', 'memberdeck'); ?><br><br>
  							<strong><?php _e('Product Types', 'memberdeck'); ?></strong>: <?php _e('<em>Standard</em> Products expire in one year, <em>Recurring</em> Products automatically renew after defined set renewal period expires (unless cancelled). <em>Lifetime</em> Products never expire.', 'memberdeck'); ?><br>
  							<?php _e('If you have selected <em>Recurring</em> you will need to select a recurring length, and give it a Stripe Plan Name.', 'memberdeck'); ?><br><br>
  							<span class="tip"><strong><?php _e('Licenses Per Download', 'memberdeck'); ?></strong>: <?php _e('*Full Feature Coming Soon* This will limit Licenses for activation. -1 = Unlimited, 0 = 0 and 1 or higher is that number of activations available per licensed download.', 'memberdeck'); ?></span>
  						</div>
						<div class="inside">
							<p class="list-shortcode" style="display: none;"></p>
							<form method="POST" action="" id="idmember-settings" name="idmember-settings">
								<div class="columns" style="width: 49%; margin-right: 3%;">
									<div class="form-input">
										<label for="edit-level"><?php _e('Edit Product or Get Shortcode', 'memberdeck'); ?></label>
										<select id="edit-level" name="edit-level">
											<option><?php _e('Choose Product', 'memberdeck'); ?></option>
										</select>
									</div>
									<div class="form-input">
										<label for="level-name"><?php _e('Product Name', 'memberdeck'); ?></label>
										<input type="text" name="level-name" id="level-name" value=""/>
									</div>
									<div class="form-input">
										<label for="level-price"><?php _e('Product Price', 'memberdeck'); ?></label>
										<input type="text" name="level-price" id="level-price" value=""/>
									</div>
									<div class="form-input">
										<label for="credit-value"><?php _e('Credit Value', 'memberdeck'); ?></label>
										<input type="number" name="credit-value" id="credit-value" min="0" value="0"/>
									</div>
									<div class="form-input">
										<label for="txn-type"><?php _e('Transaction Type', 'memberdeck'); ?><br/><?php _e('*Pre-orders will disable Paypal', 'memberdeck'); ?></label>
										<select name="txn-type" id="txn-type">
											<option value="capture">Order</option>
											<option value="preauth">Pre-Order</option>
										</select>
									</div>								
								</div>
								<div class="columns" style="width: 47%;">
									<div class="form-input">
										<label for="level-type"><?php _e('Product Type', 'memberdeck'); ?></label>
										<select name="level-type" id="level-type">
											<option value="standard"><?php _e('Standard', 'memberdeck'); ?></option>
											<option value="recurring" <?php echo ($eb == 1 && $epp == 0 ? 'disabled="disabled"' : ''); ?>><?php _e('Recurring', 'memberdeck'); ?></option>
											<option value="lifetime"><?php _e('Lifetime', 'memberdeck'); ?></option>
										</select>
									</div>
									<div id="recurring-input" class="form-input" style="display: none;">
										<label for="recurring-type"><?php _e('Recurring Type', 'memberdeck'); ?></label>
										<select name="recurring-type" id="recurring-type">
											<option value="weekly"><?php _e('Weekly', 'memberdeck'); ?></option>
											<option value="monthly"><?php _e('Monthly', 'memberdeck'); ?></option>
											<option value="annual"><?php _e('Annual', 'memberdeck'); ?></option>
										</select>
										<br/>
										<label for="plan"><?php _e('Stripe Plan Name', 'memberdeck'); ?><br/><?php _e('*can only be used once', 'memberdeck'); ?></label>
										<input type="text" name="plan" id="plan" value=""/>
									</div>
									<div class="form-input">
										<label for="license-count"><?php _e('Licenses per download', 'memberdeck'); ?></label>
										<input type="number" name="license-count" id="license-count" value="" />
									</div>
								</div>
								<div class="submit">
									<input type="submit" name="level-submit" id="level-submit" class="button-primary" value="<?php _e('Create', 'memberdeck'); ?>"/>
									<input type="submit" name="level-delete" id="level-delete" class="button button" value="<?php _e('Delete', 'memberdeck'); ?>"/>
								</div>
							</form>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span><?php _e('Download Settings', 'memberdeck'); ?></span><a id="download-settings" class="md_help_link">help</a></h3>
						<div id="download-settings-help" class="info inside" style="display: none;">
							<?php _e('Downloads are items you will be assigning to Products. These can be anything you want to link your Members to from their Dashboard. From here you can control the link, the image thumbnail location, the <em>more info</em> link, the documentation link, the dashboard postion - and a whole lot more.', 'memberdeck'); ?>
							<span class="tip"><strong><?php _e('Note', 'memberdeck'); ?>:</strong> <?php _e('Any time you update a Download here, the <em>Updated On:</em> message on the dashboard with change to the current date.', 'memberdeck'); ?></span>
  							<strong><?php _e('Create a Download', 'memberdeck'); ?></strong>: <?php _e('To create a new Download, leave the <em>Edit Downloads</em> dropdown at <em>Choose Download</em>, and start filling out your information in the blank fields below. Be sure to select what Section your download will appear in, on the Dashboard, and what Products are allowed access to the Download.', 'memberdeck'); ?> <br><br>
  							<strong><?php _e('Download Name', 'memberdeck'); ?></strong>: <?php _e('This will be the name of your Download, and will display on the Dashboard with this name.', 'memberdeck'); ?> <br><br>
  							<strong><?php _e('Button Text', 'memberdeck'); ?></strong>: <?php _e('The text that be displayed on the button for this Download. This gives you creative liberty to say what you need to.  If you are linking to a page within your site, or to a PDF, or a Survey - you will want the button text to reflect this. Example: Finish the Survey', 'memberdeck'); ?> <br><br>
  							<strong><?php _e('Download Version', 'memberdeck'); ?></strong>: <?php _e('This can be left black if not needed.  If you use this, it will display to the right of your button text, inside the button.  Example: <em>Button Text</em> 1.5 ', 'memberdeck'); ?> <br><br>
  							<strong><?php _e('Download Link', 'memberdeck'); ?></strong>: <?php _e('This is the complete link that the button itself will direct to.  When using Amazon S3, you would only put the name of the download itself.', 'memberdeck'); ?> <br><br>
  							<strong><?php _e('Info Link', 'memberdeck'); ?></strong>: <?php _e('This is the link that the <em>More Info</em> icon would link to.  This displays right by the button.  Leave blank to disable the <em>More Info</em> icon.', 'memberdeck'); ?> <br><br>
  							<strong><?php _e('Documentation Link', 'memberdeck'); ?></strong>: <?php _e('This is the link that the <em>Documentation</em> icon would link to.  This displays right by the button.  Leave blank to disable the <em>Documentation</em> icon.', 'memberdeck'); ?> <br><br>
  							<strong><?php _e('Image URL', 'memberdeck'); ?></strong>: <?php _e('This is the link to the Image for your download - this image will display on the Dashboard, above the Button and Download Name.', 'memberdeck'); ?> 
  							 <span class="tip"><strong><?php _e('Tip', 'memberdeck'); ?>:</strong> <?php _e('When creating images for your downloads, think about making them a consistent width and height between all of them, so their thumbnails look nice together.', 'memberdeck'); ?></span>
  							 <strong><?php _e('Hide From Non-Members', 'memberdeck'); ?></strong>: <?php _e('Check this box to hide this download from everyone except those that already own it.', 'memberdeck'); ?> <br><br>
  							 <strong><?php _e('Host on S3', 'memberdeck'); ?></strong>: <?php _e('If you have a download that is hosted on S#, and you have set up your Amazon S3 settings, check this box. Use the apprpriate file name in the Download Link field. ', 'memberdeck'); ?> <br><br>
  							 <strong><?php _e('Enable Dashboard Purchases', 'memberdeck'); ?></strong>: <?php _e('If you would like Instant Checkout for this Download, check this box. From here, you will be able to select the Product this Download is assigned to. You can also use this for Add-Ons with your Crowdfunding Campaign, also selectable from here if IgnitionDeck Crowdfunding is installed.', 'memberdeck'); ?> <br><br>
  							 <strong><?php _e('Enable Licensing', 'memberdeck'); ?></strong>: <?php _e('Set to <em>Yes</em> in order to have this Download work with Licenses.  Must be assigned to a Product that has <em>Licenses Per Download</em> set.', 'memberdeck'); ?> <br><br>
  						</div>
						<div class="inside">
							<form method="POST" action="" id="idmember-downloads" name="idmember-downloads">
								<div class="columns" style="width: 49%; margin-right: 3%;">
									<div class="form-input">
										<label for="edit-download"><?php _e('Edit Downloads', 'memberdeck'); ?></label>
										<select id="edit-download" name="edit-download">
											<option><?php _e('Choose Download', 'memberdeck'); ?></option>
										</select>
									</div>
									<div class="form-input">
										<label for="download-name"><?php _e('Download Name', 'memberdeck'); ?></label>
										<input type="text" name="download-name" id="download-name" value=""/>
									</div>
									<div class="form-input">
										<label for="button-text"><?php _e('Button Text', 'memberdeck'); ?></label>
										<input type="text" name="button-text" id="button-text" value=""/>
									</div>
									<div class="form-input">
										<label for="download-version"><?php _e('Download Version', 'memberdeck'); ?></label>
										<input type="text" name="download-version" id="download-version" value=""/>
									</div>
									<div class="form-input inline">
										<input type="checkbox" name="hidden" id="hidden" value="1" /> <label for="hidden"><?php _e('Hide from non-members', 'memberdeck'); ?></label>
									</div>
									<div class="form-input inline">
										<input type="checkbox" name="enable_s3" id="enable_s3" value="1" /> <label for="enable_s3"><?php _e('Host on S3', 'memberdeck'); ?></label>
									</div>
									<div class="form-input inline">
										<input type="checkbox" name="enable_occ" id="enable_occ" value="1" /> <label for="enable_occ"><?php _e('Enable Dashboard Purchases', 'memberdeck'); ?></label>
									</div>
									<div class="form-input" style="display: none;">
										<label for="occ_level"><?php _e('Instant Checkout Product Assignemnt', 'memberdeck'); ?></label>
										<select name="occ_level" id="occ_level">
											<option value="0"><?php _e('Select Product', 'memberdeck'); ?></option>
										</select>
									</div>
									<?php if ($crowdfunding) { ?>
									<div class="form-input" style="display: none;">
										<label for="occ_level"><?php _e('Instant Checkout Crowdfunding Assignment ', 'memberdeck'); ?></label>
										<select name="id_project" id="id_project">
											<option value="0"><?php _e('Select Project', 'memberdeck'); ?></option>
										</select>
									</div>
									<?php } ?>
									<div id="assign-checkbox" class="form-input">
										<h4 for="download-assign"><?php _e('Product Assignment', 'memberdeck'); ?></h4>
										<p><a class="select" href="#">Select All</a>&nbsp;&nbsp;<a class="clear" href="#" style="color: #bc0b0b;">Clear</a></p>
									</div>
								</div>
								<div class="columns" style="width: 47%;">
									<div class="form-input">
										<ul>
											<li>
												<div class="downloads-icons download"></div>
												<label for="download-link"><?php _e('Download Link', 'memberdeck'); ?></label>
												<input type="text" id="download-link" name="download-link" value=""/>
											</li>
											<li>
												<div class="downloads-icons info"></div>
												<label for="info-link"><?php _e('Info Link', 'memberdeck'); ?></label>
												<input type="text" id="info-link" name="info-link" value=""/>
											</li>
											<li>
												<div class="downloads-icons doc"></div>
												<label for="doc-link"><?php _e('Documentation Link', 'memberdeck'); ?></label>
												<input type="text" id="doc-link" name="doc-link" value=""/>
											</li>
											<li>
												<div class="downloads-icons image"></div>
												<label for="image-link"><?php _e('Image URL', 'memberdeck'); ?></label>
												<input type="text" id="image-link" name="image-link" value=""/>
											</li>
										</ul>
									</div>
									<div class="form-input">
										<label for="dash-position"><?php _e('Dashboard Position', 'memberdeck'); ?></label>
										<select name="dash-position" id="dash-position">
											<option value="a"><?php _e('A', 'memberdeck'); ?></option>
											<option value="b"><?php _e('B', 'memberdeck'); ?></option>
											<option value="c"><?php _e('C', 'memberdeck'); ?></option>
										</select>
									</div>
									<div class="form-input">
										<label for="licensed"><?php _e('Enable Licensing', 'memberdeck'); ?></label>
										<select name="licensed" id="licensed">
											<option value="0">No</option>
											<option value="1">Yes</option>
										</select>
									</div>
								</div>
								<div class="submit">
									<input type="submit" name="download-submit" id="download-submit" class="button-primary" value="<?php _e('Create', 'memberdeck'); ?>"/>
									<input type="submit" name="download-delete" id="download-delete" class="button" value="<?php _e('Delete', 'memberdeck'); ?>"/>
								</div>
							</form>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span><?php _e('Credit Settings', 'memberdeck'); ?></span><a id="credit-settings" class="md_help_link">help</a></h3>
						<div id="credit-settings-help" class="info inside" style="display: none;">
							<?php _e('Credits can be used to purchase Products, if a Product has been given a <em>Credit Value</em>.  Assigning Credits to a Product, will give those Credits to anyone who purchases that Product.', 'memberdeck'); ?><br><br>
  							<strong><?php _e('Create a Credit Pack', 'memberdeck'); ?></strong>: <?php _e('To create a new Credit Pack, leave the <em>Edit Credits</em> dropdown at <em>Choose Credit</em>, and start filling out the information in the blank fields below.', 'memberdeck'); ?><br><br>
  							 <strong><?php _e('Credit Name', 'memberdeck'); ?></strong>: <?php _e('The name of this Credit pack, for administration.', 'memberdeck'); ?><br><br>
  							 <strong><?php _e('Credit Price', 'memberdeck'); ?></strong>: <?php _e('The value of this credit pack.  Currently for internal adminsitration purposes only.', 'memberdeck'); ?><br><br>
  							 <strong><?php _e('Credit Count', 'memberdeck'); ?></strong>: <?php _e('The amount of Credits included in this Credit Pack.', 'memberdeck'); ?>
  						</div>
						<div class="inside">
							<form method="POST" action="" id="idmember-credits" name="idmember-credits">
								<div class="form-input">
									<label for="edit-credit"><?php _e('Edit Credits', 'memberdeck'); ?></label><br/>
									<select id="edit-credit" name="edit-credit">
										<option><?php _e('Choose Credit', 'memberdeck'); ?></option>
									</select>
								</div>
								<div class="form-input">
									<label for="credit-name"><?php _e('Credit Name', 'memberdeck'); ?></label><br/>
									<input type="text" name="credit-name" id="credit-name" value=""/>
								</div>
								<div class="form-input half">
									<label for="credit-price"><?php _e('Credit Price', 'memberdeck'); ?></label><br/>
									<input type="text" name="credit-price" id="credit-price" value=""/>
								</div>
								<div class="form-input half">
									<label for="credit-price"><?php _e('Credit Count', 'memberdeck'); ?></label><br/>
									<input type="text" name="credit-count" id="credit-count" value=""/>
								</div>
								<div class="form-input">
									<label for="credit-assign"><?php _e('Assign to Product', 'memberdeck'); ?></label><br/>
									<select id="credit-assign" name="credit-assign">
										<option><?php _e('None', 'memberdeck'); ?></option>
									</select>
								</div>
								<div class="submit">
									<input type="submit" name="credit-submit" id="credit-submit" class="button-primary" value="<?php _e('Create', 'memberdeck'); ?>"/>
									<input type="submit" name="credit-delete" id="credit-delete" class="button" value="<?php _e('Delete', 'memberdeck'); ?>"/>
								</div>
							</form>
						</div>
					</div>
					<div class="postbox info">
							<h3 class="hndle"><span><?php _e('About This Plugin', 'memberdeck'); ?></span></h3>
							<div class="inside">
								<p>MemberDeck is designed and developed by <a href="http://virtuousgiant.com">Virtuous Giant</a>.</p>
								<p>Get in touch with us on <a href="https://www.facebook.com/virtuousgiant">Facebook</a>, Twitter <a href="http://twitter.com/virtuousgiant">@virtuousgiant</a>, or App.net <a href="http://alpha.app.net/vg">@VG</a>.</p>
							</div>
						</div>
						<div class="postbox info">
							<h3 class="hndle"><span><?php _e('Links', 'memberdeck'); ?></span></h3>
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
		<!-- Begin Right Side -->
		<div class="postbox-container" style="width:49%;">
			<div class="metabox-holder">
				<div class="meta-box-sortables" style="min-height:0;">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e('Dashboard Setup', 'memberdeck'); ?></span><a id="dashboard-setup" class="md_help_link">help</a></h3>
						<div id="dashboard-setup-help" class="info inside" style="display: none;">
							<strong><?php _e('Dashboard URL', 'memberdeck'); ?></strong>: <?php _e('If you change the location of your Member Dashboard from the default page, be sure to put the URL to that location in here.', 'memberdeck'); ?> <br><br>
							<strong><?php _e('Sections', 'memberdeck'); ?></strong>: <?php _e('You can name each Section, or leave blank if you wish. Be sure to select a <em>Layout Style</em> for each section.', 'memberdeck'); ?> <br><br>
							<strong><?php _e('Dashboard Layout', 'memberdeck'); ?></strong>: <?php _e('When you select a Dashboard Layout, be aware of what sections remain visible in your chosen Layout. The Sections come into play with <em>Downloads</em>, when you assign your Downloads to Sections of the Dashboard.', 'memberdeck'); ?><br><br>
  							<strong><?php _e('Show MemberDeck Love', 'memberdeck'); ?></strong>: <?php _e('This displays a small <em>Powered By MemberDeck</em> image below your Dashboard, with a link to the MemberDeck site.  You do not need to use this, but if you do, Thank you!', 'memberdeck'); ?>
  						</div>
						<div class="inside">
							<form method="POST" action="" id="idmember-dash" name="idmember-dash">
								<div class="columns" style="width: 57%; margin-right: 2%; padding-right: 2%; border-right: 1px solid #C8D3DC;">
									<h4><?php _e('Dashboard Settings', 'memberdeck'); ?></h4>
									<div class="form-input">
										<label for="durl"><?php _e('Dashboard URL', 'memberdeck'); ?></label>
										<input type="text" name="durl" id="durl" value="<?php echo (isset($durl) ? $durl : ''); ?>"/>
									</div>
									<div class="form-input section-wrapper">
										<div class="section-banner">A</div>
										<div class="section-content">
											<label for="a-name"><?php _e('Section A Name', 'memberdeck'); ?></label>
											<input type="text" name="a-name" id="a-name" value="<?php echo (isset($aname) ? $aname : ''); ?>"/>
											<label for="a-layout">Section A Layout Style</label>
											<select name="a-layout" id="a-layout">
												<option value="md-featured" <?php echo (isset($alayout) && $alayout == 'md-featured' ? 'selected="selected"' : ''); ?>>Featured</option>
												<option value="md-2columnThumb" <?php echo (isset($alayout) && $alayout == 'md-2columnThumb' ? 'selected="selected"' : ''); ?>>Thumbnail 2 Column</option>
												<option value="md-3columnThumb" <?php echo (isset($alayout) && $alayout == 'md-3columnThumb' ? 'selected="selected"' : ''); ?>>Thumbnail 3 Column</option>
												<option value="md-4columnThumb" <?php echo (isset($alayout) && $alayout == 'md-4columnThumb' ? 'selected="selected"' : ''); ?>>Thumbnail 4 Column</option>
												<option value="md-list-fat" <?php echo (isset($alayout) && $alayout == 'md-list-fat' ? 'selected="selected"' : ''); ?>>List Fat</option>
												<option value="md-list-thin" <?php echo (isset($alayout) && $alayout == 'md-list-thin' ? 'selected="selected"' : ''); ?>>List Thin</option>
											</select>
										</div>
									</div>
									<div class="form-input section-wrapper">
										<div class="section-banner">B</div>
										<div class="section-content">
											<label for="b-name"><?php _e('Section B Name', 'memberdeck'); ?></label>
											<input type="text" name="b-name" id="b-name" value="<?php echo (isset($bname) ? $bname : ''); ?>"/>
											<label for="b-layout">Section B Layout Style</label>
											<select name="b-layout" id="b-layout">
												<option value="md-featured" <?php echo (isset($blayout) && $blayout == 'md-featured' ? 'selected="selected"' : ''); ?>>Featured</option>
												<option value="md-2columnThumb" <?php echo (isset($blayout) && $blayout == 'md-2columnThumb' ? 'selected="selected"' : ''); ?>>Thumbnail 2 Column</option>
												<option value="md-3columnThumb" <?php echo (isset($blayout) && $blayout == 'md-3columnThumb' ? 'selected="selected"' : ''); ?>>Thumbnail 3 Column</option>
												<option value="md-4columnThumb" <?php echo (isset($blayout) && $blayout == 'md-4columnThumb' ? 'selected="selected"' : ''); ?>>Thumbnail 4 Column</option>
												<option value="md-list-fat" <?php echo (isset($blayout) && $blayout == 'md-list-fat' ? 'selected="selected"' : ''); ?>>List Fat</option>
												<option value="md-list-thin" <?php echo (isset($blayout) && $blayout == 'md-list-thin' ? 'selected="selected"' : ''); ?>>List Thin</option>
											</select>
										</div>
									</div>
									<div class="form-input section-wrapper">
										<div class="section-banner">C</div>
										<div class="section-content">
											<label for="c-name"><?php _e('Section C Name', 'memberdeck'); ?></label>
											<input type="text" name="c-name" id="c-name" value="<?php echo (isset($cname) ? $cname : ''); ?>"/>
											<label for="c-layout">Section C Layout Style</label>
											<select name="c-layout" id="c-layout">
												<option value="md-featured" <?php echo (isset($clayout) && $clayout == 'md-featured' ? 'selected="selected"' : ''); ?>>Featured</option>
												<option value="md-2columnThumb" <?php echo (isset($clayout) && $clayout == 'md-2columnThumb' ? 'selected="selected"' : ''); ?>>Thumbnail 2 Column</option>
												<option value="md-3columnThumb" <?php echo (isset($clayout) && $clayout == 'md-3columnThumb' ? 'selected="selected"' : ''); ?>>Thumbnail 3 Column</option>
												<option value="md-4columnThumb" <?php echo (isset($clayout) && $clayout == 'md-4columnThumb' ? 'selected="selected"' : ''); ?>>Thumbnail 4 Column</option>
												<option value="md-list-fat" <?php echo (isset($clayout) && $clayout == 'md-list-fat' ? 'selected="selected"' : ''); ?>>List Fat</option>
												<option value="md-list-thin" <?php echo (isset($clayout) && $clayout == 'md-list-thin' ? 'selected="selected"' : ''); ?>>List Thin</option>
											</select>
										</div>
									</div>
									<div class="form-input">
										<label for="powered_by"><?php _e('Show MemberDeck Love', 'memberdeck'); ?></label>
										<input type="checkbox" name="powered_by" id="powered_by" value="1" <?php echo (isset($powered_by) && $powered_by == 1 ? 'checked="checked"' : ''); ?>/>
									</div>
									<div class="submit">
										<input type="submit" name="dash-submit" id="dash-submit" class="button-primary" value="<?php _e('Save', 'memberdeck'); ?>"/>
									</div>
								</div>
								<div class="columns">
									<h4><?php _e('Dashboard Layout', 'memberdeck'); ?></h4>
									<div class="form-input dashboardlayout">
										<ul>
											<li>
												<label for="layout1"><img src="<?php echo plugins_url('/images/dash-layout-1.png', dirname(dirname(__FILE__))); ?>"></label><input type="radio" name="layout-select" id="layout1" value="1" <?php echo (isset($layout) && $layout == 1 ? 'checked="checked"' : ''); ?>/>
											</li>
											<li>
												<label for="layout2"><img src="<?php echo plugins_url('/images/dash-layout-2.png', dirname(dirname(__FILE__))); ?>"></label><input type="radio" name="layout-select" id="layout2" value="2" <?php echo (isset($layout) && $layout == 2 ? 'checked="checked"' : ''); ?>/>
											</li>
											<li>
												<label for="layout3"><img src="<?php echo plugins_url('/images/dash-layout-3.png', dirname(dirname(__FILE__))); ?>"></label><input type="radio" name="layout-select" id="layout3" value="3" <?php echo (isset($layout) && $layout == 3 ? 'checked="checked"' : ''); ?>/>
											</li>
											<li>
												<label for="layout4"><img src="<?php echo plugins_url('/images/dash-layout-4.png', dirname(dirname(__FILE__))); ?>"></label><input type="radio" name="layout-select" id="layout4" value="4" <?php echo (isset($layout) && $layout == 4 ? 'checked="checked"' : ''); ?>/>
											</li>
										</ul>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span><?php _e('General Settings', 'memberdeck'); ?></span><a id="general-settings" class="md_help_link">help</a></h3>
						<div id="general-settings-help" class="info inside" style="display: none;">
							<strong><?php _e('Receipt Settings', 'memberdeck'); ?></strong>: <?php _e('This is the information used in your automatically generated receipts, that are sent to your members and customers.', 'memberdeck'); ?><br><br>
							<strong><?php _e('Enable Crowdfunding', 'memberdeck'); ?></strong>: <?php _e('You will only see this if IgnitionDeck Crowdfunding is installed. If so, check this box to allow your Crowdfunding Projects to have Membership capabilities with their Reward Levels.', 'memberdeck'); ?><br><br>
							<strong><?php _e('Enable Amazon S3', 'memberdeck'); ?></strong>: <?php _e('If you would like your <em>Downloads</em>to be hosted with Amazon S3, check this box.  You will need to setup your Amazon S3 account in <em>S3 Settings</em> admin screen, available once this is activated.', 'memberdeck'); ?><br><br>
  							<strong><?php _e('Enable Creator Accounts', 'memberdeck'); ?></strong>: <?php _e('You will only see this if IgnitionDeck Enterprise is activated.  Check this box to allow your site members to create their own Projects, using the IgnitionDeck Enterprise Front-End Submission feature.', 'memberdeck'); ?>
  						</div>
						<div class="inside">
							<form method="POST" action="" id="idmember-dash" name="idmember-dash">
								<h4><?php _e('Receipt Settings', 'memberdeck'); ?></h4>
								<div class="form-input">
									<label for="co-name"><?php _e('Company Name (for receipts)', 'memberdeck'); ?></label>
									<input type="text" name="co-name" id="co-name" value="<?php echo (isset($coname) ? $coname : ''); ?>"/>
								</div>
								<div class="form-input">
									<label for="co-email"><?php _e('Customer Service Email (for receipts)', 'memberdeck'); ?></label>
									<input type="text" name="co-email" id="co-email" value="<?php echo (isset($coemail) ? $coemail : ''); ?>"/>
								</div>
								<div class="form-input inline">
									<input type="checkbox" name="crowdfunding" id="crowdfunding" value="1" <?php echo (isset($crowdfunding) && $crowdfunding == 1 ? 'checked="checked"' : ''); ?>/>
									<label for="crowdfunding"><?php _e('Enable Crowdfunding', 'memberdeck'); ?></label>
								</div>
								<div class="form-input inline">
									<input type="checkbox" name="s3" id="s3" value="1" <?php echo (isset($s3) && $s3 == 1 ? 'checked="checked"' : ''); ?>/>
									<label for="s3"><?php _e('Enable Amazon S3', 'memberdeck'); ?></label>
								</div>
								<?php if (function_exists('is_id_pro') && is_id_pro()) { ?>
								<div class="form-input inline">
									<input type="checkbox" name="enable_creator" id="enable_creator" value="1" <?php echo (isset($enable_creator) && $enable_creator == 1 ? 'checked="checked"' : ''); ?>/>
									<label for="enable_creator"><?php _e('Enable Creator Accounts', 'memberdeck'); ?></label>
								</div>
								<?php } ?>
								<div class="submit">
									<input type="submit" name="receipt-submit" id="receipt-submit" class="button-primary" value="<?php _e('Save', 'memberdeck'); ?>"/>
								</div>
							</form>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle"><span><?php _e('CRM Settings', 'memberdeck'); ?></span> <a id="crm-settings" class="md_help_link">help</a></h3>
						<div id="crm-settings-help" class="info inside" style="display: none;">
							<strong><?php _e('Enable Shipping Info on Profile', 'memberdeck'); ?></strong>: <?php _e('Check this box to display the Shipping Info fields on a users My Profile screen.  Use this when you need your members to be able to update their Shipping Info at any time.', 'memberdeck'); ?>
  						</div>
						<div class="inside">
							<form method="POST" action="">
								<div class="columns">
									<div class="form-input inline">
									<p>
										<strong><?php _e('Profile Settings', 'memberdeck'); ?></strong>
									</p>
										<input type="checkbox" name="shipping_info" id="shipping_info" value="1" <?php echo (isset($shipping_info) && $shipping_info == 1 ? 'checked="checked"' : ''); ?> /> <label for="shipping_info"><?php _e('Enable Shipping Info on Profile', 'memberdeck'); ?></label>
									</div>
									<hr>
									<img style="float: right;" src="<?php echo plugins_url('/images/mailchimp.png', dirname(dirname(__FILE__))); ?>">
									<p>
										<strong><?php _e('Mailchimp Settings', 'memberdeck'); ?></strong>
									</p>
										<?php _e('Sign up for a free ', 'memberdeck'); ?><a href="http://eepurl.com/DqCdz">Mailchimp</a> <?php _e('account to start building a customer database.', 'memberdeck'); ?>
									<p>
									</p>
									<div class="form-input half">
										<label for="mailchimp_key"><?php _e('Mailchimp API Key', 'memberdeck'); ?></label>
										<input type="text" name="mailchimp_key" id="mailchimp_key" value="<?php echo (isset($mailchimp_key) ? $mailchimp_key : ''); ?>"/>
									</div>
									<div class="form-input half">
										<label for="mailchimp_list"><?php _e('Mailchimp List ID', 'memberdeck'); ?></label>
										<input type="text" name="mailchimp_list" id="mailchimp_list" value="<?php echo (isset($mailchimp_list) ? $mailchimp_list : ''); ?>"/>
									</div>
									<div class="form-input inline">
										<input type="checkbox" name="enable_mailchimp" id="enable_mailchimp" value="1" <?php echo (isset($enable_mailchimp) && $enable_mailchimp == 1 ? 'checked="checked"' : ''); ?> /> <label for="enable_mailchimp"><?php _e('Enable Mailchimp?', 'memberdeck'); ?></label>
									</div>
									<hr>
									<img style="float: right;" src="<?php echo plugins_url('/images/sendgrid.png', dirname(dirname(__FILE__))); ?>">
									<p>
										<strong><?php _e('SendGrid Settings', 'memberdeck'); ?></strong>
									</p>
									<p><?php _e('Sign up for a free ', 'memberdeck'); ?><a href="http://sendgrid.tellapal.com/a/clk/1QS3FN">Sendgrid</a> <?php _e('account to start sending transactional email.', 'memberdeck'); ?></p>
									<div class="form-input half">
										<label for="sendgrid_username"><?php _e('Sendgrid Username', 'memberdeck'); ?></label>
										<input type="text" name="sendgrid_username" id="sendgrid_username" value="<?php echo (isset($sendgrid_username) ? $sendgrid_username : ''); ?>"/>
									</div>
									<div class="form-input half">
										<label for="sendgrid_pw"><?php _e('Sendgrid Password', 'memberdeck'); ?></label>
										<input type="password" name="sendgrid_pw" id="sendgrid_pw" value="<?php echo (isset($sendgrid_pw) ? $sendgrid_pw : ''); ?>"/>
									</div>
									<div class="form-input inline">
										<input type="checkbox" name="enable_sendgrid" id="enable_sendgrid" value="1" <?php echo (isset($enable_sendgrid) && $enable_sendgrid == 1 ? 'checked="checked"' : ''); ?> <?php echo (isset($enable_mandrill) && $enable_mandrill == 1 ? 'disabled="disabled"' : ''); ?>/> <label for="enable_sendgrid inline"><?php _e('Enable SendGrid? (Will replace default SMTP Settings)', 'memberdeck'); ?></label>
									</div>
									<hr>
									<img style="float: right;" src="<?php echo plugins_url('/images/mandrill.png', dirname(dirname(__FILE__))); ?>">
									<p>
										<strong><?php _e('Mandrill Settings', 'memberdeck'); ?></strong>
									</p>
										<?php _e('Sign up for a free ', 'memberdeck'); ?><a href="http://mandrillapp.com">Mandrill</a> <?php _e('account to start sending transactional email.', 'memberdeck'); ?>
									<p>
									</p>
									<div class="form-input">
										<label for="mandrill_key"><?php _e('Mandrill API Key', 'memberdeck'); ?></label>
										<input type="text" name="mandrill_key" id="mandrill_key" value="<?php echo (isset($mandrill_key) ? $mandrill_key : ''); ?>"/>
									</div>
									<div class="form-input inline">
										<input type="checkbox" name="enable_mandrill" id="enable_mandrill" value="1" <?php echo (isset($enable_mandrill) && $enable_mandrill == 1 ? 'checked="checked"' : ''); ?> <?php echo (isset($enable_sendgrid) && $enable_sendgrid == 1 ? 'disabled="disabled"' : ''); ?>/> <label for="enable_mandrill"><?php _e('Enable Mandrill? (Will replace default SMTP Settings)', 'memberdeck'); ?></label>
									</div>
									<div class="submit">
										<input type="submit" name="crm_submit" id="crm_submit" class="button-primary" value="<?php _e('Save', 'memberdeck'); ?>"/>
									</div>
									<hr>
									<p>
										<strong><?php _e('Data Export', 'memberdeck'); ?></strong>
									</p>
									<div class="form-input">
										<p>
											<label for="export_customers"><?php _e('Export Customer Data to CSV', 'memberdeck'); ?></label>
										</p>
										<input type="submit" name="export_customers" id="export_customers" class="button-primary button-large" value="<?php _e('Start Export', 'memberdeck'); ?>"/>
									</div>
								</div>
							</form>
						</div>
					</div>
					</div>
			</div>
		</div>
	</div>
	<!-- End Sidebar -->
</div>