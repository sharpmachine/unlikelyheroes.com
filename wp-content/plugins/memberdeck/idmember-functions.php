<?php
global $crowdfunding;

function is_md_network_activated() {
	// check for network activation
	$active_plugins = get_site_option( 'active_sitewide_plugins');
	if (isset($active_plugins['memberdeck/memberdeck.php'])) {
		if (is_multisite()) {
			return true;
		}
	}
	return false;
}

function md_wpdb_prefix($blog_id = null) {
	global $wpdb;
	if (!empty($blog_id) && is_md_network_activated()) {
		// set prefix for each blog install on network activation
		if ($blog_id == 1) {
			// The first blog doesn't use a prefix of 1, so use base prefix instead
			$prefix = $wpdb->base_prefix;
		}
		else {
			$prefix = $wpdb->base_prefix.$blog_id.'_';
		}
	}
	else if (!empty($blog_id)) {
		// set prefix for each intall on standard ms activation
		if ($blog_id == 1) {
			$prefix = $wpdb->prefix;
		}
		else {
			$prefix = $wpdb->prefix.$blog_id.'_';
		}
	}
	else {
		// we aren't in ms, so use standard prefix
		$prefix = $wpdb->prefix;
	}
	return $prefix;
}

function md_user_prefix() {
	global $wpdb;
	if (is_multisite()) {
		$prefix = $wpdb->base_prefix;
	}
	else {
		$prefix = $wpdb->prefix;
	}
	return $prefix;
}

function memberdeck_pp_currency() {
	$settings = get_option('memberdeck_gateways');
	$currency = array('code' => 'USD', 'symbol' => '$');
	if (!empty($settings)) {
		$array = unserialize($settings);
		if (is_array($array)) {
			$pp_currency = $array['pp_currency'];
			$pp_symbol = $array['pp_symbol'];
			$currency = array('code' => $pp_currency,
				'symbol' => $pp_symbol);
		}
	}
	return $currency;
}

function memberdeck_auto_page($level_id, $level_name) {
	$page = array(
    	'menu_order' => 100,
    	'comment_status' => 'closed',
    	'ping_status' => 'closed',
    	'post_name' => $level_name.'-checkout',
    	'post_status' => 'draft',
    	'post_title' => $level_name.' '.__('Checkout', 'memberdeck'),
    	'post_type' => 'page',
    	'post_content' => '[memberdeck_checkout product="'.$level_id.'"]');
	$get_page = get_page_by_title($level_name.' '.__('Checkout', 'memberdeck'));
	if (empty($get_page)) {
    	$post_in = wp_insert_post($page);
	    if (isset($wp_error)) {
	    	echo $wp_error;
	    }
	    else {
	    	return $post_in;
	    }
    }
    else {
    	return $get_page->ID;
    }
}

function idmember_pw_gen($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function idmember_protect_singular($content) {
	ob_start();
	global $post;
	if (is_multisite()) {
		require (ABSPATH . WPINC . '/pluggable.php');
		get_currentuserinfo();
		$md_user_levels = null;
		if (!empty($current_user)) {
			$user_id = $current_user->ID;
			$md_user = ID_Member::user_levels($user_id);
			if (!empty($md_user)) {
				$md_user_levels = unserialize($md_user->access_level);
			}
		}
	}
	else {
		global $md_user_levels;
	}
	
	if (isset($post)) {
		if (isset($post->ID)) {
			$post_id = $post->ID;
			$protected = get_post_meta($post_id, 'memberdeck_protected_posts', true);
			if (!current_user_can('manage_options')) {
				//echo 'not admin';
				if ($protected) {
					//echo 'protected';
					$login_url = site_url('/wp-login.php');
					if (!empty($md_user_levels)) {
						//echo 'they have levels';
						$access = unserialize($protected);
						$pass = false;
						foreach ($md_user_levels as $access_level) {
							if (in_array($access_level, $access)) {
								$pass = true;
								break;
							}
						}
						if (!$pass) {
							//echo 'does not match';
							include_once 'templates/_protectedPage.php';
							$content = ob_get_contents();
							//return $content;
						}
						
					}
					else {
						//echo 'no levels';
						include_once 'templates/_protectedPage.php';
						$content = ob_get_contents();
						
						//return $content;
					}
				}
				else {
					//echo 'not protected';
				}
			}
			else {
				//echo 'is admin';
			}
		}
		else {
			//echo 'no post id';
		}
	}
	ob_end_clean();
	return $content;
}

function idmember_protect_category($content) {
	if (current_user_can('manage_options')) {
		return $content;
	}
	ob_start();
	global $wp_query;
	if (is_multisite()) {
		require (ABSPATH . WPINC . '/pluggable.php');
		get_currentuserinfo();
		$md_user_levels = null;
		if (!empty($current_user)) {
			$user_id = $current_user->ID;
			$md_user = ID_Member::user_levels($user_id);
			if (!empty($md_user)) {
				$md_user_levels = unserialize($md_user->access_level);
			}
		}
	}
	else {
		global $md_user_levels;
	}
	//print_r($wp_query);
	$tag_terms = get_terms(array('category', 'post_tag'));
	//print_r($tag_terms);
	$term_array = array();
	$i = 0;
	if (is_array($tag_terms)) {
		//print_r($tag_terms);
		foreach ($tag_terms as $object) {
			//echo $k." = ".$v."<br/>";
			//print_r($object);
			//if ($object == 'term_id') {
				$term_id = $object->term_id;
				//echo $term_id;
				$term_protected = get_option('protect_term_'.$term_id);
				//echo $term_protected;
				if ($term_protected == true) {
					if (is_user_logged_in()) {
					//echo 'protected';
						$term_array[$i]['term_id'] = $term_id;
						$allowed = get_option('term_'.$term_id.'_allowed_levels');
						if (isset($allowed)) {
							$array = unserialize($allowed);
							$term_array[$i]['terms'] = $array;
							//print_r($md_user_levels);
							foreach ($term_array as $array) {
								//print_r($array);
								foreach ($md_user_levels as $level) {
									if (in_array($level, $array['terms'])) {
										$pass = true;
									}
									else {
										$fail = true;
									}
								}
							}
							if (!isset($pass)) {
								// user doesn't own any required level
								include_once 'templates/_protectedPage.php';
								$content = ob_get_contents();
							}
						}
						else {
							// user doesn't own any levels
							include_once 'templates/_protectedPage.php';
							$content = ob_get_contents();
						}
					}
					else {
						// user not logged in
						include_once 'templates/_protectedPage.php';
						$content = ob_get_contents();
					}
				}
			//}
			$i++;
		}
	}
	//print_r($term_array);
	/*if (!empty($term_array)) {
		foreach ($term_array as $term_levels) {
			if (!empty($md_user_levels)) {
				foreach ($md_user_levels as $md_level) {
					if (in_array($md_level, $term_levels['terms'])) {
						$fail = true;
					}
					else {
						$pass = true;
					}
				}
			}
		}
	}*/
	ob_end_clean();
	return $content;
}

add_action('posts_selection', 'move_to_protect');

function move_to_protect() {
	if (is_category()) {
		add_filter('the_content', 'idmember_protect_category');
	}
	else if (is_tax()) {
		//echo 'tax';
	}
	else if (is_archive()) {
		//echo 'archive';
	}
	else if (is_singular()) {
		$theme_name = wp_get_theme();
		$textdomain = $theme_name->get('Template');
		if ($textdomain == 'fivehundred') {
			md_fh_protection_check();
		}
		else {
			add_filter('the_content', 'idmember_protect_singular');
		}
	}
	else {
		//echo 'else';
	}
}

function md_fh_protection_check() {
	global $post;
	if (is_multisite()) {
		require (ABSPATH . WPINC . '/pluggable.php');
		get_currentuserinfo();
		$md_user_levels = null;
		if (!empty($current_user)) {
			$user_id = $current_user->ID;
			$md_user = ID_Member::user_levels($user_id);
			if (!empty($md_user)) {
				$md_user_levels = unserialize($md_user->access_level);
			}
		}
	}
	else {
		global $md_user_levels;
	}
	
	if (isset($post)) {
		if (isset($post->ID)) {
			$post_id = $post->ID;
			$protected = get_post_meta($post_id, 'memberdeck_protected_posts', true);
			if (!current_user_can('manage_options')) {
				//echo 'not admin';
				if ($protected) {
					$dash = get_option('md_dash_settings');
					if (!empty($dash)) {
						$dash = unserialize($dash);
						if (isset($durl)) {
							$durl = $dash['durl'];
						}
						else {
							$durl = home_url().'/dashboard';
						}
					}
					//echo 'protected';
					$login_url = site_url('/wp-login.php');
					if (!empty($md_user_levels)) {
						//echo 'they have levels';
						$access = unserialize($protected);
						$pass = false;
						foreach ($md_user_levels as $access_level) {
							if (in_array($access_level, $access)) {
								$pass = true;
								break;
							}
						}
						if (!$pass) {
							//echo 'does not match';
							echo '<script>location.href="'.$durl.'";</script>';
						}
						
					}
					else {
						//echo 'no levels';
						echo '<script>location.href="'.$durl.'";</script>';
					}
				}
				else {
					//echo 'not protected';
				}
			}
			else {
				//echo 'is admin';
			}
		}
		else {
			//echo 'no post id';
		}
	}
}

function idmember_protect_bbp($content) {
	global $post;
	if (isset($post)) {
		$post_id = $post->ID;
		$post_parent = $post->post_parent;
		$protected = get_post_meta($post_id, 'memberdeck_protected_posts', true);
		$parent_protected = get_post_meta($post_parent, 'memberdeck_protected_posts', true);
		if (!empty($protected) || !empty($parent_protected)) {
			$access = array();
			$parent_access = array();
			ob_start();
			if (!empty($protected)) {
				$access = unserialize($protected);
				//print_r($access);
			}
			if (!empty($parent_protected)) {
				$parent_access = unserialize($parent_protected);
				//print_r($parent_access);
			}
			$login_url = site_url('/wp-login.php');
			if (is_user_logged_in()) {
				global $current_user;
				get_currentuserinfo();
				$member = new ID_Member();
				$member_levels = $member->user_levels($current_user->ID);
				$unserialized = unserialize($member_levels->access_level);

				if (empty($unserialized) && !current_user_can('manage_options')) {
					//echo 'no levels';
					$unserialized = array();
					include_once 'templates/_protectedPage.php';
					$content = ob_get_contents();
				}
				foreach ($unserialized as $check) {
					if ( !in_array($check, $access) && !in_array($check, $parent_access) && !current_user_can('manage_options')) {
						$fail = true;
					}
					else {
						$pass = true;
					}

				}
				if (!isset($pass)) {
					//echo 'does not match';
					include_once 'templates/_protectedPage.php';
					$content = ob_get_contents();
				}
			}
			else {
				//echo 'not logged in';
				include_once 'templates/_protectedPage.php';
				$content = ob_get_contents();
			}
			ob_end_clean();
		}
	}
	return $content;
}

add_filter('bbp_replace_the_content', 'idmember_protect_bbp');

add_action( 'wp_login_failed', 'md_bad_login' );  // hook failed login
function md_bad_login( $username ) {
	$referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
	// if there's a valid referrer, and it's not the default log-in screen
	if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
		$settings = get_option('md_dash_settings');
		if (!empty($settings)) {
			$settings = unserialize($settings);
			if (isset($settings['durl'])) {
				$durl = $settings['durl'];
			}
			else {
				$durl = $home_url('/dashboard');
			}
		}
		wp_redirect((isset($durl) ? $durl : home_url('/dashboard')) . '/?login_failure=1' );
		exit;
	}
}

function memberdeck_login_form($content) {
	if (isset($_GET['login_form']) && $_GET['login_form'] == 1) {
		$content = wp_login_form(array('redirect' => get_permalink()));
	}
	return $content;
}

add_filter('the_content', 'memberdeck_login_form');

function memberdeck_profile_check() {
	if (is_user_logged_in()) {
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;
		$nicename = $current_user->display_name;
		$user_firstname = $current_user->user_firstname;
		$user_lastname = $current_user->user_lastname;
		$email = $current_user->user_email;
		global $customer_id;
		global $instant_checkout;
		if (isset($_GET['edit-profile']) && $_GET['edit-profile'] == $user_id) {
			if (isset($_POST['edit-profile-submit'])) {
				$user_firstname = esc_attr($_POST['first-name']);
				$user_lastname = esc_attr($_POST['last-name']);
				$email = esc_attr($_POST['email']);
				$nicename = esc_attr($_POST['nicename']);
				$url = esc_attr($_POST['url']);
				$description = esc_attr($_POST['description']);
				$url = esc_attr($_POST['url']);
				$twitter = esc_attr($_POST['twitter']);
				$facebook = esc_attr($_POST['facebook']);
				$google = esc_attr($_POST['google']);
				if (isset($_POST['instant_checkout'])) {
					$instant_checkout = absint($_POST['instant_checkout']);
				}
				else {
					$instant_checkout = 0;
				}

				$pw = esc_attr($_POST['pw']);
				$cpw = esc_attr($_POST['cpw']);

				if ($pw == $cpw) {
					if ($pw !== '') {
						wp_update_user(array(
						'ID' => $user_id,
						'user_email' => $email,
						'user_pass' => $pw,
						'first_name' => $user_firstname,
						'last_name' => $user_lastname,
						'display_name' => $nicename,
						'description' => $description,
						'user_url' => $url));
					}
					else {
						wp_update_user(array(
						'ID' => $user_id,
						'user_email' => $email,
						'first_name' => $user_firstname,
						'last_name' => $user_lastname,
						'display_name' => $nicename,
						'description' => $description,
						'user_url' => $url));
					}
				}
				update_user_meta($user_id, 'instant_checkout', $instant_checkout);
				update_user_meta($user_id, 'twitter', $twitter);
				update_user_meta($user_id, 'facebook', $facebook);
				update_user_meta($user_id, 'google', $google);
			}
			add_filter('the_content', 'memberdeck_profile_form');
		}
		else if (isset($_GET['edit-profile'])) {
			echo '<script>location.href="?edit-profile='.$user_id.'";</script>';
		}
	}
}

