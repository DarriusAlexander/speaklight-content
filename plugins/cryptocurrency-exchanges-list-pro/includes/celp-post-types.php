<?php 

    function celp_post_type() {

        $labels = array(
            'name'                  => _x( 'Crypto Exchanges', 'Post Type General Name', 'celp1' ),
            'singular_name'         => _x( 'Crypto Exchanges', 'Post Type Singular Name', 'celp1' ),
            'menu_name'             => __( 'Crypto Exchanges', 'celp1' ),
            'name_admin_bar'        => __( 'Crypto Exchanges', 'celp1' ),
            'archives'              => __( 'Item Archives', 'celp1' ),
            'attributes'            => __( 'Item Attributes', 'celp1' ),
            'parent_item_colon'     => __( 'Parent Item:', 'celp1' ),
            'all_items'             => __( 'All Descriptions', 'celp1' ),
            'add_new_item'          => __( 'Add New Description', 'celp1' ),
            'add_new'               => __( 'Add Description ', 'celp1' ),
            'new_item'              => __( 'New Item', 'celp1' ),
            'edit_item'             => __( 'Edit Item', 'celp1' ),
            'update_item'           => __( 'Update Item', 'celp1' ),
            'view_item'             => __( 'View Item', 'celp1' ),
            'view_items'            => __( 'View Items', 'celp1' ),
            'search_items'          => __( 'Search Item', 'celp1' ),
            'not_found'             => __( 'Not found', 'celp1' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'celp1' ),
            'featured_image'        => __( 'Featured Image', 'celp1' ),
            'set_featured_image'    => __( 'Set featured image', 'celp1' ),
            'remove_featured_image' => __( 'Remove featured image', 'celp1' ),
            'use_featured_image'    => __( 'Use as featured image', 'celp1' ),
            'insert_into_item'      => __( 'Insert into item', 'celp1' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'celp1' ),
            'items_list'            => __( 'Items list', 'celp1' ),
            'items_list_navigation' => __( 'Items list navigation', 'celp1' ),
            'filter_items_list'     => __( 'Filter items list', 'celp1' ),

        );
        $args = array(
            'label'                 => __( 'Exchange Description', 'celp1' ),
            'description'           => __( 'Post Type Description', 'celp1' ),
            'labels'                => $labels,
            'supports'              => array( 'title' ),
            'taxonomies'            => array(''),
            'hierarchical'          => false,
            'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
            'show_ui'               => true,
            'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
            'menu_position'         =>20,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive' => false,  // it shouldn't have archive page
            'rewrite' => false,  // it shouldn't have rewrite rules
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'menu_icon'           =>CELP_URL.'/assets/celp-icon.png',
            'capability_type'       => 'page',

        );
        register_post_type( 'celp', $args );


}
    

  
