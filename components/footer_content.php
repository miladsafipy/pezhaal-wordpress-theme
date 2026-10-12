
<?php
/**
 * Pezhaal Footer Content
 */

$settings = get_option('pezhaal_footer_settings', array());

$brand = $settings['brand'] ?? 'پژال';

$links = $settings['links'] ?? array();

$socials = $settings['socials'] ?? array();

$badges = $settings['badges'] ?? array();

$trust_text = $settings['trust_text']
    ?? 'ایمن و با اطمینان از پژال خرید کنید';

$copyright = $settings['copyright']
    ?? 'تمامی حقوق این فروشگاه محفوظ می‌باشد';


/**
 * آیکون‌های شبکه‌های اجتماعی
 * SVG داخلی؛ بدون نیاز به افزونه آیکون
 */
function pezhaal_footer_social_svg($icon) {

    $paths = array(

        'instagram' =>
            '<rect x="3" y="3" width="18" height="18" rx="5"/>
             <circle cx="12" cy="12" r="4"/>
             <circle cx="17.5" cy="6.5" r=".8" fill="currentColor"/>',

        'telegram' =>
            '<path d="M22 3 2 11l7 3 3 7 10-18Z"/>
             <path d="m9 14 13-11-10 18-3-7Z"/>',

        'pinterest' =>
            '<circle cx="12" cy="12" r="9"/>
             <path d="m10 15-1 5m3-8c-1 4-4 3-4 0 0-3 2-6 5-6 3 0 4 2 4 5 0 3-2 5-4 5-1 0-2-1-2-2"/>',

        'whatsapp' =>
            '<path d="M21 11.5a8.5 8.5 0 0 1-12.5 7.4L3 20l1.2-5.1A8.5 8.5 0 1 1 21 11.5Z"/>
             <path d="M8 8c1 4 3 6 7 7l1-2-3-1-1 1c-1-1-2-2-2-3l1-1-1-2H8Z"/>',

        'youtube' =>
            '<rect x="2" y="5" width="20" height="14" rx="4"/>
             <path d="m10 9 5 3-5 3V9Z" fill="currentColor" stroke="none"/>',

        'facebook' =>
            '<path d="M14 21v-8h3l.5-4H14V7c0-1 .5-2 2-2h2V2h-3c-3 0-5 2-5 5v2H7v4h3v8h4Z"/>',
    );

    if (!isset($paths[$icon])) {
        return '';
    }

    return '<svg class="footer-social-icon"
        viewBox="0 0 24 24"
        width="22"
        height="22"
        fill="none"
        stroke="currentColor"
        stroke-width="1.7"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true">'
        . $paths[$icon] .
        '</svg>';
}
?>

<div class="footer-inner">

    <!-- بخش اول: سه ستون -->
    <div class="footer-top">

        <!-- ستون راست: نام برند -->
        <div class="footer-brand">
            <span class="footer-brand-name">
                <?php echo esc_html($brand); ?>
            </span>
        </div>


        <!-- ستون وسط: لینک‌های دسترسی سریع -->
        <nav class="footer-links" aria-label="دسترسی سریع">

            <h2 class="footer-column-title">
                دسترسی سریع
            </h2>

            <?php foreach ($links as $item) : ?>

                <?php
                $label = $item['label'] ?? '';
                $url = $item['url'] ?? '';
                ?>

                <?php if ($label && $url) : ?>
                    <a
                        class="footer-link"
                        href="<?php echo esc_url($url); ?>"
                    >
                        <?php echo esc_html($label); ?>
                    </a>
                <?php endif; ?>

            <?php endforeach; ?>

        </nav>


        <!-- ستون چپ: شبکه‌های اجتماعی -->
        <div class="footer-socials">

            <h2 class="footer-column-title">
                همراه پژال باشید
            </h2>

            <div class="footer-social-list">

                <?php foreach ($socials as $item) : ?>

                    <?php
                    $name = $item['name'] ?? '';
                    $url = $item['url'] ?? '';
                    $icon = $item['icon'] ?? '';
                    ?>

                    <?php if ($name && $url) : ?>

                        <a
                            class="footer-social-link"
                            href="<?php echo esc_url($url); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="<?php echo esc_attr($name); ?>"
                            title="<?php echo esc_attr($name); ?>"
                        >
                            <?php
                            echo pezhaal_footer_social_svg($icon);
                            ?>
                            <span><?php echo esc_html($name); ?></span>
                        </a>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        </div>

    </div>


    <!-- جداکننده اول -->
    <div class="footer-divider" aria-hidden="true"></div>


    <!-- بخش اعتماد و پرداخت -->
    <section class="footer-trust">

        <h2 class="footer-trust-title">
            <?php echo esc_html($trust_text); ?>
        </h2>

        <div class="footer-badges">

            <?php foreach ($badges as $item) : ?>

                <?php
                $image_id = absint($item['image_id'] ?? 0);
                $alt = $item['alt'] ?? '';
                $url = $item['url'] ?? '';

                if (!$image_id || !wp_attachment_is_image($image_id)) {
                    continue;
                }
                ?>

                <div class="footer-badge">

                    <?php if ($url) : ?>

                        <a
                            href="<?php echo esc_url($url); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="<?php echo esc_attr($alt); ?>"
                        >
                            <?php
                            echo wp_get_attachment_image(
                                $image_id,
                                'medium',
                                false,
                                array(
                                    'class' => 'footer-badge-image',
                                    'alt' => $alt,
                                    'loading' => 'lazy',
                                )
                            );
                            ?>
                        </a>

                    <?php else : ?>

                        <?php
                        echo wp_get_attachment_image(
                            $image_id,
                            'medium',
                            false,
                            array(
                                'class' => 'footer-badge-image',
                                'alt' => $alt,
                                'loading' => 'lazy',
                            )
                        );
                        ?>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>

    </section>


    <!-- جداکننده دوم -->
    <div class="footer-divider" aria-hidden="true"></div>


    <!-- متن پایانی -->
    <div class="footer-bottom">

        <p class="footer-copyright">
            <?php echo esc_html($copyright); ?>
        </p>

    </div>

</div>
