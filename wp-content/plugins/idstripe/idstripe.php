<?php

//error_reporting(E_ALL);

//@ini_set('display_errors', 1);

/*
Plugin Name: IgnitionDeck Stripe Extension
URI: http://ignitiondeck.com
Description: Enables IgnitionDeck users to accept payments via Stripe. Users must have an account at http://stripe.com
Version: 1.1.4
Author: Virtuous Giant
Author URI: http://VirtuousGiant.com
License: GPL2
*/

register_activation_hook(__FILE__,'idstripe_install');
global $idstripe_db_version;
$idstripe_db_version = "1.1.4";

function idstripe_install() {
	global $wpdb;
	global $idstripe_db_version;
	// Payment settings for Stripe Payment Gateway
	$stripe_pay_settings = $wpdb->prefix . "ign_stripe_settings";
    $sql = "CREATE TABLE " . $stripe_pay_settings . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					currency VARCHAR(255) NOT NULL DEFAULT 'USD',
					fund_type VARCHAR(255) NOT NULL,
					api_key VARCHAR (100) NOT NULL,
					stripe_publishable_key VARCHAR (100) NOT NULL,
					sandbox_api_key VARCHAR (100) NOT NULL,
					sandbox_publishable_key VARCHAR (100) NOT NULL,
					sandbox_mode ENUM('sandbox','production') NOT NULL,
					disable_pp int(1) NOT NULL,
					UNIQUE KEY id (id));";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    update_option("idstripe_db_version", $idstripe_db_version);

    $stripe_user_settings = $wpdb->prefix . "ign_stripe_users";
    $sql = "CREATE TABLE " . $stripe_user_settings . " (
					id MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT,
					customer_id VARCHAR(255) NOT NULL,
					pay_info_id VARCHAR(255) NOT NULL,
					UNIQUE KEY id (id));";
    dbDelta($sql);
}
include_once 'idstripe-functions.php';
//include_once 'inc/stripeConnect.php';
define ('IDSTRIPE_PATH', plugin_dir_path(__FILE__));

function idstripe_init() {
  load_plugin_textdomain( 'memberdeck', false, IDSTRIPE_PATH.'languages/' ); 
}
add_action('plugins_loaded', 'idstripe_init');

// This function adds the admin menu to the IgnitionDeck menu
add_action('id_submenu', 'idstripe_menu');

function idstripe_menu() {
	$settings = add_submenu_page( 'ignitiondeck', __('Stripe Settings', 'idstripe'), __('Stripe Settings', 'idstripe'), 'manage_options', 'stripe-settings', 'idstripe_settings');
	add_action('admin_print_styles-'.$settings, 'idstripe_admin_scripts');
}

function idstripe_admin_scripts() {
	wp_register_script('idstripe-admin', plugins_url('/js/idstripe-admin.js', __FILE__));
	wp_register_style('idstripe-admin', plugins_url('/admin-style.css', __FILE__));
	wp_enqueue_script('idstripe-admin');
	wp_enqueue_style('idstripe-admin');
}

function idstripe_front_css() {
	wp_register_style('idstripe-front', plugins_url('/style.css', __FILE__));
	wp_enqueue_style('idstripe-front');
}

add_action('wp_enqueue_scripts', 'idstripe_front_css');
add_action('admin_enqueue_scripts', 'idstripe_front_css');

function idstripe_front_js() {
	wp_register_script('idstripe', plugins_url('/js/idstripe.js', __FILE__));
	$payment_settings = get_idstripe_settings();
    if (empty($payment_settings)) {
    	$idstripe_ajax_url = str_replace('http://', 'https://', site_url('/').'wp-admin/admin-ajax.php');
    }
    else {
    	if ($payment_settings->sandbox_mode == 'production') {
    		$idstripe_ajax_url = str_replace('http://', 'https://', site_url('/').'wp-admin/admin-ajax.php');
    	}
    	else {
    		$idstripe_ajax_url = site_url('/').'wp-admin/admin-ajax.php';
    	}
    }
	
	global $post;
	if (isset($post)) {
		// if (stripos($post->post_content, 'purchase_form')) {
        if (has_shortcode($post->post_content, 'project_purchase_form')) {
			wp_register_script('stripe', 'https://js.stripe.com/v1/');
			wp_enqueue_script('stripe');
		}
        else if (isset($_GET['purchaseform'])) {
            wp_register_script('stripe', 'https://js.stripe.com/v1/');
            wp_enqueue_script('stripe');
        }
	}
    else if (isset($_GET['purchaseform'])) {
        wp_register_script('stripe', 'https://js.stripe.com/v1/');
        wp_enqueue_script('stripe');
    }
	wp_enqueue_script('jquery');
	wp_enqueue_script('idstripe');
	wp_localize_script('idstripe', 'idstripe_ajax_url', $idstripe_ajax_url);
}

