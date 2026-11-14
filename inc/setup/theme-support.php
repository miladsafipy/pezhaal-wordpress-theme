<?php
defined( 'ABSPATH' ) || exit;

// =================================================================
//                   Add logo image to Theme
// =================================================================
function pezhaal_customize_register($wp_customize) {

    $wp_customize->add_section('pezhaal_header', array(
        'title'    => 'Header Settings',
        'priority' => 30,
    ));

    $wp_customize->add_setting('pezhaal_logo', array(
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'pezhaal_logo',
            array(
                'label'     => 'Logo',
                'section'   => 'pezhaal_header',
                'mime_type' => 'image',
            )
        )
    );
}

add_action('customize_register', 'pezhaal_customize_register');


// =================================================================
//                   Lucide Icons for Theme
// =================================================================
function pezhaal_enqueue_lucide() {

    wp_enqueue_script(
        'lucide',
        'https://unpkg.com/lucide@latest',
        array(),
        null,
        true
    );

    wp_add_inline_script(
        'lucide',
        'lucide.createIcons();'
    );
}

add_action('wp_enqueue_scripts', 'pezhaal_enqueue_lucide');





// =====================================================
//               تعداد محصولات در هر صفحه
// =====================================================
add_filter( 'loop_shop_per_page', function ( $products_per_page ) {
    return 9;
}, 20 );



