<div class="memberdeck">
	<?php include_once MD_PATH.'templates/_mdProfileTabs.php'; ?>
	<ul class="md-box-wrapper full-width cf">
		<li class="md-box <?php echo $p_width; ?>">
			<div class="md-profile">
				<div class="md-avatar">
					<?php if (is_user_logged_in()) {
						echo get_avatar( $current_user->ID);
					}
					?>
				</div>
				<div class="md-fullname">
					<?php 
					      echo (isset($fname) ? '<span class="md-firstname">'.$fname.'</span>' : '')." ";
					      echo (isset($lname) ? '<span class="md-lastname">'.$lname.'</span>' : '')."<br>";
					?>
				</div>
					<?php if (isset($md_credits) && $md_credits > 0) {
						echo '<div class="md-credits">';
						echo __('Available Credits: ', 'memberdeck').$md_credits;
					}
					else {
						echo '<div class="md-membership">';
					 	echo (!empty($user_levels) ? __('Customer', 'memberdeck') : __('Member', 'memberdeck')); 
					} ?>
				</div>
				<div class="md-registered">
					<?php echo (isset($registered) ? __('Registered', 'memberdeck').': '.date("n/j/Y", strtotime($registered)) : '') . "<br>"; ?>
				</div>
			</div>
			<div class="md-dash-sidebar">
				<ul>
			      <?php ( function_exists('dynamic_sidebar') ? dynamic_sidebar('dashboard-sidebar')  : ''); ?>
			   </ul>
			</div>
		</li>
		<li class="md-box <?php echo $a_width; ?>">
			<ul class="<?php echo $alayout; ?>">
				<h3 class="big center"><?php echo (isset($aname) ? $aname : ''); ?></h3>
				<?php 
					if (isset($download_array['visible'])) {
						foreach ($download_array['visible'] as $extension) { 
							if ($extension->position == 'a') {
							?>
				<li>
					<img src="<?php echo $extension->image_link; ?>">
					<span class="title"><?php echo $extension->download_name; ?></span>
					<div class="components">
						<button onclick="location.href='<?php echo get_permalink()."?md_download=".$extension->id."&key=".$key; ?>';" title="<?php echo $extension->button_text; ?> <?php echo $extension->download_name; ?>" class="button"> <i class="md-icon-variable"></i><?php echo (!empty($extension->button_text) ? $extension->button_text : __('Download', 'memberdeck').' '.$extension->version); ?></button>
						<span class="license"><?php echo (!empty($extension->key) ? '<a><i class="fa fa-key"></i></a><span class="license-display"><strong>'.__('License Key:', 'memberdeck').'</strong> <br> <span class="key">'.$extension->key.'</span></span>' : ''); ?></span>
						<span class="info"><a href="<?php echo $extension->info_link; ?>" title="<?php _e('Learn About', 'memberdeck'); ?> <?php echo $extension->download_name; ?>"><i class="fa fa-info-circle"></i></a></span>
						<span class="documentation"><a href="<?php echo $extension->doc_link; ?>" title="<?php echo $extension->download_name; ?> <?php _e('Documentation', 'memberdeck'); ?>"><i class="fa fa-book"></i></a></span>
						<span class="updatedOn"><?php _e('Updated', 'memberdeck'); ?> <?php echo date('m/d/Y', strtotime($extension->updated)); ?></span>
					</div>
				</li>
				<?php 
					}
						} 
							}
					?>
					<?php 
				if (isset($download_array['invisible'])) {
					foreach ($download_array['invisible'] as $hidden) {
						if ($hidden->position == 'a' && $hidden->hidden !== '1'){ ?>
				<a class="inactive-item" href="<?php echo $hidden->info_link; ?>">
					<li class="inactive" data-levelid="<?php echo (isset($hidden->occ_level) && $hidden->occ_level > 0 && $show_occ == true ? $hidden->occ_level : ''); ?>" data-pid="<?php echo (isset($hidden->occ_level) && $hidden->occ_level > 0  && $hidden->id_project > 0 ? $hidden->id_project : ''); ?>">
						<img src="<?php echo $hidden->image_link; ?>">
						<span class="title"><?php echo $hidden->download_name; ?></span>
						<div class="components">
							<button><i class="md-icon-variable"></i><?php echo (isset($hidden->enable_occ) && $hidden->enable_occ ? __('Click to Purchase', 'memberdeck') : __('Click for Info', 'memberdec')); ?></button>
						</div>
					</li>
				</a>
				<?php 
					}
						}
							} ?>
			</ul>
		</li>
		<li class="md-box <?php echo $b_width; ?>">
			<h3 class="big center"><?php echo (isset($bname) ? $bname : ''); ?></h3>
			<ul class="<?php echo $blayout; ?>">
				<?php 
					if (isset($download_array['visible'])) {
						foreach ($download_array['visible'] as $extension) { 
							if ($extension->position == 'b') {
							?>
				<li>
					<img src="<?php echo $extension->image_link; ?>">
					<span class="title"><?php echo $extension->download_name; ?></span>
					<div class="components">
						<button onclick="location.href='<?php echo get_permalink()."?md_download=".$extension->id."&key=".$key; ?>';" title="<?php echo $extension->button_text; ?> <?php echo $extension->download_name; ?>" class="button"> <i class="md-icon-variable"></i><?php echo (!empty($extension->button_text) ? $extension->button_text : __('Download', 'memberdeck').' '.$extension->version); ?></button>
						<span class="license"><?php echo (!empty($extension->key) ? '<a><i class="fa fa-key"></i></a><span class="license-display"><strong>'.__('License Key:', 'memberdeck').'</strong> <br> <span class="key">'.$extension->key.'</span></span>' : ''); ?></span>
						<span class="info"><a href="<?php echo $extension->info_link; ?>" title="<?php _e('Learn About', 'memberdeck'); ?> <?php echo $extension->download_name; ?>"><i class="fa fa-info-circle"></i></a></span>
						<span class="documentation"><a href="<?php echo $extension->doc_link; ?>" title="<?php echo $extension->download_name; ?> <?php _e('Documentation', 'memberdeck'); ?>"><i class="fa fa-book"></i></a></span>
						<span class="updatedOn"><?php _e('Updated', 'memberdeck'); ?> <?php echo date('m/d/Y', strtotime($extension->updated)); ?></span>
					</div>
				</li>
				<?php 
					}
						} 
							}
					?>
					<?php 
				if (isset($download_array['invisible'])) {
					foreach ($download_array['invisible'] as $hidden) {
						if ($hidden->position == 'b' && $hidden->hidden !== '1'){ ?>
				<a class="inactive-item" href="<?php echo $hidden->info_link; ?>">
					<li class="inactive" data-levelid="<?php echo (isset($hidden->occ_level) && $hidden->occ_level > 0 && $show_occ == true ? $hidden->occ_level : ''); ?>" data-pid="<?php echo (isset($hidden->occ_level) && $hidden->occ_level > 0  && $hidden->id_project > 0 ? $hidden->id_project : ''); ?>">
						<img src="<?php echo $hidden->image_link; ?>">
						<span class="title"><?php echo $hidden->download_name; ?></span>
						<div class="components">
							<button><i class="md-icon-variable"></i><?php echo (isset($hidden->enable_occ) && $hidden->enable_occ ? __('Click to Purchase', 'memberdeck') : __('Click for Info', 'memberdec')); ?></button>
						</div>
					</li>
				</a>
				<?php 
					}
						}
							} ?>

			</ul>
		</li>
		<li class="md-box <?php echo $c_width; ?>">
			<h3 class="big center"><?php echo (isset($cname) ? $cname : ''); ?></h3>
			<ul class="<?php echo $clayout; ?>">
					<?php 
					if (isset($download_array['visible'])) {
						foreach ($download_array['visible'] as $extension) { 
							if ($extension->position == 'c') {
							?>
				<li>
					<img src="<?php echo $extension->image_link; ?>">
					<span class="title"><?php echo $extension->download_name; ?></span>
					<div class="components">
						<button onclick="location.href='<?php echo get_permalink()."?md_download=".$extension->id."&key=".$key; ?>';" title="<?php echo $extension->button_text; ?> <?php echo $extension->download_name; ?>" class="button"> <i class="md-icon-variable"></i><?php echo (!empty($extension->button_text) ? $extension->button_text : __('Download', 'memberdeck').' '.$extension->version); ?></button>
						<span class="license"><?php echo (!empty($extension->key) ? '<a><i class="fa fa-key"></i></a><span class="license-display"><strong>'.__('License Key:', 'memberdeck').'</strong> <br> <span class="key">'.$extension->key.'</span></span>' : ''); ?></span>
						<span class="info"><a href="<?php echo $extension->info_link; ?>" title="<?php _e('Learn About', 'memberdeck'); ?> <?php echo $extension->download_name; ?>"><i class="fa fa-info-circle"></i></a></span>
						<span class="documentation"><a href="<?php echo $extension->doc_link; ?>" title="<?php echo $extension->download_name; ?> <?php _e('Documentation', 'memberdeck'); ?>"><i class="fa fa-book"></i></a></span>
						<span class="updatedOn"><?php _e('Updated', 'memberdeck'); ?> <?php echo date('m/d/Y', strtotime($extension->updated)); ?></span>
					</div>
				</li>
				<?php 
					}
						} 
							}
					?>
					<?php 
				if (isset($download_array['invisible'])) {
					foreach ($download_array['invisible'] as $hidden) {
						if ($hidden->position == 'c' && $hidden->hidden !== '1'){ ?>
				<a class="inactive-item" href="<?php echo $hidden->info_link; ?>">
					<li class="inactive" data-levelid="<?php echo (isset($hidden->occ_level) && $hidden->occ_level > 0 && $show_occ == true ? $hidden->occ_level : ''); ?>" data-pid="<?php echo (isset($hidden->occ_level) && $hidden->occ_level > 0  && $hidden->id_project > 0 ? $hidden->id_project : ''); ?>">
						<img src="<?php echo $hidden->image_link; ?>">
						<span class="title"><?php echo $hidden->download_name; ?></span>
						<div class="components">
							<button><i class="md-icon-variable"></i><?php echo (isset($hidden->enable_occ) && $hidden->enable_occ ? __('Click to Purchase', 'memberdeck') : __('Click for Info', 'memberdeck')); ?></button>
						</div>
					</li>
				</a>
				
				<?php 
					}
						}
							} ?>
			</ul>
		</li>
	</ul>
	<?php if (isset($powered_by) && $powered_by == 1) { ?>
	<div class="powered-by">
		<a href="http://MemberDeck.com" alt="WordPress Membership Management" title="WordPress Membership Management" target="_blank"><img src="<?php echo plugins_url('/images/powered-by-md.png', dirname(dirname(__FILE__))); ?>"/></a>
	</div>
	<?php } ?>
	<!-- Buy Tooltip -->
	<div class="memberdeck buy-tooltip" style="display: none;">
		<div class="tt-title"><?php _e('Buy', 'memberdec'); ?> <span class="tt-product-name"></span></div>
		<div class="price">$<span class="tt-price"></span> or <span class="tt-credit-value"></span> <span class="credit-text"><?php _e('credit', 'memberdeck'); ?></span></div>
		<div class="credits-avail"><?php _e('you have', 'memberdeck'); ?> <?php echo (isset($md_credits) ? $md_credits : 0); ?> <?php echo (isset($md_credits) && ($md_credits > 1 || $md_credits == 0) ? __('credits available', 'memberdeck') : __('credit available', 'memberdeck')); ?> </div>
		<div class="payment-options">
			<select name="occ_method">
				<option><?php _e('Select Payment Option', 'memberdeck'); ?></option>
				<?php if (isset($instant_checkout) && $instant_checkout == 1) { ?>
				<option value="cc"><?php _e('Card on File', 'memberdeck'); ?></option>
				<?php } ?>
				<?php if (isset($md_credits) && $md_credits > 0) { ?>
				<option value="credit"><?php _e('Pay with Credits', 'memberdeck'); ?></option>
				<?php } ?>
			</select>
		</div>
		<a href="<?php echo (isset($current_user) ? the_permalink().'?edit-profile='.$current_user->ID : ''); ?>" class="oneclick"><?php _e('What is Instant Checkout', 'memberdeck'); ?>?</a>
		<button class="md_occ invert"><?php _e('Confirm', 'memberdeck'); ?></button>
		<div class="tt-footer"><a class="tt-more" href="#"><?php _e('Learn More', 'memberdeck'); ?></a>&nbsp;<a href="#" class="tt-close"><?php _e('Close', 'memberdeck'); ?></a></div>
	</div>
</div>