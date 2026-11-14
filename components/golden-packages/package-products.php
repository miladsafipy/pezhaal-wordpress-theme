<?php
defined('ABSPATH') || exit;

$pricing = $args['pricing'] ?? null;

if (empty($pricing) || is_wp_error($pricing)) {
    return;
}
?>

<section class="golden-package-products">

    <h2 class="golden-package-products__title">
        محصولات موجود در این پکیج
    </h2>

    <div class="golden-package-products__grid">

        <?php foreach ($pricing['products'] as $product) : ?>

            <?php
            $product_id = $product->get_id();
            ?>

            <article class="golden-package-product">

                <a
                    href="<?php echo esc_url(get_permalink($product_id)); ?>"
                    class="golden-package-product__image"
                >
                    <?php
                    echo wp_kses_post(
                        $product->get_image('woocommerce_thumbnail')
                    );
                    ?>
                </a>

                <div class="golden-package-product__content">

                    <h3>
                        <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                            <?php echo esc_html($product->get_name()); ?>
                        </a>
                    </h3>

                    <div class="golden-package-product__price">
                        <?php
                        echo wp_kses_post(
                            $product->get_price_html()
                        );
                        ?>
                    </div>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

</section>