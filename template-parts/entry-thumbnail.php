<?php
/**
 * Template part for displaying a post's featured image
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Images;
use Eutopia_Theme\Helpers\Sizes;

if ( ! has_post_thumbnail() ) {
	return;
}

// The featured image takes up the full width of the content area on all the breakpoints.
$sizes = Images::get_sizes_attr(
	[
		Sizes::BREAK_MOBILE => '100vw',
	],
);

if ( is_singular( get_post_type() ) ) {
	?>
	<div class="post-thumbnail">
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
	<a class="post-thumbnail" href="<?php the_permalink(); ?>">
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
