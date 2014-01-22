<div class="id-embedwidget">
	<div class="id-product-infobox">
		<div class="product-wrapper">
			<?php
			$post_image = getimagepostbyProductID($project_id);
			//echo $post_image;
			?>
			<a href="<?php echo $product_url; ?>" target="_blank"><img src="<?php echo $post_image; ?>"/></a>
			<div class="pledge">
				<h2 class="id-product-title"><?php echo stripslashes($product->product_name);?></h2>
				<div class="id-product-description"><?php echo $project_desc; ?></div>
				<div class="progress-wrapper">
					<div class="id-progress-raised"> <?php echo $cCode; ?><?php echo number_format(getTotalProductFund($project_id));?> RAISED </div>
					<div class="progress-bar" style="width: <?php echo $rating_per; ?>%"></div>
				</div><!-- end progress wrapper --> 
			</div><!-- end pledge -->
		</div><!-- end product-wrapper -->		
		<div class="id-product-proposed-end"><?php echo $tr_Only; ?> <?php echo (($days_left != "" || $days_left != 0) ? $days_left : '0'); ?> <?php echo $tr_Days_To_Go; ?>.</div>
		<div class="learn-more-btn"><a href="<?php echo $product_url; ?>" class="main-btn" target="_blank"><?php echo $tr_Learn_More ?></a></div>
		<?php if ($logo_on == true) { ?>
		<div id="poweredbyID">
			<span>
				<a href="http://www.ignitiondeck.com" title="<?php echo $tr_Crowdfunding;?>">powered by IgnitionDeck</a>
			</span>
		</div>
		<?php } ?>
	</div><!-- end id product infobox -->
</div><!-- end id widget id embed -->