<?php
function register_photo_post_type()
{
    $labels = array(
        'name'               => __('Photos', 'text-domain'),
        'singular_name'      => __('Photo', 'text-domain'),
        'add_new'            => __('Add New Photo', 'text-domain'),
        'add_new_item'       => __('Add New Photo', 'text-domain'),
        'edit_item'          => __('Edit Photo', 'text-domain'),
        'new_item'           => __('New Photo', 'text-domain'),
        'view_item'          => __('View Photo', 'text-domain'),
        'search_items'       => __('Search Photos', 'text-domain'),
        'not_found'          => __('No Photos found', 'text-domain'),
        'not_found_in_trash' => __('No Photos found in Trash', 'text-domain'),
        'parent_item_colon'  => __('Parent Photo:', 'text-domain'),
        'menu_name'          => __('Photos', 'text-domain'),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('This is where you can manage Photos.', 'text-domain'),
        'public'             => true,
        'menu_icon'          => 'dashicons-slides', // Custom icon for the menu
        'supports'           => array('title'), // Add 'excerpt' support
        'has_archive'        => false, // Set to true if you want an archive page
        'rewrite'            => array('slug' => 'photo'), // Custom slug
        'show_in_rest'       => true, // Enable Gutenberg editor support
        'show_in_menu'       => false,
    );

    register_post_type('Photo', $args);
}
add_action('init', 'register_photo_post_type');

function custom_photo_columns($columns)
{
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title'),
        'thumbnail' => __('Thumbnail'),
        'date' => __('Date'),
    );
    return $new_columns;
}
add_filter('manage_photo_posts_columns', 'custom_photo_columns');

function custom_photo_column_data($column, $post_id)
{
    switch ($column) {
        case 'thumbnail':
            $thumbnail = get_field('images', $post_id)[0]['sizes']['thumbnail'];
            if ($thumbnail) {
                echo '<img style="width:200px" src="' . esc_url($thumbnail) . '"/>';
            } else {
                echo 'No Thumbnail';
            }
            break;
        default:
            break;
    }
}
add_action('manage_photo_posts_custom_column', 'custom_photo_column_data', 10, 2);
