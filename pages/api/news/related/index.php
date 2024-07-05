<?php
add_action('rest_api_init', 'register_news_related_routes');

function register_news_related_routes()
{
    register_rest_route('api', '/news/related/(?P<slug>[a-zA-Z0-9-]+)', array(
        'methods'  => 'GET',
        'callback' => 'get_related_posts',
        'permission_callback' => '__return_true',
    ));
}

function get_related_posts($request)
{
    $slug = $request['slug'];
    $lang = $request->get_param('lang');
    $post_id = url_to_postid($slug);

    if (!$post_id || is_wp_error($post_id)) {
        return new WP_Error('invalid_post_slug', 'Invalid post slug', array('status' => 404));
    }

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'orderby'        => 'rand', // Order by random to get different posts on each load
        'post__not_in'   => array($post_id), // Exclude current post
        'tax_query'      => array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => wp_get_post_categories($post_id),
            ),
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => wp_get_post_tags($post_id),
            ),
        ),
    );

    if ($lang && function_exists('pll_current_language')) {
        $args['lang'] = $lang;
    }

    $query = new WP_Query($args);

    $related_posts = array();
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

            $related_post_item = array(
                'ID'           => get_the_ID(),
                'title'        => get_the_title(),
                // 'content'      => get_the_content(),
                'excerpt'      => get_the_excerpt(),
                'permalink'    => get_post()->post_name,
                'thumbnail'    => $thumbnail,
            );

            $related_posts[] = $related_post_item;
        }
    }

    wp_reset_postdata();

    $response_data = array(
        'posts' => $related_posts,
        // 'pagination' => array(
        //     'total_pages' => $query->max_num_pages,
        //     'current_page' => $paged,
        //     'per_page' => $posts_per_page,
        // ),
    );

    return new WP_REST_Response($response_data, 200);
}
