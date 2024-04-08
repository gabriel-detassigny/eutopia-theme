<?php
/**
 * Block template.
 *
 * @var array     $attributes Block attributes.
 * @var string    $content    Block default content.
 * @var \WP_Block $block      Block instance.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme;

use Eutopia_Theme\Components\Images;
use Eutopia_Theme\Helpers\Sizes;

// The block wrapper should only be rendered on the frontend. In most cases, it
// contains block-specific class names (e.g. alignment classes).
// In the editor, ACF automatically adds a wrapper element in the Edit function.
// Since we don't want to have two wrappers in the editor, this one is rendered
// conditionally.
$should_have_wrapper = empty( $is_preview );

// Block level classes and styles.
$wrapper_attributes = get_block_wrapper_attributes();

/** @var string $carousel_title Carousel title. */
$carousel_title = get_field( 'title' );

if ( empty( $carousel_title ) ) {
	$carousel_title = __( 'Carousel', 'eutopia-theme' );
}

/** @var string $swiper_options Swiper options. */
$swiper_options = wp_json_encode(
	[
		'loop'          => ! empty( get_field( 'loop_slides' ) ),
		'autoHeight'    => ! empty( get_field( 'auto_height' ) ),
		'slidesPerView' => 'auto', // Thanks to this, we can control the slide width with CSS.
	]
);

if ( $should_have_wrapper ) {
	printf( '<div %s>', wp_kses( $wrapper_attributes, [] ) );
}

// Keep the values below in sync with the block's CSS.
$slide_width_percent = 33;
$slide_min_width_px  = 256;
$sizes               = [
	Sizes::BREAK_MOBILE => sprintf( '%dpx', $slide_min_width_px ),
];

if ( ! empty( $block['align'] ) ) {
	// Carousel breakpoint is when slides are not fixed at 256px anymore
	// and take 33% of the container or the viewport width.
	$carousel_breakpoint           = $slide_min_width_px / $slide_width_percent * 100;
	$sizes[ $carousel_breakpoint ] = sprintf( 'calc(%dvw - 1rem)', absint( $slide_width_percent ) );

	if ( 'wide' === $block['align'] ) {
		$sizes[ Sizes::CONTENT_WIDE ] = sprintf( 'calc(%dpx - 1rem)', absint( Sizes::CONTENT_WIDE * $slide_width_percent / 100 ) );
	}
}
?>

<h3>
	<?php echo esc_html( $carousel_title ); ?>
</h3>

<?php if ( have_rows( 'carousel_images' ) ) : ?>
	<div class="swiper" data-swiper="<?php echo esc_attr( $swiper_options ); ?>">
		<div class="swiper-wrapper">
			<?php
			while ( have_rows( 'carousel_images' ) ) :
				the_row();
				?>
				<div class="swiper-slide">
					<?php
					/** @var int $carousel_image_id Carousel image ID. */
					$carousel_image_id = get_sub_field( 'carousel_image' );
					echo wp_get_attachment_image(
						$carousel_image_id,
						Images::RATIO_3X2,
						false,
						[
							'loading' => 'lazy',
							'sizes'   => Images::get_sizes_attr( $sizes ),
						],
					);
					?>
				</div>
			<?php endwhile; ?>
		</div>

		<div class="swiper-scrollbar"></div>
	</div>
<?php endif; ?>

<?php
if ( $should_have_wrapper ) {
	echo '</div>';
}
?>
