<?php

add_action('rest_api_init', 'register_news_details_routes');

function register_news_details_routes()
{
    register_rest_route('api', '/news/(?P<slug>[a-zA-Z0-9-]+)', array(
        'methods'  => 'GET',
        'callback' => 'get_news_details_by_slug',
        'permission_callback' => '__return_true',
    ));
}

function get_news_details_by_slug($request)
{
    $slug = $request->get_param('slug');
    $lang = $request->get_param('lang');
    $args = array(
        'name'           => $slug,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'numberposts'    => 1,
    );
    $posts = get_posts($args);
    if (empty($posts)) {
        return new WP_REST_Response(array('message' => 'Post not found'), 404);
    }
    $post = $posts[0];
    $translations = pll_get_post_translations($post->ID);
    if (empty($translations[$lang])) {
        return new WP_REST_Response(array('message' => 'Translation not available for this language'), 404);
    }
    $post_id = $translations[$lang];
    $post = get_post($post_id);
    $post_data = array(
        'ID'           => $post->ID,
        'title'        => get_the_title($post->ID),
        'content'      => apply_filters('the_content', $post->post_content),
        'permalink'    => get_permalink($post->ID),
    );
    return new WP_REST_Response($post_data, 200);
}
