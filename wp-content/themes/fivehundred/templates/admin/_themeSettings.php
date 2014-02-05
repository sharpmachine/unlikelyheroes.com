<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div><h2><?php _e('500 Theme', 'fivehundred'); ?></h2>
		<form method="POST" action="" id="fh_theme_settings">
			<table class="form-table">
				<tbody>
					<tr>
						<td>
						<label for="logo-input"><h2><?php _e('Replace Site Name with your Logo', 'fivehundred'); ?></h2></label>
						<p><?php _e('Maximum image dimensions are 300 pixels wide and/or 50 pixels high.', 'fivehundred'); ?><br/><span style="font-size: .85em;"><?php _e('Note: If you upload an image larger than this, it will automatically resize the image to fit, however we highly recommend you control the image dimensions prior to upload.', 'fivehundred'); ?></span></p>
							<div class="uploader">
  								<input type="text" name="logo-input" id="logo-input" value="<?php echo ($logo ? $logo : ''); ?>"/>
  								<input type="button" class="button" name="logo-upload" id="logo-upload" value="Upload" /><br/>
  								<span id="logo-preview"><?php echo ($logo ? '<img src="'.$logo.'"/>' : ''); ?></span>
							</div>
							
						</td>
					</tr>
					<tr>
						<td>
							<label for="about-us"><h2><?php _e('About Us Text', 'fivehundred'); ?></h2></label>
							<p><?php _e('This will appear below your featured projects on the home page.', 'fivehundred'); ?></p>
							<?php echo wp_editor(($about ? $about : ""), 'about-us', array(
								'media_buttons' => true,
								'textarea_rows' => 10,
								'tinymce' => true)); 
							?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="home-project"><?php _e('Home Page Layout', 'fivehundred'); ?></label><br/>
							<?php echo $levels; ?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="home-projects"><?php _e('Number of Projects to Display on Home Page', 'fivehundred'); ?></label><br/>
							<input type="number" id="home-projects" name="home-projects" class="regular-text" value="<?php echo ($home_projects ? $home_projects : ""); ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<label for="custom_css"><h2><?php _e('Custom CSS', 'fivehundred'); ?></h2></label>
							<p><?php _e('Enter custom CSS here.', 'fivehundred'); ?></p>
							<textarea name="custom_css" id="custom_css"><?php echo (isset($custom_css) ? $custom_css : ''); ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label for="ga"><h2><?php _e('Google Analytics Code', 'fivehundred'); ?></h2></label>
							<p><?php _e('Enter your full Google Analytics code snippet here.', 'fivehundred'); ?></p>
							<textarea name="ga" id="ga"><?php echo (isset($ga) ? $ga : ''); ?></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<h2><?php _e('Social Settings', 'fivehundred'); ?></h2>
							<p><?php _e('Check to show on theme home page, uncheck to hide.', 'fivehundred'); ?></p>
							<label for="twitter-button"><?php _e('Twitter', 'fivehundred'); ?></label>
							<input type="checkbox" id="twitter-button" name="twitter-button" value="1" <?php echo ($twitter == 1 ? 'checked="checked"' : ''); ?>/><br/>
							<label for="twitter-via"><?php _e('Twitter Username', 'fivehundred'); ?></label>
							<input type="text" id="twitter-via" name="twitter-via" value="<?php echo $twitter_via; ?>"/><br/>
							<label for="fb-button"><?php _e('Facebook', 'fivehundred'); ?></label>
							<input type="checkbox" id="fb-button" name="fb-button" value="1" <?php echo ($fb == 1 ? 'checked="checked"' : ''); ?>/><br/>
							<label for="twitter-via"><?php _e('Facebook Username', 'fivehundred'); ?></label>
							<input type="text" id="fb-via" name="fb-via" value="<?php echo $fbname; ?>"/><br/>
							<label for="g-button"><?php _e('Google+', 'fivehundred'); ?></label>
							<input type="checkbox" id="g-button" name="g-button" value="1" <?php echo ($google == 1 ? 'checked="checked"' : ''); ?>/><br/>
							<label for="twitter-via"><?php _e('Google+ User Number', 'fivehundred'); ?></label>
							<input type="text" id="g-via" name="g-via" value="<?php echo $gname; ?>"/><br/>
							<label for="li-button"><?php _e('LinkedIn', 'fivehundred'); ?></label>
							<input type="checkbox" id="li-button" name="li-button" value="1" <?php echo ($li == 1 ? 'checked="checked"' : ''); ?>/>
							<label for="twitter-via"><?php _e('LinkedIn Username', 'fivehundred'); ?></label>
							<input type="text" id="li-via" name="li-via" value="<?php echo $liname; ?>"/><br/>
						</td>
					</tr>
					<?php echo do_action('fivehundred_extra_fields'); ?>
					<tr>
						<td>
							<input type="submit" id="submit-theme-settings" name="submit-theme-settings" class="btn button" value="<?php _e('Save Settings', 'fivehundred'); ?>"/>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
</div>