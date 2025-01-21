<?php

/**
 * Gets and renders the product feed.
 *
 * @since   3.1.1
 *
 * @return  void
 */

// Properties.
$version = esc_attr(DVMD_TM_PRODUCT_FEED);
$allowed = wp_kses_allowed_html('post');
$allowed['style'] = array();

// Cached feed.
// delete_transient("dvmd_product_feed_{$version}");
$feed = get_transient("dvmd_product_feed_{$version}");
if ($feed) {
  echo wp_kses($feed, $allowed);
  return;
}

// Live feed.
$feed = wp_remote_get("https://divi-modules.com/feed/product/?version={$version}", array(
  'timeout'   => 30,
  'sslverify' => false,
));

// Cache and output.
if (! is_wp_error($feed) && isset($feed['body']) && strlen($feed['body']) > 0) {
  $feed = wp_remote_retrieve_body($feed);
  if (strpos($feed, "<!--dvmd-product-feed-{$version}-->")) {
    set_transient("dvmd_product_feed_{$version}", $feed, 60 * 60 * 24); // 24 hours.
    echo wp_kses($feed, $allowed);
    return;
  }
}

// Error and output.
echo wp_kses(sprintf('<div class="notice error"><p>%s <a href="%s" target"_blank">%s</a></p></div>',
  /* 01 */ sprintf(__('There was an error getting the %s product list from the server.', 'dvmd-table-maker'), '<strong>Divi-Modules</strong>'),
  /* 02 */ esc_attr(admin_url(sprintf('admin.php?page=dvmd-table-maker&tab=%s', self::get_active_tab()))),
  /* 03 */ esc_html__('Please try again later', 'dvmd-table-maker')
), $allowed);
