<?php
global $post;
$id = $post->ID;
$content = the_project_content($id);
$project_id = get_post_meta($id, 'ign_project_id', true);
?>

<div id="ign-product-levels" data-projectid="<?php echo $project_id; ?>">
	<?php get_template_part('loop', 'levels'); ?>
</div>

<?php
$settings = getSettings();
?>
<?php if ($settings->id_widget_logo_on == 1) {
	echo '<div id="poweredbyID"><span><a href="http://www.ignitiondeck.com" title="Crowdfunding Wordpress Theme by IgnitionDeck"></a></span></div>';
} ?>

<hr class="visible-sm visible-xs">