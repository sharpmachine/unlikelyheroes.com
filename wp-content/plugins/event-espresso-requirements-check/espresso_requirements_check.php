<?php
/*
Plugin Name: Event Espresso Requirements Check
Plugin URI: http://eventespresso.com/
Description: Checks the WordPress hosting environment to verify system requirements for <a href='http://eventespresso.com/ target='_blank'>Event Espresso</a>, the premium event management plugin for WordPress
Version: 0.9
Author: Event Espresso
Author URI: http://www.eventespresso.com
*/
global $wp_version;
define("ESPRESSO_CURRENT_PAGE", basename($_SERVER['PHP_SELF']));

    // set up some boolean defaults
    $has_recommended_php = false;
    $has_recommended_mysql = false;
    $has_recommended_wordpress = false;
    $has_recommended_php_mem = false;
    $has_mod_security = false;
    $is_iis = false;
    $is_godaddy = false;
    $is_wpengine = false;
    $is_mediatemple = false;
    $is_php_mem_ok = false;
    $is_warn = false;
    $is_fail = false;
    $is_pass = false;
    $is_apache = false;
    $mod_rewrite = false;
    $has_max_upload = false;
    $is_themeforest = false;
    $is_php_strict = false;
    $is_wp_debug = false;
    $apache_mods = false;
    $wp_deregister = false;

    // these are the hard EE requirements -- if any of these fail, you'll fail the requirements check
    $is_php_valid = version_compare(phpversion(), '5.2.4', '>'); // checks the php version
    $is_mysql_valid = version_compare(mysql_get_server_info(), '5.0.0', '>'); // checks the mysql version
    $is_wp_valid = version_compare( $wp_version , '3.5.0', '>='); // checks the wordpress version

    // server environment stuff
    $webserver = $_SERVER['SERVER_SOFTWARE']; // gets server info
    $has_recommended_php = version_compare(phpversion(), '5.3.0', '>'); // php 5.3 is recommended
    $has_recommended_mysql = version_compare(mysql_get_server_info(), '5.5.0', '>'); // mysql 5.5 is recommended
    $has_recommended_wordpress = version_compare( $wp_version, '3.5.1', '>='); // the current version of wordpress is recommended
    $has_max_upload = version_compare(ini_get('upload_max_filesize'), '5M', '>'); // checks upload_max_filesize php.ini setting
    $is_curl_valid = in_array('curl', get_loaded_extensions()); // checks if curl is installed
    $domain = str_replace('http://', '', home_url()); // gets the domain minus the http
    if ( function_exists('dns_get_record') ) {
        $dns = json_encode(dns_get_record($domain)); // gets some dns info
    } else {
        $dns = null;
    }
    $php_mem = get_cfg_var('memory_limit'); // checks the memory limit
    $max_upload = ini_get('upload_max_filesize'); // max upload size

    // error reporting stuff
    // helper function for determining error levels
    // @link http://www.php.net/manual/en/errorfunc.constants.php
    $errLvl = error_reporting();
    for ($i = 0; $i < 15;  $i++ ) {
        $error_level = FriendlyErrorType($errLvl & pow(2, $i)) . "<br>\n";
    }
    function FriendlyErrorType($type)
    {
        switch($type)
        {
            case E_ERROR: // 1 //
                return 'E_ERROR';
            case E_WARNING: // 2 //
                return 'E_WARNING';
            case E_PARSE: // 4 //
                return 'E_PARSE';
            case E_NOTICE: // 8 //
                return 'E_NOTICE';
            case E_CORE_ERROR: // 16 //
                return 'E_CORE_ERROR';
            case E_CORE_WARNING: // 32 //
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR: // 64 //
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING: // 128 //
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR: // 256 //
                return 'E_USER_ERROR';
            case E_USER_WARNING: // 512 //
                return 'E_USER_WARNING';
            case E_USER_NOTICE: // 1024 //
                return 'E_USER_NOTICE';
            case E_STRICT: // 2048 //
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR: // 4096 //
                return 'E_RECOVERABLE_ERROR';
            case 'E_DEPRECATED': // 8192 //
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED: // 16384 //
                return 'E_USER_DEPRECATED';
        }
        return "";
    }

    if ( strpos( $error_level, 'E_STRICT' ) ) { $is_php_strict = true; }
    if ( WP_DEBUG == true ) { $is_wp_debug = true; }

    // get theme info
    $theme_style = file_get_contents(get_stylesheet_uri()); // ThemeURI always returns null, so checking the entire style.css for themeforest instead
    if ( $is_wp_valid ) { // fixes fatal error if wp_get_theme doesn't exist, this was added in 3.4 so if they've already failed their WordPress version check, this will fail.
        $theme = wp_get_theme();
        $theme_author_uri = $theme->AuthorURI;
        $theme_author = $theme->Author;
        if ( strpos($theme_author_uri,'themeforest') || strpos($theme_style,'themeforest') ) { $is_themeforest = true; } // is the theme a themeforest theme?
    } else {
        if ( strpos($theme_style,'themeforest') ) { $is_themeforest = true; } // if we can't run wp_get_theme, still check if it's a themeforest theme
        if ( $is_themeforest ) {
            // if this is a themeforest theme but we can't get the details from wp_get_theme, just make some stuff up
            $theme = __( 'Unknown Themeforest Theme', 'espresso-requirements' );
            $theme_author_uri = 'http://themeforest.net';
            $theme_author = __( 'Unknown Theme Author', 'espresso-requirements' );
        }
    }
    if ( file_exists( get_template_directory() . '/functions.php' ) ) {
        $functions = file_get_contents(get_template_directory() . '/functions.php');
        $jquery_dereg = array("wp_deregister_script( 'jquery' )", "wp_deregister_script('jquery')");
        $wp_deregister = strpos( $functions, $jquery_dereg[0] );
        if ( !$wp_deregister ) {
            $wp_deregister = strpos( $functions, $jquery_dereg[1] );
        }
    }

    // start running the actual checks
    if (function_exists('apache_get_modules'))
        $has_mod_security = in_array('mod_security', apache_get_modules()) || in_array('mod_security2', apache_get_modules()); // checks for mod_security and mod_security2
    $is_php_mem_ok = version_compare( $php_mem, '32M', '>=');
    $has_recommended_php_mem = version_compare( $php_mem, '64M', '>=');
    if ( strpos($dns, 'mediatemple') || strpos($dns, 'MEDIATEMPLE') ) { $is_mediatemple = true; } // if the nameserver contains mediatemple, this is a mediatemple site
    if ( strpos($dns, 'domaincontrol') || strpos($dns, 'DOMAINCONTROL') ) { $is_godaddy = true; } // if the nameserver contains domaincontrol, this is a godaddy site
    if ( strpos($dns,'wpengine') ) { $is_wpengine = true; $is_godaddy = false; } // if there's a record to point to wpengine, we're actually using their servers, not GoDaddy's, so GoDaddy is always false
    if ( strpos($webserver, 'pache') ) { $is_apache = true; } // checks if the server is apache
    if ( strpos($webserver, 'IS') ) { $is_iis = true; } // checks the _SERVER variable for IIS
    if (function_exists('apache_get_modules')) {
        $apache_mods = true;
        $mod_rewrite = in_array('mod_rewrite', apache_get_modules());
    } else {
        if ( $_SERVER['HTTP_MOD_REWRITE'] )
                $mod_rewrite = true;
    }

    // pass or fail
    if ( !$is_curl_valid || $is_iis || $is_godaddy || !$is_php_mem_ok || !$has_recommended_wordpress || !$has_mod_security || !$has_max_upload ) { $is_warn = true; } // any warnings?
    if ( $is_php_valid && $is_mysql_valid && $is_wp_valid ) { $is_pass = true; } else { $is_fail = true; } // did we pass?

