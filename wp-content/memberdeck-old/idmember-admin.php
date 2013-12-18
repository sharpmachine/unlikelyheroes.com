<?php

add_action('admin_menu', 'memberdeck_add_menus', 5);

function memberdeck_add_menus() {
	if (current_user_can('manage_options')) {
		$settings = add_menu_page(__('MemberDeck', 'memberdeck'), 'MemberDeck', 'manage_options', 'memberdeck', 'memberdeck_settings');
		//$settings = add_submenu_page('options-general.php', 'MemberDeck', 'MemberDeck', 'manage_options', 'memberdeck-settings', 'memberdeck_settings');
		$users = add_submenu_page('memberdeck', __('Members', 'memberdeck'), __('Members', 'memberdeck'), 'manage_options', 'memberdeck-users', 'memberdeck_users');
		$payments = add_submenu_page('memberdeck', __('Gateways', 'memberdeck'), __('Gateways', 'memberdeck'), 'manage_options', 'memberdeck-gateways', 'memberdeck_gateways');
		$general = get_option('md_receipt_settings');
		if (isset($general)) {
			$general = unserialize($general);
			if (isset($general['crowdfunding']) && $general['crowdfunding'] == 1) {
				$bridge_settings = add_submenu_page('memberdeck', __('Crowdfunding', 'mdid'), __('Crowdfunding', 'mdid'), 'manage_options', 'bridge-settings', 'bridge_settings');
				add_action('admin_print_styles-'.$bridge_settings, 'mdid_admin_scripts');
			}
		}
		$gateways = get_option('memberdeck_gateways');
		if (isset($gateways)) {
			$gateways = unserialize($gateways);
			if (isset($gateways['esc']) && $gateways['esc'] == 1) {
				$sc_menu = add_submenu_page('memberdeck', __('Stripe Connect', 'mdid'), __('Stripe Connect', 'mdid'), 'manage_options', 'sc-settings', 'md_sc_settings');
				add_action('admin_print_styles-'.$sc_menu, 'md_sc_scripts');
			}
		}
		global $s3;
		if ($s3) {
			$s3_menu = add_submenu_page('memberdeck', __('S3 Settings', 'mdid'), __('S3 Settings', 'mdid'), 'manage_options', 's3-settings', 'md_s3_settings');
		}
	}
}

