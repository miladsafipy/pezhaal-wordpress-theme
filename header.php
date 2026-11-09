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
<?php wp_body_open(); ?>

<header class="site-header">

    <div class="container navbar">

        <!-- سمت راست: لوگو -->
         <!-- Mobile Menu Button -->
        <button class="navbar__hamburger" id="menu-toggle" aria-label="باز کردن منو" aria-expanded="false" aria-controls="mobile-sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>

         <!-- Desktop Logo -->
        <a
            class="site-logo"
            href="<?php echo esc_url(home_url('/')); ?>"
        >

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

            <span class="site-logo-name">
                پژال
            </span>

        </a>


        <!-- وسط: منوی اصلی -->
         <!-- Desktop Menu -->
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


        <!-- سمت چپ: سبد خرید و حساب کاربری -->
         <!-- Desktop Actions -->
        <div class="navbar-actions">

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

        

        <!-- Search -->
        <div class="navbar-search-field">
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
        </div>


    </div>



    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobile-overlay"></div>



    <!-- Mobile SideBar -->
    <aside class="mobile-sidebar" id="mobile-sidebar" aria-hidden="true">

        <!-- Mobile Sidebar Header -->
        <div class="mobile-sidebar__header">

            <div class="mobile-sidebar__logo">
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
                <span>پژال</span>
            </div>

            <button class="mobile-sidebar__close" id="menu-close" aria-label="بستن منو">
                &times;
            </button>
        </div>
        

        <!-- Mobile Sidebar Menus -->
         <nav class="mobile-sidebar__menu">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu-list',
                    'fallback_cb'    => false,
                )
            );
            ?>
         </nav>

         <!-- Mobile Sidebar Actions -->
          <div class="mobile-sidebar__actions">

            <!-- سبد خرید -->
            <a
                class="navbar-icon cart-icon"
                href="<?php echo esc_url(wc_get_cart_url()); ?>"
                aria-label="سبد خرید"
            >
                <span class="icon-cart">
                    <i data-lucide="shopping-cart"></i>
                </span>
                <label for="icon-cart">سبد خرید</label>
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
                <label for="icon-account">حساب کاربری</label>
            </a>
          </div>

    </aside>

</header>

