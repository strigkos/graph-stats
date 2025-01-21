<?php

/**
 * Loads the Divi 4 extension.
 *
 * @since  3.1.0
 */

// Die.
if (! defined('ABSPATH')) {
  die('Direct access forbidden.');
}


/**
 * Inits the extension.
 *
 * @since   3.1.0
 *
 * @return  void
 */
add_action('divi_extensions_init', function() {
  require_once DVMD_TM_PLUGIN_DIR_PATH . 'extensions/divi-4/includes/main-class.php';
});
