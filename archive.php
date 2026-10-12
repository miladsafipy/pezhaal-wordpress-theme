<?php

get_header();

?>

<main class="archive-page">

    <h1>
        آرشیو
    </h1>

    <?php if ( have_posts() ) : ?>

        <div class="archive-content">

            <?php while ( have_posts() ) : the_post(); ?>

                <article class="archive-item">

                    <h2>
                        <?php the_title(); ?>
                    </h2>

                </article>

            <?php endwhile; ?>

        </div>

    <?php else : ?>

        <p>
            محتوایی پیدا نشد.
        </p>

    <?php endif; ?>

</main>

<?php

get_footer();

?>