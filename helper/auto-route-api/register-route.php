<?php

function register_rest_route_with_callback($namespace, $route, $callback_function, $methods = 'GET')
{
    register_rest_route($namespace, $route, array(
        'methods' => $methods,
        'callback' => function ($data) use ($callback_function) {
            return $callback_function($data);
        },
    ));
}

function create_rest_response($data, $status = 200)
{
    return new WP_REST_Response($data, $status);
}
