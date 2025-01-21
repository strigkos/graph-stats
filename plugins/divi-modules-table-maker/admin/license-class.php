<?php

if (! defined('ABSPATH')) exit;

/**
 * Plugin License.
 *
 * @since  3.1.0
 *
 */
final class DVMD_Table_Maker_License {


  /**
   * Class constructor.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public function __construct() {

    // Init.
    self::init_updater();

    // Hooks.
    add_action('admin_init',                            array(__CLASS__, 'check_license_status'), 11);
    add_action('wp_ajax_dvmd_tm_update_license',        array(__CLASS__, 'update_license'));
    add_action('wp_ajax_nopriv_dvmd_tm_update_license', array(__CLASS__, 'update_license'));
  }


  /**
   * Initialises EDD Software License Updater.
   *
   * Initialize the updater. Hooked into `init` to work with the
   * wp_version_check cron job, which allows auto-updates.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  void
   */
  private static function init_updater() {
    
    // Auto updates.
    $doing_cron = defined('DOING_CRON') && DOING_CRON;
    if (! current_user_can('manage_options') && ! $doing_cron) return;

    // Init updater.
    $updater = 'DVMD_Table_Maker_Updater';
    if (class_exists($updater)) {
      $updater = new $updater(DVMD_TM_STORE_URL, DVMD_TM_PLUGIN_FILE,
        array(
          'author'  => 'Divi-Modules',
          'item_id' =>  DVMD_TM_PRODUCT_ID,
          'version' =>  DVMD_TM_PLUGIN_VERSION,
          'license' =>  self::get_license_key(),
          'beta'    =>  false,
      ));
    }
  }


  /**
   * Checks the license status.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public static function check_license_status() {

    // Reset.
    // delete_option('dvmd_tm_admin_options');
    // delete_option('dvmd_tm_activation_options');

    // Get optons.
    $options = get_option('dvmd_tm_activation_options');
    $license_status  = (isset($options['license_status']))  ? $options['license_status']  : false;
    $license_checked = (isset($options['license_checked'])) ? $options['license_checked'] : 0;

    // License not active.
    if (! $license_status) {
      add_action('admin_notices', function() {
        echo sprintf('<div class="notice notice-warning"><p>%s</p></div>', wp_kses_post(self::get_notice('init_error')));
      });
      return;
    }

    // License is active but requires checking.
    if ((time() - $license_checked) > (7 * DAY_IN_SECONDS)) {
      $data = self::_activate_license();
      self::set_license_status($data['active']);
      if (! $data['active']) {
        add_action('admin_notices', function() {
          echo sprintf('<div class="notice notice-warning"><p>%s</p></div>', wp_kses_post(self::get_notice('init_error')));
        });
      }
    }
  }


  /**
   * Activates or deactivates the license over ajax.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  string
   */
  public static function update_license() {

    // Check referrer.
    check_ajax_referer('dvmd-tm-license-nonce', 'security');

    // Get license key.
    $license  = (isset($_POST['license'])) ? sanitize_text_field($_POST['license']) : '';
    $activate = (isset($_POST['activate'])) ? sanitize_text_field($_POST['activate']) : 'false';
    $activate = filter_var($activate, FILTER_VALIDATE_BOOLEAN);

    // Remove the sanitize callback function.
    remove_all_filters('sanitize_option_dvmd_tm_admin_options');

    // Update license key.
    self::update_license_key($license);

    // Update license.
    if ($activate) {
      $data = self::_activate_license();
    } else {
      $data = self::_deactivate_license();
    }

    // Set license status.
    self::set_license_status($data['active']);

    // Return.
    die(wp_json_encode($data));
  }


  /**
   * Initiates an API request to activate the license.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  array
   */
  private static function _activate_license() {

    // Make api request.
    $response = self::make_api_request('activate_license');

    // Error.
    if (is_wp_error($response) ) {
      $data['notice'] = self::get_notice('activation_error', $response->get_error_message());
      $data['active'] = false;
      return $data;
    }

    // Error.
    if (200 !== wp_remote_retrieve_response_code($response)) {
      $data['notice'] = self::get_notice('activation_error');
      $data['active'] = false;
      return $data;
    }

    // Get response body.
    $body = json_decode(wp_remote_retrieve_body($response));

    // Success.
    if (isset($body->success) && true === $body->success && isset($body->license) && 'valid' === $body->license) {
      $data['notice'] = self::get_notice('activated');
      $data['active'] = true;
      return $data;
    }

    // Error.
    if (isset($body->error)) {
      $data['notice'] = self::get_notice($body->error, $body);
      $data['active'] = false;
      return $data;
    }

    // Error.
    $data['notice'] = self::get_notice('unknown_error');
    $data['active'] = false;
    return $data;
  }


