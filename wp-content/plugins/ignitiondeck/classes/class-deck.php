<?php
class Deck extends ID_Project {
	var $project_id;

	function __construct($project_id = null) {
		$this->project_id = $project_id;
		parent::__construct($this->project_id);
	}

	function the_deck() {
		$the_project = parent::the_project();
		$prod_settings = parent::get_project_settings();
		if (empty($prod_settings)) {
			$prod_settings = getProductDefaultSettings();
		}
		$post_id = parent::get_project_postid();
		$item_fund_goal = parent::the_goal();
		$item_fund_end = parent::end_date();
		$no_levels = get_post_meta( $post_id, $name="ign_product_level_count", true );
		$custom_level_order = get_post_meta($post_id, 'custom_level_order', true);
		$level_data = parent::get_level_data($post_id, $no_levels);
		$project_desc = html_entity_decode(get_post_meta( $post_id, "ign_project_description", true ));
		$project_type = get_post_meta($post_id, 'ign_project_type', true);
		$end_type = get_post_meta($post_id, 'ign_end_type', true);
		$p_current_sale = parent::get_project_raised();
		$p_count = new stdClass;
		$p_count->p_number = parent::get_project_orders();
		$rating_per = parent::percent();
		$days_left = parent::days_left();
		$end_month = parent::end_month();
		$end_day = parent::end_day();
		$end_year = parent::end_year();
		//GETTING the main settings of ignitiondeck
		$settings = getSettings();
		if ($settings->id_widget_link == "") {
			$affiliate_link = "http://ignitiondeck.com";
		}
		else {
			$affiliate_link = $settings->id_widget_link;
		}
		
		$unique_widget_id = rand(101282,293773);
		//GETTING the currency symbol
		$currencyCodeValue = $prod_settings->currency_code;	
		$cCode = setCurrencyCode($currencyCodeValue);
		$meta_price_1 = get_post_meta( $post_id, "ign_product_price", true );
		$meta_title_1 = $the_project->ign_product_title;
		$meta_desc_1 = get_post_meta($post_id, 'ign_product_details', true);
		$meta_limit_1 = $the_project->ign_product_limit;
		$meta_order_1 = get_post_meta($post_id, 'ign_projectmeta_level_order', true);
		$level_count_1 = getCurrentLevelOrders($this->project_id, $post_id, 1);
		$level_invalid_1 = getLevelLimitReached($this->project_id, $post_id, 1);
		$level_one_data = new stdClass;
		$level_one_data->id = 1;
		$level_one_data->meta_price = $meta_price_1;
		$level_one_data->meta_title = $meta_title_1;
		$level_one_data->meta_desc = $meta_desc_1;
		//$level_one_data->level_count = $meta_count
		$level_one_data->meta_limit = $meta_limit_1;
		$level_one_data->meta_order = $meta_order_1;
		$level_one_data->meta_count = $level_count_1;
		$level_one_data->level_invalid = $level_invalid_1;
		array_unshift($level_data, $level_one_data);
		if ($custom_level_order) {
			usort($level_data, array('parent','level_sort'));
		}
		// create deck
		$the_deck = new stdClass;
		$the_deck->project = $the_project;
		$the_deck->prod_settings = $prod_settings;
		$the_deck->post_id = $post_id;
		$the_deck->item_fund_goal = $item_fund_goal;
		$the_deck->item_fund_end = $item_fund_end;
		$the_deck->no_levels = $no_levels;
		$the_deck->custom_level_order = $custom_level_order;
		$the_deck->level_data = $level_data;
		$the_deck->project_desc = $project_desc;
		$the_deck->project_type = $project_type;
		$the_deck->end_type = $end_type;
		$the_deck->p_current_sale = $p_current_sale;
		$the_deck->p_count = $p_count;
		$the_deck->rating_per = $rating_per;
		$the_deck->days_left = $days_left;
		$the_deck->month = apply_filters('id_end_month', $end_month);
		$the_deck->day = $end_day;
		$the_deck->year = $end_year;
		$the_deck->settings = $settings;
		$the_deck->cCode = $cCode;
		$the_deck->meta_price_1 = $meta_price_1;
		$the_deck->meta_title_1 = $meta_title_1;
		$the_deck->meta_limit_1 = $meta_limit_1;
		$the_deck->level_count_1 = $level_count_1;
		$the_deck->affiliate_link = $affiliate_link;
		return $the_deck;
	}

