<?php

//error_reporting(E_ALL);

//@ini_set('display_errors', 1);

/*
Plugin Name: MemberDeck
URI: http://MemberDeck.com
Description: A powerful, yet simple, content delivery system for WordPress. Features a widgetized dashboard so you can customize your product offerings, instant checkout, credits, and more.
Version: 1.2.5
Author: Virtuous Giant
Author URI: http://VirtuousGiant.com
License: GPL2
*/

define( 'MD_PATH', plugin_dir_path(__FILE__) );

global $memberdeck_db_version;
$memberdeck_db_version = "1.2.5";

include_once 'classes/class-id-member.php';
include_once 'classes/class-id-member-level.php';
include_once 'classes/class-id-member-download.php';
include_once 'classes/class-id-member-order.php';
include_once 'classes/class-id-member-credit.php';
include_once 'classes/class-id-member-metaboxes.php';
include_once 'classes/class-md-keys.php';
include_once 'classes/class-md-form.php';
include_once 'memberdeck-globals.php';
include_once 'idmember-admin.php';
$s3_enabled = md_s3_enabled();
if ($s3_enabled) {
	include_once MD_PATH.'lib/aws-config.php';
}
include_once 'idmember-functions.php';
include_once 'idmember-shortcodes.php';
include_once 'memberdeck-update.php';
if (function_exists('is_id_pro') && is_id_pro()) {
	$gateways = get_option('memberdeck_gateways');
	if (isset($gateways)) {
		$gateways = unserialize($gateways);
		if (isset($gateways['esc']) && $gateways['esc'] == 1) {
			include_once 'memberdeck-sc.php';
		}
	}
	include_once 'inc/memberdeck-ide.php';
}
global $crowdfunding;

function memberdeck_init() {
  load_plugin_textdomain( 'memberdeck', false, MD_PATH.'languages/' ); 
}
add_action('plugins_loaded', 'memberdeck_init');

// Let's determine whether we are installing on multisite or standard WordPress
// If multisite, we need to know whether we are network activated or activated on a per-site basis

if (is_multisite()) {
	// we only run this if we're network activating
	if (is_network_admin()) {
		register_activation_hook(__FILE__,'memberdeck_blog_install');
	}
	// we are not in network admin, so we run regular activation script
	else {
		memberdeck_install();
	}
}
else {
	// not multisite, standard install
	register_activation_hook(__FILE__,'memberdeck_install');
}

if (is_md_network_activated()) {
	// setup again when new blogs are added
	add_action('wpmu_new_blog', 'memberdeck_install', 1, 1);
}

function memberdeck_blog_install() {
	global $wpdb;
	$sql = 'SELECT * FROM '.$wpdb->base_prefix.'blogs';
	$res = $wpdb->get_results($sql);
	foreach ($res as $blog) {
		memberdeck_install($blog->blog_id);
	}
}

