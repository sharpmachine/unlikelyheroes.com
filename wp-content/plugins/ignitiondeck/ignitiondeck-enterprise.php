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
	$wp_upload_dir = wp_upload_dir();
	if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
	if (empty($post_id)) {
		if (isset($_GET['edit_project'])) {
			$post_id = $_GET['edit_project'];
		}
	}
	$memberdeck_gateways = get_option('memberdeck_gateways');
	$project_fund_type = get_option('idc_cf_fund_type');
	if (empty($project_fund_type)) {
		$project_fund_type = 'capture';
	}
	$vars = array('project_fund_type' => $project_fund_type);
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
		$categories = wp_get_post_terms($post_id, 'project_category');
		if (!empty($categories) && is_array($categories)) {
			$project_category = $categories[0]->slug;
		}
		else {
			$project_category = null;
		}
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
		$project_end_type = get_post_meta($post_id, 'ign_end_type', true);
		// levels
		$project_levels = get_post_meta($post_id, 'ign_product_level_count', true);

		$levels = array();
		$levels[0] = array();
		$levels[0]['title'] = get_post_meta($post_id, 'ign_product_title', true); /* level 1 */
		$levels[0]['price'] = get_post_meta($post_id, 'ign_product_price', true); /* level 1 */
		$levels[0]['short'] = get_post_meta($post_id, 'ign_product_short_description', true); /* level 1 */
		$levels[0]['long'] = get_post_meta($post_id, 'ign_product_details', true); /* level 1 */
		$levels[0]['limit'] = get_post_meta($post_id, 'ign_product_limit', true); /* level 1 */
		
		for ($i = 1; $i <= $project_levels - 1; $i++) {
			$levels[$i] = array();
			$levels[$i]['title'] = get_post_meta($post_id, 'ign_product_level_'.($i+1).'_title', true);
			$levels[$i]['price'] = get_post_meta($post_id, 'ign_product_level_'.($i+1).'_price', true);
			$levels[$i]['short'] = get_post_meta($post_id, 'ign_product_level_'.($i+1).'_short_desc', true);
			$levels[$i]['long'] = get_post_meta($post_id, 'ign_product_level_'.($i+1).'_desc', true);
			$levels[$i]['limit'] = get_post_meta($post_id, 'ign_product_level_'.($i+1).'_limit', true);
		}

		$new_vars = array('post_id' => $post_id,
			'company_name' => $company_name,
			'company_logo' => $company_logo,
			'company_location' => $company_location,
			'company_url' => $company_url,
			'company_fb' => $company_fb,
			'company_twitter' => $company_twitter,
			'project_name' => $project_name,
			'project_category' => $project_category,
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
			'project_end_type' => $project_end_type,
			'project_levels' => $project_levels,
			'levels' => $levels,
			'status' => $status);
		$vars = array_merge($new_vars);
	}
	if (isset($_POST['project_fesubmit'])) {
		// prep for file inputs
		// Create team variables
		if (isset($_POST['company_name'])) {
			$company_name = esc_attr($_POST['company_name']);
		}

		if (isset($_FILES['company_logo']) && $_FILES['company_logo']['size'] > 0) {
			$company_logo = wp_handle_upload($_FILES['company_logo'], array('test_form' => false));
			$logo_filetype = wp_check_filetype(basename($company_logo['file']), null);
			if ($logo_filetype['ext'] == strtolower('png') || $logo_filetype['ext'] == strtolower('jpg') || $logo_filetype['ext'] == strtolower('gif') || $logo_filetype['ext'] == strtolower('jpeg')) {
				$logo_attachment = array(
			    	'guid' => $wp_upload_dir['url'] . '/' . basename( $company_logo['file'] ), 
			    	'post_mime_type' => $logo_filetype['type'],
			    	'post_title' => preg_replace('/\.[^.]+$/', '', basename($company_logo['file'])),
			    	'post_content' => '',
			    	'post_status' => 'inherit'
			  	);
			  	$company_logo_posted = true;
			}
			else {
				$company_logo_posted = false;
			}
		}
		else {
			$company_logo_posted = false;
			if (empty($vars['company_logo'])) {
				$company_logo = null;
			}
			else {
				$company_logo = $vars['company_logo'];
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
		if (isset($_POST['project_category'])) {
			$project_category = esc_attr($_POST['project_category']);
		}
		else {
			$project_category = null;
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
		else {
			$project_fund_type = 'capture';
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
			if ($hero_filetype['ext'] == strtolower('png') || $hero_filetype['ext'] == strtolower('jpg') || $hero_filetype['ext'] == strtolower('gif') || $hero_filetype['ext'] == strtolower('jpeg')) {
				$hero_attachment = array(
			    	'guid' => $wp_upload_dir['url'] . '/' . basename( $project_hero['file'] ), 
			    	'post_mime_type' => $hero_filetype['type'],
			    	'post_title' => preg_replace('/\.[^.]+$/', '', basename($project_hero['file'])),
			    	'post_content' => '',
			    	'post_status' => 'inherit'
			  	);
			  	$hero_posted = true;
			}
			else {
				$hero_posted = false;
			}
		}
		else {
			$hero_posted = false;
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
			if ($image2_filetype['ext'] == strtolower('png') || $image2_filetype['ext'] == strtolower('jpg') || $image2_filetype['ext'] == strtolower('gif') || $image2_filetype['ext'] == strtolower('jpeg')) {
				$image2_attachment = array(
			    	'guid' => $wp_upload_dir['url'] . '/' . basename( $project_image2['file'] ), 
			    	'post_mime_type' => $image2_filetype['type'],
			    	'post_title' => preg_replace('/\.[^.]+$/', '', basename($project_image2['file'])),
			    	'post_content' => '',
			    	'post_status' => 'inherit'
			  	);
			  	$project_image2_posted = true;
			}
			else {
				$project_image2_posted = false;
			}
		}
		else {
			$project_image2_posted = false;
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
			if ($image3_filetype['ext'] == strtolower('png') || $image3_filetype['ext'] == strtolower('jpg') || $image3_filetype['ext'] == strtolower('gif') || $image3_filetype['ext'] == strtolower('jpeg')) {
				$image3_attachment = array(
			    	'guid' => $wp_upload_dir['url'] . '/' . basename( $project_image3['file'] ), 
			    	'post_mime_type' => $image3_filetype['type'],
			    	'post_title' => preg_replace('/\.[^.]+$/', '', basename($project_image3['file'])),
			    	'post_content' => '',
			    	'post_status' => 'inherit'
			  	);
			  	$project_image3_posted = true;
			}
			else {
				$project_image3_posted = false;
			}
		}
		else {
			$project_image3_posted = false;
			if (empty($vars['project_image3'])) {
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
			if ($image4_filetype['ext'] == strtolower('png') || $image4_filetype['ext'] == strtolower('jpg') || $image4_filetype['ext'] == strtolower('gif') || $image4_filetype['ext'] == strtolower('jpeg')) {
				$image4_attachment = array(
			    	'guid' => $wp_upload_dir['url'] . '/' . basename( $project_image4['file'] ), 
			    	'post_mime_type' => $image4_filetype['type'],
			    	'post_title' => preg_replace('/\.[^.]+$/', '', basename($project_image4['file'])),
			    	'post_content' => '',
			    	'post_status' => 'inherit'
			  	);
			  	$project_image4_posted = true;
			}
			else {
				$project_image4_posted = false;
			}
		}
		else {
			$project_image4_posted = false;
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
			$project_end_type = esc_attr($_POST['project_end_type']);
		}

		if (isset($_POST['project_levels'])) {
			$project_levels = absint($_POST['project_levels']);
			$saved_levels = array();
			for ($i = 0; $i <= $project_levels - 1; $i++) {
				$saved_levels[$i] = array();
				if (isset($_POST['project_level_title'][$i])) {
					$saved_levels[$i]['title'] = $_POST['project_level_title'][$i];
				}
				else {
					// project is live and title cannot be edited
					$saved_levels[$i]['title'] = $levels[$i]['title'];
				}
				if (isset($_POST['project_level_price'][$i])) {
					$saved_levels[$i]['price'] = floatval(str_replace(',', '', $_POST['project_level_price'][$i]));
				}
				else {
					// project is live and price cannot be edited
					$saved_levels[$i]['price'] = $levels[$i]['price'];
				}
				$saved_levels[$i]['short'] = $_POST['level_description'][$i];
				$saved_levels[$i]['long'] = $_POST['level_long_description'][$i];
				if (isset($_POST['project_level_limit'][$i])) {
					$saved_levels[$i]['limit'] = absint($_POST['project_level_limit'][$i]);
				}
				else {
					// project is live and limit cannot be edited
					$saved_levels[$i]['limit'] = $levels[$i]['limit'];
				}
			}
		}

		// Create user variables
		if (is_user_logged_in()) {
			global $current_user;
			get_currentuserinfo();
			$user_id = $current_user->ID;

			// Create a New Post
			$args = array('comment_status' => 'closed',
				'post_author' => $user_id,
				'post_title' => $project_name,
				'post_type' => 'ignition_product',
				'tax_input' => array('project_category' => $project_category));
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
				if ($company_logo_posted) {
					$logo_id = wp_insert_attachment($logo_attachment, $company_logo['file'], $post_id);
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$logo_data = wp_generate_attachment_metadata( $logo_id, $company_logo['file'] );
		  			$metadata = wp_update_attachment_metadata( $logo_id, $logo_data );
				}
				if ($hero_posted) {
					$hero_id = wp_insert_attachment($hero_attachment, $project_hero['file'], $post_id);
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$hero_data = wp_generate_attachment_metadata( $hero_id, $project_hero['file'] );
		  			$metadata = wp_update_attachment_metadata( $hero_id, $hero_data );
				}
				if ($project_image2_posted) {
					$image2_id = wp_insert_attachment($image2_attachment, $project_image2['file'], $post_id);
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$image2_data = wp_generate_attachment_metadata( $image2_id, $project_image2['file'] );
		  			wp_update_attachment_metadata( $image2_id, $image2_data );
				}
				if ($project_image3_posted) {
					$image3_id = wp_insert_attachment($image3_attachment, $project_image3['file'], $post_id);
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$image3_data = wp_generate_attachment_metadata( $image3_id, $project_image3['file'] );
		  			wp_update_attachment_metadata( $image3_id, $image3_data );
				}
				if ($project_image4_posted) {
					$image4_id = wp_insert_attachment($image4_attachment, $project_image4['file'], $post_id);
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$image4_data = wp_generate_attachment_metadata( $image4_id, $project_image4['file'] );
		  			wp_update_attachment_metadata( $image4_id, $image4_data );
				}
				// Insert to ign_products
				$proj_args = array('product_name' => $project_name);
				if (isset($saved_levels[0])) {
					$proj_args['ign_product_title'] = $saved_levels[0]['title'];
					$proj_args['ign_product_limit'] = $saved_levels[0]['limit'];
					$proj_args['product_details'] = $saved_levels[0]['short'];
					$proj_args['product_price'] = $saved_levels[0]['price'];
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
					if (isset($company_logo['url']) && is_array($company_logo)) {
						$company_logo = esc_attr($company_logo['url']);
						update_post_meta($post_id, 'ign_company_logo', $company_logo);
					}
					else if (!isset($company_logo)) {
						delete_post_meta($post_id, 'ign_company_logo');
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
					if (isset($project_hero['url']) && is_array($project_hero)) {
						$project_hero = esc_attr($project_hero['url']);
						update_post_meta($post_id, 'ign_product_image1', $project_hero);
					}
					else if (!isset($project_hero)) {
						delete_post_meta($post_id, 'ign_product_image1');
					}

					if (isset($project_image2['url']) && is_array($project_image2)) {
						$project_image2 = esc_attr($project_image2['url']);
						update_post_meta($post_id, 'ign_product_image2', $project_image2);
					}
					else if (!isset($project_image2)) {
						delete_post_meta($post_id, 'ign_product_image2');
					}

					if (isset($project_image3['url']) && is_array($project_image3)) {
						$project_image3 = esc_attr($project_image3['url']);
						update_post_meta($post_id, 'ign_product_image3', $project_image3);
					}
					else if (!isset($project_image3)) {
						delete_post_meta($post_id, 'ign_product_image3');
					}

					if (isset($project_image4['url']) && is_array($project_image4)) {
						$project_image4 = esc_attr($project_image4['url']);
						update_post_meta($post_id, 'ign_product_image4', $project_image4);
					}
					else if (!isset($project_image4)) {
						delete_post_meta($post_id, 'ign_product_image4');
					}

					update_post_meta($post_id, 'ign_project_id', $project_id);
					update_post_meta($post_id, 'ign_project_type', $project_type);
					update_post_meta($post_id, 'ign_end_type', $project_end_type);
					// levels
					update_post_meta($post_id, 'ign_product_level_count', $project_levels);
					update_post_meta($post_id, 'ign_product_title', $saved_levels[0]['title']); /* level 1 */
					update_post_meta($post_id, 'ign_product_price', $saved_levels[0]['price']); /* level 1 */
					update_post_meta($post_id, 'ign_product_short_description', $saved_levels[0]['short']); /* level 1 */
					update_post_meta($post_id, 'ign_product_details', $saved_levels[0]['long']); /* level 1 */
					update_post_meta($post_id, 'ign_product_limit', $saved_levels[0]['limit']); /* level 1 */

					for ($i = 2; $i <= $project_levels; $i++) {
						update_post_meta($post_id, 'ign_product_level_'.($i).'_title', $saved_levels[$i-1]['title']);
						update_post_meta($post_id, 'ign_product_level_'.($i).'_price', $saved_levels[$i-1]['price']);
						update_post_meta($post_id, 'ign_product_level_'.($i).'_short_desc', $saved_levels[$i-1]['short']);
						update_post_meta($post_id, 'ign_product_level_'.($i).'_desc', $saved_levels[$i-1]['long']);
						update_post_meta($post_id, 'ign_product_level_'.($i).'_limit', $saved_levels[$i-1]['limit']);
					}
					// Attach product to user
					set_user_projects($post_id, $user_id);
					if (!isset($status)) {
						do_action('ide_fes_create', $user_id, $project_id, $post_id, $proj_args, $saved_levels, $project_fund_type);
					}
					else {
						do_action('ide_fes_update', $user_id, $project_id, $post_id, $proj_args, $saved_levels, $project_fund_type);
					}
					$vars = array('post_id' => $post_id,
						'company_name' => $company_name,
						'company_logo' => $company_logo,
						'company_location' => $company_location,
						'company_url' => $company_url,
						'company_fb' => $company_fb,
						'company_twitter' => $company_twitter,
						'project_name' => $project_name,
						'project_category' => $project_category,
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
						'project_fund_type' => $project_fund_type,
						'project_end_type' => $project_end_type,
						'project_levels' => $project_levels,
						'levels' => $saved_levels);
						echo '<script>location.href="'.apply_filters('ide_fes_submit_redirect', '?edit_project='.$post_id).'";</script>';
				}
				else {
					// return some error
				}
			}
			else {
				// return some error
			}
		}
	}
	/*if (isset($_GET['ide_fes_create']) && $_GET['ide_fes_create'] == 1) {
		$output = '<p class="fes saved">'.$tr_Project_Submitted.'</p>';
	}
	else {
		$form = new ID_FES(null, $vars);
		$output = '<div class="ignitiondeck"><div class="id-fes-form-wrapper">';
		$output .= '<form name="fes" id="fes" action="" method="POST" enctype="multipart/form-data">';
		$output .= $form->display_form();
		$output .= '</form>';
		$output .= '</div></div>';
	}*/
	$form = new ID_FES(null, $vars);
	do_action('ide_before_fes_display');
	$output = '<div class="ignitiondeck"><div class="id-fes-form-wrapper">';
	$output .= '<form name="fes" id="fes" action="" method="POST" enctype="multipart/form-data">';
	$output .= $form->display_form();
	$output .= '</form>';
	$output .= '</div></div>';
	return apply_filters('ide_fes_display', $output);
}

add_action('init', 'ide_check_create_project', 2);

function ide_check_create_project() {
	if (isset($_GET['create_project'])&& is_user_logged_in()) {
		add_action('wp_enqueue_scripts', 'enqueue_enterprise_js');
		add_filter('the_content', 'ide_create_project');
		if (class_exists('WPSEO_OpenGraph')) {
			remove_action('init', 'initialize_wpseo_front');
		}
	}
	else if (isset($_GET['edit_project'])) {
		$project_id = absint($_GET['edit_project']);
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;
		$user_projects = get_user_meta($user_id, 'ide_user_projects', true);
		if (!empty($user_projects)) {
			$user_projects = unserialize($user_projects);
			if (in_array($project_id, $user_projects)) {
				add_filter('the_content', 'ide_edit_project');
				add_action('wp_enqueue_scripts', 'enqueue_enterprise_js');
			}
		}
		if (class_exists('WPSEO_OpenGraph')) {
			remove_action('init', 'initialize_wpseo_front');
		}
	}
}

function ide_create_project($content) {
	$content = id_submissionForm();
	return $content;
}


//add_action('init', 'ide_check_edit_project');

/*function ide_check_edit_project() {
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
}*/

function ide_edit_project($content) {
	/*$edit_form = new ID_FES();
	$content = '<div class="ignitiondeck"><div class="id-purchase-form-wrapper">';
	$content .= '<form name="fes" id="fes" action="" method="POST">';
	$content .= $edit_form->display_form();
	$content .= '</form>';
	$content .= '</div></div>';*/
	$post_id = absint($_GET['edit_project']);
	if (isset($post_id) && $post_id > 0) {
		$status = get_post_status($post_id);
		$content = '';
		if (strtoupper($status) == 'DRAFT') {
			$content .= '<div class="ignitiondeck"><p class="notification green"><strong>'.__('Your project has been submitted and is awaiting review.', 'ignitiondeck').'</strong><br/>';
			$content .= __('You can visit this page at any time in order to continue editing your project.', 'ignitiondeck').'</p></div>';
		}
		else if (strtoupper($status) == 'PUBLISH') {
			$content .= '<div class="ignitiondeck"><p class="notification green"><strong>'.__('Your project is live.', 'ignitiondeck').'</strong><br/>';
			$content .= __('You may continue to add levels or edit content available to you on this screen.', 'ignitiondeck').'</p></div>';
		}
		$content .= id_submissionForm($post_id);
	}
	return $content;
}

function enqueue_enterprise_js() {
	wp_enqueue_script('jquery-ui-datepicker');
	wp_register_style('id-datepicker', plugins_url('ign_metabox/style.css', __FILE__));
	wp_enqueue_style('id-datepicker');
}

function set_user_projects($post_id, $user_id = null) {
	$post = get_post($post_id);
	if (isset($post)) {
		$post_type = $post->post_type;
		if ($post_type == 'ignition_product') {
			if (empty($user_id)) {
				$user_id = $post->post_author;
			}
			else {
				$user_id = 1;
			}
			if (isset($user_id)) {
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
			}
		}
	}
}

add_action('save_post', 'set_user_projects', 500);

add_action('wp', 'ide_use_default_project_page');

function ide_use_default_project_page() {
	global $theme_base;
	if (empty($theme_base) || $theme_base !== 'fivehundred') {
		global $post;
		if (isset($post)) {
			$post_id = $post->ID;
			$content = $post->post_content;
			if ($post->post_type == 'ignition_product') {
				add_filter('the_content', 'ide_default_shortcode');
			}
		}
	}
}

function ide_default_shortcode($content) {
	global $post;
	$post_id = $post->ID;
	$project_id = get_post_meta($post_id, 'ign_project_id', true);
	$content = do_shortcode('[project_page_complete product="'.$project_id.'"]');
	return $content;
}

add_action('wp', 'ide_check_show_preview');

function ide_check_show_preview() {
	global $post;
	if (isset($post)) {
		$post_id = $post->ID;
		if (isset($post_id)) {
			if (is_user_logged_in()) {
				global $current_user;
				get_currentuserinfo();
				$user_id = $current_user->ID;
				$user_projects = get_user_meta($user_id, 'ide_user_projects', true);
				if (!empty($user_projects)) {
					$user_projects = unserialize($user_projects);
					if (in_array($post_id, $user_projects)) {
						//add_filter('pre_get_posts', 'ide_show_preview');
					}
				}
			}
		}
	}
}

//add_action('pre_get_posts', 'ide_show_preview');

function ide_show_preview_old($query) {
	if (!is_admin() && $query->is_main_query() && $query->is_singular()) {
		if (isset($_GET['p'])) {
			$post_id = $_GET['p'];
		}
	}
	if (isset($post_id)) {
		if (is_user_logged_in()) {
			global $current_user;
			get_currentuserinfo();
			$user_id = $current_user->ID;
			$user_projects = get_user_meta($user_id, 'ide_user_projects', true);
			if (!empty($user_projects)) {
				$user_projects = unserialize($user_projects);
				if (in_array($post_id, $user_projects)) {
					$query->set('post_status', 'publish, draft');
					add_filter('posts_results', 'test_some_stuff');
				}
			}
		}
	}
	return $query;
}

add_filter('posts_results', 'ide_show_preview');

function ide_show_preview($posts) {
	if (isset($posts)) {
		if (is_main_query() && !is_admin() && is_singular()) {
			if (!empty($posts)) {
				$post = $posts[0];
				if ($post->post_type == 'ignition_product') {
					$post_id = $post->ID;
				}
			}
		}
	}
	if (isset($post_id)) {
		if (is_user_logged_in()) {
			global $current_user;
			get_currentuserinfo();
			$user_id = $current_user->ID;
			$user_projects = get_user_meta($user_id, 'ide_user_projects', true);
			if (!empty($user_projects)) {
				$user_projects = unserialize($user_projects);
				if (in_array($post_id, $user_projects)) {
					$posts[0]->post_status = 'publish';
				}
			}
		}
	}
	return $posts;
}

add_action('md_profile_extratabs', 'ide_backer_profile_tab', 1);

function ide_backer_profile_tab() {
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	if (isset($_GET['backer_profile'])) {
		$profile = absint($_GET['backer_profile']);
	}
	echo '<li '.(isset($profile) && $profile == $user_id ? 'class="active"' : '').'><a href="?backer_profile='.$user_id.'">'.__('My Profile', 'ignitiondeck').'</a></li>';
}

add_action('init', 'ide_backer_profile');

function ide_backer_profile() {
	if (isset($_GET['backer_profile'])) {
		$profile = absint($_GET['backer_profile']);
		if (isset($profile) && $profile > 0) {
			add_filter('the_content', 'ide_backer_profile_display');
		}
	}
}

function ide_backer_profile_display($content) {
	// we should really turn this into a template
	$content = '';
	$profile = absint($_GET['backer_profile']);
	$user = get_user_by('id', $profile);
	$name = $user->user_firstname.' '.$user->user_lastname;
	$twitter_link = get_user_meta($profile, 'twitter', true);
	$fb_link = get_user_meta($profile, 'facebook', true);
	$google_link = get_user_meta($profile, 'google', true);
	do_action('ide_before_backer_profile');
	$content .= '<div class="ignitiondeck backer_profile">';
	$content .= '<div class="backer_info">';
	$content .= '<div class="backer_avatar">'. get_avatar($profile) .'</div>';
	$content .= '<div class="backer_title"><h3>'.$name.'<div class="id-backer-links">'.(!empty($twitter_link) ? '<a href="'.$twitter_link.'" class="twitter">Twitter</a>' : '').(!empty($fb_link) ? '<a href="'.$fb_link.'" class="facebook">Facebook</a>' : '').(!empty($google_link) ? '<a href="'.$google_link.'" class="googleplus">Google Plus</a>' : '').'</div></h3>';
	$content .= '<p>'.$user->description.'</p></div></div>';
	// this would be so much more efficient if we attached a project ID to an mdid order or
	// to a pay info id
	if (class_exists('ID_Member_Order')) {
		$misc = ' WHERE user_id = "'.$profile.'"';
		$misc = ' WHERE user_id = "'.$profile.'"';
		$orders = ID_Member_Order::get_orders(null, null, $misc);
		if (!empty($orders)) {
			$mdid_orders = array();
			foreach ($orders as $order) {
				$mdid_order = mdid_by_orderid($order->id);
				if (!empty($mdid_order)) {
					$mdid_orders[] = $mdid_order;
				}
			}
			if (!empty($mdid_orders)) {
				$id_orders = array();
				foreach ($mdid_orders as $payment) {
					$order = new ID_Order($payment->pay_info_id);
					$the_order = $order->get_order();
					if (!empty($the_order)) {
						$id_orders[] = $the_order;
					}
				}
				if (!empty($id_orders)) {
					$listed = array();
					$order_content = '<div class="cf"> </div><ul class="backer_projects">';
					foreach ($id_orders as $id_order) {
						$project = new ID_Project($id_order->product_id);
						$the_project = $project->the_project();
						if (!empty($the_project) && !in_array($id_order->product_id, $listed)) {
							$post_id = $project->get_project_postid();
							$url = getProjectURLfromType($id_order->product_id);
							$image = get_post_meta($post_id, 'ign_product_image1', true);
							$order_content .= '<li class="backer_project_mini">';
							if (isset($image)) {
								$order_content .= '<a href="'.$url.'"><div class="backer_project_image" style="background-image: url('.$image.');"></div></a>';
							}
							$order_content .= '<strong><a href="'.$url.'">'.$the_project->product_name.'</a></strong>';
							$order_content .= '<p>'.strip_tags(html_entity_decode($project->short_description())).'</p>';
							$order_content .= '</li>';
							$listed[] = $id_order->product_id;
						}
					}
					$order_content .= '</ul>';
					$order_count = count($listed);
					$content .= '<div class="backer_data"><p class="backer_supported"><span class="order_count">'.$order_count.'</span> '.__('Projects Supported', 'ignitiondeck').'</p>';
					$content .= '<p class="backer_joined">'.__('Joined', 'ignitiondeck').' '.date('n/j/Y', strtotime($user->user_registered)).'</p></div>';
					$content .= $order_content;
				}
			}
		}
	$content .= '</div>';
	do_action('ide_after_backer_profile');
	}
	return $content;
}
?>