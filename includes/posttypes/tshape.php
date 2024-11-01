<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function tshape_post_type() {

    $labels = array(
        'name'                  => _x( 'T-Shapes', 'Post Type General Name', 'wp-tshape' ),
        'singular_name'         => _x( 'T-Shape', 'Post Type Singular Name', 'wp-tshape' ),
        'menu_name'             => __( 'T-Shape', 'wp-tshape' ),
        'name_admin_bar'        => __( 'Post Type', 'wp-tshape' ),
        'archives'              => __( 'Item Archives', 'wp-tshape' ),
        'attributes'            => __( 'Item Attributes', 'wp-tshape' ),
        'parent_item_colon'     => __( 'Parent Item:', 'wp-tshape' ),
        'all_items'             => __( 'All T-Shapes', 'wp-tshape' ),
        'add_new_item'          => __( 'Add New T-Shape', 'wp-tshape' ),
        'add_new'               => __( 'Add New T-Shape', 'wp-tshape' ),
        'new_item'              => __( 'New T-Shape', 'wp-tshape' ),
        'edit_item'             => __( 'Edit T-Shape', 'wp-tshape' ),
        'update_item'           => __( 'Update T-Shape', 'wp-tshape' ),
        'view_item'             => __( 'View Item', 'wp-tshape' ),
        'view_items'            => __( 'View Items', 'wp-tshape' ),
        'search_items'          => __( 'Search Item', 'wp-tshape' ),
        'not_found'             => __( 'Not found', 'wp-tshape' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'wp-tshape' ),
        'featured_image'        => __( 'Featured Image', 'wp-tshape' ),
        'set_featured_image'    => __( 'Set featured image', 'wp-tshape' ),
        'remove_featured_image' => __( 'Remove featured image', 'wp-tshape' ),
        'use_featured_image'    => __( 'Use as featured image', 'wp-tshape' ),
        'insert_into_item'      => __( 'Insert into item', 'wp-tshape' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'wp-tshape' ),
        'items_list'            => __( 'Items list', 'wp-tshape' ),
        'items_list_navigation' => __( 'Items list navigation', 'wp-tshape' ),
        'filter_items_list'     => __( 'Filter items list', 'wp-tshape' ),
    );
    $args = array(
        'label'                 => __( 'T-Shape', 'wp-tshape' ),
        'description'           => __( 'Custom Post Type for skill T-shapes', 'wp-tshape' ),
        'labels'                => $labels,
        'supports'              => array('title','custom-fields'),

        'taxonomies'            => array(  ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 't_shape', $args );



}
add_action( 'init', 'tshape_post_type', 0 );