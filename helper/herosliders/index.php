<?php
function register_hero_slider_post_type()
{
    $labels = array(
        'name'               => __('Hero Sliders', 'text-domain'),
        'singular_name'      => __('Hero Slider', 'text-domain'),
        'add_new'            => __('Add New Slider', 'text-domain'),
        'add_new_item'       => __('Add New Slider', 'text-domain'),
        'edit_item'          => __('Edit Slider', 'text-domain'),
        'new_item'           => __('New Slider', 'text-domain'),
        'view_item'          => __('View Slider', 'text-domain'),
        'search_items'       => __('Search Hero Sliders', 'text-domain'),
        'not_found'          => __('No sliders found', 'text-domain'),
        'not_found_in_trash' => __('No sliders found in Trash', 'text-domain'),
        'parent_item_colon'  => __('Parent Slider:', 'text-domain'),
        'menu_name'          => __('Hero Sliders', 'text-domain'),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('This is where you can manage hero sliders.', 'text-domain'),
        'public'             => true,
        'menu_icon'          => 'dashicons-slides',
        'supports'           => array('title', 'thumbnail', 'excerpt'), // Add 'excerpt' support
        'has_archive'        => false, // Set to true if you want an archive page
        'rewrite'            => array('slug' => 'hero-slider'), // Custom slug
        'show_in_rest'       => true, // Enable Gutenberg editor support
        // 'show_ui'            => false, 
        'show_in_menu'       => false,
    );

    register_post_type('hero_slider', $args);
}
add_action('init', 'register_hero_slider_post_type');

function custom_hero_slider_columns($columns)
{
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __('Title'),
        'thumbnail' => __('Thumbnail'),
        'date' => __('Date'),
    );
    return $new_columns;
}
add_filter('manage_hero_slider_posts_columns', 'custom_hero_slider_columns');

function custom_hero_slider_column_data($column, $post_id)
{
    switch ($column) {
        case 'thumbnail':
            $thumbnail = get_field('background', $post_id)['sizes']['thumbnail'];
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
add_action('manage_hero_slider_posts_custom_column', 'custom_hero_slider_column_data', 10, 2);
