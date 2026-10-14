<?php
/**
 * My Addresses - Nasim Style Custom Template
 * Path: wp-content/themes/nasim-style-child/woocommerce/myaccount/my-address.php
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing'  => 'آدرس صورتحساب و پیش‌فرض',
            'shipping' => 'آدرس تحویل سفارش (مرسوله)',
        ),
        $customer_id
    );
} else {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing' => 'آدرس پیش‌فرض ارسال و صورتحساب',
        ),
        $customer_id
    );
}
?>

<div class="ns-acc-addresses-section">
    <div class="ns-acc-section-header">
        <div>
            <h2 class="ns-acc-section-title">آدرس‌های ثبت‌شده من</h2>
            <p class="ns-acc-section-desc">آدرس‌های زیر به صورت پیش‌فرض در صفحه تسویه‌حساب برای ارسال سفارش‌های شما استفاده می‌شوند.</p>
        </div>
    </div>

    <div class="ns-acc-addresses-grid">
        <?php foreach ( $get_addresses as $name => $address_title ) : ?>
            <?php
                $address   = wc_get_account_formatted_address( $name );
                $edit_link = wc_get_endpoint_url( 'edit-address', $name );
                $state     = get_user_meta( $customer_id, $name . '_state', true );
                $city      = get_user_meta( $customer_id, $name . '_city', true );
                $address_1 = get_user_meta( $customer_id, $name . '_address_1', true );
                $postcode  = get_user_meta( $customer_id, $name . '_postcode', true );
                $phone     = get_user_meta( $customer_id, 'billing_phone', true );
                $recipient = trim( get_user_meta( $customer_id, $name . '_first_name', true ) . ' ' . get_user_meta( $customer_id, $name . '_last_name', true ) );
            ?>

            <div class="ns-acc-address-card">
                <div class="ns-acc-address-card__header">
                    <div class="ns-acc-address-card__title-wrap">
                        <span class="ns-acc-address-card__icon"><?php echo ns_acc_get_svg_icon( 'edit-address' ); ?></span>
                        <h3 class="ns-acc-address-card__title"><?php echo esc_html( $address_title ); ?></h3>
                    </div>
                    <span class="ns-acc-badge-default">پیش‌فرض</span>
                </div>

                <?php if ( $address ) : ?>
                    <div class="ns-acc-address-card__body">
                        <?php if ( ! empty( $recipient ) ) : ?>
                            <div class="ns-acc-address-detail">
                                <span class="ns-acc-address-detail__label">تحویل‌گیرنده:</span>
                                <strong class="ns-acc-address-detail__val"><?php echo esc_html( $recipient ); ?></strong>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $city ) || ! empty( $state ) ) : ?>
                            <div class="ns-acc-address-detail">
                                <span class="ns-acc-address-detail__label">استان و شهر:</span>
                                <span class="ns-acc-address-detail__val"><?php echo esc_html( $state . '، ' . $city ); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $address_1 ) ) : ?>
                            <div class="ns-acc-address-detail">
                                <span class="ns-acc-address-detail__label">نشانی پستی:</span>
                                <span class="ns-acc-address-detail__val"><?php echo esc_html( $address_1 ); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $postcode ) ) : ?>
                            <div class="ns-acc-address-detail">
                                <span class="ns-acc-address-detail__label">کد پستی:</span>
                                <span class="ns-acc-address-detail__val" dir="ltr"><?php echo esc_html( $postcode ); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $phone ) ) : ?>
                            <div class="ns-acc-address-detail">
                                <span class="ns-acc-address-detail__label">شماره تماس:</span>
                                <span class="ns-acc-address-detail__val" dir="ltr"><?php echo esc_html( $phone ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="ns-acc-address-card__footer">
                        <a href="<?php echo esc_url( $edit_link ); ?>" class="ns-acc-btn ns-acc-btn--outline">
                            <?php echo ns_acc_get_svg_icon( 'edit' ); ?>
                            <span>ویرایش آدرس</span>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="ns-acc-address-card__empty">
                        <p>هنوز آدرسی برای این بخش ثبت نکرده‌اید.</p>
                        <a href="<?php echo esc_url( $edit_link ); ?>" class="ns-acc-btn ns-acc-btn--primary">
                            + افزودن آدرس جدید
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        <?php endforeach; ?>
    </div>
</div>