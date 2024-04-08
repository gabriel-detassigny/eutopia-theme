<?php
/**
 * Template part for displaying a grid entry.
 *
 * @package Eutopia_Theme
 *
 * @var array $args The template part args.
 */

use Eutopia_Theme\Components\Entry_Meta_Labels;
use Eutopia_Theme\Components\Images;

// Parse the arguments.
$args = wp_parse_args(
	$args,
	[
		'size'                => 'm',
		'show_category'       => false,
		'category_is_link'    => false,
		'show_excerpt'        => false,
		'show_media'          => false,
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
	'l-grid-entry',
	"l-grid-entry--size-$size",
];
?>
<article <?php post_class( trim( implode( ' ', $classes ) ) ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">

	<?php if ( $args['show_media'] ) : ?>
		<figure class="l-grid-entry__media">
			<?php
			get_template_part(
				'template-parts/components/entry-image',
				null,
				[
					'aspect_ratio' => Images::RATIO_3X2,
					'link'         => get_permalink(),
					'attrs'        => [
						'sizes' => $args['image_sizes_attr'] ?? '',
					],
				],
			);
			?>
		</figure><!-- .l-grid-entry__media -->
	<?php endif; ?>

	<div class="l-grid-entry__content">

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
		if ( $args['show_excerpt'] ) {
			$excerpt_size = 's';
			if ( 'l' === $size ) {
				$excerpt_size = 'm';
			} elseif ( 'xs' === $size ) {
				$excerpt_size = 'xs';
			}
			?>
			<div class="<?php echo esc_attr( "t-body t-body--$excerpt_size t-clamp t-clamp--3-lines" ); ?>">
				<?php the_excerpt(); ?>
			</div>
			<?php
		}
		?>

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

	</div><!-- .l-grid-entry__content -->
</article><!-- .l-grid-entry -->
