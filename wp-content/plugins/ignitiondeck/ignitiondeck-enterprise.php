<?php

add_action('id_before_content_description', 'ide_creator_profile', 5, 2);

function ide_creator_profile($project_id, $post_id) {
	$company_name = get_post_meta($post_id, 'ign_company_name', true);
	$company_logo = get_post_meta($post_id, 'ign_company_logo', true);
	$company_location = get_post_meta($post_id, 'ign_company_location', true);
	$company_url = get_post_meta($post_id, 'ign_company_url', true);
	$company_fb = get_post_meta($post_id, 'ign_company_fb', true);
	$company_twitter = get_post_meta($post_id, 'ign_company_twitter', true);
	if (!empty($company_name)) {
		include_once ID_PATH.'templates/_projectCreatorProfile.php';
	}
}

add_shortcode('project_submission_form', 'id_submissionForm');


function id_submissionForm($post_id = null) {
	/*
	Tasks:
	1. Deal with number formatting
	2. Deal with apostrophes and html
	3. Deal with empty vars
	*/
	ob_start();
	$vars = null;
	if (empty($post_id)) {
		if (isset($_GET['edit_project'])) {
			$post_id = $_GET['edit_project'];
		}
	}
	if (!empty($post_id) && $post_id > 0) {
		$post = get_post($post_id);
		$status = $post->post_status;
		$company_name = get_post_meta($post_id, 'ign_company_name', true);
		$company_logo = get_post_meta($post_id, 'ign_company_logo', true);
		$company_location = get_post_meta($post_id, 'ign_company_location', true);
		$company_url = get_post_meta($post_id, 'ign_company_url', true);
		$company_fb = get_post_meta($post_id, 'ign_company_fb', true);
		$company_twitter = get_post_meta($post_id, 'ign_company_twitter', true);
		$project_name = get_post_meta($post_id, 'ign_product_name', true);
		$project_start = get_post_meta($post_id, 'ign_start_date', true);
		$project_end = get_post_meta($post_id, 'ign_fund_end', true);
		$project_goal = get_post_meta($post_id, 'ign_fund_goal', true);
		$project_ship_date = get_post_meta($post_id, 'ign_proposed_ship_date', true);
		$project_short_description = get_post_meta($post_id, 'ign_project_description', true);
		$project_long_description = get_post_meta($post_id, 'ign_project_long_description', true);
		$project_faq = get_post_meta($post_id, 'ign_faqs', true);
		$project_updates = get_post_meta($post_id, 'ign_updates', true);
		$project_video = get_post_meta($post_id, 'ign_product_video', true);
		$project_hero = get_post_meta($post_id, 'ign_product_image1', true);
		$project_image2 = get_post_meta($post_id, 'ign_product_image2', true);
		$project_image3 = get_post_meta($post_id, 'ign_product_image3', true);
		$project_image4 = get_post_meta($post_id, 'ign_product_image4', true);
		$project_id = get_post_meta($post_id, 'ign_project_id', true);
		$project_type = get_post_meta($post_id, 'ign_project_type', true);
		$end_type = get_post_meta($post_id, 'ign_end_type', true);
		// levels
		$project_levels = get_post_meta($post_id, 'ign_product_level_count', true);

		$levels = array();
		$levels[0] = array();
		$levels[0]['title'] = get_post_meta($post_id, 'ign_product_title', true); /* level 1 */
		$levels[0]['price'] = get_post_meta($post_id, 'ign_product_price', true); /* level 1 */
		$levels[0]['short'] = get_post_meta($post_id, 'ign_product_details', true); /* level 1 */
		$levels[0]['long'] = get_post_meta($post_id, 'ign_product_details', true); /* level 1 */
		$levels[0]['limit'] = get_post_meta($post_id, 'ign_product_limit', true); /* level 1 */
		
		for ($i = 1; $i <= $project_levels - 1; $i++) {
			$levels[$i] = array();
			$levels[$i]['title'] = get_post_meta($post_id, 'ign_product_level_'.($i+1).'_title', true);
			$levels[$i]['price'] = get_post_meta($post_id, 'ign_product_level_'.($i+1).'_price', true);
			$levels[$i]['short'] = get_post_meta($post_id, 'ign_product_level_'.($i+1).'short_desc', true);
			$levels[$i]['long'] = get_post_meta($post_id, 'ign_product_level_'.($i+1).'_desc', true);
			$levels[$i]['limit'] = get_post_meta($post_id, 'ign_product_level_'.($i+1).'_limit', true);
		}

		$vars = array('post_id' => $post_id,
			'company_name' => $company_name,
			'company_logo' => $company_logo,
			'company_location' => $company_location,
			'company_url' => $company_url,
			'company_fb' => $company_fb,
			'company_twitter' => $company_twitter,
			'project_name' => $project_name,
			'project_start' => $project_start,
			'project_end' => $project_end,
			'project_goal' => $project_goal,
			'project_ship_date' => $project_ship_date,
			'project_short_description' => $project_short_description,
			'project_long_description' => $project_long_description,
			'project_faq' => $project_faq,
			'project_updates' => $project_updates,
			'project_video' => $project_video,
			'project_hero' => $project_hero,
			'project_image2' => $project_image2,
			'project_image3' => $project_image3,
			'project_image4' => $project_image4,
			'project_id' => $project_id,
			'project_type' => $project_type,
			'end_type' => $end_type,
			'project_levels' => $project_levels,
			'levels' => $levels,
			'status' => $status);
	}
	if (isset($_POST['project_fesubmit'])) {
		// prep for file inputs
		$wp_upload_dir = wp_upload_dir();
		if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
		
		// Create team variables
		if (isset($_POST['company_name'])) {
			$company_name = esc_attr($_POST['company_name']);
		}

		if (isset($_FILES['company_logo']) && $_FILES['company_logo']['size'] > 0) {
			$company_logo = wp_handle_upload($_FILES['company_logo'], array('test_form' => false));
			$logo_filetype = wp_check_filetype(basename($company_logo['file']), null);
			$logo_attachment = array(
		    	'guid' => $wp_upload_dir['url'] . '/' . basename( $company_logo['file'] ), 
		    	'post_mime_type' => $logo_filetype['type'],
		    	'post_title' => preg_replace('/\.[^.]+$/', '', basename($company_logo['file'])),
		    	'post_content' => '',
		    	'post_status' => 'inherit'
		  	);
		}
		else {
			if (empty($vars['company_logo'])) {
				$project_hero = null;
			}
			else {
				$project_hero = $vars['company_logo'];
			}
		}
		if (isset($_POST['company_location'])) {
			$company_location = esc_attr($_POST['company_location']);
		}
		if (isset($_POST['company_url'])) {
			$company_url = esc_attr($_POST['company_url']);
		}
		if (isset($_POST['company_fb'])) {
			$company_fb = esc_attr($_POST['company_fb']);
		}
		if (isset($_POST['company_twitter'])) {
			$company_twitter = esc_attr($_POST['company_twitter']);
		}
		// Create project variables
		if (isset($_POST['project_name'])) {
			$project_name = esc_attr($_POST['project_name']);
		}
		if (isset($_POST['project_goal'])) {
			$project_goal = esc_attr(str_replace(',', '', $_POST['project_goal']));
		}
		if (isset($_POST['project_start'])) {
			$project_start = esc_attr($_POST['project_start']);
		}
		if (isset($_POST['project_end'])) {
			$project_end = esc_attr($_POST['project_end']);
		}
		if (isset($_POST['project_ship_date'])) {
			$project_ship_date = esc_attr($_POST['project_ship_date']);
		}
		if (isset($_POST['project_fund_type'])) {
			$project_fund_type = esc_attr($_POST['project_fund_type']);
		}
		$project_short_description = esc_attr($_POST['project_short_description']);
		$project_long_description = esc_attr($_POST['project_long_description']);
		$project_faq = esc_attr($_POST['project_faq']);
		if (isset($_POST['project_updates'])) {
			$project_updates = esc_attr($_POST['project_updates']);
		}
		else {
			$project_updates = '';
		}
		$project_video = esc_attr($_POST['project_video']);
		if (isset($_FILES['project_hero']) && $_FILES['project_hero']['size'] > 0) {
			//$project_hero = esc_attr($_POST['project_hero']);
			$project_hero = wp_handle_upload($_FILES['project_hero'], array('test_form' => false));
			$hero_filetype = wp_check_filetype(basename($project_hero['file']), null);
			$hero_attachment = array(
		    	'guid' => $wp_upload_dir['url'] . '/' . basename( $project_hero['file'] ), 
		    	'post_mime_type' => $hero_filetype['type'],
		    	'post_title' => preg_replace('/\.[^.]+$/', '', basename($project_hero['file'])),
		    	'post_content' => '',
		    	'post_status' => 'inherit'
		  	);
		}
		else {
			if (empty($vars['project_hero'])) {
				$project_hero = null;
			}
			else {
				$project_hero = $vars['project_hero'];
			}
		}
		if (isset($_FILES['project_image2']) && $_FILES['project_image2']['size'] > 0) {
			//$project_image2 = esc_attr($_POST['project_image2']);
			$project_image2 = wp_handle_upload($_FILES['project_image2'], array('test_form' => false));
			$image2_filetype = wp_check_filetype(basename($project_image2['file']), null);
			$image2_attachment = array(
		    	'guid' => $wp_upload_dir['url'] . '/' . basename( $project_image2['file'] ), 
		    	'post_mime_type' => $image2_filetype['type'],
		    	'post_title' => preg_replace('/\.[^.]+$/', '', basename($project_image2['file'])),
		    	'post_content' => '',
		    	'post_status' => 'inherit'
		  	);
		}
		else {
			if (empty($vars['project_image2'])) {
				$project_image2 = null;
			}
			else {
				$project_image2 = $vars['project_image2'];
			}
		}
		if (isset($_FILES['project_image3']) && $_FILES['project_image3']['size'] > 0) {
			//$project_image3 = esc_attr($_POST['project_image3']);
			$project_image3 = wp_handle_upload($_FILES['project_image3'], array('test_form' => false));
			$image3_filetype = wp_check_filetype(basename($project_image3['file']), null);
			$image3_attachment = array(
		    	'guid' => $wp_upload_dir['url'] . '/' . basename( $project_image3['file'] ), 
		    	'post_mime_type' => $image3_filetype['type'],
		    	'post_title' => preg_replace('/\.[^.]+$/', '', basename($project_image3['file'])),
		    	'post_content' => '',
		    	'post_status' => 'inherit'
		  	);
		}
		else {
			if (empty($var['project_image3'])) {
				$project_image3 = null;
			}
			else {
				$project_image3 = $vars['project_image3'];
			}
		}
		if (isset($_FILES['project_image4']) && $_FILES['project_image4']['size'] > 0) {
			//$project_image4 = esc_attr($_POST['project_image4']);
			$project_image4 = wp_handle_upload($_FILES['project_image4'], array('test_form' => false));
			$image4_filetype = wp_check_filetype(basename($project_image4['file']), null);
			$image4_attachment = array(
		    	'guid' => $wp_upload_dir['url'] . '/' . basename( $project_image4['file'] ), 
		    	'post_mime_type' => $image4_filetype['type'],
		    	'post_title' => preg_replace('/\.[^.]+$/', '', basename($project_image4['file'])),
		    	'post_content' => '',
		    	'post_status' => 'inherit'
		  	);
		}
		else {
			if (empty($vars['project_image4'])) {
				$project_image4 = null;
			}
			else {
				$project_image4 = $vars['project_image4'];
			}
		}
		//$type = esc_attr($_POST['project_type']);
		$project_type = 'level-based';
		if (isset($_POST['project_end_type'])) {
			$end_type = esc_attr($_POST['project_end_type']);
		}

		if (isset($_POST['project_levels'])) {
			$project_levels = absint($_POST['project_levels']);
			$levels = array();
			for ($i = 0; $i <= $project_levels - 1; $i++) {
				$levels[$i] = array();
				$levels[$i]['title'] = $_POST['project_level_title'][$i];
				$levels[$i]['price'] = floatval(str_replace(',', '', $_POST['project_level_price'][$i]));
				$levels[$i]['short'] = $_POST['level_description'][$i];
				$levels[$i]['long'] = $_POST['level_long_description'][$i];
				$levels[$i]['limit'] = absint($_POST['project_level_limit'][$i]);
			}
		}

		// Create user variables
		if (is_user_logged_in()) {
			global $current_user;
			get_currentuserinfo();
			$user_id = $current_user->ID;
		}

		// Create a New Post
		$args = array('comment_status' => 'closed',
			'post_author' => $user_id,
			'post_title' => $project_name,
			'post_type' => 'ignition_product');
		if (isset($_POST['project_post_id'])) {
			$args['ID'] = absint($_POST['project_post_id']);
			$post = get_post($post_id);
			$status = $post->post_status;
			$args['post_status'] = $status;
		}
		else {
			$args['post_status'] = 'draft';
		}
		$post_id = wp_insert_post($args);
		if (isset($post_id)) {
			if (!empty($company_logo) && empty($vars['company_logo'])) {
				$logo_id = wp_insert_attachment($logo_attachment, $company_logo['file'], $post_id);
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$logo_data = wp_generate_attachment_metadata( $logo_id, $company_logo['file'] );
	  			$metadata = wp_update_attachment_metadata( $logo_id, $logo_data );
			}
			if (!empty($project_hero) && empty($vars['project_hero'])) {
				$hero_id = wp_insert_attachment($hero_attachment, $project_hero['file'], $post_id);
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$hero_data = wp_generate_attachment_metadata( $hero_id, $project_hero['file'] );
	  			$metadata = wp_update_attachment_metadata( $hero_id, $hero_data );
			}
			if (!empty($project_image2) && empty($vars['project_image2'])) {
				$image2_id = wp_insert_attachment($image2_attachment, $project_image2['file'], $post_id);
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$image2_data = wp_generate_attachment_metadata( $image2_id, $project_image2['file'] );
	  			wp_update_attachment_metadata( $image2_id, $image2_data );
			}
			if (!empty($project_image3) && empty($vars['project_image3'])) {
				$image3_id = wp_insert_attachment($image3_attachment, $project_image3['file'], $post_id);
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$image3_data = wp_generate_attachment_metadata( $image3_id, $project_image3['file'] );
	  			wp_update_attachment_metadata( $image3_id, $image3_data );
			}
			if (!empty($project_image4) && empty($vars['project_image4'])) {
				$image4_id = wp_insert_attachment($image4_attachment, $project_image4['file'], $post_id);
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				$image4_data = wp_generate_attachment_metadata( $image4_id, $project_image4['file'] );
	  			wp_update_attachment_metadata( $image4_id, $image4_data );
			}
			// Insert to ign_products
			$proj_args = array('product_name' => $project_name);
			if (isset($levels[0])) {
				$proj_args['ign_product_title'] = $levels[0]['title'];
				$proj_args['ign_product_limit'] = $levels[0]['limit'];
				$proj_args['product_details'] = $levels[0]['short'];
				$proj_args['product_price'] = $levels[0]['price'];
			}
			$proj_args['goal'] = $project_goal;
			$project_id = get_post_meta($post_id, 'ign_project_id', true);
			if (!empty($project_id)) {
				$project = new ID_Project($project_id);
				$project->update_project($proj_args);
			}
			else {
				$project_id = ID_Project::insert_project($proj_args);
			}
			if (isset($project_id)) {
				// Update postmeta
				update_post_meta($post_id, 'ign_company_name', $company_name);
				if (isset($company_logo['url'])) {
					$company_logo = esc_attr($company_logo['url']);
					update_post_meta($post_id, 'ign_company_logo', $company_logo);
				}
				update_post_meta($post_id, 'ign_company_location', $company_location);
				update_post_meta($post_id, 'ign_company_url', $company_url);
				update_post_meta($post_id, 'ign_company_fb', $company_fb);
				update_post_meta($post_id, 'ign_company_twitter', $company_twitter);

				update_post_meta($post_id, 'ign_product_name', $project_name);
				update_post_meta($post_id, 'ign_start_date', $project_start);
				update_post_meta($post_id, 'ign_fund_end', $project_end);
				update_post_meta($post_id, 'ign_fund_goal', $project_goal);
				update_post_meta($post_id, 'ign_proposed_ship_date', $project_ship_date);
				update_post_meta($post_id, 'ign_project_description', $project_short_description);
				update_post_meta($post_id, 'ign_project_long_description', $project_long_description);
				update_post_meta($post_id, 'ign_faqs', $project_faq);
				update_post_meta($post_id, 'ign_updates', $project_updates);
				update_post_meta($post_id, 'ign_product_video', $project_video);
				if (isset($project_hero['url'])) {
					$project_hero = esc_attr($project_hero['url']);
					update_post_meta($post_id, 'ign_product_image1', $project_hero);
				}
				if (isset($project_image2['url'])) {
					$project_image2 = esc_attr($project_image2['url']);
					update_post_meta($post_id, 'ign_product_image2', $project_image2);
				}
				if (isset($project_image3['url'])) {
					$project_image3 = esc_attr($project_image3['url']);
					update_post_meta($post_id, 'ign_product_image3', $project_image3);
				}
				if (isset($project_image3['url'])) {
					$project_image4 = esc_attr($project_image4['url']);
					update_post_meta($post_id, 'ign_product_image4', $project_image4);
				}
				update_post_meta($post_id, 'ign_project_id', $project_id);
				update_post_meta($post_id, 'ign_project_type', $project_type);
				update_post_meta($post_id, 'ign_end_type', $end_type);
				// levels
				update_post_meta($post_id, 'ign_product_level_count', $project_levels);
				update_post_meta($post_id, 'ign_product_title', $levels[0]['title']); /* level 1 */
				update_post_meta($post_id, 'ign_product_price', $levels[0]['price']); /* level 1 */
				update_post_meta($post_id, 'ign_product_details', $levels[0]['short']); /* level 1 */
				update_post_meta($post_id, 'ign_product_details', $levels[0]['long']); /* level 1 */
				update_post_meta($post_id, 'ign_product_limit', $levels[0]['limit']); /* level 1 */

				for ($i = 2; $i <= $project_levels; $i++) {
					update_post_meta($post_id, 'ign_product_level_'.($i).'_title', $levels[$i-1]['title']);
					update_post_meta($post_id, 'ign_product_level_'.($i).'_price', $levels[$i-1]['price']);
					update_post_meta($post_id, 'ign_product_level_'.($i).'short_desc', $levels[$i-1]['short']);
					update_post_meta($post_id, 'ign_product_level_'.($i).'_desc', $levels[$i-1]['long']);
					update_post_meta($post_id, 'ign_product_level_'.($i).'_limit', $levels[$i-1]['limit']);
				}
				// Attach product to user
				$user_projects = get_user_meta($user_id, 'ide_user_projects', true);
				if (!empty($user_projects)) {
					$user_projects = unserialize($user_projects);
					if (is_array($user_projects)) {
						$user_projects[] = $post_id;
						$user_projects = array_unique($user_projects);
					}
					else {
						$user_projects = array($post_id);
					}
				}
				else {
					$user_projects = array($post_id);
				}
				$new_record = serialize($user_projects);
				update_user_meta($user_id, 'ide_user_projects', $new_record);
				if (!isset($status)) {
					do_action('ide_fes_create', $user_id, $project_id, $post_id, $proj_args, $levels, $project_fund_type);
				}
				$vars = array('post_id' => $post_id,
					'company_name' => $company_name,
					'company_logo' => $company_logo,
					'company_location' => $company_location,
					'company_url' => $company_url,
					'company_fb' => $company_fb,
					'company_twitter' => $company_twitter,
					'project_name' => $project_name,
					'project_start' => $project_start,
					'project_end' => $project_end,
					'project_goal' => $project_goal,
					'project_ship_date' => $project_ship_date,
					'project_short_description' => $project_short_description,
					'project_long_description' => $project_long_description,
					'project_faq' => $project_faq,
					'project_updates' => $project_updates,
					'project_video' => $project_video,
					'project_hero' => $project_hero,
					'project_image2' => $project_image2,
					'project_image3' => $project_image3,
					'project_image4' => $project_image4,
					'project_id' => $project_id,
					'project_type' => $project_type,
					'end_type' => $end_type,
					'project_levels' => $project_levels,
					'levels' => $levels);
				$form = new ID_FES(null, $vars);
				$output = '<div class="ignitiondeck"><div class="id-purchase-form-wrapper">';
				$output .= '<form name="fes" id="fes" class="form-inline action="" method="POST">';
				$output .= $form->display_form();
				$output .= '</form>';
				$output .= '</div></div>';
				echo '<script>location.href="?edit_project='.$post_id.'";</script>';
			}
			else {
				// return some error
			}
		}
		else {
			// return some error
		}
	}

	if (isset($_GET['ide_fes_create']) && $_GET['ide_fes_create'] == 1) {
		$output = '<p class="fes saved">'.$tr_Project_Submitted.'</p>';
	}
	else {
		$form = new ID_FES(null, $vars);
		$output = '<div class="row project-creation-form"><div class="id-fes-form-wrapper col-md-12">';
		$output .= '<form name="fes" id="fes" action="" method="POST" enctype="multipart/form-data">';
		$output .= $form->display_form();
		$output .= '</form>';
		$output .= '</div></div>';
	}
	return apply_filters('ide_fes_display', $output);
}

