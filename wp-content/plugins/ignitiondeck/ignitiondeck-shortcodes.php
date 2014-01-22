<?php

add_shortcode('project_faq', 'id_projectFAQ');
add_shortcode('project_updates', 'id_projectUpdates');
if (is_id_licensed()) {
	add_shortcode('project_name', 'id_projectName');
	add_shortcode('project_short_desc', 'id_ShortDesc');
	add_shortcode('project_long_desc', 'id_projectLongDesc');
	add_shortcode('project_percentage_bar', 'id_projectPercentBar');
	add_shortcode('project_goal', 'id_projectGoal');
	add_shortcode('project_users', 'id_projectUsers');
	add_shortcode('project_pledged', 'id_projectPledgedPrice');
	add_shortcode('project_daystogo', 'id_projectdaytogo');
	add_shortcode('project_end', 'id_projectend');
	add_shortcode('project_mini_widget', 'id_projectMiniWidget');
	add_shortcode('project_page_widget', 'id_projectPageWidget');
	add_shortcode('project_grid', 'id_projectGrid');
	add_shortcode('project_image', 'id_projectImage');
	add_shortcode('project_video', 'id_projectVideo');
	add_shortcode('project_page_content_left', 'id_projectPageContent');
	add_shortcode('project_page_content', 'id_projectPageContentFull');
	add_shortcode('project_page_complete', 'id_projectPageComplete');
	add_shortcode('project_purchase_form', 'id_purchaseForm');
}

//**************************************
// functions for shortcodes (project)
//**************************************

function id_projectName($attrs) {
	global $wpdb;
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$deck = new Deck($project_id);
		$project = $deck->the_project();
		$project_name = $project->product_name;
		return '<div class="product-name" style="clear: both;">'.$project_name.'</div>';
	}
	else {
		return null;
	}
}

function id_ShortDesc($attrs) {
	global $wpdb;
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new Deck($project_id);
		$short_desc = $project->short_description();
		return '<div class="product-details" style="clear: both;">'.nl2br(html_entity_decode($short_desc)).'</div>';
	}
	else {
		return null;
	}
}

function id_projectLongDesc($attrs) {
	global $wpdb;
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$post_id = $project->get_project_postid();
		$long_desc = get_post_meta($post_id, 'ign_project_long_description', true);
		return '<div class="product-details" style="clear: both;">'.html_entity_decode($long_desc).'</div>';
	}
	else {
		return null;
	}
}

function id_projectPercentBar($attrs) {
	global $wpdb;
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new Deck($project_id);		
		$percent = $project->percent();
		$progress_bar = '<div class="ignitiondeck"><div class="progress-wrapper" style="clear: both;">
							<div class="progress-percentage">
							'.$percent.'%
							</div>
							<div style="width: '.$percent.'%" class="progress-bar">
							</div>
					 	</div></div>';
	}
	else {
		$progress_bar = null;
	}
	return $progress_bar;
}

function id_projectGoal($attrs){
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$goal = $project->the_goal();
		return '<span class="product-goal" style="clear: both;">'.$goal.'</span>';
	}
	else {
		return null;
	}
}

function id_projectUsers($attrs){
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$orders = $project->get_project_orders();
		return '<span class="product-Users" style="clear: both;">'.$orders.'</span>';
	}
	else {
		return null;
	}
}

function id_projectPledgedPrice($attrs){
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$cCode = $project->currency_code();
		$raised = number_format($project->get_project_raised(), 2, '.', ',');
		return '<span class="product_pledged" style="clear: both;">'.$cCode.$raised.'</span>';
	}
	else {
		return null;
	}
}

function id_projectdaytogo($attrs) {
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$days_left = $project->days_left();
		return '<span class="product_daystogo" style="clear: both;">'.$days_left.'</span>';	 
	}
	else {
		return null;
	}
}

function id_projectend($attrs) {
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$end_date = $project->end_date();
		return '<span class="project_end" style="clear: both;">'.$end_date.'</span>';
	}
	else {
		return null;
	}
}

function id_projectImage($attrs) {
	global $wpdb;
	if (isset($attrs['product']) && isset($attrs['image'])) {
		$project_id = $attrs['product'];
		$image_no = $attrs['image'];
		$project = new ID_Project($project_id);
		$post_id = $project->get_project_postid();
		return '<div class="product-image-container" style="clear: both;"><img src="'.get_post_meta($post_id, "ign_product_image".$image_no, true).'" /></div>';
	}
	else {
		return null;
	}
	
}

function id_projectVideo($attrs) {
	global $wpdb;
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$post_id = $project->get_project_postid();
		$video = get_post_meta($post_id, 'ign_product_video', true);
		return '<div class="product-video-container" style="clear: both;">'.html_entity_decode(stripslashes($video)).'</div>';
	}
	else {
		return null;
	}
}

