<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'برای ثبت سفارش باید وارد حساب کاربری خود شوید.', 'woocommerce' ) ) );
    return;
}
?>

<div class="nasim-checkout-page" dir="rtl">
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

        <div class="checkout-grid">
            <!-- ستون سمت راست: اطلاعات کاربر -->
            <div class="checkout-details-column">
                <?php if ( $checkout->get_checkout_fields() ) : ?>
                    <div class="clean-card">
                        <div class="card-header">
                            <h3>اطلاعات گیرنده سفارش</h3>
                        </div>
                        <div class="card-body">
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ستون سمت چپ: فاکتور و پرداخت -->
            <div class="checkout-review-column">
                <div class="clean-card sticky-card">
                    <div class="card-header">
                        <h3>فاکتور نهایی</h3>
                    </div>
                    <div class="card-body" id="order_review">
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>