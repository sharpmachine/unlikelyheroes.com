<?php
class ID_Member_Level {
	var $level_id;
	var $level_name;
	var $level_price;
	var $credit_value;
	var $txn_type;
	var $level_type;
	var $recurring_type;
	var $plan;
	var $license_count;
	var $home_page;

	function __construct() {
		
	}

	function add_level($level) {
		global $wpdb;
		$this->level_name = $level['level_name'];
		$this->level_price = $level['level_price'];
		$this->credit_value = $level['credit_value'];
		$this->txn_type = $level['txn_type'];
		$this->level_type = $level['level_type'];
		
		if ($this->level_type !== 'recurring') {
			$this->recurring_type = 'none';
			$this->plan = '';
		}
		else {
			$this->recurring_type = $level['recurring_type'];
			$this->plan = $level['plan'];
		}
		$this->license_count = $level['license_count'];
		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'memberdeck_levels (level_name, level_price, credit_value, txn_type, level_type, recurring_type, plan, license_count) VALUES (%s, %s, %d, %s, %s, %s, %s, %d)', $this->level_name, $this->level_price, $this->credit_value, $this->txn_type, $this->level_type, $this->recurring_type, $this->plan, $this->license_count);
		$res = $wpdb->query($sql);
		$this->level_id = $wpdb->insert_id;

		$post_id = memberdeck_auto_page($this->level_id, $this->level_name);
		return array('level_id' => $this->level_id, 'post_id' => $post_id);
	}

	public static function update_level($level) {
		global $wpdb;
		$level_name = $level['level_name'];
		$level_price = $level['level_price'];
		$credit_value = $level['credit_value'];
		$txn_type = $level['txn_type'];
		$level_id = $level['level_id'];
		$level_type = $level['level_type'];
		$recurring_type = $level['recurring_type'];
		$plan = $level['plan'];
		$license_count = $level['license_count'];
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_levels SET level_name=%s, level_price=%s, credit_value = %d, txn_type=%s, level_type=%s, recurring_type=%s, plan=%s, license_count=%d WHERE id=%d', $level_name, $level_price, $credit_value, $txn_type, $level_type, $recurring_type, $plan, $license_count, $level_id);
		$res = $wpdb->query($sql);
	}

	public static function delete_level($level) {
		global $wpdb;
		$level_id = $level['level_id'];
		$sql = 'DELETE FROM '.$wpdb->prefix.'memberdeck_levels WHERE id='.$level_id;
		$res = $wpdb->query($sql);
	}

	public static function get_levels() {
		global $wpdb;
		$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_levels';
		$res = $wpdb->get_results($sql);
		return $res;
	}

	public static function get_level($id) {
		global $wpdb;
		$level_id = absint(esc_attr($id));
		$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_levels WHERE id='.$level_id;
		$res = $wpdb->get_row($sql);
		return $res;
	}

	public static function get_level_by_plan($plan) {
		global $wpdb;
		$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_levels WHERE plan = "'.$plan.'"';
		$res = $wpdb->get_row($sql);
		return $res->id;
	}

	public static function get_level_member_count($id) {
		global $wpdb;
		$sql = 'SELECT COUNT(*) as count FROM '.$wpdb->prefix.'memberdeck_members WHERE access_level LIKE "%i:'.$id.'%" OR access_level LIKE "%s:1:\"'.$id.'\"%"';
		$res = $wpdb->get_row($sql);
		return $res;
	}
}
?>