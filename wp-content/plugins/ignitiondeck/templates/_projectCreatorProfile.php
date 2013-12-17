<div class="ignitiondeck id-creatorprofile">
	<div class="id-creator-avatar"><img src="<?php echo (isset($company_logo) ? $company_logo : ''); ?>" title="Creator Name"/></div>
	<div class="id-creator-content">
		<div class="id-creator-name"><?php echo (isset($company_name) ? $company_name : ''); ?></div>
		<div class="id-creator-title"><?php echo (isset($company_location) ? $company_location : ''); ?></div>
	</div>
	<div class="id-creator-links">
		<?php if (!empty($company_twitter)) { ?>
		<a href="<?php echo $company_twitter; ?>" class="twitter"><?php _e('Twitter', 'ignitiondeck'); ?></a>
		<?php } ?>
		<?php if (!empty($company_fb)) { ?>
		<a href="<?php echo $company_fb; ?>" class="facebook"><?php _e('Facebook', 'ignitiondeck'); ?></a>
		<?php } ?>
		<!--<a href="#" class="googleplus"></a>-->
		<?php if (!empty($company_url)) { ?>
		<a href="<?php echo $company_url; ?>" class="website"><?php echo $company_url; ?></a>
		<?php } ?>
	</div>
	<div class="cf"></div>
</div>