add_action('init', 'memberdeck_profile_check');

function memberdeck_profile_form($content) {
	ob_start();
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;
	$nicename = $current_user->display_name;
	$user_firstname = $current_user->user_firstname;
	$user_lastname = $current_user->user_lastname;
	$email = $current_user->user_email;
	$usermeta = get_user_meta($user_id);
	$url = $current_user->user_url;
	if (isset($usermeta['description'][0]))
		$description = $usermeta['description'][0];
	$url = $current_user->user_url;
	if (isset($usermeta['twitter'][0]))
		$twitter = $usermeta['twitter'][0];
	if (isset($usermeta['facebook']))
		$facebook = $usermeta['facebook'][0];
	if (isset($usermeta['google']))
		$google = $usermeta['google'][0];
	$settings = get_option('memberdeck_gateways');
	if (isset($settings)) {
		$options = unserialize($settings);
		if (is_array($options)) {
			$es = $options['es'];
			$eb = $options['eb'];
			if ($es == 1) {
				global $customer_id;
			}
			else if ($eb == 1) {
				global $balanced_customer_id;
				$customer_id = $balanced_customer_id;
			}
		}
	}

	$general = get_option('md_receipt_settings');
	
	global $instant_checkout;
	//$instant_checkout = get_user_meta($user_id, 'instant_checkout', true);
	if (isset($_POST['edit-profile-submit'])) {
		$user_firstname = esc_attr($_POST['first-name']);
		$user_lastname = esc_attr($_POST['last-name']);
		$email = esc_attr($_POST['email']);
		$nicename = esc_attr($_POST['nicename']);
		$description = esc_attr($_POST['description']);
		$url = esc_attr($_POST['url']);
		$twitter = esc_attr($_POST['twitter']);
		$facebook = esc_attr($_POST['facebook']);
		$google = esc_attr($_POST['google']);
		if (isset($_POST['pw'])) {
			$pw = esc_attr($_POST['pw']);
		}
		if (isset($_POST['cpw'])) {
			$cpw = esc_attr($_POST['cpw']);
		}
		$description = esc_attr($_POST['description']);
		if (isset($_POST['instant_checkout'])) {
			$instant_checkout = absint($_POST['instant_checkout']);
		}
		else {
			$instant_checkout = 0;
		}
	}

	if (isset($pw) && $pw !== $cpw) {
		$error = __('Passwords do not match', 'memberdeck');
	}
	else if (isset($_GET['edited'])) {
		$success = __('Profile Updated!', 'memberdeck');
	}

	include 'templates/_editProfile.php';
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_action('init', 'md_shipping_on_profile');

function md_shipping_on_profile() {
	$crm_settings = get_option('crm_settings');
	if (!empty($crm_settings)) {
		$shipping_info = $crm_settings['shipping_info'];
		if (isset($shipping_info) && $shipping_info == '1') {
			add_action('md_profile_extrafields', 'md_shipping_info');
		}
	}
}

function md_shipping_info() {
	global $current_user;
	get_currentuserinfo();
	$user_id = $current_user->ID;

	$shipping_info = get_user_meta($user_id, 'md_shipping_info', true);
	if (isset($_POST['edit-profile-submit'])) {
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;

		$address = esc_attr($_POST['address']);
		$address_two = esc_attr($_POST['address_two']);
		$city = esc_attr($_POST['city']);
		$state = esc_attr($_POST['state']);
		$zip = esc_attr($_POST['zip']);
		$country = esc_attr($_POST['country']);

		$shipping_info = array(
			'address' => $address,
			'address_two' => $address_two,
			'city' => $city,
			'state' => $state,
			'zip' => $zip,
			'country' => $country
			);

		update_user_meta($user_id, 'md_shipping_info', $shipping_info);
	}
	include_once 'templates/_shippingInfo.php';
}

function idmember_login_redirect($user_login, $user) {
	// not needed yet - in wp login form
}

//add_action('wp_login', 'idmember_login_redirect', 10, 2);

add_filter('login_redirect', 'memberdeck_login_redirect', 3, 3);

function md_stripe_currency_symbol($currency) {
	switch($currency) {
		case 'USD':
			$ccode = '$';
			break;
		case 'EUR':
			$ccode = '&euro;';
			break;
		case 'GBP':
			$ccode = '&pound;';
			break;
		case 'CAD':
			$ccode = '$';
			break;
		case 'AUD':
			$ccode = '$';
			break;
	}
	return $ccode;
}

function memberdeck_login_redirect($redirect_to, $request, $user) {
	//is there a user to check?
    if( isset( $user->roles ) && is_array( $user->roles ) ) {
        //check for admins
        if( in_array( "administrator", $user->roles ) ) {
            // redirect them to the default place
            return $redirect_to;
        } 
        else {
        	$names = get_option('md_dash_settings');
			if (!empty($names)) {
				$names = unserialize($names);
				if (isset($names['durl'])) {
            		return $names['durl'];
            	}
            	else {
            		return $home_url('/dashboard');
            	}
            	//get_permalink(get_page_by_title('Dashboard'));
            }
            else {
            	return $redirect_to;
            }
        }
    }
    else {
        return $redirect_to;
    }
}

function idmember_purchase_receipt($user_id, $price, $level_id, $source) {
	error_reporting(0);
	$settings = get_option('md_receipt_settings');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		$coname = $settings['coname'];
		$coemail = $settings['coemail'];
	}
	else {
		$coname = '';
		$coemail = '';
	}
	$currency = 'USD';
	$symbol = '$';
	if ($source == 'stripe') {
		$settings = get_option('memberdeck_gateways');
		if (!empty($settings)) {
			$settings = unserialize($settings);
			if (is_array($settings)) {
				$currency = $settings['stripe_currency'];
				$symbol = md_stripe_currency_symbol($stripe_currency);
			}
		}
	}
	$user = get_userdata($user_id);
	$email = $user->user_email;
	$fname = $user->first_name;
	$lname = $user->last_name;

	/*
	** Check CRM Settings
	*/

	$crm_settings = get_option('crm_settings');
	if (!empty($crm_settings)) {
		$sendgrid_username = $crm_settings['sendgrid_username'];
		$sendgrid_pw = $crm_settings['sendgrid_pw'];
		$enable_sendgrid = $crm_settings['enable_sendgrid'];
		$mandrill_key = $crm_settings['mandrill_key'];
		$enable_mandrill = $crm_settings['enable_mandrill'];
	}

	/* 
	** Mail Function
	*/

	// Sending email to customer on the completion of order
	$subject = __('Payment Receipt', 'memberdeck');
	$headers = __('From', 'memberdeck').': '.$coname.' <'.$coemail.'>' . "\n";
	$headers .= __('Reply-To', 'memberdeck').': ' . $coemail ."\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
	$message = '<html><body>';
	$message .= '<div style="padding:10px;background-color:#f2f2f2;">
					<div style="padding:10px;border:1px solid #eee;background-color:#fff;">
					<h2>'.$coname.' '.__('Payment Receipt', 'memberdeck').'</h2>

						<div style="margin:10px;">

 							'.__('Hello', 'memberdeck'). ' ' . $fname .' '. $lname .', <br /><br />
  
  							'.__('You have successfully made a payment of ', 'memberdeck').$symbol.number_format($price, 2, '.', ',').' '.$currency.'<br /><br />
    
    						'.__('This transaction should appear on your Credit Card statement as', 'memberdeck').': '.$coname.'<br /><br />
    						<div style="border: 1px solid #333333; width: 500px;">
    							<table width="500" border="0" cellspacing="0" cellpadding="5">
          							<tr bgcolor="#333333" style="color: white">
				                        <td width="100">'.__('DATE', 'memberdeck').'</td>
				                        <td width="275">'.__('DESCRIPTION', 'memberdeck').'</td>
				                        <td width="125">'.__('AMOUNT', 'memberdeck').'</td>
				                    </tr>
			                         <tr>
			                           <td width="200">'.date("D, M j").'</td>
			                           <td width="275">'.$coname.'</td>
			                           <td width="125">'.__($symbol, 'memberdeck').number_format($price, 2, '.', ',').' '.__($currency, 'memberdeck').'</td>
			                      	</tr>
    							</table>
    						</div>
    						<br /><br />
    						'.__('Thank you for your support!', 'memberdeck').'<br />
    						'.__('The', 'memberdeck').' '.$coname.' '.__('team', 'memberdeck').'
						</div>

						<table rules="all" style="border-color:#666;width:80%;margin:20px auto;" cellpadding="10">

    					<!--table rows-->

						</table>

		               ---------------------------------<br />
		               '.$coname.'<br />
		               <a href="mailto:'.$coemail.'">'.$coemail.'</a>
		           

		            </div>
		        </div>';
	$message .= '</body></html>';
	if (isset($enable_sendgrid) && $enable_sendgrid == 1) {
		require_once MD_PATH.'lib/sendgrid-php-master/lib/SendGrid.php';
		require_once MD_PATH.'lib/unirest-php-master/lib/Unirest.php';
		SendGrid::register_autoloader();
		$sendgrid = new SendGrid($sendgrid_username, $sendgrid_pw);
		$mail = new SendGrid\Email();
		$mail->
			addTo($email)->
			setFrom($coemail)->
			setSubject($subject)->
			setText(null)->
			addHeader($headers)->
			setHtml($message);
		$go = $sendgrid->web->send($mail);
	}
	else if (isset($enable_mandrill) && $enable_mandrill == 1) {
		try {
			require_once MD_PATH.'lib/mandrill-php-master/src/Mandrill.php';
			$mandrill = new Mandrill($mandrill_key);
			$msgarray = array(
				'html' => $message,
				'text' => '',
				'subject' => $subject,
				'from_email' => $coemail,
				'from_name' => $coname,
				'to' => array(
					array(
						'email' => $email,
						'name' => $fname.' '.$lname,
						'type' => 'to'
						)
					),
				'headers' => array(
					'MIME-Version' => '1.0',
					'Content-Type' => 'text/html',
					'charset' =>  'ISO-8859-1',
					'Reply-To' => $coemail
					)
					);
			$async = false;
			$ip_pool = null;
			$send_at = null;
			$go = $mandrill->messages->send($msgarray, $async, $ip_pool, $send_at);
		}
		catch(Mandrill_Error $e) {
		    // Mandrill errors are thrown as exceptions
		    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
		    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
		    throw $e;
		}
	}
	else {
		//echo $email."<br>".$subject."<br>".$message;
		mail($email, $subject, $message, $headers);
	}
}

add_action('idmember_receipt', 'idmember_purchase_receipt', 1, 4);