	function mini_deck() {
		$the_project = parent::the_project();
		$prod_settings = parent::get_project_settings();
		if (empty($prod_settings)) {
			$prod_settings = getProductDefaultSettings();
		}
		$post_id = parent::get_project_postid();
		$item_fund_goal = parent::the_goal();
		$item_fund_end = parent::end_date();
		$no_levels = get_post_meta( $post_id, $name="ign_product_level_count", true );
		$project_desc = html_entity_decode(get_post_meta( $post_id, "ign_project_description", true ));
		$project_type = get_post_meta($post_id, 'ign_project_type', true);
		$end_type = get_post_meta($post_id, 'ign_end_type', true);
		$p_current_sale = parent::get_project_raised();
		$p_count = new stdClass;
		$p_count->p_number = parent::get_project_orders();
		$rating_per = parent::percent();
		$days_left = parent::days_left();
		$end_month = parent::end_month();
		$end_day = parent::end_day();
		$end_year = parent::end_year();
		//GETTING the main settings of ignitiondeck
		$settings = getSettings();
		if ($settings->id_widget_link == "") {
			$affiliate_link = "http://ignitiondeck.com";
		}
		else {
			$affiliate_link = $settings->id_widget_link;
		}
		
		$unique_widget_id = rand(101282,293773);
		//GETTING the currency symbol
		$currencyCodeValue = $prod_settings->currency_code;	
		$cCode = setCurrencyCode($currencyCodeValue);
		$the_deck = new stdClass;
		$the_deck->project = $the_project;
		$the_deck->prod_settings = $prod_settings;
		$the_deck->post_id = $post_id;
		$the_deck->item_fund_goal = $item_fund_goal;
		$the_deck->item_fund_end = $item_fund_end;
		$the_deck->no_levels = $no_levels;
		$the_deck->project_desc = $project_desc;
		$the_deck->project_type = $project_type;
		$the_deck->end_type = $end_type;
		$the_deck->p_current_sale = $p_current_sale;
		$the_deck->p_count = $p_count;
		$the_deck->rating_per = $rating_per;
		$the_deck->days_left = $days_left;
		$the_deck->month = apply_filters('id_end_month', $end_month);
		$the_deck->day = $end_day;
		$the_deck->year = $end_year;
		$the_deck->settings = $settings;
		$the_deck->cCode = $cCode;
		$the_deck->affiliate_link = $affiliate_link;
		return $the_deck;
	}

	public static function create_deck($attrs) {
		global $wpdb;
		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'ign_deck_settings (attributes) VALUES (%s)', serialize($attrs));
		$res = $wpdb->query($sql);
		if (isset($res))
			return $wpdb->insert_id;
	}

	public static function update_deck($attrs, $deck_id) {
		global $wpdb;
		$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'ign_deck_settings SET attributes = %s WHERE id = %d', serialize($attrs), $deck_id);
		$res = $wpdb->query($sql);
	}

	public static function delete_deck($deck_id) {
		global $wpdb;
		$sql = $wpdb->prepare('DELETE FROM '.$wpdb->prefix.'ign_deck_settings WHERE id = %d', $deck_id);
		$res = $wpdb->query($sql);
	}

	public static function get_deck_attrs($deck_id) {
		global $wpdb;
		$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'ign_deck_settings WHERE id = %s', $deck_id);
		$res = $wpdb->get_row($sql);
		return $res;
	}

	public static function get_deck_list() {
		global $wpdb;
		$sql = 'SELECT * FROM '.$wpdb->prefix.'ign_deck_settings';
		$res = $wpdb->get_results($sql);
		return $res;
	}
}
?>