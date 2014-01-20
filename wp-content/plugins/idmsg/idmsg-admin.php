<?php
function idmsg_admin_menu() {
	add_submenu_page('ignitiondeck', __('Recepit Settings', 'idmsg') , __('Receipts', 'idmsg'), 'manage_options', 'idmsg', 'idmsg_main_menu');
}

add_action('id_submenu', 'idmsg_admin_menu');

function idmsg_main_menu() {
	global $wpdb;

	$settings = get_idmsg_settings();
	
	if (isset($settings)) {
		$confirmation_on = $settings->confirmation_on;
		$receipt_on = $settings->receipt_on;
		$notification_email = $settings->notification_email;
		$from_email = $settings->from_email;
		$receipt_msg = $settings->receipt_msg;
	}
	else {
		$confirmation_on = 0;
		$receipt_on = 0;
		$notification_email = '';
		$from_email = '';
		$receipt_msg = '';
	}

	if (isset($_POST['submit'])) {

		if (isset($_POST['confirmation-on'])) {
			$confirmation_on = $_POST['confirmation-on'];
		}
		else {
			$confirmation_on = 0;
		}
		if (isset($_POST['receipt-on'])) {
			$receipt_on = $_POST['receipt-on'];
		}
		else {
			$receipt_on = 0;
		}
		$notification_email = $_POST['notification-email'];
		$from_email = $_POST['from-email'];
		$receipt_msg = esc_textarea($_POST['receipt-msg']);

		if (isset($settings)) {
			$sql = 'UPDATE '.$wpdb->prefix.'idmsg_settings SET confirmation_on='.$confirmation_on.', receipt_on='.$receipt_on.', notification_email="'.$notification_email.'", from_email="'.$from_email.'", receipt_msg="'.$receipt_msg.'" WHERE id="1"';
		}
		else {
			$sql = 'INSERT INTO '.$wpdb->prefix.'idmsg_settings (confirmation_on, receipt_on, notification_email, from_email, receipt_msg) VALUES ("'.$confirmation_on.'", "'.$receipt_on.'", "'.$notification_email.'", "'.$from_email.'", "'.$receipt_msg.'")';
		}
		$res = $wpdb->query($sql);
	}

	if (isset($_POST['send-test'])) {

		$settings = get_idmsg_settings();

		$to_email = $_POST['test-email'];
		$from_email = $_POST['test-from'];

		$custom_message = str_replace("{{NAME}}", 'Name', $settings->receipt_msg);
		$custom_message = str_replace("{{AMOUNT}}", (isset($cCode) ? $cCode : '$').'0.00', $custom_message);
		$custom_message = str_replace("{{DATE}}", date('F j, Y'), $custom_message);

		$headers = "From: ".$from_email. "\n";
		$headers .= "Reply-To: ".$from_email. "\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";

		$subject = __("Your Product Name Here Pledge Notification", "idmsg");

		$message = "<html><body>";
		$message .= stripslashes(html_entity_decode($custom_message))."<br/>";
		$message .= "Pledge Price: ".(isset($cCode) ? $cCode : '$')."100.00<br/>";
		$message .= "Supporter Name: John Johnson<br/>";
		$message .= "Product: Product name<br/>";
		$message .= "Level: 1<br/>";
		$message .= "Date of Pledge: ".date('F j, Y')."<br/>";
		$message .= "</body></html>";

		mail($to_email, $subject, $message, $headers);
	}

	include_once 'templates/admin/_idmsgSettings.php';
}

?>