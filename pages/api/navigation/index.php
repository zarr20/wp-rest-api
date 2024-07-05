<?php
add_action('rest_api_init', 'register_navigation_routes');

function register_navigation_routes()
{
    register_rest_route('api', '/navigation/', array(
        'methods'  => 'GET',
        'callback' => 'get_primary_menu_items',
        'permission_callback' => '__return_true',
    ));
}

function get_primary_menu_items()
{
    // Get the menu by location
    $locations = get_nav_menu_locations();
    $menu_id = isset($locations['primary']) ? $locations['primary'] : 0;

    // Check if the menu exists
    if (!$menu_id) {
        return new WP_Error('no_menu', 'Primary menu not found', array('status' => 404));
    }

    // Get the menu items
    $menu_items = wp_get_nav_menu_items($menu_id);

    // If there are no menu items, return an error
    if (empty($menu_items)) {
        return new WP_Error('no_items', 'No items in the primary menu', array('status' => 404));
    }

    // Format the menu items for the response
    $formatted_items = array();
    $items_by_parent = array();

    // Organize menu items by their parent ID
    foreach ($menu_items as $item) {
        $items_by_parent[$item->menu_item_parent][] = $item;
    }

    // Function to build the tree
    function build_menu_tree($parent_id, $items_by_parent)
    {
        $menu_tree = array();

        if (!isset($items_by_parent[$parent_id])) {
            return $menu_tree;
        }

        foreach ($items_by_parent[$parent_id] as $item) {
            $menu_node = array(
                'ID'         => $item->ID,
                'title'      => $item->title,
                'url'        => $item->url,
                'menu_order' => $item->menu_order,
            );

            // Check if the item has children and build the submenu
            $submenu = build_menu_tree($item->ID, $items_by_parent);
            if (!empty($submenu)) {
                $menu_node['submenu'] = $submenu;
            }

            $menu_tree[] = $menu_node;
        }

        return $menu_tree;
    }

    $formatted_items = build_menu_tree(0, $items_by_parent);

    return new WP_REST_Response($formatted_items, 200);
}