add_action('wp_enqueue_scripts', 'idstripe_front_js');

// This function handles the IDStripe admin menu
function idstripe_settings() {
    global $wpdb;
    $payment_settings = get_idstripe_settings();
    if (empty($payment_settings)) {
    	$empty = true;
    	$payment_settings->api_key = '';
    	$payment_settings->stripe_publishable_key = '';
    	$payment_settings->sandbox_api_key = '';
    	$payment_settings->sandbox_publishable_key = '';
    	$payment_settings->sandbox_mode = 'production';
    	$payment_settings->disable_pp = 0;
    }
    if(isset($_POST['btnSaveStripe'])){
        if($_POST['btnSaveStripe'] == "Save Settings") {
        	$payment_settings->api_key = $_POST['stripe_api_key'];
    		$payment_settings->stripe_publishable_key = $_POST['stripe_publishable_key'];
    		$payment_settings->sandbox_api_key = $_POST['sandbox_api_key'];
    		$payment_settings->sandbox_publishable_key = $_POST['sandbox_publishable_key'];
            if (isset($_POST['sandbox-mode'])) {
            	$payment_settings->sandbox_mode = 'sandbox';
            }
            else {
            	$payment_settings->sandbox_mode = 'production';
            }
            if (isset($_POST['disable-pp'])) {
            	$payment_settings->disable_pp = esc_attr($_POST['disable-pp']);
            }
            else {
            	$payment_settings->disable_pp = 0;
            }

            $fund_type = esc_attr($_POST['fund-type']);
            if (isset($_POST['currency'])) {
            	$currency = esc_attr($_POST['currency']);
            }
            else {
            	$currency = 'USD';
            }

            if(isset($empty) && $empty == true){
                $sql = "INSERT INTO ".$wpdb->prefix."ign_stripe_settings (id, currency, fund_type, api_key, stripe_publishable_key, sandbox_api_key, sandbox_publishable_key, sandbox_mode, disable_pp)
						VALUES (
							1,
							'".$currency."',
							'".$fund_type."',
							'".$payment_settings->api_key."',
							'".$payment_settings->stripe_publishable_key."',
							'".$payment_settings->sandbox_api_key."',
							'".$payment_settings->sandbox_publishable_key."',
							'".$payment_settings->sandbox_mode."',
							'".$payment_settings->disable_pp."')";
                $res = $wpdb->query( $sql );
            } else {
                $sql = "UPDATE ".$wpdb->prefix."ign_stripe_settings SET
                		currency = '".$currency."',
                		fund_type = '".$fund_type."',
						api_key = '".$payment_settings->api_key."',
						stripe_publishable_key = '".$payment_settings->stripe_publishable_key."',
						sandbox_api_key = '".$payment_settings->sandbox_api_key."',
						sandbox_publishable_key = '".$payment_settings->sandbox_publishable_key."',
						sandbox_mode = '".$payment_settings->sandbox_mode."',
						disable_pp = '".$payment_settings->disable_pp."'
						WHERE id='1'";
                $res = $wpdb->query( $sql );
            }
            $message = '<div class="updated fade below-h2" id="message"><p>'.__('Settings Saved', 'idstripe').'</p></div>';	#change-languageVariables_20Jan2012
        }
    }
	
	echo '<div class="wrap">
			'.admin_menu_html();
    include_once 'templates/admin/_stripeSettings.php';
	echo '</div>';
}

function idstripe_submenu_tab($output) {
	$output .= '<a '.(($_GET['page'] == "stripe-settings") ? ' class="nav-tab nav-tab-active"' : 'class="nav-tab"').' href="admin.php?page=stripe-settings">'.__('Stripe Settings', 'idstripe').'</a>';
	return $output;
}

add_filter('id_submenu_tab', 'idstripe_submenu_tab');

function idstripe_settings_link() {
	echo '<div class="payment-link"><a href="admin.php?page=stripe-settings">'.__('Stripe Settings', 'idstripe').'</a></div>';
}

add_action('id_paysettings_links', 'idstripe_settings_link');
?>