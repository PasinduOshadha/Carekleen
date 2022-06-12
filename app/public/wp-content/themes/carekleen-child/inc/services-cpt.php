<?php
// Register Custom Post Type Service
// Post Type Key: Service
function create_service_cpt() {

    $labels = array(
        'name' => _x( 'Services', 'Post Type General Name', 'carekleen' ),
        'singular_name' => _x( 'Service', 'Post Type Singular Name', 'carekleen' ),
        'menu_name' => _x( 'Services', 'Admin Menu text', 'carekleen' ),
        'name_admin_bar' => _x( 'Service', 'Add New on Toolbar', 'carekleen' ),
        'archives' => __( 'Service', 'carekleen' ),
        'attributes' => __( 'Service', 'carekleen' ),
        'parent_item_colon' => __( 'Service', 'carekleen' ),
        'all_items' => __( 'All Services', 'carekleen' ),
        'add_new_item' => __( 'Add New Service', 'carekleen' ),
        'add_new' => __( 'Add New', 'carekleen' ),
        'new_item' => __( 'New Service', 'carekleen' ),
        'edit_item' => __( 'Edit Service', 'carekleen' ),
        'update_item' => __( 'Update Service', 'carekleen' ),
        'view_item' => __( 'View Service', 'carekleen' ),
        'view_items' => __( 'View Services', 'carekleen' ),
        'search_items' => __( 'Search Service', 'carekleen' ),
        'not_found' => __( 'Not found', 'carekleen' ),
        'not_found_in_trash' => __( 'Not found in Trash', 'carekleen' ),
        'featured_image' => __( 'Featured Image', 'carekleen' ),
        'set_featured_image' => __( 'Set featured image', 'carekleen' ),
        'remove_featured_image' => __( 'Remove featured image', 'carekleen' ),
        'use_featured_image' => __( 'Use as featured image', 'carekleen' ),
        'insert_into_item' => __( 'Insert into Service', 'carekleen' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Service', 'carekleen' ),
        'items_list' => __( 'Services list', 'carekleen' ),
        'items_list_navigation' => __( 'Services list navigation', 'carekleen' ),
        'filter_items_list' => __( 'Filter Services list', 'carekleen' ),
    );
    
    $args = array(
        'label' => __( 'Service', 'carekleen' ),
        'description' => __( '', 'carekleen' ),
        'labels' => $labels,
        'menu_icon' => 'dashicons-images-alt',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
        'taxonomies' => array(),
        'hierarchical' => false,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'has_archive' => true,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'can_export' => true,
        'show_in_nav_menus' => true,
        'menu_position' => 5,
        'capability_type' => 'post',
        'show_in_rest' => true,
    );
    
    register_post_type( 'services', $args );

}
add_action( 'init', 'create_service_cpt', 0 );