function memberdeck_install($blog_id = null) {
	global $wpdb;
	global $memberdeck_db_version;

	$prefix = md_wpdb_prefix($blog_id);

	// 
	$memberdeck_members = $prefix . "memberdeck_members";
    $sql = "CREATE TABLE " . $memberdeck_members . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					user_id MEDIUMINT(9) NOT NULL,
					access_level VARCHAR(250) NOT NULL,
					add_ons VARCHAR(250) NOT NULL,
					credits SMALLINT(2) NOT NULL,
					r_date DATETIME,
					reg_key VARCHAR(250) NOT NULL,
					data TEXT NOT NULL,
					UNIQUE KEY id (id));";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    update_option("memberdeck_db_version", $memberdeck_db_version);

    $memberdeck_key_assoc = $prefix . "memberdeck_key_assoc";
    $sql = "CREATE TABLE " . $memberdeck_key_assoc . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					user_id MEDIUMINT(9) NOT NULL,
					download_id MEDIUMINT(9) NOT NULL,
					assoc MEDIUMINT(9) NOT NULL,
					UNIQUE KEY id (id));";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    update_option("memberdeck_db_version", $memberdeck_db_version);

    $memberdeck_keys = $prefix . "memberdeck_keys";
    $sql = "CREATE TABLE " . $memberdeck_keys . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					license VARCHAR(250) NOT NULL,
					avail MEDIUMINT(9) NOT NULL,
					in_use MEDIUMINT(9) NOT NULL,
					UNIQUE KEY id (id));";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    update_option("memberdeck_db_version", $memberdeck_db_version);

    $memberdeck_levels = $prefix . "memberdeck_levels";
    $sql = "CREATE TABLE " . $memberdeck_levels . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					level_name VARCHAR(250) NOT NULL,
					level_price VARCHAR (250) NOT NULL,
					credit_value MEDIUMINT(9) NOT NULL,
					txn_type VARCHAR (250) NOT NULL DEFAULT 'capture',
					level_type VARCHAR(250) NOT NULL,
					recurring_type VARCHAR(250) NOT NULL DEFAULT 'NONE',
					plan VARCHAR(250),
					license_count MEDIUMINT(9),
					UNIQUE KEY id (id));";
    dbDelta($sql);

    $memberdeck_credits = $prefix . "memberdeck_credits";
    $sql = "CREATE TABLE " . $memberdeck_credits . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					credit_name VARCHAR(250) NOT NULL,
					credit_count MEDIUMINT(9) NOT NULL,
					credit_price VARCHAR (250) NOT NULL,
					credit_level MEDIUMINT(9) NOT NULL,
					UNIQUE KEY id (id));";
    dbDelta($sql);

    $memberdeck_downloads = $prefix . "memberdeck_downloads";
    $sql = "CREATE TABLE " . $memberdeck_downloads . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					download_name VARCHAR(250) NOT NULL,
					download_levels VARCHAR (250) NOT NULL,
					button_text VARCHAR (250) NOT NULL,
					download_link VARCHAR (250) NOT NULL,
					info_link VARCHAR (250) NOT NULL,
					doc_link VARCHAR (250) NOT NULL,
					image_link VARCHAR (250) NOT NULL,
					version VARCHAR(250) NOT NULL,
					position VARCHAR(250) NOT NULL,
					licensed TINYINT(1) NOT NULL,
					hidden TINYINT(1) NOT NULL,
					enable_s3 TINYINT(1) NOT NULL,
					enable_occ TINYINT(1) NOT NULL,
					occ_level MEDIUMINT(9) NOT NULL,
					id_project MEDIUMINT(9) NOT NULL,
					updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					UNIQUE KEY id (id));";
    dbDelta($sql);

    $memberdeck_orders = $prefix . "memberdeck_orders";
    $sql = "CREATE TABLE " . $memberdeck_orders . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					user_id MEDIUMINT( 9 ) NOT NULL,
					level_id MEDIUMINT( 9 ) NOT NULL,
					order_date DATETIME,
					transaction_id VARCHAR (250) NOT NULL,
					subscription_id VARCHAR (250) NOT NULL,
					e_date DATETIME,
					status VARCHAR (250) NOT NULL,
					UNIQUE KEY id (id));";
    dbDelta($sql);

     $memberdeck_preorders = $prefix . "memberdeck_preorder_tokens";
    $sql = "CREATE TABLE " . $memberdeck_preorders . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					order_id MEDIUMINT( 9 ) NOT NULL,
					charge_token VARCHAR (250) NOT NULL,
					gateway VARCHAR (250) NOT NULL,
					UNIQUE KEY id (id));";
    dbDelta($sql);

    $mdid_assignments = $prefix . "mdid_assignments";
    $sql = "CREATE TABLE " . $mdid_assignments . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					level_id BIGINT(20) NOT NULL,
					project_id BIGINT(20) NOT NULL,
					assignment_id BIGINT(20) NOT NULL,
					UNIQUE KEY id (id));";
    dbDelta($sql);

    $project_levels = $prefix . "mdid_project_levels";
    $sql = "CREATE TABLE " . $project_levels . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					levels VARCHAR(255) NOT NULL,
					UNIQUE KEY id (id));";
    dbDelta($sql);

    $mdid_orders = $prefix . "mdid_orders";
	$sql = "CREATE TABLE " . $mdid_orders . " (
		id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
		customer_id VARCHAR(255) NOT NULL,
		subscription_id VARCHAR(255),
		order_id BIGINT(20),
		pay_info_id BIGINT(20) NOT NULL,
		UNIQUE KEY id (id));";
	dbDelta($sql);

	$md_sc_params = $prefix . "memberdeck_sc_params";
	$sql = "CREATE TABLE " . $md_sc_params . " (
		id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
		user_id MEDIUMINT(9) NOT NULL,
		access_token VARCHAR(255) NOT NULL,
		refresh_token VARCHAR(255) NOT NULL,
		stripe_publishable_key VARCHAR(255) NOT NULL,
		stripe_user_id VARCHAR(255) NOT NULL,
		UNIQUE KEY id (id));";
	dbDelta($sql);

    $reg = array(
    	'menu_order' => 100,
    	'comment_status' => 'closed',
    	'ping_status' => 'closed',
    	'post_name' => 'membership-registration',
    	'post_status' => 'publish',
    	'post_title' => 'Membership Registration',
    	'post_type' => 'page');

    $db = array(
    	'menu_order' => 100,
    	'comment_status' => 'closed',
    	'ping_status' => 'closed',
    	'post_name' => 'dashboard',
    	'post_status' => 'publish',
    	'post_title' => 'Dashboard',
    	'post_type' => 'page',
    	'post_content' => '[memberdeck_dashboard]');

    $get_reg = get_page_by_title('Membership Registration');
    $get_db = get_page_by_title('Dashboard');

    if (empty($get_reg)) {
    	$post_in = wp_insert_post($reg);
	    if (isset($wp_error)) {
	    	echo $wp_error;
	    }
    }
    if (empty($get_db)) {
    	$post_in = wp_insert_post($db);
	    if (isset($wp_error)) {
	    	echo $wp_error;
	    }
    }
}

