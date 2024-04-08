<?php
/**
 * Template part for an entry image.
 *
 * @package Eutopia_Theme
 *
 * @var array $args The template part args.
 */

use Eutopia_Theme\Components\Images;

$aspect_ratio = $args['aspect_ratio'] ?? Images::RATIO_4X3;

$attributes = wp_parse_args(
	$args['attrs'] ?? [],
	[
		'loading' => 'lazy',
	],
);

$classnames = [ 'c-image', "size-$aspect_ratio" ];
if ( ! empty( $args['media_style'] ) ) {
	$classnames[] = "c-image--{$args['media_style']}";
}
$classnames = implode( ' ', $classnames );

if ( empty( $args['link'] ) ) {
	?>
	<div class="<?php echo esc_attr( $classnames ); ?>">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( $aspect_ratio, $attributes );
		} else {
			echo '<span class="c-image__placeholder"></span>';
		}
		?>
	</div>
	<?php
} else {
	?>
	<a href="<?php echo esc_url( $args['link'] ); ?>" class="<?php echo esc_attr( $classnames ); ?>" rel="bookmark">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( $aspect_ratio, $attributes );
		} else {
			echo '<span class="c-image__placeholder"></span>';
		}
		?>
	</a>
	<?php
}
