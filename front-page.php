<?php get_header(); ?>

<main class="home-main-content">

    <section class="hero-section">

        <div class="hero-wrapper">

            <!-- =========================================
                 گل‌های تزئینی
                 آدرس عکس‌ها را از assets قرار بده
            ========================================== -->

            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/jasmine-1.png"
                class="hero-flower hero-flower-1" alt="" aria-hidden="true">

            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/H-yasi.svg"
                class="hero-flower hero-flower-2" alt="" aria-hidden="true">

            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/H-yasi.svg"
                class="hero-flower hero-flower-3" alt="" aria-hidden="true">

            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Home.svg"
                class="hero-flower hero-flower-4" alt="" aria-hidden="true">

            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Home.svg"
                class="hero-flower hero-flower-5" alt="" aria-hidden="true">

            <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Cate.svg"
                class="hero-flower hero-flower-6" alt="" aria-hidden="true">


            <!-- =========================================
                 HERO SLIDER
            ========================================== -->

            <div class="swiper heroSwiper">

                <div class="swiper-wrapper">

                    <?php

                    $has_any_slide = false;

                    for ($i = 1; $i <= 3; $i++):

                        $desktop_img = get_field(
                            'slide_' . $i . '_desktop'
                        );

                        $mobile_img = get_field(
                            'slide_' . $i . '_mobile'
                        );

                        $link = get_field(
                            'slide_' . $i . '_link'
                        );

                        if ($desktop_img):

                            $has_any_slide = true;

                            $mobile_img_src = $mobile_img
                                ? $mobile_img
                                : $desktop_img;

                            $slide_link = $link
                                ? $link
                                : home_url();

                            ?>

                            <div class="swiper-slide">

                                <a href="<?php echo esc_url($slide_link); ?>">

                                    <!-- دسکتاپ -->

                                    <img src="<?php echo esc_url($desktop_img); ?>" alt="نسیم استایل"
                                        class="hero-banner-img desktop-banner"
                                        loading="<?php echo $i === 1 ? 'eager' : 'lazy'; ?>">

                                    <!-- موبایل -->

                                    <img src="<?php echo esc_url($mobile_img_src); ?>" alt="نسیم استایل"
                                        class="hero-banner-img mobile-banner"
                                        loading="<?php echo $i === 1 ? 'eager' : 'lazy'; ?>">

                                </a>

                            </div>

                            <?php

                        endif;

                    endfor;

                    ?>

                </div>

                <?php if ($has_any_slide): ?>

                    <div class="swiper-pagination"></div>

                <?php endif; ?>

            </div>

        </div>

    </section>
    <!-- =========================================
         سکشن دسته‌بندی‌های محبوب (داینامیک از ووکامرس)
         ========================================= -->
    <section class="popular-categories">
        <!-- شاخه گل تزئینی (آدرس دقیق از پوشه آیکون‌های شما) -->
        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/yasi.jpeg" alt="تزئین گل"
            class="floral-branch-decor">

        <div class="cat-header">
            <div class="cat-title-box">
                <h2 class="cat-main-title">دسته‌بندی‌های محبوب</h2>
            </div>
            <!-- دریافت داینامیک لینک صفحه فروشگاه -->
            <a href="<?php echo esc_url(wc_get_page_id('shop') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/')); ?>"
                class="view-all-link">
                <span class="view-all-arrow">&larr;</span> مشاهده همه
            </a>
        </div>

        <div class="cat-scroll-container">

            <?php
            // تنظیمات دریافت دسته‌بندی‌های محصولات از ووکامرس
            $args = array(
                'taxonomy' => 'product_cat',
                'orderby' => 'count', // مرتب‌سازی بر اساس تعداد محصولات (محبوب‌ترین‌ها)
                'order' => 'DESC',
                'hide_empty' => false,   // نمایش دسته‌ها حتی اگر فعلاً محصولی ندارند
                'number' => 7        // نمایش نهایتاً ۷ دسته (برای حفظ زیبایی قالب)
            );

            $product_categories = get_terms($args);

            if (!empty($product_categories) && !is_wp_error($product_categories)) {
                foreach ($product_categories as $cat) {

                    // دریافت لینک و نام دسته
                    $cat_name = $cat->name;
                    $cat_link = get_term_link($cat);

                    // دریافت تصویر اختصاصی دسته از ووکامرس
                    $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
                    $cat_img_url = '';

                    if ($thumbnail_id) {
                        // اگر عکس آپلود شده بود، آدرس آن را بگیر
                        $cat_img_url = wp_get_attachment_url($thumbnail_id);
                    } else {
                        // اگر عکسی نداشت، یک عکس پیش‌فرض (خالی) نشان بده تا قالب به هم نریزد
                        $cat_img_url = wc_placeholder_img_src();
                    }
                    ?>

                    <a href="<?php echo esc_url($cat_link); ?>" class="cat-item-card">
                        <div class="cat-img-wrapper">
                            <img src="<?php echo esc_url($cat_img_url); ?>" alt="<?php echo esc_attr($cat_name); ?>"
                                class="cat-img">
                            <div class="cat-overlay"></div>
                        </div>
                        <h3 class="cat-name"><?php echo esc_html($cat_name); ?></h3>
                    </a>

                    <?php
                }
            } else {
                echo '<p style="text-align:center; width:100%; color:var(--primary-color);">هنوز دسته‌بندی برای محصولات ساخته نشده است.</p>';
            }
            ?>

        </div>
    </section>















    <!-- =========================================
         سکشن ۱: جدیدترین محصولات (New Arrivals)
    ========================================= -->
    <section class="new-arrivals-section">
        <div class="na-container">

            <!-- هدر استاندارد مشابه سکشن‌های دیگر -->
            <div class="bs-heading">
                <div class="bs-heading-text">
                    <span class="bs-mini-title">کالکشن فصل</span>
                    <h2>جدیدترین‌های نسیم</h2>
                </div>

                <!-- دکمه مشاهده همه در بالا سمت چپ -->
                <a href="<?php echo esc_url(site_url('/shop/?orderby=date')); ?>" class="bs-see-all">
                    مشاهده همه <span>←</span>
                </a>
            </div>

            <div class="na-slider-wrapper">
                <div class="swiper naSwiper">
                    <div class="swiper-wrapper">
                        <?php
                        $na_args = array(
                            'post_type' => 'product',
                            'posts_per_page' => 10,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        );
                        $new_arrivals = new WP_Query($na_args);

                        if ($new_arrivals->have_posts()):
                            while ($new_arrivals->have_posts()):
                                $new_arrivals->the_post();
                                echo '<div class="swiper-slide">';
                                get_template_part('template-parts/product-card');
                                echo '</div>';
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <!-- دکمه اضافی پایین کلاً حذف شد -->

        </div>
    </section>




    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.naSwiper', {
                slidesPerView: 2.2,
                spaceBetween: 12,
                grabCursor: true,
                breakpoints: {
                    480: { slidesPerView: 2.5, spaceBetween: 14 },
                    768: { slidesPerView: 3.5, spaceBetween: 16 },
                    1024: { slidesPerView: 4.5, spaceBetween: 18 },
                    1200: { slidesPerView: 5.5, spaceBetween: 20 },
                    1400: { slidesPerView: 6, spaceBetween: 20 }
                }
            });
        });
    </script>














    <!-- =========================================
         سکشن ویژگی‌ها و ضمانت‌های فروشگاه (نوار اعتماد)
         ========================================= -->
    <section class="store-features-section">
        <!-- تزئین گل سمت چپ (دقیقاً مشابه عکس) -->
        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/Home.svg" alt="گل تزئینی"
            class="feature-floral-decor">

        <div class="features-container">
            <!-- ۱: پشتیبانی -->
            <div class="feature-item">
                <div class="feature-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/suport.png"
                        alt="پشتیبانی ۲۴ ساعته">
                </div>
                <div class="feature-text">
                    <h4 class="feature-title">پشتیبانی ۲۴ ساعته</h4>
                    <p class="feature-desc">در تمام روزهای هفته</p>
                </div>
            </div>

            <!-- ۲: پرداخت ایمن -->
            <div class="feature-item">
                <div class="feature-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/padlock.png" alt="پرداخت ایمن">
                </div>
                <div class="feature-text">
                    <h4 class="feature-title">پرداخت ایمن</h4>
                    <p class="feature-desc">با درگاه معتبر</p>
                </div>
            </div>

            <!-- ۳: ضمانت اصالت -->
            <div class="feature-item">
                <div class="feature-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/guarantee.png"
                        alt="ضمانت اصالت کالا">
                </div>
                <div class="feature-text">
                    <h4 class="feature-title">ضمانت اصالت کالا</h4>
                    <p class="feature-desc">تا ۱۴ روز</p>
                </div>
            </div>

            <!-- ۴: ارسال سریع -->
            <div class="feature-item">
                <div class="feature-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/delivery-truck.png"
                        alt="ارسال سریع">
                </div>
                <div class="feature-text">
                    <h4 class="feature-title">ارسال سریع</h4>
                    <p class="feature-desc">به سراسر ایران</p>
                </div>
            </div>
        </div>
    </section>
















    <!-- =========================================
     سکشن پرفروش‌ترین‌ها (همراه با بنر هنری)
