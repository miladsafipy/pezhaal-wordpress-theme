<?php

get_header();

?>


<main class="single-product-page">

    <?php if ( have_posts() ) : ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <?php global $product; ?>

            <article class="single-product">

                <div class="single-product__image-content">

                    <section class="single-product__image">
    
                        <?php
                        get_template_part(
                            'components/product-gallery'
                        );
                        ?>
    
                    </section>
    
                    <section class="single-product__content">
    
                        <?php
                        get_template_part(
                            'components/product-summary'
                        );
                        ?>
    
                    </section>
                    
                </div>



                <section class="single-product__review">

                    <?php
                    get_template_part(
                        'components/product-reviews'
                    );
                    ?>

                </section>


            </article>

        <?php endwhile; ?>

    <?php endif; ?>

</main>

<?php
get_footer();
?>