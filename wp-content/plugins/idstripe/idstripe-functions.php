<?php

add_filter('id_pay_choices', 'idstripe_pay_choice', 5, 2);

function idstripe_pay_choice($pay_choices, $product_id) {
	$pay_choices .= '<a id="pay-with-stripe" class="pay-choice btn" href="#"><span class="icon-credit"></span> '.__('Credit Card', 'idstripe').'</a>';
	return $pay_choices;
}
//add_shortcode('stripe_form', 'idstripe_stripe_form');
add_filter('id_purchaseform_extrafields', 'idstripe_stripe_form', 10, 1);

function idstripe_stripe_form() {
	ob_start();
	global $wpdb;

	// Setting Stripe libs and keys
	require_once 'inc/lib/Stripe.php';
    $payment_settings = get_idstripe_settings();
    if ($payment_settings->sandbox_mode !== 'sandbox') {
    	$key = $payment_settings->api_key;
    	$pub_key = $payment_settings->stripe_publishable_key;
    }
    else {
    	$key = $payment_settings->sandbox_api_key;
    	$pub_key = $payment_settings->sandbox_publishable_key;
    }
    $pp_off = $payment_settings->disable_pp;

    // Getting product id from URL
    if (isset($_GET)) {
    	if (isset($_GET['prodid']) && $_GET['prodid'] > 0) {
    		$product_id = $_GET['prodid'];
    	}
    }
    else {
    	// Setting default product ID
    	$product_id = 1;
    }

   /* if (class_exists('ID_Project')) {
		$project = new ID_Project($product_id);
		$post_id = $project->get_project_postid();
		$params = get_post_meta($post_id, 'idsc_custom_params', true);
		if (!empty($params)) {
			$params = unserialize($params);
			if (is_array($params)) {
				$key = $params['access_token'];
				$pub_key = $params['stripe_publishable_key'];
			}
		}
	} */

	$cCode = idstripe_ccode();

    $ty_url = get_idstripe_ty_url($product_id, "thank_you_url");

	Stripe::setApiKey($key);

	include_once 'templates/_stripeForm.php';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

function get_idstripe_settings() {
	// This function gets the stripe payment info from admin
	global $wpdb;
	$sql="SELECT * FROM ".$wpdb->prefix . "ign_stripe_settings where id='1'";
    $payment_settings = $wpdb->get_row( $sql );
    return $payment_settings;
}

function idstripe_ccode() {
	$settings = get_idstripe_settings();
	$ccode = '$';
	if (!empty($settings)) {
		$currency = $settings->currency;
		if (isset($currency)) {
			switch($currency) {
				case 'USD':
					$ccode = '$';
					break;
				case 'EUR':
					$ccode = '&euro;';
					break;
				case 'GBP':
					$ccode = '&pound;';
					break;
				case 'CAD':
					$ccode = '$';
					break;
				case 'AUD':
					$ccode = '$';
					break;
			}
		}
	}
	return $ccode;
}

function get_idstripe_form_settings($product_id) {
	// This function returns custom form settings, if they exist
	global $wpdb;
	
	$sql_settings = "SELECT form_settings FROM ".$wpdb->prefix."ign_product_settings WHERE product_id = '".$product_id."'";
	$settings = $wpdb->get_row( $sql_settings );
	
	if (count($settings) > 0) {
		$form = $settings->form_settings;
		return $form;
	}
	else
		return -1;
}

function get_idstripe_product_default_settings($product_id) {
	// This function returns default product settings
	global $wpdb;
	
	$sql_settings = "SELECT * FROM ".$wpdb->prefix."ign_prod_default_settings WHERE id = '1'";
	$settings = $wpdb->get_row( $sql_settings );
	
	if (count($settings) > 0)
		return $settings;
	else
		return -1;
}

function get_idstripe_ty_url($prod_id, $page="") {
	global $wpdb;
	$prod_id = urlencode($prod_id);
	$page = urlencode($page);
	$post = getPostDetailbyProductID($prod_id);

	$post_page = get_post_meta($post->ID, 'ign_option_ty_url', true);

	if (get_option('permalink_structure') == "") {
		if ($post_page == "current_page") {		// If Project URL is the normal Project Page
			if (isset($page) && $page=="thank_you_url") {
				$thank_you_url = site_url()."/?ignition_product=".$post->post_name."&cc_success=1";
			}
			else {
				$thank_you_url = site_url()."/?ignition_product=".$post->post_name;
			}
				
		} else if ($post_page == "page_or_post") {		// If Project URL is another post or Project page

			$post_name = get_post_meta($post->ID, 'ign_ty_post_name', true);

			$sql_ty_post = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_name = '".$post_name."' AND post_type != 'ignition_product' LIMIT 1";
			$ty_post = $wpdb->get_row($sql_ty_post);
			
			if (isset($page) && $page == "thank_you_url") {
				$thank_you_url = $ty_post->guid."&cc_success=1";
			}

			else {
				$thank_you_url = $ty_post->guid;
			}
				
		} else if ($post_page == "external_url") {		//If some external URL is set as Project page
			if ($page == "thank_you_url") {
				$thank_you_url = get_post_meta($post->ID, 'ty_project_URL', true)."?cc_success=1";
			}
			
			else {
				$thank_you_url = get_post_meta($post->ID, 'ty_project_URL', true);
			}
				
		}
	} else {
		
		if ($post_page == "current_page") {		// If Project URL is the normal Project Page
			
			if ($page == "thank_you_url") {
				$thank_you_url = site_url()."/projects/".$post->post_name."/?cc_success=1";
			}
			
			else {
				$thank_you_url = site_url()."/projects/".$post->post_name;
			}
				
				
		} else if ($post_page == "page_or_post") {		// If Project URL is another post or Project page
			
			//$post_name = get_post_meta($post->ID, 'ign_ty_post_name', true);
			$post_name_query = "SELECT meta_value FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'ign_ty_post_name' AND post_id = '".$post->ID."'";
			$post_name_res = $wpdb->get_results($post_name_query);
			if (!empty($post_name_res)) {
				$post_name = $post_name_res[0]->meta_value;
			}
			else {
				$post_name = null;
			}

			$sql_ty_post = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_name = '".$post_name."' AND post_type != 'ignition_product' LIMIT 1";
			$ty_post = $wpdb->get_row($sql_ty_post);

			if (isset($page) && $page == "thank_you_url") {
				$thank_you_url = get_permalink($ty_post->ID)."?cc_success=1";
			}
			
			else {
				$thank_you_url = get_permalink($ty_post->ID);
			}
				
		} else if ($post_page == "external_url") {		//If some external URL is set as Project page
			
			if (isset($page) && $page == "thank_you_url") {
				$thank_you_url = get_post_meta($post->ID, 'ty_project_URL', true)."?cc_success=1";
			}
			
			else {
				$thank_you_url = get_post_meta($post->ID, 'ty_project_URL', true);
			}
				
		}
		
	}
	
	return $thank_you_url;
}

function idstripe_submit_handler() {
	// This function handles jQuery submit on the Stripe payment form (Ajax)
	global $wpdb;
	$payment_settings = get_idstripe_settings();
	if ($payment_settings->sandbox_mode !== 'sandbox') {
    	$key = $payment_settings->api_key;
    	$pub_key = $payment_settings->stripe_publishable_key;
    }
    else {
    	$key = $payment_settings->sandbox_api_key;
    	$pub_key = $payment_settings->sandbox_publishable_key;
    }
    $fund_type = $payment_settings->fund_type;
    $currency = $payment_settings->currency;
	if (isset($_POST['Keys'])) {
		$data = $_POST['Keys'][0];
		$token = $data['token'];

		$first_name = esc_html($data['fname']);
		$last_name = esc_html($data['lname']);
		$email = esc_attr($data['email']);
		$address = esc_attr($data['address']);
		$city = esc_attr($data['city']);
		$state = esc_attr($data['state']);
		$zip = esc_attr($data['zip']);
		$country = $data['country'];
		$product_id = absint(esc_attr($data['product']));
		$level = absint(esc_attr($data['level']));
		$amount = esc_attr($data['amount'])*100;

		/* if (class_exists('ID_Project')) {
			$project = new ID_Project($product_id);
			$post_id = $project->get_project_postid();
			$params = get_post_meta($post_id, 'idsc_custom_params', true);
			if (!empty($params)) {
				$params = unserialize($params);
				if (is_array($params)) {
					$key = $params['access_token'];
					$pub_key = $params['stripe_publishable_key'];
					$connect = true;
					$app_fee = 0;
					$settings = get_option('idsc_settings');
					if (!empty($settings)) {
						$settings = unserialize($settings);
						if (is_array($settings)) {
							$app_fee = $settings['app_fee'];
							$fee_type = $settings['fee_type'];
							if ($fee_type == 'percentage') {
								$app_fee = $amount * ($app_fee/100);
							}
						}
					}
				}
			}
		} */
		require_once 'inc/lib/Stripe.php';
		Stripe::setApiKey($key);
		$payment_variables = array("amount" => $amount,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"email" => $email,
			"address" => $address,
			"country" => $country,
			"state" => $state,
			"city" => $city,
			"zip" => $zip,
			"product_id" => $product_id,
			"txn_id" => "",
			"level" => $level,
			"status" => "P",
			"token" => $token);

		if ($token) {
			$stripe_params = array(
					'amount' => $amount,
					'currency' => $currency,
					'card' => $token,
				  	'description' => $email);
			if (isset($connect) && $connect == true) {
				$stripe_params['application_fee'] = $app_fee;
			}
			if ($fund_type == 'capture') {
				try {
					$charge = Stripe_Charge::create($stripe_params);
					$paid = $charge->paid;
				  	$refunded = $charge->refunded;
				  	$txn_id = $charge->id;
				  	$price = ($charge->amount/100);
				  	$created = $charge->created;
				  	if ($paid == 1 && $refunded !== 1) {
				  		// Payment succeeded and was not refunded
				  		$payment_variables["status"] = "C";
				  		$payment_variables["txn_id"] = $txn_id;
				  		idstripe_add_order($payment_variables);
						$response = array('code' => 'success');
					}
					else {
						$response = array('code' => __('Sorry, but your card could not be authorized.', 'idstripe'));
					}
				}
				catch (Stripe_InvalidRequestError $e) {
					$response = array('code' => $e->json_body['error']['message']);
				}
			}
			else {
				$customer = idstripe_create_customer($key, $token, $payment_variables);
				$id = $customer->id;
				if ($id) {
					$response = array('code' => 'success');
				}
				else {
					$response = array('code' => __('Sorry, but your card could not be authorized.', 'idstripe'));
				}
			}
		}
		else {
				$response = array('code' => __('Sorry, something went wrong. Please try again.', 'idstripe'));
			}
		print_r(json_encode($response));
		exit;
	}
	else {
		$response = array('code' => __('Sorry, something went wrong. Please try again.', 'idstripe'));
	}
}

add_action('wp_ajax_idstripe_submit_handler', 'idstripe_submit_handler');
add_action('wp_ajax_nopriv_idstripe_submit_handler', 'idstripe_submit_handler');

function idstripe_process_handler() {
	global $wpdb;
	$payment_settings = get_idstripe_settings();
	if ($payment_settings->sandbox_mode !== 'sandbox') {
    	$key = $payment_settings->api_key;
    	$pub_key = $payment_settings->stripe_publishable_key;
    }
    else {
    	$key = $payment_settings->sandbox_api_key;
    	$pub_key = $payment_settings->sandbox_publishable_key;
    }
    $currency = $payment_settings->currency;
	if (isset($_POST['Project'])) {
		$project_id = $_POST['Project'];
		/* if (class_exists('ID_Project')) {
			$project = new ID_Project($project_id);
			$post_id = $project->get_project_postid();
			$params = get_post_meta($post_id, 'idsc_custom_params', true);
			if (!empty($params)) {
				$params = unserialize($params);
				if (is_array($params)) {
					$key = $params['access_token'];
					$pub_key = $params['stripe_publishable_key'];
					$connect = true;
					$app_fee = 0;
					$settings = get_option('idsc_settings');
					if (!empty($settings)) {
						$settings = unserialize($settings);
						if (is_array($settings)) {
							$app_fee = $settings['app_fee'];
							$fee_type = $settings['fee_type'];
							if ($fee_type == 'percentage') {
								$app_fee = $amount * ($app_fee/100);
							}
						}
					}
				}
			}
		} */

		$stripe_orders = idstripe_stripe_orders($project_id);
		$success = array();
		$fail = array();
		$response = array();
		require_once 'inc/lib/Stripe.php';
		Stripe::setApiKey($key);
		foreach ($stripe_orders as $capture) {
			try {
				$stripe_params = array( "amount" => ($capture->prod_price*100),
				    "currency" => $currency,
				    "customer" => $capture->customer_id);
				if (isset($connect) && $connect == true) {
					$stripe_params['application_fee'] = $app_fee;
				}
				$charge = Stripe_Charge::create($stripe_params);
				$paid = $charge->paid;
				$refunded = $charge->refunded;
				$txn_id = $charge->id;
				$created = $charge->created;
				$payment_variables = array(
					"txn_id" => $txn_id,
					"status" => "C",
					"id" => $capture->id
					);
				if ($paid == 1 && $refunded !== 1) {
			  		// Payment succeeded and was not refunded
			  		idstripe_set_approval($payment_variables);
					$response = array('code' => 'success');
					$success[] = $txn_id;
					do_action('id_payment_success', $capture->id);
				}
				else {
					$response = array('code' => 'failure');
					$fail[] = "failure";
				}
			}
			catch(Stripe_Error $e) {
				//echo $e;
				$fail[] = "failure";
			}
		}
	}
	if (isset($success)) {
		$successes = count($success);
	}
	else {
		$successes = 0;
	}
	if (isset($failures)) {
		$failures = count($fail);
	}
	else {
		$failures = 0;
	}
	$response["counts"] = array("success" => $successes, "failures" => $failures);
	print_r(json_encode($response));
	exit;
}

add_action('wp_ajax_idstripe_process_handler', 'idstripe_process_handler');
add_action('wp_ajax_nopriv_idstripe_process_handler', 'idstripe_process_handler');

function idstripe_order_data($project_id) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'ign_pay_info WHERE product_id = %d AND status="W"', $project_id);
	$res = $wpdb->get_results($sql);
	return $res;
}

