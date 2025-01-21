<?php

if (! defined('ABSPATH')) exit;

/**
 * Plugin Admin.
 *
 * @since  3.1.2
 *
 */
final class DVMD_Table_Maker_Admin {


  /**
   * Class constructor.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public function __construct() {
    add_action('init',       array(__CLASS__, 'init_plugin'));
    add_action('init',       array(__CLASS__, 'init_plugin_version'));
    add_action('admin_menu', array(__CLASS__, 'check_php_version'));
    add_action('admin_menu', array(__CLASS__, 'check_divi_builder'));
    // add_action('admin_menu', array(__CLASS__, 'check_divi_version'));  // @to-do: Enable for Divi 5.
    add_filter('plugin_action_links_' . plugin_basename(DVMD_TM_PLUGIN_FILE), array(__CLASS__, 'add_settings_link'));
  }


  /**
   * Initializes the plugin admin.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public static function init_plugin() {

    // Constants.
    define('DVMD_TM_BUILDER_TYPE',  self::get_builder_type());
    define('DVMD_TM_PURCHASE_TYPE', self::get_purchase_type());

    // Init plugin type.
    if ('DM' === DVMD_TM_PURCHASE_TYPE) self::init_divi_modules_plugin();
    if ('ET' === DVMD_TM_PURCHASE_TYPE) self::init_divi_marketplace_plugin();
  }


  /**
   * Initializes a Divi-Modules plugin.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  void
   */
  private static function init_divi_modules_plugin() {

    // Constants.
    define('DVMD_TM_PRODUCT_ID',   '4877');
    define('DVMD_TM_STORE_URL',    'https://divi-modules.com');
    define('DVMD_TM_PURCHASE_URL', 'https://divi-modules.com/products/table-maker');
    define('DVMD_TM_DOCS_URL',     'https://divi-modules.com/docs/table-maker');
    define('DVMD_TM_SUPPORT_URL',  'https://divi-modules.com/support/contact');
    define('DVMD_TM_PRODUCT_FEED', '1');

    // Classes.
    include_once 'updater-class.php';
    require_once 'license-class.php';
    require_once 'settings-class.php';
    require_once 'menu-class.php';
    require_once 'deactivator-class.php';
  }


  /**
   * Initializes a Divi Marketplace plugin.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  void
   */
  private static function init_divi_marketplace_plugin() {

    // Constants.
    define('DVMD_TM_PRODUCT_ID',   '550');
    define('DVMD_TM_STORE_URL',    'https://www.elegantthemes.com/marketplace/author/divi-modules/');
    define('DVMD_TM_PURCHASE_URL', 'https://www.elegantthemes.com/marketplace/table-maker');
    define('DVMD_TM_DOCS_URL',     'https://divi-modules.com/docs/table-maker');
    define('DVMD_TM_SUPPORT_URL',  'https://www.elegantthemes.com/marketplace/table-maker/support/tickets/1');
    define('DVMD_TM_PRODUCT_FEED', '2');

    // Classes.
    require_once 'subscription-class.php';
    require_once 'settings-class.php';
    require_once 'menu-class.php';
    require_once 'deactivator-class.php';
  }


  /**
   * Inits the plugin version and migrates settings.
   *
   * @since   3.1.2
   * @access  public
   *
   * @return  void
   */
  public static function init_plugin_version() {
    
    // Bail.
    $plugin_version = get_option('dvmd_tm_plugin_version') ?: '';
    if (version_compare(DVMD_TM_PLUGIN_VERSION, $plugin_version, '==')) return;

    // Admin options.
    $admin_options = get_option('dvmd_tm_admin_options') ?: [];

    // Activation options.
    $activation_options = get_option('dvmd_tm_activation_options') ?: array(
      'license_status'       => false,
      'license_checked'      => 0,
      'subscription_status'  => false,
      'subscription_checked' => 0,
    );

    // License key. (Pre: 3.1.0)
    if ($license_key = get_option('dvmd_table_maker_license_key')) {
      $admin_options['license_key'] = sanitize_text_field($license_key);
    }

    // License status. (Pre: 3.1.0)
    if ($license_status = get_option('dvmd_table_maker_license_status')) {
      $activation_options['license_status'] = (bool) $license_status;
    }

    // Update options.
    update_option('dvmd_tm_plugin_version', DVMD_TM_PLUGIN_VERSION);
    update_option('dvmd_tm_admin_options', $admin_options);
    update_option('dvmd_tm_activation_options', $activation_options);

    // Clean up. (Pre: 3.1.0)
    delete_option('dvmd_table_maker_updated');
    delete_option('dvmd_table_maker_license_key');
    delete_option('dvmd_table_maker_license_status');
  }