function memberdeck_settings() {
	//$levels = idmember_get_levels();
	global $crowdfunding;
	$gateways = get_option('memberdeck_gateways', true);
	if (!empty($gateways)) {
		$settings = unserialize($gateways);
		if (is_array($settings)) {
			$es = $settings['es'];
			$eb = $settings['eb'];
			$epp = $settings['epp'];
		}
	}
	if (isset($_POST['level-submit'])) {
		$name = esc_attr($_POST['level-name']);
		$price = esc_attr(str_replace(',', '', $_POST['level-price']));
		$credit = absint($_POST['credit-value']);
		$txn_type = esc_attr($_POST['txn-type']);
		$type = esc_attr($_POST['level-type']);
		if ($type == 'recurring') {
			$recurring = esc_attr($_POST['recurring-type']);
		}
		else {
			$recurring = '';
		}
		$plan = esc_attr($_POST['plan']);
		$license_count = $_POST['license-count'];
		$level = array('level_name' => $name,
			'level_price' => $price,
			'credit_value' => $credit,
			'txn_type' => $txn_type,
			'level_type' => $type,
			'recurring_type' => $recurring,
			'plan' => $plan,
			'license_count' => $license_count);
		if ($_POST['level-submit'] == 'Create') {
			$level_create = new ID_Member_Level();
			$new = $level_create->add_level($level);
			$level_id = $new['level_id'];
			$post_id = $new['post_id'];
			echo '<div id="message" class="updated">'.__('Level Created', 'memberdeck').' | <a href="'.get_edit_post_link($post_id).'">'.__('Edit Checkout Page', 'memberdeck').'</a></div>';
		}
		else if ($_POST['level-submit'] == 'Update') {
			$level['level_id'] = $_POST['edit-level'];
			$level_update = ID_Member_Level::update_level($level);
			$name = '';
			$price = '';
			$credit = 0;
			$txn_type = '';
			$type = '';
			$recurring = '';
			$plan = '';
			$license_count = '';
			echo '<div id="message" class="updated">'.__('Level Saved', 'memberdeck').'</div>';
		}
		
	}

	if (isset($_POST['level-delete'])) {
		$level = array('level_id' => $_POST['edit-level']);
		$delete_level = ID_Member_Level::delete_level($level);
		$name = '';
		$price = '';
		echo '<div id="message" class="updated">'.__('Level Deleted', 'memberdeck').'</div>';
	}

	if (isset($_POST['credit-submit'])) {
		$credit_name = $_POST['credit-name'];
		$credit_price = $_POST['credit-price'];
		$credit_count = $_POST['credit-count'];
		$credit = array('credit_name' => $credit_name,
			'credit_price' => $credit_price,
			'credit_count' => $credit_count);
		if (isset($_POST['credit-assign'])) {
			$credit['credit_level'] = $_POST['credit-assign'];
		}
		else {
			$credit['credit_level'] = '';
		}
		if ($_POST['credit-submit'] == 'Create') {
			$credit_create = new ID_Member_Credit($credit);
			$credit_create->add_credit();
		}
		else if ($_POST['credit-submit'] == 'Update') {
			$credit['credit_id'] = $_POST['edit-credit'];
			$credit_update = ID_Member_Credit::update_credit($credit);
			$credit_name = '';
			$credit_price = '';
			$credit_count = '';
		}
		echo '<div id="message" class="updated">'.__('Credit Saved', 'memberdeck').'</div>';
	}

	if (isset($_POST['download-submit'])) {
		$download_name = $_POST['download-name'];
		$version = $_POST['download-version'];
		if (isset($_POST['enable_occ'])) {
			$enable_occ = absint($_POST['enable_occ']);
		}
		else {
			$enable_occ = 0;
		}
		if (isset($_POST['hidden'])) {
			$hidden = absint($_POST['hidden']);
		}
		else {
			$hidden = 0;
		}
		if (isset($_POST['enable_s3'])) {
			$enable_s3 = absint($_POST['enable_s3']);
		}
		else {
			$enable_s3 = 0;
		}
		if (isset($_POST['occ_level'])) {
			$occ_level = $_POST['occ_level'];
		}
		else {
			$occ_level = null;
		}
		if (isset($_POST['id_project'])) {
			$id_project = $_POST['id_project'];
		}
		else {
			$id_project = null;
		}
		$position = $_POST['dash-position'];
		$licensed = $_POST['licensed'];
		$levels = array();
		if (isset($_POST['lassign'])) {
			foreach ($_POST['lassign'] as $lassign) {
				$levels[] = $lassign;
			}
		}
		$dlink = $_POST['download-link'];
		$ilink = $_POST['info-link'];
		$doclink = $_POST['doc-link'];
		$imagelink = $_POST['image-link'];
		$button_text = $_POST['button-text'];

		if ($_POST['download-submit'] == 'Create') {
			$download_create = new ID_Member_Download(
			null,
			$download_name,
			$version,
			$hidden,
			$enable_s3,
			$enable_occ,
			$occ_level,
			$id_project,
			$position,
			$licensed,
			$levels, 
			$dlink, 
			$ilink, 
			$doclink,
			$imagelink,
			$button_text);
			$download_create->add_download();
		}
		else if ($_POST['download-submit'] == 'Update') {
			$id = $_POST['edit-download'];
			$download = new ID_Member_Download(
				$id,
				$download_name,
				$version,
				$hidden,
				$enable_s3,
				$enable_occ,
				$occ_level,
				$id_project,
				$position,
				$licensed,
				$levels, 
				$dlink, 
				$ilink, 
				$doclink,
				$imagelink,
				$button_text
			);
			$check_dl = $download->get_download();
			if (isset($check_dl->levels)) {
				$old_levels = unserialize($download->download_levels);
				foreach ($old_levels as $new) {
					if (!in_array($new, $levels)) {
						$levels[] = $new;
					}
				}
			}
			$download->update_download();
		}
		echo '<div id="message" class="updated">'.__('Download Saved', 'memberdeck').'</div>';
	}
	if (isset($_POST['download-delete'])) {
		$download_id = $_POST['edit-download'];
		ID_Member_Download::delete_download($download_id);
		unset($_POST);
		echo '<div id="message" class="updated">'.__('Download Deleted', 'memberdeck').'</div>';
	}
	if (isset($_POST['credit-delete'])) {
			$credit = array('credit_id' => $_POST['edit-credit']);
			$delete_credit = ID_Member_Credit::delete_credit($credit);
			$name = '';
			$price = '';
			echo '<div id="message" class="updated">'.__('Credit Deleted', 'memberdeck').'</div>';
	}
	$dash = get_option('md_dash_settings');
	if (!empty($dash)) {
		$dash = unserialize($dash);
		if (isset($durl)) {
			$durl = $dash['durl'];
		}
		else {
			$durl = home_url('/dashboard');
		}
		if (isset($dash['alayout'])) {
			$alayout = $dash['alayout'];
		}
		else {
			$alayout = 'md-featured';
		}
		$aname = $dash['aname'];
		if (isset($dash['blayout'])) {
			$blayout = $dash['blayout'];
		}
		else {
			$blayout = 'md-featured';
		}
		$bname = $dash['bname'];
		if (isset($dash['clayout'])) {
			$clayout = $dash['clayout'];
		}
		else {
			$clayout = 'md-featured';
		}
		$cname = $dash['cname'];
		if (isset($dash['layout'])) {
			$layout = $dash['layout'];
		}
		else {
			$layout = 1;
		}
		if (isset($dash['powered_by'])) {
			$powered_by = $dash['powered_by'];
		}
		else {
			$powered_by = 1;
		}
	}
	if (isset($_POST['dash-submit'])) {
		$durl = $_POST['durl'];
		$alayout = $_POST['a-layout'];
		$aname = $_POST['a-name'];
		$blayout = $_POST['b-layout'];
		$bname = $_POST['b-name'];
		$clayout = $_POST['c-layout'];
		$cname = $_POST['c-name'];
		$layout = $_POST['layout-select'];
		if (isset($_POST['powered_by'])) {
			$powered_by = $_POST['powered_by'];
		}
		else {
			$powered_by = 0;
		}
		$dash = array('durl' => $durl, 'alayout' => $alayout, 'aname' => $aname, 'blayout' => $blayout, 'bname' => $bname, 'clayout' => $clayout, 'cname' => $cname, 'layout' => $layout, 'powered_by' => $powered_by);
		update_option('md_dash_settings', serialize($dash));
		echo '<div id="message" class="updated">'.__('Dashboard Saved', 'memberdeck').'</div>';
	}
	$general = get_option('md_receipt_settings');
	if (!empty($general)) {
		$general = unserialize($general);
		$coname = $general['coname'];
		$coemail = $general['coemail'];
		$crowdfunding = $general['crowdfunding'];
		$s3 = $general['s3'];
		$enable_creator = $general['enable_creator'];
	}
	if (isset($_POST['receipt-submit'])) {
		$coname = $_POST['co-name'];
		$coemail = $_POST['co-email'];
		if (isset($_POST['crowdfunding'])) {
			$crowdfunding = absint($_POST['crowdfunding']);
		}
		else {
			$crowdfunding = 0;
		}
		if (isset($_POST['s3'])) {
			$s3 = absint($_POST['s3']);
		}
		else {
			$se = 0;
		}
		if (isset($_POST['enable_creator'])) {
			$enable_creator = absint($_POST['enable_creator']);
		}
		else {
			$enable_creator = 0;
		}
		$receipts = array('coname' => $coname, 'coemail' => $coemail, 'crowdfunding' => $crowdfunding, 's3' => $s3, 'enable_creator' => $enable_creator);
		update_option('md_receipt_settings', serialize($receipts));
		echo '<div id="message" class="updated">'.__('Receipt Settings Saved', 'memberdeck').'</div>';
	}
	$crm_settings = get_option('crm_settings');
	if (!empty($crm_settings)) {
		$shipping_info = $crm_settings['shipping_info'];
		$mailchimp_key = $crm_settings['mailchimp_key'];
		$mailchimp_list = $crm_settings['mailchimp_list'];
		$enable_mailchimp = $crm_settings['enable_mailchimp'];
		$sendgrid_username = $crm_settings['sendgrid_username'];
		$sendgrid_pw = $crm_settings['sendgrid_pw'];
		$enable_sendgrid = $crm_settings['enable_sendgrid'];
		$mandrill_key = $crm_settings['mandrill_key'];
		$enable_mandrill = $crm_settings['enable_mandrill'];
	}
	if (isset($_POST['crm_submit'])) {
		if (isset($_POST['shipping_info'])) {
			$shipping_info = absint($_POST['shipping_info']);
		}
		else {
			$shipping_info = 0;
		}
		$mailchimp_key = esc_attr($_POST['mailchimp_key']);
		$mailchimp_list = esc_attr($_POST['mailchimp_list']);
		if (isset($_POST['enable_mailchimp'])) {
			$enable_mailchimp = absint($_POST['enable_mailchimp']);
		}
		else {
			$enable_mailchimp = 0;
		}
		$sendgrid_username = esc_attr($_POST['sendgrid_username']);
		$sendgrid_pw = esc_attr($_POST['sendgrid_pw']);
		if (isset($_POST['enable_sendgrid'])) {
			$enable_sendgrid = absint($_POST['enable_sendgrid']);
		}
		else {
			$enable_sendgrid = 0;
		}
		$mandrill_key = esc_attr($_POST['mandrill_key']);
		if (isset($_POST['enable_mandrill'])) {
			$enable_mandrill = absint($_POST['enable_mandrill']);
		}
		else {
			$enable_mandrill = 0;
		}
		$crm_settings = array(
			'shipping_info' => $shipping_info,
			'mailchimp_key' => $mailchimp_key,
			'mailchimp_list' => $mailchimp_list,
			'enable_mailchimp' => $enable_mailchimp,
			'sendgrid_username' => $sendgrid_username,
			'sendgrid_pw' => $sendgrid_pw,
			'enable_sendgrid' => $enable_sendgrid,
			'mandrill_key' => $mandrill_key,
			'enable_mandrill' => $enable_mandrill);
		update_option('crm_settings', $crm_settings);
		echo '<div id="message" class="updated">'.__('CRM Settings Saved', 'memberdeck').'</div>';
	}

	/***
	Export handler tied to init hook in plugin base
	***/

	include 'templates/admin/_settingsMenu.php';
}

