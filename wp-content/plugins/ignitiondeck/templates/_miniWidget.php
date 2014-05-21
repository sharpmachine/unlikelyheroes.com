<div class="ignitiondeck id-widget id-mini" data-projectid="<?php echo (isset($project_id)? $project_id : ''); ?>">
	<div class="id-product-infobox">
		<div class="product-wrapper">
			<?php echo do_action('id_widget_before', $project_id); ?>
			<?php echo do_action('id_mini_widget_before', $project_id); ?>
			<div class="pledge">
				<?php $mini_image = get_post_meta($mini_deck->post_id, "ign_product_image1", true); ?>
				<?php  if (!$custom || ($custom && isset($attrs['project_title']))) { ?>
					<h2 class="id-product-title"><a href="<?php echo getProjectURLfromType($project_id); ?>"><?php echo stripslashes($mini_deck->project->product_name); ?></a></h2>
				<?php } ?>
				<?php  if (!$custom || ($custom && isset($attrs['project_image']))) { ?>
					<?php if (!empty($mini_image)) { ?>
						<div class="img_cur"><img src="<?php echo $mini_image; ?>" /></div>
					<?php } ?>
				<?php } ?>
				<?php  if (!$custom || ($custom && isset($attrs['project_bar']))) { ?>
					<div class="progress-wrapper">
						<div class="progress-percentage"><?php echo number_format(apply_filters('id_percentage_raised', $mini_deck->rating_per, $mini_deck->post_id, $mini_deck->project->goal)); ?>% </div>					
						<!-- end progress-percentage -->
						<div class="progress-bar" style="width: <?php echo apply_filters('id_percentage_raised', $mini_deck->rating_per, $mini_deck->post_id, $mini_deck->project->goal); ?>%"> 
							<!----> 
						</div>
						<!-- end progress bar --> 
					</div>
					<!-- end progress wrapper --> 
				<?php } ?>
			</div>
			
			<!-- end pledge -->
			
			<div class="clearing"><!----></div>
			<div class="id-product-funding"></div>
			<?php  if (!$custom || ($custom && isset($attrs['project_pledged']))) { ?>
				<div class="id-progress-raised"> <?php echo $mini_deck->cCode; ?><?php echo number_format(apply_filters('id_funds_raised', getTotalProductFund($project_id), $mini_deck->post_id)); ?> </div>
			<?php } ?>
			<?php  if (!$custom || ($custom && isset($attrs['project_goal']))) { ?>
				<div class="id-product-funding"><?php echo $tr_Pledged_Of.' '.$mini_deck->cCode.number_format($mini_deck->project->goal, 0, '.', ',').' '.$tr_Goal ;?> </div>
			<?php } ?>
			<?php  if (!$custom || ($custom && isset($attrs['project_pledgers']))) { ?>
				<div class="id-product-total"><?php echo number_format(apply_filters('id_number_pledges', (($mini_deck->p_count->p_number !== "" || $mini_deck->p_count->p_number !== 0) ? $mini_deck->p_count->p_number : '0'), $mini_deck->post_id));?></div>
				<div class="id-product-pledges"><?php echo $tr_Pledgers; ?></div>
			<?php } ?>
			<?php  if (!$custom || ($custom && isset($attrs['days_left']))) { ?>
				<?php if (isset($mini_deck->days_left) && $mini_deck->days_left > 0) { ?>
					<div class="id-product-days"><?php echo (($mini_deck->days_left !== "" || $mini_deck->days_left !== 0) ? $mini_deck->days_left : '0'); ?></div>
					<div class="id-product-days-to-go"><?php echo $tr_Days_To_Go; ?></div>
				<?php } ?>
			<?php } ?>
		</div>
		<?php  if (!$custom || ($custom && isset($attrs['project_end']))) { ?>
			<!-- end product-wrapper -->	
			<?php if ($mini_deck->item_fund_end !== '') { ?>	
			<div class="id-product-proposed-end"><?php echo $tr_funded_on; ?>
				<div class="id-widget-date">
					<div class="id-widget-month"><?php echo $mini_deck->month; ?></div>
					<div class="id-widget-day"><?php echo $mini_deck->day; ?></div>
					<div class="id-widget-year"><?php echo $mini_deck->year; ?></div>
				</div>
			</div>
			<?php } ?>
		<?php } ?>
		<div class="separator">&nbsp;</div>
		<?php  if (!$custom || ($custom && isset($attrs['project_button']))) { ?>
			<div class="btn-container"><a href="<?php echo getProjectURLfromType($project_id); ?>" class="learn-more-button"><?php echo $tr_Learn_More ?></a></div>
		<?php } ?>
		<?php
		if ($mini_deck->settings->id_widget_logo_on == 1) {
			?>
			<div class="poweredbyID"><span><a href="<?php echo $mini_deck->affiliate_link; ?>" title="<?php echo $tr_Crowdfunding;?>"><?php echo $tr_Powered; ?></a></span></div>
			<?php
		}
		?>
		<?php echo do_action('id_widget_after', $project_id); ?>
		<?php echo do_action('id_mini_widget_after', $project_id); ?>
	</div>
	<!-- end product-infobox -->
</div>
<!-- end mini-widget -->