=== Social Tags ===
Contributors: sharpmachine
Tags: tags, social networks, twitter, facebook, google, google+, hashtags, hash tags, tag converter
Requires at least: 3.0
Test up to: 3.6
Stable tag: 0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically converts a post's tags into hashtags, allowing your readers to join the conversation on their favorite social network.

== Description ==

With Social Tags, the post's tags are seen as hashtags, and adds a dropdown menu when the hashtag is hovered over, giving the reader the opportunity to join that hashtag's conversation on Twitter, Facebook or Google+.

Blogs are the digital counterpart to newspapers and magazines.  Titles are likened to newpaper headlines, while categories are likened to the sections of a newspaper (Sports, Finance, etc.).  Tags are where blogs are different than their printed counterparts.  They allow the readers to easily find other articles related to a specific topic, within the blog.

Today, hashtags do the same as tags across our favorite social networks, creating topical conversations across the internet.  By converting your blogs tags into hashtags, you give your readers the opportunity to see what others are saying about a topic, outside of the confines of your blog.

Give your tags social skills!

== Customization ==

If you know how to modify a plugin or theme, you can add more social networks if you want!  Simply add a filter to `socialtags_network_urls`.  You can do that like so:

    function add_custom_urls($urls) {
        return array_merge($urls, array(
            'https://twitter.com/search?q=%%23%s' => 'Twitter',
        );
    }
    add_filter('socialtags_network_urls', 'add_custom_urls');

The tag will be injected into the URL string with `sprintf()`.

== Installation ==

= Automatic =

Upload the plugin zip file to your site with the plugin admin, and activate it.

= Manual =

Unzip the plugin zip file and upload the socialtags directory to the `wp-content/plugins` directory on your site, and activate from the admin.

== Screenshots ==

1. Just add tags as normal

2. Your readers will be have the opportunity to join the conversation on their favorite social network

3. Twitter

4. Google+

5. Facebook