// prepare deletion hooks
if (is_md_network_activated()) {
	add_action('delete_blog', 'memberdeck_uninstall', 1, 1);
	register_uninstall_hook(__FILE__,'md_remove_all_traces');
}
else {
	register_uninstall_hook(__FILE__, 'memberdeck_uninstall');
}

function md_remove_all_traces() {
	global $wpdb;
	$sql = 'SELECT * FROM '.$wpdb->base_prefix.'blogs';
	$res = $wpdb->get_results($sql);
	foreach ($res as $blog) {
		memberdeck_uninstall($blog->blog_id);
	}
}

function memberdeck_uninstall($blog_id = null) {
	global $wpdb;
	// once again, check for type of install and get proper prefixes
	$prefix = md_wpdb_prefix($blog_id);

	$sql = 'DROP TABLE IF EXISTS '.$prefix.'memberdeck_members, '.$prefix.'memberdeck_levels, '.$prefix
	.'memberdeck_credits, '.$prefix.'memberdeck_downloads, '.$prefix.'memberdeck_orders, '.$prefix.'memberdeck_preorder_tokens, '.$prefix
	.'mdid_assignments, '.$prefix.'mdid_project_levels, '.$prefix.'mdid_orders, '.$prefix.'memberdeck_keys, '.$prefix.'memberdeck_key_assoc, '.$prefix
	.'memberdeck_sc_params';
	$option = get_option('testme');
	update_option('testme', $option.', '.$sql);
	$res = $wpdb->query($sql);
	delete_option('memberdeck_gateways');
	delete_option('md_dash_settings');
	delete_option('md_receipt_settings');
}

global $crowdfunding;

