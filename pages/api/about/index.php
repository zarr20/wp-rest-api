<?php
add_action('rest_api_init', 'register_about_routes');

function register_about_routes()
{
    register_rest_route('api', '/about/', array(
        'methods'  => 'GET',
        'callback' => 'handle_about_request',
        'permission_callback' => '__return_true',
    ));
}

function handle_about_request($request)
{
    // $lang = $request->get_param('lang');
    // sudah otomatis di translate pake plugin acf-options-for-polylang

    $title = get_field('about_title', 'option');
    $subtitle = get_field('about_sub_title', 'option');
    $content = get_field('about_content', 'option');
    $thumbnail = get_field('about_thumbnail', 'option');

    if (empty($title) || empty($content)) {
        return new WP_REST_Response(array('message' => 'No content found'), 404);
    }

    return new WP_REST_Response(array(
        // 'lang'=> $request->get_param('lang'),
        'title' => $title,
        'subtitle' => $subtitle,
        'content' => $content,
        'thumbnail' => $thumbnail
    ), 200);
}
