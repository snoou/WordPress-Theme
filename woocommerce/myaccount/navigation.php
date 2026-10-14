<?php
/**
 * My Account navigation - Nasim Style Custom Template
 * Path: wp-content/themes/nasim-style-child/woocommerce/myaccount/navigation.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="ns-acc-nav" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
    <ul class="ns-acc-nav__list">
        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
            <?php
            $classes = wc_get_account_menu_item_classes( $endpoint );
            $is_active = strpos( $classes, 'is-active' ) !== false;
            $item_class = 'ns-acc-nav__item ' . ( $is_active ? 'ns-acc-nav__item--active' : '' );
            if ( 'customer-logout' === $endpoint ) {
                $item_class .= ' ns-acc-nav__item--logout';
            }
            ?>
            <li class="<?php echo esc_attr( $item_class . ' ' . $classes ); ?>">
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="ns-acc-nav__link">
                    <span class="ns-acc-nav__icon">
                        <?php echo ns_acc_get_svg_icon( $endpoint ); ?>
                    </span>
                    <span class="ns-acc-nav__label"><?php echo esc_html( $label ); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>