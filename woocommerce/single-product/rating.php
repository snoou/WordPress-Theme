<?php
/**
 * Single Product Rating
 *
 * Custom Persian version
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();

if ( $rating_count > 0 ) : ?>

	<div class="woocommerce-product-rating">

		<?php echo wc_get_rating_html( $average, $rating_count ); ?>

		<?php if ( comments_open() ) : ?>

			<a href="#reviews" class="woocommerce-review-link" rel="nofollow">

				<?php if ( $review_count == 1 ) : ?>

					(۱ دی	دگاه مشتری)

				<?php else : ?>

					(<?php echo esc_html( number_format_i18n( $review_count ) ); ?> دیدگاه مشتری)

				<?php endif; ?>

			</a>

		<?php endif; ?>

	</div>

<?php endif; ?>