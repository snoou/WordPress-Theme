<?php
/**
 * My Account Dashboard - Nasim Style Custom Template
 * Path: wp-content/themes/nasim-style-child/woocommerce/myaccount/dashboard.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$user_id = get_current_user_id();

// محاسبه آماری سفارش‌های کاربر
$all_orders = wc_get_orders( array(
    'customer_id' => $user_id,
    'limit'       => -1,
    'return'      => 'ids',
) );
$total_orders_count = count( $all_orders );

$processing_orders = wc_get_orders( array(
    'customer_id' => $user_id,
    'status'      => array( 'processing', 'on-hold', 'pending' ),
    'limit'       => -1,
    'return'      => 'ids',
) );
$active_orders_count = count( $processing_orders );

$completed_orders = wc_get_orders( array(
    'customer_id' => $user_id,
    'status'      => array( 'completed' ),
    'limit'       => -1,
    'return'      => 'ids',
) );
$completed_orders_count = count( $completed_orders );

// دریافت ۳ سفارش اخیر برای نمایش در پیشخوان
$recent_orders = wc_get_orders( array(
    'customer_id' => $user_id,
    'limit'       => 3,
    'orderby'     => 'date',
    'order'       => 'DESC',
) );
?>

<div class="ns-acc-dashboard">
    <!-- بنر خوش‌آمدگویی ظریف پیشخوان -->
    <div class="ns-acc-welcome-banner">
        <div class="ns-acc-welcome-banner__text">
            <h2 class="ns-acc-section-title">پیشخوان حساب کاربری</h2>
            <p class="ns-acc-section-desc">
                از این بخش می‌توانید آخرین وضعیت سفارش‌های خود را پیگیری کنید، آدرس‌های ارسال را مدیریت نمایید و اطلاعات کاربری خود را به‌روز نگه دارید.
            </p>
        </div>
        <div class="ns-acc-welcome-banner__decor" aria-hidden="true">
            <?php echo ns_acc_get_svg_icon( 'jasmine' ); ?>
        </div>
    </div>

    <!-- کارت‌های آماری وضعیت سفارش‌ها -->
    <div class="ns-acc-stats-grid">
        <div class="ns-acc-stat-card">
            <div class="ns-acc-stat-card__icon ns-acc-stat-card__icon--total">
                <?php echo ns_acc_get_svg_icon( 'orders' ); ?>
            </div>
            <div class="ns-acc-stat-card__body">
                <span class="ns-acc-stat-card__value"><?php echo esc_html( $total_orders_count ); ?> سفارش</span>
                <span class="ns-acc-stat-card__label">کل سفارش‌های ثبت‌شده</span>
            </div>
        </div>

        <div class="ns-acc-stat-card">
            <div class="ns-acc-stat-card__icon ns-acc-stat-card__icon--active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="ns-acc-stat-card__body">
                <span class="ns-acc-stat-card__value"><?php echo esc_html( $active_orders_count ); ?> سفارش</span>
                <span class="ns-acc-stat-card__label">در حال پردازش و ارسال</span>
            </div>
        </div>

        <div class="ns-acc-stat-card">
            <div class="ns-acc-stat-card__icon ns-acc-stat-card__icon--completed">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="ns-acc-stat-card__body">
                <span class="ns-acc-stat-card__value"><?php echo esc_html( $completed_orders_count ); ?> سفارش</span>
                <span class="ns-acc-stat-card__label">تحویل شده و تکمیل‌شده</span>
            </div>
        </div>
    </div>

    <!-- میانبرهای کاربردی دسترسی سریع -->
    <div class="ns-acc-quick-links">
        <h3 class="ns-acc-subtitle">دسترسی سریع</h3>
        <div class="ns-acc-quick-grid">
            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="ns-acc-quick-card">
                <span class="ns-acc-quick-card__icon"><?php echo ns_acc_get_svg_icon( 'orders' ); ?></span>
                <div class="ns-acc-quick-card__info">
                    <strong>پیگیری سفارش‌ها</strong>
                    <span>مشاهده فاکتور و وضعیت مرسوله</span>
                </div>
            </a>

            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>" class="ns-acc-quick-card">
                <span class="ns-acc-quick-card__icon"><?php echo ns_acc_get_svg_icon( 'edit-address' ); ?></span>
                <div class="ns-acc-quick-card__info">
                    <strong>مدیریت آدرس‌ها</strong>
                    <span>ثبت یا ویرایش آدرس تحویل</span>
                </div>
            </a>

            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>" class="ns-acc-quick-card">
                <span class="ns-acc-quick-card__icon"><?php echo ns_acc_get_svg_icon( 'edit-account' ); ?></span>
                <div class="ns-acc-quick-card__info">
                    <strong>مشخصات و رمز عبور</strong>
                    <span>ویرایش موبایل و اطلاعات فردی</span>
                </div>
            </a>
        </div>
    </div>

    <!-- سفارش‌های اخیر کاربر -->
    <div class="ns-acc-recent-orders">
        <div class="ns-acc-section-header">
            <h3 class="ns-acc-subtitle">سفارش‌های اخیر شما</h3>
            <?php if ( ! empty( $recent_orders ) ) : ?>
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="ns-acc-link-more">
                    مشاهده همه سفارش‌ها &larr;
                </a>
            <?php endif; ?>
        </div>

        <?php if ( ! empty( $recent_orders ) ) : ?>
            <div class="ns-acc-orders-list">
                <?php foreach ( $recent_orders as $order ) : 
                    $status_slug = $order->get_status();
                    $status_name = wc_get_order_status_name( $status_slug );
                    $item_count  = $order->get_item_count();
                ?>
                    <div class="ns-acc-order-row-card">
                        <div class="ns-acc-order-row-card__main">
                            <span class="ns-acc-order-number">سفارش #<?php echo esc_html( $order->get_order_number() ); ?></span>
                            <span class="ns-acc-order-date"><?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'j F Y' ) ); ?></span>
                        </div>
                        <div class="ns-acc-order-row-card__meta">
                            <span class="ns-acc-status-badge ns-acc-status--<?php echo esc_attr( $status_slug ); ?>">
                                <?php echo esc_html( $status_name ); ?>
                            </span>
                            <span class="ns-acc-order-total">
                                <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
                                <small>(<?php echo esc_html( $item_count ); ?> کالا)</small>
                            </span>
                        </div>
                        <div class="ns-acc-order-row-card__action">
                            <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="ns-acc-btn ns-acc-btn--outline">
                                جزئیات سفارش
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <!-- حالت خالی (Empty State) سفارش‌ها -->
            <div class="ns-acc-empty-state">
                <div class="ns-acc-empty-state__icon">
                    <?php echo ns_acc_get_svg_icon( 'jasmine' ); ?>
                </div>
                <h4 class="ns-acc-empty-state__title">هنوز سفارشی در نسیم استایل ثبت نکرده‌اید</h4>
                <p class="ns-acc-empty-state__text">جدیدترین کلکسیون پوشاک زنانه ما را ببینید و اولین تجربه خرید خود را رقم بزنید.</p>
                <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="ns-acc-btn ns-acc-btn--primary">
                    مشاهده فروشگاه نسیم استایل
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php
    /**
     * حفظ هوک‌های استاندارد ووکامرس برای سازگاری با سایر افزونه‌ها
     */
    do_action( 'woocommerce_account_dashboard' );
    ?>
</div>