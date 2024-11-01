<?php
/*
Plugin Name: WP T-Shape
Plugin URI: http://www.wp-tshape.com
Description: Create T-Shapes and include them easily on pages or posts via shortcode.
Version: 1.0
Author: Besserdich und Redmann GmbH
Author URI: http://www.besserdich-redmann.com
Text Domain: wp-tshape
Domain Path: /lang
License: GPLv2
*/

global $wpdb;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('WPTS_TSHAPE_BASE', __FILE__);
// TABLES
define('WPTS_BUR_LEVEL_TYPES_TABLE', $wpdb->prefix . 'bur_ts_level_types');
define('WPTS_BUR_SKILLS_TABLE', $wpdb->prefix . 'bur_ts_skills');

require_once(dirname(__FILE__) . '/includes/autoload.php');
require_once(dirname(__FILE__) . '/includes/shortcodes/tshape.php');
require_once(dirname(__FILE__) . '/includes/posttypes/tshape.php');
require_once(dirname(__FILE__) . '/includes/backend/metaboxes.php');
require_once(dirname(__FILE__) . '/includes/backend/options-page.php');

// INSTALLATION
function wpts_tshape_install()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $sql = "CREATE TABLE " . WPTS_BUR_LEVEL_TYPES_TABLE . " (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(45) NULL,
    `label` VARCHAR(45) NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;";

    dbDelta($sql);

    $sql = "CREATE TABLE " . WPTS_BUR_SKILLS_TABLE . " (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(45) NULL,
     `tshape_id` INT NULL,
     `bur_ts_level_types_id` INT NOT NULL,
     PRIMARY KEY (`id`),
     INDEX `fk_bur_ts_skills_bur_ts_level_types_idx` (`bur_ts_level_types_id` ASC),
     CONSTRAINT `fk_bur_ts_skills_bur_ts_level_types`
      FOREIGN KEY (`bur_ts_level_types_id`)
      REFERENCES " . WPTS_BUR_LEVEL_TYPES_TABLE . " (`id`)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION)
    ENGINE = InnoDB;";

    dbDelta($sql);

    $sql_insert_initial_values = "INSERT INTO " . WPTS_BUR_LEVEL_TYPES_TABLE . " ( `name`, `label`)
    VALUES
    ('basic',''),
    ('good',''),
    ('very good',''),
    ('expert','');";

    $wpdb->query($sql_insert_initial_values);

}


// UNINSTALLATION
function wpts_tshape_uninstall()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $wpdb->query("DROP TABLE IF EXISTS " . WPTS_BUR_SKILLS_TABLE);
    $wpdb->query("DROP TABLE IF EXISTS " . WPTS_BUR_LEVEL_TYPES_TABLE);
}



// SAVE META FIELDS
function wpts_save_meta_fields($post_id)
{

    global $wpdb;

    // verify nonce
    if (!isset($_POST['add_skill']) || !wp_verify_nonce($_POST['add_skill'], WPTS_TSHAPE_BASE))

        return 'nonce not verified';

    // check autosave
    if (wp_is_post_autosave($post_id))
        return 'autosave';

    //check post revision
    if (wp_is_post_revision($post_id))
        return 'revision';

    // check permissions
    if ('project' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return 'cannot edit page';
    } elseif (!current_user_can('edit_post', $post_id)) {
        return 'cannot edit post';
    }

    $wp_skill_name =  sanitize_text_field( $_POST['skill_name']);
    $wp_skill_level = sanitize_text_field($_POST['skill_level']);

    if ($wp_skill_name != '') {

        $wpdb->insert(
            WPTS_BUR_SKILLS_TABLE,
            array(
                'tshape_id' => $post_id,
                'name' => $wp_skill_name,
                'bur_ts_level_types_id' => $wp_skill_level
            )
        );

    }
}


//ADD CSS STYLES
function wpts_register_css_styles()
{
    wp_register_style('tshape_style', plugins_url('/media/sass/style.css', __FILE__));
    wp_enqueue_style('tshape_style');
    wp_register_style('tshape_tooltip_balloon_style', plugins_url('/media/css/balloon.css', __FILE__));
    wp_enqueue_style('tshape_tooltip_balloon_style');
}


//ADD ADMIN CSS STYLES
function wpts_register_css_admin_styles()
{
    wp_enqueue_style('admin-styles', plugins_url('/media/sass/style.css', __FILE__));
    if (is_admin()) {
        wp_enqueue_style('wp-color-picker');
    }
}


//LOAD TEXT DOMAIN FOR INTERNATIONALIZATION
function wpts_load_wp_tshape_textdomain()
{
    load_plugin_textdomain('wp-tshape', false, basename(dirname(__FILE__)) . '/lang/');
}


//ADD OPTIONS PAGE UNDER SETTING MENU
function wpts_add_page()
{
    add_options_page(__('WP T-Shape Options', 'wp-tshape'), __('WP T-Shape Options', 'wp-tshape'), 'manage_options', __FILE__, 'wpts_display_page');
}


// ADD JAVASCRIPT FILE FOR COLOR PICKER
function wpts_enqueue_admin_js()
{
    wp_enqueue_script('tshape', plugins_url('/media/js/wp-tshape.js', __FILE__), array('wp-color-picker'), false, true);
}

// HOOK for calling delete-skills.php by parameter (prevent to load wp_load in this file)
function wpts_parse_request($wp) {

    // only process requests with "tshape=delete_skill"
    if (array_key_exists('tshape', $wp->query_vars)
        && $wp->query_vars['tshape'] == 'delete_skill') {

        // process the request.
        require_once(dirname(__FILE__) . '/delete_skill.php');


        // For now, we'll just call wp_die, so we know it got processed
        wp_die('tshape ajax-handler!');
    }
}

function wpts_query_vars($vars) {
    $vars[] = 'tshape';
    return $vars;
}

add_filter('query_vars', 'wpts_query_vars');
add_action('parse_request', 'wpts_parse_request');

// HOOKS
register_activation_hook(__FILE__, 'wpts_tshape_install');
register_uninstall_hook(__FILE__, 'wpts_tshape_uninstall');

//ACTIONS
add_action('add_meta_boxes', 'wpts_add_custom_meta_box');

/* Add meta boxes on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'wpts_add_how_to_use_meta_box' );


add_action('save_post', 'wpts_save_meta_fields');
add_action('new_to_publish', 'wpts_save_meta_fields');

add_action('wp_enqueue_scripts', 'wpts_register_css_styles');
add_action('admin_enqueue_scripts', 'wpts_register_css_admin_styles');
add_action('admin_enqueue_scripts', 'wpts_enqueue_admin_js');

add_action('plugins_loaded', 'wpts_load_wp_tshape_textdomain');

add_action('admin_menu', 'wpts_add_page');
add_action('admin_init', 'wpts_register_page_options');

//SHORTCODES
add_shortcode('tshape', 'wpts_tshape_shortcode');