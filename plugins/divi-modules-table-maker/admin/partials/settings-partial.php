<?php

/**
 * Renders the options settings.
 *
 * @since   3.1.1
 *
 * @return  void
 */

?>

<form action="options.php" method="post">
  <?php settings_fields("dvmd_tm_{$slug}_options_group"); ?>
  <?php do_settings_sections("dvmd_tm_{$slug}_settings_page"); ?>
  <input type="submit" class="button-primary" name="dvmd_tm_<?php echo esc_attr($slug); ?>_options[submit-<?php echo esc_attr($slug); ?>]" value="<?php esc_html_e('Save Settings', 'dvmd-table-maker'); ?>">
  <input type="submit" class="button-secondary" name="dvmd_tm_<?php echo esc_attr($slug); ?>_options[reset-<?php echo esc_attr($slug); ?>]" value="<?php esc_html_e('Reset Settings', 'dvmd-table-maker'); ?>">
</form>
