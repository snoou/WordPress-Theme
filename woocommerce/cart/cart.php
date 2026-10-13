<?php
/**
 * Nasim Modern WooCommerce Cart
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>

<div class="nasim-cart-wrapper">

    <!-- =========================
         CART PRODUCTS
    ========================== -->
    <div class="nasim-cart-main">

        <form
            class="woocommerce-cart-form"
            action="<?php echo esc_url( wc_get_cart_url() ); ?>"
            method="post"
        >

            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="n-cart-items">

                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :

                    $_product = apply_filters(
                        'woocommerce_cart_item_product',
                        $cart_item['data'],
                        $cart_item,
                        $cart_item_key
                    );

                    $product_id = apply_filters(
                        'woocommerce_cart_item_product_id',
                        $cart_item['product_id'],
                        $cart_item,
                        $cart_item_key
                    );

                    if (
                        $_product &&
                        $_product->exists() &&
                        $cart_item['quantity'] > 0 &&
                        apply_filters(
                            'woocommerce_cart_item_visible',
                            true,
                            $cart_item,
                            $cart_item_key
                        )
                    ) :

                        $product_permalink = apply_filters(
                            'woocommerce_cart_item_permalink',
                            $_product->is_visible()
                                ? $_product->get_permalink( $cart_item )
                                : '',
                            $cart_item,
                            $cart_item_key
                        );
                ?>

                    <div
                        class="n-cart-item <?php echo esc_attr(
                            apply_filters(
                                'woocommerce_cart_item_class',
                                'cart_item',
                                $cart_item,
                                $cart_item_key
                            )
                        ); ?>"
                        data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>"
                    >

                        <!-- PRODUCT IMAGE -->
                        <div class="n-item-img">

                            <?php
                            $thumbnail = apply_filters(
                                'woocommerce_cart_item_thumbnail',
                                $_product->get_image( 'woocommerce_thumbnail' ),
                                $cart_item,
                                $cart_item_key
                            );

                            if ( ! $product_permalink ) {

                                echo $thumbnail;

                            } else {

                                printf(
                                    '<a href="%s">%s</a>',
                                    esc_url( $product_permalink ),
                                    $thumbnail
                                );
                            }
                            ?>

                        </div>


                        <!-- PRODUCT INFO -->
                        <div class="n-item-info">

                            <!-- HEADER -->
                            <div class="n-item-header">

                                <div class="n-item-title">

                                    <?php

                                    $product_name = apply_filters(
                                        'woocommerce_cart_item_name',
                                        $_product->get_name(),
                                        $cart_item,
                                        $cart_item_key
                                    );

                                    if ( ! $product_permalink ) {

                                        echo wp_kses_post( $product_name );

                                    } else {

                                        echo wp_kses_post(
                                            sprintf(
                                                '<a href="%s">%s</a>',
                                                esc_url( $product_permalink ),
                                                $_product->get_name()
                                            )
                                        );
                                    }

                                    ?>

                                    <?php
                                    do_action(
                                        'woocommerce_after_cart_item_name',
                                        $cart_item,
                                        $cart_item_key
                                    );
                                    ?>

                                    <?php
                                    echo wc_get_formatted_cart_item_data( $cart_item );
                                    ?>

                                </div>


                                <!-- REMOVE -->
                                <div class="n-item-remove">

                                    <?php

                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s"
                                                class="n-remove-product"
                                                aria-label="%s"
                                                data-product_id="%s"
                                                data-product_sku="%s">

                                                <svg
                                                    width="20"
                                                    height="20"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <path d="M3 6h18"></path>
                                                    <path d="M8 6V4h8v2"></path>
                                                    <path d="M19 6l-1 14H6L5 6"></path>
                                                    <path d="M10 11v5"></path>
                                                    <path d="M14 11v5"></path>
                                                </svg>

                                            </a>',
                                            esc_url(
                                                wc_get_cart_remove_url(
                                                    $cart_item_key
                                                )
                                            ),
                                            esc_attr__(
                                                'Remove this item',
                                                'woocommerce'
                                            ),
                                            esc_attr( $product_id ),
                                            esc_attr( $_product->get_sku() )
                                        ),
                                        $cart_item_key
                                    );

                                    ?>

                                </div>

                            </div>


                            <!-- PRICE -->
                            <div class="n-item-price">

                                <?php
                                echo apply_filters(
                                    'woocommerce_cart_item_price',
                                    WC()->cart->get_product_price( $_product ),
                                    $cart_item,
                                    $cart_item_key
                                );
                                ?>

                            </div>


                            <!-- FOOTER -->
                            <div class="n-item-footer">

                                <!-- QUANTITY -->
                                <div class="ns-qty-box">

                                    <?php if ( ! $_product->is_sold_individually() ) : ?>

                                        <button
                                            type="button"
                                            class="ns-qty-btn ns-minus"
                                            aria-label="کاهش تعداد"
                                        >
                                            −
                                        </button>


                                        <?php

                                        $product_quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                'min_value'    => 1,
                                                'step'         => 1,
                                                'product_name' => $_product->get_name(),
                                                'classes'      => array(
                                                    'input-text',
                                                    'qty',
                                                    'text'
                                                ),
                                            ),
                                            $_product,
                                            false
                                        );

                                        echo apply_filters(
                                            'woocommerce_cart_item_quantity',
                                            $product_quantity,
                                            $cart_item_key,
                                            $cart_item
                                        );

                                        ?>


                                        <button
                                            type="button"
                                            class="ns-qty-btn ns-plus"
                                            aria-label="افزایش تعداد"
                                        >
                                            +
                                        </button>

                                    <?php else : ?>

                                        <div class="ns-single-quantity">
                                            1
                                        </div>

                                        <input
                                            type="hidden"
                                            name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]"
                                            value="1"
                                        >

                                    <?php endif; ?>

                                </div>


                                <!-- TOTAL -->
                                <div class="n-item-total">

                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_subtotal',
                                        WC()->cart->get_product_subtotal(
                                            $_product,
                                            $cart_item['quantity']
                                        ),
                                        $cart_item,
                                        $cart_item_key
                                    );
                                    ?>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endif; endforeach; ?>

            </div>


            <!-- =========================
                 CART ACTIONS
            ========================== -->
            <div class="n-cart-actions">

                <?php if ( wc_coupons_enabled() ) : ?>

                    <div class="n-coupon-box">

                        <div class="n-coupon-input">

                            <svg
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7"></path>
                                <path d="M2 7h20"></path>
                                <path d="M12 7V3"></path>
                                <path d="M8 3h8"></path>
                            </svg>

                            <input
                                type="text"
                                name="coupon_code"
                                class="input-text"
                                id="coupon_code"
                                value=""
                                placeholder="کد تخفیف دارید؟"
                            >

                        </div>

                        <button
                            type="submit"
                            class="n-coupon-button"
                            name="apply_coupon"
                            value="ثبت"
                        >
                            ثبت
                        </button>

                    </div>

                <?php endif; ?>


                <!-- HIDDEN UPDATE -->
                <button
                    type="submit"
                    class="n-hidden-update"
                    name="update_cart"
                    value="آپدیت"
                >
                    آپدیت سبد
                </button>


                <?php do_action( 'woocommerce_cart_actions' ); ?>

                <?php
                wp_nonce_field(
                    'woocommerce-cart',
                    'woocommerce-cart-nonce'
                );
                ?>

            </div>


            <?php do_action( 'woocommerce_after_cart_table' ); ?>

        </form>

    </div>


    <!-- =========================
         CART SIDEBAR
    ========================== -->
    <aside class="nasim-cart-sidebar cart-collaterals">

        <?php do_action( 'woocommerce_cart_collaterals' ); ?>

    </aside>

</div>


<?php do_action( 'woocommerce_after_cart' ); ?>

