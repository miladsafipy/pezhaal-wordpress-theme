<?php

global $product;

$main_image_id = $product->get_image_id();

$gallery_image_ids = $product->get_gallery_image_ids();

?>

<div class="product-gallery">

    <!-- تصویر اصلی -->
    <div class="product-gallery-main">

        <?php

        echo wp_get_attachment_image(
            $main_image_id,
            'large',
            false,
            array(
                'class' => 'product-main-image',
            )
        );

        ?>

    </div>


    <!-- تصاویر کوچک -->
    <div class="product-gallery-thumbnails">

        <?php

        // تصویر اصلی را هم به عنوان thumbnail نمایش می‌دهیم
        $thumbnail_ids = array_merge(
            array( $main_image_id ),
            $gallery_image_ids
        );

        foreach ( $thumbnail_ids as $index => $image_id ) :

            $full_image_url = wp_get_attachment_image_url(
                $image_id,
                'large'
            );

        ?>

            <button
                type="button"
                class="product-thumbnail <?php echo $index === 0 ? 'is-active' : ''; ?>"
                data-image="<?php echo esc_url( $full_image_url ); ?>"
            >

                <?php

                echo wp_get_attachment_image(
                    $image_id,
                    'thumbnail'
                );

                ?>

            </button>

        <?php endforeach; ?>

    </div>

</div>