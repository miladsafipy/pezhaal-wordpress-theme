<?php
defined('ABSPATH') || exit;


/*
|--------------------------------------------------------------------------
| Custom cart product
|--------------------------------------------------------------------------
*/

if (class_exists('WC_Product_Simple') && !class_exists('Pezhaal_Golden_Package_Product')) {

    class Pezhaal_Golden_Package_Product extends WC_Product_Simple {

        public function __construct($package_id, $price) {

            parent::__construct(0);

            $this->set_id(0);
            $this->set_name(get_the_title($package_id));
            $this->set_price($price);
            $this->set_regular_price($price);
            $this->set_virtual(true);
            $this->set_sold_individually(false);

        }

        public function is_purchasable() {
            return true;
        }

        public function is_in_stock() {
            return true;
        }

        public function has_enough_stock($quantity) {
            return true;
        }

        public function managing_stock() {
            return false;
        }

        public function get_stock_status($context = 'view') {
            return 'instock';
        }

        public function exists() {
            return true;
        }

    }

}


/*
|--------------------------------------------------------------------------
| Validate package stock
|--------------------------------------------------------------------------
*/

function pezhaal_validate_package_stock($package_id, $quantity = 1) {

    $product_ids = pezhaal_get_package_product_ids($package_id);

    if (empty($product_ids)) {
        return new WP_Error('empty_package', 'این پکیج محصولی ندارد.');
    }

    foreach ($product_ids as $product_id) {

        $product = wc_get_product($product_id);

        if (!$product || !$product->is_purchasable()) {
            return new WP_Error(
                'invalid_package_product',
                'یکی از محصولات این پکیج قابل خرید نیست.'
            );
        }

        if (!$product->is_in_stock()) {
            return new WP_Error(
                'package_product_out_of_stock',
                sprintf('محصول «%s» ناموجود است.', $product->get_name())
            );
        }

        if (
            $product->managing_stock() &&
            !$product->has_enough_stock($quantity)
        ) {
            return new WP_Error(
                'insufficient_package_stock',
                sprintf(
                    'موجودی محصول «%s» برای تعداد درخواستی کافی نیست.',
                    $product->get_name()
                )
            );
        }

    }

    return true;
}


/*
|--------------------------------------------------------------------------
| Add package to cart
|--------------------------------------------------------------------------
*/

add_action('template_redirect', 'pezhaal_handle_package_add_to_cart');

