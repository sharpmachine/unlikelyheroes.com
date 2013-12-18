<?php
add_action ('md_profile_extratabs', 'md_creator_projects');

function md_creator_projects() {
	$enable_creator = 0;
	$general = get_option('md_receipt_settings');
	if (!empty($general)) {
		$general = unserialize($general);
		if (is_array($general)) {
			$enable_creator = $general['enable_creator'];
		}
	}
	if ($enable_creator) {
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;
		$user_projects = get_user_meta($user_id, 'ide_user_projects', true);
		if (!empty($user_projects)) {
			$user_projects = unserialize($user_projects);
			if (is_array($user_projects)) {
				echo '<li><a href="?payment_settings=1">'.__('Payment Settings', 'memberdeck').'</a></li>';
			}
		}
		echo '<li><a href="?creator_projects=1">'.__('My Projects', 'memberdeck').'</a></li>';
	}
}

add_action('init', 'md_ide_check_creator_profile');

function md_ide_check_creator_profile() {
	if (isset($_GET['creator_projects']) && $_GET['creator_projects'] == 1 && is_user_logged_in()) {
		add_filter('the_content', 'md_ide_creator_projects');
	}
}

function md_ide_creator_projects($content) {
	ob_start();
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	echo '<div class="memberdeck">';
	include_once MD_PATH.'templates/_mdProfileTabs.php';
	echo '<ul class="md-box-wrapper full-width cf"><li class="md-box full"><div class="md-profile">';
	echo '<h3>'.__('My Projects', 'memberdeck').': </h3>';
	echo '<ul>';
	$user_projects = get_user_meta($user_id, 'ide_user_projects', true);
	if (!empty($user_projects)) {
		$user_projects = unserialize($user_projects);
		if (is_array($user_projects)) {
			foreach ($user_projects as $editable_project) {
				$post_id = $editable_project;
				$project_id = get_post_meta($post_id, 'ign_project_id', true);
				if (!empty($project_id)) {
					$post = get_post($post_id);
					$project = new ID_Project($project_id);
					$the_project = $project->the_project();
					$thumb = get_post_meta($post_id, 'ign_product_image1', true);
					include MD_PATH.'templates/_myProjects.php';
				}
			}
		}
	}
	echo '</ul><button class="create_project button-medium" onclick="location.href=\'?create_project=1\'">'.__('Create Project', 'memberdeck').'</button></div></li></ul>';
	echo '</div>';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_action('init', 'md_ide_check_payment_settings');

function md_ide_check_payment_settings() {
	if (isset($_GET['payment_settings']) && $_GET['payment_settings'] == 1 && is_user_logged_in()) {
		add_filter('the_content', 'md_ide_payment_settings');
	}
}

function md_ide_payment_settings($content) {
	ob_start();
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	$content = null;
	$settings = get_option('memberdeck_gateways');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		if (is_array($settings)) {
			$epp_fes = $settings['epp_fes'];
			$esc = $settings['esc'];
			$eb_fes = $settings['eb_fes'];
		}
	}
	$paypal_email = get_user_meta($user_id, 'md_paypal_email', true);
	if (isset($_POST['payment_settings_submit'])) {
		$paypal_email = esc_attr($_POST['paypal_email']);
		update_user_meta($user_id, 'md_paypal_email', $paypal_email);
	}
	include_once MD_PATH.'templates/_paymentSettings.php';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_action('ide_fes_create', 'mdid_fes_associations', 5, 6);

function mdid_fes_associations($user_id, $project_id, $post_id, $proj_args, $levels, $auth) {
	/*
	Steps:
	Detect which gateways are enabled so we know how to use auth
	Enable CF for that project
	Create MD Level
	Associate MD Level to ID Project Levels
	Associate user to MD level
	*/
	global $wpdb;
	$gateways = get_option('memberdeck_gateways', true);
	if (!empty($gateways)) {
		$settings = unserialize($gateways);
		if (is_array($settings)) {
			$es = $settings['es'];
			$eb = $settings['eb'];
			$epp = $settings['epp'];
		}
	}
	if ($es == 1 || $eb == 1) {
		$auth = $auth;
	}
	else {
		$auth = 'capture';
	}
	// enable project for cf
	update_post_meta($post_id, 'mdid_project_activate', 'yes');
	$i = 0;

	foreach ($levels as $level) {
		$title = $levels[$i]['title'];
		$price = $levels[$i]['price'];

		$level = new ID_Member_Level();

		$args = array();
		$args['level_name'] = $title;
		$args['level_price'] = $price;
		$args['credit_value'] = 0;
		$args['txn_type'] = $auth;
		$args['level_type'] = 'lifetime';
		$args['license_count'] = 0;
		// create level
		$new_level = $level->add_level($args);
		$level_id = $new_level['level_id'];
		// assign cf levels
		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'mdid_project_levels (levels) VALUES (%s)', serialize(array($i+1)));
		$res = $wpdb->query($sql);
		$assignment_id = $wpdb->insert_id;
		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'mdid_assignments (level_id, project_id, assignment_id) VALUES (%d, %d, %d)', $level_id, $project_id, $assignment_id);
		$res = $wpdb->query($sql);
		// attach user to this project/level
		$claim_level = update_option('md_level_'.$level_id.'_owner', $user_id);
		$i++;
	}
}

add_action('id_fes_create', 'md_ide_notify_admin', 5, 6);

