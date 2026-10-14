<?php
/**
 * Edit account form - Phone-Only & Mobile App UI
 * Path: wp-content/themes/nasim-style-child/woocommerce/myaccount/form-edit-account.php
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );

$user_phone = ns_acc_get_user_phone( $user->ID );
?>

<div class="ns-acc-edit-account">
    <div class="ns-acc-section-header">
        <div>
            <h2 class="ns-acc-section-title">اطلاعات حساب کاربری</h2>
            <p class="ns-acc-section-desc">مشخصات فردی، شماره موبایل و رمز عبور خود را مدیریت کنید</p>
        </div>
    </div>

    <form class="ns-acc-form woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

        <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

        <div class="ns-acc-form__grid">
            <div class="ns-acc-form__group">
                <label for="account_first_name">نام <span class="ns-acc-required">*</span></label>
                <input type="text" class="ns-acc-input woocommerce-Input input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" placeholder="نام خود را وارد کنید" value="<?php echo esc_attr( $user->first_name ); ?>" required />
            </div>

            <div class="ns-acc-form__group">
                <label for="account_last_name">نام خانوادگی <span class="ns-acc-required">*</span></label>
                <input type="text" class="ns-acc-input woocommerce-Input input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" placeholder="نام خانوادگی خود را وارد کنید" value="<?php echo esc_attr( $user->last_name ); ?>" required />
            </div>

            <div class="ns-acc-form__group ns-acc-form__group--full">
                <label for="account_billing_phone">شماره تلفن همراه <span class="ns-acc-required">*</span></label>
                <div class="ns-acc-input-icon-wrap">
                    <input
                        type="tel"
                        inputmode="numeric"
                        dir="ltr"
                        maxlength="11"
                        class="ns-acc-input ns-acc-input--ltr ns-acc-input--phone"
                        name="account_billing_phone"
                        id="account_billing_phone"
                        placeholder="09123456789"
                        value="<?php echo esc_attr( $user_phone ); ?>"
                        required
                    />
                    <span class="ns-acc-input-icon"><?php echo ns_acc_get_svg_icon( 'phone' ); ?></span>
                </div>
                <span class="ns-acc-field-hint">کد تایید و پیامک‌های وضعیت ارسال سفارش به این شماره ارسال می‌شود.</span>
            </div>
        </div>

        <!-- فیلدهای مخفی برای سازگاری کامل با هسته ووکامرس بدون نمایش ایمیل -->
        <input type="hidden" name="account_display_name" value="<?php echo esc_attr( trim( $user->first_name . ' ' . $user->last_name ) ?: $user->display_name ); ?>" />
        <input type="hidden" name="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />

        <!-- بخش تغییر رمز عبور -->
        <fieldset class="ns-acc-fieldset">
            <legend class="ns-acc-fieldset__legend">تغییر رمز عبور (اختیاری)</legend>

            <div class="ns-acc-form__grid">
                <div class="ns-acc-form__group ns-acc-form__group--full">
                    <label for="password_current">رمز عبور فعلی</label>
                    <input type="password" class="ns-acc-input woocommerce-Input input-text" name="password_current" id="password_current" autocomplete="off" placeholder="فقط در صورت تمایل به تغییر رمز پر کنید" />
                </div>

                <div class="ns-acc-form__group">
                    <label for="password_1">رمز عبور جدید</label>
                    <input type="password" class="ns-acc-input woocommerce-Input input-text" name="password_1" id="password_1" autocomplete="off" placeholder="رمز عبور جدید" />
                </div>

                <div class="ns-acc-form__group">
                    <label for="password_2">تکرار رمز عبور جدید</label>
                    <input type="password" class="ns-acc-input woocommerce-Input input-text" name="password_2" id="password_2" autocomplete="off" placeholder="تکرار رمز عبور جدید" />
                </div>
            </div>
        </fieldset>

        <?php do_action( 'woocommerce_edit_account_form' ); ?>

        <div class="ns-acc-form__actions">
            <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
            <button type="submit" class="ns-acc-btn ns-acc-btn--primary ns-acc-btn--block-mobile woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>">
                ذخیره اطلاعات کاربری
            </button>
            <input type="hidden" name="action" value="save_account_details" />
        </div>

        <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
    </form>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>