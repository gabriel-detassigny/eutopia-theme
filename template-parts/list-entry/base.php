<?php
/**
 * Template part for displaying a list entry.
 *
 * @package Eutopia_Theme
 *
 * @var array $args The template part args.
 */

use Eutopia_Theme\Components\Entry_Meta_Labels;
use Eutopia_Theme\Components\Images;
use Eutopia_Theme\Helpers\Sizes;

// Parse the arguments.
$args = wp_parse_args(
	$args,
	[
		'size'                => 'm',
		'flip_layout'         => false,
		'show_category'       => false,
		'category_is_link'    => false,
		'show_media'          => false,
		'media_style'         => 'rounded-corners',
		'show_author'         => false,
		'show_published_date' => false,
	],
);

// Define entry meta to display in the footer. The order matters.
$meta_items = [];
if ( $args['show_author'] ) {
	$meta_items[] = 'author';
}
if ( $args['show_published_date'] ) {
	$meta_items[] = 'published_date';
}

// Get and sanitize the size.
$size = $args['size'] ?? 'm';
if ( ! in_array( $size, [ 'l', 'm', 's', 'xs' ], true ) ) {
	$size = 'm';
}

// Define base classnames.
$classes = [
	'l-list-entry',
	"l-list-entry--size-$size",
	$args['flip_layout'] ? 'l-list-entry--flipped' : '',
];
?>
<article <?php post_class( trim( implode( ' ', $classes ) ) ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">

	<?php if ( $args['show_media'] ) : ?>
		<figure class="l-list-entry__media">
			<?php
			// Determine image sizes attribute.
			if ( 'xs' === $size ) {
				$image_sizes_attr = Images::get_sizes_attr(
					[
						Sizes::BREAK_MOBILE => '48px',
						Sizes::BREAK_TABLET => '64px',
					],
				);
			} elseif ( 's' === $size ) {
				$image_sizes_attr = Images::get_sizes_attr(
					[
						Sizes::BREAK_MOBILE => '64px',
						Sizes::BREAK_TABLET => '88px',
					],
				);
			} else {
				$image_sizes_attr = '88px';
			}

			get_template_part(
				'template-parts/components/entry-image',
				null,
				[
					'aspect_ratio' => Images::RATIO_1X1,
					'media_style'  => $args['media_style'],
					'link'         => get_permalink(),
					'attrs'        => [
						'sizes' => $image_sizes_attr,
					],
				],
			);
			?>
		</figure><!-- .l-list-entry__media -->
	<?php endif; ?>

	<div class="l-list-entry__content">

		<?php
		if ( $args['show_category'] ) {
			$badge_size = ( 'l' === $size ) ? 'm' : 's';

			if ( $args['category_is_link'] ) {
				Entry_Meta_Labels::get_instance()?->the_entry_category_label_link( "c-badge c-badge--size-$badge_size" );
			} else {
				Entry_Meta_Labels::get_instance()?->the_entry_category_label( "c-badge c-badge--size-$badge_size c-badge--secondary" );
			}
		}
		?>

		<h3 class="<?php echo esc_attr( "t-heading t-heading--$size" ); ?>">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h3>

		<?php
		get_template_part(
			'template-parts/components/entry-meta-list',
			null,
			[
				'meta_items'  => $meta_items,
				'size'        => ( 'l' === $size ) ? 'l' : 'm',
				'date_format' => $args['date_format'],
			],
		);
		?>

	</div><!-- .l-list-entry__content -->
</article><!-- .l-list-entry -->
