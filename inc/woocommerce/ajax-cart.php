
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





// Cart page -> Update quantity

/**
 * Update cart item quantity using AJAX.
 */

add_action(
    'wp_ajax_pezhaal_update_cart_item',
    'pezhaal_update_cart_item'
);

add_action(
    'wp_ajax_nopriv_pezhaal_update_cart_item',
    'pezhaal_update_cart_item'
);


function pezhaal_update_cart_item() {

    // Verify nonce.
    check_ajax_referer(
        'pezhaal_cart_nonce',
        'nonce'
    );


    // Get cart item key.
    $cart_item_key = isset( $_POST['cart_item_key'] )
        ? sanitize_text_field( wp_unslash( $_POST['cart_item_key'] ) )
        : '';


    // Get new quantity.
    $quantity = isset( $_POST['quantity'] )
        ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) )
        : 0;


    // Validate inputs.
    if ( empty( $cart_item_key ) || $quantity < 1 ) {

        wp_send_json_error([
            'message' => 'اطلاعات ارسالی معتبر نیست.'
        ]);

    }


    // Check cart item.
    $cart = WC()->cart->get_cart();


    if ( ! isset( $cart[ $cart_item_key ] ) ) {

        wp_send_json_error([
            'message' => 'محصول موردنظر در سبد خرید وجود ندارد.'
        ]);

    }


    $product = $cart[ $cart_item_key ]['data'];


    // Check stock.
    if (
        ! $product->is_purchasable() ||
        ! $product->has_enough_stock( $quantity )
    ) {

        wp_send_json_error([
            'message' => 'تعداد درخواستی بیشتر از موجودی محصول است.'
        ]);

    }


    // Update quantity and calculate totals.
    WC()->cart->set_quantity(
        $cart_item_key,
        $quantity,
        true
    );


    // Get updated cart item.
    $updated_cart = WC()->cart->get_cart();

    $updated_item = $updated_cart[ $cart_item_key ];


    // Get updated item subtotal.
    $item_subtotal = WC()->cart->get_product_subtotal(
        $updated_item['data'],
        $updated_item['quantity']
    );


    // Capture cart subtotal HTML.
    ob_start();

    wc_cart_totals_subtotal_html();

    $cart_subtotal = ob_get_clean();


    // Capture cart total HTML.
    ob_start();

    wc_cart_totals_order_total_html();

    $cart_total = ob_get_clean();


    // Send response.
    wp_send_json_success([

        'message'       => 'تعداد محصول با موفقیت به‌روزرسانی شد.',

        'item_subtotal' => $item_subtotal,

        'cart_subtotal' => $cart_subtotal,

        'cart_total'    => $cart_total,

    ]);

}