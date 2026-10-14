<?php
/**
 * Orders - Nasim Style Custom Template
 * Path: wp-content/themes/nasim-style-child/woocommerce/myaccount/orders.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<div class="ns-acc-orders-section">
    <div class="ns-acc-section-header">
        <div>
            <h2 class="ns-acc-section-title">تاریخچه سفارش‌های من</h2>
            <p class="ns-acc-section-desc">فهرست کامل سفارش‌های ثبت‌شده و وضعیت ارسال هر مرسوله</p>
        </div>
    </div>

    <?php if ( $has_orders ) : ?>

        <div class="ns-acc-table-responsive">
            <table class="ns-acc-orders-table shop_table shop_table_responsive my_account_orders">
                <thead>
                    <tr>
                        <th class="ns-acc-th-order">شماره سفارش</th>
                        <th class="ns-acc-th-date">تاریخ ثبت</th>
                        <th class="ns-acc-th-status">وضعیت سفارش</th>
                        <th class="ns-acc-th-total">مبلغ کل</th>
                        <th class="ns-acc-th-actions">عملیات</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ( $customer_orders->orders as $customer_order ) {
                        $order      = wc_get_order( $customer_order );
                        $item_count = $order->get_item_count() - $order->get_item_count_refunded();
                        $status     = $order->get_status();
                        ?>
                        <tr class="ns-acc-order-tr order">
                            <td class="ns-acc-td-order" data-title="شماره سفارش">
                                <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="ns-acc-order-id-link">
                                    #<?php echo esc_html( $order->get_order_number() ); ?>
                                </a>
                            </td>

                            <td class="ns-acc-td-date" data-title="تاریخ ثبت">
                                <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
                                    <?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'j F Y' ) ); ?>
                                </time>
                            </td>

                            <td class="ns-acc-td-status" data-title="وضعیت سفارش">
                                <span class="ns-acc-status-badge ns-acc-status--<?php echo esc_attr( $status ); ?>">
                                    <?php echo esc_html( wc_get_order_status_name( $status ) ); ?>
                                </span>
                            </td>

                            <td class="ns-acc-td-total" data-title="مبلغ کل">
                                <span class="ns-acc-price-wrap">
                                    <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
                                    <small class="ns-acc-item-count">(<?php echo esc_html( $item_count ); ?> مرسوله)</small>
                                </span>
                            </td>

                            <td class="ns-acc-td-actions" data-title="جزئیات">
                                <div class="ns-acc-actions-group">
                                    <?php
                                    $actions = wc_get_account_orders_actions( $order );
                                    if ( ! empty( $actions ) ) {
                                        foreach ( $actions as $key => $action ) {
                                            $btn_class = ( 'view' === $key ) ? 'ns-acc-btn ns-acc-btn--outline' : 'ns-acc-btn ns-acc-btn--primary';
                                            $label     = ( 'view' === $key ) ? 'مشاهده جزئیات سفارش' : $action['name'];
                                            echo '<a href="' . esc_url( $action['url'] ) . '" class="' . esc_attr( $btn_class . ' ' . sanitize_html_class( $key ) ) . '">' . esc_html( $label ) . '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

        <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
            <div class="ns-acc-pagination woocommerce-pagination">
                <?php if ( 1 !== $current_page ) : ?>
                    <a class="ns-acc-btn ns-acc-btn--ghost" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>">&rarr; صفحه قبل</a>
                <?php endif; ?>

                <?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
                    <a class="ns-acc-btn ns-acc-btn--ghost" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>">صفحه بعد &larr;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <div class="ns-acc-empty-state">
            <div class="ns-acc-empty-state__icon">
                <?php echo ns_acc_get_svg_icon( 'jasmine' ); ?>
            </div>
            <h4 class="ns-acc-empty-state__title">لیست سفارش‌های شما خالی است</h4>
            <p class="ns-acc-empty-state__text">شما تاکنون هیچ سفارشی ثبت نکرده‌اید. به فروشگاه سر بزنید و استایل جدیدتان را انتخاب کنید.</p>
            <a class="ns-acc-btn ns-acc-btn--primary" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                شروع خرید از نسیم استایل
            </a>
        </div>
    <?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>