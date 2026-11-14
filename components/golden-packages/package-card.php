<?php
defined('ABSPATH') || exit;

$package_id = isset($args['package_id'])
    ? absint($args['package_id'])
    : get_the_ID();

if (!$package_id) {
    return;
}

$pricing = pezhaal_get_package_pricing($package_id);

$title = get_the_title($package_id);
$excerpt = get_the_excerpt($package_id);
$link = get_permalink($package_id);

$product_ids = pezhaal_get_package_product_ids($package_id);
?>

<article class="golden-package-card">

    <a
        href="<?php echo esc_url($link); ?>"
        class="golden-package-card__image"
    >

        <?php if (has_post_thumbnail($package_id)) : ?>

            <?php
            echo get_the_post_thumbnail(
                $package_id,
                'large',
                array(
                    'loading' => 'lazy',
                    'alt'     => esc_attr($title)
                )
            );
            ?>

        <?php else : ?>

            <div class="golden-package-card__placeholder">
                <span>پکیج طلایی پژال</span>
            </div>

        <?php endif; ?>

        <?php
        get_template_part(
            'components/golden-packages/package-badge',
            null,
            array(
                'package_id' => $package_id,
                'pricing'    => $pricing
            )
        );
        ?>

    </a>

    <div class="golden-package-card__content">

        <h3 class="golden-package-card__title">
            <a href="<?php echo esc_url($link); ?>">
                <?php echo esc_html($title); ?>
            </a>
        </h3>

        <?php if ($excerpt) : ?>
            <p class="golden-package-card__description">
                <?php echo esc_html($excerpt); ?>
            </p>
        <?php endif; ?>

        <div class="golden-package-card__count">
            <span><?php echo esc_html(count($product_ids)); ?></span>
            محصول در این پکیج
        </div>

        <?php if (!is_wp_error($pricing)) : ?>

            <?php
            get_template_part(
                'components/golden-packages/package-price',
                null,
                array(
                    'pricing' => $pricing
                )
            );
            ?>

        <?php else : ?>

            <p class="golden-package-card__unavailable">
                اطلاعات قیمت این پکیج در دسترس نیست.
            </p>

        <?php endif; ?>

        <a
            href="<?php echo esc_url($link); ?>"
            class="golden-package-card__button"
        >
            مشاهده پکیج
        </a>

    </div>

</article>