========================================= -->
    <section class="best-sellers-section">
        <div class="bs-container">

            <!-- بخش راست: محصولات -->
            <div class="bs-products-area">

                <div class="bs-heading">
                    <div class="bs-heading-text">
                        <span class="bs-mini-title">انتخاب مشتری‌ها</span>
                        <h2>پرفروش‌ترین‌ها</h2>
                        <p>محبوب‌ترین انتخاب‌های نسیم استایل</p>
                    </div>

                    <a href="<?php echo esc_url(site_url('/shop/')); ?>" class="bs-see-all">
                        مشاهده همه <span>←</span>
                    </a>
                </div>

                <div class="bs-slider-wrapper">
                    <div class="swiper bsSwiper">
                        <div class="swiper-wrapper">
                            <?php
                            $args = array(
                                'post_type' => 'product',
                                'posts_per_page' => 12, /* تعداد افزایش یافت تا اسلایدر پرتر باشد */
                                'meta_key' => 'total_sales',
                                'orderby' => 'meta_value_num',
                                'order' => 'DESC'
                            );
                            $best_sellers = new WP_Query($args);

                            if ($best_sellers->have_posts()):
                                while ($best_sellers->have_posts()):
                                    $best_sellers->the_post();
                                    echo '<div class="swiper-slide">';
                                    get_template_part('template-parts/product-card');
                                    echo '</div>';
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- بخش چپ: بنر -->
            <div class="bs-banner-wrapper">
                <a href="<?php echo esc_url(site_url('/shop/')); ?>" class="bs-banner-link">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/banerpor.jpg"
                        alt="پرفروش‌ترین محصولات نسیم استایل" class="bs-banner-img" loading="lazy" decoding="async">
                    <div class="bs-banner-content">

                    </div>
                </a>
            </div>

        </div>
    </section>

    <!-- اسکریپت Swiper -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.bsSwiper', {
                slidesPerView: 2.2, /* موبایل */
                spaceBetween: 10,
                grabCursor: true,
                breakpoints: {
                    480: { slidesPerView: 2.5, spaceBetween: 12 },
                    768: { slidesPerView: 3.5, spaceBetween: 16 },
                    1024: { slidesPerView: 4.5, spaceBetween: 18 },
                    1200: { slidesPerView: 5.5, spaceBetween: 20 },
                    1400: { slidesPerView: 6, spaceBetween: 20 } /* نمایش ۶ محصول در مانیتورهای بزرگ */
                }
            });
        });
    </script>

















    <!-- =========================================
     سکشن محصولات تخفیف‌خورده (بنر در راست)