function memberdeck_gateways() {
	
	$settings = get_option('memberdeck_gateways');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		if (is_array($settings)) {
			$pp_currency = $settings['pp_currency'];
			$pp_symbol = $settings['pp_symbol'];
			$pp_email = $settings['pp_email'];
			$test_email = $settings['test_email'];
			$paypal_redirect = $settings['paypal_redirect'];
			$pk = $settings['pk'];
			$sk = $settings['sk'];
			$tpk = $settings['tpk'];
			$tsk = $settings['tsk'];
			$test = $settings['test'];
			$epp = $settings['epp'];
			$epp_fes = $settings['epp_fes'];
			$es = $settings['es'];
			$esc = $settings['esc'];
			$bk = $settings['bk'];
			$burl = $settings['burl'];
			$btk = $settings['btk'];
			$bturl = $settings['bturl'];
			$eb = $settings['eb'];
			$eb_fes = $settings['eb_fes'];
		}
	}
	if (isset($_POST['gateway-submit'])) {
		$pp_currency = $_POST['pp-currency'];
		$pp_symbol = $_POST['pp-symbol'];
		$pp_email = $_POST['pp-email'];
		$test_email = $_POST['test-email'];
		$paypal_redirect = $_POST['paypal-redirect'];
		$pk = $_POST['pk'];
		$sk = $_POST['sk'];
		$tpk = $_POST['tpk'];
		$tsk = $_POST['tsk'];
		$bk = $_POST['bk'];
		$burl = $_POST['burl'];
		$btk = $_POST['btk'];
		$bturl = $_POST['bturl'];

		if (isset($_POST['test'])) {
			$test = $_POST['test'];
		}
		else {
			$test = '0';
		}
		if (isset($_POST['epp'])) {
			$epp = $_POST['epp'];
		}
		else {
			$epp = '0';
		}
		if (isset($_POST['epp_fes'])) {
			$epp_fes = $_POST['epp_fes'];
		}
		else {
			$epp_fes = '0';
		}
		if (isset($_POST['es'])) {
			$es = $_POST['es'];
		}
		else {
			$es = '0';
		}
		if (isset($_POST['esc'])) {
			$esc = $_POST['esc'];
		}
		else {
			$esc = 0;
		}
		if (isset($_POST['eb'])) {
			$eb = $_POST['eb'];
		}
		else {
			$eb = 0;
		}
		if (isset($_POST['eb_fes'])) {
			$eb_fes = $_POST['eb_fes'];
		}
		else {
			$eb_fes = 0;
		}
		$settings = array(
			'pp_currency' => $pp_currency,
			'pp_symbol' => $pp_symbol,
			'pp_email' => $pp_email,
			'test_email' => $test_email,
			'paypal_redirect' => $paypal_redirect,
			'pk' => $pk,
			'sk' => $sk,
			'tpk' => $tpk,
			'tsk' => $tsk,
			'test' => $test,
			'epp' => $epp,
			'epp_fes' => $epp_fes,
			'es' => $es,
			'esc' => $esc,
			'bk' => $bk,
			'burl' => $burl,
			'btk' => $btk,
			'bturl' => $bturl,
			'eb' => $eb,
			'eb_fes' => $eb_fes);
		update_option('memberdeck_gateways', serialize($settings));
		echo '<div id="message" class="updated">'.__('Gateways Saved', 'memberdeck').'</div>';
	}
	include 'templates/admin/_gatewaySettings.php';
}