  /**
   * Checks PHP version.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public static function check_php_version() {

    // Check PHP.
    if (version_compare(PHP_VERSION, '5.6', '>=')) return;

    // Notice.
    add_action('admin_notices', function() {
      $notice = sprintf(__('%s requires PHP version 5.6 or greater. Your current PHP version is %s.', 'dvmd-table-maker'),
        /* 01 */ sprintf('<strong>Divi-Modules – %s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE)),
        /* 02 */ esc_html(PHP_VERSION)
      );
      echo sprintf('<div class="notice error"><p>%s</p></div>', wp_kses_post($notice));
    });
  }


  /**
   * Checks for Divi Builder.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public static function check_divi_builder() {

    // Check Divi Builder.
    if (DVMD_TM_BUILDER_TYPE !== false) return;

    // Notice.
    add_action('admin_notices', function() {
      $notice = sprintf(__('%s requires the %s, %s, or %s to be installed and activated.', 'dvmd-table-maker'),
        /* 01 */  sprintf('<strong>Divi-Modules – %s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE)),
        /* 02 */ '<a href="https://www.elegantthemes.com/gallery/divi/" target="_blank">Divi Theme</a>',
        /* 03 */ '<a href="https://www.elegantthemes.com/gallery/extra/" target="_blank">Extra Theme</a>',
        /* 04 */ '<a href="https://www.elegantthemes.com/plugins/divi-builder/" target="_blank">Divi Builder Plugin</a>'
      );
      echo sprintf('<div class="notice error"><p>%s</p></div>', wp_kses_post($notice));
    });
  }


  /**
   * Checks Divi version.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public static function check_divi_version() {

    // Bail.
    if (DVMD_TM_BUILDER_TYPE === false) return;
    if (! defined('ET_BUILDER_VERSION')) return;

    // Check Divi version.
    if (version_compare(ET_BUILDER_VERSION, '5.0', '>=')) return;

    // Notice.
    add_action('admin_notices', function() {
      $notice = sprintf(__('This version of %s includes support for %s (and above). Please update to the latest version if Divi to enjoy the full %s experience.', 'dvmd-table-maker'),
      /* 01 */ sprintf('<strong>Divi-Modules – %s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE)),
      /* 02 */ sprintf('<strong>%s</strong>', esc_html__('Divi 5.0', 'dvmd-table-maker')),
      /* 03 */ sprintf('<strong>%s</strong>', esc_html__('Divi Builder', 'dvmd-table-maker'))
      );
      echo sprintf('<div class="notice notice-warning"><p>%s</p></div>', wp_kses_post($notice));
    });
  }


  /**
   * Gets which Divi Builder type is activated. 
   * ie. Divi Theme, Extra Theme, Divi Plugin, or None.
   *
   * @since   3.1.2
   * @access  private
   *
   * @return  string|false
   */
  private static function get_builder_type() {
    if (! defined('ET_BUILDER_VERSION')) return false;
    if (function_exists('et_is_builder_plugin_active') && et_is_builder_plugin_active()) return 'plugin';
    if (defined('EXTRA_LAYOUT_POST_TYPE')) return 'extra';
    return 'divi';
  }


  /**
   * Gets where the plugin was purchased from.
   * ie. Divi-Modules or Divi-Marketplace.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  boolean
   */
  private static function get_purchase_type() {
    if (! function_exists('get_plugin_data')) require_once ABSPATH . 'wp-admin/includes/plugin.php';
    $data = get_plugin_data(DVMD_TM_PLUGIN_FILE);
    $uri = (isset($data['UpdateURI'])) ? $data['UpdateURI'] : '';
    return (strpos($uri, 'elegantthemes') === false) ? 'DM' : 'ET';
  }


  /**
   * Adds a settings link to the admin Plugins item.
   *
   * @since   3.1.0
   * @access  public
   *
   * @param   array  $links  The plugin links.
   *
   * @return  array
   */
  public static function add_settings_link($links) {
    $links[] = sprintf('<a href="%s">%s</a>', 
      /* 01 */ admin_url('admin.php?page=dvmd-table-maker'),
      /* 02 */ esc_html__('Settings', 'dvmd-table-maker')
    );
    return $links;
  }
}

// Init.
new DVMD_Table_Maker_Admin;