function memberdeck_preauth_receipt($user_id, $price, $level_id, $source) {
	error_reporting(0);
	global $crowdfunding;
	$settings = get_option('md_receipt_settings');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		$coname = $settings['coname'];
		$coemail = $settings['coemail'];
	}
	else {
		$coname = '';
		$coemail = '';
	}
	$currency = 'USD';
	$symbol = '$';
	if ($source == 'stripe') {
		$settings = get_option('memberdeck_gateways');
		if (!empty($settings)) {
			$settings = unserialize($settings);
			if (is_array($settings)) {
				$currency = $settings['stripe_currency'];
				$symbol = md_stripe_currency_symbol($stripe_currency);
			}
		}
	}
	$user = get_userdata($user_id);
	$email = $user->user_email;
	$fname = $user->first_name;
	$lname = $user->last_name;

	/*
	** Check CRM Settings
	*/

	$crm_settings = get_option('crm_settings');
	if (!empty($crm_settings)) {
		$sendgrid_username = $crm_settings['sendgrid_username'];
		$sendgrid_pw = $crm_settings['sendgrid_pw'];
		$enable_sendgrid = $crm_settings['enable_sendgrid'];
		$mandrill_key = $crm_settings['mandrill_key'];
		$enable_mandrill = $crm_settings['enable_mandrill'];
	}

	$level = ID_Member_Level::get_level($level_id);
	$credit_data = ID_Member_Credit::get_credit_by_level($level_id);
	if (!empty($credit_data)) {
		$credit_value = $credit_data->credit_count;
	}
	$level_name = $level->level_name;

	$cf_level = false;
	if ($crowdfunding) {
		$cf_assignments = get_assignments_by_level($level_id);
		if (!empty($cf_assignments)) {
			$project_id = $cf_assignments[0]->project_id;
			$project = new ID_Project($project_id);
			$the_project = $project->the_project();
			$post_id = $project->get_project_postid();
			$end = get_post_meta($post_id, 'ign_fund_end', true);
			$cf_level = true;
		}
	}

	/* 
	** Mail Function
	*/

	// Sending email to customer on the completion of order
	$subject = $level_name.' '.__('Pre-Order Confirmation', 'memberdeck');
	$headers = __('From', 'memberdeck').': '.$coname.' <'.$coemail.'>' . "\n";
	$headers .= __('Reply-To', 'memberdeck').': ' . $coemail ."\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
	$message = '<html><body>';
	$message .= '<div style="padding:10px;background-color:#f2f2f2;">
					<div style="padding:10px;border:1px solid #eee;background-color:#fff;">
					<h2>'.$coname.' '.__('Pre-Order Confirmation', 'memberdeck').'</h2>

						<div style="margin:10px;">

 							'.__('Hello', 'memberdeck'). ' ' . $fname .' '. $lname .', <br /><br />
  
  							'.__('This is a confirmation of your pre-order of ', 'memberdeck').$level_name.' for '.$symbol.number_format($price, 2, '.', ',').' '.$currency.'<br /><br />';
  	if (isset($credit_value) && $credit_value > 0) {
   		$message .=			__('You have earned a total of ', 'memberdeck').$credit_value.' '.($credit_value > 1 ? __('credits for this purchase', 'memberdeck') : 'credit for this purchase').'<br/><br/>';
    }
    if ($cf_level) {
    	$message .=			__('If funding is successful, this charge will process on ', 'memberdeck').$end.'<br/><br/>';
    }
    $message .=				'<div style="border: 1px solid #333333; width: 500px;">
    							<table width="500" border="0" cellspacing="0" cellpadding="5">
          							<tr bgcolor="#333333" style="color: white">
				                        <td width="100">'.__('DATE', 'memberdeck').'</td>
				                        <td width="275">'.__('DESCRIPTION', 'memberdeck').'</td>
				                        <td width="125">'.__('AMOUNT', 'memberdeck').'</td>
				                    </tr>
			                         <tr>
			                           <td width="200">'.date("D, M j").'</td>
			                           <td width="275">'.$level_name.'</td>
			                           <td width="125">'.__($symbol, 'memberdeck').number_format($price, 2, '.', ',').' '.__($currency, 'memberdeck').'</td>
			                      	</tr>
    							</table>
    						</div>
    						<br /><br />
    						'.__('Thank you for your support!', 'memberdeck').'<br />
    						'.__('The', 'memberdeck').' '.$coname.' '.__('team', 'memberdeck').'
						</div>

						<table rules="all" style="border-color:#666;width:80%;margin:20px auto;" cellpadding="10">

    					<!--table rows-->

						</table>

		               ---------------------------------<br />
		               '.$coname.'<br />
		               <a href="mailto:'.$coemail.'">'.$coemail.'</a>
		           

		            </div>
		        </div>';
	$message .= '</body></html>';
	if (isset($enable_sendgrid) && $enable_sendgrid == 1) {
		require_once MD_PATH.'lib/sendgrid-php-master/lib/SendGrid.php';
		require_once MD_PATH.'lib/unirest-php-master/lib/Unirest.php';
		SendGrid::register_autoloader();
		$sendgrid = new SendGrid($sendgrid_username, $sendgrid_pw);
		$mail = new SendGrid\Email();
		$mail->
			addTo($email)->
			setFrom($coemail)->
			setSubject($subject)->
			setText(null)->
			addHeader($headers)->
			setHtml($message);
		$go = $sendgrid->web->send($mail);
	}
	else if (isset($enable_mandrill) && $enable_mandrill == 1) {
		try {
			require_once MD_PATH.'lib/mandrill-php-master/src/Mandrill.php';
			$mandrill = new Mandrill($mandrill_key);
			$msgarray = array(
				'html' => $message,
				'text' => '',
				'subject' => $subject,
				'from_email' => $coemail,
				'from_name' => $coname,
				'to' => array(
					array(
						'email' => $email,
						'name' => $fname.' '.$lname,
						'type' => 'to'
						)
					),
				'headers' => array(
					'MIME-Version' => '1.0',
					'Content-Type' => 'text/html',
					'charset' =>  'ISO-8859-1',
					'Reply-To' => $coemail
					)
					);
			$async = false;
			$ip_pool = null;
			$send_at = null;
			$go = $mandrill->messages->send($msgarray, $async, $ip_pool, $send_at);
		}
		catch(Mandrill_Error $e) {
		    // Mandrill errors are thrown as exceptions
		    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
		    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
		    throw $e;
		}
	}
	else {
		//echo $email."<br>".$subject."<br>".$message;
		mail($email, $subject, $message, $headers);
	}
}

add_action('memberdeck_preauth_receipt', 'memberdeck_preauth_receipt', 1, 4);

function idmember_registration_email($user_id, $reg_key) {
	$settings = get_option('md_receipt_settings');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		$coname = $settings['coname'];
		$coemail = $settings['coemail'];
	}
	else {
		$coname = '';
		$coemail = '';
	}
	$user = get_userdata($user_id);
	$email = $user->user_email;
	$fname = $user->first_name;
	$lname = $user->last_name;

	/*
	** Check CRM Settings
	*/

	$crm_settings = get_option('crm_settings');
	if (!empty($crm_settings)) {
		$sendgrid_username = $crm_settings['sendgrid_username'];
		$sendgrid_pw = $crm_settings['sendgrid_pw'];
		$enable_sendgrid = $crm_settings['enable_sendgrid'];
		$mandrill_key = $crm_settings['mandrill_key'];
		$enable_mandrill = $crm_settings['enable_mandrill'];
	}

	$level = ID_Member_Level::get_level($level_id);
	$credit_data = ID_Member_Credit::get_credit_by_level($level_id);
	if (!empty($credit_data)) {
		$credit_value = $credit_data->credit_count;
	}
	$level_name = $level->level_name;

	/* 
	** Mail Function
	*/

	// Sending email to customer on the completion of order
	$subject = __('Complete Your Registration', 'memberdeck');
	$headers = __('From', 'memberdeck').': '.$coname.' <'.$coemail.'>' . "\n";
	$headers .= __('Reply-To', 'memberdeck').': '.$coemail."\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
	$message = '<html><body>';
	$message .= '<div style="padding:10px;background-color:#f2f2f2;">
					<div style="padding:10px;border:1px solid #eee;background-color:#fff;">
					<h2>'.$coname.' '.__('Payment Receipt', 'memberdeck').'</h2>

						<div style="margin:10px;">

 							'.__('Hello', 'memberdeck').' '. $fname .' '. $lname .', <br /><br />
  
  							'.__('Thank you for your purchase of ', 'memberdeck').' '.$level_name.'.<br /><br />
    
    						'.__('Your order is almost ready to go. We just need you to click the link
    						below to complete your registration', 'memberdeck').':
    						<br /><br />
    						'.home_url("/").'?reg='.$reg_key.'
    						<br /><br />';
    if (isset($credit_value) && $credit_value > 0) {
   	$message .=
    						__('You have earned a total of ', 'memberdeck').$credit_value.' '.($credit_value > 1 ? __('credits for this purchase', 'memberdeck') : 'credit for this purchase');
    }
    $message .=				__('Thank you for your support', 'memberdeck').'!<br />
    						'.__('The', 'memberdeck').' '.$coname.' '.__('team', 'memberdeck').'
						</div>

						<table rules="all" style="border-color:#666;width:80%;margin:20px auto;" cellpadding="10">

    					<!--table rows-->

						</table>

		               ---------------------------------<br />
		               '.$coname.'<br />
		               <a href="mailto:'.$coemail.'">'.$coemail.'</a>
		           

		            </div>
		        </div>';
	$message .= '</body></html>';
	if (isset($enable_sendgrid) && $enable_sendgrid == 1) {
		require_once MD_PATH.'lib/sendgrid-php-master/lib/SendGrid.php';
		require_once MD_PATH.'lib/unirest-php-master/lib/Unirest.php';
		SendGrid::register_autoloader();
		$sendgrid = new SendGrid($sendgrid_username, $sendgrid_pw);
		$mail = new SendGrid\Email();
		$mail->
			addTo($email)->
			setFrom($coemail)->
			setSubject($subject)->
			setText(null)->
			addHeader($headers)->
			setHtml($message);
		$go = $sendgrid->web->send($mail);
	}
	else if (isset($enable_mandrill) && $enable_mandrill == 1) {
		try {
			require_once MD_PATH.'lib/mandrill-php-master/src/Mandrill.php';
			$mandrill = new Mandrill($mandrill_key);
			$msgarray = array(
				'html' => $message,
				'text' => '',
				'subject' => $subject,
				'from_email' => $coemail,
				'from_name' => $coname,
				'to' => array(
					array(
						'email' => $email,
						'name' => $fname.' '.$lname,
						'type' => 'to'
						)
					),
				'headers' => array(
					'MIME-Version' => '1.0',
					'Content-Type' => 'text/html',
					'charset' =>  'ISO-8859-1',
					'Reply-To' => $coemail
					)
					);
			$async = false;
			$ip_pool = null;
			$send_at = null;
			$go = $mandrill->messages->send($msgarray, $async, $ip_pool, $send_at);
		}
		catch(Mandrill_Error $e) {
		    // Mandrill errors are thrown as exceptions
		    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
		    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
		    throw $e;
		}
	}
	else {
		//echo $email."<br>".$subject."<br>".$message;
		mail($email, $subject, $message, $headers);
	}
}

add_action('idmember_registration_email', 'idmember_registration_email', 1, 2);

function md_send_mail($email, $headers = null, $subject, $message) {
	$settings = get_option('md_receipt_settings');
	if (!empty($settings)) {
		$settings = unserialize($settings);
		$coname = $settings['coname'];
		$coemail = $settings['coemail'];
	}
	else {
		$coname = '';
		$coemail = '';
	}

	/*
	** Check CRM Settings
	*/

	$crm_settings = get_option('crm_settings');
	if (!empty($crm_settings)) {
		$sendgrid_username = $crm_settings['sendgrid_username'];
		$sendgrid_pw = $crm_settings['sendgrid_pw'];
		$enable_sendgrid = $crm_settings['enable_sendgrid'];
		$mandrill_key = $crm_settings['mandrill_key'];
		$enable_mandrill = $crm_settings['enable_mandrill'];
	}

	if (isset($enable_sendgrid) && $enable_sendgrid == 1) {
		require_once MD_PATH.'lib/sendgrid-php-master/lib/SendGrid.php';
		require_once MD_PATH.'lib/unirest-php-master/lib/Unirest.php';
		SendGrid::register_autoloader();
		$sendgrid = new SendGrid($sendgrid_username, $sendgrid_pw);
		$mail = new SendGrid\Email();
		$mail->
			addTo($email)->
			setFrom($coemail)->
			setSubject($subject)->
			setText(null)->
			//addHeader('MIME-Version', '1.0')->
			//addHeader('Content-Type', 'text/html')->
			//addHeader('charset', 'ISO-8859-1')->
			setReplyTo($coemail)->
			setHtml($message);
		$go = $sendgrid->web->send($mail);
	}
	else if (isset($enable_mandrill) && $enable_mandrill == 1) {
		try {
			require_once MD_PATH.'lib/mandrill-php-master/src/Mandrill.php';
			$mandrill = new Mandrill($mandrill_key);
			$msgarray = array(
				'html' => $message,
				'text' => '',
				'subject' => $subject,
				'from_email' => $coemail,
				'from_name' => $coname,
				'to' => array(
					array(
						'email' => $email,
						'name' => (isset($fname) && isset($lname) ? $fname.' '.$lname : ''),
						'type' => 'to'
						)
					),
				'headers' => array(
					'MIME-Version' => '1.0',
					'Content-Type' => 'text/html',
					'charset' =>  'ISO-8859-1',
					'Reply-To' => $coemail
					)
				);
			$async = false;
			$ip_pool = null;
			$send_at = null;
			$go = $mandrill->messages->send($msgarray, $async, $ip_pool, $send_at);
		}
		catch(Mandrill_Error $e) {
		    // Mandrill errors are thrown as exceptions
		    echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
		    // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
		    throw $e;
		}
	}
	else {
		//echo $email."<br>".$subject."<br>".$message;
		if ($headers = null) {
			$headers = __('From', 'memberdeck').': '.$coname.' <'.$coemail.'>' . "\n";
			$headers .= __('Reply-To', 'memberdeck').': '.$coemail."\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
		}
		mail($email, $subject, $message, $headers);
	}
}

add_action('widgets_init', 'memberdeck_dashboard_widgets');

function memberdeck_dashboard_widgets() {

	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'name' => __('Dashboard Sidebar', 'memberdeck'),
			'id' => 'dashboard-sidebar',
			'description' => __('Appears on the Dashboard below the User Profile', 'memberdeck'),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="dashboard-widget">',
			'after_title' => '</h3>',
		));
	}
}

add_action('memberdeck_stripe_success', 'memberdeck_auto_login', 4, 1);

function memberdeck_auto_login($user_id) {
	wp_set_auth_cookie( $user_id, true, is_ssl() );
}

use Aws\S3\S3Client;

function memberdeck_download_handler() {
	if (isset($_GET['md_download'])) {
		$download = $_GET['md_download'];
		if (isset($_GET['key'])) {
			global $current_user;
			get_currentuserinfo();
			$user_id = $current_user->ID;
			$user_registered = $current_user->user_registered;
			$key = $_GET['key'];
			$validate = validate_key($download, $key, $user_id, $user_registered);
			if ($validate) {
				$new_dl = new ID_Member_Download($download);
				$dl = $new_dl->get_download();
				$link = $dl->download_link;
				if ($dl->enable_s3 == 1) {
					
					$access_key = '';
					$secret_key = '';
					$bucket = '';
					$settings = get_option('md_s3_settings');
					if (!empty($settings)) {
						$settings = unserialize($settings);
						if (is_array($settings)) {
							$access_key = $settings['access_key'];
							$secret_key = $settings['secret_key'];
							$bucket = $settings['bucket'];
						}
					}

					$client = S3Client::factory(array(
					    'key'    => $access_key,
					    'secret' => $secret_key,
					));
					$link = $client->getObjectURL($bucket, $link, '2 minutes');
				}
				header('Location: '.$link);
				exit;
			}
		}
		else {
			header('Location: '.site_url());
			exit;
		}
	}
}