function md_sc_settings() {
	// Stripe Connect Admin
	$client_id = '';
	$dev_client_id = '';
	$fee_type = 'flat';
	$app_fee = 0;
	$dev_mode = 0;
	$sc_settings = get_option('md_sc_settings');
	if (!empty($sc_settings)) {
		$sc_settings = unserialize($sc_settings);
		if (is_array($sc_settings)) {
			$client_id = $sc_settings['client_id'];
			$dev_client_id = $sc_settings['dev_client_id'];
			$fee_type = $sc_settings['fee_type'];
			$app_fee = $sc_settings['app_fee'];
			$dev_mode = $sc_settings['dev_mode'];
		}
	}
	if (isset($_POST['sc_submit'])) {
		$client_id = $_POST['client_id'];
		$dev_client_id = $_POST['dev_client_id'];
		$fee_type = $_POST['fee_type'];
		$app_fee = $_POST['app_fee'];
		if (isset($_POST['dev_mode'])) {
			$dev_mode = 1;
		}
		else {
			$dev_mode = 0;
		}
		$sc_settings = array('client_id' => $client_id,
			'dev_client_id' => $dev_client_id,
			'fee_type' => $fee_type,
			'app_fee' => $app_fee,
			'dev_mode' => $dev_mode);
		update_option('md_sc_settings', serialize($sc_settings));

	}
	if ($dev_mode == 1) {
		$link_id = $dev_client_id;
	}
	else {
		$link_id = $client_id;
	}
	include 'templates/admin/_stripeConnect.php';
}

