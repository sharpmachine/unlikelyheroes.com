<?php

if (idf_edd_set()) {
	add_action( 'add_meta_boxes', 'idedd_project_pairing');
	//add_filter('id_purchase_form', 'idedd_swap_forms', 1, 2);
	remove_shortcode('project_purchase_form');
	add_shortcode('project_purchase_form', 'idedd_swap_forms', 1);
	add_action('edd_insert_payment', 'idedd_insert_order', 5, 2);
	add_action('edd_update_edited_purchase', 'idedd_update_order', 5, 1);
	add_action('edd_complete_purchase', 'idedd_complete_order', 5, 1);
	add_action('edd_payment_deleted', 'idedd_delete_order', 5, 1);
	// can we de-register the scripts and links from the template?
}

function idedd_project_pairing() {
	add_meta_box("idedd_project_pairing", "EDD Shortcode", "set_idedd_project_pairing", "ignition_product", "side", "default");
}

function set_idedd_project_pairing($post) {
	// Add an nonce field so we can check for it later.
  	wp_nonce_field( 'set_idedd_project_pairing', 'set_idedd_project_pairing_nonce' );
  	$value = get_post_meta($post->ID, 'idedd_project_pairing', true);
	$fields = array(
		array(
			'before' => '<p>If matching IgnitionDeck project to Easy Digital Download, please enter the download ID here.<br/>Otherwise, leave this field blank.</p>',
			'label' => 'Download ID',
			'value' => (isset($value) ? $value : ''),
			'name' => 'idedd_project_pairing',
			'type' => 'number'
		)
	);
	$form = new ID_Form($fields);
	echo apply_filters('idedd_project_pairing_form', $form->build_form());
}

add_action('save_post', 'save_idedd_project_pairing');

function save_idedd_project_pairing($post_id) {
	if (!isset($_POST['set_idedd_project_pairing_nonce'])) {
		return $post_id;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
	$value = esc_attr($_POST['idedd_project_pairing']);
	update_post_meta($post_id, 'idedd_project_pairing', $value);
	$project_id = get_post_meta($post_id, 'ign_project_id', true);
	update_post_meta($value, '_edd_project_pairing', $project_id);
}

function idedd_swap_forms($attrs) {
	if (isset($attrs['product'])) {
		$project_id = absint($attrs['product']);
	}
	if (isset($_GET['prodid'])) {
		$project_id = absint($_GET['prodid']);	
	}
	if (isset($project_id) && $project_id > 0) {
		$project = new ID_Project($project_id);
		$post_id = $project->get_project_postid();
		if (isset($post_id) && $post_id > 0) {
			$download_id = get_post_meta($post_id, 'idedd_project_pairing', true);
			if (!empty($download_id) && $download_id > 0) {
				return do_shortcode('[purchase_link id="251" text="Checkout"]');
			}
		}
	}
	return;
}

function idedd_insert_order($payment_id) {
	// we need to run this on status update, edit, and delete
	// this used EDD payment_id as txn_id rather than gateway txn_id
	$vars = idedd_payment_vars($payment_id);
	$rc = new ReflectionClass('ID_Order');
	$order = $rc->newInstanceArgs($vars);
	$pay_id = $order->insert_order();
}

function idedd_update_order($payment_id) {
	$vars = idedd_payment_vars($payment_id);
	$rc = new ReflectionClass('ID_Order');
	$order = $rc->newInstanceArgs($vars);
	$check = $order->check_new_order($vars['transaction_id']);
	if (isset($check)) {
		$pay_id = $check->id;
		if (isset($pay_id) && $pay_id > 0) {
			$vars['id'] = $pay_id;
			$rc = new ReflectionClass('ID_Order');
			$order = $rc->newInstanceArgs($vars);
			$update = $order->update_order();
		}
	}
}

function idedd_complete_order($payment_id) {
	$order = ID_Order::get_order_by_txn($payment_id);
	if (!empty($order)) {
		$pay_id = $order->id;
		$update = setOrderStatus('C', $pay_id);
	}
}

function idedd_delete_order($payment_id) {
	$order = ID_Order::get_order_by_txn($payment_id);
	if (!empty($order)) {
		$pay_id = $order->id;
		$delete = ID_Order::delete_order($pay_id);
	}
}

function idedd_payment_vars($payment_id) {
	$vars = array();
	$paymeta = get_post_meta($payment_id, '_edd_payment_meta', true);
	if (is_array($paymeta)) {
		$downloads = unserialize($paymeta['downloads']);
		if (is_array($downloads)) {
			//print_r($downloads);
			$download_id = $downloads[0]['id'];
			$level = $downloads[0]['options']['price_id'] + 1;
		}
		if (isset($download_id)) {
			$post = get_post($payment_id);
			if (isset($post)) {
				$status = strtoupper(substr($post->post_status, 0, 1));
				$date = $post->post_date;
			}
			else {
				$status = 'P';
			}
			if (is_array($paymeta['user_info'])) {
				// strange but seems that after editing, it saves as array and not serialized array
				$user_info = $paymeta['user_info'];
			}
			else {
				$user_info = unserialize($paymeta['user_info']);
			}
			if (isset($user_info['first_name'])) {
				$first_name = $user_info['first_name'];
			}
			else {
				$first_name = '';
			}
			if (isset($user_info['last_name'])) {
				$last_name = $user_info['last_name'];
			}
			else {
				$last_name = '';
			}
			if (isset($user_info['email'])) {
				$email = $user_info['email'];
			}
			else {
				$email = '';
			}
			$cart_details = unserialize($paymeta['cart_details']);
			$price = get_post_meta($payment_id, '_edd_payment_total', true);
			if (!isset($date)) {
				$date = null;
			}
			if (isset($download_id) && $download_id > 0) {
				$project_id = get_post_meta($download_id, '_edd_project_pairing', true);
				if (isset($project_id) && $project_id > 0) {
					$project = new ID_Project($project_id);
					$the_project = $project->the_project();
					$vars = array(
						'id' => null,
						'first_name' => $first_name,
						'last_name' => $last_name,
						'email' => $email,
						'address' => '',
						'state' => '',
						'city' => '',
						'zip' => '',
						'country' => '',
						'product_id' => $project_id,
						'transaction_id' => $payment_id,
						'preapproval_key' => '',
						'product_level' => $level,
						'prod_price' => $price,
						'status' => $status,
						'created_at' => $date
					);
				}
			}
		}
	}
	return $vars;
}

?>