function md_ide_notify_admin($user_id, $project_id, $post_id, $proj_args, $levels, $project_fund_type) {
	$user = get_userdata($user_id);
	$user_login = $user->user_login;

	$settings = get_option('md_receipt_settings');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		$coname = $settings['coname'];
		$coemail = $settings['coemail'];
	}
	else {
		$coname = '';
		$coemail = '';
	}

	if (isset($project_id) && $project_id > 0) {
		$project = new ID_Project($project_id);
		$the_project = $project->the_project();
		$description = get_post_meta($post_id, 'ign_project_description', true);
		$edit_link = admin_url().'/post.php?post='.$post_id.'&amp;action=edit';
		/* 
		** Mail Function
		*/

		// Sending email to customer on the completion of order
		$subject = __('New Project Submission', 'memberdeck');
		$headers = __('From', 'memberdeck').': '.$coname.' <'.$coemail.'>' . "\n";
		$headers .= __('Reply-To', 'memberdeck').': '.$coemail."\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
		$message = '<html><body>';
		$message .= '<div style="padding:10px;background-color:#f2f2f2;">
						<div style="padding:10px;border:1px solid #eee;background-color:#fff;">
						<h2>'.__('Project Submission Notification', 'memberdeck').'</h2>

							<div style="margin:10px 0;">
	  
	  							'.__('You have a new project submission from user ', 'memberdeck').' '.$user_login.__('with the following attributes', 'memberdeck').':<br /><br />
							</div>';
		$message .= '		<div style="border: 1px solid #333333; width: 500px;">
								<table width="500" border="0" cellspacing="0" cellpadding="5">
	      							<tr bgcolor="#333333" style="color: white">
				                        <td width="100">'.__('Title', 'memberdeck').'</td>
				                        <td width="275">'.__('Description', 'memberdeck').'</td>
				                        <td width="125">'.__('Goal', 'memberdeck').'</td>
				                    </tr>
			                         <tr>
			                           <td width="200">'.$the_project->product_name.'</td>
			                           <td width="275">'.$description.'</td>
			                           <td width="125">'.number_format($the_project->goal, 2, '.', ',').'</td>
			                      	</tr>
								</table>
							</div>';
		$message .= '		<div style="margin:10px 0;"><a href="'.$edit_link.'">'.__('Use this link', 'memberdeck').'</a>'.__(' to moderate the project', 'memberdeck').'<br /><br />
							</div>';
		$message .= '		<table rules="all" style="border-color:#666;width:80%;margin:20px auto;" cellpadding="10">

	    					<!--table rows-->

							</table>

			               ---------------------------------<br />
			               '.$coname.'<br />
			               <a href="mailto:'.$coemail.'">'.$coemail.'</a>
			           

			            </div>
			        </div>';
		$message .= '</body></html>';
		$send = md_send_mail($coemail, $headers, $subject, $message);
	}
}

add_action('id_fes_create', 'md_ide_notify_creator', 5, 6);

function md_ide_notify_creator($user_id, $project_id, $post_id, $proj_args, $levels, $project_fund_type) {
	$user = get_userdata($user_id);
	$email = $user->user_email;
	$settings = get_option('md_receipt_settings');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		$coname = $settings['coname'];
		$coemail = $settings['coemail'];
	}
	else {
		$coname = '';
		$coemail = '';
	}

	if (isset($project_id) && $project_id > 0) {
		$project = new ID_Project($project_id);
		$the_project = $project->the_project();
		$description = get_post_meta($post_id, 'ign_project_description', true);
		$dash = get_option('md_dash_settings');
			if (!empty($dash)) {
				$dash = unserialize($dash);
				if (isset($durl)) {
					$durl = $dash['durl'];
				}
				else {
					$durl = home_url().'/dashboard';
				}
		}
		$edit_link = $durl.'/?edit_project='.$post_id;
		/* 
		** Mail Function
		*/

		// Sending email to customer on the completion of order
		$subject = __('Project Submission Confirmation', 'memberdeck');
		$headers = __('From', 'memberdeck').': '.$coname.' <'.$coemail.'>' . "\n";
		$headers .= __('Reply-To', 'memberdeck').': '.$coemail."\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
		$message = '<html><body>';
		$message .= '<div style="padding:10px;background-color:#f2f2f2;">
						<div style="padding:10px;border:1px solid #eee;background-color:#fff;">
						<h2>'.__('Project Submission Confirmation', 'memberdeck').'</h2>

							<div style="margin:10px 0;">
	  
	  							'.__('Congratulations. The following project has been submitted for approval', 'memberdeck').':<br /><br />
							</div>';
		$message .= '		<div style="border: 1px solid #333333; width: 500px;">
								<table width="500" border="0" cellspacing="0" cellpadding="5">
	      							<tr bgcolor="#333333" style="color: white">
				                        <td width="100">'.__('Title', 'memberdeck').'</td>
				                        <td width="275">'.__('Description', 'memberdeck').'</td>
				                        <td width="125">'.__('Goal', 'memberdeck').'</td>
				                    </tr>
			                         <tr>
			                           <td width="200">'.$the_project->product_name.'</td>
			                           <td width="275">'.$description.'</td>
			                           <td width="125">'.number_format($the_project->goal, 2, '.', ',').'</td>
			                      	</tr>
								</table>
							</div>';
		$message .= '		<div style="margin:10px 0;">'.__('You will be notified when the review process has been completed. In the interim, you may use ', 'memberdeck').'<a href="'.$edit_link.'">'.__('use this link', 'memberdeck').'</a>'.__(' to continue editing the project', 'memberdeck').'<br /><br />
							</div>';
		$message .= '		<table rules="all" style="border-color:#666;width:80%;margin:20px auto;" cellpadding="10">

	    					<!--table rows-->

							</table>

			               ---------------------------------<br />
			               '.$coname.'<br />
			               <a href="mailto:'.$coemail.'">'.$coemail.'</a>
			           

			            </div>
			        </div>';
		$message .= '</body></html>';
		$send = md_send_mail($email, $headers, $subject, $message);
	}
}
?>