<?php
/**
 * Plugin Name: Social Tags
 * Plugin URI: 
 * Description: Give your tags social skills
 * Version: 0.1
 * Author: Jonah Dahlquist
 * Author URI: http://nucleussystems.com/
 * License: none
 */

define('SOCIALTAGS', 'socialtags');

load_plugin_textdomain(SOCIALTAGS, false, basename(dirname(__FILE__)) . '/languages');

class Socialtags {

    private static $last_terms = array();

    /**
     * Initialize the plugin.  Inject necessary CSS.
     */
    public static function init() {
        wp_enqueue_style('socialtags', plugins_url('social-tags/stylesheets/main.css'), '0.1.0');
    }

    /**
     * Save terms on currently processing post to be re-rendered later.
     */
    public static function filter_get_the_terms($terms, $post_id, $taxonomy) {
        if ($taxonomy == 'post_tag') {

            self::$last_terms = array_values($terms);
        }
        return $terms;
    }

    /**
     * Give theme tag links with dropdowns embedded into markup using
     * previously captured tags.
     */
    public static function filter_term_links($tag_links) {
        foreach ($tag_links as $index => $tag_link) {
            if (isset(self::$last_terms[$index])) {
                $tag = self::$last_terms[$index];
                $tag_name = preg_replace('/[^a-z0-9-_]/ui', '', $tag->name);
                $tag_name = urlencode($tag_name);
                $tag_link = get_term_link($tag);
                $tag_links[$index] = apply_filters('socialtags_render', null, $tag_name, $tag_link);
            }
        }
        return $tag_links;
    }

    /**
     * Provide the social networks to the rendering method.  This is done
     * through a filter so that themes and other plugins can add their own
     * networks to the dropdown.
     */
    public static function filter_socialtags_network_urls($network_urls) {
        return array_merge($network_urls, array(
            'https://twitter.com/search?q=%%23%s' => 'Twitter',
            'https://plus.google.com/s/%%23%s' => 'Google+',
            'https://www.facebook.com/hashtag/%s' => 'Facebook',
        ));
    }

    /**
     * Render a tag dropdown menu.  Done through a filter so that it can be
     * overridden.
     */
    public static function filter_socialtags_render($output, $tag_name, $tag_url) {
        $network_urls = apply_filters('socialtags_network_urls', array());
        $networks = '';
        foreach ($network_urls as $network_url => $network_name) {
            $network_url = sprintf($network_url, $tag_name);
            $networks .= "<li><a target=\"_blank\" href=\"$network_url\">$network_name</a></li>";
        }

        return "<div class=\"socialtags-dropdown\" tabindex=\"-1\">
                    <a href=\"$tag_url\">#$tag_name</a>
                    <ul class=\"socialtags-dropdown-menu\">
                        <li class=\"socialtags-dropdown-header\">" .
                            __('Join the Conversation', SOCIALTAGS) .
                        "</li>
                        $networks
                    </ul>
                </div>";

    }

}

// WordPress hooks
add_filter('init',                    array('Socialtags', 'init'));
add_filter('get_the_terms',           array('Socialtags', 'filter_get_the_terms'), 10, 3);
add_filter('term_links-post_tag',     array('Socialtags', 'filter_term_links'));

// Socialtags hooks
add_filter('socialtags_network_urls', array('Socialtags', 'filter_socialtags_network_urls'));
add_filter('socialtags_render',       array('Socialtags', 'filter_socialtags_render'), 5, 3);

