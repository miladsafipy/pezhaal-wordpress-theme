<?php

get_header();

?>

<main class="single-product-page">

    <?php if ( have_posts() ) : ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <?php global $product; ?>

            <article class="single-product">

                <?php
                get_template_part(
                    'components/product-gallery'
                );
                ?>

                <?php
                get_template_part(
                    'components/product-summary'
                );
                ?>

                <!-- Active Rating and Review in WooCommerce -->
                <?php
                if ( comments_open() || get_comments_number() ) {
                    comments_template();
                }
                ?>


            </article>

        <?php endwhile; ?>

    <?php endif; ?>

</main>

<?php

get_footer();

?>