  /**
   * Initiates an API request to deactivate the license.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  array
   */
  private static function _deactivate_license() {

    // Make api request.
    $response = self::make_api_request('deactivate_license');

    // Error.
    if (is_wp_error($response)) {
      $data['notice'] = self::get_notice('deactivation_error', $response->get_error_message());
      $data['active'] = true;
      return $data;
    }

    // Error.
    if (200 !== wp_remote_retrieve_response_code($response)) {
      $data['notice'] = self::get_notice('deactivation_error');
      $data['active'] = true;
      return $data;
    }

    // Get response body.
    $body = json_decode(wp_remote_retrieve_body($response));

    // Success.
    if (isset($body->success) && true === $body->success && isset($body->license) && 'deactivated' === $body->license ) {
      $data['notice'] = self::get_notice('deactivated');
      $data['active'] = false;
      return $data;
    }

    // When deactivate license fails it returns no meaningful information.
    // We therefore use activate license to gather information about the error.
    $data = self::_activate_license();
    return $data;
  }


  /**
   * Makes an API request.
   *
   * @since   3.1.0
   * @access  private
   *
   * @param   string  $action   The EDD action.
   * @param   string  $license  The license key.
   *
   * @return  array|WP_Error
   */
  private static function make_api_request($action, $license = '') {

    // License key.
    $license = ($license) ? $license : self::get_license_key();

    // API request.
    return wp_remote_post(esc_url_raw(DVMD_TM_STORE_URL), array(
      'timeout'      =>  30,
      'sslverify'    =>  false,
      'body'         =>  array(
        'item_id'    =>  esc_attr(DVMD_TM_PRODUCT_ID),
        'edd_action' =>  sanitize_text_field($action),
        'license'    =>  sanitize_text_field($license),
        'url'        =>  home_url(),
      )
    ));
  }


  /**
   * Updates the license key in the database.
   *
   * @since   3.1.0
   * @access  private
   * 
   * @param   string  $license_key  The license key.
   *
   * @return  void
   */
  private static function update_license_key($license_key) {  
    $options = get_option('dvmd_tm_admin_options') ?: [];
    $options['license_key'] = sanitize_text_field($license_key);
    update_option('dvmd_tm_admin_options', $options);
  }


  /**
   * Gets the license key from the database.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  string
   */
  private static function get_license_key() {
    $options = get_option('dvmd_tm_admin_options');
    return (isset($options['license_key'])) ? sanitize_text_field($options['license_key']) : '';
  }


  /**
   * Sets the license status.
   *
   * @since   3.1.0
   * @access  private
   * 
   * @param   boolean  $status  The license active status.
   *
   * @return  void
   */
  private static function set_license_status($status) {
    update_option('dvmd_tm_activation_options', array(
      'license_status'  => (bool) $status,
      'license_checked' => (int)  time(),
    ));
  }


