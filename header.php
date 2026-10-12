<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php bloginfo('name'); ?>
    </title>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="site-header">

    <div class="container navbar">

        <!-- سمت چپ: سبد خرید و حساب کاربری -->
        <div class="navbar-actions">

            <!-- جستجو -->
            <form
                class="navbar-search"
                method="get"
                action="<?php echo esc_url(home_url('/')); ?>"
            >

                <input
                    type="search"
                    name="s"
                    placeholder="جستجو..."
                    aria-label="جستجو"
                >

                <button type="submit" aria-label="جستجو">
                    <i data-lucide="search"></i>
                </button>

            </form>


            <!-- سبد خرید -->
            <a
                class="navbar-icon cart-icon"
                href="<?php echo esc_url(wc_get_cart_url()); ?>"
                aria-label="سبد خرید"
            >
                <span class="icon-cart">
                    <i data-lucide="shopping-cart"></i>
                </span>
            </a>


            <!-- حساب کاربری -->
            <a
                class="navbar-icon account-icon"
                href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
                aria-label="حساب کاربری"
            >
                <span class="icon-account">
                    <i data-lucide="user"></i>
                </span>
            </a>

        </div>


        <!-- وسط: منوی اصلی -->
        <nav class="site-navigation">

            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                )
            );
            ?>

        </nav>


        <!-- سمت راست: لوگو -->
        <a
            class="site-logo"
            href="<?php echo esc_url(home_url('/')); ?>"
        >

            <span class="site-logo-name">
                پژال
            </span>

            <?php
            $logo_id = get_theme_mod('pezhaal_logo');

            if ($logo_id) :

                echo wp_get_attachment_image(
                    $logo_id,
                    'full',
                    false,
                    array(
                        'class' => 'site-logo-image',
                        'alt'   => get_bloginfo('name'),
                    )
                );

            endif;
            ?>

            

        </a>

    </div>

</header>