function memberdeck_styles() {
	wp_register_script('memberdeck-js', plugins_url('js/idmember.js', __FILE__));
	wp_register_style('memberdeck', plugins_url('css/style.css', __FILE__));
	wp_register_style('font-awesome', "//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
	wp_enqueue_script('jquery');
	wp_enqueue_script('memberdeck-js');
	wp_enqueue_style('font-awesome');
	wp_register_script('stripe', 'https://js.stripe.com/v1/');
	wp_register_script('balanced', 'https://js.balancedpayments.com/v1/balanced.js');
	$ajaxurl = site_url('/wp-admin/admin-ajax.php');
	$pluginsurl = plugins_url('', __FILE__);
	$siteurl = site_url();
	$durl = md_get_durl();
	$settings = get_option('memberdeck_gateways');
	$test = '0';
	if (!empty($settings)) {
		$settings = unserialize($settings);
		if (is_array($settings)) {
			if (isset($settings['test'])) {
				$test = (string)$settings['test'];
			}
			else {
				$test = '0';
			}
			if (isset($settings['es'])) {
				$es = $settings['es'];
			}
			else {
				$es = '0';
			}
			if (isset($settings['esc'])) {
				$esc = $settings['esc'];
			}
			else {
				$esc = '0';
			}
			if (isset($settings['epp'])) {
				$epp = $settings['epp'];
			}
			else {
				$epp = '0';
			}
			if (isset($settings['eb'])) {
				$eb = $settings['eb'];
			}
			else {
				$eb = '0';
			}
			global $post;
			if (isset($post)) {
				if (strpos($post->post_content, 'memberdeck_checkout') || isset($_GET['mdid_checkout'])) {
					if ($es == '1') {
						wp_enqueue_script('stripe');
					}
					if ($eb == '1') {
						wp_enqueue_script('balanced');
					}
				}
			}

			if ($es == '1') {
				wp_localize_script( 'memberdeck-js', 'memberdeck_es', '1');
				$pk = $settings['pk'];
				$tpk = $settings['tpk'];
				if ($test == '1') {
					wp_localize_script( 'memberdeck-js', 'memberdeck_pk', $tpk);
				}
				else {
					wp_localize_script( 'memberdeck-js', 'memberdeck_pk', $pk);
				}
			}
			else {
				wp_localize_script( 'memberdeck-js', 'memberdeck_es', '0');
			}
			if ($esc == '1') {
				wp_register_style('sc_buttons', plugins_url('/lib/connect-buttons.css', __FILE__));
				wp_enqueue_style('sc_buttons');
			}
			if ($epp == '1') {
				wp_localize_script( 'memberdeck-js', 'memberdeck_epp', '1');
				$pp_email = $settings['pp_email'];
				$test_email = $settings['test_email'];
				if ($test == '1') {
					wp_localize_script('memberdeck-js', 'memberdeck_pp', $test_email);
					wp_localize_script('memberdeck-js', 'memberdeck_paypal', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
				}
				else {
					wp_localize_script('memberdeck-js', 'memberdeck_pp', $pp_email);
					wp_localize_script('memberdeck-js', 'memberdeck_paypal', 'https://www.paypal.com/cgi-bin/webscr');
				}
			}
			else {
				wp_localize_script( 'memberdeck-js', 'memberdeck_epp', '0');
			}
			if ($eb == '1') {
				wp_localize_script( 'memberdeck-js', 'memberdeck_eb', '1');
			}
			else {
				wp_localize_script( 'memberdeck-js', 'memberdeck_eb', '0');
			}
			wp_localize_script('memberdeck-js', 'memberdeck_testmode', $test);
		}
	}
	else {
		wp_localize_script( 'memberdeck-js', 'memberdeck_epp', '0');
		wp_localize_script( 'memberdeck-js', 'memberdeck_es', '0');
		wp_localize_script( 'memberdeck-js', 'memberdeck_eb', '0');
	}
	wp_localize_script( 'memberdeck-js', 'memberdeck_ajaxurl', $ajaxurl );
	wp_localize_script( 'memberdeck-js', 'memberdeck_siteurl', $siteurl );
	wp_localize_script( 'memberdeck-js', 'memberdeck_pluginsurl', $pluginsurl );
	wp_localize_script( 'memberdeck-js', 'memberdeck_durl', $durl);
	wp_enqueue_style('memberdeck');
};

add_action('wp_enqueue_scripts', 'memberdeck_styles');

function memberdeck_webhook_listener() {
	global $crowdfunding;
	if (isset($_POST)) {
		//$log = fopen('idmlog.txt', 'a+');
		ini_set('post_max_size', '12M');
		if (isset($_GET['memberdeck_notify']) && $_GET['memberdeck_notify'] == 'pp') {
			global $wpdb;
			// need to generate a secure key
			// need to redirect them tto registration url with that key
			//$key = md5(strtotime('now'), 

			$vars = array();

			$payment_complete = false;
			$status = null;
			
			foreach($_POST as $key=>$val) {
	           	$data = array($key => $val);

	            $vars[$key] = $val;
	            //fwrite($log, $key.' = '.$val."\n");
				if ($key == "payment_status" && strtoupper($val) == "COMPLETED") {
	                $payment_complete = true;
	                //fwrite($log, 'complete'."\n");
	            }
	            else if ($key = 'txn_type' && strtoupper($val) == 'SUBSCR_CANCEL') {
	            	$subscription_cancel = true;
	            }

	            else if ($key = 'txn_type' && strtoupper($val) == 'NEW_CASE') {
	            	if (strtoupper($vars['case_type']) == 'COMPLAINT') {
	            		$dispute = true;
	            	}
	            }
	        }
	        if ($payment_complete) {
	        	// lets get our vars
	            $fname = $vars['first_name'];
	            $lname = $vars['last_name'];
	            $price = $vars['payment_gross'];
	            $payer_email = $vars['payer_email'];
	            $email = $_GET['email'];
	            $product_id = $vars['item_number'];
	            $ipn_id = $vars['ipn_track_id'];
	            $txn_id = $vars['txn_id'];
	            $level = ID_Member_Level::get_level($product_id);

	            if (isset($vars['txn_type']) && $vars['txn_type'] == 'subscr_payment') {
	            	$recurring = true;
	            	$sub_id = $vars['subscr_id'];
	            	//fwrite($log, 'sub id: '.$sub_id."\n");
	            }
	            else {
	            	$recurring = false;
	            }

	            $access_levels = array(absint($product_id));
	            //fwrite($log, 'id: '.$product_id."\n");
	            //fwrite($log, $email."\n");
	            // now we need to see if this user exists in our db
	            $check_user = ID_Member::check_user($email);
	            //fwrite($log, serialize($check_user)."\n");
	            if (!empty($check_user)) {
	        		//fwrite($log, 'user exists'."\n");
	            	// now we know this user exists we need to see if he is a current ID_Member
	            	$user_id = $check_user->ID;
	            	$match_user = ID_Member::match_user($user_id);
	            	if (!isset($match_user)) {
	            		//fwrite($log, 'first purchase'."\n");
	            		// not a member, this is their first purchase
	            		if ($recurring == true) {
	            			$recurring_type = $level->recurring_type;
	            			if ($recurring_type == 'weekly') {
	            				// weekly
	            				$exp = strtotime('+1 week');
	            			}
	            			else if ($recurring_type == 'monthly') {
	            				// monthly
	            				$exp = strtotime('+1 month');
	            			}
	            			else {
	            				// annually
	            				$exp = strtotime('+1 years');
								
	            			}
	            			$e_date = date('Y-m-d h:i:s', $exp);
	            			$data = array('ipn_id' => $ipn_id, 'sub_id' => $sub_id);
	            		}
	            		else if ($level->e_date == 'lifetime') {
	            			$e_date = null;
	            		}
	            		else {
	            			$exp = strtotime('+1 years');
							$e_date = date('Y-m-d h:i:s', $exp);
	            			$data = array('ipn_id' => $ipn_id);
	            		}
	            		

	            		$user = array('user_id' => $user_id, 'level' => $access_levels, 'data' => $data);
						$new = ID_Member::add_user($user);
						$order = new ID_Member_Order(null, $user_id, $product_id, null, $txn_id, $sub_id, 'active', $e_date);
						$new_order = $order->add_order();
	            	}

	            	else {
	            		//fwrite($log, 'more than one purchase'."\n");
	            		// is a member, we need to push new data to their info table
	            		if (isset($match_user->access_level)) {
	            			$levels = unserialize($match_user->access_level);
	            			foreach ($levels as $key['val']) {
								$access_levels[] = absint($key['val']);
							}
	            		}

	            		if (isset($match_user->data)) {
	            			$data = unserialize($match_user->data);
	            			if (!is_array($data)) {
	            				$data = array($data);
	            			}
	            			if ($recurring == true) {
	            				$recurring_type = $level->recurring_type;
		            			if ($recurring_type == 'weekly') {
		            				// weekly
		            				$exp = strtotime('+1 week');
		            			}
		            			else if ($recurring_type == 'monthly') {
		            				// monthly
		            				$exp = strtotime('+1 month');
		            			}
		            			else {
		            				// annually
		            				$exp = strtotime('+1 years');
									
		            			}
		            			$e_date = date('Y-m-d h:i:s', $exp);
	            				$data[] = array('ipn_id' => $ipn_id, 'sub_id' => $sub_id);
	            			}
	            			else if ($level->level_type == 'lifetime') {
	            				$e_date = null;
	            			}
	            			else {
	            				$exp = strtotime('+1 years');
								$e_date = date('Y-m-d h:i:s', $exp);
	            				$data[] = array('ipn_id' => $ipn_id);
	            			}
	            		}
	            		else {
	            			if ($recurring == true) {
	            				$recurring_type = $level->recurring_type;
		            			if ($recurring_type == 'weekly') {
		            				// weekly
		            				$exp = strtotime('+1 week');
		            			}
		            			else if ($recurring_type == 'monthly') {
		            				// monthly
		            				$exp = strtotime('+1 month');
		            			}
		            			else {
		            				// annually
		            				$exp = strtotime('+1 years');
									
		            			}
		            			$e_date = date('Y-m-d h:i:s', $exp);
	            				$data[] = array('ipn_id' => $ipn_id, 'sub_id' => $sub_id);
	            			}
	            			else if ($level->e_date == 'lifetime') {
	            				$e_date = null;
	            			}
	            			else {
	            				$exp = strtotime('+1 years');
								$e_date = date('Y-m-d h:i:s', $exp);
	            				$data[] = array('ipn_id' => $ipn_id);
	            			}
	            		}

						$user = array('user_id' => $user_id, 'level' => $access_levels, 'data' => $data);
						$new = ID_Member::update_user($user);
						//fwrite($log, $user_id);
						$order = new ID_Member_Order(null, $user_id, $product_id, null, $txn_id, $sub_id, 'active', $e_date);
						$new_order = $order->add_order();
	            	}
	            }
	            else {
	            	//fwrite($log, 'new user: '."\n");
	            	// user does not exist, we must create them
	            	// gen random pw they can change later
	            	$pw = idmember_pw_gen();
	            	// gen our user input
	            	$userdata = array('user_pass' => $pw,
	            		'first_name' => $fname,
	            		'last_name' => $lname,
	            		'user_login' => $email,
	            		'user_email' => $email,
	            		'display_name' => $fname);
	            	//fwrite($log, serialize($userdata));
	            	// insert user into WP db and return user id
	            	$user_id = wp_insert_user($userdata);
	            	//fwrite($log, $user_id."\n");
	            	// now add user to our member table
	            	if ($recurring == true) {
	            		$recurring_type = $level->recurring_type;
	            		//fwrite($log, 'recurring type: '.$recurring_type."\n");
            			if ($recurring_type == 'weekly') {
            				// weekly
            				$exp = strtotime('+1 week');
            			}
            			else if ($recurring_type == 'monthly') {
            				// monthly
            				$exp = strtotime('+1 month');
            			}
            			else {
            				// annually
            				$exp = strtotime('+1 years');
							
            			}
            			$e_date = date('Y-m-d h:i:s', $exp);
						$data = array('ipn_id' => $ipn_id, 'sub_id' => $sub_id);
	            	}
	            	else if ($level->e_date == 'lifetime') {
            			$e_date = null;
            		}
	            	else {
	            		$exp = strtotime('+1 years');
						$e_date = date('Y-m-d h:i:s', $exp);
	            		$data = array('ipn_id' => $ipn_id);
	            	}
	            	//fwrite($log, 'exp: '.$exp."\n");
	            	$reg_key = md5($email.time());
	            	$user = array('user_id' => $user_id, 'level' => $access_levels, 'reg_key' => $reg_key, 'data' => $data);
					$new = ID_Member::add_paypal_user($user);
					//fwrite($log, $new."\n");
					$order = new ID_Member_Order(null, $user_id, $product_id, null, $txn_id, $sub_id, 'active', $e_date);
					$new_order = $order->add_order();
					//fwrite($log, 'order added: '.$new_order."\n");
					do_action('idmember_registration_email', $user_id, $reg_key);
	            }
	            // we need to pass any extra post fields set during checkout
	            if (isset($_GET)) {
	            	$fields = $_GET;
	            }
	            else {
	            	$fields = array();
	            }
	            if (empty($reg_key)) {
	            	$reg_key = '';
	            }
	            //
	            if ($crowdfunding) {
	            	if (isset($fields['memberdeck_notify']) && $fields['memberdeck_notify'] == 'pp') {
						if (isset($fields['mdid_checkout'])) {
							$mdid_checkout = $fields['mdid_checkout'];
						}
						if (isset($fields['project_id'])) {
							$project_id = $fields['project_id'];
						}
						if (isset($fields['project_level'])) {
							$proj_level = $fields['project_level'];
						}
						$order = new ID_Member_Order($new_order);
						$order_info = $order->get_order();
						$created_at = $order_info->order_date;
						$pay_id = mdid_insert_payinfo($fname, $lname, $email, $project_id, $txn_id, $proj_level, $price, $status, $created_at);
					}
				}	
	            //
	            do_action('memberdeck_payment_success', $user_id, $new_order, $reg_key, $fields);
	            //fwrite($log, 'user added');
	        }
	        else if (isset($subscription_cancel) && $subscription_cancel == true) {
	        	$sub_id = $vars['subscr_id'];
	        	//fwrite($log, 'subscription cancelled with id: '.$sub_id."\n");
	        	$order = new ID_Member_Order(null, null, null, null, null, $sub_id);
	        	$sub_data = $order->get_subscription($sub_id);
	        	if (!empty($sub_data)) {
	        		//fwrite($log, $sub_data->user_id."\n");
	        		$sub_id = $sub_data->subscription_id;
	        		$level_to_drop = $sub_data->level_id;
	        		$user_id = $sub_data->user_id;
	        		$match_user = ID_Member::match_user($user_id);
	        		if (isset($match_user)) {
	        			$level_array = unserialize($match_user->access_level);
	        			$key = array_search($level_to_drop, $level_array);
	        			unset($level_array[$key]);
	        			$cancel = ID_Member_Order::cancel_subscription($sub_data->id);
	        			//fwrite($log, $cancel);
	        			$data = unserialize($match_user->data);
	        			$i = 0;
	        			foreach ($data as $record) {
	        				//fwrite($log, 'record'."\n");
	        				foreach ($record as $key=>$value) {
	        					//fwrite($log, $key."\n");
	        					//fwrite($log, $value."\n");
		        				if ($value == $sub_id) {
	        						//fwrite($log, 'value = sub id'."\n");
	        						$record_id = $i;
	        						//fwrite($log, $record_id);
	        					}
	        				}
        					$i++;
	        			}
	        			if (isset($record_id)) {
	        				$cut_data = $data[$record_id];
	        				$cut_data['cancel_date'] = date('Y-m-d h:i:s');
	        				unset($data[$record_id]);
	        				$data[] = $cut_data;
	        			}
	        			$data = serialize($data);
	        			$access_level = serialize($level_array);
	        			//fwrite($log, $data."\n");
						//fwrite($log, $access_level."\n");
	        			$user = array('user_id' => $user_id, 'level' => $access_level, 'data' => $data);
	        			$update_user = ID_Member::update_user($user);
	        		}
	        	}
	        }
	        else if (isset($dispute) && $dispute == true) {
	        	$txn_id = $vars['txn_id'];
	        	$order = new ID_Member_Order(null, null, null, null, $txn_id);
	        	$transaction = $order->get_transaction();
	        	if (!empty($transaction->subscription_id)) {
	        		$sub_id = $transaction->subscription_id;
	        		$level_to_drop = $transaction->level_id;
	        		$user_id = $transaction->user_id;
	        		$match_user = ID_Member::match_user($user_id);
	        		if (isset($match_user)) {
	        			$level_array = unserialize($match_user->access_level);
	        			$key = array_search($level_to_drop, $level_array);
	        			unset($level_array[$key]);
	        			$cancel = ID_Member_Order::cancel_subscription($transaction->id);
	        			//fwrite($log, $cancel);
	        			$data = unserialize($match_user->data);
	        			$i = 0;
	        			foreach ($data as $record) {
	        				foreach ($record as $key=>$value) {
		        				if ($value == $sub_id) {
	        						$record_id = $i;
	        					}
	        				}
        					$i++;
	        			}
	        			if (isset($record_id)) {
	        				$cut_data = $data[$record_id];
	        				$cut_data['dispute_date'] = date('Y-m-d h:i:s');
	        				unset($data[$record_id]);
	        				$data[] = $cut_data;
	        			}
	        			$data = serialize($data);
	        			$access_level = serialize($level_array);
	        			$user = array('user_id' => $user_id, 'level' => $access_level, 'data' => $data);
	        			$update_user = ID_Member::update_user($user);
	        		}
	        	}
	        	else {
	        		// not a subscription, but a regular purchase
	        		$level_to_drop = $transaction->level_id;
	        		$user_id = $transaction->user_id;
	        		$match_user = ID_Member::match_user($user_id);
	        		if (isset($match_user)) {
	        			$level_array = unserialize($match_user->access_level);
	        			$key = array_search($level_to_drop, $level_array);
	        			unset($level_array[$key]);
	        			$cancel = ID_Member_Order::cancel_subscription($transaction->id);
	        			$data = unserialize($match_user->data);
	        			$data['dispute_date'] = date('Y-m-d h:i:s');
	        			$data = serialize($data);
	        			$access_level = serialize($level_array);
	        			$user = array('user_id' => $user_id, 'level' => $access_level, 'data' => $data);
	        			$update_user = ID_Member::update_user($user);
	        		}
	        	}
	        }
		}
		else if (isset($_GET['memberdeck_notify']) && $_GET['memberdeck_notify'] == 'stripe') {
			//fwrite($log, 'inside stripe'."\n");

			$json = @file_get_contents('php://input');
			//fwrite($log, $json."\n");

			$object = json_decode($json);
			//fwrite($log, $object->type."\n");
			if ($object->type == 'invoice.payment_succeeded') {
				$data = $object->data;
				$txn_id = $data->object->charge;
				//fwrite($log, $txn_id."\n");
				$customer = $data->object->customer;
				//fwrite($log, $customer."\n");
				$plan = $data->object->lines->data[0]->plan->id;
				$start = $data->object->lines->data[0]->period->start;
				//fwrite($log, 'start: '.$start."\n");
				//fwrite($log, $plan."\n");
				if (isset($customer)) {
					$member = ID_Member::get_customer_data($customer);
					$user_id = $member->user_id;
					$userdata = get_userdata($user_id);
					$user_email = $userdata->user_email;
					//fwrite($log, $user_id."\n");
					if (isset($user_id)) {
						$txn_check = ID_Member_Order::check_order_exists($txn_id);
						if (empty($txn_check)) {
							//fwrite($log, 'check is empty'."\n");
							$product_id = ID_Member_Level::get_level_by_plan($plan);
							//fwrite($log, $product_id."\n");
							$level = ID_Member_Level::get_level($product_id);
							$recurring_type = $level->recurring_type;
							if ($recurring_type == 'weekly') {
								// weekly
								$exp = strtotime('+1 week');
							}
							else if ($recurring_type == 'monthly') {
								// monthly
								$exp = strtotime('+1 month');
							}
							else {
								// annually
								$exp = strtotime('+1 years');
							}
							$e_date = date('Y-m-d h:i:s', $exp);
							//fwrite($log, $e_date);
							$paykey = md5($user_email.time());
							$order = new ID_Member_Order(null, $user_id, $product_id, null, $txn_id, $plan, 'active', $e_date);
							$new_order = $order->add_order();
							//fwrite($log, 'new order: '.$new_order."\n");
							// we need to pass any extra post fields set during checkout
							if (isset($_GET)) {
								$fields = $_GET;
							}
							else {
								$fields = array();
							}
							//
							if ($crowdfunding) {
								$user_meta = get_user_meta($user_id);
								$fname = $user_meta['first_name'][0]; // var
								$lname = $user_meta['last_name'][0]; // var
								$price = $level->level_price; // var
								$order = new ID_Member_Order($new_order);
								$the_order = $order->get_order();
								$created_at = $the_order->order_date; // var
								// txn id is null, so this won't work fug
								$check = mdid_start_check($start);
								//fwrite($log, serialize($check)."\n");
								if (!empty($check)) {
									// this is the first payment, pay id and mdid order are already set. time to update.
									$pay_id = $check->pay_info_id;
									//fwrite($log, 'pay id: '.$pay_id."\n");
									if (isset($pay_id)) {
										$mdid_order = mdid_payid_check($pay_id);
										if (isset($mdid_order)) {
											//fwrite($log, 'mdid order id: '.$mdid_order->id."\n");
											mdid_transaction_to_order($mdid_order->id, $txn_id);
											mdid_payinfo_transaction($pay_id, $txn_id);
										}
									}
								}
								else {
									// this is 2+ payments
									$order_check = mdid_order_by_customer_plan($customer, $plan);
									if (!empty($order_check)) {
										$pay_info = $order_check->pay_info_id;
										if (isset($pay_info_id)) {
											$id_order = getOrderById($pay_info_id);
											if (isset($id_order)) {
												$project_id = $id_order->product_id;
												$proj_level = $id_order->product_level;
												$pay_id = mdid_insert_payinfo($fname, $lname, $user_email, $project_id, $txn_id, $proj_level, $price, 'C', $created_at);
												$mdid_order = mdid_insert_order($customer, $pay_id, null, $plan);
												do_action('id_payment_success', $pay_id);
											}
										}
									}
								}
								//
							}
							//
							do_action('memberdeck_payment_success', $user_id, $new_order, $paykey, $fields);
							do_action('memberdeck_stripe_success', $user_id);
							do_action('idmember_receipt', $user_id, $level->level_price, $product_id);
						}
					}
					
				}
			}
		}
		else if (isset($_GET['reg']) && $_GET['reg'] !== '') {
			$reg_key = $_GET['reg'];
			$user = ID_Member::retrieve_user_key($reg_key);
			//print_r($user);
			// maybe do some sort of email verification here
			if (!empty($user)) {
				$userdata = get_userdata($user->user_id);
				$url = home_url('/membership-registration').'?email='.urlencode($userdata->user_email).'&key_valid='.$reg_key;
				echo '<script>location.href="'.$url.'";</script>';
			}
		}
		else if (isset($_GET['ppsuccess']) && $_GET['ppsuccess'] == 1) {
			$settings = get_option('memberdeck_gateways');
			if (!empty($settings)) {
				$settings = unserialize($settings);
				if (is_array($settings) && isset($settings['paypal_redirect'])) {
					$url = $settings['paypal_redirect'];
					if (empty($url)) {
						$url = site_url();
					}
					header('Location: '.$url);
				}
			}
		}
		//fclose($log);
	}
}

add_action('init', 'memberdeck_webhook_listener');

add_action('init', 'memberdeck_disable_autop', 1);

function memberdeck_disable_autop() {
	if (isset($_GET['action']) && $_GET['action'] == 'register') {
		remove_filter('the_content', 'wpautop');
	}
	else if (isset($_GET['key_valid']) && isset($_GET['email'])) {
		remove_filter('the_content', 'wpautop');
	}
}

add_filter('the_content', 'idmember_registration_form', 1);

function idmember_registration_form($content) {
	if (isset($_GET['key_valid']) && isset($_GET['email'])) {
		$reg_key = $_GET['key_valid'];
		$email = urldecode($_GET['email']);
		$user = ID_Member::retrieve_user_key($reg_key);
		$member = new ID_Member();
		$check_user = $member->check_user($email);

		if (isset($user) && isset($check_user) && $check_user->ID == $user->user_id) {
			$valid = true;
		}
		else {
			$valid = false;
		}
		if ($valid == true) {
			ob_start();
			$user_id = $user->user_id;
			$current_user = get_userdata($user_id);
			$user_firstname = $current_user->user_firstname;
			$user_lastname = $current_user->user_lastname;
			$extra_fields = null;
			include_once 'templates/_regForm.php';
			$content = ob_get_contents();
			ob_end_clean();
			do_action('memberdeck_reg_form', $user_id);
			return $content;
		}
		else {
			$dash = get_option('md_dash_settings');
			if (!empty($dash)) {
				$dash = unserialize($dash);
				if (isset($durl)) {
					$durl = $dash['durl'];
				}
				else {
					$durl = home_url('/dashboard');
				}
			}
            echo '<script>window.location="'.$durl.'";</script>';
		}
	}
	else if (isset($_GET['action']) && $_GET['action'] == 'register') {
		if (!is_user_logged_in()) {
			ob_start();
			include_once 'templates/_regForm.php';
			$content = ob_get_contents();
			ob_end_clean();
		}
		else {
			$dash = get_option('md_dash_settings');
			if (!empty($dash)) {
				$dash = unserialize($dash);
				if (isset($durl)) {
					$durl = $dash['durl'];
				}
				else {
					$durl = home_url('/dashboard');
				}
			}
            echo '<script>window.location="'.$durl.'";</script>';
		}
	}
	return $content;
}

add_action('init', 'md_export_handler');

function md_export_handler() {
	global $phpmailer;
	print_r($phpmailer);
	if (isset($_POST['export_customers'])) {
		$force_download = ID_Member::export_members();
	}
}

function md_s3_enabled() {
	// a function to see if any downloads are using S3
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_downloads WHERE enable_s3 = %d LIMIT 1', absint(1));
	$res = $wpdb->get_row($sql);
	if (!empty($res)) {
		return true;
	}
	else {
		return false;
	}
}
?>