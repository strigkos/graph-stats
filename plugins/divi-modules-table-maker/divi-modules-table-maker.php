<?php

/**
 * Plugin Name:  Divi-Modules – Table Maker
 * Plugin URI:   https://divi-modules.com/products/table-maker/
 * Description:  Brings beautiful responsive tables to the Divi Builder.
 * Version:      3.1.2
 * Author:       Divi-Modules
 * Author URI:   https://divi-modules.com/
 * Update URI:   https://divi-modules.com/
 * License:      GPLv2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  dvmd-table-maker
 * Domain Path:  /languages
 *
 * Divi-Modules – Table Maker is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by the
 * Free Software Foundation, either version 2 of the License, or any later version.
 *
 * Divi-Modules – Table Maker is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License.
 * If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

// Die.
if (! defined('ABSPATH')) {
  die('Direct access forbidden.');
}

// Bail.
if (defined('DVMD_TM_PLUGIN_TITLE')) return;

// Constants.
define('DVMD_TM_PLUGIN_TITLE',   'Table Maker');
define('DVMD_TM_PLUGIN_VERSION', '3.1.2');
define('DVMD_TM_PLUGIN_FILE',     __FILE__);
define('DVMD_TM_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('DVMD_TM_PLUGIN_DIR_URL',  plugin_dir_url(__FILE__));

// Activate plugin.
register_activation_hook(__FILE__, function() {
  require_once DVMD_TM_PLUGIN_DIR_PATH . 'activate.php';
});

// Activation error.
if ($activateError = get_transient('dvmd-tm-activate-error')) {
  add_action('admin_notices', function() use ($activateError) {
    echo wp_kses($activateError, 'post');
    deactivate_plugins(plugin_basename(DVMD_TM_PLUGIN_FILE), true);
    if (isset($_GET['activate'])) unset($_GET['activate']); // phpcs:ignore
    delete_transient('dvmd-tm-activate-error');
  });
}

// Load text-domain.
add_action('init', function() {
  load_plugin_textdomain('dvmd-table-maker', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

// Load admin.
if (is_admin()) require_once 'admin/admin-class.php';   

// Load extensions.
require_once DVMD_TM_PLUGIN_DIR_PATH . 'extensions/load.php';
