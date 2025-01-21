<?php

if (! defined('ABSPATH')) exit;

/**
 * Plugin Settings.
 *
 * @since  3.1.2
 *
 */
class DVMD_Table_Maker_Settings {


  /**
   * Settings slug.
   *
   * @since   3.1.0
   * @access  protected
   *
   * @var     string
   */
  protected $settings_slug;


  /**
   * Options name.
   *
   * @since   3.1.0
   * @access  protected
   *
   * @var     string
   */
  protected $options_name;


  /**
   * Options group.
   *
   * @since   3.1.0
   * @access  protected
   *
   * @var     string
   */
  protected $options_group;


  /**
   * Settings name.
   *
   * @since   3.1.0
   * @access  protected
   *
   * @var     string
   */
  protected $settings_name;


  /**
   * Settings page.
   *
   * @since   3.1.0
   * @access  protected
   *
   * @var     string
   */
  protected $settings_page;



  /**
   * Class initialiser.
   *
   * @since   3.1.0
   * @access  public
   *
   * @return  void
   */
  public function init() {

    // Properties.
    $this->options_name  = "dvmd_tm_{$this->settings_slug}_options";
    $this->options_group = "dvmd_tm_{$this->settings_slug}_options_group";
    $this->settings_name = "dvmd_tm_{$this->settings_slug}_settings";
    $this->settings_page = "dvmd_tm_{$this->settings_slug}_settings_page";

    // Reset options.
    // delete_option($this->options_name);

    // Get options.
    $default_options = $this->get_default_options();
    $current_options = get_option($this->options_name);
    $updated_options = array();

    // Init Options
    foreach ($default_options as $key => $value) {
      $updated_options[$key] = isset($current_options[$key]) ? $current_options[$key] : $default_options[$key];
    }

    // Update Options.
    update_option($this->options_name, $updated_options);

    // Register settings.
    register_setting(
      $this->options_group, $this->options_name, array($this, 'validate_options_cb')
    );
  }



  // ------------------------------------------- //
  // ---------- Defaults & Validation ---------- //
  // ------------------------------------------- //


  /**
   * Returns default options.
   * To be overridden by child classes.
   *
   * @since   3.1.0
   * @access  protected
   *
   * @return  array
   */
  protected function get_default_options() {
    return array();
  }


  /**
   * Resets options to default values.
   *
   * @since   3.1.0
   * @access  protected
   *
   * @return  array
   */
  protected function reset_options() {
    $options  = get_option($this->options_name);
    $defaults = $this->get_default_options();
    foreach ($options as $key => $val) {
      $options[$key] = array_key_exists($key, $defaults) ? $defaults[$key] : $val;
    }
    return $options;
  }


  /**
   * Callback function to validate options.
   * To be overridden by child classes.
   *
   * @since   3.1.0
   * @access  public
   * 
   * @param   array  $input  The options values.
   *
   * @return  array
   */
  public function validate_options_cb($input) {
    return array();
  }


  /**
   * Validates options for various fields.
   *
   * @since   3.1.2
   * @access  protected
   * 
   * @param   string  $key  The option to validate.
   * @param   array   $new  The new options.
   * @param   array   $old  The old options.
   *
   * @return  string|boolean
   */

  // License key field.
  protected function validate_license_key_field($key, $new, $old) {
    if (array_key_exists($key, $new) && '****************' !== $new[$key]) return sanitize_text_field($new[$key]);
    if (array_key_exists($key, $old)) return $old[$key];
    return $this->get_default_options()[$key];
  }

  // Text field.
  protected function validate_text_field($key, $new, $old) {
    if (array_key_exists($key, $new)) return sanitize_text_field($new[$key]);
    return $this->get_default_options()[$key];
  }

  // Textarea field.
  protected function validate_textarea_field($key, $new, $old) {
    if (array_key_exists($key, $new)) return sanitize_textarea_field($new[$key]);
    return $this->get_default_options()[$key];
  }

  // Number field.
  protected function validate_number_field($key, $new, $old) {
    if (array_key_exists($key, $new) && is_numeric($new[$key])) return sanitize_text_field($new[$key]);
    return $this->get_default_options()[$key];
  }

  // Color field.
  protected function validate_color_field($key, $new, $old) {
    if (array_key_exists($key, $new) && $this->is_hex_color($new[$key])) return sanitize_text_field($new[$key]);
    return $this->get_default_options()[$key];
  }

