<?php 
$attachment_id = get_field('campaign_photo');
$size = "thumbnail-box";
$image = wp_get_attachment_image_src( $attachment_id, $size );
?>

<div class="col-sm-6 col-md-3 box-one campaign-summary grouprev-campaign-summary">
	<div class="box-one-inner">
		<a href="<?php the_field('grouprev_link'); ?>" target="_blank">
			<div class="box-one-img" style="background: url(<?php echo $image[0]; ?>) no-repeat center center; background-size: cover;">
				<div class="box-one-img-bg"></div>
				<!-- <img src="<?php echo $image[0]; ?>" class="img-responsive" alt="<?php the_title(); ?>" /> -->
			</div>
			<h3><?php the_short_title(40); ?></h3>
			<div class="money-raised">
				<span>$<?php the_field('campaign_goal'); ?></span>
				Goal
			</div>
			<p><?php the_field('description'); ?></p>
		</a>
	</div>
</div>