<?php

//error_reporting(E_ALL);

//@ini_set('display_errors', 1);

/*
Plugin Name: IgnitionDeck Email Messaging Plugin
URI: http://ignitiondeck.com
Description: 
Version: 1.04
Author: Virtuous Giant
Author URI: http://VirtuousGiant.com
License: GPL2
*/

register_activation_hook(__FILE__,'idmsg_install');

global $idmsg_db_version;
$idmsg_db_version = "1.04";

function idmsg_install() {
	global $wpdb;
	global $idmsg_db_version;

	$current_idmsg_db_version = get_option("idmsg_db_version");

	$table_name = $wpdb->prefix."idmsg_settings";

	$sql = "CREATE TABLE ".$table_name." (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		confirmation_on TINYINT(1) NOT NULL DEFAULT '0',
		receipt_on TINYINT(1) NOT NULL DEFAULT '0',
		from_email VARCHAR(55) NOT NULL,
		notification_email VARCHAR(55) NOT NULL,
		receipt_msg TEXT NOT NULL,
		UNIQUE KEY id (id));";
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	update_option('idmsg_db_version', $idmsg_db_version);
}

require_once 'idmsg-admin.php';
require_once 'idmsg-functions.php';

function sendSalesReceipt($pay_info_id) {
	global $wpdb;
	global $cCode;

	$settings = get_idmsg_settings();
	$pay_info = get_id_settings($pay_info_id);

	$from_email = $settings->from_email;
	$to_email = $pay_info->email;
	$name = $pay_info->first_name.' '.$pay_info->last_name;
	$prod_id = $pay_info->product_id;
	$project = new ID_Project($prod_id);
	$cCode = $project->currency_code();
	$prod_title = get_id_prodname($prod_id);

	$price = $pay_info->prod_price;
	$date = $pay_info->created_at;
	$level = $pay_info->product_level;
	$tx_id = $pay_info->transaction_id;

	$custom_message = str_replace('{{NAME}}', $name, $settings->receipt_msg);
	$custom_message = str_replace('{{AMOUNT}}', (isset($cCode) ? $cCode : '$').$price, $custom_message);
	$custom_message = str_replace('{{DATE}}', $date, $custom_message);

	$headers = "From: ".$from_email. "\n";
	$headers .= "Reply-To: ".$from_email. "\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";

	$subject = stripslashes($prod_title->product_name).__(" Pledge Notification", "idmsg");

	$message = "<html><body>";
	$message .= stripslashes(html_entity_decode($custom_message))."<br/>";
	$message .= "Pledge Price: ".(isset($cCode) ? $cCode : '$').$price."<br/>";
	$message .= "Supporter Name: ".$pay_info->first_name." ".$pay_info->last_name."<br/>";
	$message .= "Product: ".stripslashes(mysql_real_escape_string($prod_title->product_name))."<br/>";
	$message .= "Level: ".$level."<br/>";
	$message .= "Date of Pledge: ".$date."<br/>";
	$message .= "</body></html>";

	mail($to_email, $subject, $message, $headers);
}

function sendSalesNotification($pay_info_id) {
	global $wpdb;

	$settings = get_idmsg_settings();
	$pay_info = get_id_settings($pay_info_id);

	$from_email = $settings->from_email;
	$to_email = $settings->notification_email;
	$first_name = $pay_info->first_name;
	$last_name = $pay_info->last_name;
	$prod_id = $pay_info->product_id;

	$prod_title = get_id_prodname($prod_id);

	$price = $pay_info->prod_price;
	$date = $pay_info->created_at;
	$level = $pay_info->product_level;
	$tx_id = $pay_info->transaction_id;

	$headers = "From: ".$from_email." \n".
				"Reply-To: ".$from_email;

	$subject = "IgnitionDeck Pledge Notification";

	$message = __("Congrats! You just received a pledge with the following information:", "idmsg");
	$message .= "
	====================================================================
	Price: ".(isset($cCode) ? $cCode : '$').$price."
	Supporter: ".$first_name." ".$last_name."
	Product: ".$prod_title->product_name."
	Level: ".$level;

	mail($to_email, $subject, $message, $headers);
}

$settings = get_idmsg_settings();

if ($settings->confirmation_on == 1) {
	add_action('id_payment_success', 'sendSalesNotification', 10, 1);
}

if ($settings->receipt_on == 1) {
	add_action('id_payment_success', 'sendSalesReceipt', 10, 1);
}

?>