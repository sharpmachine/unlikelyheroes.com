<div class="wrap">
	<div class="icon32" id="icon-options-general"></div><h2><?php _e('MemberDeck IgnitionDeck Bridge', 'mdid'); ?></h2>
	<div class="postbox-container" style="width:100%;">
		<div class="metabox-holder">
			<div class="meta-box-sortables" style="min-height:0;">
				<div class="postbox">
					<h3 class="hndle"><span><?php _e('Level Settings', 'mdid'); ?></span></h3>
					<div class="inside">
						<form method="POST" action="" id="idmember-settings" name="idmember-settings">
							<div class="form-input">
								<label for="edit-level"><?php _e('Edit Level or Get Shortcode', 'mdid'); ?></label><br/>
								<select id="edit-level" name="edit-level">
									<option><?php _e('Choose Level', 'mdid'); ?></option>
								</select>
							</div>
							<div>
								<p><?php _e('Select IgnitionDeck levels to assign to MemberDeck level', 'mdid'); ?> &nbsp; <a href="#" id="master-select-all"><?php _e('Select All', 'mdid'); ?></a> &nbsp; <a href="#" id="master-clear-all" class="" style="color:#bc0b0b;"><?php _e('Clear All', 'mdid'); ?></a></p>
							</div>
							<div>
								<?php foreach ($projects as $project) {
									$this_project = new ID_Project($project->id);
									$the_project = $this_project->the_project();
									$level_count = $this_project->level_count();
									$post_id = $this_project->get_project_postid();
									$cCode = $this_project->currency_code();
									$active = get_post_meta($post_id, 'mdid_project_activate', true);
								?>
								<?php if (isset($active) && $active == 'yes') { ?>
									<div class="mdid-project-grid" data-projectid="<?php echo $project->id; ?>">
										<ul>
											<li style="display: inline; list-style: none;"><strong class="project-title"><?php echo strip_tags(stripslashes($project->product_name)); ?></strong></li>
											<li><a href="#" class="select-all"><?php _e('Select All', 'mdid'); ?></a> &nbsp; <a href="#" class="clear-all" class="" style="color:#bc0b0b;"><?php _e('Clear', 'mdid'); ?></a></li>
											<li>
												<ul>
													<?php for ($i = 1; $i <= $level_count; $i++) { 
														if ($i == 1) {
															$level_title = stripslashes(strip_tags(html_entity_decode($the_project->ign_product_title)));
															$level_price = $the_project->product_price;
														}
														else {
															$level_title = stripslashes(strip_tags(html_entity_decode(get_post_meta($post_id, 'ign_product_level_'.$i.'_title', true))));
															$level_price = get_post_meta($post_id, 'ign_product_level_'.$i.'_price', true);
														}
														$is_level_available = is_level_available($project->id, $i);
														$owner = mdid_get_owner($project->id, $i);
													?>
													<li><input type="checkbox" id="select-<?php echo $project->id; ?>-<?php echo $i; ?>" class="level-select select-<?php echo $project->id; ?>" data-level="<?php echo $i; ?>" data-owner="<?php echo $owner; ?>" <?php echo ($is_level_available ? '' : 'disabled="disabled"'); ?>/><label for="select-<?php echo $project->id; ?>-<?php echo $i; ?>"><?php echo $level_title.' '.$cCode.$level_price; ?></label></li>
													<?php } ?>
												</ul>
											</li>
										</ul>
									</div>
								<?php } ?>
								<?php } ?>
								<br style="clear: both;">
								<button id="save-assignments" class="button button-primary button-large"><?php _e('Save Assignments', 'mdid'); ?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>