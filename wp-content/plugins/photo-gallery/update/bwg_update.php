<?php

function bwg_update($version) {
  global $wpdb;
  if (version_compare($version, '1.0.1') == -1) {
    // Add thumb title option.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `image_title_show_hover` varchar(20) NOT NULL DEFAULT 'none' AFTER `image_enable_page`");
    // Add image title theme options.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_shadow` varchar(64) NOT NULL DEFAULT '0px 0px 0px #888888' AFTER `thumb_transition`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_margin` varchar(64) NOT NULL DEFAULT '2px' AFTER `thumb_transition`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_font_weight` varchar(64) NOT NULL DEFAULT 'bold' AFTER `thumb_transition`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_font_size` int(4) NOT NULL DEFAULT 16 AFTER `thumb_transition`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_font_style` varchar(64) NOT NULL DEFAULT 'segoe ui' AFTER `thumb_transition`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_font_color` varchar(64) NOT NULL DEFAULT 'CCCCCC' AFTER `thumb_transition`");
    // Add thumb upload dimensions.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `upload_thumb_height` int(4) NOT NULL DEFAULT 300 AFTER `thumb_height`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `upload_thumb_width` int(4) NOT NULL DEFAULT 300 AFTER `thumb_height`");
  }
  if (version_compare($version, '1.1.10') == -1) {
    // Add image right click option.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `image_right_click` tinyint(1) NOT NULL DEFAULT 0 AFTER `gallery_role`");
    // Add popup fullscreen option
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `popup_fullscreen` tinyint(1) NOT NULL DEFAULT 0 AFTER `image_right_click`");
  }
  if (version_compare($version, '1.1.12') == -1) {
    // Add image title position.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_pos` varchar(64) NOT NULL DEFAULT 'bottom' AFTER `thumb_title_font_style`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `album_compact_thumb_title_pos` varchar(64) NOT NULL DEFAULT 'bottom' AFTER `album_compact_title_font_style`");
	  // Add popup autoplay option.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `popup_autoplay` tinyint(1) NOT NULL DEFAULT 0 AFTER `popup_fullscreen`");
	  // Add album view type option.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `album_view_type` varchar(255) NOT NULL DEFAULT 'thumbnail' AFTER `popup_autoplay`");
  }
  if (version_compare($version, '1.1.14') == -1) {
    // Add Pinterest, Tumblr share buttons enable options.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `popup_enable_pinterest` tinyint(1) NOT NULL DEFAULT 0 AFTER `popup_fullscreen`");
	  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `popup_enable_tumblr` tinyint(1) NOT NULL DEFAULT 0 AFTER `popup_fullscreen`");
	  // Add image title/description theme options.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_info_pos` varchar(128) NOT NULL DEFAULT 'top' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_info_align` varchar(128) NOT NULL DEFAULT 'right' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_info_bg_color` varchar(128) NOT NULL DEFAULT '000000' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_info_bg_transparent` int(4) NOT NULL DEFAULT 70 AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_info_border_width` int(4) NOT NULL DEFAULT 1 AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_info_border_style` varchar(128) NOT NULL DEFAULT 'none' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_info_border_color` varchar(128) NOT NULL DEFAULT '000000' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_info_border_radius` varchar(128) NOT NULL DEFAULT '5px' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_info_padding` varchar(128) NOT NULL DEFAULT '5px' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_info_margin` varchar(128) NOT NULL DEFAULT '15px' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_title_color` varchar(128) NOT NULL DEFAULT 'FFFFFF' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_title_font_style` varchar(128) NOT NULL DEFAULT 'segoe ui' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_title_font_weight` varchar(128) NOT NULL DEFAULT 'bold' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_title_font_size` int(4) NOT NULL DEFAULT 18 AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_description_color` varchar(128) NOT NULL DEFAULT 'FFFFFF' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_description_font_style` varchar(128) NOT NULL DEFAULT 'segoe ui' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_description_font_weight` varchar(128) NOT NULL DEFAULT 'normal' AFTER `image_browser_full_transparent`");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `lightbox_description_font_size` int(4) NOT NULL DEFAULT 14 AFTER `image_browser_full_transparent`");
  }
  if (version_compare($version, '1.1.15') == -1) {
    // Add search box option.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `show_search_box` tinyint(1) NOT NULL DEFAULT 0 AFTER `album_view_type`");
    // Add search box width option.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `search_box_width` int(4) NOT NULL DEFAULT 180 AFTER `show_search_box`");
    // Add info enable/disable option.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `popup_enable_info` tinyint(1) NOT NULL DEFAULT 1 AFTER `search_box_width`");
  }
  return;
}

?>
