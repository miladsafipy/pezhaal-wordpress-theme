
<?php

defined('ABSPATH') || exit;

/**
 * Save Golden Package Meta Fields.
 */

function pezhaal_save_package_meta($post_id) {

    // Check nonce.

    if (
        !isset($_POST['pezhaal_package_nonce']) ||
        !wp_verify_nonce(
            sanitize_text_field(
                wp_unslash($_POST['pezhaal_package_nonce'])
            ),
            'pezhaal_save_package'
        )
    ) {
        return;
    }

    // Prevent autosave.

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check post type.

    if (
        get_post_type($post_id) !== 'golden_package'
    ) {
        return;
    }

    // Check user permission.

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }


    /**
     * Save selected products.
     */

    $product_ids = array();

    if (isset($_POST['pezhaal_package_products'])) {

        $submitted_ids = wp_unslash(
            $_POST['pezhaal_package_products']
        );

        if (is_array($submitted_ids)) {

            foreach ($submitted_ids as $product_id) {

                $product_id = absint($product_id);

                $product = wc_get_product($product_id);

                if ($product) {
                    $product_ids[] = $product_id;
                }

            }

        }

    }

    $product_ids = array_values(
        array_unique($product_ids)
    );

    update_post_meta(
        $post_id,
        '_pezhaal_package_products',
        $product_ids
    );


    /**
     * Save discount type.
     */

    $discount_type = isset(
        $_POST['pezhaal_package_discount_type']
    )
        ? sanitize_key(
            wp_unslash(
                $_POST['pezhaal_package_discount_type']
            )
        )
        : 'percentage';

    if (!in_array(
        $discount_type,
        array('percentage', 'fixed'),
        true
    )) {
        $discount_type = 'percentage';
    }

    update_post_meta(
        $post_id,
        '_pezhaal_package_discount_type',
        $discount_type
    );


    /**
     * Save discount amount.
     */

    $discount_amount = isset(
        $_POST['pezhaal_package_discount_amount']
    )
        ? wc_format_decimal(
            wp_unslash(
                $_POST['pezhaal_package_discount_amount']
            )
        )
        : 0;

    $discount_amount = max(
        0,
        (float) $discount_amount
    );

    if ($discount_type === 'percentage') {

        $discount_amount = min(
            100,
            $discount_amount
        );

    }

    update_post_meta(
        $post_id,
        '_pezhaal_package_discount_amount',
        $discount_amount
    );


    /**
     * Save homepage visibility.
     */

    $show_home = isset(
        $_POST['pezhaal_package_show_home']
    ) ? '1' : '0';

    update_post_meta(
        $post_id,
        '_pezhaal_package_show_home',
        $show_home
    );


    /**
     * Save display priority.
     */

    $priority = isset(
        $_POST['pezhaal_package_priority']
    )
        ? absint(
            wp_unslash(
                $_POST['pezhaal_package_priority']
            )
        )
        : 0;

    update_post_meta(
        $post_id,
        '_pezhaal_package_priority',
        $priority
    );

}

add_action(
    'save_post_golden_package',
    'pezhaal_save_package_meta'
);
