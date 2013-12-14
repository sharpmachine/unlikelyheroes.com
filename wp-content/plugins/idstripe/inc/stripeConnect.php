<?php

// This function adds the admin menu to the IgnitionDeck menu
add_action('id_submenu', 'idsc_menu');

function idsc_menu() {
	$sc = add_submenu_page( 'ignitiondeck', __('Stripe Connect', 'idstripe'), __('Stripe Connect', 'idstripe'), 'manage_options', 'sc-settings', 'sc_settings');
	add_action('admin_print_styles-'.$sc, 'idsc_admin_scripts');
}

function idsc_admin_scripts() {	
	wp_register_style('idsc-buttons', plugins_url('/inc/lib/connect-buttons.css', dirname(__FILE__)));
	wp_register_script('idsc-admin', plugins_url('/js/idsc-admin.js', dirname(__FILE__)));
	wp_register_style('idstripe-admin', plugins_url('/admin-style.css', dirname(__FILE__)));
	wp_enqueue_style('idsc-buttons');
	wp_enqueue_script('idsc-admin');
	wp_enqueue_style('idstripe-admin');
	$settings = get_option('idsc_settings');
	$link_id = '';
	$settings = get_option('idsc_settings');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		if (is_array($settings)) {
			$dev_mode = $settings['dev_mode'];
			if ($dev_mode == 1) {
				$link_id = $settings['dev_client_id'];
			}
			else {
				$link_id = $settings['client_id'];
			}
		}
	}
	wp_localize_script('idsc-admin', 'idsc_clientid', $link_id);
}

function sc_settings() {
	// Stripe Connect Admin
	$client_id = '';
	$dev_client_id = '';
	$fee_type = 'flat';
	$app_fee = 0;
	$dev_mode = 0;
	$settings = get_option('idsc_settings');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		if (is_array($settings)) {
			$client_id = $settings['client_id'];
			$dev_client_id = $settings['dev_client_id'];
			$fee_type = $settings['fee_type'];
			$app_fee = $settings['app_fee'];
			$dev_mode = $settings['dev_mode'];
		}
	}
	if (isset($_POST['submit'])) {
		$client_id = $_POST['client_id'];
		$dev_client_id = $_POST['dev_client_id'];
		$fee_type = $_POST['fee_type'];
		$app_fee = $_POST['app_fee'];
		if (isset($_POST['dev_mode'])) {
			$dev_mode = 1;
		}
		else {
			$dev_mode = 0;
		}
		$settings = array('client_id' => $client_id,
			'dev_client_id' => $dev_client_id,
			'fee_type' => $fee_type,
			'app_fee' => $app_fee,
			'dev_mode' => $dev_mode);
		update_option('idsc_settings', serialize($settings));

	}
	if ($dev_mode == 1) {
		$link_id = $dev_client_id;
	}
	else {
		$link_id = $client_id;
	}
	include_once IDSTRIPE_PATH.'templates/admin/_connectSettings.php';
}

add_action('wp_enqueue_scripts', 'idsc_scripts');

function idsc_scripts() {
	wp_register_style('idsc-buttons', plugins_url('/inc/lib/connect-buttons.css', dirname(__FILE__)));
	wp_enqueue_style('idsc-buttons');
}

add_action('init', 'idsc_return_handler');

function idsc_return_handler() {
	if (isset($_GET['ipn_handler']) && $_GET['ipn_handler'] == 'sc_return') {
		// we're in
		if (isset($_GET['code'])) {
			$code = $_GET['code'];
		}
		if (isset($_GET['state'])) {
			$state = $_GET['state'];
		}
		if (isset($code) && isset($state)) {
			$url = 'https://connect.stripe.com/oauth/token?code='.$code.'&grant_type=authorization_code';
			$ch = curl_init($url);
			$payment_settings = get_idstripe_settings();
		    if ($payment_settings->sandbox_mode !== 'sandbox') {
		    	$key = $payment_settings->api_key;
		    }
		    else {
		    	$key = $payment_settings->sandbox_api_key;
		    }
			$params = array('client_secret' => $key);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$json = curl_exec($ch);
			curl_close($ch);
			if (isset($json)) {
				$response = json_decode($json);
				$access_token = $response->access_token;
				$refresh_token = $response->refresh_token;
				$stripe_publishable_key = $response->stripe_publishable_key;
				$stripe_user_id = $response->stripe_user_id;
				$params = array('access_token' => $access_token,
					'refresh_token' => $refresh_token,
					'stripe_publishable_key' => $stripe_publishable_key,
					'stripe_user_id' => $stripe_user_id);
				$project_id = $_GET['state'];
				if (isset($project_id) && class_exists('ID_Project')) {
					$project = new ID_Project($project_id);
					$post_id = $project->get_project_postid();
					if (isset($post_id)) {
						update_post_meta($post_id, 'idsc_custom_params', serialize($params));
					}
				}
			}
		}
	}
}
?>