function idstripe_stripe_orders($id) {
	global $wpdb;
	$sql = 'SELECT '.$wpdb->prefix.'ign_pay_info.* ,'.$wpdb->prefix.'ign_stripe_users.customer_id FROM '.$wpdb->prefix.'ign_pay_info, '.$wpdb->prefix.'ign_stripe_users WHERE '.$wpdb->prefix.'ign_pay_info.id = '.$wpdb->prefix.'ign_stripe_users.pay_info_id AND '.$wpdb->prefix.'ign_pay_info.status="W" AND '.$wpdb->prefix.'ign_pay_info.product_id="'.$id.'"';
	$res = $wpdb->get_results($sql);
	return $res;
}

function idstripe_set_approval($payment_variables) {
	global $wpdb;
	$txn_id = $payment_variables['txn_id'];
	$status = $payment_variables['status'];
	$id = $payment_variables['id'];
	$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'ign_pay_info SET transaction_id=%s, status=%s WHERE id=%d', $txn_id, $status, $id);
	$res = $wpdb->query($sql);
}

function idstripe_products_handler() {
	global $wpdb;
	$sql = 'SELECT * FROM '.$wpdb->prefix.'ign_products';
	$res = $wpdb->get_results($sql);
	print_r(json_encode($res));
	exit;
}

