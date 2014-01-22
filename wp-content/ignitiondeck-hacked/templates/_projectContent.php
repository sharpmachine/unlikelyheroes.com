<?php echo (isset($float) ? '<div class="id-content-wrap id-complete-projcont" data-projectid="'.(isset($project_id) ? $project_id : '').'">' : '<div class="id-content-wrap" data-projectid="'.(isset($project_id) ? $project_id : '').'">'); ?>
<div class="product-post-output" style="clear: both;">
	<?php echo do_action('id_content_before', $project_id); ?>
	<div class="product-video-container"><?php echo html_entity_decode(stripslashes(get_post_meta($post_id, "ign_product_video", true))); ?></div>
	<?php include ID_PATH.'templates/_socialButtons.php'; ?>
		<div style="clear:both;"></div>
		<?php do_action('id_before_content_description', $project_id, $post_id); ?>
		<div class="long-description"><?php echo nl2br(html_entity_decode($project_long_desc)); ?></div>
		<div class="product-image-container">
			<div>
				<span class="image1"><?php echo ((get_post_meta($post_id, "ign_product_image1", true) != "") ? '<img src="'.get_post_meta($post_id, "ign_product_image1", true).'" />' : ''); ?></span>
				<span class="image2"><?php echo ((get_post_meta($post_id, "ign_product_image2", true) != "") ? '<img src="'.get_post_meta($post_id, "ign_product_image2", true).'" />' : ''); ?></span>
			</div>
			<div>
				<span class="image3"><?php echo ((get_post_meta($post_id, "ign_product_image3", true) != "") ? '<img src="'.get_post_meta($post_id, "ign_product_image3", true).'" />' : ''); ?></span>
				<span class="image4"><?php echo ((get_post_meta($post_id, "ign_product_image4", true) != "") ? '<img src="'.get_post_meta($post_id, "ign_product_image4", true).'" />' : ''); ?></span>
			</div>
		</div>
		<div class="clear"></div>
	<?php
	$product_faq = get_post_meta($post_id, "ign_faqs", true);
	if ($product_faq) {?>
		<h3 class="product-dashed-heading"><span class="sign">+</span><?php echo $tr_Product_FAQ; ?></h3>
		<div id="prodfaq">
			<?php echo html_entity_decode(stripslashes($product_faq)); ?>
			<div><?php do_action('id_faqs', $project_id); ?></div>
		</div><?php
	}
	else if (has_action('id_faqs')){?>
		<h3 class="product-dashed-heading"><span class="sign">+</span><?php echo $tr_Product_FAQ; ?></h3>
		<div id="prodfaq">
		<?php
		do_action('id_faqs', $project_id); ?>
		</div><?php }
	
	$product_updates = get_post_meta($post_id, "ign_updates", true);
	if ($product_updates) { ?>
		<h3 class="product-dashed-heading1"><span class="sign">+</span><?php echo $tr_Updates; ?></h3>
		<div id="produpdates">
			<?php echo html_entity_decode(stripslashes($product_updates)); ?>
			<div><?php do_action('id_updates', $project_id); ?></div>
		</div><?php
	}
	else if (has_action('id_updates')){ ?>
		<h3 class="product-dashed-heading1"><span class="sign">+</span><?php echo $tr_Updates; ?></h3>
		<div id="produpdates">
		<?php
		do_action('id_updates', $project_id); ?>
		</div><?php
	}
	echo do_action('id_content_after', $project_id); ?>
</div>
</div>