add_action('init', 'memberdeck_download_handler');

function validate_key($download, $key, $user_id, $user_registered) {
	$member = new ID_Member();
	$match = $member->match_user($user_id);
	if (!empty($match)) {
		if (md5($user_registered.$user_id) !== $key) {
			return false;
		}
		else {
			$access_levels = unserialize($match->access_level);
			$new_dl = new ID_Member_Download($download);
			$dl = $new_dl->get_download();
			foreach ($access_levels as $level) {
				if (in_array($level, unserialize($dl->download_levels))) {
					$pass = true;
				}
			}
			if ($pass) {
				return true;
			}
			else {
				return false;
			}
		}	
	}	
}

add_action('wp_login', 'memberdeck_exp_check_onlogin', 1, 2);

function memberdeck_exp_check_onlogin($user_login, $user) {
	$userdata = $user->data;
	foreach ($userdata as $k=>$v) {
		if ($k == 'ID') {
			$user_id = $v;
		}
	}
	$user_levels = ID_Member::user_levels($user_id);
	if (!empty($user_levels)) {
		$level_array = unserialize($user_levels->access_level);
	}
	if (isset($level_array)) {
		//print_r($level_array)."\n";
		$i = 0;
		foreach ($level_array as $level) {
			$order = new ID_Member_Order(null, $user_id, $level);
			//print_r($order);
			$latest = $order->get_last_order();
				//print_r($latest)."\n";
			// make sure there is an order and it isn't for a free level
			if (isset($latest) && $latest->transaction_id !== 'free') {
				// a non-expiring level has a null value for e_date
				if (isset($latest->e_date) && $latest->e_date !== '0000-00-00 00:00:00') {
					$e_date = $latest->e_date;
					$datestring = strtotime($e_date);
					$now = time();
					if ($now > $datestring) {
						unset($level_array[$i]);
						ID_Member_Order::cancel_subscription($latest->id);
					}
				}
			}
			$i++;
		}
		//print_r($level_array)."\n";
		//exit();
		ID_Member::expire_level($user_id, $level_array);
	}
}

function memberdeck_exp_checkondash($user_id) {
	$user_levels = ID_Member::user_levels($user_id);
	if (!empty($user_levels)) {
		$level_array = unserialize($user_levels->access_level);
	}
	else {
		$level_array = null;
	}
	if (is_array($level_array)) {
		//print_r($level_array)."\n";
		$i = 0;
		foreach ($level_array as $level) {
			$order = new ID_Member_Order(null, $user_id, $level);
			//print_r($order);
			$latest = $order->get_last_order();
				//print_r($latest)."\n";
			// make sure there is an order and it isn't for a free level
			if (isset($latest) && $latest->transaction_id !== 'free') {
				// a non-expiring level has a null value for e_date
				if (isset($latest->e_date) && $latest->e_date !== '0000-00-00 00:00:00') {
					$e_date = $latest->e_date;
					$datestring = strtotime($e_date);
					$now = time();
					if ($now > $datestring) {
						unset($level_array[$i]);
						ID_Member_Order::cancel_subscription($latest->id);
					}
				}
			}
			$i++;
		}
		//print_r($level_array)."\n";
		//exit();
		ID_Member::expire_level($user_id, $level_array);
	}
}

add_action('wp_login', 'memberdeck_license_gen_check', 1, 2);

function memberdeck_license_gen_check($user_login, $user) {
	$userdata = $user->data;
	foreach ($userdata as $k=>$v) {
		if ($k == 'ID') {
			$user_id = $v;
		}
	}

	$md_user = ID_Member::user_levels($user_id);
	if (!empty($md_user)) {
		$md_user_levels = unserialize($md_user->access_level);
	}

	if (!empty($md_user_levels)) {
		//echo 1;
		$downloads = ID_Member_Download::get_downloads();
		foreach ($md_user_levels as $level_id) {
			//echo 2;
			$level = ID_Member_Level::get_level($level_id);
			if (isset($level->license_count) && ($level->license_count > 0 || $level->license_count == -1)) {
				foreach ($downloads as $download) {
					//echo 3;
					$dl_id = $download->id;
					if (!empty($download->download_levels)) {
						//echo 4;
						$levels = unserialize($download->download_levels);
						if (is_array($levels) && in_array($level_id, $levels)) {
							if ($download->licensed == 1) {
								//echo 5;
								//echo $user_id;
								$key = MD_Keys::get_license($user_id, $dl_id);
								if (empty($key) || $key == '') {
									//echo 6;
									$keys = new MD_Keys();
									$license = $keys->generate_license($user_id);
									if (isset($license)) {
										//echo 7;
										$new_license = new MD_Keys($license, $level->license_count);
										$save_license = $new_license->store_license($user_id, $dl_id);
									}
								}
							}
						}
					}
				}
			}
		}
	}
	//exit;
}

add_action('init', 'md_validate_license');

function md_validate_license() {
	if (isset($_GET['action']) && $_GET['action'] == 'md_validate_license') {
		$response = array('valid' => 0, 'download_id' => null);
		if (isset($_GET['key'])) {
			$key = $_GET['key'];
			$keys = new MD_Keys($key);
			$response = $keys->validate_license();
		}
		print_r(json_encode($response));
		exit;
	}
}

add_action('memberdeck_payment_success', 'md_sendto_mailchimp', 100, 4);

function md_sendto_mailchimp($user_id, $order_id, $paykey, $fields) {
	//echo 'start of mc';
	require_once MD_PATH.'lib/mailchimp-api-master/MailChimp.class.php';
	$crm_settings = get_option('crm_settings');
	if (!empty($crm_settings)) {
		//echo 'inside crm';
		$mailchimp_key = $crm_settings['mailchimp_key'];
		$mailchimp_list = $crm_settings['mailchimp_list'];
		$enable_mailchimp = $crm_settings['enable_mailchimp'];
		if ($enable_mailchimp == 1) {
			//echo 'inside enable';
			$current_user = get_userdata($user_id);
			$email = $current_user->user_email;
			$usermeta = get_user_meta($user_id);
			$fname = $usermeta['first_name'];
			$lname = $usermeta['last_name'];
			$level_name = '';
			$order = new ID_Member_Order($order_id);
			$the_order = $order->get_order();
			if (!empty($the_order)) {
				//echo 'inside order';
				$level_id = $the_order->level_id;
				if ($level_id > 0) {
					//echo 'inside level id';
					$level = ID_Member_Level::get_level($level_id);
					if (!empty($level)) {
						//echo 'inside level';
						$level_name = $level->level_name;
					}
				}
			}
			$the_order = $order->get_order();
			$mailchimp = new MailChimp($mailchimp_key);
			//echo 'after instantiation';
			$name = urlencode('MD Level Name');
			$add_merge = $mailchimp->call('lists/merge-var-add', array(
					'id' => $mailchimp_list,
					'tag' => 'LEVEL',
					'name' => $name,
					'options' => array(
						'field_type' => 'text',
						'req' => false,
						'public' => false,
						'show' => true
						)
					));
			//echo 'after call 1';
			$merge_vars = array(
                    'FNAME' => $fname,
                  	'LNAME' => $lname,
                  	'LEVEL' => $level_name
                  );

			$result = $mailchimp->call('lists/subscribe', array(
					'id' => $mailchimp_list,
					'email' => array('email' => $email),
					'merge_vars' => $merge_vars,
					'double_optin' => true,
					'update_existing' => true,
					'replace_interests' => false,
					'send_welcome' => false
				));
		}
	}
	//echo 'after mc';
}

/**
* MemberDeck Ajax
*/

function md_level_data() {
	if (isset($_POST['level_id'])) {
		$level_id = absint($_POST['level_id']);
		if ($level_id > 0) {
			$level = ID_Member_Level::get_level($level_id);
			print_r(json_encode($level));
		}
	}
	exit;
}

add_action('wp_ajax_md_level_data', 'md_level_data');
add_action('wp_ajax_nopriv_md_level_data', 'md_level_data');

function idmember_get_profile() {
	if ($_POST['ID'] > 0) {
		$user_id = absint($_POST['ID']);
		$userdata = get_userdata($user_id);
		if (!empty($userdata)) {
			$usermeta = get_user_meta($user_id);
			$shipping_info = get_user_meta($user_id, 'md_shipping_info', true);
			print_r(json_encode(array('shipping_info' => $shipping_info, 'userdata' => $userdata, 'usermeta' => $usermeta)));
		}
	}
	exit;
}

add_action('wp_ajax_idmember_get_profile', 'idmember_get_profile');
add_action('wp_ajax_nopriv_idmember_get_profile', 'idmember_get_profile');

function idmember_get_levels() {
	global $wpdb;
	$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_levels';
	$res = $wpdb->get_results($sql);
	$level = array();
	foreach ($res as $object) {
		$level[$object->id] = $object;
	}
	print_r(json_encode($level));
	exit;
}

add_action('wp_ajax_idmember_get_levels', 'idmember_get_levels');
add_action('wp_ajax_nopriv_idmember_get_levels', 'idmember_get_levels');

function idmember_get_credits() {
	$credits = ID_Member_Credit::get_all_credits();
	$credit = array();
	foreach ($credits as $object) {
		$credit[$object->id] = $object;
	}
	print_r(json_encode($credit));
	exit;
}

add_action('wp_ajax_idmember_get_credits', 'idmember_get_credits');
add_action('wp_ajax_nopriv_idmember_get_credits', 'idmember_get_credits');

function idmember_get_downloads() {
	global $wpdb;
	$sql = 'SELECT * FROM '.$wpdb->prefix.'memberdeck_downloads';
	$res = $wpdb->get_results($sql);
	$downloads = array();
	foreach ($res as $object) {
		$levels = unserialize($object->download_levels);
		$object->levels = $levels;
		$downloads[$object->id] = $object;
	}
	print_r(json_encode($downloads));
	exit;
}

add_action('wp_ajax_idmember_get_downloads', 'idmember_get_downloads');
add_action('wp_ajax_nopriv_idmember_get_downloads', 'idmember_get_downloads');

function idmember_get_level() {
	if (isset($_POST['action']) && isset($_POST['Level'])) {
		$id = $_POST['Level'];
		$level = ID_Member_Level::get_level($id);
		print_r(json_encode($level));
	}
	else {
		echo 0;
	}
	exit();
}

add_action('wp_ajax_idmember_get_level', 'idmember_get_level');
add_action('wp_ajax_nopriv_idmember_get_level', 'idmember_get_level');

function idmember_edit_user() {
	if (isset($_POST['action']) && $_POST['action'] == 'idmember_edit_user') {
		$id = $_POST['ID'];
		$user = new ID_Member();
		$levels = $user->user_levels($id);
		if (isset($levels)) {
			$levels = unserialize($levels->access_level);
			$lasts = array();
			if (is_array($levels)) {
				$i = 0;
				foreach ($levels as $level) {
					$order = new ID_Member_Order(null, $id, $level);
					$last = $order->get_last_order();
					if (!empty($last)) {
						$lasts[$i]['e_date'] = $last->e_date;
						$lasts[$i]['order_date'] = $last->order_date;
						$lasts[$i]['id'] = $last->id;
					}
					$i++;
				}
			}
			if ($levels == null) {
				$levels = 0;
			}
		}
		else {
			$levels = 0;
			$lasts = array();
			//echo 0;
		}
		print_r(json_encode(array('levels' => $levels, 'lasts' => $lasts)));
	}
	else {
		echo 0;
	}
	exit();
}

add_action('wp_ajax_idmember_edit_user', 'idmember_edit_user');
add_action('wp_ajax_nopriv_idmember_edit_user', 'idmember_edit_user');

function idmember_edit_profile() {
	if (isset($_POST['Userdata'])) {
		$userdata_array = $_POST['Userdata'];
		// need to get user ID
		$user_id = $userdata_array['id'];
		$user = array('ID' => $user_id, 
				'user_email' => $userdata_array['user_email'], 
				'first_name' => (isset($userdata_array['first_name']) ? $userdata_array['first_name'] : ''), 
				'last_name' => (isset($userdata_array['last_name']) ? $userdata_array['last_name'] : ''),
				'display_name' => $userdata_array['display_name'],
				'description' => (isset($userdata_array['description']) ? $userdata_array['description'] : ''),
				'user_url' => (isset($userdata_array['user_url']) ? $userdata_array['user_url'] : '')
				);
		$update_user = wp_update_user($user);
		update_user_meta($user_id, 'twitter', $userdata_array['twitter']);
		update_user_meta($user_id, 'facebook', $userdata_array['facebook']);
		update_user_meta($user_id, 'google', $userdata_array['google']);
	}
	exit;
}

add_action('wp_ajax_idmember_edit_profile', 'idmember_edit_profile');
add_action('wp_ajax_nopriv_idmember_edit_profile', 'idmember_edit_profile');