add_action('wp_ajax_idstripe_products_handler', 'idstripe_products_handler');
add_action('wp_ajax_nopriv_idstripe_products_handler', 'idstripe_products_handler');

function idstripe_fund_type() {
	// This function gets the stripe payment info from admin
	global $wpdb;
	$sql="SELECT fund_type FROM ".$wpdb->prefix . "ign_stripe_settings where id='1'";
    $payment_settings = $wpdb->get_row( $sql );
    if (!empty($payment_settings)) {
    	echo $payment_settings->fund_type;
    }
    else {
    	echo 'capture';
    }
    exit;
}

add_action('wp_ajax_idstripe_fund_type', 'idstripe_fund_type');
add_action('wp_ajax_nopriv_idstripe_fund_type', 'idstripe_fund_type');

function get_idstripe_currency() {
	$currency = 'USD';
	$settings = get_idstripe_settings();
	if (!empty($settings)) {
		$currency = $settings->currency;
	}
	echo $currency;
	exit;
}

add_action('wp_ajax_get_idstripe_currency', 'get_idstripe_currency');
add_action('wp_ajax_nopriv_get_idstripe_currency', 'get_idstripe_currency');

function idstripe_create_customer($key, $token, $payment_variables) {
	require_once 'inc/lib/Stripe.php';
	Stripe::setApiKey($key);
	$amount = $payment_variables['amount'];
	$token = $payment_variables['token'];
	$email = $payment_variables['email'];

	$customer = Stripe_Customer::create(array(
		"card" => $token,
		"description" => $email)
	);

	$payment_variables["status"] = "W";
	$pay_info_id = idstripe_add_order($payment_variables);
	idstripe_save_customer($pay_info_id, $customer->id);
	return $customer;
}

