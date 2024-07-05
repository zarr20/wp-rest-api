<?php
add_action('rest_api_init', 'register_languages_routes');

function register_languages_routes()
{
    register_rest_route('api', '/languages', array(
        'methods' => 'GET',
        'callback' => 'get_languages',
        'permission_callback' => '__return_true',
    ));
}

function get_languages()
{
    if (function_exists('pll_the_languages')) {
        $languages = pll_the_languages(array('dropdown' => 1, 'hide_current' => 0, 'raw' => 1));

        $langs_array = array();
        foreach ($languages as $lang) {
            $langs_array[] = array(
                'flag' => $lang['flag'],
                'slug' => $lang['slug'],
            );
        }

        $response_data = array(
            'data' => $langs_array,
        );

        return new WP_REST_Response($response_data, 200);
    } else {
        $response_data = array(
            'error' => 'Polylang plugin is not active or installed.',
        );

        return new WP_REST_Response($response_data, 500);
    }
}
?>
