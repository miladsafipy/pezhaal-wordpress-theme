<?php
/**
 * Product Categories Component
 *
 * Displays WooCommerce product categories
 * as horizontally scrollable cards.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$product_categories = get_terms(
    array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'orderby'    => 'menu_order',
        'order'      => 'ASC',
    )
);

if ( empty( $product_categories ) || is_wp_error( $product_categories ) ) {
    return;
}

$shop_page_id = wc_get_page_id( 'shop' );
$shop_url     = get_permalink( $shop_page_id );
?>

<section class="product-categories" aria-labelledby="product-categories-title">

    <div class="product-categories__container">

        <div class="product-categories__header">

            <div class="product-categories__heading">
                <span class="product-categories__eyebrow">
                    دسته‌بندی محصولات
                </span>
                <div class="title-divider"></div>
            </div>

        </div>


        <div class="product-categories__track">

            <?php foreach ( $product_categories as $category ) : ?>

                <?php
                $thumbnail_id = get_term_meta(
                    $category->term_id,
                    'thumbnail_id',
                    true
                );

                $image_url = $thumbnail_id
                    ? wp_get_attachment_image_url( $thumbnail_id, 'large' )
                    : wc_placeholder_img_src( 'large' );

                $category_url = add_query_arg(
                    'product_cat',
                    $category->slug,
                    $shop_url
                );
                ?>

                <a
                    class="product-category-card"
                    href="<?php echo esc_url( $category_url ); ?>"
                    aria-label="<?php echo esc_attr( $category->name ); ?>"
                >

                    <div class="product-category-card__image-wrapper">

                        <img
                            class="product-category-card__image"
                            src="<?php echo esc_url( $image_url ); ?>"
                            alt="<?php echo esc_attr( $category->name ); ?>"
                            loading="lazy"
                        >

                        <div class="product-category-card__overlay"></div>

                        <span class="product-category-card__arrow">
                            ←
                        </span>

                    </div>


                    <div class="product-category-card__content">

                        <h3 class="product-category-card__title">
                            <?php echo esc_html( $category->name ); ?>
                        </h3>

                        <span class="product-category-card__count">
                            <?php
                            echo esc_html(
                                sprintf(
                                    '%d محصول',
                                    $category->count
                                )
                            );
                            ?>
                        </span>

                    </div>

                </a>

            <?php endforeach; ?>

        </div>

        <div class="section-divider"></div>

    </div>

</section>