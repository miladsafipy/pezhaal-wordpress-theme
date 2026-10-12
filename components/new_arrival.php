<?php
$products = wc_get_products([
    'limit'   => 4,
    'status'  => 'publish',
    'orderby' => 'date',
    'order'   => 'DESC',
]);

?>

<section class="new-arrival-section">

    <div class="new-arrival__container">

        <div class="new-arrival__header">
            <div class="new-arrival__heading">
                <span class="new-arrival__eyebrow">
                    جدیدترین محصولات
                </span>
                <div class="title-divider"></div>
            </div>
        </div>
    
    
        <div class="new-arrival__track">
    
            <?php foreach ($products as $product) : ?>
                
            
                <div class="new-arrival__card">

                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                            
                        <div class="new-arrival-card__image-wrapper">
                            <img src="<?php echo esc_url(wp_get_attachment_image_url(
                                $product->get_image_id(),
                                'woocommerce_thumbnail'
                            )); ?>"
                             alt="<?php echo esc_attr($product -> get_name()); ?>">
                        </div>
    
                        <div class="new-arrival__content">
    
                            <div class="new-arrival__name">
                                <h4>
                                    <?php echo esc_html($product->get_name()); ?>
                                </h4>
                            </div>
                    
                    
                            <div class="new-arrival__price">
                                <?php echo wp_kses_post($product->get_price_html()); ?>
                            </div>
            
                            <div class="new-arrival__button">
                                <span>مشاهده محصول</span>
                            </div>
    
                        </div>

                    </a>
    
    
            
                </div>
            
            <?php endforeach; ?>
    
        </div>

    </div>



</section>
