<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// VALIDATE ALL FIELDS OF OPTIONS PAGE
function wpts_tshape_validate_options($fields) {

  $options       = get_option('wpts_settings_options');
  $valid_fields  = [];
  $bg_skilllevel = trim($fields['bg_skilllevel']);
  $bg_skilllevel = strip_tags(stripslashes($bg_skilllevel));


  // Check if is a valid hex color
  if (FALSE === wpts_check_color($bg_skilllevel)) {
    // Set the error message
    add_settings_error('wpts_settings_options',
      'wpts_bg_error',
      'Insert a valid color for Background of Skill Level',
      'error'); // $setting, $code, $message, $type
    // Get the previous valid value
    $valid_fields['bg_skilllevel'] = $options['bg_skilllevel'];

  }
  else {
    $valid_fields['bg_skilllevel'] = $bg_skilllevel;
  }

  // Validate Background Color of Skill Name
  $bg_skillname = trim($fields['bg_skillname']);
  $bg_skillname = strip_tags(stripslashes($bg_skillname));

  // Check if is a valid hex color
  if (FALSE === wpts_check_color($bg_skillname)) {
    // Set the error message
    add_settings_error('wpts_settings_options',
      'wpts_bg_error',
      'Insert a valid color for Background of Skill Name',
      'error'); // $setting, $code, $message, $type
    // Get the previous valid value
    $valid_fields['bg_skillname'] = $options['bg_skillname'];

  }
  else {
    $valid_fields['bg_skillname'] = $bg_skillname;
  }

  // Validate Font Color of Skill Name
  $font_col_skillname = trim($fields['font_col_skillname']);
  $font_col_skillname = strip_tags(stripslashes($font_col_skillname));


  // Check if is a valid hex color
  if (FALSE === wpts_check_color($font_col_skillname)) {
    // Set the error message
    add_settings_error('wpts_settings_options',
      'wpts_bg_error',
      'Insert a valid font color',
      'error'); // $setting, $code, $message, $type
    // Get the previous valid value
    $valid_fields['font_col_skillname'] = $options['font_col_skillname'];


  }
  else {
    $valid_fields['font_col_skillname'] = $font_col_skillname;
  }

  return apply_filters('validate_options', $valid_fields, $fields);
}

// CHECK IF VALUE IS A VALID HEX COLOR
function wpts_check_color($value) {
  if (preg_match('/^#[a-f0-9]{6}$/i',
    $value)) { // if user insert a HEX color with #
    return TRUE;
  }
  return FALSE;
}

/// CALLBACK FOR SETTINGS SECTION
function display_section() {
  // BLANK
}

function wpts_bg_skilllevel_settings_field() {
  $options = get_option('wpts_settings_options');
  $val     = (isset($options['bg_skilllevel'])) ? $options['bg_skilllevel'] : '';
  echo '<input type="text" name="wpts_settings_options[bg_skilllevel]" value="' . esc_html($val) . '" class="cpa-color-picker" >';
}

function wpts_bg_skillname_settings_field() {
  $options = get_option('wpts_settings_options');
  $val     = (isset($options['bg_skillname'])) ? $options['bg_skillname'] : '';
  echo '<input type="text" name="wpts_settings_options[bg_skillname]" value="' . esc_html($val) . '" class="cpa-color-picker" >';
}


function wpts_font_col_skillname_settings_field() {
  $options = get_option('wpts_settings_options');
  $val     = (isset($options['font_col_skillname'])) ? $options['font_col_skillname'] : '';
  echo '<input type="text" name="wpts_settings_options[font_col_skillname]" value="' . esc_html($val) . '" class="cpa-color-picker" >';
}


// REGISTER ADMIN PAGE OPTIONS
function wpts_register_page_options() {
  // Add Section for option fields
  add_settings_section('wpts_section',
    __('Colors', 'wp-tshape'),
    'display_section',
    __FILE__); // id, title, display cb, page

  // Add Background Color Field fpr Skill Level
  $label = __('Background Color of Skill Level', 'wp-tshape') . ' (1)';
  add_settings_field('wpts_bg_skilllevel_field',
    $label,
    'wpts_bg_skilllevel_settings_field',
    __FILE__,
    'wpts_section'); // id, title, display cb, page, section

  // Add Background Color Field for Skill Name
  $label = __('Background Color of Skill Name', 'wp-tshape') . ' (2)';
  add_settings_field('wpts_bg_skillname_field',
    $label,
    'wpts_bg_skillname_settings_field',
    __FILE__,
    'wpts_section'); // id, title, display cb, page, section

  // Add Color Field for Skill Name Color
  $label = __('Font Color of Skill Name', 'wp-tshape') . ' (3)';
  add_settings_field('wpts_font_col_skillname_field',
    $label,
    'wpts_font_col_skillname_settings_field',
    __FILE__,
    'wpts_section'); // id, title, display cb, page, section

  // Register Settings
  register_setting(__FILE__,
    'wpts_settings_options',
    ['sanitize_callback' => 'wpts_tshape_validate_options']); // option group, option name, sanitize cb
}


function wpts_display_page() {
  ?>
  <div class="tshape-admin_options_wrapper">

    <h2><?php _e('WP T-Shape Options', "wp-tshape"); ?></h2>

    <div class="tshape-admin_options">

      <form method="post" action="options.php">
        <?php
        settings_fields(__FILE__);
        do_settings_sections(__FILE__);
        submit_button();
        ?>
      </form>

    </div>

    <div class="tshape-admin_options_img">
      <?php
      $img = plugins_url('/includes/backend/options-helper.png', WPTS_TSHAPE_BASE);
      echo '<img src="' . esc_html($img) . '">';
      ?>

    </div>

  </div>
  <?php
}