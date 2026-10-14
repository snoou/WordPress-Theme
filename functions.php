<?php
if (!defined('ABSPATH')) exit;

function nasim_style_scripts() {
    wp_enqueue_style('nasim-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), time());
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'nasim_style_scripts');

function nasim_style_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    
    remove_theme_support('wc-product-gallery-zoom');
    remove_theme_support('wc-product-gallery-lightbox');
    remove_theme_support('wc-product-gallery-slider');

    register_nav_menus(array(
        'primary_menu'  => 'منوی صفحات اصلی (راست)',
        'category_menu' => 'منوی دسته‌بندی‌ها (چپ)'
    ));
}
add_action('after_setup_theme', 'nasim_style_setup');

/* =====================================================
   حذف سایدبارهای مزاحم وردپرس
===================================================== */
add_action('wp', 'ns_remove_all_sidebars');
function ns_remove_all_sidebars() {
    if (is_woocommerce() || is_shop() || is_product_category() || is_product()) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
}

/* =====================================================
   نمایش فیلترهای فعال (بالای محصولات)
===================================================== */
add_action('woocommerce_before_shop_loop', 'ns_show_active_filters_top', 15);
function ns_show_active_filters_top() {
    if (!is_shop() && !is_product_category()) return;
    
    $has_filters = false;
    $html = '<ul class="ns-active-filters-top">';
    
    if (!empty($_GET['min_price'])) {
        $html .= '<li>حداقل: ' . esc_html($_GET['min_price']) . ' تومان</li>';
        $has_filters = true;
    }
    if (!empty($_GET['max_price'])) {
        $html .= '<li>حداکثر: ' . esc_html($_GET['max_price']) . ' تومان</li>';
        $has_filters = true;
    }
    if (!empty($_GET['product_cat'])) {
        $cat = get_term_by('slug', $_GET['product_cat'], 'product_cat');
        if ($cat) {
            $html .= '<li>دسته: ' . esc_html($cat->name) . '</li>';
            $has_filters = true;
        }
    }
    
    if ($has_filters) {
        $shop_url = wc_get_page_permalink('shop');
        $html .= '<li><a href="'.esc_url($shop_url).'" class="ns-clear-all-btn">حذف همه فیلترها</a></li>';
        $html .= '</ul>';
        echo $html;
    }
}

/* =====================================================
   گالری Swiper صفحه محصول
===================================================== */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_before_single_product_summary', 'ns_custom_swiper_gallery', 20 );

function ns_custom_swiper_gallery() {
    global $product;
    $main_image_id = $product->get_image_id();
    $gallery_image_ids = $product->get_gallery_image_ids();
    
    $all_ids = array();
    if ( $main_image_id ) $all_ids[] = $main_image_id;
    if ( !empty($gallery_image_ids) ) $all_ids = array_merge($all_ids, $gallery_image_ids);
    
    if ( empty($all_ids) ) {
        echo '<div class="ns-custom-gallery"><img src="' . wc_placeholder_img_src() . '" alt="Placeholder"/></div>';
        return;
    }
    
    echo '<div class="ns-custom-gallery-wrapper">';
    echo '<div class="swiper ns-main-swiper"><div class="swiper-wrapper">';
    foreach ( $all_ids as $id ) {
        $full_img = wp_get_attachment_image_url($id, 'full');
        echo '<div class="swiper-slide"><img src="' . esc_url($full_img) . '" alt=""/></div>';
    }
    echo '</div><div class="swiper-pagination"></div></div>';
    
    if ( count($all_ids) > 1 ) {
        echo '<div class="swiper ns-thumb-swiper"><div class="swiper-wrapper">';
        foreach ( $all_ids as $id ) {
            $thumb_img = wp_get_attachment_image_url($id, 'woocommerce_thumbnail');
            echo '<div class="swiper-slide"><img src="' . esc_url($thumb_img) . '" alt=""/></div>';
        }
        echo '</div></div>';
    }
    echo '</div>';
    
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            var thumbSwiper = new Swiper('.ns-thumb-swiper', {
                spaceBetween: 12, slidesPerView: 4, freeMode: true, watchSlidesProgress: true,
                breakpoints: { 320: { slidesPerView: 4, spaceBetween: 8 }, 768: { slidesPerView: 4, spaceBetween: 12 } }
            });
            var mainSwiper = new Swiper('.ns-main-swiper', {
                spaceBetween: 10, effect: 'slide',
                pagination: { el: '.swiper-pagination', clickable: true },
                thumbs: { swiper: thumbSwiper }
            });
            jQuery(document).on('found_variation', 'form.variations_form', function(event, variation) {
                if(variation && variation.image && variation.image.full_src) {
                    jQuery('.ns-main-swiper .swiper-slide:first-child img').attr('src', variation.image.full_src);
                    mainSwiper.slideTo(0);
                }
            });
        }
    });
    </script>
    <?php
}

