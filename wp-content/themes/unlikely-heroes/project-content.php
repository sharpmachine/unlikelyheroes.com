<?php
/*
* Single Campaign page
*/
global $post;
$id = $post->ID;
$content = the_project_content($id);
$project_id = get_post_meta($id, 'ign_project_id', true);
$summary = the_project_summary($id);
?>

<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<span>Support a campaign </span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row main-content single-campaign">
		<div class="col-xs-12 col-md-5 pull-right">
			<?php get_template_part( 'project', 'hDeck' ); ?>
		</div>
		<div class="col-xs-12 col-md-7 pull-left">
			<div class="video-container" style="background: url(<?php echo $summary->image_url; ?>) no-repeat; background-size: cover;">
				<?php echo the_project_video($id); ?>
			</div>
			<?php get_template_part('project', 'social'); ?>
			<br>

			<div id="ign-project-content" class="ign-project-content">
				<div class="entry-content">
					<?php do_action('id_before_content_description', $project_id, $id); ?>
					<br>
					<div class="ign-content-long">
						<?php echo apply_filters('fivehundred_long_description', $content->long_description); ?>
					</div>
					<div id="updateslink">
						<?php echo apply_filters('fivehundred_updates', do_shortcode( '[project_updates product="'.$project_id.'"]')); ?>
					</div>

					<div id="faqlink">
						<?php echo apply_filters('fivehundred_faq', do_shortcode( '[project_faq product="'.$project_id.'"]')); ?>
					</div>
				</div>
			</div>
			<div class="visible-xs">
				<?php get_template_part( 'project', 'hDeck-mobile' ); ?>
			</div>
		</div>
	</div>
</div>