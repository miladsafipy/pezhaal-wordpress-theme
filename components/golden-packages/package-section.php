<?php
defined('ABSPATH') || exit;

$packages_query = new WP_Query(array(
    'post_type'      => 'golden_package',
    'post_status'    => 'publish',
    'posts_per_page' => 4,
    'meta_query'     => array(
        array(
            'key'     => '_pezhaal_package_show_home',
            'value'   => '1',
            'compare' => '='
        )
    ),
    'meta_key'       => '_pezhaal_package_priority',
    'orderby'        => array(
        'meta_value_num' => 'ASC',
        'date'           => 'DESC'
    )
));

if (!$packages_query->have_posts()) {
    return;
}
?>

<section class="golden-package-section">

    <div class="golden-package-section__header">

        <div>
            <span class="golden-package-section__eyebrow">
                انتخاب ویژه پژال
            </span>

            <h2 class="golden-package-section__title">
                پکیج‌های طلایی
            </h2>
        </div>

        <a
            href="<?php echo esc_url(get_post_type_archive_link('golden_package')); ?>"
            class="golden-package-section__view-all"
        >
            مشاهده همه پکیج‌ها
        </a>

    </div>

    <div class="golden-package-section__grid">

        <?php while ($packages_query->have_posts()) : ?>

            <?php
            $packages_query->the_post();

            get_template_part(
                'components/golden-packages/package-card',
                null,
                array(
                    'package_id' => get_the_ID()
                )
            );
            ?>

        <?php endwhile; ?>

    </div>

</section>

<?php wp_reset_postdata(); ?>