<?php

include('helper/auto-route-api/index.php');
include('helper/auto-route-api/register-route.php');

include('helper/herosliders/index.php');
include('helper/gallery/index.php');
include('helper/members/index.php');

include('pages/options/master/index.php');

// Register Navigation Menus
function custom_theme_setup() {
    register_nav_menus( array(
        'primary'   => __( 'Primary Menu', 'text_domain' ),
        'secondary' => __( 'Secondary Menu', 'text_domain' ),
        'footer'    => __( 'Footer Menu', 'text_domain' ),
    ) );
}
add_action( 'after_setup_theme', 'custom_theme_setup' );

