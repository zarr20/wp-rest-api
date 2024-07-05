<?php
add_action('rest_api_init', 'register_heroslider_routes');

function register_heroslider_routes()
{
    register_rest_route('api', '/herosliders/', array(
        'methods'  => 'GET',
        'callback' => 'get_hero_sliders',
        'permission_callback' => '__return_true',
    ));
}

function get_hero_sliders($request)
{
    $lang = $request->get_param('lang');

    $args = array(
        'post_type'      => 'hero_slider',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    if ($lang && function_exists('pll_current_language')) {
        $args['lang'] = $lang;
    }

    $query = new WP_Query($args);

    $hero_sliders = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $slider_item = array(
                'ID'           => get_the_ID(),
                'title'        => get_the_title(),
                'thumbnail'    => get_field('background')['url'],
                'caption'    => get_field('caption')
            );

            $hero_sliders[] = $slider_item;
        }
    }

    wp_reset_postdata();

    $response_data = array(
        'data' => $hero_sliders,
    );

    return new WP_REST_Response($response_data, 200);
}
