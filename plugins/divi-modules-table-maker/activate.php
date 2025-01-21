<?php

/**
 * Checks server environment for correct PHP version, MBSTRING, DOM and LIBXML extensions.
 *
 * @since   3.1.0
 *
 */

// Die.
if (! defined('ABSPATH')) {
  die('Direct access forbidden.');
}

$err = array();

// Check PHP version.
if (! version_compare(PHP_VERSION, '5.6', '>=')) {
  $e = esc_html__('This plugin requires that your server PHP version is 5.6 or greater.', 'dvmd-table-maker');
  array_push($err, $e);
}

// Check MBSTRING extension.
if (! extension_loaded('mbstring')) {
  $e = esc_html__('This plugin requires that your server PHP has the MBSTRING extension enabled.', 'dvmd-table-maker');
  array_push($err, $e);
}

// Check DOM extension.
if (! extension_loaded('dom')) {
  $e = esc_html__('This plugin requires that your server PHP has the DOM extension enabled.', 'dvmd-table-maker');
  array_push($err, $e);
}

// Check LIBXML extension.
if (! extension_loaded('libxml')) {
  $e = esc_html__('This plugin requires that your server PHP has the LIBXML extension enabled.', 'dvmd-table-maker');
  array_push($err, $e);
}

// Build error message.
if (count($err)) {
  $m2 = '';
  foreach ($err as $k => $v) $m2 .= '<br>' . ($k + 1) . ') ' . $v;
  $m1 = sprintf(esc_html__('Divi-Modules – %s has been deactivated because:', 'dvmd-table-maker'), DVMD_TM_PLUGIN_TITLE);
  $m3 = esc_html__('See your website hosting control panel, or contact your hosting provider.', 'dvmd-table-maker');
  $message = sprintf('<div class="notice error"><p><strong>%s</strong>%s<br>%s</p></div>', $m1, $m2, $m3);
  set_transient('dvmd-tm-activate-error', $message, 30);
}