  /**
   * Gets a notice.
   *
   * @since   3.1.0
   * @access  private
   *
   * @param   string  $type  The notice type.
   * @param   string  $data  The notice data.
   *
   * @return  string
   */
  private static function get_notice($type, $data = '') {

    // Notices.
    switch($type) {

      // Init notice.
      case 'init_error' :
        return sprintf(__('The %s license is not active. %s', 'dvmd-table-maker'),
          /* 01 */ sprintf('<strong>Divi-Modules – %s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE)),
          /* 02 */ self::get_cta('activate_now')
        );

      // Success notices.
      case 'activated' :
        return sprintf(__('%s License active.', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Success', 'dvmd-table-maker'))
        );
      case 'deactivated' :
        return sprintf(__('%s License deactivated.', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Success', 'dvmd-table-maker'))
        );

      // Activation notice.
      case 'activation_error' :
        if ($data && is_string($data)) {
          return sprintf(__('%s %s – please try again later. %s', 'dvmd-table-maker'), 
            /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 01', 'dvmd-table-maker')),
            /* 02 */ esc_html($data),
            /* 03 */ self::get_cta('contact_us')
          );
        } else {
          return sprintf(__('%s An activation error occurred – please try again later. %s', 'dvmd-table-maker'), 
            /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 02', 'dvmd-table-maker')),
            /* 02 */ self::get_cta('contact_us')
          );
        }

      // Deactivation notice.
      case 'deactivation_error' :
        if ($data && is_string($data)) {
          return sprintf(__('%s %s – please try again later. %s', 'dvmd-table-maker'), 
            /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 03', 'dvmd-table-maker')),
            /* 02 */ esc_html($data),
            /* 03 */ self::get_cta('contact_us')
          );
        } else {
          return sprintf(__('%s A deactivation error occurred – please try again later. %s', 'dvmd-table-maker'), 
            /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 04', 'dvmd-table-maker')),
            /* 02 */ self::get_cta('contact_us')
          );
        }

      // Status notices.
      case 'invalid' :
        return sprintf(__('%s The license key is not valid. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 05', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('purchase_now')
        );
      case 'missing' :
        return sprintf(__('%s The license key is not valid. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 06', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('purchase_now')
        );
      case 'invalid_item_id' :
        return sprintf(__('%s The license key is not valid for this product. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 07', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('purchase_now')
        );
      case 'key_mismatch' :
        return sprintf(__('%s The license key is not valid for this product. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 08', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('purchase_now')
        );
      case 'item_name_mismatch' :
        return sprintf(__('%s The license key is not valid for this product. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 09', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('purchase_now')
        );
      case 'site_inactive' :
        return sprintf(__('%s The license key is not active for this URL. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 10', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('purchase_now')
        );
      case 'disabled' :
        return sprintf(__('%s The license key is valid but has been disabled. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 11', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('contact_us')
        );
      case 'no_activations_left':
        return sprintf(__('%s The license key has reached its activation limit. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 12', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('purchase_now')
        );
      case 'expired' :
        if (isset($data->expires)) {
          return sprintf(__('%s The license key expired on %s. %s', 'dvmd-table-maker'), 
            /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 13', 'dvmd-table-maker')),
            /* 02 */ date_i18n(get_option('date_format'), strtotime($data->expires, current_time('timestamp'))),
            /* 03 */ self::get_cta('renew_now')
          );
        } else {
          return sprintf(__('%s The license key has expired. %s', 'dvmd-table-maker'), 
            /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 14', 'dvmd-table-maker')),
            /* 02 */ self::get_cta('renew_now')
          );
        }
      
      // Unkown notice.
      case 'unknown_error' :
        return sprintf(__('%s An unknown error occurred – please try again later. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 15', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('contact_us')
        );

      // Default notice.
      default :
        return sprintf(__('%s An unknown error occurred – please try again later. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 16', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('contact_us')
        );
    }
  }


  /**
   * Gets a call-to-action.
   *
   * @since   3.1.0
   * @access  private
   *
   * @param   string  $type  The action type.
   *
   * @return  string
   */
  private static function get_cta($type) {

    // CTAs.
    switch($type) {

      case 'contact_us' :
        return sprintf('<a href="%s" target="_blank">%s</a>', 
          /* 01 */ esc_url(DVMD_TM_SUPPORT_URL),
          /* 02 */ esc_html__('Contact Us', 'dvmd-table-maker')
        );

      case 'activate_now' :
        $admin_url = admin_url('admin.php?page=dvmd-table-maker&tab=admin');
        $nonce_url = wp_nonce_url($admin_url, 'dvmd-tm-tabs-nonce');
        return sprintf('<a href="%s">%s</a>', 
          /* 01 */ esc_url($nonce_url),
          /* 02 */ esc_html__('Activate Now', 'dvmd-table-maker')
        );

      case 'purchase_now' :
        return sprintf('<a href="%s" target="_blank">%s</a>', 
          /* 01 */ esc_url(DVMD_TM_PURCHASE_URL),
          /* 02 */ esc_html__('Purchase Now', 'dvmd-table-maker')
        );

      case 'renew_now' :
        return sprintf('<a href="%s" target="_blank">%s</a>', 
          /* 01 */ esc_url_raw(sprintf('%s/shop/checkout/?edd_license_key=%s', DVMD_TM_STORE_URL, self::get_license_key())),
          /* 02 */ esc_html__('Renew Now', 'dvmd-table-maker')
        );
    }
  }
}

// Init.
new DVMD_Table_Maker_License;