/* =====================================================
   اسکریپت نوار ابزار فروشگاه، فرم فیلتر اختصاصی و منوی موبایل
===================================================== */
add_action('wp_footer', 'ns_shop_toolbar_and_mobile_menu_script', 99);
function ns_shop_toolbar_and_mobile_menu_script() {
    // -----------------------------------------------------
    // مقادیر فیلتر (فقط در صفحات فروشگاه محاسبه شوند)
    // -----------------------------------------------------
    $min_price = isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : '';
    $max_price = isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : '';
    $current_cat = isset($_GET['product_cat']) ? wc_clean($_GET['product_cat']) : '';
    $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
    ?>
    
    <script>
    jQuery(document).ready(function($) {
        // ==========================================
        // ۱. اسکریپت منوی همبرگری موبایل (Global - در همه صفحات)
        // ==========================================
        $('body').on('click', '.hamburger-btn', function(e) {
            e.preventDefault();
            $('.mobile-sidebar, .mobile-sidebar-overlay').addClass('active');
            $('body').css('overflow', 'hidden'); 
        });

        $('body').on('click', '.close-sidebar-btn, .mobile-sidebar-overlay', function(e) {
            e.preventDefault();
            $('.mobile-sidebar, .mobile-sidebar-overlay').removeClass('active');
            $('body').css('overflow', 'auto');
        });

        // ==========================================
        // آپدیت خودکار سبد خرید هنگام تغییر تعداد
        // ==========================================
        let timeout;
        $('body').on('change', 'input.qty', function() {
            if ( timeout !== undefined ) clearTimeout( timeout );
            timeout = setTimeout(function() {
                $('[name="update_cart"]').prop('disabled', false).trigger('click');
            }, 500); // نیم ثانیه صبر می‌کند تا کاربر تایپ یا کلیکش تمام شود
        });

        // ==========================================
        // ۲. اسکریپت‌های مربوط به فروشگاه (فقط اگر عناصر فروشگاه وجود داشتند)
        // ==========================================
        if ($('.woocommerce-result-count').length || $('.woocommerce-ordering').length || $('.ns-filter-btn').length) {
            
            // ساخت نوار ابزار و دکمه فیلتر
            if (!$('.ns-shop-toolbar').length && $('.woocommerce-ordering').length) {
                $('<div class="ns-shop-toolbar"></div>').insertBefore('.woocommerce-result-count');
                var filterBtn = $('<button class="ns-filter-btn"><svg class="ns-filter-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg> فیلتر محصولات</button>');
                var leftGroup = $('<div class="ns-toolbar-left"></div>').append(filterBtn);
                var rightGroup = $('<div class="ns-toolbar-right"></div>').append($('.woocommerce-result-count')).append($('.woocommerce-ordering'));
                $('.ns-shop-toolbar').append(leftGroup).append(rightGroup);
            }

            // باز و بسته شدن کشوی فیلتر محصولات
            $('body').on('click', '.ns-filter-btn', function(e) {
                e.preventDefault();
                $('.ns-offcanvas-filter, .ns-filter-overlay').addClass('active');
                $('body').css('overflow', 'hidden'); 
            });

            $('body').on('click', '.ns-filter-close, .ns-filter-overlay', function(e) {
                // اگر روی اورلی منوی موبایل کلیک شد، این رویداد رو نگیره
                if($(this).hasClass('mobile-sidebar-overlay')) return;
                
                e.preventDefault();
                $('.ns-offcanvas-filter, .ns-filter-overlay').removeClass('active');
                $('body').css('overflow', 'auto');
            });
        }
    });
    </script>

    <?php 
    // ==========================================
    // ساختار HTML پنل کشویی فیلتر (فقط در فروشگاه چاپ شود)
    // ==========================================
    if (is_woocommerce() || is_shop() || is_product_category()) : ?>
    <div class="ns-filter-overlay"></div>
    <div class="ns-offcanvas-filter">
        <div class="ns-filter-header">
            <h3 class="ns-filter-title">فیلتر محصولات</h3>
            <button class="ns-filter-close">×</button>
        </div>
        <div class="ns-filter-content">
            
            <form action="<?php echo esc_url($shop_url); ?>" method="GET" class="ns-custom-filter-form">
                
                <!-- بخش دسته‌بندی‌ها -->
                <div class="ns-filter-group">
                    <label>دسته‌بندی محصولات</label>
                    <?php
                    wp_dropdown_categories(array(
                        'taxonomy'          => 'product_cat',
                        'name'              => 'product_cat',
                        'show_option_none'  => 'همه دسته‌بندی‌ها',
                        'option_none_value' => '',
                        'value_field'       => 'slug',
                        'selected'          => $current_cat,
                        'class'             => 'ns-custom-select',
                        'hierarchical'      => true,
                        'hide_empty'        => true,
                    ));
                    ?>
                </div>

                <!-- بخش محدوده قیمت -->
                <div class="ns-filter-group">
                    <label>محدوده قیمت (تومان)</label>
                    <div class="ns-price-inputs">
                        <input type="number" name="min_price" placeholder="از مبلغ" value="<?php echo $min_price; ?>">
                        <span>تا</span>
                        <input type="number" name="max_price" placeholder="تا مبلغ" value="<?php echo $max_price; ?>">
                    </div>
                </div>

                <!-- دکمه‌ها -->
                <div class="ns-filter-actions">
                    <button type="submit" class="ns-filter-submit">اعمال فیلتر</button>
                    <a href="<?php echo esc_url($shop_url); ?>" class="ns-filter-reset">پاک کردن فیلترها</a>
                </div>

            </form>

        </div>
    </div>
    <?php endif; ?>
    
    <?php
}

