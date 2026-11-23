
<?php

defined('ABSPATH') || exit;

/**
 * Add product to cart using AJAX.
 */
function pezhaal_ajax_add_to_cart() {

    check_ajax_referer(
        'pezhaal_cart_nonce',
        'nonce'
    );

    $product_id = isset($_POST['product_id'])
        ? absint($_POST['product_id'])
        : 0;

    $quantity = isset($_POST['quantity'])
        ? wc_stock_amount(
            wp_unslash($_POST['quantity'])
        )
        : 1;

    if (
        !$product_id ||
        $quantity <= 0 ||
        !is_numeric($quantity)
    ) {
        wp_send_json_error([
            'message' => 'اطلاعات محصول معتبر نیست.'
        ]);
    }

    

    $product = wc_get_product($product_id);

    if (
        !$product ||
        !$product->is_type('simple') ||
        !$product->is_purchasable() ||
        !$product->is_in_stock()
    ) {

        wp_send_json_error([
            'message' => 'این محصول قابل خرید نیست.'
        ]);

    }

    // اعتبارسنجی تعداد سفارش با قوانین ووکامرس
    $passed_validation = apply_filters(
        'woocommerce_add_to_cart_validation',
        true,
        $product_id,
        $quantity
    );

    if (!$passed_validation) {

        wp_send_json_error([
            'message' => 'امکان افزودن این تعداد از محصول وجود ندارد.'
        ]);

    }

    // افزودن محصول به سبد خرید
    $cart_item_key = WC()->cart->add_to_cart(
        $product_id,
        $quantity
    );

    if (!$cart_item_key) {

        wp_send_json_error([
            'message' => 'افزودن محصول ناموفق بود.'
        ]);

    }

    // محاسبه مجدد مجموع سبد خرید
    WC()->cart->calculate_totals();

    // تعداد انواع محصولات موجود در سبد خرید
    $cart_count = count(WC()->cart->get_cart());

    // ارسال پاسخ به JavaScript
    wp_send_json_success([

        'cart_count' => $cart_count,

        'cart_hash' => WC()->cart->get_cart_hash(),

    ]);

}

add_action(
    'wp_ajax_pezhaal_add_to_cart',
    'pezhaal_ajax_add_to_cart'
);

add_action(
    'wp_ajax_nopriv_pezhaal_add_to_cart',
    'pezhaal_ajax_add_to_cart'
);