function id_projectFAQ($attrs) {
	ob_start();
	global $wpdb;
	require 'languages/text_variables.php';		#change-languageVariables_20Jan2012
	
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$the_project = $project->the_project();
		$post_id = $project->get_project_postid();
		$faq_output = html_entity_decode(stripslashes(get_post_meta($post_id, "ign_faqs", true)));
		if (!empty($faq_output)) {
			echo '<div class="ignitiondeck"><h3 class="product-dashed-heading"><span class="sign">+</span>'.$tr_Product_FAQ.'</h3>';
			echo '<div id="prodfaq">';
			echo $faq_output;
			echo '<div>'.do_action("id_faqs", $project_id).'</div>';
			echo '</div></div>';
		}
		else if (has_action('id_faqs')) {
			echo '<div class="ignitiondeck"><h3 class="product-dashed-heading"><span class="sign">+</span>'.$tr_Product_FAQ.'</h3>';
			echo '<div id="prodfaq">';
			echo do_action("id_faqs", $attrs);
			echo '</div></div>';
		}
		$output = ob_get_contents();
		ob_end_clean();
	}
	else {
		$output = null;
	}
	return $output;
}

function id_projectUpdates($attrs) {
	ob_start();
	global $wpdb;
	require 'languages/text_variables.php';		#change-languageVariables_20Jan2012
	
	if (isset($attrs['product'])) {
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$the_project = $project->the_project();
		$post_id = $project->get_project_postid();
		$update_data = html_entity_decode(stripslashes(get_post_meta($post_id, "ign_updates", true)));
		if (!empty($update_data)) {
			echo '<div class="ignitiondeck"><h3 class="product-dashed-heading1"><span class="sign">+</span>'.$tr_Updates.'</h3>';
			echo '<div id="produpdates">';
			echo $update_data;
			echo '<div>'.do_action("id_updates", $attrs).'</div>';
			echo '</div></div>';
		}
		else if (has_action('id_updates')) {
			echo '<div class="ignitiondeck"><h3 class="product-dashed-heading1"><span class="sign">+</span>'.$tr_Updates.'</h3>';
			echo '<div id="produpdates">';
			echo do_action("id_updates", $attrs);
			echo '</div></div>';
		}
		$output = ob_get_contents();
		ob_end_clean();
	}
	else {
		$output = null;
	}
	return $output;
}

function id_projectPageWidget($attrs) {
	if (isset($attrs['product'])) {
		ob_start();
		require 'languages/text_variables.php';
		$project_id = $attrs['product'];
		$deck = new Deck($project_id);
		$custom = false;
		if (isset($attrs['deck'])) {
			$deck_id = $attrs['deck'];
			$settings = Deck::get_deck_attrs($deck_id);
			if (!empty($settings)) {
				$attrs = unserialize($settings->attributes);
				$custom = true;
			}
		}
		$the_deck = $deck->the_deck();
		include 'templates/_igWidget.php';
		$output = ob_get_contents();
		ob_end_clean();
		return apply_filters('id_widget', $output);
	}
	else {
		return null;
	}
}

/*
 *	Desc: Mini widget shortcode function
 */
function id_projectMiniWidget($attrs) {
	if (isset($attrs['product'])) {
		ob_start();
		require 'languages/text_variables.php';
		$project_id = $attrs['product'];
		$deck = new Deck($project_id);
		$custom = false;
		if (isset($attrs['deck'])) {
			$deck_id = $attrs['deck'];
			$settings = Deck::get_deck_attrs($deck_id);
			if (!empty($settings)) {
				$attrs = unserialize($settings->attributes);
				$custom = true;
			}
		}
		$mini_deck = $deck->mini_deck();
		include 'templates/_miniWidget.php';
		$output = ob_get_contents();
		ob_end_clean();
		return apply_filters('id_mini_widget', $output);
	}
	else {
		return null;
	}

}

/*
This floats left by default
*/
function id_projectPageContent($attrs) {
	if (isset($attrs['product'])) {
		ob_start();
		require 'languages/text_variables.php';
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$the_project = $project->the_project();
		$post_id = $project->get_project_postid();
		$settings = getSettings();
		$project_long_desc = html_entity_decode(get_post_meta( $post_id, "ign_project_long_description", true ));
		$float = 1;
		include 'templates/_projectContent.php';
		$content = '<div class="ignitiondeck"><div class="product-left-content">'.
		$content .= ob_get_contents();
		$content .= '</div></div>';
		ob_end_clean();
	}
	else {
		$content = '';
	}
	
	return apply_filters('id_project_content', $content, $project_id);
}