function idmember_save_user() {
	if (isset($_POST['action']) && $_POST['action'] == 'idmember_save_user') {
		$id = $_POST['ID'];
		$levels = $_POST['Levels'];
		$date = date('Y-m-d h:i:s');
		if (isset($_POST['Dates'])) {
			$dates = $_POST['Dates'];
		}
		$level_array = array();
		$user = new ID_Member();
		$match = $user->match_user($id);
		if (!empty($match)) {
			$have_match = true;
			$current_levels = $match->access_level;
			if (isset($current_levels)) {
				$old_levels = unserialize($current_levels);
				if (is_array($old_levels)) {
					$have_levels = true;
				}
			}
		}
		else {
			// add empty user first so we can ensure credits post
			$user_vars = array('user_id' => $id,
				'level' => array(),
				'data' => array());
			$new_user = $user->add_user($user_vars);
		}
		foreach ($levels as $level) {
			if (isset($level['level']) && isset($level['value'])) {
				$level_array[] = $level['level'];
				$order = new ID_Member_Order(null, $id, $level['level']);
				$check_order = $order->get_last_order();

				if (empty($check_order)) {
					$add_order = $order->add_order();
				}
			
				// I don't think this is a possible outcome, need to examine
				else if ($check_order->status == 'active') {
					// order is still active so we should update
					if (isset($have_levels)) {
						// old levels existed
						if (!in_array($level['level'], $old_levels)) {
							// this level wasn't in the old levels, we need to reactivate
							$update = new ID_Member_Order($check_order->id, $id, $level, null, $check_order->transaction_id);
							$update_order = $update->update_order();
						}
					}
				}

				else {
					// order is cancelled add new
					$add_order = $order->add_order();
				}
			}
		}
		if (isset($have_match) && isset($have_levels)) {
			$dif = array_diff($old_levels, $level_array);
			if (!empty($dif)) {
				foreach ($dif as $dropped) {
					$order = new ID_Member_Order(null, $id, $dropped);
					$last = $order->get_last_order();
					$order = new ID_Member_Order($last->id);
					$order->cancel_status();
				}
			}
			$update = $user->save_user($id, $level_array);
		}
		else {
			$update = $user->save_user($id, $level_array);
		}
		
		if (isset($dates)) {
			foreach ($dates as $date) {
				$e_date = $date['date'];
				$oid = $date['id'];
				$update_dates = ID_Member_Order::update_order_date($oid, $e_date);
			}
		}
	}
	else {
		echo 0;
	}
	exit();
}

add_action('wp_ajax_idmember_save_user', 'idmember_save_user');
add_action('wp_ajax_nopriv_idmember_save_user', 'idmember_save_user');

function idmember_create_customer() {
	//print_r($_POST);
	if (isset($_POST['Token'])) {
		global $crowdfunding;
		$token = $_POST['Token'];
		$customer = $_POST['Customer'];
		$txn_type = $_POST['txnType'];
		$product_id = absint(esc_attr($customer['product_id']));
		$settings = get_option('memberdeck_gateways');
		$stripe_currency = 'USD';
		if (!empty($settings)) {
			$settings = unserialize($settings);
			if (is_array($settings)) {
				$test = $settings['test'];
				$sk = $settings['sk'];
				$tsk = $settings['tsk'];
				$es = $settings['es'];
				$esc = $settings['esc'];
				$eb = $settings['eb'];
				$stripe_currency = $settings['stripe_currency'];
			}
		}
		if (function_exists('is_id_pro') && is_id_pro()) {
			$settings = get_option('memberdeck_gateways');
			if (!empty($settings)) {
				$settings = unserialize($settings);
				if (is_array($settings)) {
					$esc = $settings['esc'];
					if ($esc == '1') {
						$check_claim = get_option('md_level_'.$product_id.'_owner');
						if (!empty($check_claim)) {
							$md_sc_creds = get_sc_params($check_claim);
							if (!empty($md_sc_creds)) {
								//echo 'using sc';
								$sc_accesstoken = $md_sc_creds->access_token;
							}
						}
					}
				}
			}
		}
		$source = $_POST['Source'];

		if (empty($source)) {
			if ($eb == 1) {
				$source = 'balanced';
				global $balanced_customer_id;
				$customer_id = $balanced_customer_id;
			}
			else {
				$source = 'stripe';
				global $customer_id;
			}
		}

		else {
			if ($source == 'stripe') {
				global $customer_id;
			}
			else if ($source == 'balanced') {
				global $balanced_customer_id;
				$customer_id = $balanced_customer_id;
			}
		}

		if ($source == 'stripe') {
			require_once 'lib/Stripe.php';
			if (!empty($sc_accesstoken)) {
				Stripe::setApiKey($sc_accesstoken);
			}
			else {
				if ($test == '1') {
					Stripe::setApiKey($tsk);
				}
				else {
					Stripe::setApiKey($sk);
				}
			}
		}
		else if ($source == 'balanced') {
			if ($test == '1') {
				$bk = $settings['btk'];
				$burl = $settings['bturl'];
			}
			else {
				$bk = $settings['bk'];
				$burl = $settings['burl'];
			}
			require("lib/Balanced/Httpful/Bootstrap.php");
			require("lib/Balanced/RESTful/Bootstrap.php");
			require("lib/Balanced/Bootstrap.php");

			Httpful\Bootstrap::init();
			RESTful\Bootstrap::init();
			Balanced\Bootstrap::init();

			Balanced\Settings::$api_key = $bk;
		}
		
		$access_levels = array($product_id);
		$level_data = ID_Member_Level::get_level($product_id);
		$recurring_type = $level_data->recurring_type;
		if ($level_data->level_type == 'recurring') {
			$plan = $level_data->plan;
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
			$recurring = true;
			$interval = $level_data->recurring_type;
		}
		else if ($level_data->level_type == 'lifetime') {
			$e_date = null;
			$recurring = false;
		}
		else {
			$exp = strtotime('+1 years');
			$e_date = date('Y-m-d h:i:s', $exp);
			$recurring = false;
		}
		$fname = esc_attr($customer['first_name']);
		$lname = esc_attr($customer['last_name']);
		if (isset($customer['email'])) {
			$email = esc_attr($customer['email']);
		}
		else {
			// they have used 1cc or some other mechanism and we don't have their email
			if (is_user_logged_in()) {
				global $current_user;
				get_currentuserinfo();
				$email = $current_user->user_email;
			}
		}
		if (isset($customer['pw'])) {
			$pw = esc_attr($customer['pw']);
		}
		$member = new ID_Member();
		$check_user = $member->check_user($email);
		if (!empty($check_user)) {
			//echo 'check user is set';
			// We have a match so we need to add this level to the array of access levels
			// I also need to re-use our Stripe customer somehow
			$user_id = $check_user->ID;
			$match_user = $member->match_user($user_id);
			if (!isset($match_user->data) && empty($customer_id)) {
				if ($source == 'stripe') {
					// this means we need to create a customer id with stripe
					//echo 'is new customer';
					try {
						$newcust = Stripe_Customer::create(array(
						'description' => $email,
						'card' => $token));
						//print_r($newcust);
						$custid = $newcust->id;
						$insert = true;
					}
					catch (Stripe_CardError $e) {
						// Card was declined
						$message = $e->json_body['error']['message'];
						print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => $message)));
						exit;
					}
				}
				else if ($source == 'balanced') {
					//echo 'source is balanced';
					$args = array('name' => $fname.' '.$lname,
						'email' => $email);
					$newcust = new \Balanced\Customer($args);
					$newcust->save();
					$custid = $newcust->id;
					$newcust->addCard($burl."/cards/".$token);
					$default = Balanced\Card::get($burl.'/cards/'.$token);
					$newcust->source_uri = $default->uri;
					$newcust->save();
					$card_id = $token;
					//echo $card_id;
					$insert = true;
				}
			}
			else {
				// this is the point at which we check for add card vs re-use
				if (!empty($customer_id)) {
					//echo 'cust id not empty';
					$custid = $customer_id;
					// there is a customer id saved, so we have the option to use it
					if (!empty($token) && $token == 'customer') {
						// they used 1cc
						//echo 'option 1';
					}
					else {
						// they entered new details, let's add this card to their account
						// need to make sure this card doesn't already exist
						//echo 'option 2';
						$use_token = true;
						$in_acct = false;
						if ($source == 'stripe') {
							//echo 'source is stripe';
							$token_obj = Stripe_Token::retrieve($token);
							$cards = Stripe_Customer::retrieve($custid)->cards->all();
							$list = $cards['data'];
							$last4 = $token_obj->card->last4;
							foreach ($list as $card) {
								if ($last4 == $card->last4) {
									// card exists, we don't need to create it
									$in_acct = true;
									$card_id = $card->id;
									break;
								}
							}
							if ($in_acct == false) {
								//echo 'no match';
								$cu = Stripe_Customer::retrieve($customer_id);
								try {
									$card_object = $cu->cards->create(array('card' => $token));
									$card_id = $card_object->id;
								}
								catch (Stripe_CardError $e) {
									// Card was declined
									$message = $e->json_body['error']['message'];
									print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => $message)));
									exit;
								}
							}
						}
						else if ($source == 'balanced') {
							//echo 'a';
							$card = Balanced\Card::get($burl.'/cards/'.$token);
							if (!empty($card)) {
								//echo 'b';
								if (empty($card->customer)) {
									//echo 'b1';
									$retr_customer = \Balanced\Customer::get('/v1/customers/'.$custid);
									try {
										$retr_customer->addCard($burl."/cards/".$card->id);
									}
									catch (error $e) {
										//print_r($e);
									}
								}
								else {
									$in_acct == true;
								}
								$card_id = $card->id;
								$newcust = Balanced\Card::get($burl.'/cards/'.$card_id);
							}
							else {
								//echo 'c';
								//echo 'no match';
								$retr_customer = \Balanced\Customer::get('/v1/customers/'.$custid);
								$retr_customer->addCard($burl."/cards/".$token);
								$card_id = $token;
								$newcust = $card;
							}
						}
					}
				}
				else {
					//echo 'new cust';
					$new_customer = true;
				}
				if (isset($new_customer)) {
					// we didn't find a custid so we have to make one
					if ($source == 'stripe') {
						try {
							$newcust = Stripe_Customer::create(array(
								'description' => $email,
								'card' => $token));
								$custid = $newcust->id;
								//print_r($newcust);
						}
						catch (Stripe_CardError $e) {
							// Card was declined
							$message = $e->json_body['error']['message'];
							print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => $message)));
							exit;
						}
					}
					else if ($source == 'balanced') {
						$args = array('name' => $fname.' '.$lname,
							'email' => $email);
						$newcust = new \Balanced\Customer($args);
						$newcust->save();
						$custid = $newcust->id;
						$newcust->addCard($burl."/cards/".$token);
					}

				}
			}	
		}
		else {
			if ($source == 'stripe') {
				// brand new user so we can insert with just this level
				// after we create a new Stripe customer
				try {
					$newcust = Stripe_Customer::create(array(
						'description' => $email,
						'card' => $token));
					//print_r($newcust);
					$custid = $newcust->id;
					$newuser = true;
				}
				catch (Stripe_CardError $e) {
					// Card was declined
					$message = $e->json_body['error']['message'];
					print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => $message)));
					exit;
				}
			}
			else if ($source == 'balanced') {
				$args = array('name' => $fname.' '.$lname,
					'email' => $email);
				$newcust = new \Balanced\Customer($args);
				$newcust->save();
				$custid = $newcust->id;
				$newcust->addCard($burl."/cards/".$token);
				$card_id = $token;
				$newuser = true;
			}
		}
		if (isset($custid)) {
			//echo 'custid is set';
			// now we need to charge the customer
			if (!isset($recurring) || $recurring == false) {
				//echo 'not recurring';
				if (empty($txn_type)) {
					if (!empty($level_data->txn_type)) {
						$txn_type = $level_data->txn_type;
					}
					else {
						$txn_type = 'capture';
					}
				}
				if ($txn_type == 'capture') {
					if (isset($use_token) && $use_token == true) {
						//echo 'use token';
						if ($source == 'stripe') {
							try {
								$price = str_replace(',', '', $level_data->level_price) * 100;
								if (!empty($sc_accesstoken)) {
									$fee = 0;
									$sc_settings = get_option('md_sc_settings');
									if (!empty($sc_settings)) {
										$sc_settings = unserialize($sc_settings);
										if (is_array($sc_settings)) {
											$app_fee = $sc_settings['app_fee'];
											$fee_type = $sc_settings['fee_type'];
											if ($fee_type == 'flat') {
												$fee = $app_fee; 
											}
											else {
												$fee = $price * $app_fee;
											}
											
										}
									}
									try {
										$newcharge = Stripe_Charge::create(array(
										'amount' => $price,
										'customer' => $custid,
										'card' => $card_id,
										'description' => $email,
										'currency' => $stripe_currency,
										'application_fee' => $fee));
									}
									catch (Stripe_CardError $e) {
										// Card was declined
										$message = $e->json_body['error']['message'];
										print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => $message)));
										exit;
									}
								}
								else {
									try {
										$newcharge = Stripe_Charge::create(array(
										'amount' => $price,
										'customer' => $custid,
										'card' => $card_id,
										'description' => $email,
										'currency' => $stripe_currency));
									}
									catch (Stripe_CardError $e) {
										// Card was declined
										$message = $e->json_body['error']['message'];
										print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => $message)));
										exit;
									}
								}	
							}
							catch (Stripe_InvalidRequestError $e) {
								$message = $e->json_body['error']['message'];
								print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => $message)));
								exit;
							}
						}
						else if ($source == 'balanced') {
							try {
								$newcharge = $newcust->debit(str_replace(',', '', $level_data->level_price)*100);
								$txn_id = $newcharge->transaction_number;
								update_user_meta($user_id, 'balanced_customer_id', $custid);
							}
							catch (Exception $e) {
								//print_r($e);
							}
						}
					}
					else {
						//echo 'do not use token';
						if ($source == 'stripe') {
							try {
								$price = str_replace(',', '', $level_data->level_price) * 100;
								if (!empty($sc_accesstoken)) {
									$fee = 0;
									$sc_settings = get_option('md_sc_settings');
									if (!empty($sc_settings)) {
										$sc_settings = unserialize($sc_settings);
										if (is_array($sc_settings)) {
											$app_fee = $sc_settings['app_fee'];
											$fee_type = $sc_settings['fee_type'];
											if ($fee_type == 'flat') {
												$fee = $app_fee; 
											}
											else {
												$fee = $price * $app_fee;
											}
											
										}
									}
									try {
										$newcharge = Stripe_Charge::create(array(
										'amount' => $price,
										'customer' => $custid,
										'description' => $email,
										'currency' => $stripe_currency,
										'application_fee' => $fee));
									}
									catch (Stripe_CardError $e) {
										// Card was declined
										$message = $e->json_body['error']['message'];
										print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => $message)));
										exit;
									}
								}
								else {
									try {
										//echo 'use customer';
										$newcharge = Stripe_Charge::create(array(
										'amount' => $price,
										'customer' => $custid,
										'description' => $email,
										'currency' => $stripe_currency));
									}
									catch (Stripe_CardError $e) {
										// Card was declined
										$message = $e->json_body['error']['message'];
										print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => $message)));
										exit;
									}
								}
							}
							catch (Stripe_InvalidRequestError $e) {
								$message = $e->json_body['error']['message'];
								print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => $message)));
								exit;
							}
						}
						else if ($source == 'balanced') {
							try {
								$customer = \Balanced\Customer::get("/v1/customers/".$custid);
								$newcharge = $customer->debit(str_replace(',', '', $level_data->level_price)*100);
								$txn_id = $newcharge->transaction_number;
								update_user_meta($user_id, 'balanced_customer_id', $custid);
							}
							catch (Exception $e) {
								//print_r($e);
							}
						}
					}

					if (isset($newcharge)) {
						$success = true;
						$type = 'order';
						$txn_id = $newcharge->id;
					}
				}
				else if ($txn_type == 'preauth') {
					// just store customer so we can process later
					$preauth = true;
					$txn_id = 'pre';
					$type = 'preauth';
				}
			}
			else {
				//echo 'recurring';
				// Balanced does not support recurring, so we use Stripe if active
				$c = Stripe_Customer::retrieve($custid);
				//echo $custid;
				//print_r($c);
				// varchange
				$sub = $c->updateSubscription(array('plan' => $plan));
				//print_r($sub);
				if ($sub->status == 'active') {
					$txn_id = $sub->plan->id;
					//echo $txn_id;
					$success = true;
				}
				$start = $sub->start;
				//echo $start;
				$new_order = '';
				$type = 'recurring';
				//print_r($sub);
			}
			if ((isset($success) && $success == true) || (isset($preauth) && $preauth == true)) {
				// this handles our custom post fields, if any
				if (isset($_POST['Fields'])) {
					$fields = $_POST['Fields'];
				}
				else {
					$fields = array();
				}
				//echo 'success';
				$paykey = md5($email.time());
				if (isset($newuser)) {
					//echo 'new user';
					// user doesn't exist at all, so we create and insert in both
					$user_id = wp_insert_user(array('user_email' => $email, 'user_login' => $email, 'user_pass' => $pw, 'first_name' => $fname, 'last_name' => $lname, 'display_name' => $fname));
					if ($source == 'balanced') {
						update_user_meta($user_id, 'balanced_customer_id', $custid);
						$user = array('user_id' => $user_id, 'level' => $access_levels, 'data' => array());
					}
					else {
						$user = array('user_id' => $user_id, 'level' => $access_levels, 'data' => array('customer_id' => $custid));
					}
					$new = ID_Member::add_user($user);
					if (!$recurring) {
						$order = new ID_Member_Order(null, $user_id, $product_id, null, $txn_id, '', 'active', $e_date);
						$new_order = $order->add_order();
						if (is_multisite()) {
							$blog_id = get_current_blog_id();
							//echo $blog_id;
							add_user_to_blog($blog_id, $user_id, 'subscriber');
						}
						MD_Keys::set_licenses($user_id, $product_id);
						if (isset($preauth) && $preauth == true) {
							if (isset($use_token) && $use_token == true) {
								$charge_token = $card_id;
							}
							else {
								$charge_token = $custid;
							}
							//echo 'sending a preorder';
							$preorder_entry = ID_Member_Order::add_preorder($new_order, $charge_token, $source);
							do_action('memberdeck_preauth_receipt', $user_id, $level_data->level_price, $product_id, $source);
							do_action('memberdeck_preauth_success', $user_id, $new_order, $paykey, $fields);
						}
						else {
							do_action('idmember_receipt', $user_id, $level_data->level_price, $product_id, $source);
							do_action('memberdeck_payment_success', $user_id, $new_order, $paykey, $fields);
						}
					}
				}
				else {
					//echo 'not new user';
					if (isset($match_user->access_level)) {
						//echo 'is set 1';
						$old_levels = unserialize($match_user->access_level);
						if (is_array($old_levels)) {
							foreach ($old_levels as $key['val']) {
								$access_levels[] = $key['val'];
							}
						}	
					}
					if (!empty($match_user->data)) {
						//echo 'is set 2';
						$old_data = unserialize($match_user->data);
						//print_r($old_data);
						if ($source == 'balanced') {
							update_user_meta($user_id, 'balanced_customer_id', $custid);
						}
						else if ($source == 'stripe') {
							$new_data = array('customer_id' => $custid);
							$old_data[] = $new_data;
							//$old_data[] = array('customer_id' => $custid, 'txn_id' => $txn_id);
						}
					}
					else {
						if ($source == 'balanced') {
							update_user_meta($user_id, 'balanced_customer_id', $custid);
							$old_data = array();
						}
						else if ($source == 'stripe') {
							$old_data = array('customer_id' => $custid);
						}
					}
					
					$user = array('user_id' => $user_id, 'level' => $access_levels, 'data' => $old_data);
					//print_r($user);
					if (isset($insert) && $insert == true) {
						//echo 'insert';
						// user exists only in wp_users so we insert
						$new = ID_Member::add_user($user);
					}
					else {
						//echo 'update';
						// user exists in both tables, so we update
						$new = ID_Member::update_user($user);
					}
					if (!isset($recurring) || $recurring == false) {
						$order = new ID_Member_Order(null, $user_id, $product_id, null, $txn_id, '', 'active', $e_date);
						$new_order = $order->add_order();
						if (is_multisite()) {
							$blog_id = get_current_blog_id();
							//echo $blog_id;
							add_user_to_blog($blog_id, $user_id, 'subscriber');
						}
						MD_Keys::set_licenses($user_id, $product_id);
						//echo 'order: '.$new_order;
						if (isset($preauth) && $preauth == true) {
							//echo 'sending a preorder';
							if (isset($use_token) && $use_token == true) {
								$charge_token = $card_id;
							}
							else {
								$charge_token = $custid;
							}
							$preorder_entry = ID_Member_Order::add_preorder($new_order, $charge_token, $source);
							do_action('memberdeck_preauth_receipt', $user_id, $level_data->level_price, $product_id, $source);
							do_action('memberdeck_preauth_success', $user_id, $new_order, $paykey, $fields);
						}
						else {
							//echo 'before order action';
							do_action('idmember_receipt', $user_id, $level_data->level_price, $product_id, $source);
							//echo 'after receipt';
							do_action('memberdeck_payment_success', $user_id, $new_order, $paykey, $fields);
							//echo 'after order action';
						}
					}
				}
				if ($crowdfunding) {
					//echo 'order: '.$new_order;
					if (isset($_POST['Fields'])) {
						//echo 'isset post fields';
						$fields = $_POST['Fields'];
						if (is_array($fields)) {
							foreach ($fields as $field) {
								if ($field['name'] == 'project_id') {
									$project_id = $field['value'];
								}
								else if ($field['name'] == 'project_level') {
									$proj_level = $field['value'];
								}
							}
						}
						if (isset($project_id) && isset($proj_level)) {
							'isset project id and level';
							$price = $level_data->level_price;
							if (isset($new_order)) {
								//echo $new_order;
								$order = new ID_Member_Order($new_order);
								//print_r($order);
								//$the_order = $order->get_order();
								$created_at = $order->order_date;
							}
							else {
								$created_at = date('Y-m-d h:i:s');
							}
							if (isset($preauth) && $preauth == true) {
								$status = 'W';
							}
							else {
								$status = 'C';
							}
							$pay_id = mdid_insert_payinfo($fname, $lname, $email, $project_id, $txn_id, $proj_level, $price, $status, $created_at);
							// now need to insert mdid order
							if (isset($pay_id)) {
								if ($type == 'recurring') {
									$mdid_id = mdid_insert_order($custid, $pay_id, $start, $txn_id);
								}
								else {
									$mdid_id = mdid_insert_order($custid, $pay_id, $new_order, null);
								}
								do_action('id_payment_success', $pay_id);
							}
						}
					}
				}
				if ($source == 'stripe') {
					//echo 'inside stripe success';
					do_action('memberdeck_stripe_success', $user_id);
				}
				else if ($source == 'balanced') {
					//echo 'inside balanced success';
					do_action('memberdeck_balanced_success', $user_id);
				}
				//echo 'before response';
				// go ahead and send the response so we can redirect them
				print_r(json_encode(array('response' => 'success', 'product' => $product_id, 'paykey' => $paykey, 'customer_id' => $custid, 'user_id' => $user_id, 'order_id' => $new_order, 'type' => $type)));
			}
			else {
				print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => __('Could not authorize transaction', 'memberdeck').'.')));
			}
		}
		else {
			print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => __('Could not create customer token', 'memberdeck').'.')));
		}
	}
	else {
		print_r(json_encode(array('response' => __('failure', 'memberdeck'), 'message' => __('Could not create customer token', 'memberdeck').'.')));
	}
	exit();
}

