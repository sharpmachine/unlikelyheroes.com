<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php get_template_part('head'); ?>

<body <?php body_class(); ?> id="fivehundred">
	<div id="wrapper" class="hfeed">
		<header id="header">
			<div class="headerwrapper">
				<?php get_template_part('branding'); ?>
				<?php get_template_part('nav', 'above'); ?>
			</div>
		</header>
	<?php if (isset($post) && $post->post_type == 'post' && is_home()) { ?>
		<div id="containerwrapper" class="<?php echo (isset($post) ? $post->post_type : ''); ?> containerwrapper-home">
	<?php } else { ?>
	<div id="containerwrapper" class="<?php echo (isset($post) ? $post->post_type : ''); ?> containerwrapper">
	<?php } ?>