<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$check_nonce = wp_verify_nonce( $_GET['_wpnonce'], 'delete_skill'. $_GET['post'] );

if (is_user_logged_in() && $check_nonce !== FALSE) {


    if (isset($_GET['id'])) {
        // echo $_GET['id'];
        $skill_id = sanitize_text_field($_GET['id']);
        $post_id  = sanitize_text_field($_GET['post']);
        global $wpdb;

        check_admin_referer('delete_skill' . $post_id);

        $stmt = "DELETE  FROM " . WPTS_BUR_SKILLS_TABLE . "
            where id = " . $skill_id;

        $myrows = $wpdb->get_results($stmt);

        $url = get_edit_post_link($post_id, '');

        wp_redirect($url);

        exit();

    }
}