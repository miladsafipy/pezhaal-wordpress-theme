<?php
defined('ABSPATH') || exit;

get_header();
?>

<main class="golden-package-archive">

    <header class="golden-package-archive__header">

        <span>انتخاب‌های ویژه پژال</span>

        <h1>تمام پکیج‌های طلایی</h1>

        <p>
            مجموعه‌ای از محصولات منتخب پژال در قالب پکیج‌هایی
            ویژه با قیمت مناسب‌تر.
        </p>

    </header>

    <div class="golden-package-section__grid">

        <?php if (have_posts()) : ?>

            <?php while (have_posts()) : the_post(); ?>

                <?php
                get_template_part(
                    'components/golden-packages/package-card',
                    null,
                    array(
                        'package_id' => get_the_ID()
                    )
                );
                ?>

            <?php endwhile; ?>

        <?php else : ?>

            <p class="golden-package-archive__empty">
                در حال حاضر پکیجی برای نمایش وجود ندارد.
            </p>

        <?php endif; ?>

    </div>

    <div class="golden-package-archive__pagination">

        <?php
        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => 'قبلی',
            'next_text' => 'بعدی'
        ));
        ?>

    </div>

</main>

<?php
get_footer();