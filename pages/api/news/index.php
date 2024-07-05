<?php
add_action('rest_api_init', 'register_news_routes');

function register_news_routes()
{
    register_rest_route('api', '/news/', array(
        'methods'  => 'GET',
        'callback' => 'get_posts_with_pagination',
        'permission_callback' => '__return_true',
    ));
}

function get_posts_with_pagination($request)
{
    $params = $request->get_params();
    $lang = $request->get_param('lang');

    // Set default values for pagination
    $paged = isset($params['page']) ? intval($params['page']) : 1;
    $posts_per_page = isset($params['per_page']) ? intval($params['per_page']) : 10;
    $search_query = isset($params['search']) ? sanitize_text_field($params['search']) : '';
    $category = isset($params['category']) ? sanitize_text_field($params['category']) : '';

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        's'              => $search_query,
        'category_name'  => $category,
    );

    if ($lang && function_exists('pll_current_language')) {
        $args['lang'] = $lang;
    }


    $query = new WP_Query($args);

    $posts = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get the post thumbnail
            $thumbnail = '';
            if (has_post_thumbnail()) {
                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'full');
            } else {
                $thumbnail = get_first_image_from_content(get_the_content());
            }

            $post_item = array(
                'ID'           => get_the_ID(),
                'title'        => get_the_title(),
                // 'content'      => get_the_content(),
                'excerpt'      => get_the_excerpt(),
                'permalink'    => get_post()->post_name,
                'thumbnail'    => $thumbnail,
                // Add more fields as needed
            );

            $posts[] = $post_item;
        }
    }

    wp_reset_postdata();

    // Prepare response data
    $response_data = array(
        'posts' => $posts,
        'pagination' => array(
            'total_pages' => $query->max_num_pages,
            'current_page' => $paged,
            'per_page' => $posts_per_page,
        ),
    );

    return new WP_REST_Response($response_data, 200);
}

function get_first_image_from_content($content)
{
    if (preg_match_all('/<img[^>]+>/i', $content, $matches)) {
        if (isset($matches[0][0])) {
            if (preg_match('/src="([^"]+)"/i', $matches[0][0], $src)) {
                return isset($src[1]) ? $src[1] : '';
            }
        }
    }
    return '';
}
