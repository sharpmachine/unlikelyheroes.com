=== Event Espresso Requirements Check ===
Contributors: jazzs3quence, sethshoultes, eventespresso
Donate link: http://eventespresso.com/
Tags: event registration, forms, events, event management, email, captcha
Requires at least: 2.8
Tested up to: 3.6
Stable tag: 0.9

This plugin checks your web hosting environment to ensure compatibility with Event Espresso, the premium event management plugin for WordPress.

== Description ==

This plugin checks your server for some basic environment information to ensure compatibility with Event Espresso, the premium event management plugin for WordPress. While it's possible to use this plugin for general environment information, it is primarily focussed on providing information that would benefit or relate to your Event Espresso installation.

**It is *not* recommended to leave this plugin active.** While it won't harm your server in any way if you do, there are a number of checks and global variables that are set that don't need to be running all the time.

If you have any feature requests or issues, please post to the plugin forums on WordPress.org.

Event Espresso is the premier event management solution for WordPress.  Event Espresso provides an easy to use interface for creating and managing event registrations within the WordPress Administration Panel. Create a event in minutes!

* Seamless Integration with WordPress 2.8+
* Visually create events with our sleek editor
* Protect your data from spam with reCAPTCHA
* Easily embed an event into to your posts or pages
* Export registration data to CSV
* View registration summary from the Dashboard

See the official [Event Espresso website](http://eventespresso.com/) for more details.

== Changelog ==

= Version 0.9 =

* fixed debug bar check
* better WordPress version check
* updated WordPress requirement
* fixed fatal error on dns_get_record
* fixed undefined variable notices
* fixed E_DEPRECATED notices
* added a check for wp_deregister_script('jquery')
* improved Themeforest check

= Version 0.8 =

* fixed header alert on plugins page
* fixed bold message under plugin row
* added more information and a apache_get_modules check
* moved apache_get_modules info to the top if apache_get_modules isn't found

= Version 0.7.8 =

* more accurate error reporting
* added wp_debug check
* displays a pass if wp_debug is true and debug bar is active

= Version 0.7.7 =

* fixes Media Temple check
* updated some warning text and rearranged server environment checks so all the server stuff is together and all the hosting stuff is after everything else

= Version 0.7.6 =

* adds a check before wp_get_theme function is run
* adds more comments to code

= Version 0.7.5 =

* added upload_max_filesize check
* added phpinfo link in the php section
* adds check for themeforest author uri
* adds php strict checking
* abstrats the version number at the bottom of the requirements check page to a function that gets it from the plugin version (so it doesn't need to be updated in two places in every update)
* adds Media Temple check
* displays link to installed apache modules on mod_security warning
* makes error levels to look for an array

= Version 0.7.4 =

* removed apache problems check (was detecting problems when there were none)
* moved mod_rewrite check to top of file, using `$mod_rewrite` as a global variable

* adds changelog

= Version 0.7.3 =

* adds an exception to the godaddy flag if using wp engine and displays a message if they are on a wp engine site

= Version 0.7.2 =

* this update adds server environment variables for htaccess/mod_rewrite problems

= Version 0.7.1 =

* minor update, adds initial default value for `$apache_problems` variable.

= Version 0.7 =

* minor fix that hides apache version if it's the same as the `$webserver` variable (which uses `$_SERVER['SERVER_SOFTWARE']`). probably doesn't apply to most environments other than localhost installs that seem to dump more stuff into that variable.
* on my live server apache_get_version() just returns "Apache" with no actual version number...so this should only display if the two things are different (which they may well never be)...

= Version 0.6.2 =

* adds .htaccess check and outputs contents of .htaccess file for debugging
* fixes link to requirements page from plugins page
* adds a check for htaccess and displays the contents of the htaccess file(s) if found. if not, displays an information message.
* adds a phpinfo file for more information about failed checks
* adds a check for `apache_get_modules`, if that fails, it triggers an "apache_problems" flag which is an automatic fail
* adds scripts to the plugins.php page to load the stylesheets on that page
* displays apache version if `apache_get_version` is present
* displays a warning if `mod_security` or `mod_rewrite` are not found on an apache installation
* displays a fail if `apache_get_modules` is not found on an apache installation
* adds a flag for htaccess files in all possible directories and does a better check for if those files are found
* checks if the htaccess file exists before displaying the hidden div with the contents
* displays installed apache modules if `apache_get_modules` is present
* displays a requirements check version for debugging
* removes phpinfo link on requirements page for `apache_get_modules` error since it's being displayed in the top fail error
* fixed some layout issues
* changed some language in the mod_rewrite/htaccess info alert

= Version 0.6.1 =

* changed > to >= for memory check (64M shouldn't trigger warning)

= Version 0.6 =

* updates iis messages so they are using the same layout as everything else
* adds break-word class to IIS message

= Version 0.5 =

* updated versions it checks for, and added a requirements check page that shows what you are using
* adds some extra server environment parameters
* finishes up the checks, and plugs it into the alert on the plugins page.
* adds css and icon
* changed db check to fix josh's fatal errors
* cleans up the markup with some twitter bootstrap styles
* adds glyphicon set. technically this isn't gpl, but that's okay because we're not posting this in the wp repository
* adds some twitter bootstrap grid stuff
* added some alert styling and other cosmetic updates
* updated messages on the plugins page
* fixed repeating icon thing
* updated author & notes
* fixes fatal error if `apache_get_modules` doesn't exist
* breaks word for wp version and mysql version (pretty much just for firefox and possibly IE)
* moves submenu to tools instead of Plugins

= Version 0.1 =

* Original Version

== Frequently Asked Questions ==

= Is this the Event Espresso plugin? =

No, this is a plugin that allows you to test your system configuration and hosting to tell if you meet the Event Espresso plugin minimum requirements.

See the official [Event Espresso website](http://eventespresso.com/) for more details.

== Installation ==

To install the plugin and verify your web hosting environment is compatible with Event Espresso:

- Upload the plugin to /wp-content/plugins
- Activate the plugin via the Plugins menu
- A message will appear notifying you if your hosting is compatible.  If Event Espresso is not compatible, you will be notified which requirements failed.

See the [official documentation](http://eventespresso.com/wiki/espresso-requirements-check/) for more information.

== Screenshots ==

Screenshots are available in the [official documentation](http://eventespresso.com/wiki/espresso-requirements-check/) for the plugin.