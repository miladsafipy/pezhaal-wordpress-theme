
<?php
defined( 'ABSPATH' ) || exit;

global $product;

// اطمینان از اینکه محصول معتبر است.
if ( ! $product instanceof WC_Product ) {
    return;
}

$product_id = $product->get_id();
$product_url = get_permalink( $product_id );

// توضیحات مختصر محصول
$short_description = $product->get_short_description();

// امتیاز و تعداد نظرات
$average_rating = $product->get_average_rating();
$review_count   = $product->get_review_count();

// وضعیت موجودی
$is_in_stock = $product->is_in_stock();

// فقط محصولات ساده و قابل خرید مستقیماً به سبد اضافه می‌شوند.
$can_add_directly = (
    $product->is_type( 'simple' )
    && $product->is_purchasable()
    && $is_in_stock
);

// تعیین حداقل و حداکثر تعداد سفارش
$min_quantity = $product->get_min_purchase_quantity();
$max_quantity = $product->get_max_purchase_quantity();
?>

<article class="product-card">

    <!-- تصویر محصول -->
    <a
        class="product-card__image-link"
        href="<?php echo esc_url( $product_url ); ?>"
        aria-label="<?php echo esc_attr( $product->get_name() ); ?>"
    >
        <?php
        echo wp_kses_post(
            $product->get_image( 'woocommerce_thumbnail' )
        );
        ?>
    </a>

    <div class="product-card__content">

        <!-- نام محصول -->
        <h4 class="product-card__title">
            <a href="<?php echo esc_url( $product_url ); ?>">
                <?php echo esc_html( $product->get_name() ); ?>
            </a>
        </h4>

        

        <!-- امتیاز و تعداد رأی‌دهندگان -->
        <?php if ( $product->get_rating_count() > 0 ) : ?>
            <div class="product-card__rating">

                <?php
                echo wp_kses_post(
                    wc_get_rating_html(
                        $average_rating,
                        $review_count
                    )
                );
                ?>

                <span class="product-card__review-count">
                    (<?php echo esc_html( $review_count ); ?> نظر)
                </span>

            </div>
        <?php else : ?>
            <div class="product-card__rating product-card__rating--empty">
                هنوز امتیازی ثبت نشده است
            </div>
        <?php endif; ?>

        <!-- قیمت -->
        <div class="product-card__price">
            <?php
            echo wp_kses_post( $product->get_price_html() );
            ?>
        </div>

        <!-- وضعیت موجودی -->
        <div class="product-card__stock">
            <?php if ( $is_in_stock ) : ?>

                <span class="product-card__stock--available">
                    موجود در انبار
                </span>

            <?php else : ?>

                <span class="product-card__stock--unavailable">
                    ناموجود
                </span>

            <?php endif; ?>
        </div>

        <!-- فرم خرید -->
        <div class="product-card__actions">

            <?php if ( $can_add_directly ) : ?>

                <form
                    class="cart product-card__cart-form"
                    action="<?php echo esc_url( $product_url ); ?>"
                    method="post"
                >


                    <?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>

                        <div class="product-card__quantity">

                            <label for="quantity-<?php echo esc_attr( $product->get_id() ); ?>">
                                تعداد سفارش شما:
                            </label>

                            <input
                                type="number"
                                id="quantity-<?php echo esc_attr( $product->get_id() ); ?>"
                                name="quantity"
                                value="1"
                                min="1"
                                max="<?php echo esc_attr( $product->get_max_purchase_quantity() ); ?>"
                                step="1"
                            >

                        </div>

                    <?php endif; ?>

                    <button
                        type="submit"
                        name="add-to-cart"
                        value="<?php echo esc_attr( $product_id ); ?>"
                        class="button product-card__add-to-cart"
                    >
                        افزودن به سبد خرید
                    </button>

                </form>

            <?php elseif ( $product->is_type( 'variable' ) && $is_in_stock ) : ?>

                <a
                    class="button product-card__select-options"
                    href="<?php echo esc_url( $product_url ); ?>"
                >
                    انتخاب ویژگی‌ها
                </a>

            <?php elseif ( ! $is_in_stock ) : ?>

                <button
                    type="button"
                    class="button product-card__add-to-cart"
                    disabled
                >
                    ناموجود
                </button>

            <?php else : ?>

                <a
                    class="button product-card__select-options"
                    href="<?php echo esc_url( $product_url ); ?>"
                >
                    مشاهده محصول
                </a>

            <?php endif; ?>

            <a
                class="product-card__view-product"
                href="<?php echo esc_url( $product_url ); ?>"
            >
                مشاهده محصول
            </a>

        </div>

    </div>

</article>
