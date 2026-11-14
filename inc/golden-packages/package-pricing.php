
<?php

defined('ABSPATH') || exit;

/**
 * Get package product IDs.
 */
function pezhaal_get_package_product_ids($package_id) {

    $product_ids = get_post_meta(
        $package_id,
        '_pezhaal_package_products',
        true
    );

    if (!is_array($product_ids)) {
        return array();
    }

    return array_values(
        array_unique(
            array_filter(
                array_map('absint', $product_ids)
            )
        )
    );

}

/**
 * Calculate Golden Package Pricing.
 *
 * @return array|WP_Error
 */
function pezhaal_get_package_pricing($package_id) {

    if (!function_exists('wc_get_product')) {
        return new WP_Error(
            'woocommerce_missing',
            'WooCommerce is not available.'
        );
    }

    $package_id = absint($package_id);

    if (
        !$package_id ||
        get_post_type($package_id) !== 'golden_package'
    ) {
        return new WP_Error(
            'invalid_package',
            'Invalid package.'
        );
    }

    // Get selected products.

    $product_ids = pezhaal_get_package_product_ids(
        $package_id
    );

    if (count($product_ids) < 2) {

        return new WP_Error(
            'insufficient_products',
            'A package must contain at least two products.'
        );

    }

    // Calculate products subtotal.

    $products = array();

    $subtotal = 0;

    foreach ($product_ids as $product_id) {

        $product = wc_get_product($product_id);

        if (
            !$product ||
            !in_array(
                $product->get_type(),
                array('simple', 'variation'),
                true
            ) ||
            !$product->is_purchasable() ||
            $product->get_price() === ''
        ) {

            return new WP_Error(
                'invalid_product',
                sprintf(
                    'Product ID %d is not eligible for this package.',
                    $product_id
                )
            );

        }

        $price = (float) $product->get_price();

        if ($price < 0) {

            return new WP_Error(
                'invalid_product_price',
                'Product price cannot be negative.'
            );

        }

        $subtotal += $price;

        $products[] = $product;

    }

    // Round subtotal.

    $decimals = wc_get_price_decimals();

    $subtotal = round($subtotal, $decimals);

    // Get discount settings.

    $discount_type = get_post_meta(
        $package_id,
        '_pezhaal_package_discount_type',
        true
    );

    $discount_amount = get_post_meta(
        $package_id,
        '_pezhaal_package_discount_amount',
        true
    );

    $discount_type = $discount_type ?: 'percentage';

    $discount_amount = max(
        0,
        (float) $discount_amount
    );

    // Calculate discount.

    if ($discount_type === 'percentage') {

        $discount_amount = min(
            100,
            $discount_amount
        );

        $discount = $subtotal * (
            $discount_amount / 100
        );

    } elseif ($discount_type === 'fixed') {

        $discount = min(
            $subtotal,
            $discount_amount
        );

    } else {

        return new WP_Error(
            'invalid_discount_type',
            'Invalid discount type.'
        );

    }

    // Calculate final price.

    $discount = round(
        $discount,
        $decimals
    );

    $final_price = max(
        0,
        round(
            $subtotal - $discount,
            $decimals
        )
    );

    return array(

        'package_id'      => $package_id,

        'products'        => $products,

        'product_ids'     => $product_ids,

        'products_count'  => count($products),

        'subtotal'        => $subtotal,

        'discount_type'   => $discount_type,

        'discount_amount' => $discount_amount,

        'discount'        => $discount,

        'final_price'     => $final_price,

        'currency'        => get_woocommerce_currency(),

        'decimals'        => $decimals,

    );

}