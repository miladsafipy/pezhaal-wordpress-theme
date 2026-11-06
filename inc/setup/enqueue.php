<?php
defined( 'ABSPATH' ) || exit;

// Use main.css and main.js for styling and scripts

/**
 * =========================================================
 * PEZHAAL ASSET SYSTEM
 * =========================================================
 */


/**
 * Enqueue one CSS file with automatic cache busting.
 */
function pezhaal_enqueue_css($handle, $relative_path, $dependencies = array()) {

    $theme_uri  = get_template_directory_uri();
    $theme_path = get_template_directory();

    $file_path = $theme_path . $relative_path;

    if (!file_exists($file_path)) {
        return false;
    }

    wp_enqueue_style(
        $handle,
        $theme_uri . $relative_path,
        $dependencies,
        filemtime($file_path)
    );

    return true;
}


/**
 * Automatically enqueue all CSS files inside a directory.
 */
function pezhaal_enqueue_css_directory(
    $directory,
    $prefix,
    $dependencies = array(),
    $exclude = array()
) {

    $theme_path = get_template_directory();
    $directory_path = $theme_path . $directory;

    if (!is_dir($directory_path)) {
        return $dependencies;
    }

    $files = glob($directory_path . '/*.css');

    if (!$files) {
        return $dependencies;
    }

    sort($files);

    foreach ($files as $file_path) {

        $filename = basename($file_path, '.css');

        if (in_array($filename, $exclude, true)) {
            continue;
        }

        $handle = sanitize_title(
            $prefix . '-' . $filename
        );

        $relative_path =
            $directory . '/' . basename($file_path);

        pezhaal_enqueue_css(
            $handle,
            $relative_path,
            $dependencies
        );

        $dependencies = array($handle);
    }

    return $dependencies;
}


/**
 * =========================================================
 * MAIN ASSET LOADER
 * =========================================================
 */
function pezhaal_enqueue_assets() {


    /*
     * -----------------------------------------------------
     * DESIGN SYSTEM
     * -----------------------------------------------------
     */

    $design_dependencies = array();

    /*
     * 1. Variables
     */
    if (
        pezhaal_enqueue_css(
            'pezhaal-variables',
            '/assets/css/design-system/variables.css'
        )
    ) {
        $design_dependencies = array(
            'pezhaal-variables'
        );
    }


    /*
     * 2. Other Design System files
     */
    $design_dependencies = pezhaal_enqueue_css_directory(
        '/assets/css/design-system',
        'pezhaal-design',
        $design_dependencies,
        array('variables')
    );


    /*
     * -----------------------------------------------------
     * MAIN CSS
     * -----------------------------------------------------
     */

    pezhaal_enqueue_css(
        'pezhaal-main',
        '/assets/css/main.css',
        $design_dependencies
    );


    /*
     * -----------------------------------------------------
     * COMPONENTS
     * -----------------------------------------------------
     */

    $component_dependencies = array(
        'pezhaal-main'
    );

    $component_dependencies = pezhaal_enqueue_css_directory(
        '/assets/css/components-style',
        'pezhaal-component',
        $component_dependencies
    );


    /*
     * -----------------------------------------------------
     * PAGE STYLES
     * -----------------------------------------------------
     */

    $page_styles = array(

        /*
        * -----------------------------------------------------
        * HOME
        * -----------------------------------------------------
        */
        'front-page' => function () {
            return is_front_page();
        },


        /*
        * -----------------------------------------------------
        * WOOCOMMERCE
        * -----------------------------------------------------
        */

        'products' => function () {
            return function_exists('is_shop') && is_shop();
        },

        'product-detail' => function () {
            return function_exists('is_product') && is_product();
        },

        'cart' => function () {
            return function_exists('is_cart') && is_cart();
        },

        'checkout' => function () {
            return function_exists('is_checkout') && is_checkout();
        },

        'my-account' => function () {
            return function_exists('is_account_page') && is_account_page();
        },


        /*
        * -----------------------------------------------------
        * NORMAL PAGES
        * -----------------------------------------------------
        */

        'about-us' => function () {
            return is_page('about-us');
        },

        'contact-us' => function () {
            return is_page('contact-us');
        },

    );


    foreach ($page_styles as $filename => $condition) {

        $relative_path =
            '/assets/css/pages-style/'
            . $filename
            . '.css';

        $file_path =
            get_template_directory()
            . $relative_path;

        if (!file_exists($file_path)) {
            continue;
        }

        if ($condition()) {

            pezhaal_enqueue_css(
                'pezhaal-page-' . $filename,
                $relative_path,
                $component_dependencies
            );
        }
    }


    /*
     * -----------------------------------------------------
     * JAVASCRIPT
     * -----------------------------------------------------
     */
    $js_relative_path = '/assets/js/main.js';

    $js_path =
        get_template_directory()
        . $js_relative_path;

    if (file_exists($js_path)) {

        wp_enqueue_script(
            'pezhaal-main-script',
            get_template_directory_uri() . $js_relative_path,
            array(),
            filemtime($js_path),
            true
        );
    }


    /* ==============================
    Hero Slider
    ============================== */

    $hero_js_relative_path = '/assets/js/hero.js';

    $hero_js_path =
        get_template_directory()
        . $hero_js_relative_path;

    if (file_exists($hero_js_path)) {

        wp_enqueue_script(
            'pezhaal-hero-script',
            get_template_directory_uri() . $hero_js_relative_path,
            array(),
            filemtime($hero_js_path),
            true
        );
    }

    /* ==============================
        Carousel Gallery
    ============================== */

    $hero_js_relative_path = '/assets/js/carousel.js';

    $hero_js_path =
        get_template_directory()
        . $hero_js_relative_path;

    if (file_exists($hero_js_path)) {

        wp_enqueue_script(
            'pezhaal-carousel-script',
            get_template_directory_uri() . $hero_js_relative_path,
            array(),
            filemtime($hero_js_path),
            true
        );
    }


    /* ==============================
        Review Carts
    ============================== */

    $hero_js_relative_path = '/assets/js/product-reviews.js';

    $hero_js_path =
        get_template_directory()
        . $hero_js_relative_path;

    if (file_exists($hero_js_path)) {

        wp_enqueue_script(
            'pezhaal-product-reviews-script',
            get_template_directory_uri() . $hero_js_relative_path,
            array(),
            filemtime($hero_js_path),
            true
        );
    }

    /* ==============================
                LOGIN FORM
    ============================== */

    $hero_js_relative_path = '/assets/js/login.js';

    $hero_js_path =
        get_template_directory()
        . $hero_js_relative_path;

    if (file_exists($hero_js_path)) {

        wp_enqueue_script(
            'pezhaal-login-script',
            get_template_directory_uri() . $hero_js_relative_path,
            array(),
            filemtime($hero_js_path),
            true
        );
    }
}

add_action(
    'wp_enqueue_scripts',
    'pezhaal_enqueue_assets'
);