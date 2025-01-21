<?php

if (! defined('ABSPATH')) exit;

/**
 * Plugin Admin Settings.
 *
 * @since  3.1.0
 *
 */
final class DVMD_Table_Maker_Admin_Settings extends DVMD_Table_Maker_Settings {


  /**
   * Class constructor.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public function __construct() {

    // Properties.
    $this->settings_slug = 'admin';

    // Parent.
    parent::init();

    // Register sections.
    if ('DM' === DVMD_TM_PURCHASE_TYPE) parent::register_section(esc_html__('License',      'dvmd-table-maker'), '1');
    if ('ET' === DVMD_TM_PURCHASE_TYPE) parent::register_section(esc_html__('Subscription', 'dvmd-table-maker'), '1');
    parent::register_section(esc_html__('Support',     'dvmd-table-maker'), '2');
    parent::register_section(esc_html__('Delete Data', 'dvmd-table-maker'), '3');

    // Register fields.
    if ('DM' === DVMD_TM_PURCHASE_TYPE) parent::register_field(esc_html__('License Key',         'dvmd-table-maker'), 'license_key',         '1');
    if ('ET' === DVMD_TM_PURCHASE_TYPE) parent::register_field(esc_html__('Subscription Status', 'dvmd-table-maker'), 'subscription_status', '1');
    parent::register_field(esc_html__('Links',       'dvmd-table-maker'), 'support_links', '2');
    parent::register_field(esc_html__('Delete Data', 'dvmd-table-maker'), 'delete_data',   '3');
  }



  // ------------------------------------------- //
  // ---------- Defaults & Validation ---------- //
  // ------------------------------------------- //


  /**
   * Returns default options.
   *
   * @since   3.1.0
   * @access  protected
   *
   * @return  array
   */
  protected function get_default_options() {
    return array(
      'license_key'         => '',
      'subscription_status' => '',
      'support_links'       => '',
      'delete_data'         => false,
    );
  }


  /**
   * Callback function to validate options.
   *
   * @since   3.1.0
   * @access  public
   * 
   * @param   array  $input  The options values.
   *
   * @return  array
   */
  public function validate_options_cb($input) {

    // Reset options.
    if (! empty($input["reset-{$this->settings_slug}"])) return $this->reset_options();

    // Get options.
    $options = get_option($this->options_name);

    // Submit options.
    if (! empty($input["submit-{$this->settings_slug}"])) {
      $options['license_key']         = $this->validate_license_key_field('license_key', $input, $options);
      $options['subscription_status'] = $this->validate_text_field('subscription_status', $input, $options);
      $options['support_links']       = $this->validate_text_field('support_links', $input, $options);
      $options['delete_data']         = $this->validate_checkbox_field('delete_data', $input, $options);
    }

    // Return.
    return $options;
  }



  // --------------------------------------- //
  // ---------- Section Callbacks ---------- //
  // --------------------------------------- //


  /**
   * Callback functions for sections.
   *
   * @since   3.1.0
   * @access  public
   * 
   * @param   array  $args  The section arguments.
   *
   * @return  string
   */

  public function section_1_cb($args) {

    // License.
    if ('DM' === DVMD_TM_PURCHASE_TYPE) {
      echo sprintf('<p class="dvmd_section_description">%s</p>', 
        sprintf(__('Here you can activate your %s license to access updates and support. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE)),
          /* 02 */ sprintf('<a href="%s" target="_blank">%s</a>', esc_url(DVMD_TM_DOCS_URL), esc_html__('Find out more', 'dvmd-table-maker'))
        )
      );
    }
    
    // Subscription.
    if ('ET' === DVMD_TM_PURCHASE_TYPE) {
      echo sprintf('<p class="dvmd_section_description">%s</p>', 
        sprintf(__('Here you can check your %s subscription status to access updates and support. %s', 'dvmd-table-maker'), 
          /* 01 */ sprintf('<strong>%s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE)),
          /* 02 */ sprintf('<a href="%s" target="_blank">%s</a>', esc_url(DVMD_TM_DOCS_URL), esc_html__('Find out more', 'dvmd-table-maker'))
        )
      );
    }
  }

  public function section_2_cb($args) {
    echo sprintf('<p class="dvmd_section_description">%s</p>', 
      sprintf(__('Here you can find links to %s documentation and customer support.', 'dvmd-table-maker'), 
        /* 01 */ sprintf('<strong>%s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE))
      )
    );
  }

  public function section_3_cb($args) {
    echo sprintf('<p class="dvmd_section_description">%s</p>', 
      sprintf(__('Here you can choose to delete all %s data on plugin deletion.', 'dvmd-table-maker'), 
        /* 01 */ sprintf('<strong>%s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE))
      )
    );
  }



  // ------------------------------------- //
  // ---------- Field Callbacks ---------- //
  // ------------------------------------- //


  /**
   * Callback functions for fields.
   *
   * @since   3.1.0
   * @access  public
   * 
   * @param   array  $args  The field arguments.
   *
   * @return  void
   */

  public function license_key_field_cb($args) {
    parent::render_license_key_field(
      $args['field_key'],
      sprintf(__('This copy of %s was purchased from the %s website. To access updates and support you will need to enter your license key into the field provided and click activate. You can find your license key on your %s or the %s page of your Divi-Modules account.', 'dvmd-table-maker'),
        /* 01 */  sprintf('<strong>%s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE)), 
        /* 02 */ '<strong>Divi-Modules</strong>',
        /* 03 */  sprintf('<em>%s</em>', esc_html__('Purchase Receipt', 'dvmd-table-maker')), 
        /* 04 */  sprintf('<em>%s</em>', esc_html__('Licenses', 'dvmd-table-maker'))
      )
    );
  }

  public function subscription_status_field_cb($args) {
    $url = ('extra' === DVMD_TM_BUILDER_TYPE) ? admin_url('admin.php?page=et_extra_options') : admin_url('admin.php?page=et_divi_options');
    $url = ( false  === DVMD_TM_BUILDER_TYPE) ? '#' : $url;
    parent::render_subscription_status_field(
      $args['field_key'],
      sprintf(__('This copy of %1$s was purchased from the %2$s. To access updates and support you will need to enter your Elegant Themes account details into the %3$s tab of this website and maintain an active %1$s subscription on the Divi Marketplace.', 'dvmd-table-maker'),
        /* 01 */  sprintf('<strong>%s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE)), 
        /* 02 */ '<strong>Elegant Themes Divi Marketplace</strong>',
        /* 03 */  sprintf('<a href="%s">%s</a>', esc_url_raw($url), esc_html__('Divi > Theme Options > Updates', 'dvmd-table-maker'))
      )
    );
  } 

  public function support_links_field_cb($args) {
    parent::render_support_links_field(
      $args['field_key'],
      sprintf(__('Please check the %s documentation for answers before contacting customer support.', 'dvmd-table-maker'), 
        /* 01 */ sprintf('<strong>%s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE))
      )
    );
  }

  public function delete_data_field_cb($args) {
    parent::render_checkbox_field(
      $args['field_key'],
      sprintf(__('Check this to delete all %s data on plugin deletion. %s', 'dvmd-table-maker'), 
        /* 01 */ sprintf('<strong>%s</strong>', esc_html(DVMD_TM_PLUGIN_TITLE)),
        /* 02 */ sprintf('<em class="dvmd_warning">%s</em>', esc_html__('This can not be undone.', 'dvmd-table-maker'))
      )
    );
  }

}

// Init.
new DVMD_Table_Maker_Admin_Settings;
