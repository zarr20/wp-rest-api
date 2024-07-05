<?php
function register_member_post_type()
{
    $labels = array(
        'name'               => __('Members', 'text-domain'),
        'singular_name'      => __('Member', 'text-domain'),
        'add_new'            => __('Add New Member', 'text-domain'),
        'add_new_item'       => __('Add New Member', 'text-domain'),
        'edit_item'          => __('Edit Member', 'text-domain'),
        'new_item'           => __('New Member', 'text-domain'),
        'view_item'          => __('View Member', 'text-domain'),
        'search_items'       => __('Search Members', 'text-domain'),
        'not_found'          => __('No Members found', 'text-domain'),
        'not_found_in_trash' => __('No Members found in Trash', 'text-domain'),
        'parent_item_colon'  => __('Parent Member:', 'text-domain'),
        'menu_name'          => __('Members', 'text-domain'),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('This is where you can manage members.', 'text-domain'),
        'public'             => true,
        'menu_icon'          => 'dashicons-slides',
        'supports'           => array('title', 'thumbnail', 'excerpt'), // Add 'excerpt' support
        'has_archive'        => false, // Set to true if you want an archive page
        'rewrite'            => array('slug' => 'hero-Member'), // Custom slug
        'show_in_rest'       => true, // Enable Gutenberg editor support
        // 'show_ui'            => false, 
        // 'show_in_menu'       => false,
    );

    register_post_type('member', $args);
}
add_action('init', 'register_member_post_type');

function custom_member_columns($columns)
{
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'thumbnail' => __('Thumbnail'),
        'title' => __('Title'),
        'date' => __('Date'),
    );
    return $new_columns;
}
add_filter('manage_member_posts_columns', 'custom_member_columns');

function custom_member_column_data($column, $post_id)
{
    switch ($column) {
        case 'thumbnail':
            $thumbnail = get_field('logo', $post_id);
            if ($thumbnail) {
                echo '<img src="' . esc_url($thumbnail) . '"/>';
            } else {
                echo 'No Thumbnail';
            }
            break;
        default:
            break;
    }
}
add_action('manage_member_posts_custom_column', 'custom_member_column_data', 10, 2);