add_action('init', 'ide_check_create_project');

function ide_check_create_project() {
	if (isset($_GET['create_project']) || isset($_GET['edit_project']) && is_user_logged_in()) {
		add_filter('the_content', 'ide_fes_create');
		add_action('wp_enqueue_scripts', 'enqueue_enterprise_js');
	}
}

function ide_fes_create($content) {
	return do_shortcode('[project_submission_form]');
}


add_action('init', 'ide_check_edit_project');

function ide_check_edit_project() {
	if (isset($_GET['edit_project']) && $_GET['edit_project'] > 0) {
		$project_id = absint($_GET['edit_project']);
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;
		$user_projects = get_user_meta($user_id, 'ide_user_projects', true);
		if (!empty($user_projects)) {
			$user_projects = unserialize($user_projects);
			if (in_array($project_id, $user_projects)) {
				add_filter('the_content', 'ide_edit_project');
			}
		}
	}
}

function ide_edit_project($content) {
	/*$edit_form = new ID_FES();
	$content = '<div class="ignitiondeck"><div class="id-purchase-form-wrapper">';
	$content .= '<form name="fes" id="fes" action="" method="POST">';
	$content .= $edit_form->display_form();
	$content .= '</form>';
	$content .= '</div></div>';*/
	$post_id = absint($_GET['edit_project']);
	$content = id_submissionForm($post_id);
	return $content;
}

function enqueue_enterprise_js() {
	wp_enqueue_script('jquery-ui-datepicker');
	wp_register_style('id-datepicker', plugins_url('ign_metabox/style.css', __FILE__));
	wp_enqueue_style('id-datepicker');
}
?>