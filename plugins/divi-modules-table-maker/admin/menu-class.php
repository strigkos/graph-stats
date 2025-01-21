<?php

if (! defined('ABSPATH')) exit;

/**
 * Plugin Menu.
 *
 * @since  3.1.2
 *
 */
final class DVMD_Table_Maker_Menu {


  /**
   * Class constructor.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public function __construct() {
    add_action('admin_init',            array(__CLASS__, 'load_menu_settings'));
    add_action('admin_menu',            array(__CLASS__, 'register_menu_page'));
    add_action('admin_enqueue_scripts', array(__CLASS__, 'load_admin_styles_and_scripts'));
  }


  /**
   * Returns menu page tabs.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  array
   */
  private static function get_menu_tabs() {
    return array(
      'admin'    => esc_html__('Admin', 'dvmd-table-maker'),
      'products' => esc_html__('Products', 'dvmd-table-maker'),
    );
  }


  /**
   * Loads the menu settings files.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public static function load_menu_settings() {
    foreach (array_keys(self::get_menu_tabs()) as $tab) {
      include_once "settings/{$tab}-settings-class.php";
    }
  }


  /**
   * Returns the active tab.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  string
   */
  private static function get_active_tab() {

    // Get default tab.
    $tabs = self::get_menu_tabs();
    $default = key($tabs);

    // Verify nonce.
    if (! isset($_GET['_wpnonce']) 
      || ! wp_verify_nonce(sanitize_text_field($_GET['_wpnonce']), 'dvmd-tm-tabs-nonce')) {
      return $default;
    }

    // Get active tab.
    $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default;
    return array_key_exists($tab, self::get_menu_tabs()) ? $tab : $default;
  }


  /**
   * Loads admin styles and scripts.
   *
   * @since   3.1.2
   * @access  public
   *
   * @param   string  $page  The current admin page.
   *
   * @return  void
   */
  public static function load_admin_styles_and_scripts($page) {

    // Bail.
    if (strpos($page, 'dvmd-table-maker') === false) return;

    // Color picker style.
    wp_enqueue_style('wp-color-picker');

    // Admin style.
    $url = DVMD_TM_PLUGIN_DIR_URL . 'admin/styles/admin-style.css';
    wp_enqueue_style('dvmd-tm-admin-style', $url, false, DVMD_TM_PLUGIN_VERSION);

    // Admin script.
    $tab = self::get_active_tab();
    $url = DVMD_TM_PLUGIN_DIR_URL . "admin/scripts/{$tab}-script-min.js";
    wp_enqueue_script("dvmd-tm-admin-{$tab}-script", $url, array('jquery', 'wp-color-picker'), DVMD_TM_PLUGIN_VERSION, true);

    // License script.
    if ('admin' === $tab && 'DM' === DVMD_TM_PURCHASE_TYPE) {
      $url = DVMD_TM_PLUGIN_DIR_URL . 'admin/scripts/admin-license-script-min.js';
      wp_enqueue_script('dvmd-tm-admin-license-script', $url, array('jquery'), DVMD_TM_PLUGIN_VERSION, true);
      wp_localize_script('dvmd-tm-admin-license-script', 'dvmd_tm_license_data', self::get_license_script_data());
    }

    // Subscription script.
    if ('admin' === $tab && 'ET' === DVMD_TM_PURCHASE_TYPE) {
      $url = DVMD_TM_PLUGIN_DIR_URL . 'admin/scripts/admin-subscription-script-min.js';
      wp_enqueue_script('dvmd-tm-admin-subscription-script', $url, array('jquery'), DVMD_TM_PLUGIN_VERSION, true);
      wp_localize_script('dvmd-tm-admin-subscription-script', 'dvmd_tm_subscription_data', self::get_subscription_script_data());
    }
  }


  /**
   * Returns data for the license script.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  array
   */
  private static function get_license_script_data() {

    // Get license key.
    $options = get_option('dvmd_tm_admin_options');
    $license_key = (isset($options['license_key'])) ? $options['license_key'] : '';

    // Get activation status.
    $options = get_option('dvmd_tm_activation_options');
    $license_status = (isset($options['license_status'])) ? $options['license_status'] : false;

    // Return.
    return array(
      'strings' => array(
        'activate'    => esc_html__('Activate', 'dvmd-table-maker'),
        'deactivate'  => esc_html__('Deactivate', 'dvmd-table-maker'),
        'activated'   => esc_html__('License active.', 'dvmd-table-maker'),
        'deactivated' => esc_html__('License not active.', 'dvmd-table-maker'),
        'validating'  => esc_html__('Validating license key...', 'dvmd-table-maker'),
      ),
      'api' => array(
        'nonce'       => wp_create_nonce('dvmd-tm-license-nonce'),
        'status'      => (bool) $license_status,
        'key'         => sanitize_text_field($license_key),
      ),
    );
  }


  /**
   * Returns data for the subscription script.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  array
   */
  private static function get_subscription_script_data() {

    // Get subscription status.
    $options = get_option('dvmd_tm_activation_options');
    $subscription_status = (isset($options['subscription_status'])) ? $options['subscription_status'] : false;

    // Return.
    return array(
      'strings' => array(
        'activated'   => esc_html__('Subscription active.', 'dvmd-table-maker'),
        'deactivated' => esc_html__('Subscription not active.', 'dvmd-table-maker'),
        'validating'  => esc_html__('Validating subscription...', 'dvmd-table-maker'),
      ),
      'api' => array(
        'nonce'       => wp_create_nonce('dvmd-tm-subscription-nonce'),
        'status'      => (bool) $subscription_status,
      ),
    );
  }


