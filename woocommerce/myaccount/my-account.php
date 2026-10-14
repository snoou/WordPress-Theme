<?php
/**
 * My Account Wrapper - Mobile App Experience
 * Path: wp-content/themes/nasim-style-child/woocommerce/myaccount/my-account.php
 */

defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;
$full_name    = trim( $current_user->first_name . ' ' . $current_user->last_name );
$display_name = ! empty( $full_name ) ? $full_name : $current_user->display_name;
$user_phone   = ns_acc_get_user_phone( $user_id );
$registered   = ! empty( $current_user->user_registered ) ? date_i18n( 'j F Y', strtotime( $current_user->user_registered ) ) : '';
$edit_url     = wc_get_account_endpoint_url( 'edit-account' );
?>

<div class="ns-acc-wrapper" dir="rtl">
    <!-- سربرگ هوشمند پروفایل -->
    <header class="ns-acc-header">
        <div class="ns-acc-header__jasmine-bg" aria-hidden="true">
            <?php echo ns_acc_get_svg_icon( 'jasmine' ); ?>
        </div>

        <div class="ns-acc-header__top-bar">
            <span class="ns-acc-header__badge">نسیم استایل • حساب کاربری من</span>
            <a href="<?php echo esc_url( $edit_url ); ?>" class="ns-acc-btn ns-acc-btn--ghost ns-acc-header__edit-btn">
                <?php echo ns_acc_get_svg_icon( 'edit' ); ?>
                <span>ویرایش پروفایل</span>
            </a>
        </div>

        <div class="ns-acc-header__profile">
            <div class="ns-acc-header__avatar-wrap">
                <?php echo get_avatar( $user_id, 96, '', esc_attr( $display_name ), array( 'class' => 'ns-acc-header__avatar' ) ); ?>
                <span class="ns-acc-header__status-dot" title="کاربر فعال"></span>
            </div>

            <div class="ns-acc-header__info">
                <div class="ns-acc-header__title-row">
                    <h1 class="ns-acc-header__name"><?php echo esc_html( $display_name ); ?></h1>
                    <a href="<?php echo esc_url( $edit_url ); ?>" class="ns-acc-mobile-edit-icon" aria-label="ویرایش اطلاعات">
                        <?php echo ns_acc_get_svg_icon( 'edit' ); ?>
                    </a>
                </div>
                <p class="ns-acc-header__welcome-sub">به دنیای لطافت و زیبایی نسیم استایل خوش آمدید</p>

                <div class="ns-acc-header__meta">
                    <div class="ns-acc-meta-pill">
                        <?php echo ns_acc_get_svg_icon( 'phone' ); ?>
                        <span>موبایل: <strong dir="ltr"><?php echo esc_html( $user_phone ?: 'ثبت نشده' ); ?></strong></span>
                    </div>

                    <?php if ( ! empty( $registered ) ) : ?>
                        <div class="ns-acc-meta-pill">
                            <?php echo ns_acc_get_svg_icon( 'calendar' ); ?>
                            <span>عضویت: <strong><?php echo esc_html( $registered ); ?></strong></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- ساختار اصلی صفحه -->
    <div class="ns-acc-layout">
        <aside class="ns-acc-sidebar">
            <?php do_action( 'woocommerce_account_navigation' ); ?>
        </aside>

        <main class="ns-acc-main-content">
            <div class="ns-acc-content-card">
                <?php do_action( 'woocommerce_account_content' ); ?>
            </div>
        </main>
    </div>
</div>