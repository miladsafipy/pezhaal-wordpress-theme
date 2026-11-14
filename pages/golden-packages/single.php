<?php
defined('ABSPATH') || exit;

$package_id = get_the_ID();
$pricing = pezhaal_get_package_pricing($package_id);
?>

<main class="golden-package-single">

    <div class="golden-package-single__container">

        <?php while (have_posts()) : the_post(); ?>

            <div class="golden-package-single__main">

                <div class="golden-package-single__image">

                    <?php if (has_post_thumbnail()) : ?>

                        <?php
                        the_post_thumbnail('large', array(
                            'loading' => 'eager'
                        ));
                        ?>

                    <?php endif; ?>

                </div>

                <div class="golden-package-single__information">

                    <span class="golden-package-single__eyebrow">
                        پکیج ویژه پژال
                    </span>

                    <h1>
                        <?php the_title(); ?>
                    </h1>

                    <?php if (has_excerpt()) : ?>

                        <div class="golden-package-single__excerpt">
                            <?php the_excerpt(); ?>
                        </div>

                    <?php endif; ?>

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

                        <p class="golden-package-single__error">
                            <?php echo esc_html($pricing->get_error_message()); ?>
                        </p>

                    <?php endif; ?>

                </div>

            </div>

            <?php if (get_the_content()) : ?>

                <section class="golden-package-single__description">

                    <h2>درباره‌ی این پکیج</h2>

                    <?php the_content(); ?>

                </section>

            <?php endif; ?>

            <?php if (!is_wp_error($pricing)) : ?>

                <?php
                get_template_part(
                    'components/golden-packages/package-products',
                    null,
                    array(
                        'pricing' => $pricing
                    )
                );
                ?>

            <?php endif; ?>

        <?php endwhile; ?>

        <!-- اضافه کردن دکمه ی خرید در صفحه ی جزئیات -->
         <form
            class="golden-package-add-to-cart"
            method="post"
            action="<?php echo esc_url(get_permalink($package_id)); ?>"
        >

            <input
                type="hidden"
                name="package_id"
                value="<?php echo esc_attr($package_id); ?>"
            >

            <?php
            wp_nonce_field(
                'pezhaal_add_package_' . $package_id,
                'package_nonce'
            );
            ?>

            <div class="golden-package-add-to-cart__quantity">

                <label for="package_qty">تعداد پکیج:</label>

                <input
                    type="number"
                    id="package_qty"
                    name="package_qty"
                    value="1"
                    min="1"
                    max="20"
                >

            </div>

            <button
                type="submit"
                name="pezhaal_add_package"
                value="1"
                class="golden-package-add-to-cart__button"
            >
                افزودن پکیج به سبد خرید
            </button>

        </form>

    </div>

</main>