// This is a full width template
function id_projectPageContentFull($attrs) {
	if (isset($attrs['product'])) {
		ob_start();
		require 'languages/text_variables.php';
		$project_id = $attrs['product'];
		$project = new ID_Project($project_id);
		$the_project = $project->the_project();
		$post_id = $project->get_project_postid();
		$settings = getSettings();
		$project_long_desc = html_entity_decode(get_post_meta( $post_id, "ign_project_long_description", true ));
		$float = 1;
		include 'templates/_projectContent.php';
		$content = ob_get_contents();
		ob_end_clean();
	}
	else {
		$content = '';
	}
	
	return apply_filters('id_project_content', $content, $project_id);
}




function id_projectPageComplete($attrs) {
	if (isset($attrs['product'])) {
		ob_start();
		require 'languages/text_variables.php';
		$project_id = $attrs['product'];
		$deck = new Deck($project_id);
		$custom = false;
		if (isset($attrs['deck'])) {
			$deck_id = $attrs['deck'];
			$settings = Deck::get_deck_attrs($deck_id);
			if (!empty($settings)) {
				$attrs = unserialize($settings->attributes);
				$custom = true;
			}
		}
		$the_deck = $deck->the_deck();
		$post_id = $deck->get_project_postid();
		$settings = getSettings();
		$project_long_desc = html_entity_decode(get_post_meta( $post_id, "ign_project_long_description", true ));
		$float = 1;
		include 'templates/_projectContent.php';
		include 'templates/_igWidget.php';
		echo '<div style="clear: both;"></div>';
		$content = ob_get_contents();
		ob_end_clean();
		return apply_filters('id_project_complete', $content, $project_id);
	}
}

function id_purchaseForm($attrs) {
	ob_start();
	require 'languages/text_variables.php';
	if (isset($attrs['product'])) {
		$project_id = absint($attrs['product']);
	}
	if (isset($_GET['prodid'])) {
		$project_id = absint($_GET['prodid']);
		
	}
	if (isset($_GET['level'])) {
		$level = absint($_GET['level']);
	}

	if (isset($project_id)) {
		$form = new ID_Purchase_Form($project_id);
		$purchase_form = $form->id_purchase_form();
		$post_id = $purchase_form->post_id;
	}
	else {
		$project_id = null;
	}

	if (!isset($_SESSION['paypal_errors_content'])) {
		$_SESSION['paypal_errors_content'] = "";
	}
	include 'templates/_purchaseForm.php';
	$purchase_form = ob_get_contents();
	ob_end_clean();
	$purchase_form = apply_filters('id_purchase_form', $purchase_form, $project_id);
	return $purchase_form;
}

function id_projectGrid($attrs) {
	ob_start();
	if (isset($attrs['columns'])) {
		$wide = $attrs['columns'];
	}
	else {
		$wide = 3;
	}
	$width = 90 / $wide;
	$margin = 10 / ($wide-1);
	if (isset($attrs['max'])) {
		$max = $attrs['max'];
	}
	else {
		$max = null;
	}
	if (isset($attrs['category'])) {
		$category = $attrs['category'];
		$args = array(
			'post_type' => 'ignition_product',
			'tax_query' => array(
				array(
					'taxonomy' => 'project_category',
					'field' => 'id',
					'terms' => $category
				)
			)
		);
		if (isset($max)) {
			$args['posts_per_page'] = $max;
		}
		$posts = get_posts($args);
		$project_ids = array();
		foreach ($posts as $post) {
			$post_id = $post->ID;
			$project_id = get_post_meta($post_id, 'ign_project_id', true);
			$project_ids[] = $project_id;
		}
	}
	require 'languages/text_variables.php';
	$custom = false;
	if (isset($attrs['deck'])) {
		$deck_id = $attrs['deck'];
		$settings = Deck::get_deck_attrs($deck_id);
		if (!empty($settings)) {
			$attrs = unserialize($settings->attributes);
			$custom = true;
		}
	}
	$projects = ID_Project::get_all_projects();
	echo '<div class="ignitiondeck"><div class="grid_wrap" data-wide="'.$wide.'">';
	$i = 1;
	if (!empty($projects)) {
		foreach ($projects as $project) {
			$pass = false;
			$project_id = $project->id;
			if (isset($project_ids)) {
				if (in_array($project_id, $project_ids)) {
					$pass = true;
				}
			}
			else if ($i <= $max) {
				$pass = true;
			}
			if ($pass) {
				$deck = new Deck($project_id);
				$mini_deck = $deck->mini_deck();
				$post_id = $deck->get_project_postid();
				$status = get_post_status($post_id);
				if (strtoupper($status) == 'PUBLISH') {
					$settings = getSettings();
					echo '<div class="grid_item" style="float: left; margin: 0 '.$margin.'% '.$margin.'% 0; width: '.$width.'%;">';
					include 'templates/_miniWidget.php';
					echo '</div>';
					$i++;
				}
			}
		}
	}
	echo '</div></div>';
	echo '<br style="clear: both"/>';
	$grid = ob_get_contents();
	ob_end_clean();
	return $grid;
}
?>