function md_s3_settings() {
	$access_key = '';
	$secret_key = '';
	$settings = get_option('md_s3_settings');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		if (is_array($settings)) {
			$access_key = $settings['access_key'];
			$secret_key = $settings['secret_key'];
			$bucket = $settings['bucket'];
		}
	}
	if (isset($_POST['s3_submit'])) {
		$access_key = esc_attr($_POST['access_key']);
		$secret_key = esc_attr($_POST['secret_key']);
		$bucket = esc_attr($_POST['bucket']);
		$settings = array('access_key' => $access_key, 'secret_key' => $secret_key, 'bucket' => $bucket);
		update_option('md_s3_settings', serialize($settings));
	}
	include 'templates/admin/_s3Settings.php';
}

function idmember_admin_js() {
	wp_register_script('idmember-admin-js', plugins_url('js/idmember-admin.js', __FILE__));
	wp_enqueue_script('jquery');
	$ajaxurl = site_url('/wp-admin/admin-ajax.php');
	$currencies = plugins_url('/templates/admin/currencies.json', __FILE__);
	wp_localize_script('idmember-admin-js', 'md_ajaxurl', $ajaxurl);
	wp_localize_script('idmember-admin-js', 'md_currencies', $currencies);
	wp_enqueue_script('idmember-admin-js');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-ui-core', '//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
}