========================================= -->
    <section class="sale-products-section">
        <div class="sale-container">

            <!-- بخش راست: بنر حراج (Special Offer) -->
            <div class="sale-banner-wrapper">
                <a href="<?php echo esc_url(site_url('/shop/?on_sale=1')); ?>" class="bs-banner-link sale-banner-style">
                    <!-- عکس بنر حراج (می‌توانید یک عکس جذاب با تم قرمز/بنفش ملایم بگذارید) -->
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/off.jpeg" alt="حراج ویژه"
                        class="bs-banner-img" loading="lazy">
                    <div class="bs-banner-content sale-content-style">
                        <span>فرصت طلایی</span>
                        <strong>حراج ویژه</strong>
                        <small>تخفیف‌های تکرارنشدنی</small>
                        <b>مشاهده همه ←</b>
                    </div>
                </a>
            </div>

            <!-- بخش چپ: اسلایدر محصولات حراجی -->
            <div class="sale-products-area">

                <div class="bs-heading">
                    <div class="bs-heading-text">
                        <span class="bs-mini-title">فرصت رو از دست نده!</span>
                        <h2>پیشنهادهای ویژه</h2>
                    </div>
                    <!-- دکمه فلش‌دار کنترل اسلایدر (اختیاری و شیک) -->
                    <div class="sale-nav-buttons">
                        <div class="swiper-button-prev sale-prev"></div>
                        <div class="swiper-button-next sale-next"></div>
                    </div>
                </div>

                <div class="bs-slider-wrapper">
                    <div class="swiper saleSwiper">
                        <div class="swiper-wrapper">
                            <?php
                            // کوئری هوشمند: فقط محصولاتی که تخفیف دارند
                            $sale_args = array(
                                'post_type' => 'product',
                                'posts_per_page' => 8,
                                'post__in' => array_merge(array(0), wc_get_product_ids_on_sale())
                            );
                            $sale_products = new WP_Query($sale_args);

                            if ($sale_products->have_posts()):
                                while ($sale_products->have_posts()):
                                    $sale_products->the_post();
                                    echo '<div class="swiper-slide">';
                                    get_template_part('template-parts/product-card');
                                    echo '</div>';
                                endwhile;
                                wp_reset_postdata();
                            else:
                                echo '<p>در حال حاضر محصول تخفیف‌داری موجود نیست.</p>';
                            endif;
                            ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- اسکریپت Swiper اختصاصی برای بخش حراج -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.saleSwiper', {
                slidesPerView: 2.2,
                spaceBetween: 10,
                grabCursor: true,
                navigation: {
                    nextEl: '.sale-next',
                    prevEl: '.sale-prev',
                },
                breakpoints: {
                    480: { slidesPerView: 2.5, spaceBetween: 12 },
                    768: { slidesPerView: 3, spaceBetween: 16 },
                    1024: { slidesPerView: 3.5, spaceBetween: 18 },
                    1200: { slidesPerView: 4.5, spaceBetween: 20 }
                }
            });
        });
    </script>
































    <!-- =========================================
     سکشن خواندنی‌ها و باشگاه مشتریان