function pezhaal_handle_package_add_to_cart() {

    if (
        empty($_POST['pezhaal_add_package']) ||
        !isset($_POST['package_id'])
    ) {
        return;
    }

    if (
        !function_exists('WC') ||
        !WC()->cart
    ) {
        return;
    }

    $package_id = absint($_POST['package_id']);
    $quantity = isset($_POST['package_qty'])
        ? max(1, absint($_POST['package_qty']))
        : 1;

    if (
        !isset($_POST['package_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(wp_unslash($_POST['package_nonce'])),
            'pezhaal_add_package_' . $package_id
        )
    ) {
        wc_add_notice('درخواست نامعتبر است. دوباره تلاش کنید.', 'error');
        wp_safe_redirect(get_permalink($package_id));
        exit;
    }

    if (get_post_type($package_id) !== 'golden_package' || get_post_status($package_id) !== 'publish') {
        wc_add_notice('پکیج موردنظر معتبر نیست.', 'error');
        wp_safe_redirect(wc_get_page_permalink('shop'));
        exit;
    }

    $pricing = pezhaal_get_package_pricing($package_id);

    if (is_wp_error($pricing)) {
        wc_add_notice($pricing->get_error_message(), 'error');
        wp_safe_redirect(get_permalink($package_id));
        exit;
    }

    $stock_check = pezhaal_validate_package_stock($package_id, $quantity);

    if (is_wp_error($stock_check)) {
        wc_add_notice($stock_check->get_error_message(), 'error');
        wp_safe_redirect(get_permalink($package_id));
        exit;
    }

    /*
     * WooCommerce needs a real product ID to create a cart line.
     * We use the first package product as a temporary carrier.
     * Its cart product object is replaced below.
     */

    $first_product = $pricing['products'][0];

    $carrier_product_id = $first_product->get_id();
    $variation_id = 0;
    $variation = array();

    if ($first_product->is_type('variation')) {

        $variation_id = $first_product->get_id();
        $carrier_product_id = $first_product->get_parent_id();
        $variation = $first_product->get_variation_attributes();

    }

    $cart_item_data = array(
        'pezhaal_golden_package' => true,
        'package_id'             => $package_id,
        'package_product_ids'    => $pricing['product_ids'],
        'package_price'          => $pricing['final_price'],
    );

    $added = WC()->cart->add_to_cart(
        $carrier_product_id,
        $quantity,
        $variation_id,
        $variation,
        $cart_item_data
    );

    if ($added) {
        wc_add_notice('پکیج با موفقیت به سبد خرید اضافه شد.', 'success');
        wp_safe_redirect(wc_get_cart_url());
    } else {
        wc_add_notice('افزودن پکیج به سبد خرید انجام نشد.', 'error');
        wp_safe_redirect(get_permalink($package_id));
    }

    exit;
}


/*
|--------------------------------------------------------------------------
| Replace temporary carrier with package cart product
|--------------------------------------------------------------------------
*/

function pezhaal_prepare_package_cart_item($cart_item) {

    if (empty($cart_item['pezhaal_golden_package'])) {
        return $cart_item;
    }

    $package_id = absint($cart_item['package_id']);
    $price = (float) $cart_item['package_price'];

    $cart_item['data'] = new Pezhaal_Golden_Package_Product(
        $package_id,
        $price
    );

    return $cart_item;
}

add_filter(
    'woocommerce_add_cart_item',
    'pezhaal_prepare_package_cart_item'
);

add_filter(
    'woocommerce_get_cart_item_from_session',
    'pezhaal_prepare_package_cart_item'
);


/*
|--------------------------------------------------------------------------
| Cart item title and thumbnail
|--------------------------------------------------------------------------
*/

add_filter('woocommerce_cart_item_name', function ($name, $cart_item) {

    if (empty($cart_item['pezhaal_golden_package'])) {
        return $name;
    }

    $package_id = absint($cart_item['package_id']);

    return sprintf(
        '<a href="%s">%s</a>',
        esc_url(get_permalink($package_id)),
        esc_html(get_the_title($package_id))
    );

}, 10, 2);


add_filter('woocommerce_cart_item_thumbnail', function ($thumbnail, $cart_item) {

    if (empty($cart_item['pezhaal_golden_package'])) {
        return $thumbnail;
    }

    $package_id = absint($cart_item['package_id']);

    return get_the_post_thumbnail(
        $package_id,
        'woocommerce_thumbnail'
    );

}, 10, 2);


/*
|--------------------------------------------------------------------------
| Validate package stock in cart and checkout
|--------------------------------------------------------------------------
*/

add_action('woocommerce_check_cart_items', function () {

    if (!WC()->cart) {
        return;
    }

    foreach (WC()->cart->get_cart() as $cart_item) {

        if (empty($cart_item['pezhaal_golden_package'])) {
            continue;
        }

        $package_id = absint($cart_item['package_id']);
        $quantity = absint($cart_item['quantity']);

        $result = pezhaal_validate_package_stock(
            $package_id,
            $quantity
        );

        if (is_wp_error($result)) {
            wc_add_notice($result->get_error_message(), 'error');
        }

    }

});


/*
|--------------------------------------------------------------------------
| Save package information in order item
|--------------------------------------------------------------------------
*/

add_action(
    'woocommerce_checkout_create_order_line_item',
    function ($item, $cart_item_key, $values, $order) {

        if (empty($values['pezhaal_golden_package'])) {
            return;
        }

        $package_id = absint($values['package_id']);
        $product_ids = array_map(
            'absint',
            $values['package_product_ids']
        );

        $item->set_product_id(0);
        $item->set_variation_id(0);
        $item->set_name(get_the_title($package_id));

        $item->add_meta_data(
            '_pezhaal_package_id',
            $package_id,
            true
        );

        $item->add_meta_data(
            '_pezhaal_package_product_ids',
            $product_ids,
            true
        );

        $item->add_meta_data(
            'محصولات تشکیل‌دهنده پکیج',
            implode(', ', array_map(
                function ($id) {
                    return get_the_title($id);
                },
                $product_ids
            )),
            true
        );

    },
    10,
    4
);


/*
|--------------------------------------------------------------------------
| Reduce constituent product stock after order stock reduction
|--------------------------------------------------------------------------
*/

add_action('woocommerce_reduce_order_stock', function ($order) {

    if (!$order instanceof WC_Order) {
        return;
    }

    if ($order->get_meta('_pezhaal_package_stock_reduced')) {
        return;
    }

    $updated_products = array();

    foreach ($order->get_items('line_item') as $item) {

        $product_ids = $item->get_meta(
            '_pezhaal_package_product_ids',
            true
        );

        if (!is_array($product_ids) || empty($product_ids)) {
            continue;
        }

        $quantity = (int) $item->get_quantity();

        foreach ($product_ids as $product_id) {

            $product = wc_get_product(absint($product_id));

            if (!$product || !$product->managing_stock()) {
                continue;
            }

            $result = wc_update_product_stock(
                $product,
                $quantity,
                'decrease'
            );

            if (is_wp_error($result) || false === $result) {

                // Roll back package stock changes already made.
                foreach ($updated_products as $updated) {
                    wc_update_product_stock(
                        $updated['product'],
                        $updated['quantity'],
                        'increase'
                    );
                }

                $order->add_order_note(
                    'خطا در کاهش موجودی محصولات یکی از پکیج‌های طلایی.'
                );

                return;
            }

            $updated_products[] = array(
                'product'  => $product,
                'quantity' => $quantity,
            );

        }

    }

    $order->update_meta_data(
        '_pezhaal_package_stock_reduced',
        'yes'
    );

    $order->save();

});


/*
|--------------------------------------------------------------------------
| Restore constituent stock when WooCommerce restores order stock
|--------------------------------------------------------------------------
*/

add_action('woocommerce_restore_order_stock', function ($order) {

    if (!$order instanceof WC_Order) {
        return;
    }

    if (
        !$order->get_meta('_pezhaal_package_stock_reduced') ||
        $order->get_meta('_pezhaal_package_stock_restored')
    ) {
        return;
    }

    foreach ($order->get_items('line_item') as $item) {

        $product_ids = $item->get_meta(
            '_pezhaal_package_product_ids',
            true
        );

        if (!is_array($product_ids) || empty($product_ids)) {
            continue;
        }

        $quantity = (int) $item->get_quantity();

        foreach ($product_ids as $product_id) {

            $product = wc_get_product(absint($product_id));

            if (!$product || !$product->managing_stock()) {
                continue;
            }

            wc_update_product_stock(
                $product,
                $quantity,
                'increase'
            );

        }

    }

    $order->update_meta_data(
        '_pezhaal_package_stock_restored',
        'yes'
    );

    $order->save();

});


// نمایش محصولات داخل پکیج در سبد خرید
add_filter('woocommerce_get_item_data', function ($item_data, $cart_item) {

    if (empty($cart_item['pezhaal_golden_package'])) {
        return $item_data;
    }

    $product_ids = $cart_item['package_product_ids'] ?? array();

    $names = array();

    foreach ($product_ids as $product_id) {
        $name = get_the_title(absint($product_id));

        if ($name) {
            $names[] = $name;
        }
    }

    $item_data[] = array(
        'key'   => 'محصولات پکیج',
        'value' => implode('، ', $names),
    );

    return $item_data;

}, 10, 2);