/* =====================================================
   بهینه‌سازی و تمیزکاری صفحه تسویه حساب (Checkout)
   ترتیب فیلدها: نام، نام خانوادگی، (کشور)، استان، شهر، خیابان، کد پستی، تلفن
===================================================== */
add_filter( 'woocommerce_checkout_fields' , 'ns_custom_override_checkout_fields', 9999 );
function ns_custom_override_checkout_fields( $fields ) {
    // حذف فیلدهای اضافی
    unset($fields['billing']['billing_company']);   // نام شرکت
    unset($fields['billing']['billing_address_2']); // آدرس دوم
    unset($fields['billing']['billing_email']);     // ایمیل

    // نام و نام خانوادگی
    $fields['billing']['billing_first_name']['class']    = array('form-row-first');
    $fields['billing']['billing_first_name']['priority'] = 10;
    $fields['billing']['billing_last_name']['class']     = array('form-row-last');
    $fields['billing']['billing_last_name']['priority']  = 20;

    // کشور (اگر فقط یک کشور فعال باشد ووکامرس خودش مخفی‌اش می‌کند)
    if ( isset($fields['billing']['billing_country']) ) {
        $fields['billing']['billing_country']['priority'] = 30;
    }

    // ۱. استان
    $fields['billing']['billing_state']['label']    = 'استان';
    $fields['billing']['billing_state']['required'] = true;
    $fields['billing']['billing_state']['class']    = array('form-row-first');
    $fields['billing']['billing_state']['priority'] = 40;

    // ۲. شهر
    $fields['billing']['billing_city']['label']    = 'شهر';
    $fields['billing']['billing_city']['required'] = true;
    $fields['billing']['billing_city']['class']    = array('form-row-last');
    $fields['billing']['billing_city']['priority'] = 50;

    // ۳. خیابان (آدرس)
    $fields['billing']['billing_address_1']['label']       = 'خیابان و آدرس دقیق';
    $fields['billing']['billing_address_1']['placeholder'] = 'خیابان، کوچه، پلاک، واحد';
    $fields['billing']['billing_address_1']['required']    = true;
    $fields['billing']['billing_address_1']['class']       = array('form-row-wide');
    $fields['billing']['billing_address_1']['priority']    = 60;

    // ۴. کد پستی
    $fields['billing']['billing_postcode']['label']    = 'کد پستی';
    $fields['billing']['billing_postcode']['required'] = true;
    $fields['billing']['billing_postcode']['class']    = array('form-row-first');
    $fields['billing']['billing_postcode']['priority'] = 70;

    // ۵. شماره تلفن (اجباری)
    $fields['billing']['billing_phone']['label']    = 'شماره تلفن';
    $fields['billing']['billing_phone']['required'] = true;
    $fields['billing']['billing_phone']['class']    = array('form-row-last');
    $fields['billing']['billing_phone']['priority'] = 80;

    // توضیحات سفارش
    $fields['order']['order_comments']['placeholder'] = 'اگر یادداشتی برای تحویل کالا دارید اینجا بنویسید...';
    
    return $fields;
}

