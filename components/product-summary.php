<?php

global $product;

?>

<div class="product-summary">

    <div class="product-category">

        <?php

        $categories = get_the_terms(
            $product->get_id(),
            'product_cat'
        );

        if ( $categories && ! is_wp_error( $categories ) ) :

            foreach ( $categories as $category ) :

        ?>

                <a
                    href="<?php echo esc_url( get_term_link( $category ) ); ?>"
                >
                    <?php echo esc_html( $category->name ); ?>
                </a>

        <?php

            endforeach;

        endif;

        ?>

    </div>


    <h1 class="product-title">

        <?php echo esc_html( $product->get_name() ); ?>

    </h1>



    <!-- Activa Rating Stars -->


    <?php
    $average_rating = (float) $product->get_average_rating();
    $rating_count   = (int) $product->get_rating_count();
    ?>

    <div class="product-rating">

        <div class="product-rating__stars">
            <?php
            echo wc_get_rating_html(
                $average_rating,
                $rating_count
            );
            ?>
        </div>

        <span class="product-rating__average">
            <?php echo esc_html( number_format_i18n( $average_rating, 1 ) ); ?>
            از ۵
        </span>

        <span class="product-rating__count">
            (<?php echo esc_html( number_format_i18n( $rating_count ) ); ?>
            رأی)
        </span>

    </div>




    <?php if ( $product->get_description() ) : ?>

        <div class="product-description">

            <?php
            echo wp_kses_post(
                $product->get_description()
            );
            ?>

        </div>

    <?php endif; ?>


    <div class="product-price">
        <span class="price-text">
            قیمت: 
        </span>

        <span class="price-count">
            <?php echo $product->get_price_html(); ?>
        </span>


    </div>


    


    <div class="product-stock">

        <span>
            وضعیت موجودی: 
        </span>

        <div>

            <?php if ( $product->is_in_stock() ) : ?>
    
                <span>
                    موجود در انبار
                </span>
    
                <?php if ( $product->managing_stock() ) : ?>
    
                    <span>
                        (<?php echo esc_html( $product->get_stock_quantity() ); ?> عدد)
                    </span>
    
                <?php endif; ?>
    
            <?php else : ?>
    
                <span>
                    ناموجود
                </span>
    
            <?php endif; ?>
        </div>


    </div>


    <form
        class="product-purchase cart"
        method="post"
        action="<?php echo esc_url( wc_get_cart_url() ); ?>"
    >

        <div class="product-quantity">

            <label for="product-quantity">
                تعداد سفارش: 
            </label>


            <input
                type="number"
                id="quantity-<?php echo esc_attr( $product->get_id() ); ?>"
                name="quantity"
                value="1"
                min="1"
                max="<?php echo esc_attr( $product->get_max_purchase_quantity() ); ?>"
                step="1"
            >

        </div>

        <button
            type="submit"
            name="add-to-cart"
            value="<?php echo esc_attr( $product->get_id() ); ?>"
            class="product-button"
        >
            افزودن به سبد خرید
        </button>

    </form>

</div>