<?php

if (! defined('ABSPATH')) exit;

/**
 * Plugin Subscription.
 *
 * @since  3.1.2
 *
 */
final class DVMD_Table_Maker_Subscription {


  /**
   * Class constructor.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public function __construct() {

    // Hooks.
    add_action('admin_init',                                array(__CLASS__, 'check_subscription_status'), 11);
    add_action('wp_ajax_dvmd_tm_check_subscription',        array(__CLASS__, 'check_subscription'));
    add_action('wp_ajax_nopriv_dvmd_tm_check_subscription', array(__CLASS__, 'check_subscription'));
  }


  /**
   * Checks the subscription status.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public static function check_subscription_status() {

    // Reset.
    // delete_option('dvmd_tm_admin_options');
    // delete_option('dvmd_tm_activation_options');

    // Get optons.
    $options = get_option('dvmd_tm_activation_options');
    $subscription_status  = (isset($options['subscription_status']))  ? $options['subscription_status']  : false;
    $subscription_checked = (isset($options['subscription_checked'])) ? $options['subscription_checked'] : 0;

    // Subscription not active.
    if (! $subscription_status) {
      add_action('admin_notices', function() {
        echo sprintf('<div class="notice notice-warning"><p>%s</p></div>', wp_kses_post(self::get_notice('init_error')));
      });
      return;
    }

    // Subscription is active but requires checking.
    if ((time() - $subscription_checked) > (7 * DAY_IN_SECONDS)) {
      $data = self::_check_subscription();
      self::set_subscription_status($data['active']);
      if (! $data['active']) {
        add_action('admin_notices', function() {
          echo sprintf('<div class="notice notice-warning"><p>%s</p></div>', wp_kses_post(self::get_notice('init_error')));
        });
      }
    }
  }


  /**
   * Checks the subscription over ajax.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  string
   */
  public static function check_subscription() {

    // Check referer.
    check_ajax_referer('dvmd-tm-subscription-nonce', 'security');

    // Check subscription.
    $data = self::_check_subscription();

    // Set subscription status.
    self::set_subscription_status($data['active']);

    // Return.
    die(wp_json_encode($data));
  }


  /**
   * Initiates an API request to check the subscription.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  array
   */
  private static function _check_subscription() {

    // Error.
    if (false === DVMD_TM_BUILDER_TYPE || ! function_exists('et_core_get_et_account')) {
      $data['notice'] = self::get_notice('divi_builder');
      $data['active'] = false;
      return $data;
    }

    // Get account details.
    $account = et_core_get_et_account();
    $account_username = (isset($account['et_username'])) ? $account['et_username'] : '';
    $account_api_key  = (isset($account['et_api_key']))  ? $account['et_api_key'] : '';
    $account_status   = (isset($account['status']))      ? $account['status'] : '';
    $api_key_status   = get_site_option('et_account_api_key_status');

    // Get account status.
    $account_active = ($account_username && $account_api_key && 'active' === $account_status && ! $api_key_status);

    // Error.
    if (! $account_active) {
      $data['notice'] = self::get_notice('account_error');
      $data['active'] = false;
      return $data;
    }

    // Free. (SIMPLE HEADING)
    if (371 == DVMD_TM_PRODUCT_ID) {
      $data['notice'] = self::get_notice('subscription_active');
      $data['active'] = true;
      return $data;
    }

    // Make api request.
    $response = self::make_api_request($account_api_key);

    // Error.
    if (is_wp_error($response)) {
      $data['notice'] = self::get_notice('api_error', $response->get_error_message());
      $data['active'] = false;
      return $data;
    }

    // Error.
    if (200 !== wp_remote_retrieve_response_code($response)) {
      $data['notice'] = self::get_notice('api_error');
      $data['active'] = false;
      return $data;
    }

    // Get response body.
    $body = json_decode(wp_remote_retrieve_body($response));

    // Success.
    if (isset($body->success) && true === $body->success && isset($body->code)) {

      // Active.
      if ('subscription_active' === $body->code) {
        $data['notice'] = self::get_notice($body->code);
        $data['active'] = true;
        return $data;
      }

      // Expired.
      if ('subscription_expired' === $body->code) {
        $data['notice'] = self::get_notice($body->code);
        $data['active'] = false;
        return $data;
      }
    }

    // Error.
    if (isset($body->error) && true === $body->error && isset($body->code)) {

      // Billing eror.
      if ('no_billing_records' === $body->code) {
        $data['notice'] = self::get_notice($body->code);
        $data['active'] = false;
        return $data;
      }

      // API key error.
      if ('api_key_not_found' === $body->code) {
        $data['notice'] = self::get_notice($body->code);
        $data['active'] = false;
        return $data;
      }
    }

    // Error.
    $data['notice'] = self::get_notice('unknown_error');
    $data['active'] = false;
    return $data;
  }