function mdid_admin_scripts() {
	wp_register_script('cf', plugins_url('/js/cf.js', __FILE__));
	wp_enqueue_script('cf');
}

function md_sc_scripts() {
	wp_register_script('md_sc', plugins_url('/js/mdSC.js', __FILE__));
	wp_register_style('sc_buttons', plugins_url('/lib/connect-buttons.css', __FILE__));
	wp_enqueue_script('jquery');
	wp_enqueue_script('md_sc');
	wp_enqueue_style('sc_buttons');
	$sc_settings = get_option('md_sc_settings');
	if (!empty($sc_settings)) {
		$sc_settings = unserialize($sc_settings);
		if (is_array($sc_settings)) {
			$client_id = $sc_settings['client_id'];
			$dev_client_id = $sc_settings['dev_client_id'];
			$dev_mode = $sc_settings['dev_mode'];
			if ($dev_mode == 1) {
				$md_sc_clientid = $dev_client_id;
			}
			else {
				$md_sc_clientid = $client_id;
			}
			wp_localize_script('md_sc', 'md_sc_clientid', $md_sc_clientid);
		}
	}
}

function idmember_admin_styles() {
	wp_register_style('idmember-admin', plugins_url('css/admin-style.css', __FILE__));
	wp_enqueue_style('idmember-admin');
}

function idmember_metabox_styles() {
	wp_register_script('idmember-metabox', plugins_url('js/idmember-metabox.js', __FILE__));
	wp_enqueue_script('idmember-metabox');
	$ajaxurl = admin_url('/admin-ajax.php');
	wp_localize_script('idmember-metabox', 'md_ajaxurl', $ajaxurl);
}

function idmember_load_metabox_styles() {
	global $pagenow;
	if (isset($pagenow)) {
		if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
			add_action('admin_enqueue_scripts', 'idmember_metabox_styles');
			call_level_metabox();
		}
		else if ($pagenow == 'edit-tags.php') {
			add_action('admin_enqueue_scripts', 'idmember_metabox_styles');
			call_level_metabox();
		}
	}
}

add_action ('admin_init', 'idmember_load_metabox_styles');

function idmember_load_admin_scripts() {
	// we're only going to load the js inside of the memberdeck menu
	global $pagenow;
	if (isset($pagenow)) {
		if ($pagenow == 'admin.php') {
			add_action('admin_enqueue_scripts', 'idmember_admin_js');
		}
	}
	add_action('admin_enqueue_scripts', 'idmember_admin_styles');
}

add_action('admin_init', 'idmember_load_admin_scripts');