add_action('wp_ajax_idmember_create_customer', 'idmember_create_customer');
add_action('wp_ajax_nopriv_idmember_create_customer', 'idmember_create_customer');

function md_export_customers() {
	$url = ID_Member::export_members();
	echo $url;
	exit;
}

add_action('wp_ajax_md_export_customers', 'md_export_customers');
add_action('wp_ajax_nopriv_md_export_customers', 'md_export_customers');

function md_delete_export() {
	if (isset($_POST['file'])) {
		$file = $_POST['file'];
		ID_Member::delete_export($filepath);
	}
	exit;
}

add_action('wp_ajax_md_delete_export', 'md_delete_export');
add_action('wp_ajax_nopriv_md_delete_export', 'md_delete_export');

function md_use_credit() {
	global $crowdfunding;
	global $customer_id;
	global $md_credits;
	$customer = $_POST['Customer'];
	$product_id = absint(esc_attr($customer['product_id']));
	$access_levels = array($product_id);
	$level_data = ID_Member_Level::get_level($product_id);
	if ($level_data->level_type == 'recurring') {
		// we need to return false here
		$error = __('Cannot use credits to purchase recurring products', 'memberdeck');
		print_r(json_encode(array('response' => 'failure', 'message' => $error)));
		exit;
	}
	else if ($level_data->level_type == 'lifetime') {
		$e_date = null;
	}
	else {
		$exp = strtotime('+1 years');
		$e_date = date('Y-m-d h:i:s', $exp);
	}
	$fname = esc_attr($customer['first_name']);
	$lname = esc_attr($customer['last_name']);
	if (isset($customer['email'])) {
		$email = esc_attr($customer['email']);
	}
	else {
		// they have used 1cc or some other mechanism and we don't have their email
		if (is_user_logged_in()) {
			global $current_user;
			get_currentuserinfo();
			$email = $current_user->user_email;
		}
	}
	$check_user = ID_Member::check_user($email);
	if (!empty($check_user)) {
		if ($md_credits >= $level_data->credit_value) {
			$user_id = $check_user->ID;
			$match_user = ID_Member::match_user($user_id);
			if (!empty($match_user)) {
				// this user already exists within MemberDeck
				$txn_id = 'credit';
				if (isset($match_user->access_level)) {
					// let's combine levels
					$old_levels = unserialize($match_user->access_level);
					foreach ($old_levels as $key['val']) {
						$access_levels[] = $key['val'];
					}	
				}
				if (isset($match_user->data)) {
					// let's combine data
					$old_data = unserialize($match_user->data);
					// do we need any data for credit purchases?
					//$old_data[] =  array('customer_id' => $custid);
				}
				else {
					$old_data = array();
				}
				$paykey = md5($email.time());
				$order = new ID_Member_Order(null, $user_id, $product_id, null, $txn_id, '', 'active', $e_date);
				$new_order = $order->add_order();
				MD_Keys::set_licenses($user_id, $product_id);
				$user = array('user_id' => $user_id, 'level' => $access_levels, 'data' => $old_data);
				$new = ID_Member::update_user($user);
				ID_Member_Credit::use_credits($user_id, $level_data->credit_value);
				// don't send receipt for now
				//do_action('memberdeck_credit_receipt', $user_id, $level_data->credit_value);
				do_action('memberdeck_payment_success', $user_id, $new_order, $paykey, null);
				print_r(json_encode(array('response' => 'success', 'product' => $product_id, 'paykey' => $paykey, 'customer_id' => null, 'user_id' => $user_id, 'order_id' => $new_order, 'type' => 'credit')));
			}
			else {
				$error = __('You do not have enough credits to complete this transaction', 'memberdeck');
				print_r(json_encode(array('response' => 'failure', 'message' => $error)));
			}
		}
		else {
			$error = __('This user is not a memberdeck user', 'memberdeck');
			print_r(json_encode(array('response' => 'failure', 'message' => $error)));
		}
	}
	else {
		$error = __('User was not found', 'memberdeck');
		print_r(json_encode(array('response' => 'failure', 'message' => $error)));
	}
	exit;
}