========================================= -->
    <section class="ns-blog-vip-section">
        <div class="ns-bv-container">

            <!-- بخش راست: خواندنی‌ها (مقالات) -->
            <div class="ns-blog-area">
                <div class="bs-heading">
                    <div class="bs-heading-text">
                        <span class="bs-mini-title">مجله استایل</span>
                        <h2>خواندنی‌های نسیم</h2>
                        <p>بروزترین ترندها و نکات فشن</p>
                    </div>
                    <a href="<?php echo esc_url(site_url('/blog/')); ?>" class="bs-see-all">
                        همه مقالات <span>←</span>
                    </a>
                </div>

                <div class="ns-blog-grid">
                    <?php
                    // دریافت ۳ مقاله آخر وبلاگ
                    $blog_args = array(
                        'post_type' => 'post',
                        'posts_per_page' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    );
                    $blog_posts = new WP_Query($blog_args);

                    if ($blog_posts->have_posts()):
                        while ($blog_posts->have_posts()):
                            $blog_posts->the_post(); ?>

                            <article class="ns-blog-card">
                                <a href="<?php the_permalink(); ?>" class="ns-blog-img-link">
                                    <?php
                                    if (has_post_thumbnail()) {
                                        the_post_thumbnail('medium_large', array('class' => 'ns-blog-img'));
                                    } else {
                                        echo '<img src="' . get_template_directory_uri() . '/assets/images/default-blog.jpg" class="ns-blog-img" alt="مقاله">';
                                    }
                                    ?>
                                    <div class="ns-blog-date">
                                        <?php echo get_the_date('j F'); ?>
                                    </div>
                                </a>
                                <div class="ns-blog-content">
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <a href="<?php the_permalink(); ?>" class="ns-blog-readmore">مطالعه مقاله
                                        <span>&larr;</span></a>
                                </div>
                            </article>

                        <?php endwhile;
                        wp_reset_postdata();
                    else:
                        echo '<p style="color:var(--primary-color);">هنوز مقاله‌ای منتشر نشده است.</p>';
                    endif;
                    ?>
                </div>
            </div>

            <!-- بخش چپ: باشگاه مشتریان VIP (طراحی شیشه‌ای) -->
            <div class="ns-vip-area">
                <div class="ns-vip-box">
                    <!-- تزئینات بک‌گراند -->
                    <div class="ns-vip-blob-1"></div>
                    <div class="ns-vip-blob-2"></div>

                    <div class="ns-vip-content">
                        <div class="ns-vip-badge">VIP CLUB</div>
                        <h3 class="ns-vip-title">باشگاه مشتریان نسیم</h3>
                        <p class="ns-vip-desc">با عضویت در باشگاه، از تخفیف‌های ویژه، ارسال رایگان و دسترسی زودهنگام به
                            کالکشن‌های جدید بهره‌مند شوید.</p>

                        <a href="<?php echo esc_url(site_url('/my-account/')); ?>" class="ns-vip-btn">
                            عضویت و دریافت هدیه
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
















    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bsSwiper = new Swiper('.bsSwiper', {
                slidesPerView: 2.1, /* موبایل: دیدن بخشی از کارت بعدی برای هدایت کاربر */
                spaceBetween: 12,
                grabCursor: true,
                breakpoints: {
                    768: { slidesPerView: 3, spaceBetween: 16 },
                    1024: { slidesPerView: 3.5, spaceBetween: 20 }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bsSwiper = new Swiper('.bsSwiper', {
                slidesPerView: 2.2, /* در موبایل ۲.۲ کارت نشان می‌دهد */
                spaceBetween: 16,
                grabCursor: true,
                breakpoints: {
                    768: { slidesPerView: 3, spaceBetween: 20 },
                    1024: { slidesPerView: 3.5, spaceBetween: 24 } /* در دسکتاپ سایز کارت‌ها بزرگتر و شیک‌تر است */
                }
            });
        });
    </script>
















    <!-- =========================================
         سکشن شبکه‌های اجتماعی (طراحی مینیمال بوتیک)
    ========================================= -->
    <section class="ns-boutique-socials">
        <div class="boutique-social-container">

            <div class="boutique-social-content">
                <span class="bs-subtitle">کمی نزدیک‌تر...</span>
                <h2 class="bs-title">به دنیای نسیم بپیوندید</h2>
                <p class="bs-desc">پشت صحنه‌ها، استایل‌های روزمره، و کدهای تخفیف مخفی را در شبکه‌های اجتماعی ما پیدا
                    کنید.</p>

                <div class="boutique-social-links">
                    <!-- اینستاگرام -->
                    <a href="#" class="b-social-pill ig-style" target="_blank">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                        <span>اینستاگرام</span>
                    </a>

                    <!-- تلگرام -->
                    <a href="#" class="b-social-pill tg-style" target="_blank">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        <span>تلگرام</span>
                    </a>

                    <!-- ایتا -->
                    <a href="#" class="b-social-pill ei-style" target="_blank">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                            </path>
                        </svg>
                        <span>ایتا</span>
                    </a>
                </div>
            </div>

        </div>
    </section>




























    <!-- =========================================
         تصویر عرضی جداکننده (ژورنال)
    ========================================= -->
    <div class="ns-fullwidth-separator">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/icons/bg-yasi.png" alt="نسیم استایل"
            class="separator-img" loading="lazy">

    </div>










</main>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ==========================================
        // 1. منطق سایدبار موبایل (باز/بسته شدن و آکاردئون)
        // ==========================================
        const hamburgerBtn = document.querySelector('.hamburger-btn');
        const closeSidebarBtn = document.querySelector('.close-sidebar-btn');
        const sidebar = document.querySelector('.mobile-sidebar');
        const sidebarOverlay = document.querySelector('.mobile-sidebar-overlay');

        if (hamburgerBtn && sidebar) {
            hamburgerBtn.addEventListener('click', () => {
                sidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            const closeMenu = () => {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            };

            closeSidebarBtn.addEventListener('click', closeMenu);
            sidebarOverlay.addEventListener('click', closeMenu);
        }

        const mobileDropdowns = document.querySelectorAll('.mobile-sidebar .menu-item-has-children > a');
        mobileDropdowns.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const parentLi = this.parentElement;
                const subMenu = parentLi.querySelector('.sub-menu');

                document.querySelectorAll('.mobile-sidebar .menu-item-has-children').forEach(item => {
                    if (item !== parentLi) {
                        item.classList.remove('open');
                        const otherSub = item.querySelector('.sub-menu');
                        if (otherSub) $(otherSub).slideUp(300); // اگر jQuery دارید، وگرنه display: none
                        if (otherSub) otherSub.style.display = 'none';
                    }
                });

                if (parentLi.classList.contains('open')) {
                    parentLi.classList.remove('open');
                    subMenu.style.display = 'none';
                } else {
                    parentLi.classList.add('open');
                    subMenu.style.display = 'block';
                }
            });
        });

        // ==========================================
        // 2. منطق جستجوی زنده AJAX (دسکتاپ و موبایل)
        // ==========================================
        const searchInputs = document.querySelectorAll('.ajax-search-field');
        let searchTimer;

        // باز کردن اورلی جستجو در موبایل
        const mobileSearchTrigger = document.querySelector('.mobile-search-trigger');
        const mobileSearchOverlay = document.querySelector('.mobile-search-overlay');
        const msfClose = document.querySelector('.msf-close');
        const msfInput = document.querySelector('.msf-input');

        if (mobileSearchTrigger) {
            mobileSearchTrigger.addEventListener('click', () => {
                mobileSearchOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                setTimeout(() => msfInput.focus(), 300); // فوکوس خودکار روی اینپوت
            });
            msfClose.addEventListener('click', () => {
                mobileSearchOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        // پردازش تایپ کاربر برای هر دو باکس (دسکتاپ و موبایل)
        searchInputs.forEach(input => {
            input.addEventListener('keyup', function () {
                clearTimeout(searchTimer);
                let keyword = this.value.trim();
                // پیدا کردن باکس نتایج مربوط به همین اینپوت
                let resultsContainer = this.closest('.ns-ajax-search') ?
                    this.closest('.ns-ajax-search').querySelector('.ns-search-results') :
                    document.querySelector('.msf-results');

                if (keyword.length < 3) {
                    if (this.classList.contains('msf-input')) {
                        resultsContainer.innerHTML = '<div class="msf-empty-state">عبارت خود را جستجو کنید...</div>';
                    } else {
                        resultsContainer.style.display = 'none';
                    }
                    return;
                }

                searchTimer = setTimeout(function () {
                    resultsContainer.innerHTML = '<div class="ns-search-loading">در حال جستجو...</div>';
                    if (!input.classList.contains('msf-input')) resultsContainer.style.display = 'block';

                    let formData = new FormData();
                    formData.append('action', 'ns_live_search'); // این اکشن در functions.php نوشته شده است
                    formData.append('keyword', keyword);

                    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                let html = '';
                                data.forEach(item => {
                                    html += `
                            <a href="${item.url}" class="ns-search-item">
                                <img src="${item.image}" alt="${item.title}">
                                <div class="ns-search-info">
                                    <h4>${item.title}</h4>
                                    <span class="ns-search-price">${item.price}</span>
                                </div>
                            </a>`;
                                });
                                html += `<a href="<?php echo site_url('?s='); ?>${keyword}&post_type=product" class="ns-search-all-btn">مشاهده همه نتایج</a>`;
                                resultsContainer.innerHTML = html;
                            } else {
                                resultsContainer.innerHTML = '<div class="ns-no-result">محصولی یافت نشد!</div>';
                            }
                        });
                }, 500);
            });
        });

        // بستن باکس نتایج دسکتاپ با کلیک در بیرون
        document.addEventListener('click', function (e) {
            const desktopSearch = document.querySelector('.desktop-search');
            if (desktopSearch && !desktopSearch.contains(e.target)) {
                const results = desktopSearch.querySelector('.ns-search-results');
                if (results) results.style.display = 'none';
            }
        });
    });
</script>


<script>

    document.addEventListener('DOMContentLoaded', function () {

        const heroElement = document.querySelector('.heroSwiper');

        if (!heroElement) return;

        new Swiper(heroElement, {

            loop: true,

            speed: 600,

            autoplay: {
                delay: 4500,
                disableOnInteraction: false
            },

            effect: 'fade',

            fadeEffect: {
                crossFade: true
            },

            pagination: {
                el: '.swiper-pagination',
                clickable: true
            }

        });

    });

</script>

<?php get_footer(); ?>

</body>

</html>