function memberdeck_users() {
	global $pagenow;
	$users = array();
	$levels = array();
	$member = new ID_Member();
	$users = array_reverse($member->get_users());
	$total_users = count($users);
	$level = new ID_Member_Level();
	$levels = $level->get_levels();
	for ($i = 0; $i < count($levels); $i++) {
		$count = ID_Member_Level::get_level_member_count($levels[$i]->id);
		$levels[$i]->count = $count->count;
	}

	if (isset($_GET['level']) && $_GET['level'] !== '') {
		$level_filter = $_GET['level'];
		$users = ID_Member::get_level_users($level_filter);
	}

	if (isset($_GET['s']) && $_GET['s'] !== '') {
		$search = $_GET['s'];
		$users = ID_Member::get_like_users($search);
	}

	$pages = ceil(count($users) / 20);

	if ($pages == 0) {
		$pages = 1;
	}
	if (isset($_GET['p'])) {
		// if we have a page query, we get that page number
		$page = $_GET['p'];
		if ($page < $pages) {
			$nextp = $page + 1;
		}
		else {
			$nextp = $page;
		}
		
		if ($page == 1) {
			// still page 1
			$start = 0;
			$lastp = 1;
		}
		else {
			// start counting by 20, 30, 40, etc
			$start = ($page*20) - 20;
			$lastp = $page -1;
		}
		if (count($users) < 19) {
			// if we have less than a full page, we only show those users
			$count = count($users)-1;
		}
		else {
			// we have more, so we show the next 19
			// this will trigger a warning if we go over the true count
			$count = $start + 19;
		}
	}
	else {
		// start on 0 if no page set
		$page = 1;
		$start = 0;
		$nextp = 2;
		$lastp = 1;
		$count = $start + 19;
	}
	$section = 'memberdeck-users';
	$query = array('page' => $section);
	$next_query = array('page' => $section, 'p' => $nextp);
	$prev_query = array('page' => $section, 'p' => $lastp);
	$end_query = array('page' => $section, 'p' => $pages);
	$first_query = array('page' => $section, 'p' => 1);

	if (isset($search)) {
		//$query['s'] = $search;
		$next_query['s'] = $search;
		$prev_query['s'] = $search;
		$end_query['s'] = $search;
		$first_query['s'] = $search;
	}
	if (isset($level_filter)) {
		//$query['level'] = $level_filter;
		$next_query['level'] = $level_filter;
		$prev_query['level'] = $level_filter;
		$end_query['level'] = $level_filter;
		$first_query['level'] = $level_filter;
	}
	$gets = $_SERVER['QUERY_STRING'];
	$mail_url = '?'.$gets.'&send_mail=1';
	$query_string = http_build_query($query);
	$query_next = http_build_query($next_query);
	$query_prev = http_build_query($prev_query);
	$query_last = http_build_query($end_query);
	$query_first = http_build_query($first_query);

	if (isset($_GET['send_mail']) && $_GET['send_mail'] == 1) {
		$emails = array();
		foreach ($users as $user) {
			$emails[] = $user->user_email;
		}
		$back_url = admin_url('admin.php?').str_replace('send_mail=1', 'send_mail=0', $_SERVER['QUERY_STRING']);
		if (isset($_POST['send_mail'])) {
			$subject = str_replace('&#039;', "'", stripslashes(esc_attr($_POST['subject'])));
			$message = html_entity_decode(stripslashes(esc_html($_POST['message'])));
			$general = get_option('md_receipt_settings');
			if (!empty($general)) {
				$general = unserialize($general);
				$coname = $general['coname'];
				$coemail = $general['coemail'];
				foreach ($emails as $email) {
					md_send_mail($email, null, $subject, $message);
				}
				echo '<script>location.href="'.$back_url.'";</script>';
			}
		}
		include_once 'templates/admin/_sendMail.php';
	}
	else {
		include 'templates/admin/_userMenu.php';
	}
}

function bridge_settings() {
	if (class_exists('ID_Project')) {
		$projects = ID_Project::get_all_projects();

	}
	include_once 'templates/admin/_bridgeSettings.php';
}

// This function calls the metabox function inside of our levels class

function call_level_metabox() {
	$metabox = new ID_Member_Metaboxes();
}

// Bridge Metaboxes
global $crowdfunding;
if ($crowdfunding) {
	add_action('add_meta_boxes', 'mdid_project_metaboxes');
}

function mdid_project_metaboxes() {
	$screens = array('ignition_product');
	foreach ($screens as $screen) {
		add_meta_box(
			'mdid_project_activate',
			__('Make Available for Memberships', 'mdid'),
			'mdid_project_activate',
			$screen,
			'side'
		);
	}
}