  // Boolean field.
  protected function validate_boolean_field($key, $new, $old) {
    if (array_key_exists($key, $new)) return true;
    return false;
  }

  // Checkbox field.
  protected function validate_checkbox_field($key, $new, $old) {
    if (array_key_exists($key, $new)) return true;
    return false;
  }

  // Multi-checkbox field.
  protected function validate_multi_checkbox_field($key, $new, $old, $allowNone = true) {
    if (array_key_exists($key, $new)) return $new[$key];
    return $allowNone ? [] : $this->get_default_options()[$key];
  }

  // Radio field.
  protected function validate_radio_field($key, $new, $old) {
    if (array_key_exists($key, $new)) return $new[$key];
    return $this->get_default_options()[$key];
  }

  // Select field.
  protected function validate_select_field($key, $new, $old) {
    if (array_key_exists($key, $new)) return $new[$key];
    return $this->get_default_options()[$key];
  }



  // ------------------------------------------------ //
  // ---------- Register Sections & Fields ---------- //
  // ------------------------------------------------ //


  /**
   * Registers a section.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $section_name  The section name.
   * @param   string  $section_key   The section key.
   *
   * @return  void
   */
  protected function register_section($section_name, $section_key) {
    add_settings_section(
      sprintf("%s_section_{$section_key}", $this->settings_name),
      "{$section_name}:",
      array($this, "section_{$section_key}_cb"),
      $this->settings_page
    );
  }


  /**
   * Registers a field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $field_name   The field name.
   * @param   string  $field_key    The field key.
   * @param   string  $section_key  The section key.
   *
   * @return  void
   */
  protected function register_field($field_name, $field_key, $section_key) {
    add_settings_field(
      $field_key,
      "{$field_name}:",
      array($this, "{$field_key}_field_cb"),
      $this->settings_page,
      sprintf("%s_section_{$section_key}", $this->settings_name),
      array('field_key' => $field_key, 'class' => "dvmd_{$field_key}_row", 'label_for' => "dvmd_{$field_key}_field")
    );
  }



  // ----------------------------------- //
  // ---------- Render Fields ---------- //
  // ----------------------------------- //


