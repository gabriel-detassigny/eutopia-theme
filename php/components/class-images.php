<?php
/**
 * Eutopia_Theme\Components\Images class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use ReflectionClass;
use Eutopia_Theme\Helpers\Sizes;

/**
 * Class for managing images.
 *
 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 */
class Images extends Component {


	/**
	 * Aspect ratios.
	 *
	 * Caution: Every aspect ratio will generate a set of image files, one for every image width.
	 */
	const RATIO_1X1  = '1:1';
	const RATIO_3X2  = '3:2';
	const RATIO_4X3  = '4:3';
	const RATIO_3X4  = '3:4';
	const RATIO_16X9 = '16:9';
	const RATIO_21X9 = '21:9';

	/**
	 * Image widths that are used to define custom image sizes.
	 *
	 * Caution: Every image width will generate a set of image files, one for every aspect ratio.
	 *
	 * @var int[]
	 */
	const IMAGE_WIDTHS = [
		384, // = 0.5 x `medium_large`.
		768, // Core's `medium_large`.
		1024, // Core's `large`.
		1536, // = 2 x `medium_large` (i.e. 1536x1536).
		2048, // = 2 x `large` (i.e. 2048x2048).
	];

	/**
	 * WP image sizes to remove.
	 *
	 * @var string[]
	 */
	const SIZES_TO_REMOVE = [
		'thumbnail_size_h',
		'thumbnail_size_w',
		'medium_size_h',
		'medium_size_w',
		'medium_large_size_h',
		'medium_large_size_w',
		'large_size_h',
		'large_size_w',
		'1536x1536_size_h',
		'1536x1536_size_w',
		'2048x2048_size_h',
		'2048x2048_size_w',
	];