function mdid_project_activate($post) {
	wp_nonce_field(plugin_basename(__FILE__), 'mdid_project_activation');
	$active = get_post_meta($post->ID, 'mdid_project_activate', true);
	if (empty($active)) {
		$active = 'no';
	}
	echo '<p><label for="mdid_project_activate">Activate for Membership</label></p>';
	echo '<p><input type="radio" name="mdid_project_activate" id="mdid_project_activate" value="yes" '.(isset($active) && $active == 'yes' ? 'checked="checked"' : '').'/> '.__('Yes', 'mdid').'</p>';
	echo '<p><input type="radio" name="mdid_project_activate" id="mdid_project_activate" value="no" '.(isset($active) && $active == 'no' ? 'checked="checked"' : '').'/> '.__('No', 'mdid').'</p>';
}

if ($crowdfunding) {
	add_action('save_post', 'md_extension_save');
}

function md_extension_save($post_id) {
	
	if (!isset($_POST['mdid_project_activation']) || !wp_verify_nonce($_POST['mdid_project_activation'], plugin_basename(__FILE__))) {
  		return;
  	}

  	if ( 'page' == $_REQUEST['post_type'] ) {
   		if ( ! current_user_can( 'edit_page', $post_id ) ) {
        	return;
    	}
  	}

  	else {
    	if ( ! current_user_can( 'edit_post', $post_id ) ) {
        	return;
        }
  	}

  	$post_id = $_POST['post_ID'];

  	$active = $_POST['mdid_project_activate'];
  	update_post_meta($post_id, 'mdid_project_activate', $active);
}

/**
* Category and Tag Metaboxes
*/

// if we're editing a category or tag, use this form
add_action('edit_category_form_fields', 'md_protect_old_cat');
add_action('edit_tag_form_fields', 'md_protect_old_cat');

function md_protect_old_cat($tag) {
	$term_id = $tag->term_id;
	$protect = get_option('protect_term_'.$term_id);
	$class = new ID_Member_Level();
  	$levels = $class->get_levels();
	if (empty($protect) || !isset($protect)) {
		$protect = 0;
	}
	else {
		$allowed = get_option('term_'.$term_id.'_allowed_levels');
		if (!empty($allowed)) {
			$array = unserialize($allowed);
		}
	}
	ob_start();
	include_once 'templates/admin/_metaboxCategory.php';
	$content = ob_get_contents();
	ob_end_clean();
	echo $content;
}
// if we're on a new category or tag, use this form
add_action('category_add_form_fields', 'md_protect_new_cat');
add_action('post_tag_add_form_fields', 'md_protect_new_cat');

function md_protect_new_cat($taxonomy) {
	$class = new ID_Member_Level();
  	$levels = $class->get_levels();
	ob_start();
	include_once 'templates/admin/_metaboxContent.php';
	$content = ob_get_contents();
	ob_end_clean();
	echo $content;
}

// save protection regardless of new tag, category or edit tag/category
add_action('edit_category', 'md_protect_cat_save');
add_action('create_category', 'md_protect_cat_save');
add_action('edit_tag', 'md_protect_cat_save');
add_action('create_post_tag', 'md_protect_cat_save');

function md_protect_cat_save($id) {
	if (isset($_POST['tag_ID'])) {
		$term_id = $_POST['tag_ID'];
	}
	else if (isset($id)) {
		$term_id = $id;
	}
	else {
		return;
	}
	if (isset($_POST['protect-choice'])) {
		// saving new
		$protect = $_POST['protect-choice'];
		if ($protect == 'yes') {
			$protect = 1;
		}
		else if ($protect == 'no') {
			$protect = 0;
		}
		if ($protect == 1) {
	  		$protected = array();
	  		if (isset($_POST['protect-level'])) {
	  			foreach ($_POST['protect-level'] as $protect_level) {
		  			$protected[] = $protect_level;
		  		}
	  		}
	  		$serialize = serialize($protected);
	  		update_option('term_'.$term_id.'_allowed_levels', $serialize);	  	
		}
		else {
		  	delete_option('term_'.$term_id.'_allowed_levels');
		  	return;
		 }
	}
	else {
		return;
	}
	update_option('protect_term_'.$term_id, $protect);
}

?>