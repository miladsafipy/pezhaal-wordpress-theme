<?php
defined( 'ABSPATH' ) || exit;

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