// فیلدهای آدرس پیش‌فرض ووکامرس (برای اطمینان از ترتیب و اجباری بودن)
add_filter( 'woocommerce_default_address_fields', 'ns_custom_default_address_fields' );
function ns_custom_default_address_fields( $fields ) {
    $fields['state']['priority']     = 40;
    $fields['city']['priority']      = 50;
    $fields['address_1']['priority'] = 60;
    $fields['postcode']['priority']  = 70;
    return $fields;
}

// حذف عبارت «اختیاری» از فیلد تلفن و نمایش ستاره اجباری
add_filter( 'woocommerce_form_field', 'ns_fix_phone_field_label', 9999, 4 );
function ns_fix_phone_field_label( $field, $key, $args, $value ) {
    if ( $key === 'billing_phone' ) {
        // حذف متن اختیاری (فارسی یا انگلیسی)
        $field = preg_replace( '/<span class="optional">.*?<\/span>/su', '', $field );
        // اضافه کردن ستاره اجباری اگر وجود نداشت
        if ( strpos( $field, 'class="required"' ) === false ) {
            $field = preg_replace(
                '/(<label[^>]*>.*?)(<\/label>)/su',
                '$1 <abbr class="required" title="ضروری">*</abbr>$2',
                $field,
                1
            );
        }
    }
    return $field;
}

// اعتبارسنجی سمت سرور: شماره تلفن حتماً باید پر شود
add_action( 'woocommerce_checkout_process', 'ns_validate_required_phone' );
function ns_validate_required_phone() {
    if ( empty( trim( $_POST['billing_phone'] ?? '' ) ) ) {
        wc_add_notice( 'وارد کردن شماره تلفن الزامی است.', 'error' );
    }
}

// تغییر متن دکمه ثبت سفارش
add_filter( 'woocommerce_order_button_text', 'ns_custom_button_text' );
function ns_custom_button_text() {
    return 'تکمیل و پرداخت نهایی'; 
}







/**
 * Nasim Style - Mobile-First App Architecture & Phone-Only Account System
 * Prefix: ns_acc_
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ۱. بارگذاری استایل‌ها و حذف استایل‌های مزاحم پیش‌فرض در صفحه حساب کاربری
 */
add_action( 'wp_enqueue_scripts', 'ns_acc_enqueue_assets', 999 );
function ns_acc_enqueue_assets() {
    if ( function_exists( 'is_account_page' ) && is_account_page() ) {
        wp_enqueue_style(
            'ns-vazirmatn-font',
            'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css',
            array(),
            '33.003'
        );

        wp_enqueue_style(
            'nasim-myaccount-style',
            get_stylesheet_directory_uri() . '/assets/css/nasim-myaccount.css',
            array( 'ns-vazirmatn-font' ),
            '2.0.0'
        );
    }
}

/**
 * ۲. تابع کمکی تبدیل اعداد فارسی و عربی به انگلیسی (رفع باگ کیبورد فارسی موبایل)
 */
function ns_acc_convert_persian_numbers( $string ) {
    $persian = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
    $arabic  = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
    $english = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );

    $string = str_replace( $persian, $english, $string );
    $string = str_replace( $arabic, $english, $string );
    return preg_replace( '/[^0-9]/', '', $string );
}

/**
 * ۳. دریافت شماره موبایل کاربر (سازگار با متاهای ووکامرس، دیجیتس و شاهکار)
 */
