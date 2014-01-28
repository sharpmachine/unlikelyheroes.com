<?php
global $post;
$id = $post->ID;
$content = the_project_content($id);
$project_id = get_post_meta($id, 'ign_project_id', true);
?>
<div id="site-description">
	<h1><?php echo $content->name; ?> </h1>
	<h2><?php echo $content->short_description; ?></h2> 
</div>
<?php get_template_part( 'project', 'hDeck' ); ?>
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
		<?php comments_template('/comments.php', true); ?>
	</div>
	<?php get_template_part( 'project', 'sidebar' ); ?>
	<div class="clear"></div>
</div>