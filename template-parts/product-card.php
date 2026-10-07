<?php
global $product;
if ( empty($product) || !$product->is_visible() ) {
    return;
}

// محاسبه درصد تخفیف
$discount_percent = 0;
if ($product->is_on_sale()) {
    $regular_price = (float) $product->get_regular_price();
    $sale_price    = (float) $product->get_sale_price();
    if ( $regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price ) {
        $discount_percent = round( (($regular_price - $sale_price) / $regular_price) * 100 );
    }
}
?>

<article class="ns-product-card">
    
    <!-- تصویر محصول -->
    <div class="ns-img-box">
        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="ns-img-link" aria-label="<?php echo esc_attr($product->get_name()); ?>">
            <?php 
            echo $product->get_image('woocommerce_thumbnail', array(
                'class'    => 'ns-img',
                'loading'  => 'lazy',
                'decoding' => 'async'
            )); 
            ?>
        </a>
        
        <?php if ($discount_percent > 0) : ?>
            <div class="ns-discount-badge">
                <strong><?php echo esc_html($discount_percent); ?>٪</strong>
                <span>تخفیف</span>
            </div>
        <?php endif; ?>
    </div>

    <!-- اطلاعات محصول -->
    <div class="ns-prod-meta">
        <h3 class="ns-prod-title">
            <a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a>
        </h3>

        <?php if ($product->is_on_sale()) : ?>
            <div class="ns-price-box">
                <span class="ns-price-regular"><?php echo wp_kses_post( wc_price($product->get_regular_price()) ); ?></span>
                <span class="ns-price-sale">
                    <i class="ns-price-brush"></i>
                    <b><?php echo wp_kses_post( wc_price($product->get_sale_price()) ); ?></b>
                </span>
            </div>
        <?php else : ?>
            <div class="ns-price-box ns-normal-price">
                <span class="ns-price-sale">
                    <i class="ns-price-brush"></i>
                    <b><?php echo wp_kses_post( $product->get_price_html() ); ?></b>
                </span>
            </div>
        <?php endif; ?>

        <!-- دکمه افزودن به سبد خرید -->
        <a href="<?php echo esc_url('?add-to-cart=' . $product->get_id()); ?>" data-quantity="1" class="ns-add-to-cart ajax_add_to_cart" data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-product_sku="<?php echo esc_attr($product->get_sku()); ?>" aria-label="افزودن <?php echo esc_attr($product->get_name()); ?> به سبد خرید">
            <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span>افزودن به سبد</span>
        </a>
    </div>

</article>