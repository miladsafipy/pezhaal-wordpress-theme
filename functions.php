<?php


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

}

add_action('after_setup_theme', 'pezhaal_theme_setup');


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





// =================================================================
//                   Add Hero
// =================================================================
function pezhaal_register_hero_post_type() {

    $labels = array(
        'name'               => 'اسلایدهای هیرو',
        'singular_name'      => 'اسلاید هیرو',
        'menu_name'          => 'اسلایدهای هیرو',
        'add_new'            => 'افزودن اسلاید',
        'add_new_item'       => 'افزودن اسلاید جدید',
        'edit_item'          => 'ویرایش اسلاید',
        'new_item'           => 'اسلاید جدید',
        'view_item'          => 'مشاهده اسلاید',
        'search_items'       => 'جستجوی اسلایدها',
        'not_found'          => 'اسلایدی پیدا نشد',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-images-alt2',
        'supports'           => array('title', 'thumbnail'),
        'show_in_rest'       => true,
    );

    register_post_type('hero_slide', $args);
}

add_action('init', 'pezhaal_register_hero_post_type');


function pezhaal_hero_meta_box() {

    add_meta_box(
        'pezhaal_hero_details',
        'اطلاعات اسلاید هیرو',
        'pezhaal_hero_meta_box_callback',
        'hero_slide',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'pezhaal_hero_meta_box');


function pezhaal_hero_meta_box_callback($post) {

    wp_nonce_field(
        'pezhaal_save_hero',
        'pezhaal_hero_nonce'
    );

    $description = get_post_meta(
        $post->ID,
        '_hero_description',
        true
    );

    $button_text = get_post_meta(
        $post->ID,
        '_hero_button_text',
        true
    );

    $button_url = get_post_meta(
        $post->ID,
        '_hero_button_url',
        true
    );
    ?>

    <p>
        <label for="hero_description">
            توضیح هیرو
        </label>
    </p>

    <textarea
        id="hero_description"
        name="hero_description"
        rows="4"
        style="width:100%;"
    ><?php echo esc_textarea($description); ?></textarea>


    <p>
        <label for="hero_button_text">
            متن دکمه
        </label>
    </p>

    <input
        type="text"
        id="hero_button_text"
        name="hero_button_text"
        value="<?php echo esc_attr($button_text); ?>"
        style="width:100%;"
    >


    <p>
        <label for="hero_button_url">
            لینک دکمه
        </label>
    </p>

    <input
        type="url"
        id="hero_button_url"
        name="hero_button_url"
        value="<?php echo esc_url($button_url); ?>"
        style="width:100%;"
    >

    <p>
        <strong>
            تصویر هیرو:
        </strong>
        تصویر شاخص این اسلاید را انتخاب کنید.
    </p>

    <?php
}


function pezhaal_save_hero_meta($post_id) {

    if (
        !isset($_POST['pezhaal_hero_nonce']) ||
        !wp_verify_nonce(
            $_POST['pezhaal_hero_nonce'],
            'pezhaal_save_hero'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }


    if (isset($_POST['hero_description'])) {

        update_post_meta(
            $post_id,
            '_hero_description',
            sanitize_textarea_field(
                $_POST['hero_description']
            )
        );
    }


    if (isset($_POST['hero_button_text'])) {

        update_post_meta(
            $post_id,
            '_hero_button_text',
            sanitize_text_field(
                $_POST['hero_button_text']
            )
        );
    }


    if (isset($_POST['hero_button_url'])) {

        update_post_meta(
            $post_id,
            '_hero_button_url',
            esc_url_raw(
                $_POST['hero_button_url']
            )
        );
    }
}

add_action(
    'save_post_hero_slide',
    'pezhaal_save_hero_meta'
);









/* =========================================
   Pezhaal Footer Settings
========================================= */

/**
 * گزینه‌های آیکون شبکه‌های اجتماعی
 */
function pezhaal_footer_social_icons() {
    return array(
        'instagram' => 'اینستاگرام',
        'telegram'  => 'تلگرام',
        'pinterest' => 'پینترست',
        'whatsapp'  => 'واتساپ',
        'youtube'   => 'یوتیوب',
        'facebook'  => 'فیسبوک',
    );
}

/**
 * ثبت صفحه تنظیمات فوتر
 */
function pezhaal_footer_register_settings() {

    register_setting(
        'pezhaal_footer_group',
        'pezhaal_footer_settings',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'pezhaal_footer_sanitize_settings',
            'default'           => array(),
        )
    );

    add_settings_section(
        'pezhaal_footer_main_section',
        'محتوای فوتر فروشگاه',
        '__return_false',
        'pezhaal-footer'
    );

    add_settings_field(
        'pezhaal_footer_fields',
        'تنظیمات فوتر',
        'pezhaal_footer_render_fields',
        'pezhaal-footer',
        'pezhaal_footer_main_section'
    );
}
add_action('admin_init', 'pezhaal_footer_register_settings');


/**
 * پاک‌سازی و اعتبارسنجی داده‌ها
 */
function pezhaal_footer_sanitize_settings($input) {

    $input = is_array($input) ? $input : array();

    $clean = array();

    $clean['brand'] = sanitize_text_field(
        $input['brand'] ?? 'پژال'
    );

    $clean['trust_text'] = sanitize_text_field(
        $input['trust_text'] ?? ''
    );

    $clean['copyright'] = sanitize_text_field(
        $input['copyright'] ?? ''
    );

    // لینک‌های دسترسی سریع
    $clean['links'] = array();

    foreach (($input['links'] ?? array()) as $item) {

        if (!is_array($item)) {
            continue;
        }

        $label = sanitize_text_field($item['label'] ?? '');
        $url   = esc_url_raw($item['url'] ?? '');

        if ($label !== '' && $url !== '') {
            $clean['links'][] = array(
                'label' => $label,
                'url'   => $url,
            );
        }
    }

    // شبکه‌های اجتماعی
    $clean['socials'] = array();

    $allowed_icons = pezhaal_footer_social_icons();

    foreach (($input['socials'] ?? array()) as $item) {

        if (!is_array($item)) {
            continue;
        }

        $name = sanitize_text_field($item['name'] ?? '');
        $url  = esc_url_raw($item['url'] ?? '');
        $icon = sanitize_key($item['icon'] ?? '');

        if (
            $name !== '' &&
            $url !== '' &&
            isset($allowed_icons[$icon])
        ) {
            $clean['socials'][] = array(
                'name' => $name,
                'url'  => $url,
                'icon' => $icon,
            );
        }
    }

    // تصاویر مجوزها و پرداخت
    $clean['badges'] = array();

    foreach (($input['badges'] ?? array()) as $item) {

        if (!is_array($item)) {
            continue;
        }

        $image_id = absint($item['image_id'] ?? 0);

        if ($image_id > 0 && wp_attachment_is_image($image_id)) {

            $clean['badges'][] = array(
                'image_id' => $image_id,
                'alt'      => sanitize_text_field($item['alt'] ?? ''),
                'url'      => esc_url_raw($item['url'] ?? ''),
            );
        }
    }

    return $clean;
}


/**
 * افزودن منو به پیشخوان
 */
function pezhaal_footer_add_admin_menu() {

    add_theme_page(
        'تنظیمات فوتر پژال',
        'تنظیمات فوتر پژال',
        'edit_theme_options',
        'pezhaal-footer',
        'pezhaal_footer_settings_page'
    );
}
add_action('admin_menu', 'pezhaal_footer_add_admin_menu');


/**
 * نمایش صفحه تنظیمات
 */
function pezhaal_footer_settings_page() {

    if (!current_user_can('edit_theme_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>تنظیمات فوتر پژال</h1>

        <p>
            تمام اطلاعات این بخش در فوتر سایت نمایش داده می‌شوند.
        </p>

        <form method="post" action="options.php">

            <?php
            settings_fields('pezhaal_footer_group');
            do_settings_sections('pezhaal-footer');
            submit_button('ذخیره تنظیمات فوتر');
            ?>

        </form>
    </div>
    <?php
}


/**
 * نمایش فیلدهای تنظیمات
 */
function pezhaal_footer_render_fields() {

    $settings = get_option('pezhaal_footer_settings', array());

    $brand       = $settings['brand'] ?? 'پژال';
    $trust_text  = $settings['trust_text'] ?? 'ایمن و با اطمینان از پژال خرید کنید';
    $copyright   = $settings['copyright'] ?? 'تمامی حقوق این فروشگاه محفوظ می‌باشد';
    $links       = $settings['links'] ?? array();
    $socials     = $settings['socials'] ?? array();
    $badges      = $settings['badges'] ?? array();

    $icons = pezhaal_footer_social_icons();

    $base = 'pezhaal_footer_settings';
    ?>

    <style>
        .pz-footer-admin-group {
            max-width: 900px;
            margin: 24px 0;
            padding: 20px;
            background: #fff;
            border: 1px solid #dcdcde;
            border-radius: 8px;
        }

        .pz-footer-admin-group h2 {
            margin-top: 0;
        }

        .pz-footer-admin-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            align-items: end;
            padding: 14px 0;
            border-bottom: 1px solid #eee;
        }

        .pz-footer-admin-row label {
            display: block;
            margin-bottom: 6px;
        }

        .pz-footer-admin-row input,
        .pz-footer-admin-row select {
            width: 100%;
        }

        .pz-footer-admin-row .pz-footer-image-preview img {
            display: block;
            max-width: 100px;
            max-height: 80px;
            object-fit: contain;
            margin: 10px 0;
        }

        .pz-footer-admin-remove {
            color: #b32d2e !important;
        }

        @media (max-width: 700px) {
            .pz-footer-admin-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="pz-footer-admin-group">
        <h2>۱. نام برند</h2>

        <label for="pz-footer-brand">نام نمایش داده‌شده</label>

        <input
            id="pz-footer-brand"
            class="regular-text"
            type="text"
            name="<?php echo esc_attr($base); ?>[brand]"
            value="<?php echo esc_attr($brand); ?>"
        >
    </div>


    <div class="pz-footer-admin-group">
        <h2>۲. لینک‌های دسترسی سریع</h2>

        <p>عنوان و نشانی هر لینک را وارد کن.</p>

        <div id="pz-footer-links">

            <?php foreach ($links as $i => $item) : ?>
                <div class="pz-footer-admin-row">

                    <div>
                        <label>عنوان لینک</label>
                        <input
                            type="text"
                            name="<?php echo esc_attr($base); ?>[links][<?php echo esc_attr($i); ?>][label]"
                            value="<?php echo esc_attr($item['label'] ?? ''); ?>"
                        >
                    </div>

                    <div>
                        <label>آدرس لینک</label>
                        <input
                            type="url"
                            dir="ltr"
                            name="<?php echo esc_attr($base); ?>[links][<?php echo esc_attr($i); ?>][url]"
                            value="<?php echo esc_attr($item['url'] ?? ''); ?>"
                            placeholder="https://example.com/contact"
                        >
                    </div>

                    <button type="button" class="button pz-footer-admin-remove">
                        حذف این لینک
                    </button>

                </div>
            <?php endforeach; ?>

        </div>

        <button type="button" class="button" id="pz-footer-add-link">
            + افزودن لینک
        </button>
    </div>


    <div class="pz-footer-admin-group">
        <h2>۳. شبکه‌های اجتماعی</h2>

        <div id="pz-footer-socials">

            <?php foreach ($socials as $i => $item) : ?>
                <div class="pz-footer-admin-row">

                    <div>
                        <label>نام شبکه</label>
                        <input
                            type="text"
                            name="<?php echo esc_attr($base); ?>[socials][<?php echo esc_attr($i); ?>][name]"
                            value="<?php echo esc_attr($item['name'] ?? ''); ?>"
                            placeholder="مثلاً اینستاگرام"
                        >
                    </div>

                    <div>
                        <label>آدرس صفحه</label>
                        <input
                            type="url"
                            dir="ltr"
                            name="<?php echo esc_attr($base); ?>[socials][<?php echo esc_attr($i); ?>][url]"
                            value="<?php echo esc_attr($item['url'] ?? ''); ?>"
                        >
                    </div>

                    <div>
                        <label>آیکون</label>
                        <select
                            name="<?php echo esc_attr($base); ?>[socials][<?php echo esc_attr($i); ?>][icon]"
                        >
                            <?php foreach ($icons as $key => $title) : ?>
                                <option
                                    value="<?php echo esc_attr($key); ?>"
                                    <?php selected($item['icon'] ?? '', $key); ?>
                                >
                                    <?php echo esc_html($title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="button" class="button pz-footer-admin-remove">
                        حذف شبکه
                    </button>

                </div>
            <?php endforeach; ?>

        </div>

        <button type="button" class="button" id="pz-footer-add-social">
            + افزودن شبکه اجتماعی
        </button>
    </div>


    <div class="pz-footer-admin-group">
        <h2>۴. متن اعتماد و تصاویر</h2>

        <label>متن بالای تصاویر</label>

        <input
            class="regular-text"
            type="text"
            name="<?php echo esc_attr($base); ?>[trust_text]"
            value="<?php echo esc_attr($trust_text); ?>"
        >

        <p>تصاویر از کتابخانه رسانه وردپرس انتخاب می‌شوند.</p>

        <div id="pz-footer-badges">

            <?php foreach ($badges as $i => $item) :
                $image_id = absint($item['image_id'] ?? 0);
                $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';
            ?>
                <div class="pz-footer-admin-row">

                    <div>
                        <label>تصویر</label>

                        <input
                            type="hidden"
                            class="pz-footer-image-id"
                            name="<?php echo esc_attr($base); ?>[badges][<?php echo esc_attr($i); ?>][image_id]"
                            value="<?php echo esc_attr($image_id); ?>"
                        >

                        <div class="pz-footer-image-preview">
                            <?php if ($image_url) : ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="">
                            <?php endif; ?>
                        </div>

                        <button type="button" class="button pz-footer-select-image">
                            انتخاب تصویر
                        </button>
                    </div>

                    <div>
                        <label>متن جایگزین تصویر</label>

                        <input
                            type="text"
                            name="<?php echo esc_attr($base); ?>[badges][<?php echo esc_attr($i); ?>][alt]"
                            value="<?php echo esc_attr($item['alt'] ?? ''); ?>"
                            placeholder="مثلاً نماد اعتماد"
                        >

                        <label>لینک اختیاری تصویر</label>

                        <input
                            type="url"
                            dir="ltr"
                            name="<?php echo esc_attr($base); ?>[badges][<?php echo esc_attr($i); ?>][url]"
                            value="<?php echo esc_attr($item['url'] ?? ''); ?>"
                            placeholder="https://example.com"
                        >
                    </div>

                    <button type="button" class="button pz-footer-admin-remove">
                        حذف تصویر
                    </button>

                </div>
            <?php endforeach; ?>

        </div>

        <button type="button" class="button" id="pz-footer-add-badge">
            + افزودن تصویر
        </button>
    </div>


    <div class="pz-footer-admin-group">
        <h2>۵. متن انتهایی فوتر</h2>

        <label>متن حقوق سایت</label>

        <input
            class="regular-text"
            type="text"
            name="<?php echo esc_attr($base); ?>[copyright]"
            value="<?php echo esc_attr($copyright); ?>"
        >
    </div>


    <script type="text/html" id="tmpl-pz-footer-link">
        <div class="pz-footer-admin-row">
            <div>
                <label>عنوان لینک</label>
                <input type="text" name="<?php echo esc_attr($base); ?>[links][__INDEX__][label]">
            </div>
            <div>
                <label>آدرس لینک</label>
                <input type="url" dir="ltr" name="<?php echo esc_attr($base); ?>[links][__INDEX__][url]">
            </div>
            <button type="button" class="button pz-footer-admin-remove">حذف این لینک</button>
        </div>
    </script>


    <script type="text/html" id="tmpl-pz-footer-social">
        <div class="pz-footer-admin-row">
            <div>
                <label>نام شبکه</label>
                <input type="text" name="<?php echo esc_attr($base); ?>[socials][__INDEX__][name]">
            </div>
            <div>
                <label>آدرس صفحه</label>
                <input type="url" dir="ltr" name="<?php echo esc_attr($base); ?>[socials][__INDEX__][url]">
            </div>
            <div>
                <label>آیکون</label>
                <select name="<?php echo esc_attr($base); ?>[socials][__INDEX__][icon]">
                    <?php foreach ($icons as $key => $title) : ?>
                        <option value="<?php echo esc_attr($key); ?>">
                            <?php echo esc_html($title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="button" class="button pz-footer-admin-remove">حذف شبکه</button>
        </div>
    </script>


    <script type="text/html" id="tmpl-pz-footer-badge">
        <div class="pz-footer-admin-row">
            <div>
                <label>تصویر</label>
                <input type="hidden" class="pz-footer-image-id"
                    name="<?php echo esc_attr($base); ?>[badges][__INDEX__][image_id]">
                <div class="pz-footer-image-preview"></div>
                <button type="button" class="button pz-footer-select-image">انتخاب تصویر</button>
            </div>
            <div>
                <label>متن جایگزین تصویر</label>
                <input type="text" name="<?php echo esc_attr($base); ?>[badges][__INDEX__][alt]">
                <label>لینک اختیاری تصویر</label>
                <input type="url" dir="ltr" name="<?php echo esc_attr($base); ?>[badges][__INDEX__][url]">
            </div>
            <button type="button" class="button pz-footer-admin-remove">حذف تصویر</button>
        </div>
    </script>

    <?php
}


/**
 * بارگذاری کتابخانه رسانه و اسکریپت مدیریت ردیف‌ها
 */
function pezhaal_footer_admin_assets($hook) {

    if ($hook !== 'appearance_page_pezhaal-footer') {
        return;
    }

    wp_enqueue_media();

    wp_register_script(
        'pezhaal-footer-admin',
        false,
        array('jquery'),
        '1.0.0',
        true
    );

    wp_enqueue_script('pezhaal-footer-admin');

    $script = <<<'JS'
jQuery(function ($) {

    function addRow(templateId, containerId) {
        const template = document.getElementById(templateId);
        const container = document.getElementById(containerId);

        if (!template || !container) return;

        const index = Date.now().toString() +
            Math.floor(Math.random() * 1000).toString();

        const html = template.innerHTML.replace(/__INDEX__/g, index);

        container.insertAdjacentHTML('beforeend', html);
    }

    $('#pz-footer-add-link').on('click', function () {
        addRow('tmpl-pz-footer-link', 'pz-footer-links');
    });

    $('#pz-footer-add-social').on('click', function () {
        addRow('tmpl-pz-footer-social', 'pz-footer-socials');
    });

    $('#pz-footer-add-badge').on('click', function () {
        addRow('tmpl-pz-footer-badge', 'pz-footer-badges');
    });

    $(document).on('click', '.pz-footer-admin-remove', function () {
        $(this).closest('.pz-footer-admin-row').remove();
    });

    $(document).on('click', '.pz-footer-select-image', function (event) {
        event.preventDefault();

        const row = $(this).closest('.pz-footer-admin-row');

        const frame = wp.media({
            title: 'انتخاب تصویر',
            button: { text: 'استفاده از این تصویر' },
            library: { type: 'image' },
            multiple: false
        });

        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();

            row.find('.pz-footer-image-id').val(attachment.id);

            const imageUrl = attachment.sizes && attachment.sizes.medium
                ? attachment.sizes.medium.url
                : attachment.url;

            row.find('.pz-footer-image-preview').html(
                $('<img>', {
                    src: imageUrl,
                    alt: '',
                    css: {
                        maxWidth: '100px',
                        maxHeight: '80px',
                        objectFit: 'contain'
                    }
                })
            );
        });

        frame.open();
    });

});
JS;

    wp_add_inline_script('pezhaal-footer-admin', $script);
}
add_action('admin_enqueue_scripts', 'pezhaal_footer_admin_assets');





// ===============================
// تعداد محصولات در هر صفحه
// ===============================
add_filter( 'loop_shop_per_page', function ( $products_per_page ) {
    return 9;
}, 20 );






// ===============================
//          CHECKOUT
// ===============================
add_filter('woocommerce_checkout_fields', 
            'pezhaal_customize_checkout_fields');
function pezhaal_customize_checkout_fields($fields){
    // نام
    $fields['billing'] ['billing_first_name'] ['label'] = 'نام';
    $fields['billing'] ['billing_first_name'] ['placeholder'] = 'نام خود را وارد کنید';
    $fields['billing'] ['billing_first_name'] ['class'] = array('', 'pezhaal-checkout-field');

    // نام خانوادگی
    $fields['billing'] ['billing_last_name'] ['label'] = 'نام خانوادگی';
    $fields['billing'] ['billing_last_name'] ['placeholder'] = 'نام خانوادگی خود را وارد کنید';
    $fields['billing'] ['billing_last_name'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_country'] ['label'] = 'کشور';
    $fields['billing'] ['billing_country'] ['placeholder'] = 'کشور خود را وارد کنید';
    $fields['billing'] ['billing_country'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_state'] ['label'] = 'استان';
    $fields['billing'] ['billing_state'] ['placeholder'] = 'استان محل سکونت';
    $fields['billing'] ['billing_state'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_city'] ['label'] = 'شهر';
    $fields['billing'] ['billing_city'] ['placeholder'] = 'شهر محل سکونت';
    $fields['billing'] ['billing_city'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_address_1'] ['label'] = 'خیابان';
    $fields['billing'] ['billing_address_1'] ['placeholder'] = 'خیابان اصلی';
    $fields['billing'] ['billing_address_1'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_address_2'] ['label'] = 'آدرس دقیق';
    $fields['billing'] ['billing_address_2'] ['placeholder'] = 'خیابان فرعی، ساختمان، واحد';
    $fields['billing'] ['billing_address_2'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_postcode'] ['label'] = 'کدپستی';
    $fields['billing'] ['billing_postcode'] ['placeholder'] = 'کدپستی خود را وارد کنید';
    $fields['billing'] ['billing_postcode'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_phone'] ['label'] = 'شماره تماس';
    $fields['billing'] ['billing_phone'] ['placeholder'] = 'شماره ی تماس خود را وارد کنید';
    $fields['billing'] ['billing_phone'] ['class'] = array('', 'pezhaal-checkout-field');

    $fields['billing'] ['billing_email'] ['label'] = 'ایمیل';
    $fields['billing'] ['billing_email'] ['placeholder'] = 'ایمیل خود را وارد کنید';
    $fields['billing'] ['billing_email'] ['class'] = array('', 'pezhaal-checkout-field');

    return $fields;
}