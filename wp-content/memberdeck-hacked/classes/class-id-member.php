<?php
class ID_Member {

	var $user_id;
	var $membership;
	var $r_date;
	var $reg_key;
	var $data;

	function __construct($user_id = null) {
		$this->user_id = $user_id;
	}

	function get_user_credits() {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT credits FROM '.$wpdb->prefix.'memberdeck_members WHERE user_id = %s', $this->user_id);
		$res = $wpdb->get_row($sql);
		if (isset($res)) {
			return $res->credits;
		}
		else {
			return null;
		}
	}

	function match_user($user_id) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_members WHERE user_id=%d', $user_id);
		$res = $wpdb->get_row($sql);
		return $res;
	}

	function check_user($email) {
		global $wpdb;
		$prefix = md_user_prefix();
		$sql = $wpdb->prepare('SELECT * FROM '.$prefix.'users WHERE user_email = %s', $email);
		return $wpdb->get_row($sql);
	}

	function save_user($user_id, $membership) {
		global $wpdb;
		$this->user_id = $user_id;
		$this->membership = serialize($membership);
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_members SET access_level=%s WHERE user_id=%d', $this->membership, $this->user_id);
		$res = $wpdb->query($sql);
	}

	function set_credits($user_id, $sum) {
		global $wpdb;
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_members SET credits = %d WHERE user_id = %d', $sum, $user_id);
		$res = $wpdb->query($sql);
	}

	public static function add_user($user) {
		global $wpdb;
		// need to allow for custom exp dates
		$exp = strtotime('+1 years');

		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'memberdeck_members (user_id, access_level, r_date, data) VALUES (%d, %s, %s, %s)', absint($user['user_id']), serialize($user['level']), date('Y-m-d h:i:s'), serialize(array($user['data'])));
		$res = $wpdb->query($sql);
		$id = $wpdb->insert_id;
	}

	public static function add_paypal_user($user) {
		global $wpdb;

		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'memberdeck_members (user_id, access_level, r_date, reg_key, data) VALUES (%d, %s, %s, %s, %s)', absint($user['user_id']), serialize($user['level']), date('Y-m-d h:i:s'), $user['reg_key'], serialize(array($user['data'])));
		$res = $wpdb->query($sql);
		$id = $wpdb->insert_id;
		return $sql.$id;
	}

	public static function retrieve_user_key($reg_key) {
		global $wpdb;
		$reg_key = esc_attr($reg_key);
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_members WHERE reg_key = %s', $reg_key);
		$res = $wpdb->get_row($sql);
		return $res;
	}

	public static function update_user($user) {
		global $wpdb;
		$level = $user['level'];
		if (is_array($level)) {
			$level = array_unique($level);
		}
		else {
			$level = array($level);
		}
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_members SET access_level=%s, data=%s WHERE user_id=%d', serialize($level), serialize($user['data']), absint($user['user_id']));
		$res = $wpdb->query($sql);
	}

	public static function expire_level($user_id, $levels) {
		global $wpdb;
		$levels = serialize($levels);
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_members SET access_level = %s WHERE user_id = %d', $levels, $user_id);
		$res = $wpdb->query($sql);
	}

	public static function delete_reg_key($user_id) {
		global $wpdb;
		$sql = 'UPDATE '.$wpdb->prefix.'memberdeck_members SET reg_key = "" WHERE user_id = '.absint($user_id);
		$res = $wpdb->query($sql);
	}

	public static function user_levels($user_id) {
		global $wpdb;
		//$user = get_userdata($user_id);
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_members WHERE user_id=%d', $user_id);
		$res = $wpdb->get_row($sql);
		return $res;
	}

	public static function get_users() {
		global $wpdb;
		$prefix = md_user_prefix();
		// first lets join the wp users and memberdeck users tables and put them in an array
		$getusers = 'SELECT * FROM '.$prefix.'users LEFT JOIN '.$wpdb->prefix.'memberdeck_members ON ('.$prefix.'users.ID='.$wpdb->prefix.'memberdeck_members.user_id)';
		//$getusers = 'SELECT * FROM '.$wpdb->prefix.'users';
		$users = $wpdb->get_results($getusers);
		if (is_multisite()) {
			$allowed_users = array();
			$blog_id = get_current_blog_id();
			foreach ($users as $user) {
				$user_id = $user->ID;
				$blog_access = get_blogs_of_user($user_id);
				$add_user = false;
				foreach ($blog_access as $access) {
					if ($blog_id == $access->userblog_id) {
						$add_user = true;
					}
				}
				if ($add_user) {
					$allowed_users[] = $user;
				}
			}
			$users = $allowed_users;
		}
		return $users;
	}

	public static function get_like_users($like) {
		global $wpdb;
		$prefix = md_user_prefix();
		// first lets join the wp users and memberdeck users tables and put them in an array
		$getusers = 'SELECT * FROM '.$prefix.'users LEFT JOIN '.$wpdb->prefix.'memberdeck_members ON ('.$prefix.'users.ID='.$wpdb->prefix.'memberdeck_members.user_id) WHERE user_login LIKE "%'.$like.'%" OR user_nicename LIKE "%'.$like.'%" OR user_email LIKE "%'.$like.'%" OR user_url LIKE "%'.$like.'%" OR display_name LIKE "%'.$like.'%"';
		//$getusers = 'SELECT * FROM '.$wpdb->prefix.'users';
		$users = $wpdb->get_results($getusers);
		return $users;
	}

	public static function get_level_users($level_id) {
		global $wpdb;
		$users = ID_Member::get_users();
		$return = array();
		foreach ($users as $user) {
			$user_levels = unserialize($user->access_level);
			if (is_array($user_levels)) {
				if (in_array($level_id, $user_levels)) {
					$return[] = $user;
				}
			}
		}
		return $return;
	}

	public static function get_subscription_data($sub_id) {
		global $wpdb;
		$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_members WHERE data LIKE "%'.$sub_id.'%"';
		$res = $wpdb->get_results($sql);
		return $res;
	}

	public static function get_customer_data($cust_id) {
		global $wpdb;
		$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_members WHERE data LIKE "%'.$cust_id.'%"';
		$res = $wpdb->get_row($sql);
		return $res;
	}

	public static function get_customer_id($user_id) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'memberdeck_members WHERE user_id = %d', $user_id);
		$res = $wpdb->get_row($sql);
		$customer_id = null;
		if (isset($res->data)) {
			$data = unserialize($res->data);
			foreach ($data as $item) {
				foreach ($item as $k=>$v) {
					if ($k == 'customer_id') {
						$customer_id = $v;
						break 2;
					}
				}	
			}
		}
		return $customer_id;
	}

	public static function add_credits($user_id, $credit_count) {
		global $wpdb;
		$member = new ID_Member();
		$match = $member->match_user($user_id);
		if (!empty($match)) {
			$user_credits = $match->credits;
			$new_count = absint($user_credits) + absint($credit_count);
			$id = $match->id;
			$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'memberdeck_members SET credits = %d WHERE id = %d', $new_count, $id);
			$res = $wpdb->query($sql);
		}
	}

	public static function export_members() {
		global $wpdb;
		$members = self::get_users();
		$user_records = array();
		$url = '';
		if (!empty($members)) {
			foreach ($members as $member) {
				// get users and prep data
				$user = array();
				$user_id = $member->user_id;
				$levels = $member->access_level;
				if (!empty($levels)) {
					$level_array = implode(',', unserialize($levels));
				}
				else {
					$level_array = '';
				}
				$credits = $member->credits;
				/*if (!empty($member->data)) {
					$data = implode(',', unserialize($member->data));
				}
				else {
					$data = '';
				}*/
				// now WP data
				$user_data = get_userdata($user_id);
				if (!empty($user_data)) {
					$username = $user_data->user_login;
					$email = $user_data->user_email;
				}
				else {
					$username = '';
					$email = '';
				}

				$user_meta = get_user_meta($user_id);
				if (!empty($user_meta)) {
					$fname = $user_meta['first_name'][0];
					$lname = $user_meta['last_name'][0];
				}
				else {
					$fname = '';
					$lname = '';
				}
				

				$user['md_id'] = $member->id;
				$user['user_id'] = $user_id;
				$user['username'] = $username;
				$user['email'] = $email;
				$user['first_name'] = $fname;
				$user['last_name'] = $lname;
				$user['rdate'] = $member->r_date;
				$user['levels'] = $level_array;
				//$user['data'] = $data;
				$user_records[] = $user;
			}
		}
		if (!empty($user_records)) {
			// now we should have data to export
			$filename = __('MemberDeck Customer Export', 'memberdeck').'-'.date('Y-m-d h:i:s');
			$uploads = wp_upload_dir();
			$filepath = trailingslashit($uploads['basedir']).$filename;
			$baseurl = trailingslashit($uploads['baseurl']).$filename;
			$file = fopen($filepath.'.csv', 'w');
			$keys = array_keys($user_records[0]);
			fputcsv($file, $keys);
			foreach ($user_records as $record) {
				fputcsv($file, $record);
			}
			fclose($file);
			//$url = $baseurl.'.csv';
			header('Content-Type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename.'.csv');
			header('Pragma: no-cache');
			readfile(trailingslashit($uploads['baseurl']).rawurlencode($filename).'.csv');
			ID_Member::delete_export($filepath);
			exit;
			//return $url;
		}
	}

	public static function delete_export($filepath) {
		unlink($filepath.".csv");
	}
}
?>