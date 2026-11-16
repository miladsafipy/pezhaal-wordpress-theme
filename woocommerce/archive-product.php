
<?php
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<main class="shop-page">

    <div class="shop-page__container">

        
        <!-- عنوان صفحه فروشگاه -->
        <header class="shop-page__header">

            <div class="shop-page__header">

                <div class="shop-page__heading">
                    <img src="<?php echo esc_url(get_template_directory_uri() .'/assets/images/leaf-divider-right.png');?>" alt="">

                    <h2 class="shop-page__title">
                        محصولات پژال
                    </h2>

                    <img src="<?php echo esc_url(get_template_directory_uri() .'/assets/images/leaf-divider-left.png');?>" alt="">
                </div>

            </div>

        </header>

        <?php if ( woocommerce_product_loop() ) : ?>

            

            <!-- شبکه کارت‌های محصولات -->
            <div class="product-grid">

                <?php
                while ( have_posts() ) :
                    the_post();

                    global $product;

                    $product = wc_get_product( get_the_ID() );

                    if ( ! $product ) {
                        continue;
                    }

                    get_template_part(
                        'components/product_card'
                    );

                endwhile;
                ?>

            </div>

            <?php
            // صفحه‌بندی محصولات
            do_action( 'woocommerce_after_shop_loop' );
            ?>

        <?php else : ?>

            <?php
            do_action( 'woocommerce_no_products_found' );
            ?>

        <?php endif; ?>

    </div>

</main>

<?php
get_footer( 'shop' );
?>