add_action('wp_ajax_md_use_credit', 'md_use_credit');
add_action('wp_ajax_nopriv_md_use_credit', 'md_use_credit');

function idmember_free_product() {
	if (isset($_POST['action']) && $_POST['action'] == 'idmember_free_product') {
		$customer = $_POST['Customer'];
		$product_id = absint(esc_attr($customer['product_id']));
		$access_levels = array($product_id);
		$level_data = ID_Member_Level::get_level($product_id);
		$level = ID_Member_Level::get_level($product_id);
		/*$exp = strtotime('+1 years');
		$e_date = date('Y-m-d h:i:s', $exp);*/
		$fname = esc_attr($customer['first_name']);
		$lname = esc_attr($customer['last_name']);
		$email = esc_attr($customer['email']);
		if (isset($customer['pw'])) {
			$pw = esc_attr($customer['pw']);
		}
		$check_user = ID_Member::check_user($email);


			if (empty($check_user)) {
				//echo 'new user';
				// user doesn't exist at all, so we create and insert in both
				$user_id = wp_insert_user(array('user_email' => $email, 'user_login' => $email, 'user_pass' => $pw, 'first_name' => $fname, 'last_name' => $lname, 'display_name' => $fname));
				$user = array('user_id' => $user_id, 'level' => $access_levels, 'data' => array());
				$new = ID_Member::add_user($user);
				$order = new ID_Member_Order(null, $user_id, $product_id, null, 'free', '', 'active', null);
				$new_order = $order->add_order();
				if (is_multisite()) {
					$blog_id = get_current_blog_id();
					echo $blog_id;
					add_user_to_blog($blog_id, $user_id, 'subscriber');
				}
				MD_Keys::set_licenses($user_id, $product_id);
			}
			else {
				//echo 'not new user';
				$user_id = $check_user->ID;
				$match_user = ID_Member::match_user($user_id);
				if (isset($match_user->access_level)) {
					//echo 'is set 1';
					$old_levels = unserialize($match_user->access_level);
					foreach ($old_levels as $key['val']) {
						$access_levels[] = $key['val'];
					}	
				}
				$user = array('user_id' => $user_id, 'level' => $access_levels, 'data' => $match_user->data);
				//print_r($user);
				if (empty($match_user)) {
					//echo 'insert';
					// user exists only in wp_users so we insert
					$new = ID_Member::add_user($user);
				}
				else {
					//echo 'update';
					// user exists in both tables, so we update
					$new = ID_Member::update_user($user);
				}
				$order = new ID_Member_Order(null, $user_id, $product_id, null, 'free', '', 'active', null);
				$new_order = $order->add_order();
				if (is_multisite()) {
					$blog_id = get_current_blog_id();
					echo $blog_id;
					add_user_to_blog($blog_id, $user_id, 'subscriber');
				}
				MD_Keys::set_licenses($user_id, $product_id);
				//echo $new_order;
				do_action('memberdeck_free_success', $user_id, $new_order);
			}
		
			print_r(json_encode(array('response' => 'success', 'product' => $product_id)));
			exit;
	}
}
add_action('wp_ajax_idmember_free_product', 'idmember_free_product');
add_action('wp_ajax_nopriv_idmember_free_product', 'idmember_free_product');

function idmember_check_email() {
	if (isset($_POST['action']) && $_POST['action'] == 'idmember_check_email' && isset($_POST['Email'])) {
		$email = $_POST['Email'];
		$member = new ID_Member();
		$check_user = $member->check_user($email);
		if (isset($check_user)) {
			print_r(json_encode(array('response' => 'exists')));
		}
		else {
			print_r(json_encode(array('response' => 'available')));
		}
	}
	exit();
}

add_action('wp_ajax_idmember_check_email', 'idmember_check_email');
add_action('wp_ajax_nopriv_idmember_check_email', 'idmember_check_email');

function memberdeck_insert_user() {
	if (isset($_POST['action']) && $_POST['action'] == 'memberdeck_insert_user' && isset($_POST['User'])) {
		$user = $_POST['User'];
		$fname = esc_attr($user['first_name']);
		$lname = esc_attr($user['last_name']);
		$email = esc_attr($user['email']);
		$pw = esc_attr($user['pw']);

		$user = array(
			'user_pass' => $pw, 
			'user_email' => $email, 
			'user_login' => $email, 
			'first_name' => $fname, 
			'last_name' => $lname,
			'display_name' => $fname
			);
		$insert = wp_insert_user($user);
		if ($insert > 0) {
			print_r(json_encode(array('response' => 'success')));
		}
		else {
			print_r(json_encode(array('response' => 'failure')));
		}
	}
	exit();
}

add_action('wp_ajax_memberdeck_insert_user', 'memberdeck_insert_user');
add_action('wp_ajax_nopriv_memberdeck_insert_user', 'memberdeck_insert_user');

function idmember_update_user() {
	if (isset($_POST['action']) && $_POST['action'] == 'idmember_update_user' && isset($_POST['User'])) {
		$user = $_POST['User'];
		$reg_key = $user['regkey'];
		$user_object = ID_Member::retrieve_user_key($reg_key);
		if (!empty($user_object)) {
			$user_id = $user_object->user_id;
		}
		if (isset($user_id)) {
			$fname = esc_attr($user['first_name']);
			$lname = esc_attr($user['last_name']);
			$email = esc_attr($user['email']);
			$pw = esc_attr($user['pw']);

			$user = array('ID' => $user_id, 
				'user_pass' => wp_hash_password($pw), 
				'user_email' => $email, 
				'user_login' => $email, 
				'first_name' => $fname, 
				'last_name' => $lname,
				'display_name' => $fname
				);
			$update = wp_insert_user($user);
			if ($update == $user_id) {
				ID_Member::delete_reg_key($user_id);
				do_action('memberdeck_stripe_success', $user_id);
				print_r(json_encode(array('response' => 'success')));
			}
			else {
				//echo '2';
				print_r(json_encode(array('response' => 'failure')));
			}
		}
		else {
			//echo '3';
			print_r(json_encode(array('response' => 'failure')));
		}
	}
	exit();
}

add_action('wp_ajax_idmember_update_user', 'idmember_update_user');
add_action('wp_ajax_nopriv_idmember_update_user', 'idmember_update_user');

function md_get_levels() {
	$levels = ID_Member_Level::get_levels();
	if (!empty($levels)) {
		print_r(json_encode($levels));
	}
	exit;
}

add_action('wp_ajax_md_get_levels', 'md_get_levels');
add_action('wp_ajax_nopriv_md_get_levels', 'md_get_levels');

function md_process_preauth() {
	if (isset($_POST['action']) && $_POST['action'] == 'md_process_preauth') {
		global $wpdb;
		if (isset($_POST['Level'])) {
			$level_id = $_POST['Level'];
			/**
			*
			*/
			if (function_exists('is_id_pro') && is_id_pro()) {
				$settings = get_option('memberdeck_gateways');
				if (!empty($settings)) {
					$settings = unserialize($settings);
					if (is_array($settings)) {
						$esc = $settings['esc'];
						if ($esc == '1') {
							$check_claim = get_option('md_level_'.$level_id.'_owner');
							if (!empty($check_claim)) {
								$md_sc_creds = get_sc_params($check_claim);
								if (!empty($md_sc_creds)) {
									$sc_accesstoken = $md_sc_creds->access_token;
								}
							}
						}
					}
				}
			}
			require_once 'lib/Stripe.php';
			$settings = get_option('memberdeck_gateways');
			if (!empty($settings)) {
				$settings = unserialize($settings);
				if (is_array($settings)) {
					$test = $settings['test'];
					$sk = $settings['sk'];
					$tsk = $settings['tsk'];
				}
			}
			if ($test == '1') {
				if (!empty($sc_accesstoken)) {
					Stripe::setApiKey($sc_accesstoken);
				}
				else {
					Stripe::setApiKey($tsk);
				}
				$bk = $settings['btk'];
				$burl = $settings['bturl'];
			}
			else {
				if (!empty($sc_accesstoken)) {
					Stripe::setApiKey($sc_accesstoken);
				}
				else {
					Stripe::setApiKey($sk);
				}
				$bk = $settings['bk'];
				$burl = $settings['burl'];
			}

			require("lib/Balanced/Httpful/Bootstrap.php");
			require("lib/Balanced/RESTful/Bootstrap.php");
			require("lib/Balanced/Bootstrap.php");

			Httpful\Bootstrap::init();
			RESTful\Bootstrap::init();
			Balanced\Bootstrap::init();

			Balanced\Settings::$api_key = $bk;

			$preorders = ID_Member_Order::get_md_preorders($level_id);
			$success = array();
			$fail = array();
			$response = array();
			if (!empty($preorders)) {
				$level = ID_Member_Level::get_level($level_id);
				$price = $level->level_price;
			}
			foreach ($preorders as $capture) {
				// need to get customer id
				// need to update order from W to C and txn from pre to txn
				$user_id = $capture->user_id;
				$userdata = get_userdata($user_id);
				$email = $userdata->user_email;
				$pre_info = ID_Member_Order::get_preorder_by_orderid($capture->id);
				if (!empty($pre_info)) {
					$gateway = $pre_info->gateway;
					if (empty($gateway) || $gateway == 'stripe') {
						$customer_id = ID_Member::get_customer_id($user_id);
					}
					else {
						$balanced_customer_id = get_user_meta($user_id, 'balanced_customer_id', true);
						$customer_id = $balanced_customer_id;
					}
					if (!empty($customer_id)) {
						try {
							//$cu = Stripe_Customer::retrieve($customer_id);
							//$card = $cu->cards->retrieve($pre_info->charge_token);
							//$token = Stripe_Token::create(array('card' => $card));
							if ($pre_info->gateway == 'balanced') {
								$customer = \Balanced\Customer::get("/v1/customers/".$customer_id);
								if (!empty($pre_info->charge_token) && $pre_info->charge_token !== $customer_id) {
								    $card = $pre_info->charge_token;
								    $customer = Balanced\Card::get($burl."/cards/".$card);
								}
								try {
									$newcharge = $customer->debit(str_replace(',', '', $price)*100);
									$txn_id = $newcharge->transaction_number;
									if ($newcharge->hold->is_void == false && $newcharge->status == 'succeeded') {
										$paid = 1;
										$refunded = 0;
									}
								}
								catch (Exception $e) {
									//print_r($e);
								}
							}
							else {
								$priceincents = str_replace(',', '', $price) * 100;
								if (!empty($sc_accesstoken)) {
									$fee = 0;
									$sc_settings = get_option('md_sc_settings');
									if (!empty($sc_settings)) {
										$sc_settings = unserialize($sc_settings);
										if (is_array($sc_settings)) {
											$app_fee = $sc_settings['app_fee'];
											$fee_type = $sc_settings['fee_type'];
											if ($fee_type == 'flat') {
												$fee = $app_fee; 
											}
											else {
												$fee = $price * $app_fee;
											}
											
										}
									}
									$stripe_params = array(
									'amount' => $priceincents,
									'customer' => $customer_id,
									'description' => $email,
									'currency' => $stripe_currency,
									'application_fee' => $fee);
								}
								else {
									$stripe_params = array(
									"amount" => $priceincents,
								    'customer' => $customer_id,
								    'description' => $email,
								    "currency" => $stripe_currency);
								}
								if (!empty($pre_info->charge_token) && $pre_info->charge_token !== $customer_id) {
								    $stripe_params["card"] = $pre_info->charge_token;
								}
								try {
									$charge = Stripe_Charge::create($stripe_params);
								}
								catch (Stripe_CardError $e) {
									// Card was declined
									//$fail[] = "failure";
								}
								$paid = $charge->paid;
								$refunded = $charge->refunded;
								$txn_id = $charge->id;
								$created = $charge->created;
							}

							if ($paid == 1 && $refunded !== 1) {
								$payment_variables = array(
									"txn_id" => $txn_id,
									"status" => "C",
									"id" => $capture->id
									);
						  		// Payment succeeded and was not refunded
						  		$mdid_order = mdid_by_orderid($capture->id);
								if (!empty($mdid_order)) {
									$customer_id = $mdid_order->customer_id;
									if (isset($mdid_order->pay_id) && $mdid_order->pay_id !== '') {
										$pay_id = $mdid_order->pay_info_id;
									}
								}
						  		if (isset($pay_id)) {
									$payment_variables['pay_id'] = $pay_id;
									do_action('id_payment_success', $capture->id);
								}
						  		mdid_set_approval($payment_variables);
						  		$user = get_userdata($user_id);
						  		$email = $user->user_email;
						  		$paykey = md5($email.time());
								$response = array('code' => 'success');
								$success[] = $txn_id;
								do_action('idmember_receipt', $user_id, $price, $level_id, $gateway);
								do_action('memberdeck_payment_success', $user_id, $capture->id, $paykey, null);
							}
							else {
								//print_r($charge);
								$response = array('code' => 'failure');
								$fail[] = "failure";
							}
						}
						catch(Stripe_Error $e) {
							//echo $e;
							$fail[] = "failure";
						}
					}
				}
			}
		}
		$successes = count($success);
		$failures = count($fail);
		$response["counts"] = array("success" => $successes, "failures" => $failures);
		print_r(json_encode($response));
		/**
		*
		*/
	}
	exit();
}

