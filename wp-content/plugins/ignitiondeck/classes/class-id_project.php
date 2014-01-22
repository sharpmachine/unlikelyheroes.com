<?php
class ID_Project {
	var $id;

	function __construct($id=null) {
		$this->id = $id;
	}

	function the_project() {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'ign_products WHERE id = %d', $this->id);
		$res = $wpdb->get_row($sql);
		return $res;
	}

	function update_project($args) {
		global $wpdb;
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'ign_products SET product_name = %s, ign_product_title = %s, ign_product_limit = %d, product_details = %s, product_price = %s, goal = %s WHERE id = %d', $args['product_name'], $args['ign_product_title'], $args['ign_product_limit'], $args['product_details'], $args['product_price'], $args['goal'], $this->id);
		$res = $wpdb->query($sql);
	}

	function get_project_settings() {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'ign_product_settings WHERE product_id = %d', $this->id);
		$res = $wpdb->get_row($sql);
		return $res;
	}

	function currency_code() {
		$project_settings = self::get_project_settings();
		if (empty($project_settings)) {
			$project_settings = self::get_project_defaults();
		}
		if (!empty($project_settings)) {
			$currencyCodeValue = $project_settings->currency_code;
		}
		else {
			$currencyCodeValue = 'USD';
		}
		$cCode = setCurrencyCode($currencyCodeValue);
		return $cCode;
	}

	function get_project_postid() {
		global $wpdb;	
		$sql = $wpdb->prepare('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key = "ign_project_id" AND meta_value = %d ORDER BY meta_id DESC LIMIT 1', $this->id);
		$res = $wpdb->get_row($sql);
		if (!empty($res)) {
			return $res->post_id;
		}
		else {
			return null;
		}
	}

	function short_description() {
		$post_id = self::get_project_postid();
		$long_desc = get_post_meta($post_id, 'ign_project_description', true);
		return $long_desc;
	}

	function the_goal() {
		$project = self::the_project();
		$goal = $project->goal;
		return $goal;
	}

	function level_count() {
		$post_id = self::get_project_postid();
		$level_count = get_post_meta($post_id, 'ign_product_level_count', true);
		return $level_count;
	}

	function get_level_price($level_id) {
		$post_id = self::get_project_postid();
		if ($level_id == 1) {
			$price = get_post_meta($post_id, 'ign_product_price', true);
		}
		else if ($level_id > 1) {
			$price = get_post_meta($post_id, 'ign_product_level_'.$level_id.'_price', true);
		}
		return $price;
	}

	function get_project_orders() {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT COUNT(*) AS count FROM '.$wpdb->prefix.'ign_pay_info WHERE product_id = %d', $this->id);
		$res = $wpdb->get_row($sql);
		if (!empty($res)) {
			return $res->count;
		}
		else {
			return 0;
		}
	}

	function get_project_raised() {
		global $wpdb;
		$sql = 'Select SUM(prod_price) AS raise from '.$wpdb->prefix.'ign_pay_info where product_id = "'.$this->id.'"';
		$res = $wpdb->get_row($sql);
		if (!empty($res->raise)) {
			return str_replace(',', '', $res->raise);
		}
		else {
			return '0';
		}
	}

	function percent() {
		$project = self::the_project();
		$project_goal = self::the_goal();
		$project_orders = self::get_project_orders();
		$project_raised = self::get_project_raised();
		$percent = 0;
		if ($project_raised > 0 && $project_goal > 0) {
			$raw_percent = $project_raised/$project_goal*100;
			$percent = number_format($raw_percent, 2, '.', '');
		}
		return $percent;
	}

	function end_date() {
		$post_id = self::get_project_postid();
		$end_date = get_post_meta($post_id, 'ign_fund_end', true);
		return $end_date;
	}

	function days_left() {
		$end_date = self::end_date();
		if (!empty($end_date)) {
			$tz = get_option('timezone_string');
			if (empty($tz)) {
				$tz = 'UTC';
			}
			date_default_timezone_set($tz);
			$days_left = str_replace("/", "-", $end_date);
			$days_left = explode("-", $days_left);
			$days_left = $days_left[2]."-".$days_left[0]."-".$days_left[1];
			$days_left = floor( ( strtotime($days_left) - time() )/60/60/24 );
			if($days_left < 1) {
				$days_left = 0;
			}
			else if (empty($days_left) || $days_left == '') {
				$days_left = 0;
			}
		}
		else {
			$days_left = 0;
		}
		return $days_left;
	}

	function end_month() {
		$end_date = self::end_date();
		if (!empty($end_date)) {
			$tz = get_option('timezone_string');
			if (empty($tz)) {
				$tz = 'UTC';
			}
			date_default_timezone_set($tz);
			$end = str_replace('/', ' ', $end_date);
			$end = explode(' ', $end);
			$end_string = $end[2].'-'.$end[0].'-'.$end[1];
			$end_time = strtotime($end_string);
			$month = date('F', $end_time);
		}
		else {
			$month = date('F', time('now'));
		}
		return $month;
	}

	function end_day() {
		$end_date = self::end_date();
		if (!empty($end_date)) {
			$tz = get_option('timezone_string');
			if (empty($tz)) {
				$tz = 'UTC';
			}
			date_default_timezone_set($tz);
			$end = str_replace('/', ' ', $end_date);
			$end = explode(' ', $end);
			$end_string = $end[2].'-'.$end[0].'-'.$end[1];
			$end_time = strtotime($end_string);
			$day = date('d', $end_time);
		}
		else {
			$day = date('d', time('now'));
		}
		return $day;
	}

	function end_year() {
		$end_date = self::end_date();
		if (!empty($end_date)) {
			$tz = get_option('timezone_string');
			if (empty($tz)) {
				$tz = 'UTC';
			}
			date_default_timezone_set($tz);
			$end = str_replace('/', ' ', $end_date);
			$end = explode(' ', $end);
			$end_string = $end[2].'-'.$end[0].'-'.$end[1];
			$end_time = strtotime($end_string);
			$year = date('Y', $end_time);
		}
		else {
			$year = date('Y', time('now'));
		}
		return $year;
	}

	function clear_project_settings() {
		global $wpdb;
		$sql = "DELETE FROM ".$wpdb->prefix."ign_product_settings WHERE product_id = '".$this->id."'";
		$res = $wpdb->query($sql);
	}

	function get_lvl1_name() {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT ign_product_title FROM '.$wpdb->prefix.'ign_products WHERE id = %d', $this->id);
		$res = $wpdb->get_row($sql);
		return $res->ign_product_title;
	}

	function get_fancy_description($level_id) {
		$the_project = $this->the_project();
		$project_title = $the_project->product_name;
		if ($level_id > 1) {
			$post_id = $this->get_project_postid();
			$level_title = get_post_meta($post_id, 'ign_product_level_'.$level_id.'_title', true);
		}
		else if ($level_id == 1) {
			$level_title = $the_project->ign_product_title;
		}
		return $project_title.': '.$level_title;
	}

	function get_level_data($post_id, $no_levels) {
		$this->post_id = $post_id;
		$level_data = array();
		for ($i=2; $i <= $no_levels; $i++) {
			$meta_title = html_entity_decode(get_post_meta( $this->post_id, "ign_product_level_".($i)."_title", true ));
			$meta_limit = get_post_meta( $this->post_id, "ign_product_level_".($i)."_limit", true );
			$meta_order = get_post_meta($this->post_id, 'ign_product_level_'.$i.'_order', true);
			$meta_price = get_post_meta( $this->post_id, "ign_product_level_".($i)."_price", true );
			$meta_desc = html_entity_decode(get_post_meta( $this->post_id, "ign_product_level_".($i)."_desc", true ));
			$meta_count = getCurrentLevelOrders($this->id, $this->post_id, $i);
			$level_invalid = getLevelLimitReached($this->id, $this->post_id, $i);
			$level_data[$i] = new stdClass;
			$level_data[$i]->id = $i;
			$level_data[$i]->meta_title = $meta_title;
			$level_data[$i]->meta_limit = $meta_limit;
			$level_data[$i]->meta_order = $meta_order;
			$level_data[$i]->meta_price = $meta_price;
			$level_data[$i]->meta_desc = $meta_desc;
			$level_data[$i]->meta_count = $meta_count;
			$level_data[$i]->level_invalid = $level_invalid;
		}
		return $level_data;
	}

	public static function insert_project($args) {
		global $wpdb;
		$tz = get_option('timezone_string');
		if (empty($tz)) {
			$tz = 'UTC';
		}
		date_default_timezone_set($tz);
		$date = date('Y-m-d H:i:s');
		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'ign_products (
			product_name,
			ign_product_title,
			ign_product_limit,
			product_details,
			product_price,
			goal,
			created_at) VALUES (%s, %s, %d, %s, %s, %s, %s)',
		$args['product_name'],
		$args['ign_product_title'],
		$args['ign_product_limit'],
		$args['product_details'],
		$args['product_price'],
		$args['goal'],
		$date);
		$insert_id = null;
		try {
			$res = $wpdb->query($sql);
			$insert_id = $wpdb->insert_id;
		}
		catch(error $e) {
			// some error
		}
		return $insert_id;
	}

	public static function get_all_projects() {
		global $wpdb;
		$sql = 'SELECT * FROM '.$wpdb->prefix.'ign_products';
		$res = $wpdb->get_results($sql);
		return $res;
	}

	public static function get_project_defaults() {
		global $wpdb;
		$sql = 'SELECT * FROM '.$wpdb->prefix.'ign_prod_default_settings WHERE id = 1';
		$settings = $wpdb->get_row($sql);
		return $settings;
	}

	public static function get_id_settings() {
		global $wpdb;
		$sql = 'SELECT * FROM '.$wpdb->prefix.'ign_settings WHERE id = 1';
		$res = $wpdb->get_row($sql);
		return $res;
	}

	public static function get_days_left($project_end) {
		if ($project_end) {
			$tz = get_option('timezone_string');
			if (empty($tz)) {
				$tz = 'UTC';
			}
			date_default_timezone_set($tz);
			$days_left = str_replace("/", "-", $project_end);
			$days_left = explode("-", $days_left);
			$days_left = $days_left[2]."-".$days_left[0]."-".$days_left[1];
			$days_left = floor((strtotime($days_left) - time())/60/60/24);
			if($days_left < 1) {
				$days_left = 0;
			}
		}
		else {
			$days_left = 0;
		}
		return $days_left;
	}

	public static function set_raised_meta() {
		$projects = self::get_all_projects();
		foreach ($projects as $a_project) {
			$project = new ID_Project($a_project->id);
			$post_id = $project->get_project_postid();
			$raised = floatval($project->get_project_raised());
			update_post_meta($post_id, 'ign_fund_raised', $raised);
		}
	}

	public static function set_percent_meta() {
		$projects = self::get_all_projects();
		foreach ($projects as $a_project) {
			$project = new ID_Project($a_project->id);
			$post_id = $project->get_project_postid();
			$percent = floatval($project->percent());
			update_post_meta($post_id, 'ign_percent_raised', $percent);
		}
	}

	public static function set_days_meta() {
		$projects = self::get_all_projects();
		foreach ($projects as $a_project) {
			$project = new ID_Project($a_project->id);
			$post_id = $project->get_project_postid();
			$days_left = $project->days_left();
			update_post_meta($post_id, 'ign_days_left', $days_left);
		}
	}

	public static function level_sort($a, $b) {
		return $a->meta_order == $b->meta_order ? 0 : ($a->meta_order > $b->meta_order) ? 1 : -1;
	}

	public static function get_project_images($post_id, $project_id) {
		$project_image1 = get_post_meta($post_id, 'ign_product_image1', true);
		$project_image2 = get_post_meta($post_id, 'ign_product_image2', true);
		$project_image3 = get_post_meta($post_id, 'ign_product_image3', true);
		$project_image4 = get_post_meta($post_id, 'ign_product_image4', true);
		$images = array($project_image1, $project_image2, $project_image3, $project_image4);
		return $images;
	}
}
?>