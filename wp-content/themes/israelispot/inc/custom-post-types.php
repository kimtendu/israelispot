<?php
function custom_post_activity() {
    $labels = array(
        'name'               => _x( 'Activities', 'post type general name' ),
        'singular_name'      => _x( 'Activity', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'Activity' ),
        'add_new_item'       => __( 'Add New Activity' ),
        'edit_item'          => __( 'Edit Activity' ),
        'new_item'           => __( 'New Activity' ),
        'all_items'          => __( 'All Activities' ),
        'view_item'          => __( 'View Activity' ),
        'search_items'       => __( 'Search Activities' ),
        'not_found'          => __( 'No Activities found' ),
        'not_found_in_trash' => __( 'No Activities found in the Trash' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Activities',
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our activities and activity specific data',
        'public'        => true,
        'menu_position' => 4,
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'has_archive'   => true,
        'rewrite'       => true,
    );
    register_post_type( 'activity', $args );
    flush_rewrite_rules();
    register_taxonomy(
        'activity_tags', //taxonomy
        'activity', //post-type
        array(
            'hierarchical'  => false,
            'label'         => __( 'Activity Tags','taxonomy general name'),
            'singular_name' => __( 'Tag', 'taxonomy general name' ),
            'rewrite'       => true,
            'query_var'     => true,
            'show_admin_column' => true
        ));
}
add_action( 'init', 'custom_post_activity' );


function custom_post_attraction() {
    $labels = array(
        'name'               => _x( 'Attractions', 'post type general name' ),
        'singular_name'      => _x( 'Attraction', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'Attraction' ),
        'add_new_item'       => __( 'Add New Attraction' ),
        'edit_item'          => __( 'Edit Attraction' ),
        'new_item'           => __( 'New Attraction' ),
        'all_items'          => __( 'All Attractions' ),
        'view_item'          => __( 'View Attraction' ),
        'search_items'       => __( 'Search Attractions' ),
        'not_found'          => __( 'No Attractions found' ),
        'not_found_in_trash' => __( 'No Attractions found in the Trash' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Attractions',
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our attractions and attraction specific data',
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'has_archive'   => true,
        'rewrite'       => true,
    );
    register_post_type( 'attraction', $args );
    register_taxonomy(
        'attraction_categories',
        'attraction',
        array(
            'label' => __( 'Attraction categories' ),
            'rewrite' => array( 'slug' => 'attraction_categories' ),
            'hierarchical' => true,
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'show_admin_column' => true
        )
    );
    register_taxonomy(
        'attraction_tags', //taxonomy
        'attraction', //post-type
        array(
            'hierarchical'  => false,
            'label'         => __( 'Cooperation','taxonomy general name'),
            'singular_name' => __( 'Cooperation', 'taxonomy general name' ),
            'rewrite'       => true,
            'query_var'     => true,
            'show_admin_column' => true
        ));
    flush_rewrite_rules();
}
add_action( 'init', 'custom_post_attraction' );

function custom_post_blog() {
    $labels = array(
        'name'               => _x( 'Blogs', 'post type general name' ),
        'singular_name'      => _x( 'Blog', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'Blog' ),
        'add_new_item'       => __( 'Add New Blog' ),
        'edit_item'          => __( 'Edit Blog' ),
        'new_item'           => __( 'New Blog' ),
        'all_items'          => __( 'All Blogs' ),
        'view_item'          => __( 'View Blog' ),
        'search_items'       => __( 'Search Blogs' ),
        'not_found'          => __( 'No Blogs found' ),
        'not_found_in_trash' => __( 'No Blogs found in the Trash' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Blogs',
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our blogs and blog specific data',
        'public'        => true,
        'menu_position' => 6,
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
        'has_archive'   => true,
        'rewrite'       => true,
    );
    register_post_type( 'blog', $args );
    register_taxonomy(
        'blog_categories',
        'blog',
        array(
            'label' => __( 'Blog categories' ),
            'rewrite' => array( 'slug' => 'blog_categories' ),
            'hierarchical' => true,
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'show_admin_column' => true
        )
    );
    flush_rewrite_rules();
}
add_action( 'init', 'custom_post_blog' );