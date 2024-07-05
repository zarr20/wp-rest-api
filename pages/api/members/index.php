<?php
add_action('rest_api_init', 'register_members_routes');

function register_members_routes()
{
    register_rest_route('api', '/members/', array(
        'methods'  => 'GET',
        'callback' => 'get_members',
        'permission_callback' => '__return_true',
    ));
}

function get_members($request)
{
    $args = array(
        'post_type'      => 'member',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    $members = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $item = array(
                'title'         => get_the_title(),
                'logo'          => get_field('logo'),
                'redirect'      => get_field('redirect')
            );

            $members[] = $item;
        }
    }

    wp_reset_postdata();

    $response_data = array(
        'data' => $members,
    );

    return new WP_REST_Response($response_data, 200);
}
