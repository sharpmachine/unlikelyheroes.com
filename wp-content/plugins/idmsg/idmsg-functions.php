<?php
function get_idmsg_settings() {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'idmsg_settings WHERE id="%d"', 1);
	$res = $wpdb->get_row($sql);
	return $res;
}

function get_id_settings($pay_info_id) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'ign_pay_info WHERE id="%d"', $pay_info_id);
	$res = $wpdb->get_row($sql);
	return $res;
}

function get_id_prodname($prod_id) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT product_name FROM '.$wpdb->prefix.'ign_products WHERE id="%d"', $prod_id);
	$res = $wpdb->get_row($sql);
	return $res;
}
?>