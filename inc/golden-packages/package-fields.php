
<?php

defined('ABSPATH') || exit;

/**
*	Add Golden Package Meta Box.
*/

function pezhaal_add_package_meta_box() {

    add_meta_box(
        'pezhaal_package_settings',
        'تنظیمات پکیج طلایی',
        'pezhaal_render_package_meta_box',
        'golden_package',
        'normal',
        'high'
    );

}

add_action('add_meta_boxes', 'pezhaal_add_package_meta_box');




/**
*	Load WooCommerce product search scripts.
*/


function pezhaal_package_admin_scripts($hook) {
    $screen = get_current_screen();

    if (!$screen || $screen -> post_type !== 'golden_package') {
        return;
    }

    wp_enqueue_script('wc-enhanced-select');
    wp_enqueue_style('woocommerce_admin_styles');
}

add_action('admin_enqueue_scripts', 'pezhaal_package_admin_scripts');




/**
*	Render Meta Box Fields.
*/

function pezhaal_render_package_meta_box($post) {

    wp_nonce_field('pezhaal_save_package', 'pezhaal_package_nonce');
    
    $products = get_post_meta( $post->ID, '_pezhaal_package_products', true);

    $discount_type = get_post_meta( $post->ID, '_pezhaal_package_discount_type', true);
    
    $discount_amount = get_post_meta( $post->ID, '_pezhaal_package_discount_amount', true);
    
    $show_home = get_post_meta( $post->ID, '_pezhaal_package_show_home', true);
    
    $priority = get_post_meta( $post->ID, '_pezhaal_package_priority', true);
    
    $products = is_array($products) ? $products : array();

    $discount_type = $discount_type ?: 'percentage';

    ?>

    <div class="pezhaal-package-fields">

        <p>
            <label for="pezhaal_package_products">
                <strong>محصولات تشکیل‌دهنده پکیج</strong>
            </label>
        </p>

        <select
                id="pezhaal_package_products"
                name="pezhaal_package_products[]"
                class="wc-product-search"
                multiple="multiple"
                style="width: 100%;"
                data-placeholder="نام محصولات را جستجو کنید..."
                data-action="woocommerce_json_search_products_and_variations"
                data-exclude="">
        

            <?php foreach ($products as $product_id) :
                $product = wc_get_product($product_id);

                if (!$product) {
                continue;
                }


            ?>

                <option
                    value="<?php echo esc_attr($product_id); ?>"
                    selected="selected">

                    <?php
                    echo esc_html($product->get_formatted_name());
                    ?>

                </option>

            <?php endforeach; ?>

        </select>

        <hr>

        <p>
        <label for="pezhaal_package_discount_type">
        <strong>نوع تخفیف</strong>
        </label>
        </p>

        <select
        id="pezhaal_package_discount_type"
        name="pezhaal_package_discount_type"
        >

        <option
        value="percentage"
        <?php selected(`$discount_type, 'percentage'); ?>
        >
        درصدی
        </option>

        <option
        value="fixed"
        <?php selected($discount_type, 'fixed'); ?>
        >
        مبلغ ثابت (تومان)
        </option>

        </select>

        <p>
        <label for="pezhaal_package_discount_amount">
        <strong>مقدار تخفیف</strong>
        </label>
        </p>

        <input
        type="number"
        id="pezhaal_package_discount_amount"
        name="pezhaal_package_discount_amount"
        value="<?php echo esc_attr(`$discount_amount); 

        ?>"
        min="0"
        step="any"
        style="width: 100%;"
        >

        <hr>

        <p>

        <label>

        <input
        type="checkbox"
        name="pezhaal_package_show_home"
        value="1"
        <?php checked($`show_home, '1'); ?>
        >

        <strong>

        نمایش این پکیج در صفحه اصلی
        </strong>

        </label>

        </p>

        <p>
        <label for="pezhaal_package_priority">
        <strong>اولویت نمایش</strong>
        </label>
        </p>

        <input
        type="number"
        id="pezhaal_package_priority"
        name="pezhaal_package_priority"
        value="<?php echo esc_attr($priority); ?>"
        min="0"
        step="1"

        >

        <p style="color: #777;">

        عدد کمتر به معنی اولویت بالاتر است.

        </p>

    </div>

    <?php

}
