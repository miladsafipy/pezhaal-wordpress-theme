<?php
defined( 'ABSPATH' ) || exit;

// =================================================================
//                   Add Menu to Theme
// =================================================================

function pezhaal_theme_setup() {

    register_nav_menus(
        array(
            'primary' => 'منوی اصلی',
        )
    );
    add_theme_support('woocommerce');
    add_theme_support('post-thumbnails');

}

add_action('after_setup_theme', 'pezhaal_theme_setup');