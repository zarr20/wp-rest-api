<?php
add_action('rest_api_init', 'register_gallery_routes');

function register_gallery_routes()
{
    register_rest_route('api', '/photos/', array(
        'methods'  => 'GET',
        'callback' => 'get_photos',
        'permission_callback' => '__return_true',
    ));
}

function get_photos()
{

    $args = array(
        'post_type'      => 'photo',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    $photos = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $slider_item = array(
                'ID'           => get_the_ID(),
                'title'        => get_the_title(),
                'images'       => get_field('images'),
                'captions'     => get_field('captions')
            );

            $photos[] = $slider_item;
        }
    }

    wp_reset_postdata();

    $response_data = array(
        'data' => $photos,
    );

    return new WP_REST_Response($response_data, 200);
}
