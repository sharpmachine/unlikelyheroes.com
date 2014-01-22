<?php
class ID_Member_Order {
	var $id;
	var $user_id;
	var $level_id; 

	var $order_date;
	var $transaction_id;
	var $subscription_id;
	var $status;
	var $e_date;

	function __construct( 
		$id = null,
		$user_id = null,
		$level_id = null,
		$order_date = null,
		$transaction_id = 'admin',
		$subscription_id = null,
		$status = 'active',
		$e_date = null
		)
	{
		$this->id = $id;
		$this->user_id = $user_id;
		$this->level_id = $level_id;
		$this->order_date = date('Y-m-d h:i:s');
		$this->transaction_id = $transaction_id;
		$this->subscription_id = $subscription_id;
		$this->status = $status;
		$this->e_date = $e_date;	
	}

	function add_order() {
		if (empty($this->e_date)) {
			$this->e_date = $this->get_e_date();
		}
		global $wpdb;
		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'memberdeck_orders (user_id, 
			level_id, 
			order_date, 
			transaction_id,
			subscription_id,
			status,
			e_date) VALUES (%d, %d, %s, %s, %s, %s, %s)', 
		$this->user_id, 
		$this->level_id, 
		$this->order_date, 
		$this->transaction_id,
		$this->subscription_id,
		$this->status,
		$this->e_date);
		$res = $wpdb->query($sql);
		$insert_id = $wpdb->insert_id;
		if (isset($insert_id)) {
			$order = array('level_id' => $this->level_id,
				'user_id' => $this->user_id);
			ID_Member_Credit::new_order_credit($order);
			return $insert_id;
		}
		else {
			return null;
		}
	}

	function update_order() {
		if (empty($this->e_date)) {
			$this->e_date = &$this->get_e_date();
		}
		global $wpdb;
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_orders SET user_id = %d, 
			level_id = %d, 
			order_date = %s, 
			transaction_id = %s, 
			subscription_id = %s,
			status = %s,
			e_date = %s WHERE id = %d', 
			$this->user_id, 
			$this->level_id, 
			$this->order_date, 
			$this->transaction_id,
			$this->subscription_id,
			$this->status,
			$this->e_date, 
			$this->id);
		$res = $wpdb->query($sql);
	}

	function delete_order() {
		global $wpdb;
		$sql = 'DELETE FROM '.$wpdb->prefix.'memberdeck_orders WHERE id = '.$this->id;
		$res = $wpdb->query($sql);
	}

	function get_orders() {
		global $wpdb;
		$sql = 'SELECT * FROM '.$wpdb->prefix.'idmember_members';
		$res = $wpdb->get_results($sql);
		return $res;
	}

	function get_order() {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_orders WHERE id = %d', $this->id);
		$res = $wpdb->get_row($sql);
		return $res;
	}

	function get_last_order() {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_orders WHERE user_id = %d AND level_id = %d ORDER BY id DESC', $this->user_id, $this->level_id);
		$res = $wpdb->get_row($sql);
		return $res;
	}

	function get_transaction() {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_orders WHERE transaction_id = %s', $this->transaction_id);
		$res = $wpdb->get_row($sql);
		return $res;
	}

	function get_e_date() {
		$exp_level = ID_Member_Level::get_level($this->level_id);
		$level_type = $exp_level->level_type;
		if ($level_type == 'standard') {
			//if ($exp_level->level_price > 0) {
				$exp = strtotime('+1 years');
				$e_date = date('Y-m-d h:i:s', $exp);
			//}
			/*else {
				$e_date = null;
			}*/
		}
		else if ($level_type == 'recurring') {
			$recurring_type = $exp_level->recurring_type;
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
		}
		else {
			$e_date = null;
		}
		return $e_date;
	}

	function get_subscription() {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_orders WHERE subscription_id = %s', $this->subscription_id);
		$res = $wpdb->get_row($sql);
		return ($res);
	}

	function cancel_status() {
		global $wpdb;
		$e_date = date('Y-m-d h:i:s');
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_orders SET status = "cancelled", e_date = %s WHERE id = %d', $e_date, $this->id);
		$res = $wpdb->query($sql);
	}

	public static function add_preorder($order_id, $charge_token, $source) {
		global $wpdb;
		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'memberdeck_preorder_tokens (order_id, charge_token, gateway) VALUES (%d, %s, %s)', $order_id, $charge_token, $source);
		$res = $wpdb->query($sql);
		if (isset($res)) {
			return $wpdb->insert_id;
		}
		else {
			return null;
		}
	}

	public static function get_preorder_by_orderid($order_id) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_preorder_tokens WHERE order_id = %d', $order_id);
		$res = $wpdb->get_row($sql);
		return $res;
	}

	public static function cancel_subscription($id) {
		global $wpdb;
		$date = date('Y-m-d h:i:s');
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_orders SET e_date = %s, status = "cancelled" WHERE id = %d', $date, $id);
		$res = $wpdb->query($sql);
		return $sql;
	}

	public static function check_order_exists($txn_id) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_orders WHERE transaction_id = %s', $txn_id);
		$res = $wpdb->get_results($sql);
		return $res;
	}

	public static function update_order_date($id, $date) {
		global $wpdb;
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_orders SET e_date = %s WHERE id = %d', $date, $id);
		$res = $wpdb->query($sql);
	}

	public static function get_md_preorders($level_id) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_orders WHERE transaction_id = "pre" and level_id = %d', $level_id);
		$res = $wpdb->get_results($sql);
		return $res;
	}

	public static function update_txn_id($id, $txn_id) {
		global $wpdb;
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_orders SET transaction_id = %s WHERE id = %d', $txn_id, $id);
		$res = $wpdb->query($sql);
	}
}
?>