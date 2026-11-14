<?php
defined('ABSPATH') || exit;

$pricing = $args['pricing'] ?? null;

if (empty($pricing) || is_wp_error($pricing)) {
    return;
}
?>

<div class="golden-package-price">

    <div class="golden-package-price__old">
        <span>قیمت محصولات:</span>

        <del>
            <?php echo wp_kses_post(wc_price($pricing['subtotal'])); ?>
        </del>
    </div>

    <div class="golden-package-price__final">

        <span>قیمت پکیج:</span>

        <strong>
            <?php echo wp_kses_post(wc_price($pricing['final_price'])); ?>
        </strong>

    </div>

    <div class="golden-package-price__saving">
        شما
        <?php echo wp_kses_post(wc_price($pricing['discount'])); ?>
        صرفه‌جویی می‌کنید.
    </div>

</div>