  /**
   * Makes an API request.
   *
   * @since   3.1.2
   * @access  private
   * 
   * @param   string  $api_key  The API key.
   *
   * @return  array|WP_Error
   */
  private static function make_api_request($api_key) {

    // End-point.
    $url = sprintf('https://www.elegantthemes.com/marketplace/index.php/wp-json/api/v1/check_subscription/product_id/%s/api_key/%s',
      /* 01 */ DVMD_TM_PRODUCT_ID,
      /* 02 */ $api_key
    );

    // API request.
    return wp_remote_get(esc_url_raw($url), array(
      'timeout'   => 30,
      'sslverify' => false,
    ));
  }


  /**
   * Sets the subscription status.
   *
   * @since   3.1.0
   * @access  private
   * 
   * @param   boolean  $status  The subscription active status.
   *
   * @return  void
   */
  private static function set_subscription_status($status) {
    update_option('dvmd_tm_activation_options', array(
      'subscription_status'  => (bool) $status,
      'subscription_checked' => (int)  time(),
    ));
  }


  /**
   * Gets a notice.
   *
   * @since   3.1.2
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
        return sprintf(__('The %s subscription requires checking. %s', 'dvmd-table-maker'),
          /* 01 */ sprintf('<strong>Divi Marketplace – %s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE)),
          /* 02 */ self::get_cta('check_now')
        );

      // Success notice.
      case 'subscription_active' :
        return sprintf(__('%s The Elegant Themes API reports that the Divi Marketplace subscription for this product is active.', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Success', 'dvmd-table-maker'))
        );

      // Divi Builder notice.
      case 'divi_builder' :
        return sprintf(__('%s This product requires the %s, %s, or %s to be installed and activated.', 'dvmd-table-maker'),
          /* 01 */  sprintf('<strong>%s:</strong>', esc_html__('Error 01', 'dvmd-table-maker')),
          /* 02 */ '<a href="https://www.elegantthemes.com/gallery/divi/" target="_blank">Divi Theme</a>',
          /* 03 */ '<a href="https://www.elegantthemes.com/gallery/extra/" target="_blank">Extra Theme</a>',
          /* 04 */ '<a href="https://www.elegantthemes.com/plugins/divi-builder/" target="_blank">Divi Builder Plugin</a>'
        );

      // Account notice.
      case 'account_error' :
        return sprintf(__('%s The Elegant Themes API reports that the current user’s account details are incorrect. Please check that the %s and %s entered in %s are correct. %s', 'dvmd-table-maker'),
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 02', 'dvmd-table-maker')),
          /* 02 */ sprintf('<em>%s</em>', esc_html__('Username', 'dvmd-table-maker')),
          /* 03 */ sprintf('<em>%s</em>', esc_html__('API Key', 'dvmd-table-maker')),
          /* 04 */ sprintf('<em>%s</em>', esc_html__('Divi > Theme Options > Updates', 'dvmd-table-maker')),
          /* 05 */ self::get_cta('theme_options')
        );

      // Error notices.
      case 'api_error' :
        if ($data && is_string($data)) {
          return sprintf(__('%s %s – please try again later. %s', 'dvmd-table-maker'), 
            /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 03', 'dvmd-table-maker')),
            /* 02 */ esc_html($data),
            /* 03 */ self::get_cta('contact_us')
          );
        } else {
          return sprintf(__('%s An unkown error occurred – please try again later. %s', 'dvmd-table-maker'), 
            /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 04', 'dvmd-table-maker')),
            /* 02 */ self::get_cta('contact_us')
          );
        }

      // Subscription notices.
      case 'subscription_expired' :
        return sprintf(__('%s The Elegant Themes API reports that the Divi Marketplace subscription for this product has expired. Please renew it from the %s. %s %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 05', 'dvmd-table-maker')),
          /* 02 */ sprintf('<em>%s</em>', esc_html__('Divi Marketplace > Customer Dashboard', 'dvmd-table-maker')),
          /* 03 */ self::get_cta('renew_now'),
          /* 04 */ sprintf('<span class="dvmd_warning">%s</span>', esc_html__('If you have recently renewed your subscription and are still seeing this message, please allow 48 hours for the Elegant Themes API to update before contacting support. Note: This will not affect the functioning of your product.', 'dvmd-table-maker'))
        );

