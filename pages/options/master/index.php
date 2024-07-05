<?php
// Function to register Master menu and its submenu
function register_master_menu()
{
    // Add Master menu
    add_menu_page(
        'Master',                   // Page title
        'Master',                   // Menu title
        'manage_options',           // Capability required to access this menu
        'master_menu',              // Menu slug (unique identifier)
        '__return_false',       // Callback function to render the menu
        'dashicons-admin-generic',  // Icon for the menu
        30                          // Menu position
    );

    // Add submenu for Member Setting
    // add_submenu_page(
    //     'master_menu',              // Parent menu slug
    //     'Member Setting',           // Page title
    //     'Member Setting',           // Submenu title
    //     'manage_options',           // Capability required to access this submenu
    //     'member_setting',           // Submenu slug (unique identifier)
    //     'render_member_setting'     // Callback function to render the submenu
    // );

    add_submenu_page(
        'master_menu',
        'Hero Sliders',
        'Hero Sliders',
        'manage_options',
        'edit.php?post_type=hero_slider',
    );
    add_submenu_page(
        'master_menu',
        'Photo Gallery',
        'Photo Gallery',
        'manage_options',
        'edit.php?post_type=photo',
    );
    add_submenu_page(
        'master_menu',
        'Members Logo',
        'Members Logo',
        'manage_options',
        'edit.php?post_type=member',
    );
}
add_action('admin_menu', 'register_master_menu');

function hide_master_submenu()
{
    echo '<style>
        #toplevel_page_master_menu .wp-submenu li:nth-child(2) {
            display: none;
        }
    </style>';
}
add_action('admin_head', 'hide_master_submenu');
