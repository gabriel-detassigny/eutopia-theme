<?php
/**
 * Hero Section block template file.
 *
 * @var array $attributes Contains all arguments
 *
 * @package Forbes_Au
 */

use Eutopia_Theme\Components\Images;
use Eutopia_Theme\Helpers\Sizes;

$is_full_width = 'full-width' === $attributes['layout'];

// Define block styles, overlay in particular.
$block_styles = [];

if ( $is_full_width ) {
	if ( ! empty( $attributes['dimRatio'] ) ) {
		$block_styles[] = sprintf( '--hero-section--dim-ratio: %s%%;', $attributes['dimRatio'] );
	}

	if ( ! empty( $attributes['overlayBackgroundGradient'] ) ) {
		$block_styles[] = sprintf( '--hero-section--overlay-background: var(--wp--preset--gradient--%s);', $attributes['overlayBackgroundGradient'] );
	} elseif ( ! empty( $attributes['customOverlayBackgroundGradient'] ) ) {
		$block_styles[] = sprintf( '--hero-section--overlay-background: %s;', $attributes['customOverlayBackgroundGradient'] );
	} elseif ( ! empty( $attributes['overlayBackgroundColor'] ) ) {
		$block_styles[] = sprintf( '--hero-section--overlay-background: var(--wp--preset--color--%s);', $attributes['overlayBackgroundColor'] );
	} elseif ( ! empty( $attributes['customOverlayBackgroundColor'] ) ) {
		$block_styles[] = sprintf( '--hero-section--overlay-background: %s;', $attributes['customOverlayBackgroundColor'] );
	}

	if ( ! empty( $attributes['overlayColor'] ) ) {
		$block_styles[] = sprintf( '--hero-section--overlay-color: var(--wp--preset--color--%s);', $attributes['overlayColor'] );
	} elseif ( ! empty( $attributes['customOverlayColor'] ) ) {
		$block_styles[] = sprintf( '--hero-section--overlay-color: %s;', $attributes['customOverlayColor'] );
	}
}

$wrapper_attributes = get_block_wrapper_attributes(
	[
		'class' => esc_attr(
			sprintf(
				'hero-section hero-section--layout-%s alignfull',
				$attributes['layout'],
			)
		),
		'style' => esc_attr( implode( '', $block_styles ) ),
	]
);

// For some reason, if the `textColor` is set to `background`, relevant
// classnames are not added to the wrapper attributes. This patches the bug.
if ( ! empty( $attributes['textColor'] ) ) {
	$classnames_to_add   = [];
	$classnames_to_check = [
		'has-text-color',
		sprintf( 'has-%s-color', $attributes['textColor'] ),
	];

	foreach ( $classnames_to_check as $classname_to_check ) {
		if ( ! preg_match( sprintf( '/[\s"]%s[\s"]/', $classname_to_check ), $wrapper_attributes ) ) {
			$classnames_to_add[] = $classname_to_check;
		}
	}

	if ( ! empty( $classnames_to_add ) ) {
		$wrapper_attributes = preg_replace(
			'/class=\"([^\"]*)\"/',
			sprintf( 'class="$1 %s"', implode( ' ', $classnames_to_add ) ),
			$wrapper_attributes
		);
	}
}

// Define image focal point styles and get the image.
$image_styles = [];
if ( ! empty( $attributes['focalPoint']['x'] ) && ! empty( $attributes['focalPoint']['y'] ) ) {
	$image_styles[] = 'object-fit: cover;';
	$image_styles[] = sprintf(
		'object-position: %1$d%% %2$d%%;',
		$attributes['focalPoint']['x'] * 100,
		$attributes['focalPoint']['y'] * 100,
	);
}

if ( $is_full_width ) {
	$aspect_ratio = Images::RATIO_16X9;
	$sizes        = '100vw';
} else {
	$aspect_ratio = Images::RATIO_3X4;
	$sizes        = Images::get_sizes_attr(
		[
			Sizes::BREAK_MOBILE  => '100vw',
			Sizes::BREAK_DESKTOP => '50vw',
		]
	);
}

$image_html = wp_get_attachment_image(
	$attributes['imageId'],
	$aspect_ratio,
	false,
	[
		'loading' => 'eager',
		'style'   => implode( '', $image_styles ),
		'sizes'   => $sizes,
	]
);

?>
	<?php
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	printf( '<section %s>', $wrapper_attributes );
	?>

		<?php if ( ! empty( $attributes['linkUrl'] ) ) : ?>
			<a href="<?php echo esc_url( $attributes['linkUrl'] ); ?>" class="hero-section__wrap">
		<?php else : ?>
			<div class="hero-section__wrap">
		<?php endif; ?>

			<figure class="hero-section__image">

				<?php if ( ! empty( $image_html ) ) : ?>
					<?php
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $image_html;
					?>
				<?php endif; ?>

			</figure>
			<div class="hero-section__content">

				<?php if ( ! empty( $attributes['meta'] ) ) : ?>
					<div class="hero-section__meta">
						<?php echo wp_kses_post( $attributes['meta'] ); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $attributes['heading'] ) ) : ?>
					<h2 class="hero-section__heading">
						<?php echo wp_kses_post( $attributes['heading'] ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( ! empty( $attributes['description'] ) ) : ?>
					<div class="hero-section__description">
						<?php echo wp_kses_post( $attributes['description'] ); ?>
					</div>
				<?php endif; ?>

			</div>

			<?php if ( $is_full_width && ! empty( $attributes['dimRatio'] ) ) : ?>
				<div class="hero-section__overlay"></div>
			<?php endif; ?>

		<?php if ( ! empty( $attributes['linkUrl'] ) ) : ?>
			</a>
		<?php else : ?>
			</div>
		<?php endif; ?>

	</section>
<?php