      case 'no_billing_records' :
        return sprintf(__('%s The Elegant Themes API reports that there is no record of the current user purchasing this product from the Divi Marketplace. Please check that the %s and %s entered in %s are correct. %s %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 06', 'dvmd-table-maker')),
          /* 02 */ sprintf('<em>%s</em>', esc_html__('Username', 'dvmd-table-maker')),
          /* 03 */ sprintf('<em>%s</em>', esc_html__('API Key', 'dvmd-table-maker')),
          /* 04 */ sprintf('<em>%s</em>', esc_html__('Divi > Theme Options > Updates', 'dvmd-table-maker')),
          /* 05 */ self::get_cta('purchase_now'),
          /* 06 */ sprintf('<span class="dvmd_warning">%s</span>', esc_html__('If you have recently purchased this product and are still seeing this message, please allow 48 hours for the Elegant Themes API to update before contacting support. Note: This will not affect the functioning of your product.', 'dvmd-table-maker'))
        );

      case 'api_key_not_found' :
        return sprintf(__('%1$s The Elegant Themes API reports that the current user’s %3$s is incorrect. Please check that the %2$s and %3$s entered in %4$s are correct. %5$s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 07', 'dvmd-table-maker')),
          /* 02 */ sprintf('<em>%s</em>', esc_html__('Username', 'dvmd-table-maker')),
          /* 03 */ sprintf('<em>%s</em>', esc_html__('API Key', 'dvmd-table-maker')),
          /* 04 */ sprintf('<em>%s</em>', esc_html__('Divi > Theme Options > Updates', 'dvmd-table-maker')),
          /* 05 */ self::get_cta('theme_options')
        );

      // Unkown notice.
      case 'unknown_error' :
        return sprintf(__('%s An unknown error occurred – please try again later. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 08', 'dvmd-table-maker')),
          /* 02 */ self::get_cta('contact_us')
        );

      // Default notice.
      default :
        return sprintf(__('%s An unknown error occurred – please try again later. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s:</strong>', esc_html__('Error 09', 'dvmd-table-maker')),
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

      case 'check_now' :
        $admin_url = admin_url('admin.php?page=dvmd-table-maker&tab=admin');
        $nonce_url = wp_nonce_url($admin_url, 'dvmd-tm-tabs-nonce');
        return sprintf('<a href="%s">%s</a>', 
          /* 01 */ esc_url($nonce_url),
          /* 02 */ esc_html__('Check Now', 'dvmd-table-maker')
        );

      case 'theme_options' :
        return sprintf('<a href="%s">%s</a>', 
          /* 01 */ ('extra' === DVMD_TM_BUILDER_TYPE) ? admin_url('admin.php?page=et_extra_options') : admin_url('admin.php?page=et_divi_options'),
          /* 02 */ esc_html__('Theme Options', 'dvmd-table-maker')
        );

      case 'purchase_now' :
        return sprintf('<a href="%s" target="_blank">%s</a>', 
          /* 01 */ esc_url(DVMD_TM_PURCHASE_URL),
          /* 02 */ esc_html__('Purchase Now', 'dvmd-table-maker')
        );

      case 'renew_now' :
        return sprintf('<a href="%s" target="_blank">%s</a>', 
          /* 01 */ 'https://www.elegantthemes.com/marketplace/customer-dashboard/',
          /* 02 */ esc_html__('Renew Now', 'dvmd-table-maker')
        );
    }
  }
}

// Init.
new DVMD_Table_Maker_Subscription;
