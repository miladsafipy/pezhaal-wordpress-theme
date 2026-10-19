<?php

defined( 'ABSPATH' ) || exit;


?>

<main class="cart-page">

    <div class="cart-container">

        <header class="cart-header">

            <h2>
                سبد خرید
            </h2>

        </header>


        <?php do_action( 'woocommerce_before_cart' ); ?>


        <form
            class="woocommerce-cart-form"
            action="<?php echo esc_url( wc_get_cart_url() ); ?>"
            method="post"
        >

            <?php do_action( 'woocommerce_before_cart_table' ); ?>


            <div class="cart-items">

                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) : ?>

                    <?php

                    $_product = $cart_item['data'];

                    if (
                        ! $_product ||
                        ! $_product->exists() ||
                        $cart_item['quantity'] <= 0
                    ) {
                        continue;
                    }

                    ?>

                    <article class="cart-item">

                        <div class="cart-item-image">

                            <?php
                            echo $_product->get_image(
                                'thumbnail'
                            );
                            ?>

                        </div>


                        <div class="cart-item-info">

                            <h4 class="cart-item-title">

                                <a
                                    href="<?php echo esc_url( $_product->get_permalink() ); ?>"
                                >
                                    <?php echo esc_html( $_product->get_name() ); ?>
                                </a>

                            </h4>


                            <div class="cart-item-price">

                                <?php
                                echo WC()->cart->get_product_price(
                                    $_product
                                );
                                ?>

                            </div>


                            <div class="cart-item-quantity">

                                <label
                                    for="quantity-<?php echo esc_attr( $cart_item_key ); ?>"
                                >
                                    تعداد
                                </label>

                                <input
                                    id="quantity-<?php echo esc_attr( $cart_item_key ); ?>"
                                    type="number"
                                    name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]"
                                    value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
                                    min="1"
                                >

                            </div>


                            <a
                                class="cart-item-remove"
                                href="<?php echo esc_url(
                                    wc_get_cart_remove_url(
                                        $cart_item_key
                                    )
                                ); ?>"
                            >
                                حذف
                            </a>

                        </div>


                        <div class="cart-item-subtotal">

                            <?php
                            echo WC()->cart->get_product_subtotal(
                                $_product,
                                $cart_item['quantity']
                            );
                            ?>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>


            


            <?php do_action( 'woocommerce_after_cart_table' ); ?>

        </form>


        <section class="cart-totals">

            <h2>
                جمع سبد خرید
            </h2>

            <div class="cart-subtotal">

                <span>
                    جمع محصولات
                </span>

                <strong>
                    <?php
                    wc_cart_totals_subtotal_html();
                    ?>
                </strong>

            </div>


            <div class="cart-total">

                <span>
                    مبلغ نهایی
                </span>

                <strong>
                    <?php
                    wc_cart_totals_order_total_html();
                    ?>
                </strong>

            </div>


            <a
                class="checkout-button"
                href="<?php echo esc_url( wc_get_checkout_url() ); ?>"
            >
                ادامه پرداخت
            </a>

        </section>


        <?php do_action( 'woocommerce_after_cart' ); ?>

    </div>

</main>

