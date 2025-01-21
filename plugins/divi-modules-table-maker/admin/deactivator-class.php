<?php

if (! defined('ABSPATH')) exit;

/**
 * Plugin Deactivator.
 *
 * @since   3.1.0
 * @access  public
 */
final class DVMD_Table_Maker_Deactivator {


  /**
   * Class constructor.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public function __construct() {
    register_deactivation_hook(DVMD_TM_PLUGIN_FILE, array(__CLASS__, 'deactivate'));
  }


  /**
   * Fires when the plugin is deactivated.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public static function deactivate() {

    // Bail.
    if (! current_user_can('activate_plugins')) return;
    $plugin = (isset($_REQUEST['plugin'])) ? sanitize_text_field($_REQUEST['plugin']) : '';
    check_admin_referer("deactivate-plugin_{$plugin}");

    // Delete options.
    delete_option('dvmd_tm_activation_options');

    // Bail.
    if ('DM' !== DVMD_TM_PURCHASE_TYPE) return;

    // License key.
    $options = get_option('dvmd_tm_admin_options');
    $license = (isset($options['license_key'])) ? $options['license_key'] : '';

    // Deactivate.
    $response = wp_remote_post(esc_url_raw(DVMD_TM_STORE_URL), array(
      'timeout'      =>  30,
      'sslverify'    =>  false,
      'body'         =>  array(
        'item_id'    =>  esc_attr(DVMD_TM_PRODUCT_ID),
        'edd_action' => 'deactivate_license',
        'license'    =>  sanitize_text_field($license),
        'url'        =>  home_url(),
      )
    ));
  }
}

// Init.
new DVMD_Table_Maker_Deactivator;
