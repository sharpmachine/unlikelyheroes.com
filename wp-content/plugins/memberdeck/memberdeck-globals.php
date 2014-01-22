<?php

// Declare MD Global Variables

/*
1. Crowdfunding Enabled?
*/
$crowdfunding = false;
$general = get_option('md_receipt_settings');
if (isset($general)) {
	$general = unserialize($general);
	if (isset($general['crowdfunding'])) {
		if ($general['crowdfunding'] == 1) {
			$crowdfunding = true;
		}
		else {
			$crowdfunding = false;
		}
	}
}

/*
2. Customer ID
*/

/*if (isset($user_id)) {
	$member = new ID_Member();
	$match = $member->match_user($user_id);
	if (isset($match->data)) {
		$data = unserialize($match->data);
		if (is_array($data)) {
			foreach ($data as $item) {
				if (is_array($item)) {
					foreach ($item as $k=>$v) {
						if ($k == 'customer_id') {
							$customer_id = $v;
							break 2;
						}
					}
				}	
			}
		}
	}
}*/

/*
3. Balanced Customer ID
*/

/*if (isset($user_id)) {
	$balanced_customer_id = get_user_meta($user_id, 'balanced_customer_id', true);
}*/

/*
4. Credits Available
*/

/*if (isset($user_id)) {
	$member = new ID_Member($user_id);
	$md_credits = $member->get_user_credits();
}*/

/*
5. Instant Checkout
*/

/*$instant_checkout = false;
$gateways = get_option('memberdeck_gateways');
if (!empty($gateways)) {
	$settings = unserialize($gateways);
	if (isset($settings['es']) && $settings['es'] == 1) {
		if (!empty($customer_id)) {
			$instant_checkout = get_user_meta($user_id, 'instant_checkout', true);
		}
	}
	else if (isset($settings['eb']) && $settings['eb'] == 1) {
		if (!empty($balanced_customer_id)) {
			$instant_checkout = get_user_meta($user_id, 'instant_checkout', true);
		}
	}
}*/

/*
6. Default Timezone
*/

$tz = get_option('timezone_string');

/*
7. S3
*/
$s3 = 0;
$general = get_option('md_receipt_settings');
if (!empty($general)) {
	$settings = unserialize($general);
	if (is_array($settings)) {
		if (isset($settings['s3'])) {
			$s3 = $settings['s3'];
		}
		else {
			$s3 = 0;
		}
	}
}

?>