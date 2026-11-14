<?php
defined('ABSPATH') || exit;

$pricing = $args['pricing'] ?? null;

if (is_wp_error($pricing) || empty($pricing)) {
    return;
}

$type = $pricing['discount_type'];
$amount = $pricing['discount_amount'];

if ($pricing['discount'] <= 0) {
    return;
}
?>

<div class="golden-package-badge">

    <?php if ($type === 'percentage') : ?>

        <?php echo esc_html(
            rtrim(rtrim(number_format($amount, 2), '0'), '.')
        ); ?>٪ تخفیف

    <?php else : ?>

        تخفیف ویژه

    <?php endif; ?>

</div>