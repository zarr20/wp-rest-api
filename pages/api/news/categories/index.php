<?php
add_action('rest_api_init', 'register_news_categories_routes');

function register_news_categories_routes()
{
    register_rest_route('api', '/news/categories', array(
        'methods'  => 'GET',
        'callback' => 'get_categories_callback',
        'permission_callback' => '__return_true',
    ));
}

function get_categories_callback($request)
{
    $default_post_type = 'post';
    $post_type = $default_post_type;

    if (!post_type_exists($post_type)) {
        return new WP_Error('invalid_post_type', 'Invalid post type.', array('status' => 400));
    }

    $categories = get_categories(array(
        'taxonomy'   => 'category',
        'object_type' => array($post_type),
    ));

    $formatted_categories = array();
    foreach ($categories as $category) {
        $category_item = array(
            'id'   => $category->term_id,
            'name' => $category->name,
            'slug' => $category->slug,
        );

        $formatted_categories[] = $category_item;
    }

    $response_data = array(
        'post_type'   => $post_type,
        'categories'  => $formatted_categories,
    );

    return new WP_REST_Response($response_data, 200);
}
