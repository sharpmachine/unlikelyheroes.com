<?php
/*$tz = get_option('timezone_string');
date_default_timezone_set($tz);
$sql_postmeta = "SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'ign_product_name' AND meta_value = '".$product->product_name."'";
$meta_data = $wpdb->get_row( $sql_postmeta );

//GETTING the different product things
//$post_meta_query = "SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id = '".$meta_data->post_id."' AND meta_key ='ign_prod_goals'";
//$item_goals = $wpdb->get_row($post_meta_query);
$post_meta_query = "SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id = '".$meta_data->post_id."' AND meta_key ='ign_fund_goal'";
$item_fund_goal = $wpdb->get_row($post_meta_query);

$post_meta_query = "SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id = '".$meta_data->post_id."' AND meta_key ='ign_fund_end'";
$item_fund_end = $wpdb->get_row($post_meta_query);

$no_levels = get_post_meta( $meta_data->post_id, $name="ign_product_level_count", true );
$post_id = $meta_data->post_id;

//GETTING the percentage of the product purchased
$p_query = "SELECT count(*) as p_number FROM ".$wpdb->prefix . "ign_pay_info where product_id='".$product->id."'";
$p_counts = $wpdb->get_results($p_query);
$p_count = &$p_counts[0];
$p_current_sale = getTotalProductFund($product->id);
$rating_per = ($p_current_sale/$product->goal)*100;
$rating_per = ($rating_per > 100) ? 100 : $rating_per;

//echo $item_fund_end->meta_value;
$days_left = str_replace("/", "-", $item_fund_end->meta_value);
$days_left = explode("-", $days_left);
$days_left = $days_left[2]."-".$days_left[0]."-".$days_left[1];
//echo $days_left;
$days_left = (int) ((strtotime($days_left) - time())/60/60/24);

//GETTING the customers
$sql_buyers = "SELECT * FROM ".$wpdb->prefix."ign_customers WHERE product_id = '".$product->id."'";
$buyers = $wpdb->get_results($sql_buyers);

//GETTING the main settings of ignitiondeck
$settings = getSettings();

//GETTING product settings
$prod_settings = getProductSettings($product->id);*/
?>