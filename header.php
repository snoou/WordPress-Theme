<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php wp_title('|', true, 'right');
    bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <!-- =========================================
     1. هدر دسکتاپ (شناور، شیشه‌ای + جستجوی زنده)
========================================= -->
    <header class="luxury-desktop-header desktop-only">
        <div class="luxury-header-inner">

            <!-- راست: برند -->
            <div class="header-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-text">نسیم استایل</a>
            </div>

            <!-- مرکز: منوها -->
            <div class="header-nav-center">
                <?php wp_nav_menu(array('theme_location' => 'primary_menu', 'container' => false, 'fallback_cb' => false)); ?>
                <span class="nav-separator"></span>
                <?php wp_nav_menu(array('theme_location' => 'category_menu', 'container' => false, 'fallback_cb' => false)); ?>
            </div>

            <!-- چپ: جستجو + آیکون‌ها -->
            <div class="header-icons-left">

                <!-- باکس جستجوی دسکتاپ -->
                <div class="ns-ajax-search desktop-search">
                    <div class="ns-search-input-wrapper">
                        <input type="text" class="ns-search-input ajax-search-field" placeholder="جستجوی محصول..."
                            autocomplete="off">
                        <svg class="ns-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <div class="ns-search-results"></div>
                </div>

                <?php $cart_count = WC()->cart->get_cart_contents_count(); ?>
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="luxury-icon-btn">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Cart.svg" alt="سبد خرید"
                        class="desktop-svg-img">
                    <?php if ($cart_count > 0): ?>
                        <span class="luxury-badge"><?php echo esc_html($cart_count); ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>"
                    class="luxury-icon-btn">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Profile.svg" alt="پروفایل"
                        class="desktop-svg-img">
                </a>
            </div>

        </div>
    </header>

    <!-- =========================================
     2. هدر بالای موبایل (فقط لوگو، همبرگر و ذره‌بین)
========================================= -->
    <header class="mobile-top-header mobile-only">
        <button class="hamburger-btn" aria-label="منو">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-brand-text">نسیم استایل</a>

        <button class="mobile-search-trigger" aria-label="جستجو">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
    </header>

    <!-- =========================================
     3. جستجوی تمام‌صفحه موبایل (App-like Overlay)
========================================= -->
    <div class="mobile-search-overlay mobile-only">
        <div class="msf-header">
            <input type="text" class="msf-input ajax-search-field" placeholder="نام محصول را تایپ کنید..."
                autocomplete="off">
            <button class="msf-close">&times;</button>
        </div>
        <div class="ns-search-results msf-results">
            <div class="msf-empty-state">عبارت خود را جستجو کنید...</div>
        </div>
    </div>

    <!-- =========================================
     4. سایدبار کشویی موبایل
========================================= -->
    <div class="mobile-sidebar-overlay mobile-only"></div>
    <aside class="mobile-sidebar mobile-only">
        <div class="sidebar-header">
            <h3>دسترسی سریع</h3>
            <button class="close-sidebar-btn">&times;</button>
        </div>
        <div class="sidebar-menu-wrapper">
            <?php wp_nav_menu(array('theme_location' => 'primary_menu', 'container' => false, 'fallback_cb' => false)); ?>
            <hr class="sidebar-divider">
            <?php wp_nav_menu(array('theme_location' => 'category_menu', 'container' => false, 'fallback_cb' => false)); ?>
        </div>
    </aside>

    <!-- =========================================
     5. نوار پایین موبایل (Bottom Nav)
========================================= -->
    <nav class="artistic-mobile-nav mobile-only">
        <?php
        $is_home = is_front_page() || is_home() ? 'active' : '';
        $is_shop = is_shop() || is_product_category() || is_product() ? 'active' : '';
        $is_cart = is_cart() ? 'active' : '';
        $is_account = is_account_page() ? 'active' : '';
        ?>

        <a href="<?php echo esc_url(home_url('/')); ?>" class="art-nav-item <?php echo $is_home; ?>">
            <div class="art-nav-icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Home.svg"
                    alt="خانه" class="mobile-svg-img"></div>
        </a>
        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>"
            class="art-nav-item <?php echo $is_shop; ?>">
            <div class="art-nav-icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Cate.svg"
                    alt="دسته‌بندی" class="mobile-svg-img"></div>
        </a>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="art-nav-item <?php echo $is_cart; ?>">
            <div class="art-nav-icon position-relative">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Cart.svg" alt="سبد خرید"
                    class="mobile-svg-img">
                <?php if ($cart_count > 0): ?>
                    <span class="art-mobile-badge"><?php echo esc_html($cart_count); ?></span>
                <?php endif; ?>
            </div>
        </a>
        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>"
            class="art-nav-item <?php echo $is_account; ?>">
            <div class="art-nav-icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Profile.svg"
                    alt="پروفایل" class="mobile-svg-img"></div>
        </a>
    </nav>