function ns_acc_get_user_phone( $user_id ) {
    $meta_keys = array( 'billing_phone', 'digits_phone_no', 'digits_phone', 'mobile' );
    foreach ( $meta_keys as $key ) {
        $phone = get_user_meta( $user_id, $key, true );
        if ( ! empty( $phone ) ) {
            return $phone;
        }
    }
    // اگر نام کاربری خودش شماره موبایل بود
    $user = get_userdata( $user_id );
    if ( $user && preg_match( '/^09[0-9]{9}$/', $user->user_login ) ) {
        return $user->user_login;
    }
    return '';
}

/**
 * ۴. حذف کامل الزام ایمیل در فرم ویرایش حساب کاربری و جایگزینی با شماره موبایل
 */
add_action( 'woocommerce_save_account_details_errors', 'ns_acc_handle_phone_only_account', 5, 2 );
function ns_acc_handle_phone_only_account( &$errors, &$user ) {
    $current_user = wp_get_current_user();

    // دریافت و نرمال‌سازی شماره موبایل ارسال‌شده
    $raw_phone = isset( $_POST['account_billing_phone'] ) ? wp_unslash( $_POST['account_billing_phone'] ) : '';
    $phone     = ns_acc_convert_persian_numbers( $raw_phone );

    if ( empty( $phone ) || ! preg_match( '/^09[0-9]{9}$/', $phone ) ) {
        $errors->add( 'billing_phone_error', 'لطفاً یک شماره موبایل معتبر ۱۱ رقمی (مثال: 09123456789) وارد نمایید.' );
    } else {
        // بررسی عدم تکراری بودن شماره موبایل برای کاربر دیگر
        $existing_users = get_users( array(
            'meta_key'   => 'billing_phone',
            'meta_value' => $phone,
            'exclude'    => array( $current_user->ID ),
            'number'     => 1,
            'fields'     => 'ID',
        ) );

        if ( ! empty( $existing_users ) ) {
            $errors->add( 'billing_phone_exists', 'این شماره موبایل پیش‌تر برای حساب کاربری دیگری ثبت شده است.' );
        } else {
            $_POST['account_billing_phone'] = $phone;
        }
    }

    // تنظیم خودکار نام نمایشی بر اساس نام و نام خانوادگی
    $first_name = isset( $_POST['account_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['account_first_name'] ) ) : '';
    $last_name  = isset( $_POST['account_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['account_last_name'] ) ) : '';
    if ( ! empty( $first_name ) || ! empty( $last_name ) ) {
        $full_name = trim( $first_name . ' ' . $last_name );
        $_POST['account_display_name'] = $full_name;
        $user->display_name            = $full_name;
    }

    // دور زدن الزام ایمیل در هسته ووکامرس بدون خطا
    if ( ! empty( $current_user->user_email ) ) {
        $_POST['account_email'] = $current_user->user_email;
        $user->user_email       = $current_user->user_email;
    } else {
        $fallback_phone         = ! empty( $phone ) ? $phone : $current_user->ID;
        $generated_email        = $fallback_phone . '@nasimstyle.local';
        $_POST['account_email'] = $generated_email;
        $user->user_email       = $generated_email;
    }
}

// ذخیره شماره موبایل در متای کاربر پس از تایید فرم
add_action( 'woocommerce_save_account_details', 'ns_acc_save_mobile_field', 10, 1 );
function ns_acc_save_mobile_field( $user_id ) {
    if ( ! empty( $_POST['account_billing_phone'] ) ) {
        $phone = ns_acc_convert_persian_numbers( wp_unslash( $_POST['account_billing_phone'] ) );
        update_user_meta( $user_id, 'billing_phone', $phone );
    }
}

/**
 * ۵. حذف فیلد ایمیل از فرم آدرس‌های ووکامرس و بهینه‌سازی فیلدهای ایران
 */
add_filter( 'woocommerce_billing_fields', 'ns_acc_remove_billing_email_field', 999 );
function ns_acc_remove_billing_email_field( $fields ) {
    if ( isset( $fields['billing_email'] ) ) {
        unset( $fields['billing_email'] );
    }
    if ( isset( $fields['billing_phone'] ) ) {
        $fields['billing_phone']['label']    = 'شماره موبایل تحویل‌گیرنده';
        $fields['billing_phone']['required'] = true;
        $fields['billing_phone']['clear']    = true;
    }
    return $fields;
}

add_filter( 'woocommerce_default_address_fields', 'ns_acc_customize_iran_address_fields', 999 );
function ns_acc_customize_iran_address_fields( $fields ) {
    unset( $fields['company'] );
    unset( $fields['address_2'] );

    $fields['first_name']['label']       = 'نام تحویل‌گیرنده';
    $fields['last_name']['label']        = 'نام خانوادگی تحویل‌گیرنده';
    $fields['state']['label']            = 'استان';
    $fields['city']['label']             = 'شهر';
    $fields['address_1']['label']        = 'نشانی دقیق پستی (خیابان، کوچه، پلاک، واحد)';
    $fields['address_1']['placeholder']  = 'مثال: خیابان شریعتی، کوچه یاس، پلاک ۱۲، واحد ۴';
    $fields['postcode']['label']         = 'کد پستی ۱۰ رقمی';

    return $fields;
}

/**
 * ۶. شخصی‌سازی منوی حساب کاربری
 */
add_filter( 'woocommerce_account_menu_items', 'ns_acc_customize_menu_items', 999, 1 );
function ns_acc_customize_menu_items( $items ) {
    $new_items = array(
        'dashboard'    => 'پیشخوان',
        'orders'       => 'سفارش‌ها',
        'edit-address' => 'آدرس‌ها',
        'edit-account' => 'پروفایل من',
    );

    if ( defined( 'YITH_WCWL' ) || class_exists( 'TInvWL_Public_AddToWishlist' ) || isset( $items['wishlist'] ) ) {
        $new_items['wishlist'] = 'علاقه‌مندی‌ها';
    }

    $new_items['customer-logout'] = 'خروج';

    return $new_items;
}

/**
 * ۷. آیکون‌های SVG اختصاصی
 */
function ns_acc_get_svg_icon( $name ) {
    $icons = array(
        'jasmine'         => '<svg class="ns-acc-jasmine-svg" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M32 6C26 16 26 24 32 30C38 24 38 16 32 6Z" fill="currentColor" fill-opacity="0.25"/><path d="M56 32C46 26 38 26 32 32C38 38 46 38 56 32Z" fill="currentColor" fill-opacity="0.25"/><path d="M32 58C38 48 38 40 32 34C26 40 26 48 32 58Z" fill="currentColor" fill-opacity="0.25"/><path d="M8 32C18 38 26 38 32 32C26 26 18 26 8 32Z" fill="currentColor" fill-opacity="0.25"/><path d="M49 15C39 18 34 24 34 30C40 30 46 25 49 15Z" fill="currentColor" fill-opacity="0.15"/><path d="M49 49C46 39 40 34 34 34C34 40 39 46 49 49Z" fill="currentColor" fill-opacity="0.15"/><path d="M15 49C25 46 30 40 30 34C24 34 18 39 15 49Z" fill="currentColor" fill-opacity="0.15"/><path d="M15 15C18 25 24 30 30 30C30 24 25 18 15 15Z" fill="currentColor" fill-opacity="0.15"/><circle cx="32" cy="32" r="4" fill="currentColor" fill-opacity="0.5"/></svg>',
        'dashboard'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="2"/><rect x="14" y="3" width="7" height="5" rx="2"/><rect x="14" y="12" width="7" height="9" rx="2"/><rect x="3" y="16" width="7" height="5" rx="2"/></svg>',
        'orders'          => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
        'edit-address'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
        'edit-account'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
        'wishlist'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
        'customer-logout' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
        'edit'            => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
        'calendar'        => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        'phone'           => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
        'chevron-left'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>',
    );
    return isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['dashboard'];
}



/**
 * ۸. اسکرول خودکار نوار منوی موبایل روی تب فعال برای تجربه کاربری نرم‌تر
 */
add_action( 'wp_footer', 'ns_acc_mobile_nav_auto_center', 99 );
function ns_acc_mobile_nav_auto_center() {
    if ( function_exists( 'is_account_page' ) && is_account_page() ) : ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var activeTab = document.querySelector('.ns-acc-nav__item--active');
            var navList = document.querySelector('.ns-acc-nav__list');
            if (activeTab && navList && window.innerWidth <= 991) {
                var scrollPos = activeTab.offsetLeft - (navList.clientWidth / 2) + (activeTab.clientWidth / 2);
                navList.scrollTo({ left: scrollPos, behavior: 'smooth' });
            }
        });
        </script>
    <?php endif;
}