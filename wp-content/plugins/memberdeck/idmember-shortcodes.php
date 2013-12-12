<?php
add_shortcode('memberdeck_dashboard', 'memberdeck_dashboard');

function memberdeck_dashboard() {
	ob_start();
	global $crowdfunding;
	global $instant_checkout;
	if (is_user_logged_in()) {
		global $current_user;
		$user_id = $current_user->ID;
		//global $customer_id; --> will trigger 1cc notice
		get_currentuserinfo();
		$fname = $current_user->user_firstname;
		$lname = $current_user->user_lastname;
		$registered = $current_user->user_registered;
		$key = md5($registered.$current_user->ID);
		// expire any levels that they have not renewed
		$level_check = memberdeck_exp_checkondash($current_user->ID);
		// this is an array user options
		$user_levels = ID_Member::user_levels($current_user->ID);
	}

	if (isset($user_levels)) {
		// this is an array of levels a user has access to
		$access_levels = unserialize($user_levels->access_level);
		if (is_array($access_levels)) {
			$unique_levels = array_unique($access_levels);
		}
	}
	
	$downloads = ID_Member_Download::get_downloads();
	// we have a list of downloads, but we need to get to the levels by unserializing and then restoring as an array
	if (isset($downloads)) {
		// this will be a new array of downloads with array of levels
		$download_array = array();
		foreach ($downloads as $download) {
			$new_levels = unserialize($download->download_levels);
			unset($download->download_levels);
			// lets loop through each level of each download to see if it matches
			$pass = false;
			foreach ($new_levels as $single_level) {
				if (isset($unique_levels) && in_array($single_level, $unique_levels)) {
					// if this download belongs to our list of user levels, add it to array
					//$download->download_levels = $new_levels;
					$pass = true;
				}
			}
			if (isset($user_id))
				$license_key = MD_Keys::get_license($user_id, $download->id);
			if ($pass) {
				$download->key = $license_key;
				$download_array['visible'][] = $download;
			}
			else {
				$download_array['invisible'][] = $download;
			}
		}
		// we should now have an array of downloads that this user has accces to
	}
	if (is_user_logged_in()) {
		$dash = get_option('md_dash_settings');
		if (!empty($dash)) {
			$dash = unserialize($dash);
			if (isset($dash['layout'])) {
				$layout = $dash['layout'];
			}
			else {
				$layout = 1;
			}
			if (isset($dash['alayout'])) {
				$alayout = $dash['alayout'];
			}
			else {
				$alayout = 'md-featured';
			}
			$aname = $dash['aname'];
			if (isset($dash['blayout'])) {
				$blayout = $dash['blayout'];
			}
			else {
				$blayout = 'md-featured';
			}
			$bname = $dash['bname'];
			if (isset($dash['clayout'])) {
				$clayout = $dash['clayout'];
			}
			else {
				$clayout = 'md-featured';
			}
			$cname = $dash['cname'];
			if ($layout == 1) {
				$p_width = 'half';
				$a_width = 'half';
				$b_width = 'half';
				$c_width = 'half';
			}
			else if ($layout == 2) {
				$p_width = 'half';
				$a_width = 'half';
				$b_width = 'full';
				$c_width = 'full';
			}
			else if ($layout == 3) {
				$p_width = 'full';
				$a_width = 'full';
				$b_width = 'full';
				$c_width = 'full';
			}
			else if ($layout == 4) {
				$p_width = 'half';
				$a_width = 'half-tall';
				$b_width = 'half';
				$c_width = 'hidden';
			}
			if (isset($dash['powered_by'])) {
				$powered_by = $dash['powered_by'];
			}
			else {
				$powered_by = 1;
			}
		}

		global $md_credits;
		$settings = get_option('memberdeck_gateways', true);
		if (isset($settings)) {
			$options = unserialize($settings);
			if (is_array($options)) {
				$es = $options['es'];
				$eb = $options['eb'];
				if ($es == 1) {
					global $customer_id;
				}
				else if ($eb == 1) {
					global $balanced_customer_id;
					$customer_id = $balanced_customer_id;
				}
			}
		}
		if ($md_credits > 0 || !empty($customer_id)) {
			$show_occ = true;
		}
		else {
			$show_occ = false;
		}
		include_once 'templates/admin/_memberDashboard.php';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	else {
		include_once 'templates/_protectedPage.php';
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}

add_shortcode('memberdeck_checkout', 'memberdeck_checkout');

function memberdeck_checkout($attrs) {
	ob_start();
	global $customer_id;
	global $instant_checkout;
	global $crowdfunding;
	// use the shortcode attr to get our level id
	$product_id = $attrs['product'];

	// get the user info
	if (is_user_logged_in()) {
		global $current_user;
		get_currentuserinfo();
		$email = $current_user->user_email;
		$fname = $current_user->user_firstname;
		$lname = $current_user->user_lastname;
		$user_data = ID_Member::user_levels($current_user->ID);
		if (!empty($user_data)) {
			$user_levels = unserialize($user_data->access_level);
		}
		else {
			$user_levels = null;
		}
		// lets see how many levels this user owns
		if (is_array($user_levels)) {
			foreach ($user_levels as $level) {
				if ($level == $product_id) {
					$already_valid = true;
				}
			}
		}
	}
	$settings = get_option('md_receipt_settings');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		$coname = $settings['coname'];
	}
	else {
		$coname = '';
	}
	
	$gateways = get_option('memberdeck_gateways');
	if (!empty($gateways)) {
		$gateways = unserialize($gateways);
		// gateways are saved and we can now get settings from Stripe and Paypal
		if (is_array($gateways)) {
			$pp_email = $gateways['pp_email'];
			$test_email = $gateways['test_email'];
			$pk = $gateways['pk'];
			$sk = $gateways['sk'];
			$tpk = $gateways['tpk'];
			$tsk = $gateways['tsk'];
			$test = $gateways['test'];
			$epp = $gateways['epp'];
			$es = $gateways['es'];
			$esc = $gateways['esc'];
			$bk = $gateways['bk'];
			$btk = $gateways['btk'];
			$eb = $gateways['eb'];
		}
	}
	// Now we check for Stripe connect data
	if (function_exists('is_id_pro') && is_id_pro()) {
		$settings = get_option('memberdeck_gateways');
		if (!empty($settings)) {
			$settings = unserialize($settings);
			if (is_array($settings)) {
				$esc = $settings['esc'];
				if ($esc == '1') {
					$check_claim = get_option('md_level_'.$product_id.'_owner');
					if (!empty($check_claim)) {
						// do stuff
						$claimed_paypal = get_user_meta($check_claim, 'md_paypal_email', true);
						$md_sc_creds = get_sc_params($check_claim);
						if (!empty($md_sc_creds)) {
							$sc_accesstoken = $md_sc_creds->access_token;
							$sc_pubkey = $md_sc_creds->stripe_publishable_key;
						}
					}
				}
			}
		}
	}
	if ($es == 1) {
		require_once 'lib/Stripe.php';
		if (isset($test) && $test == '1') {
			Stripe::setApiKey($tsk);
		}
		else {
			Stripe::setApiKey($sk);
		}
	}
	else if ($eb == 1) {
		if (isset($test) && $test == '1') {
			$burl = $gateways['bturl'];
		}
		else {
			$burl = $gateways['burl'];
		}
	}

	// use that id to get our level data
	$return = ID_Member_Level::get_level($product_id);
	// we have that data, lets store it in vars
	$level_name = $return->level_name;
	$level_price = $return->level_price;
	$txn_type = $return->txn_type;
	if ($level_price !== '' && $level_price > 0) {
		$level_price = number_format(floatval($level_price), 2, '.', ',');
	}
	$currency = memberdeck_pp_currency();
	if (!empty($currency)) {
		$pp_currency = $currency['code'];
		$pp_symbol = $currency['symbol'];
	}
	else {
		$pp_currency = 'USD';
		$pp_symbol = '$';
	}
	
	$type = $return->level_type;
	$recurring = $return->recurring_type;

	$credit_value = $return->credit_value;
	$cf_level = false;
	if ($crowdfunding) {
		$cf_assignments = get_assignments_by_level($product_id);
		if (!empty($cf_assignments)) {
			$project_id = $cf_assignments[0]->project_id;
			$project = new ID_Project($project_id);
			$the_project = $project->the_project();
			$post_id = $project->get_project_postid();
			$id_disclaimer = get_post_meta($post_id, 'ign_disclaimer', true);
		}
	}


	// Now we'll see if this user has a Stripe Customer_ID on file
	if (!empty($user_data)) {
		$data = $user_data->data;
		if (!empty($data)) {
			$data_array = unserialize($data);
			if (is_array($data_array)) {
				foreach ($data_array as $array) {
					if (isset($array['customer_id'])) {
						$customer_id = $array['customer_id'];
					}
				}
			}
		}
	}
	if (!isset($already_valid)) {
		// they don't own this level, send forth the template
		include_once 'templates/_checkoutForm.php';
		$content = ob_get_contents();
	}
	else {
		// they already own this one
		$content = '<p>'.__('You already own this product. Please', 'memberdeck').' <a href="'.wp_logout_url().'">'.__('logout', 'memberdeck').'</a> '.__('and create a new account in order to purchase again', 'memberdeck').'.</p>';
	}
	ob_end_clean();
	return $content;
}
?>