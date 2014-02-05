<?php
$options = get_option('fivehundred_theme_settings');
if (isset($options['home'])) {
	$project_id = $options['home'];
	$id = getPostbyProductID($project_id);
	$content = the_project_content($id);
}
?>
<div id="site-description">
	<h1><?php echo $content->name; ?> </h1>
	<h2><?php echo $content->short_description; ?></h2> 
</div>
<?php get_template_part( 'project', 'hDeck-home' ); ?>
<div id="ign-project-content" class="ign-project-content">
	<div class="entry-content">
		<?php do_action('id_before_content_description', $project_id, $id); ?>
		<div class="ign-content-long">
			<?php echo apply_filters('fivehundred_long_description', $content->long_description); ?>
		</div>
		<div id="updateslink">
			<?php echo apply_filters('fivehundred_updates', do_shortcode( '[project_updates product="'.$project_id.'"]')); ?>
		</div>
					
		<div id="faqlink">
			<?php echo apply_filters('fivehundred_faq', do_shortcode( '[project_faq product="'.$project_id.'"]')); ?>
		</div>
		<?php if (dynamic_sidebar('home-content-widget-area')) : ?>
		<?php endif; ?>
	</div>
	<?php get_template_part( 'project', 'sidebar-home' ); ?>
	<div class="clear"></div>
</div>