add_action('wp_ajax_md_process_preauth', 'md_process_preauth');
add_action('wp_ajax_nopriv_md_process_preauth', 'md_process_preauth');

/**
* MDID Core Functions
*/

if ($crowdfunding) {
	add_action('init', 'mdid_replace_purchaseform');
}

function mdid_replace_purchaseform() {
	if (isset($_GET['mdid_checkout'])) {
		add_filter('the_content', 'mdid_set_form', 1);
	}
}

function mdid_set_form($content) {
	$member_level = absint($_GET['mdid_checkout']);
	if (isset($_GET['level'])) {
		$id_level = absint($_GET['level']);
		$owner = mdid_get_owner($member_level, $id_level);
		if (!empty($owner)) {
			// prevent WP from adding line breaks automatically
			remove_filter('the_content', 'wpautop');
			return do_shortcode('[memberdeck_checkout product="'.$owner.'"]');
		}
	}
	return $content;
}

if ($crowdfunding) {
	add_action('md_purchase_extrafields', 'mdid_project_fields', 1);
}

function mdid_project_fields() {
	if (isset($_GET['mdid_checkout'])) {
		$project_id = absint($_GET['mdid_checkout']);
	}
	else {
		$project_id = null;
	}
	if (isset($_GET['level'])) {
		$level = $_GET['level'];
	}
	else {
		$level = null;
	}
	$fields = '<input type="hidden" name="mdid_checkout" value="1" />';
	$fields .= '<input type="hidden" name="project_id" value="'.$project_id.'" />';
	$fields .= '<input type="hidden" name="project_level" value="'.$level.'"/>';
	echo $fields;
	return;
}

function is_level_available($project_id, $level) {
	$assignments = get_assignments_by_project($project_id);
	foreach ($assignments as $assignment) {
		$project_levels = get_project_levels($assignment->assignment_id);
		if (!empty($project_levels)) {
			$data = unserialize($project_levels->levels);
			if (in_array($level, $data)) {
				return false;
			}
		}
	}
	return true;
}

function mdid_get_owner($project_id, $level) {
	$assignments = get_assignments_by_project($project_id);
	foreach ($assignments as $assignment) {
		$project_levels = get_project_levels($assignment->assignment_id);
		if (!empty($project_levels)) {
			$data = unserialize($project_levels->levels);
			if (in_array($level, $data)) {
				return $assignment->level_id;
			}
		}
	}
	return;
}

function mdid_get_child($level) {
	$assignments = get_assignments_by_level($level);
	foreach($assignments as $assignment) {

	}
}

function get_assignments_by_level($level) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_assignments WHERE level_id = %s', $level);
	$res = $wpdb->get_results($sql);
	return $res;
}

function get_assignments_by_project($project_id) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_assignments WHERE project_id = %s', $project_id);
	$res = $wpdb->get_results($sql);
	return $res;
}

function get_project_levels($assignment_id) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_project_levels WHERE id = %d', $assignment_id);
	$res = $wpdb->get_row($sql);
	return $res;
}

function mdid_get_selected() {
	global $wpdb;
	$sql = 'SELECT * FROM '.$wpdb->prefix.'mdid_assignments';
	$res = $wpdb->get_results($sql);
	$active_projects = array();
	foreach ($res as $assignment) {
		$active_projects[] = $assignment->project_id;
	}
	return $active_projects;
}

function mdid_insert_payinfo($fname = null, $lname = null, $email = null, $project_id, $transaction_id, $proj_level, $price, $status = 'P', $created_at = null) {
	//echo $fname.$lname.$email.$project_id.$transaction_id.$proj_level.$price.$status.$created_at;
	if (empty($created_at)) {
		$created_at = date('Y-m-d h:i:s');
	}
	global $wpdb;
	$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'ign_pay_info (first_name,
					last_name,
					email, 
					product_id, 
					transaction_id, 
					product_level, 
					prod_price,
					status,
					created_at
					) VALUES (
					%s,
					%s,
					%s,
					%d,
					%s,
					%d,
					%s,
					%s,
					%s
					)', $fname, $lname, $email, $project_id, $transaction_id, $proj_level, $price, $status, $created_at);
	$res = $wpdb->query($sql);
	$pay_id = $wpdb->insert_id;
	if (isset($pay_id)) {
		return $pay_id;
	}
}

function mdid_insert_order($custid, $pay_info_id, $order_id = '', $sub_id = '') {
	global $wpdb;
	if (isset($sub_id)) {
		// subscription genius
		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'mdid_orders (customer_id, order_id, pay_info_id, subscription_id) VALUES (%s, %s, %d, %s)', $custid, $order_id, $pay_info_id, $sub_id);
	}
	else {
		// this is a normal order
		$sql = $wpdb->prepare('INSERT INTO '.$wpdb->prefix.'mdid_orders (customer_id, order_id, pay_info_id, subscription_id) VALUES (%s, %s, %d, %s)', $custid, $order_id, $pay_info_id, $sub_id);
	}
	$res = $wpdb->query($sql);
	$mdid_id = $wpdb->insert_id;
	if (isset($mdid_id)) {
		return $mdid_id;
	}
}

function mdid_transaction_to_order($id, $transaction_id) {
	global $wpdb;
	$order = new ID_Member_Order(null, null, null, null, $transaction_id);
	$transaction = $order->get_transaction();
	if (isset($transaction)) {
		$order_id = $transaction->id;
		if (isset($order_id)) {
			$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'mdid_orders SET order_id = %s WHERE id = %d', $order_id, $id);
			$res = $wpdb->query($sql);
		}
	}	
}

function mdid_plan_match($id, $sub_id) {
	global $wpdb;
	$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'mdid_orders SET subscription_id = %s WHERE id = %d', $sub_id, $id);
	$res = $wpdb->query($sql);
}

function mdid_orders_bycustid($custid) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_orders WHERE customer_id = %s', $custid);
	$res = $wpdb->get_results($sql);
	return $res;
}

function mdid_order_by_sub($sub_id) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_orders WHERE subscription_id = %s', $sub_id);
	$res = $wpdb->get_row($sql);
	return $res;
}

function mdid_order_by_customer_plan($customer_id, $plan) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_orders WHERE customer_id = %s AND subscription_id = %s', $customer_id, $plan);
	$res = $wpdb->get_row($sql);
	return $res;
}

function mdid_transaction_check($txn_id) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'ign_pay_info WHERE transaction_id = %s', $txn_id);
	$res = $wpdb->get_row($sql);
	return $res;
}

function mdid_start_check($start) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_orders WHERE order_id = %s', $start);
	$res = $wpdb->get_row($sql);
	return $res;
}

function mdid_payid_check($pay_id) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_orders WHERE pay_info_id = %d', $pay_id);
	$res = $wpdb->get_row($sql);
	return $res;
}

function mdid_payinfo_transaction($pay_id, $txn_id) {
	global $wpdb;
	$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'ign_pay_info SET transaction_id = %s WHERE id = %d', $txn_id, $pay_id);
	$res = $wpdb->query($sql);
}

function mdid_by_orderid($order_id) {
	global $wpdb;
	$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_orders WHERE order_id = %d', $order_id);
	$res = $wpdb->get_row($sql);
	return $res;
}

function mdid_set_collected($pay_id, $txn_id) {
	global $wpdb;
	$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'ign_pay_info SET transaction_id = %s, status = "C" WHERE id = %d', $txn_id, $pay_id);
	$res = $wpdb->query($sql);
}

function mdid_set_approval($args) {
	global $wpdb;
	if (!empty($args)) {
		if (isset($args['txn_id'])) {
			$txn_id = $args['txn_id'];
		}
		if (isset($args['id'])) {
			$order_id = $args['id'];
		}
		if (isset($args['pay_id'])) {
			$pay_id = $args['pay_id'];
		}
		if (isset($txn_id) && isset($order_id)) {
			$status = 'C';
			// things we need to do:
			// 1: Set MD order txn_id from pre to actual
			// 2: Set ID order txn_id from pre to actual
			// 3: Set ID order status to C
			$update_md_txn = ID_Member_Order::update_txn_id($order_id, $txn_id);
			if (isset($pay_id)) {
				mdid_set_collected($pay_id, $txn_id);
			}

		}
	}
}

/**
* MDID Bridge Ajax
*/

// Ajax listeners below

function mdid_project_list() {
	$project_set = mdid_get_selected();
	$active_projects = array();
	foreach ($project_set as $project_id) {
		$project = new ID_Project($project_id);
		$post_id = $project->get_project_postid();
		$active = get_post_meta($post_id, 'mdid_project_activate', true);
		if (!empty($active) && $active) {
			$the_project = $project->the_project();
			$active_projects[] = $the_project;
		}
	}
	print_r(json_encode($active_projects));
	exit;
}

if ($crowdfunding) {
	add_action('wp_ajax_mdid_project_list', 'mdid_project_list');
	add_action('wp_ajax_nopriv_mdid_project_list', 'mdid_project_list');
}

function mdid_get_assignments() {
	if (isset($_POST['Level'])) {
		$level = $_POST['Level'];
		if (!empty($level)) {
			$assignment_array = array();
			global $wpdb;
			$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_assignments WHERE level_id = %s', $level);
			$res = $wpdb->get_results($sql);
			foreach ($res as $assignment) {
				$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_project_levels WHERE id = %d', $assignment->assignment_id);
				$res = $wpdb->get_row($sql);
				if (!empty($res)) {
					$data = unserialize($res->levels);
					if (is_array($data)) {
						$project = array('project' => $assignment->project_id, 'levels' => $data);
						$assignment_array[] = $project;
					}
				}
			}
			print_r(json_encode($assignment_array));
		}
	}
	exit;
}

if ($crowdfunding) {
	add_action('wp_ajax_mdid_get_assignments', 'mdid_get_assignments');
	add_action('wp_ajax_nopriv_mdid_get_assignments', 'mdid_get_assignments');
}

function mdid_save_assignments() {
	if (isset($_POST['Assignments'])) {
		$assignments = $_POST['Assignments'];
		if (!empty($assignments)) {
			global $wpdb;
			$level = $assignments['level'];
			if (isset($assignments['projects'])) {
				$sql = 'SELECT * FROM '.$wpdb->prefix.'mdid_assignments WHERE level_id = "'.$level.'"';
				$res = $wpdb->get_results($sql);
				$old_array = array();
				foreach ($res as $row) {
					$old_array[] = $row->project_id;
				}
				$projects = $assignments['projects'];
				$new_array = array();
				foreach ($projects as $project) {
					$project_id = $project['id'];
					$new_array[] = $project_id;
					$levels = $project['levels'];
					$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_assignments WHERE level_id = %s AND project_id = %s', $level, $project_id);
					$check = $wpdb->get_row($sql);
					if (empty($check)) {
						$sql = 'INSERT INTO '.$wpdb->prefix.'mdid_project_levels (levels) VALUES ("'.mysql_real_escape_string(serialize($levels)).'")';
						$res = $wpdb->query($sql);
						$assignment_id = $wpdb->insert_id;
						$sql = 'INSERT INTO '.$wpdb->prefix.'mdid_assignments (level_id, project_id, assignment_id) VALUES ("'.$level.'", "'.$project_id.'", "'.$assignment_id.'")';
						$res = $wpdb->query($sql);
					}
					else {
						$sql = $wpdb->prepare('UPDATE '.$wpdb->prefix.'mdid_project_levels SET levels = %s WHERE id = %d', serialize($levels), $check->assignment_id);
						$update = $wpdb->query($sql);
					}
				}
				$array_diff = array_diff($old_array, $new_array);
				foreach ($array_diff as $diff) {
					if (!in_array($diff, $new_array)) {
						// wipe it
						$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_assignments WHERE project_id = %s', $diff);
						$check = $wpdb->get_row($sql);
						if (!empty($check)) {
							$sql = 'DELETE FROM '.$wpdb->prefix.'mdid_assignments WHERE id = '.$check->id;
							$res = $wpdb->query($sql);
							$sql = 'DELETE FROM '.$wpdb->prefix.'mdid_project_levels WHERE id = '.$check->assignment_id;
							$res = $wpdb->query($sql);
						}
					}
				}
			}
			else {
				// wipe it
				$sql = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'mdid_assignments WHERE level_id = %s', $level);
				$check = $wpdb->get_row($sql);
				if (!empty($check)) {
					$sql = 'DELETE FROM '.$wpdb->prefix.'mdid_assignments WHERE id = '.$check->id;
					$res = $wpdb->query($sql);
					$sql = 'DELETE FROM '.$wpdb->prefix.'mdid_project_levels WHERE id = '.$check->assignment_id;
					$res = $wpdb->query($sql);
				}
			}
			
		}
	}
	exit;
}

if ($crowdfunding) {
	add_action('wp_ajax_mdid_save_assignments', 'mdid_save_assignments');
	add_action('wp_ajax_nopriv_mdid_save_assignments', 'mdid_save_assignments');
}

//add_action('activated_plugin','save_error');
function save_error(){
    update_option('plugin_error',  ob_get_contents());
}

//add_action('init', 'test');

function test() {
	echo get_option('plugin_error');
}
?>