<?php
/**
 * Template part for displaying a post's featured image
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Images;
use Eutopia_Theme\Helpers\Sizes;
use Eutopia_Theme\Helpers\Utils;

if ( ! has_post_thumbnail() ) {
	return;
}

// The featured image takes up the full width of the content area on all the breakpoints.
$sizes = Images::get_sizes_attr(
	[
		Sizes::BREAK_MOBILE => '100vw',
	],
);

$classes = [ 'post-thumbnail' ];
if ( Utils::supports_navbar_overlay() ) {
	$classes[] = 'post-thumbnail--overlay';
}

if ( is_singular( get_post_type() ) ) {
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php
		the_post_thumbnail(
			Images::RATIO_21X9,
			[
				'loading' => 'eager',
				'sizes'   => $sizes,
			],
		);
		?>
	</div><!-- .post-thumbnail -->
	<?php
} else {
	?>
	<a class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" href="<?php the_permalink(); ?>">
		<?php
		the_post_thumbnail(
			Images::RATIO_21X9,
			[
				'sizes' => $sizes,
			],
		);
		?>
	</a><!-- .post-thumbnail -->
	<?php
}
