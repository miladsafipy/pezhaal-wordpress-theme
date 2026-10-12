    <section class="hero-slider">

        <div class="hero-slides">

            <?php

            $hero_slides = new WP_Query(
                array(
                    'post_type'      => 'hero_slide',
                    'posts_per_page' => -1,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                )
            );

            if ($hero_slides->have_posts()) :

                $slide_index = 0;
                while ($hero_slides->have_posts()) :

                    $hero_slides->the_post();

                    $description = get_post_meta(
                        get_the_ID(),
                        '_hero_description',
                        true
                    );

                    $button_text = get_post_meta(
                        get_the_ID(),
                        '_hero_button_text',
                        true
                    );

                    $button_url = get_post_meta(
                        get_the_ID(),
                        '_hero_button_url',
                        true
                    );

                    $image_url = get_the_post_thumbnail_url(
                        get_the_ID(),
                        'full'
                    );
                    ?>

                    <article
                        class="hero-slide <?php echo $slide_index === 0 ? 'active' : ''; ?>"
                        style="background-image: url('<?php echo esc_url($image_url); ?>');"
                    >

                        <div class="hero-overlay"></div>

                        <div class="hero-content">

                            <h3>
                                <?php
                                the_title();
                                ?>
                            </h3>

                            <?php if ($description) : ?>

                                <p>
                                    <?php
                                    echo esc_html($description);
                                    ?>
                                </p>

                            <?php endif; ?>


                            <?php if ($button_text && $button_url) : ?>

                                <a
                                    href="<?php echo esc_url($button_url); ?>"
                                    class="hero-button"
                                >
                                    <?php
                                    echo esc_html($button_text);
                                    ?>
                                </a>

                            <?php endif; ?>

                        </div>

                    </article>

                    <?php

                    $slide_index++;

                endwhile;

                wp_reset_postdata();

            endif;

            ?>

        </div>


        <button
            class="hero-arrow hero-prev"
            aria-label="اسلاید قبلی"
        >
            ‹
        </button>


        <button
            class="hero-arrow hero-next"
            aria-label="اسلاید بعدی"
        >
            ›
        </button>


        <div class="hero-dots">

            <?php

            if ($hero_slides->post_count > 0) :

                for ($i = 0; $i < $hero_slides->post_count; $i++) :
                    ?>

                    <button
                        class="hero-dot <?php echo $i === 0 ? 'active' : ''; ?>"
                        data-slide="<?php echo $i; ?>"
                        aria-label="رفتن به اسلاید <?php echo $i + 1; ?>"
                    ></button>

                    <?php
                endfor;

            endif;

            ?>

        </div>

    </section>