	/**
	 * Inline stylesheet handle.
	 *
	 * This fake stylesheet is used to add inline styles in a clean way.
	 * Note that this has to be done in 2 steps: register first and then
	 * enqueue. If we tried enqueuing a stylesheet that has no `src`, Core
	 * will bail out early and not print the inline styles.
	 *
	 * @var string
	 */
	const INLINE_STYLESHEET_HANDLE = 'eutopia-theme-image-sizes';

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'add_post_thumbnail_support' ] );
		add_action( 'after_setup_theme', [ $this, 'modify_image_sizes' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'inline_custom_image_sizes_css' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'inline_custom_image_sizes_css' ] );
		add_filter( 'wp_content_img_tag', [ $this, 'filter_content_image_tag' ], 10, 3 );
		add_filter( 'wp_get_attachment_image', [ $this, 'alter_attachment_image_attributes' ], 10, 3 );
		add_filter( 'has_post_thumbnail', [ $this, 'filter_has_post_thumbnail' ], 10, 2 );
	}

	/**
	 * Adds support for post thumbnails.
	 *
	 * @return void
	 */
	public function add_post_thumbnail_support() {
		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Adds custom and removes default image sizes.
	 *
	 * @return void
	 */
	public function modify_image_sizes() {
		// Remove default image sizes.
		foreach ( self::SIZES_TO_REMOVE as $size ) {
			add_filter( "option_$size", '__return_zero' );
		}

		// Add custom image sizes.
		foreach ( self::get_aspect_ratios() as $aspect_ratio ) {
			foreach ( self::IMAGE_WIDTHS as $width ) {
				$ratio_width_height = array_map(
					'absint',
					explode( ':', $aspect_ratio ),
				);

				add_image_size(
					$this->get_image_size_name( $aspect_ratio, $width ),
					$width,
					absint( $width * $ratio_width_height[1] / $ratio_width_height[0] ),
					true
				);
			}
		}
	}

	/**
	 * Add inline CSS for enforcing correct sizing of images.
	 *
	 * @return void
	 */
	public function inline_custom_image_sizes_css() {
		$aspect_ratio_css = [];
		$size_selectors   = [];

		foreach ( self::get_aspect_ratios() as $aspect_ratio ) {
			$ratio_width_height = array_map(
				'absint',
				explode( ':', $aspect_ratio ),
			);

			$size_selector = sprintf(
				'.size-%d\:%d',
				$ratio_width_height[0],
				$ratio_width_height[1],
			);

			$size_selectors[]   = $size_selector;
			$aspect_ratio_css[] = sprintf(
				'%s{aspect-ratio: %d/%d;}',
				$size_selector,
				$ratio_width_height[0],
				$ratio_width_height[1],
			);
		}

		$css = sprintf(
			'%s{width: 100%%;object-fit: cover;} %s',
			implode( ',', $size_selectors ),
			implode( '', $aspect_ratio_css ),
		);

		wp_register_style( self::INLINE_STYLESHEET_HANDLE, false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_style( self::INLINE_STYLESHEET_HANDLE );
		wp_add_inline_style( self::INLINE_STYLESHEET_HANDLE, $css );
	}

	/**
	 * Update image tag in post content with the correct size-based attributes.
	 *
	 * @param string $filtered_image Full img tag with attributes that will replace the source img tag.
	 * @param string $context        Additional context, like the current filter name or the function name from where
	 *                               this was called.
	 * @param int    $attachment_id  The image attachment ID. May be 0 in case the image is not an attachment.
	 *
	 * @return string
	 */
	public function filter_content_image_tag( $filtered_image, string $context, int $attachment_id ): string {
		if ( ! preg_match( '/aspect-ratio\s*:\s*(\d+)\s*(?>\/\s*(\d+))?/', $filtered_image, $matches ) ) {
			return $filtered_image;
		}

		// Square images may have CSS aspect-ratio set to just 1, so we fall back the height to 1 as well.
		$width  = absint( $matches[1] );
		$height = absint( $matches[2] ?? 1 );

		return $this->alter_attachment_image_attributes(
			$filtered_image,
			$attachment_id,
			sprintf( '%d:%d', $width, $height ),
		);
	}

	/**
	 * Update image tag with the correct size-based attributes.
	 *
	 * @param string       $html           HTML img element or empty string on failure.
	 * @param int          $attachment_id  Image attachment ID.
	 * @param string|int[] $size           Requested image size. Can be any registered image size name, or
	 *                                     an array of width and height values in pixels (in that order).
	 *
	 * @return string
	 */
	public function alter_attachment_image_attributes( string $html, int $attachment_id, $size ): string {
		if ( ! is_string( $size ) ) {
			return $html;
		}

		// Bail out early if unsupported aspect ratio is requested.
		$default_size = $this->get_image_size_name( $size );
		if ( ! $default_size ) {
			return $this->maybe_clear_sizes_attr( $html );
		}

		$tag_processor = new \WP_HTML_Tag_Processor( $html );

		if ( ! $tag_processor->next_tag( 'img' ) ) {
			return $html;
		}

		// Add the size class if it is not already there.
		$classnames = $tag_processor->get_attribute( 'class' );
		if ( ! str_contains( $classnames, sprintf( 'size-%s', $size ) ) ) {
			$tag_processor->set_attribute( 'class', sprintf( '%1$s size-%2$s attachment-%2$s', $classnames, $size ) );
		}

		$attrs = $this->determine_image_attributes_for_aspect_ratio( $size, $attachment_id );
		if ( empty( $attrs ) ) {
			return $this->maybe_clear_sizes_attr( $tag_processor->get_updated_html() );
		}

		// Overwrite base img attributes so that the fallback image and its size are correct.
		if ( ! empty( $attrs['default']['file'] ) ) {
			$tag_processor->set_attribute( 'src', $attrs['default']['file'] );
			$tag_processor->set_attribute( 'width', $attrs['default']['width'] );
			$tag_processor->set_attribute( 'height', $attrs['default']['height'] );
		}

		if ( ! empty( $attrs['srcset'] ) ) {
			$tag_processor->set_attribute( 'srcset', $attrs['srcset'] );
		}

		return $this->maybe_clear_sizes_attr( $tag_processor->get_updated_html() );
	}

	/**
	 * Remove the sizes attribute if there is no srcset attribute on the img tag.
	 *
	 * This is needed in order to be compliant with the HTML specs.
	 *
	 * @param string $html Image markup.
	 *
	 * @return string
	 */
	protected function maybe_clear_sizes_attr( string $html ): string {
		$tag_processor = new \WP_HTML_Tag_Processor( $html );

		if ( ! $tag_processor->next_tag( 'img' ) ) {
			return $html;
		}

		if ( ! $tag_processor->get_attribute( 'srcset' ) && $tag_processor->get_attribute( 'sizes' ) ) {
			$tag_processor->remove_attribute( 'sizes' );
		}

		return $tag_processor->get_updated_html();
	}

	/**
	 * Get image attributes for a specific aspect ratio.
	 *
	 * @param string       $requested_aspect_ratio Requested aspect ratio label such as 1:1 or 3:4.
	 * @param int|\WP_Post $attachment             Attachment ID or WP_Post object for the attachment.
	 *
	 * @return array
	 */
	protected function determine_image_attributes_for_aspect_ratio( string $requested_aspect_ratio, $attachment ): array {
		// Bail out early if unsupported aspect ratio is requested.
		$default_size = $this->get_image_size_name( $requested_aspect_ratio );
		if ( ! $default_size ) {
			return [];
		}

		// If the attachment doesn't have the requested image size, return early.
		$attachment = get_post( $attachment );
		$metadata   = wp_get_attachment_metadata( $attachment->ID );
		if ( empty( $metadata['sizes'][ $default_size ] ) ) {
			return [];
		}

		// Rewrite the image file path so that it is a full (absolute) URL.
		$url                      = wp_get_attachment_url( $attachment->ID );
		$url_basename             = wp_basename( $url );
		$attrs['default']         = $metadata['sizes'][ $default_size ];
		$attrs['default']['file'] = str_replace( $url_basename, $attrs['default']['file'], $url );

		// Collect image sources.
		$sources = [];
		foreach ( $metadata['sizes'] as $size_name => $size_data ) {
			// Skip sizes that don't match the requested aspect ratio.
			if ( ! str_starts_with( $size_name, $requested_aspect_ratio ) ) {
				continue;
			}

			$image_url = str_replace( $url_basename, $size_data['file'], $url );
			$image_url = str_replace( ' ', '%20', $image_url );

			$sources[ $size_data['width'] ] = $image_url;
		}

		if ( empty( $sources ) ) {
			return $attrs;
		}

		// Format a srcset attribute string.
		$srcset = '';
		foreach ( $sources as $width => $image_url ) {
			$srcset .= sprintf( '%s %dw, ', $image_url, $width );
		}

		$attrs['srcset'] = rtrim( $srcset, ', ' );

		return $attrs;
	}

	/**
	 * Get formatted `sizes` HTML attribute based on sizes array.
	 *
	 * @param array $sizes CSS-breakpoint-based sizes array.
	 *
	 * @return string
	 */
	public static function get_sizes_attr( array $sizes = [] ): string {
		$formatted_sizes = [];

		// If there are no sizes, return 100vw as the default.
		if ( empty( $sizes ) ) {
			return '100vw';
		}

		// If there is just one size and it is 100vw, return it as is without any breakpoint.
		if ( 1 === count( $sizes ) && '100vw' === $sizes[ array_key_first( $sizes ) ] ) {
			return '100vw';
		}

		// If there is no default size, assume 100vw.
		if ( ! isset( $sizes[0] ) ) {
			$sizes[0] = '100vw';
		}

		// The breakpoints need to be sorted by width in descending order.
		krsort( $sizes );

		foreach ( $sizes as $breakpoint_px => $size ) {
			$breakpoint_px = absint( $breakpoint_px );

			// If the size has no unit, assume it is pixels.
			if ( (string) absint( $size ) === (string) $size ) {
				$size = sprintf( '%dpx', absint( $size ) );
			}

			if ( 0 === $breakpoint_px ) {
				$formatted_sizes[] = $size;

				continue;
			}

			$formatted_sizes[] = sprintf(
				'(min-width: %dpx) %s',
				$breakpoint_px,
				$size,
			);
		}

		return implode( ', ', $formatted_sizes );
	}

	/**
	 * Get the image size name by label and an optional aspect ratio label such as 1:1, 3:4, etc.
	 *
	 * @param string   $aspect_ratio Aspect ratio label such as 1:1 or 3:4.
	 * @param int|null $width        Image width.
	 *
	 * @return string|null
	 */
	public function get_image_size_name( string $aspect_ratio, ?int $width = null ): ?string {
		// If the aspect ratio is not supported, return null.
		if ( ! in_array( $aspect_ratio, self::get_aspect_ratios(), true ) ) {
			return null;
		}

		// Fall back to the width that is closest to the content width.
		if ( null === $width ) {
			$image_widths = self::IMAGE_WIDTHS;
			rsort( $image_widths );

			foreach ( $image_widths as $image_width ) {
				if ( $image_width <= Sizes::BREAK_TABLET ) {
					$width = $image_width;

					break;
				}
			}
		}

		return sprintf( '%s--%dw', $aspect_ratio, $width );
	}

	/**
	 * Determines whether a post has an image attached.
	 *
	 * @param bool              $has_thumbnail true if the post has a post thumbnail, otherwise false.
	 * @param int|\WP_Post|null $post          Post ID or WP_Post object. Default is global `$post`.
	 *
	 * @return bool Whether the post has an image attached.
	 */
	public function filter_has_post_thumbnail( $has_thumbnail, $post ): bool {
		if ( ! $has_thumbnail ) {
			return false;
		}

		if ( post_password_required( $post ) ) {
			return false;
		}

		// Audio or video attachments can have featured images, so they need to be specifically checked.
		$support_slug = get_post_type( $post );

		if ( 'attachment' === $support_slug ) {
			if ( wp_attachment_is( 'audio' ) ) {
				$support_slug .= ':audio';
			} elseif ( wp_attachment_is( 'video' ) ) {
				$support_slug .= ':video';
			}
		}

		return post_type_supports( $support_slug, 'thumbnail' );
	}

	/**
	 * Get the aspect ratios constants defined in this class.
	 *
	 * @return array
	 */
	protected static function get_aspect_ratios(): array {
		$reflection_class = new ReflectionClass( self::class );

		return array_filter(
			$reflection_class->getConstants() ?? [],
			function ( $constant ) {
				return str_starts_with( $constant, 'RATIO_' );
			},
			ARRAY_FILTER_USE_KEY
		);
	}
}
