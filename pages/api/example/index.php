<?php
add_action('rest_api_init', 'register_example_routes');

function register_example_routes()
{
    register_rest_route_with_callback('api', '/example/', function ($data) {
        return create_rest_response('Example');
    });
}