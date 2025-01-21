<?php

/**
 * Loads the Divi 4 and Divi 5 extensions.
 *
 * @since  3.1.2
 */

// Die.
if (! defined('ABSPATH')) {
  die('Direct access forbidden.');
}


// Load extensions.
require_once DVMD_TM_PLUGIN_DIR_PATH . 'extensions/divi-4/load.php';
// require_once DVMD_TM_PLUGIN_DIR_PATH . 'extensions/divi-5/load.php';


/**
 * Enqueues public styles and scripts.
 *
 * @since   3.1.2
 *
 * @return  void
 */
add_action('wp_enqueue_scripts', function() {

  // Style. (Icons)
  $src = DVMD_TM_PLUGIN_DIR_URL . 'extensions/styles/public-module-style.css';
  wp_enqueue_style('dvmd-tm-public-module-style', $src, false, DVMD_TM_PLUGIN_VERSION);

  // Bail.
  $is_fb = (function_exists('et_core_is_fb_enabled') && et_core_is_fb_enabled());
  if ($is_fb) return;

  // Script. (Accordion & Hover)
  $src = DVMD_TM_PLUGIN_DIR_URL . 'extensions/scripts/public-module-script-min.js';
  wp_enqueue_script('dvmd-tm-public-module-script', $src, false, DVMD_TM_PLUGIN_VERSION, true);
});
