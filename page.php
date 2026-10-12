<?php

get_header();

?>

<main class="page-content">

    <?php if ( have_posts() ) : ?>

        <?php while ( have_posts() ) : the_post(); ?>

            <article class="page">

                <h1>
                    <?php the_title(); ?>
                </h1>

                <div class="page-body">
                    <?php the_content(); ?>
                </div>

            </article>

        <?php endwhile; ?>

    <?php endif; ?>

</main>

<?php

get_footer();

?>