// handles the alert that displays under the plugin
if(ESPRESSO_CURRENT_PAGE == "plugins.php"){
    add_action('after_plugin_row_espresso-requirements-check/espresso_requirements_check.php', 'espresso_requirements_message');
}
add_action( 'admin_menu', 'espresso_requirements_add_page' );

function espresso_requirements_get_version() {
    $plugin_data = get_plugin_data( __FILE__ );
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}

function espresso_requirements_add_page() {
    $page = add_submenu_page('tools.php','Event Espresso Requirements', 'Event Espresso Requirements', 'administrator', 'espresso_requirements_page', 'espresso_requirements_page' );
    add_action( 'admin_print_scripts-plugins.php', 'espresso_requirements_scripts' );
    add_action( 'admin_print_scripts-' . $page, 'espresso_requirements_scripts' );
}

function espresso_requirements_scripts() {
    wp_enqueue_style( 'thickbox' );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'thickbox', null, array( 'jquery' ) );
    wp_register_style( 'espresso-requirements-css', plugin_dir_url(__FILE__) . 'assets/espresso-requirements-css.css', false, '0.5' );
    wp_enqueue_style( 'espresso-requirements-css' );
}

function espresso_requirements_page() {
    global $wpdb, $has_recommended_wordpress, $has_recommended_php, $has_recommended_mysql, $is_php_valid, $is_mysql_valid, $is_wp_valid, $webserver, $is_iis, $is_curl_valid, $php_mem, $is_php_mem_ok, $has_recommended_php_mem, $is_godaddy, $is_warn, $is_fail, $is_pass, $has_mod_security, $is_apache, $mod_rewrite, $max_upload, $has_max_upload, $theme_author_uri, $theme_author, $theme, $is_themeforest, $is_php_strict, $is_mediatemple, $is_wpengine, $is_wp_debug, $apache_mods, $wp_version, $wp_deregister;
    ?>
    <div class="wrap">
        <div id="icon-espresso-requirements" class="icon32">
            <br />
        </div>
        <h2><?php _e( 'Event Espresso Requirements', 'espresso-requirements' ); ?></h2>
        <?php if ( $is_pass && !$is_warn ) { ?>
            <div class="alert span12 pass"><strong><?php _e( 'Success!', 'espresso-requirements' ); ?></strong><br /><?php _e( 'Congratulations! Your server supports Event Espresso! While this plugin does not <em>guarantee</em> that Event Espresso will work without any issues on every server, it attempts to identify the <em>most common</em> server configuration issues. The information on this page is for your own benefit.', 'espresso-requirements' ); ?> <a target='_blank' href='http://eventespresso.com/download/'><?php _e( 'Get your copy of Event Espresso here.', 'espresso-requirements' ); ?></a></div>
        <?php } elseif ( $is_pass && $is_warn ) { ?>
            <div class="alert span12 info"><?php echo sprintf(__( 'The purpose of this plugin is to identify the <em>most common</em> server configuration issues and identify any potential areas of conflict. Not everything displaying a <strong>WARNING</strong> is necessarily a cause for alarm. Please read through each alert carefully and %1$scontact the Event Espresso support staff%2$s or your webhost if you have any questions.', 'espresso-requirements' ), '<a href="http://eventespresso.com/support/forums/" target="_blank">', '</a>'); ?></div>
            <div class="alert span12 warn"><strong><?php _e( 'Passed with warnings.', 'espresso-requirements' ); ?></strong><br /><?php _e( 'Your server supports the minimum requirements but may experience some difficulties with all features.  See below for more information.', 'espresso-requirements' ); ?> <a target='_blank' href='http://eventespresso.com/download/'><?php _e( 'Get your copy of Event Espresso here.', 'espresso-requirements' ); ?></a></div>
        <?php } else { ?>
            <div class="alert span12 info"><?php echo sprintf(__( 'The purpose of this plugin is to identify the <em>most common</em> server configuration issues and identify any potential areas of conflict. Not everything displaying a <strong>WARNING</strong> is necessarily a cause for alarm. Please read through each alert carefully and %1$scontact the Event Espresso support staff%2$s or your webhost if you have any questions.', 'espresso-requirements' ), '<a href="http://eventespresso.com/support/forums/" target="_blank">', '</a>'); ?></div>
            <div class="alert span12 fail"><strong><?php _e( 'Failed!', 'espresso-requirements' ); ?></strong></br /><?php _e( 'Your server does not meet the minimum requirements to run Event Espresso.  Please refer to the information below before attempting to install and run Event Espresso on your site.' ); ?> <?php echo sprintf( __( 'Once you have addressed the issues below, re-run this plugin and %1$sget your copy of Event Espresso here%2$s.', 'espresso-requirements' ), '<a href="http://eventespresso.com/download/">', '</a>' ); ?><br /><a class="thickbox" href="<?php echo plugins_url( 'espresso-requirements-check' ); ?>/assets/phpinfo.php?KeepThis=true&TB_iframe=true&height=600&width=800"><?php _e( 'View your server configuration details.', 'espresso-requirements' ); ?></a></div>
        <?php } ?>
        <?php if ( !$apache_mods ) { ?>
            <div class="alert span12 warn"><i class="icon-warning-sign"></i> <strong><?php _e( 'Espresso Requirements Check could not find <code>apache_get_modules</code>.', 'espresso-requirements' ); ?></strong><br /><?php _e( 'Apache function <code>apache_get_modules()</code> was not found.  This is most likely due to your PHP version or your PHP configuration.  This function is used by the requirements check plugin to identify what Apache modules are enabled and, if not found will result in warning messages having to do with your server configuration.  While this is not a problem, it does result in less-accurate results in the Espresso Requirements Check. Please contact your webhost if you are concerned and ask them if it is possible to run PHP as an Apache module rather than CGI.', 'espresso-requirements' ); ?><br />
                <a href="http://php.net/manual/en/function.apache-get-modules.php" target="_blank"><?php _e( 'Click here for more information about <code>apache_get_modules()</code>', 'espresso-requirements' ); ?></a>
            </div>
        <?php } ?>
        <h3><?php _e( 'PHP', 'espresso-requirements' ); ?></h3>
        <section>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'Your version', 'espresso-requirements' ); ?></div>
                <div class="span1"><?php echo phpversion(); ?></div>
                <?php if ( $is_php_valid ) { ?>
                    <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong></div>
                <?php } elseif ( $has_recommended_php ) { ?>
                    <div class="alert span9 warn"><i class="icon-warning-sign"></i> <strong><?php _e( 'WARNING', 'espresso-requirements' ); ?></strong><br /><?php _e( 'The recommended PHP version is 5.3 or higher.', 'espresso-requirements' ); ?></div>
                <?php } elseif ( !$is_php_valid ) { ?>
                    <div class="alert span9 fail"><i class="icon-remove"></i> <strong><?php _e( 'FAIL', 'espresso-requirements' ); ?></strong><br /><?php _e( 'The required version of PHP is 5.2.4.  We recommend 5.3 or higher.' ); ?></div>
                <?php } ?>
            </div>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'PHP memory', 'espresso-requirements' ); ?></div>
                <div class="span1"><?php echo $php_mem; ?></div>
                <?php if ( $is_php_mem_ok && $has_recommended_php_mem ) { ?>
                    <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong></div>
                </div>
                <?php } elseif ( $is_php_mem_ok && !$has_recommended_php_mem ) { ?>
                    <div class="alert span9 warn"><i class="icon-warning-sign"></i> <strong><?php _e( 'WARNING', 'espresso-requirements' ); ?></strong><br /><?php _e( 'Your PHP memory is less than 64M.  If you experience issues with white screens or Internal Server Errors, please contact your host about upgrading your PHP memory to 64M or higher.', 'espresso-requirements' ); ?></div>
                </div>
                <?php } elseif ( !$is_php_mem_ok ) { ?>
                    <div class="alert span9 fail"><i class="icon-remove"></i> <strong><?php _e( 'FAIL', 'espresso-requirements' ); ?></strong><br /><?php _e( 'Your PHP memory is too low.  You may experience issues with white screens or Internal Server Errors.  Please contact your host about upgrading your PHP memory before using Event Espresso.  We recommend PHP memory of 64M or higher.', 'espresso-requirements' ); ?></div>
                </div>
                <?php } ?>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'Max Upload Size', 'espresso-requirements' ); ?></div>
                <div class="span1"><?php echo $max_upload; ?></div>
                <?php if ( $has_max_upload ) { ?>
                    <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong></div>
                <?php } else { ?>
                    <div class="alert span9 warn"><i class="icon-warning-sign"></i> <strong><?php _e( 'WARNING', 'espresso-requirements' ); ?></strong><br /><?php _e( 'Your max upload size as defined by your php.ini file is too low to upload the Event Espresso plugin via the WordPress plugin installer.  You will need to upload via FTP to install Event Espresso.', 'espresso-requirements' ); ?></div>
                <?php } ?>
            </div>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'Current PHP memory usage', 'espresso-requirements' ); ?></div>
                <div class="span10"><?php echo size_format(memory_get_usage(), 2); ?></div>
            </div>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'PHP Info', 'espresso-requirements' ); ?></div>
                <div class="span1"></div>
                <div class="span9 alert info"><i class="icon-exclamation-sign"></i> <a class="thickbox" href="<?php echo plugins_url( 'espresso-requirements-check' ); ?>/assets/phpinfo.php?KeepThis=true&TB_iframe=true&height=600&width=800"><?php _e( 'View your server configuration details.', 'espresso-requirements' ); ?></a></div>
            </div>
            <?php if ( $is_php_strict ) { ?>
                <div class="row-fluid">
                    <div class="span2"><?php _e( 'Error Reporting', 'espresso-requirements' ); ?></div>
                    <div class="span1"><?php _e( 'Strict Found!', 'espresso-requirements' ); ?></div>
                    <div class="span9 alert warn"><i class="icon-warning-sign"></i> <strong><?php _e( 'WARNING', 'espresso-requirements' ); ?></strong><br /><?php _e( 'Your server appears to have PHP error reporting set to <code>E_STRICT</code> (or some similar combination of error reporting constants).  This is <em>not</em> recommended in a production environment.  You may change this at runtime by modifying your <code>wp-config.php</code> file.', 'espresso-requirements' ); ?>  <a class="thickbox" href="#TB_inline?height=150&width=500&inlineId=php_strict"><?php _e( 'Click here to get the code to add to your <code>wp-config.php</code>.', 'espresso-requirements' ); ?></a></div>
                </div>
            <?php } elseif ( $is_wp_debug && !is_plugin_active('debug-bar/debug-bar.php') ) { ?>
                <div class="row-fluid">
                    <div class="span2"><?php _e( 'Error Reporting', 'espresso-requirements' ); ?></div>
                    <div class="span1"><?php _e( 'WP_DEBUG Found!', 'espresso-requirements' ); ?></div>
                    <div class="span9 alert info"><i class="icon-exclamation-sign"></i> <?php _e( 'Your server\'s <code>error_reporting</code> level is very verbose by setting <code>WP_DEBUG</code> to <code>true</code> in your <code>wp-config.php</code>.  It is recommended that you set <code>WP_DEBUG</code> to <code>false</code> before moving your site live or using the', 'espresso-requirements' ); ?> <a href="http://wordpress.org/extend/plugins/debug-bar/" target="_blank"><?php _e( 'Debug Bar', 'espresso-requirements' ); ?></a> <?php _e( 'plugin to hide those notices in the admin bar.', 'espresso-requirements' ); ?></div>
                </div>
            <?php } elseif ( $is_wp_debug && is_plugin_active('debug-bar/debug-bar.php') ) { ?>
                <div class="row-fluid">
                    <div class="span2"><?php _e( 'Error Reporting', 'espresso-requirements' ); ?></div>
                    <div class="span1"><?php _e( 'Debug Bar Found!', 'espresso-requirements' ); ?></div>
                    <div class="span9 alert pass"><i class="icon-ok"></i> <?php _e( '<code>WP_DEBUG</code> is set to <code>true</code> in your <code>wp-config.php</code> file which displays verbose errors to the page.  However, you\'re throwing all of these extra errors, warnings and notices to your admin bar with', 'espresso-requirements' ); ?> <a href="http://wordpress.org/extend/plugins/debug-bar/" target="_blank"><?php _e( 'Debug Bar', 'espresso-requirements' ); ?></a><?php _e( '.  Sweet!  You\'re all set.  Just make sure you don\'t deactivate Debug Bar while <code>WP_DEBUG</code> is <code>true</code> on your live site.', 'espresso-requirements' ); ?></div>
                </div>
            <?php } ?>
        </section>
        <h3><?php _e( 'MySQL', 'espresso-requirements' ); ?></h3>
        <section>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'Your version', 'espresso-requirements' ); ?></div>
                <div class="span1 break-word "><?php echo mysql_get_server_info(); ?></div>
                <?php if ( $is_mysql_valid && $has_recommended_mysql ) { ?>
                    <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong></div>
                <?php } elseif ( $is_mysql_valid && !$has_recommended_mysql ) { ?>
                    <div class="alert span9 warn"><i class="icon-warning-sign"></i> <strong><?php _e( 'WARNING', 'espresso-requirements' ); ?></strong><br /><?php _e( 'The recommended version of MySQL is 5.5 or higher.', 'espresso-requirements' ); ?></div>
                <?php } elseif ( !$is_mysql_valid ) { ?>
                    <div class="alert span9 fail"><i class="icon-remove"></i> <strong><?php _e( 'FAIL', 'espresso-requirements' ); ?></strong><br /><?php _e( 'The required version of MySQL is 5.0.  We recommend MySQL 5.5 or above.', 'espresso-requirements' ); ?></div>
                <?php } ?>
            </div>
        </section>
        <h3><?php _e( 'WordPress', 'espresso-requirements' ); ?></h3>
        <section>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'Your version', 'espresso-requirements' ); ?></div>
                <div class="span1 break-word "><?php echo $wp_version; ?></div>
                <?php if ( $is_wp_valid && $has_recommended_wordpress ) { ?>
                    <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong></div>
                <?php } elseif ( $is_wp_valid && !$has_recommended_wordpress ) { ?>
                    <div class="alert span9 warn"><i class="icon-warning-sign"></i> <strong><?php _e( 'WARNING', 'espresso-requirements' ); ?></strong><br /><?php _e( 'The latest version of WordPress is recommended.', 'espresso-requirements' ); ?> <?php echo sprintf( __( '%1$sUpgrade WordPress now%2$s.', 'espresso-requirements' ), '<a href="update-core.php">', '</a>' ); ?> </div>
                <?php } elseif ( !$is_wp_valid ) { ?>
                    <div class="alert span9 fail"><i class="icon-remove"></i> <strong><?php _e( 'FAIL', 'espresso-requirements' ); ?></strong><br /><?php _e( 'You are using an outdated version of WordPress.  Event Espresso requires WordPress 3.5 or higher.  It is recommended that you upgrade to the latest version of WordPress before using Event Espresso.', 'espresso-requirements' ); ?> <?php echo sprintf( __( '%1$sUpgrade WordPress now%2$s.', 'espresso-requirements' ), '<a href="update-core.php">', '</a>' ); ?></div>
                <?php } ?>
            </div>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'Theme', 'espresso-requirements' ); ?></div>
                <div class="span1 break-word"><?php echo $theme['Name']; ?></div>
                <?php if ( $is_themeforest ) { ?>
                    <div class="alert span9 info"><i class="icon-exclamation-sign"></i> <?php echo sprintf( __( 'You are using %5$s%1$s%4$s by %3$s, which is a %6$sThemeForest%4$s theme.  Please contact your %2$stheme developer%4$s if you have any theme-related issues or %7$spurchase a support token%4$s if there are theme issues you would like the Event Espresso support team to help resolve', 'espresso-requirements' ), $theme['Name'], '<a href="' . $theme_author_uri . '" target="_blank">', $theme_author, '</a>', $theme['ThemeURI'], '<a href="http://themeforest.com" target="_blank">', '<a href="http://eventespresso.com/product/premium-support-token/" target="_blank">' ); ?>
                <?php } else { ?>
                    <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong></div>
                <?php } ?>
                </div>
            <?php if ( $wp_deregister ) { ?>
                    <div class="row-fluid">
                        <div class="span2"><?php _e( 'jQuery', 'espresso-requirements' ); ?></div>
                        <div class="span1"><?php _e( 'Deregister script found!', 'espresso-requirements' ); ?></div>
                        <div class="alert span9 warn"><i class="icon-warning-sign"></i> <strong><?php _e( 'WARNING', 'espresso-requirements' ); ?></strong><br /><?php _e( 'Your theme is improperly loading jQuery. Rather than loading the version of jQuery that is included with WordPress, your theme is loading an alternate version of jQuery. This has been known to cause problems with Event Espresso as well as many other plugins that use jQuery.', 'espresso-requirements' ); ?> <?php echo sprintf( __( 'Please contact your %1$stheme developer%2$s or %3$slearn more%2$s about properly enqueuing javascript files.', 'espresso-requirements' ), '<a href="' . $theme_author_uri . '" target="_blank">', '</a>', '<a href="http://codex.wordpress.org/Function_Reference/wp_enqueue_script" target="_blank">'); ?></div>
                    </div>
            <?php } ?>
        </section>
        <h3><?php _e( 'Server environment', 'espresso-requirements' ); ?></h3>
        <section>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'Server type', 'espresso-requirements' ); ?></div>
                <?php if ( !$is_iis ) { ?>
                    <div class="span10"><?php echo $webserver; ?></div>
                <?php } else { ?>
                    <div class="alert span1 break-word fail"><i class="icon-remove"></i> <?php echo $webserver; ?></div>
                    <div class="alert span9 info"><i class="icon-exclamation-sign"></i> <strong><?php _e( 'FAIL', 'espresso-requirements' ); ?></strong><br /><?php _e( 'You appear to be using a Windows IIS server.  Please be aware that you may experience issues while you are on a Windows server.  We recommend moving to a Unix/Linux-based Apache server, if possible.', 'espresso-requirements' ); ?></div>
                <?php } ?>
            </div>
            <?php if ( $is_apache && function_exists('apache_get_version') && apache_get_version() != $webserver ) { ?>
                <div class="row-fluid">
                    <div class="span2"><?php _e( 'Apache version', 'espresso-requirements' ); ?></div>
                    <div class="span10"><?php echo apache_get_version(); ?></div>
                </div>
            <?php } ?>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'cURL', 'espresso-requirements' ); ?></div>
                <?php if ( $is_curl_valid ) { ?>
                    <div class="span1"><?php _e( 'Found!', 'espresso-requirements' ); ?></div>
                    <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong></div>
                <?php } else { ?>
                    <div class="span1"><?php _e( 'Not found!', 'espresso-requirements' ); ?></div>
                    <div class="alert span9 fail"><i class="icon-remove"></i> <strong><?php _e( 'FAIL', 'espresso-requirements' ); ?></strong><br /><?php _e( 'cURL is required by some gateways.  You may experience some difficulties with transactions if cURL is not enabled.', 'espresso-requirements' ); ?></div>
                <?php } ?>
            </div>
            <?php if ( $is_apache && !$mod_rewrite || $is_apache && !$has_mod_security ) { ?>
                <div class="row-fluid">
                    <div class="span2"><?php _e( 'Recommended Apache Modules', 'espresso-requirements' ); ?></div>
                    <div class="span1"><?php _e( 'Not Found!', 'espresso-requirements' ); ?></div>
                    <div class="alert span9 warn"><i class="icon-warning-sign"></i> <strong><?php _e( 'WARNING', 'espresso-requirements' ); ?></strong><br /><?php _e( 'Some recommended Apache modules appear to be missing from your installation.  This may be because of your PHP configuration or because they have not been enabled in your Apache configuration.  While this may not cause any obvious errors, some features may not work correctly.  Please contact your webhost about activating additional Apache modules or upgrading Apache.', 'espresso-requirements' ); ?>
                        <?php if ( function_exists( 'apache_get_modules' ) ) { ?><a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=apache_mods"><?php _e( 'View installed Apache modules.', 'espresso-requirements' ); ?></a></div>
                        <?php } else { ?>
                        </div>
                    </div>
                    <?php } ?>
            <?php } ?>
            <?php if ( $is_apache ) { ?>
                <div class="row-fluid">
                    <div class="span2"><?php _e( 'Apache security', 'espresso-requirements' ); ?></div>
                    <?php if ( $has_mod_security ) { ?>
                        <div class="span1"><?php _e( 'Found!', 'espresso-requirements' ); ?></div>
                        <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong></div>
                    <?php } else { ?>
                        <div class="span1"><?php _e( 'Not found!', 'espresso-requirements' ); ?></div>
                        <div class="alert span9 warn"><i class="icon-warning-sign"></i> <strong><?php _e( 'WARNING', 'espresso-requirements' ); ?></strong><br /><?php _e( 'Your site may be vulnerable to spam registrations without the Apache module <code>mod_security</code> active on your server.  We recommend enabling reCAPTCHA on the Event Espresso General Settings page to avoid spam registrations.', 'espresso-requirements' ); ?>
                              <?php if ( function_exists( 'apache_get_modules' ) ) { ?><a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=apache_mods"><?php _e( 'View installed Apache modules.', 'espresso-requirements' ); ?></a><?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php
            $site_url = 0;
            $site_htaccess = 0;
            $root_htaccess = 0;
            $wpcontent_htaccess = 0;
            $wpadmin_htaccess = 0;
            $wpincludes_htaccess = 0;
            if ( site_url() != home_url() ) {
                chdir('../..');
                $base_dir = getcwd() . '/';
                $site_url = 1;
            }
            $root_dir = ABSPATH;
                if ( file_exists($root_dir . '.htaccess') ) {
                    $root_htaccess = 1; ?>
                    <div class="row-fluid">
                        <div class="span2"><?php _e( 'Home directory .htaccess file', 'espresso-requirements' ); ?></div>
                        <div class="span1"><?php _e( 'Found!', 'espresso-requirements' ); ?></div>
                        <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong> <a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=root_htaccess"><?php _e( 'View .htaccess file', 'espresso-requirements' ); ?></a></div>
                    </div>
                <?php } ?>
                <?php if ( ( $site_url ) && file_exists($base_dir . '.htaccess') ) {
                    $site_htaccess = 1; ?>
                    <div class="row-fluid">
                        <div class="span2"><?php _e( 'Root directory .htaccess file', 'espresso-requirements' ); ?></div>
                        <div class="span1"><?php _e( 'Found!', 'espresso-requirements' ); ?></div>
                        <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong> <a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=site_htaccess"><?php _e( 'View .htaccess file', 'espresso-requirements' ); ?></a></div>
                    </div>
                <?php } ?>
                <?php if ( file_exists($root_dir . 'wp-content/.htaccess') ) {
                    $wpcontent_htaccess = 1; ?>
                    <div class="row-fluid">
                        <div class="span2"><?php _e( '/wp-content/ .htaccess file', 'espresso-requirements' ); ?></div>
                        <div class="span1"><?php _e( 'Found!', 'espresso-requirements' ); ?></div>
                        <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong> <a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=wpcontent_htaccess"><?php _e( 'View .htaccess file', 'espresso-requirements' ); ?></a></div>
                    </div>
                <?php } ?>
                <?php if ( file_exists($root_dir . 'wp-admin/.htaccess') ) {
                    $wpadmin_htaccess = 1; ?>
                    <div class="row-fluid">
                        <div class="span2"><?php _e( '/wp-admin/ .htaccess file', 'espresso-requirements' ); ?></div>
                        <div class="span1"><?php _e( 'Found!', 'espresso-requirements' ); ?></div>
                        <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong> <a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=wpadmin_htaccess"><?php _e( 'View .htaccess file', 'espresso-requirements' ); ?></a></div>
                    </div>
                <?php } ?>
                <?php if ( file_exists($root_dir . 'wp-includes/.htaccess') ) {
                    $wpincludes_htaccess = 1; ?>
                    <div class="row-fluid">
                        <div class="span2"><?php _e( '/wp-includes/ .htaccess file', 'espresso-requirements' ); ?></div>
                        <div class="span1"><?php _e( 'Found!', 'espresso-requirements' ); ?></div>
                        <div class="alert span9 pass"><i class="icon-ok"></i> <strong><?php _e( 'PASS', 'espresso-requirements' ); ?></strong> <a class="thickbox" href="#TB_inline?height=300&width=400&inlineId=wpincludes_htaccess"><?php _e( 'View .htaccess file', 'espresso-requirements' ); ?></a></div>
                    </div>
                <?php } ?>
            <?php if ( !$mod_rewrite && !$root_htaccess || !$mod_rewrite && !$site_htaccess ) { ?>
                <div class="row-fluid">
                    <div class="span2"><?php _e( '.htaccess file', 'espresso-requirements' ); ?></div>
                    <div class="span1"><?php _e( 'Not Found!', 'espresso-requirements' ); ?></div>
                    <div class="alert span9 info"><i class="icon-exclamation-sign"></i> <strong><?php _e( 'RECOMMENDED', 'espresso-requirements' ); ?></strong><br /><?php _e( 'You do not appear to have an .htaccess file or <code>mod_rewrite</code> is not enabled.  This is not a problem <em>per se</em>, but it means you cannot use WordPress\' "Pretty Permalinks".', 'espresso-requirements' ); ?> <a href="http://codex.wordpress.org/Using_Permalinks#Using_.22Pretty.22_permalinks" target="_blank"><?php _e( 'What are "pretty" permalinks?', 'espresso-requirements' ); ?></a></div>
                </div>
            <?php } ?>
            <?php if ( $is_godaddy ) { ?>
                <div class="row-fluid">
                    <div class="span2"><?php _e( 'GoDaddy server', 'espresso-requirements' ); ?></div>
                    <div class="span1"><?php _e( 'Found!', 'espresso-requirements' ); ?></div>
                    <div class="alert span9 fail"><i class="icon-remove"></i> <strong><?php _e( 'FAIL', 'espresso-requirements' ); ?></strong><br /><?php _e( 'You appear to be using a GoDaddy-hosted site.  We strongly advise against using GoDaddy for webhosting as we have seen countless problems on GoDaddy sites that have been resolved simply by changing hosts and could not be resolved on GoDaddy.  We may not be able to provide the best support possible as long as your site is hosted by GoDaddy.', 'espresso-requirements' ); ?></div>
                </div>
            <?php } ?>
            <?php if ( $is_wpengine ) { ?>
                <div class="row-fluid">
                    <div class="span2"><?php _e( 'WP Engine server', 'espresso-requirements' ); ?></div>
                    <div class="span1"><?php _e( 'Found!', 'espresso-requirements' ); ?></div>
                    <div class="alert span9 info"><i class="icon-exclamation-sign"></i> <strong><?php _e( 'INFORMATION', 'espresso-requirements' ); ?><br /><?php _e( 'You appear to be on a WP Engine-powered site.', 'espresso-requirements' ); ?> <a href="http://wpengine.com/contact/" target="_blank"><?php _e( 'Please contact WP Engine if you have any questions or problems relating to your server environment.', 'espresso-requirements' ); ?></a></div>
                </div>
            <?php } ?>
            <?php if ( $is_mediatemple ) { ?>
                <div class="row-fluid">
                    <div class="span2"><?php _e( 'MediaTemple server', 'espresso-requirements' ); ?></div>
                    <div class="span1"><?php _e( 'Found!', 'espresso-requirements' ); ?></div>
                    <div class="alert span9 info"><i class="icon-exclamation-sign"></i> <strong><?php _e( 'INFORMATION', 'espresso-requirements' ); ?></strong><br /><?php _e( 'You appear to be on a MediaTemple-powered site.  Please note that we have historically seen various issues on MediaTemple shared servers.', 'espresso-requirements' ); ?>  <a href="http://kb.mediatemple.net/" target="_blank"><?php _e( 'Consult their support documentation', 'espresso-requirements' ); ?></a> <?php _e( 'or', 'espresso-requirements' ); ?> <a href="https://ac.mediatemple.net/" target="_blank"><?php _e( 'log in to your AccountCenter', 'espresso-requirements' ); ?></a> <?php _e( 'if you have any problems with your site.', 'espresso-requirements' ); ?></div>
                </div>
            <?php } ?>
        </section>
        <section>
            <div class="row-fluid">
                <div class="span2"><?php _e( 'Requirements Check version', 'espresso-requirements' ); ?></div>
                <div class="span10"><?php echo espresso_requirements_get_version(); ?></div>
            </div>
        </section>
    <?php if ( $root_htaccess ) { ?>
        <div id="root_htaccess" style="display:none;">
            <pre>
                <?php echo file_get_contents($root_dir . '.htaccess'); ?>
            </pre>
        </div>
    <?php } ?>
    <?php if ( $site_htaccess ) { ?>
        <div id="site_htaccess" style="display:none;">
            <pre>
                <?php echo file_get_contents($base_dir . '.htaccess'); ?>
            </pre>
        </div>
    <?php } ?>
    <?php if ( $wpcontent_htaccess ) { ?>
        <div id="wpcontent_htaccess" style="display:none;">
            <pre>
                <?php echo file_get_contents($root_dir . 'wp-content/.htaccess'); ?>
            </pre>
        </div>
    <?php } ?>
    <?php if ( $wpadmin_htaccess ) { ?>
        <div id="wpadmin_htaccess" style="display:none;">
            <pre>
                <?php echo file_get_contents($root_dir . 'wp-admin/.htaccess'); ?>
            </pre>
        </div>
    <?php } ?>
    <?php if ( $wpincludes_htaccess ) { ?>
        <div id="wpincludes_htaccess" style="display:none;">
            <pre>
                <?php echo file_get_contents($root_dir . 'wp-includes/.htaccess'); ?>
            </pre>
        </div>
    <?php } ?>
    <?php if ( function_exists( 'apache_get_modules' ) ) { ?>
        <div id="apache_mods" style="display:none;">
            <pre>
                <?php print_r(apache_get_modules()); ?>
            </pre>
        </div>
    <?php } ?>
    <div id="php_strict" style="display:none;">
        <pre>

    // add these lines to your wp-config.php
    // right above the "That's all, stop editing!"
    ini_set('display_errors', '0');     // don't show any errors...
    error_reporting(E_ALL | E_STRICT);  // ...but do log them
        </pre>
    </div>
<?php }

function espresso_requirements_response() {
    global $is_warn, $is_fail, $is_pass;

    if ( $is_pass && !$is_warn ) {
        $message = '<strong>' . __( 'Success!', 'espresso-requirements' ) . '</strong><br />' . __( 'Congratulations! Your server supports Event Espresso!', 'espresso-requirements' ) . ' <a target="_blank" href="http://eventespresso.com/download/">' . __( 'Get your copy of Event Espresso here.', 'espresso-requirements' ) . '</a> ' . sprintf(  __( 'See %1$sthe Event Espresso Requirements page%2$s for more information.', 'espresso-requirements' ), '<a href="tools.php?page=espresso_requirements_page">', '</a>' );
    }
    elseif ( $is_pass && $is_warn ) {
        $message = '<strong>' . __( 'Passed with warnings.', 'espresso-requirements' ) . '</strong><br />' . sprintf( __( 'Your server supports the minimum requirements but may experience some difficulties with all features.  See %1$sthe Event Espresso Requirements page%2$s for more information.', 'espresso-requirements' ), '<a href="tools.php?page=espresso_requirements_page">', '</a> ' ) . ' <a target="_blank" href="http://eventespresso.com/download/">' .__( 'Get your copy of Event Espresso here.', 'espresso-requirements' ) . '</a>';
    }
    else {
        $message = '<strong>' . __( 'Failed!', 'espresso-requirements' ) . '</strong></br />' . sprintf( __( 'Your server does not meet the minimum requirements to run Event Espresso.  Please refer to the information on %1$sthe Event Espresso Requirements page%2$s before attempting to install and run Event Espresso on your site.' ), '<a href="tools.php?page=espresso_requirements_page">', '</a> ' ) . sprintf( __( 'Once you have addressed the issues below, re-run this plugin and %1$sget your copy of Event Espresso here%2$s.', 'espresso-requirements' ), '<a href="http://eventespresso.com/download/">', '</a>' );
    }
    return $message;
}

function espresso_requirements_message(){
    global $is_warn, $is_fail, $is_pass;

    if ( $is_pass && !$is_warn ) {
        $top_message_head = '<div class="updated alert span12 pass">';
        $message_head = '<div class="update-message alert span12 pass">';
        $message = espresso_requirements_response() . '</div>';
    } elseif ( $is_pass && $is_warn ) {
        $top_message_head = '<div class="updated alert span12 warn">';
        $message_head = '<div class="update-message alert span12 warn">';
        $message = espresso_requirements_response() . '</div>';
    } else {
        $top_message_head = '<div class="updated alert span12 fail">';
        $message_head = '<div class="update-message alert span12 fail">';
        $message = espresso_requirements_response()  . '</div>';
    }
    echo '</tr><tr class="plugin-update-tr"><td colspan="5" class="plugin-update">' . $top_message_head . $message . $message_head . $message . '</td></tr>';

}