  /**
   * Registers the menu page.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public static function register_menu_page() {
    add_menu_page(
      esc_html(DVMD_TM_PLUGIN_TITLE),
      esc_html(DVMD_TM_PLUGIN_TITLE),
      'manage_options',
      'dvmd-table-maker',
      array(__CLASS__, 'render_menu_page_cb'),
      'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNTYgMjU2IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCAyNTYgMjU2IiB4bWw6c3BhY2U9InByZXNlcnZlIj48cGF0aCBkPSJNNjYuOSAxMDIuMUg1Mi4ydjY1LjJoMTQuN2MxMC41IDAgMTguNS0yLjkgMjMuOS04LjUgNS42LTUuOCA4LjQtMTMuOSA4LjQtMjQgMC0xMC4zLTIuNy0xOC4yLTguMi0yNC01LjYtNS45LTEzLjUtOC43LTI0LjEtOC43eiIvPjxwYXRoIGQ9Ik0xMjguMSAwQzU3LjMgMCAuMSA1Ny4zLjEgMTI4YzAgNzAuNiA1Ny4yIDEyOCAxMjggMTI4IDcwLjYgMCAxMjgtNTcuMiAxMjgtMTI4LS4yLTcwLjctNTcuNS0xMjgtMTI4LTEyOHpNMTA3IDE1Ny44Yy0zLjggNi42LTkuMSAxMS42LTE1LjcgMTQuOC03LjEgMy41LTE1LjIgNS4yLTI0LjEgNS4ySDM5di04N2gyNy45YzkuMSAwIDE3LjIgMS44IDI0LjIgNS4zIDYuNiAzLjMgMTEuOSA4LjQgMTUuNyAxNS4yIDMuNyA2LjYgNS41IDE0LjUgNS41IDIzLjQuMiA4LjgtMS42IDE2LjYtNS4zIDIzLjF6bTE0LTc3LjNjLTEuOC0yLjEtMi44LTQuNi0yLjgtNy4zIDAtMi44IDEtNS4zIDIuOS03LjIgMi0yIDQuMy0yLjkgNy4yLTIuOSAzIDAgNS40IDEgNy4zIDIuOSAxLjkgMS45IDIuOCA0LjIgMi44IDcuMiAwIDIuOS0uOSA1LjMtMi44IDcuNC0yIDItNC4zIDIuOS03LjMgMi45LTIuOCAwLTUuMy0xLTcuMy0zem04OS43IDk3LjNoLTEzLjV2LTU4LjNsLTI2LjcgNTguM0gxNjJsLTI3LTU4LjRWMTc4aC0xMy4yVjkwLjhoMTNsMzEuNSA2OC41IDMxLjQtNjguNWgxM3Y4N3ptLjUtOTcuMmMtMiAyLTQuMyAyLjktNy4zIDIuOS0yLjggMC01LjMtMS03LjItMi45LTEuOS0xLjktMi45LTQuNC0yLjktNy40IDAtMi44IDEtNS4zIDIuOS03LjIgMi0yIDQuMy0yLjkgNy4yLTIuOSAzIDAgNS40IDEgNy4zIDIuOSAxLjkgMS45IDIuOCA0LjIgMi44IDcuMiAwIDIuOS0xIDUuNC0yLjggNy40eiIvPjwvc3ZnPg==',
      99
    );
  }


  /**
   * Renders the menu page tabs.
   *
   * @since   3.1.0
   * @access  private
   *
   * @return  string
   */
  private static function render_menu_tabs() {

    // Tab.
    $all_tabs   = self::get_menu_tabs();
    $active_tab = self::get_active_tab();

    // Wrapper.
    echo '<h2 class="nav-tab-wrapper">';

    // Build tabs.
    foreach ($all_tabs as $tab_slug => $tab_name) {

      // Create url.
      $admin_url = admin_url(sprintf('admin.php?page=dvmd-table-maker&tab=%s', esc_attr($tab_slug)));
      $nonce_url = wp_nonce_url($admin_url, 'dvmd-tm-tabs-nonce');

      // Create link.
      echo sprintf('<a class="nav-tab%s" href="%s">%s</a>', 
        /* 01 */ esc_attr($tab_slug == $active_tab ? ' nav-tab-active' : ''), 
        /* 02 */ esc_url($nonce_url),
        /* 03 */ esc_html($tab_name)
      );
    }

    echo '</h2>';
  }


  /**
   * Callback function to render the menu page.
   *
   * @since   3.1.2
   * @access  public
   *
   * @return  string
   */
  public static function render_menu_page_cb() {
    
    // Bail.
    if (! current_user_can('manage_options')) return;

    // Properties.
    $logo = DVMD_TM_PLUGIN_DIR_URL . 'admin/assets/logo.svg';
    $slug = self::get_active_tab();

    // Output.
    ?>
    <div id="dvmd_table_maker_wrap" class="wrap dvmd_tm_<?php echo esc_attr($slug); ?>_wrap">
      <?php include(DVMD_TM_PLUGIN_DIR_PATH . 'admin/partials/header-partial.php'); ?>
      <div class="body">
        <?php self::render_menu_tabs(); ?>
        <?php settings_errors(); ?>
        <div class="content">
          <?php if ('products' === $slug): ?>
            <?php include(DVMD_TM_PLUGIN_DIR_PATH . 'admin/partials/feed-partial.php'); ?>
          <?php else: ?>
            <?php include(DVMD_TM_PLUGIN_DIR_PATH . 'admin/partials/settings-partial.php'); ?>
          <?php endif; ?>
        </div>
      </div>
      <?php include(DVMD_TM_PLUGIN_DIR_PATH . 'admin/partials/footer-partial.php'); ?>
    </div>
    <?php
  }
}

// Init.
new DVMD_Table_Maker_Menu;
