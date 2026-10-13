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