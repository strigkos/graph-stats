<?php

/**
 * Plugin Uninstall.
 *
 * @since  3.1.0
 *
 */

// Bail.
if (! defined('WP_UNINSTALL_PLUGIN')) exit;
if (! current_user_can('activate_plugins')) exit;

// Delete data.
$options = get_option('dvmd_tm_admin_options');
if (isset($options['delete_data']) && $options['delete_data']) {
  delete_option('dvmd_tm_plugin_version');
  delete_option('dvmd_tm_admin_options');
  delete_option('dvmd_tm_products_options');
  delete_option('dvmd_tm_activation_options');
}
