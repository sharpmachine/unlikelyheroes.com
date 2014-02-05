<?php
global $post;
$id = $post->ID;
$content = the_project_content($id);
$project_id = get_post_meta($id, 'ign_project_id', true);
?>

<div class="jumbotron">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<h1>
					<span>Support Campaign</span>
				</h1>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-md-10 col-md-offset-1">
			<div id="site-description">
				<h1 class="text-center"><?php echo $content->name; ?></h1>
			</div>
			<div class="entry-content">
				<?php
				echo apply_filters('the_content', do_shortcode('[project_purchase_form]'));
				?>
			</div>
		</div>
	</div>
</div>