  /**
   * Renders a license key field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $field_name         The field name.
   * @param   string  $field_description  The field description.
   * @param   string  $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_license_key_field($field_name, $field_description, $field_classes = '') {

    // Options.
    $options = get_option($this->options_name);

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_license_key_field %3$s">
        <div class="dvmd_input_wrap">
          <button type="button" class="dvmd_reveal"><span class="dashicons dashicons-visibility"></span></button>
          <input type="password" aria-describedby="dvmd_%1$s_%2$s_aria" name="%4$s[%2$s]" value="%5$s">
          <button type="button" class="dvmd_action">...</button>
        </div>
        <p class="dvmd_status dashicons-before">...</p>
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_license_key_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%6$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ esc_attr($this->options_name),
      /* 05 */ esc_attr('****************'),
      /* 06 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a subscription status field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $field_name         The field name.
   * @param   string  $field_description  The field description.
   * @param   string  $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_subscription_status_field($field_name, $field_description, $field_classes = '') {

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_subscription_status_field %3$s">
        <pre aria-describedby="dvmd_%1$s_%2$s_aria">%4$s</pre>
        <p class="dvmd_status dashicons-before">...</p>
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_subscription_status_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%5$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ esc_html__('Checking...', 'dvmd-table-maker'),
      /* 05 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a support links field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $field_name         The field name.
   * @param   string  $field_description  The field description.
   * @param   string  $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_support_links_field($field_name, $field_description, $field_classes = '') {

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_support_links_field" aria-describedby="dvmd_%1$s_%2$s_aria %3$s">
        <a href="%4$s" target="_blank">%5$s</a>         
        <a href="%6$s" target="_blank">%7$s</a>
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_support_links_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%8$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ esc_url(DVMD_TM_DOCS_URL),
      /* 05 */ esc_html__('Documentation', 'dvmd-table-maker'),
      /* 06 */ esc_url(DVMD_TM_SUPPORT_URL),
      /* 07 */ esc_html__('Support', 'dvmd-table-maker'),
      /* 08 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a text field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $field_name         The field name.
   * @param   string  $field_description  The field description.
   * @param   string  $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_text_field($field_name, $field_description, $field_classes = '') {

    // Options.
    $options = get_option($this->options_name);

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_text_field %3$s">
        <input type="text" aria-describedby="dvmd_%1$s_%2$s_aria" name="%4$s[%2$s]" value="%5$s">
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_text_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%6$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ esc_attr($this->options_name),
      /* 05 */ esc_attr($options[$field_name]),
      /* 06 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a textarea field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string   $field_name         The field name.
   * @param   string   $field_description  The field description.
   * @param   integer  $field_rows         The number of rows.
   * @param   string   $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_textarea_field($field_name, $field_description, $field_rows = 3, $field_classes = '') {

    // Options.
    $options = get_option($this->options_name);

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_textarea_field %3$s">
        <textarea type="text" aria-describedby="dvmd_%1$s_%2$s_aria" name="%4$s[%2$s]" rows="%6$s">%5$s</textarea>
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_textarea_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%7$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ esc_attr($this->options_name),
      /* 05 */ esc_attr($options[$field_name]),
      /* 06 */ esc_attr($field_rows),
      /* 07 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a number field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string   $field_name         The field name.
   * @param   string   $field_description  The field description.
   * @param   integer  $field_min          The minimum number allowed.
   * @param   integer  $field_max          The maximum number allowed.
   * @param   integer  $field_step         The step increment.
   * @param   string   $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_number_field($field_name, $field_description, $field_min = -99999999, $field_max = 99999999, $field_step = 1, $field_classes = '') {

    // Options.
    $options = get_option($this->options_name);

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_number_field %3$s">
        <input type="number" aria-describedby="dvmd_%1$s_%2$s_aria" name="%4$s[%2$s]" min="%6$s" max="%7$s" step="%8$s" value="%5$s">
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_number_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%9$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ esc_attr($this->options_name),
      /* 05 */ esc_attr($options[$field_name]),
      /* 06 */ esc_attr($field_min),
      /* 07 */ esc_attr($field_max),
      /* 08 */ esc_attr($field_step),
      /* 09 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a color field.
   *
   * @since   3.1.1
   * @access  protected
   * 
   * @param   string  $field_name         The field name.
   * @param   string  $field_description  The field description.
   * @param   string  $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_color_field($field_name, $field_description, $field_classes = '') {

    // Options.
    $options = get_option($this->options_name);

    // Default.
    $default_color = sprintf('<p class="dvmd_default_color">%s: %s</p>', 
      /* 01 */ esc_html__('Default', 'dvmd-table-maker'), 
      /* 02 */ esc_html(strtoupper($this->get_default_options()[$field_name]))
    ); 

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_color_field %3$s">
        <input type="text" aria-describedby="dvmd_%1$s_%2$s_aria" name="%4$s[%2$s]" value="%5$s">
        <label for="dvmd_%1$s_%2$s_input">%6$s</label>
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_color_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%7$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ esc_attr($this->options_name),
      /* 05 */ esc_attr($options[$field_name]),
      /* 06 */ wp_kses_post($default_color),
      /* 07 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a boolean field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $field_name         The field name.
   * @param   string  $field_description  The field description.
   * @param   string  $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_boolean_field($field_name, $field_description, $field_classes = '') {

    // Options.
    $options = get_option($this->options_name);

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_boolean_field %3$s">
        <input type="checkbox" id="dvmd_%1$s_%2$s_input" aria-describedby="dvmd_%1$s_%2$s_aria" name="%4$s[%2$s]"%5$s>
        <label for="dvmd_%1$s_%2$s_input"></label>
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_boolean_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%6$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ esc_attr($this->options_name),
      /* 05 */ esc_attr($options[$field_name] ? ' checked' : ''),
      /* 06 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a checkbox field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $field_name         The field name.
   * @param   string  $field_description  The field description.
   * @param   string  $field_label        The field label.
   * @param   string  $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_checkbox_field($field_name, $field_description, $field_label = '', $field_classes = '') {

    // Options.
    $options = get_option($this->options_name);

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_checkbox_field %3$s">
        <input type="checkbox" id="dvmd_%1$s_%2$s_input" aria-describedby="dvmd_%1$s_%2$s_aria" name="%4$s[%2$s]"%5$s>
        <label for="dvmd_%1$s_%2$s_input">%6$s</label>
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_checkbox_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%7$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ esc_attr($this->options_name),
      /* 05 */ esc_attr($options[$field_name] ? ' checked' : ''),
      /* 06 */ esc_html($field_label),
      /* 07 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a multi-checkbox field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $field_name         The field name.
   * @param   string  $field_description  The field description.
   * @param   array   $field_options      The field options.
   * @param   string  $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_multi_checkbox_field($field_name, $field_description, $field_options, $field_classes = '') {

    // Options.
    $options = get_option($this->options_name);

    // Selections.
    $selections = '';
    foreach ($field_options as $key => $val) {
      $selections .= sprintf(
       '<div class="dvmd_option">
          <input type="checkbox" id="dvmd_%1$s_%2$s_%4$s_input" name="%3$s[%2$s][]" value="%4$s"%6$s>
          <label for="dvmd_%1$s_%2$s_%4$s_input">%5$s</label>
        </div>',
        /* 01 */ esc_attr($this->settings_slug),
        /* 02 */ esc_attr($field_name),
        /* 03 */ esc_attr($this->options_name),
        /* 04 */ esc_attr($key),
        /* 05 */ esc_html($val),
        /* 06 */ esc_attr(in_array($key, (array) $options[$field_name]) ? ' checked' : '')
      );
    }

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_multi_checkbox_field %3$s">
        <fieldset aria-describedby="dvmd_%1$s_%2$s_aria">%4$s</fieldset>
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_multi_checkbox_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%5$s</p>
      </div>',

      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ $selections, // phpcs:ignore
      /* 05 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a radio field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $field_name         The field name.
   * @param   string  $field_description  The field description.
   * @param   array   $field_options      The field options.
   * @param   string  $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_radio_field($field_name, $field_description, $field_options, $field_classes = '') {

    // Options.
    $options = get_option($this->options_name);

    // Selections.
    $selections = '';
    foreach ($field_options as $key => $val) {
      $selections .= sprintf(
        '<div class="dvmd_option">
          <input type="radio" id="dvmd_%1$s_%2$s_%4$s" name="%3$s[%2$s]" value="%4$s"%6$s>
          <label for="dvmd_%1$s_%2$s_%4$s">%5$s</label>
        </div>',
        /* 01 */ esc_attr($this->settings_slug),
        /* 02 */ esc_attr($field_name),
        /* 03 */ esc_attr($this->options_name),
        /* 04 */ esc_attr($key),
        /* 05 */ esc_html($val),
        /* 06 */ esc_attr($key == $options[$field_name] ? ' checked' : '')
      );
    }

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_radio_field %3$s">
        <fieldset aria-describedby="dvmd_%1$s_%2$s_aria">%4$s</fieldset>
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_radio_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%5$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ $selections, // phpcs:ignore
      /* 05 */ wp_kses_post($field_description)
    );
  }


  /**
   * Renders a select field.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $field_name         The field name.
   * @param   string  $field_description  The field description.
   * @param   array   $field_options      The field options.
   * @param   string  $field_classes      The field classes.
   *
   * @return  string
   */
  protected function render_select_field($field_name, $field_description, $field_options, $field_classes = '') {

    // Options.
    $options = get_option($this->options_name);

    // Selections.
    $selections = '';
    foreach ($field_options as $key => $val) {
      $selections .= sprintf('<option value="%s"%s>%s</option>', 
        /* 01 */ esc_attr($key),
        /* 02 */ esc_attr($key == $options[$field_name] ? ' selected' : ''),
        /* 03 */ esc_html($val)
      );
    }

    // Output.
    echo sprintf(
      '<div id="dvmd_%1$s_%2$s_field" class="dvmd_field dvmd_select_field %3$s">
        <select aria-describedby="dvmd_%1$s_%2$s_aria" name="%4$s[%2$s]">%5$s</select>
      </div>
      <div id="dvmd_%1$s_%2$s_field_description" class="dvmd_field_description dvmd_select_field_description">
        <p id="dvmd_%1$s_%2$s_aria">%6$s</p>
      </div>',
      /* 01 */ esc_attr($this->settings_slug),
      /* 02 */ esc_attr($field_name),
      /* 03 */ esc_attr($field_classes),
      /* 04 */ esc_attr($this->options_name),
      /* 05 */ $selections, // phpcs:ignore
      /* 06 */ wp_kses_post($field_description)
    );
  }



  // ----------------------------- //
  // ---------- Helpers ---------- //
  // ----------------------------- //


  /**
   * Checks if value is a valid HEX color.
   *
   * @since   3.1.0
   * @access  protected
   * 
   * @param   string  $value  The color value.
   *
   * @return  boolean
   */
  protected static function is_hex_color($value) {
    if (preg_match('/^#[a-f0-9]{6}$/i', $value)) return true;
    return false;
  }

}

// Init.
new DVMD_Table_Maker_Settings;
