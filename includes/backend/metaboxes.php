<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// BUILD CUSTOM META BOX
function wpts_show_custom_meta_box()
{

    wp_nonce_field(WPTS_TSHAPE_BASE, 'add_skill');

    $postid = get_the_ID();

    $all_skills = \TShape\AbstractView::getSkillsWithDetails($postid);

    if (count($all_skills) > 0) {
        echo "<table class='tshape-admin_skill_table'>";
        echo "<tr><th width='50%'><strong>" . __('Name', 'wp-tshape') . "</strong></th><th width='25%'><strong>" . __('Level', 'wp-tshape') . "</strong></th><th width='25%'>&nbsp;</th></tr>";

        $nonce = wp_create_nonce('delete_skill' . $postid);

        foreach ($all_skills as $skill) {
            echo "<tr><td>";
            echo esc_html($skill["skillname"]);
            echo "</td><td>";
            echo esc_html($skill["levelname"]);
            echo "</td><td>";

            // call delete_skill.php by parameter tshape = delete_skill to prevent to include wp_load (look @ wp-tshape.php)
            echo '<a href="/index.php?tshape=delete_skill&id=' . esc_html($skill["skillid"]) . '&post=' . esc_html($postid) . '&_wpnonce=' . $nonce . '">' . __("Delete Skill", "wp-tshape") . '</a></td></tr>';
        }

        echo "</table>";

    }

    echo "<table class='tshape-admin_skill_table--new'>";
    echo "<tr><th><strong>+ " . __('Add New Skill', 'wp-tshape') . "</strong></th><th><strong>&nbsp;</strong></th><th>&nbsp;</th></tr>";
    echo "<td width='50%'>";
    echo '<input type="text" name="skill_name" placeholder="' . __('e.g. HTML, PHP', 'wp-tshape') . '" size="40">';
    echo "</td><td width='25%'>";

    $all_skill_levels = \TShape\AbstractView::getLevels();

    if (count($all_skill_levels) > 0) {
        echo '<select name="skill_level" required>';
        foreach ($all_skill_levels as $key => $value) {
            echo '<option value="' . esc_html($key) . '">' . esc_html($value) . '</option>';
        }
        echo '</select>';
    }

    echo "</td><td class='tshape-bur-ts-adm-submit'>";
    submit_button(__('Add Skill', 'wp-tshape'), 'primary');
    echo "</td></tr>";
    echo "</table>";

}

function wpts_add_custom_meta_box()
{
    add_meta_box(
        'custom_meta_box-2',       // $id
        __('Skills', 'wp-tshape'),   // $title
        'wpts_show_custom_meta_box',  // $callback
        't_shape',                 // $page
        'normal',                  // $context
        'high'                     // $priority
    );
}


// ADD META BOX FOR HOW TO USE SHORTCODE
function wpts_add_how_to_use_meta_box() {

    add_meta_box(
        'wpts_how_to_use_meta_box',      // Unique ID
        __('Display T-Shape', 'wp-tshape'),    // Title
        'wpts_how_to_use_meta_box',   // Callback function
        't_shape',         // Admin page (or post type)
        'side',         // Context
        'default'         // Priority
    );
}

// META BOX FOR HOW TO USE SHORTCODE
function wpts_how_to_use_meta_box( $post ) { ?>
    <?php wp_nonce_field( basename( __FILE__ ), 'smashing_post_class_nonce' );
    $postid = get_the_ID();
    ?>
    <p>
        <span class="tshape-admin_shortcode--description"><?php _e( "Insert the following shortcode into posts and pages:", 'wp-tshape' ); ?></span>
        <br />
        <input class="tshape-admin_shortcode--display" type="text" disabled value='[tshape id="<?php echo esc_html($postid); ?>"]'  size="30" /><br/><br/>
        <span class="tshape-admin_shortcode--description"><?php _e( "For more options visit:<br><a href='https://www.wp-tshape.com/documentation/?lang=en' target='_blank'>Online Documentation</a>", 'wp-tshape' ); ?><br/>

    </p>
<?php }