function idstripe_save_customer($pay_info_id, $customer) {
	global $wpdb;

	$sql = 'INSERT INTO '.$wpdb->prefix.'ign_stripe_users (customer_id, pay_info_id) VALUES ("'.$customer.'", "'.$pay_info_id.'")';
	$res = $wpdb->query($sql);
	return;
}

function idstripe_add_order($payment_variables) {
	global $wpdb;
	$amount = $payment_variables['amount'] / 100;
	$query = "INSERT INTO ".$wpdb->prefix."ign_pay_info (
						prod_price,
						first_name,
						last_name,
						email,
						address,
						country,
						state,
						city,
						zip,
						product_id,
						transaction_id,
						product_level,
						status,
						created_at
					)
					VALUES (
						'".$amount."',
						'".$payment_variables['first_name']."',
						'".$payment_variables['last_name']."',
						'".$payment_variables['email']."',
						'".$payment_variables['address']."',
						'".$payment_variables['country']."',
						'".$payment_variables['state']."',
						'".$payment_variables['city']."',
						'".$payment_variables['zip']."',
						'".$payment_variables['product_id']."',
						'".$payment_variables['txn_id']."',
						'".$payment_variables['level']."',
						'".$payment_variables['status']."',
						'".date('Y-m-d H:i:s')."'
					)";
				$res = $wpdb->query( $query );
				$pay_info_id = $wpdb->insert_id;
				do_action('id_payment_success', $pay_info_id);
				return $pay_info_id;
}
?>