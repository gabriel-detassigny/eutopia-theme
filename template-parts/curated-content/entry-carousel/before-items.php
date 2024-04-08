<?php
/**
 * Template part for displaying content before CCB entry items.
 *
 * @package Eutopia_Theme
 *
 * @var array $args The template part args.
 */

use Eutopia_Theme\Components\SVG;

$column_count = absint( $args['carousel_column_count'] ?? 3 );
if ( ! in_array( $column_count, [ 2, 3, 4 ], true ) ) {
	$column_count = 3;
}

$show_pagination = ! empty( $args['show_pagination'] ) ? $args['show_pagination'] : 0;

$heading = ! empty( $args['heading'] ) ? $args['heading'] : __( 'Title', 'eutopia-theme' );
?>

<div class="l-entry-carousel" data-columns="<?php echo esc_attr( $column_count ); ?>" data-pagination="<?php echo esc_attr( $show_pagination ); ?>">
	<div class="l-entry-carousel__nav">
		<h3 class="l-entry-carousel__heading"><?php echo esc_html( $heading ); ?></h3>

		<div class="l-entry-carousel__nav-buttons">
			<button type="button" class="l-entry-carousel__nav--prev btn btn--tertiary btn--icon btn--xs" disabled>
				<?php
				SVG::get_instance()->render_svg(
					'assets/svg/icon-caret-left.svg',
					[
						'width'  => 16,
						'height' => 16,
					]
				);
				?>
				<span class="screen-reader-text">
					<?php esc_html_e( 'Previous', 'eutopia-theme' ); ?>
				</span>
			</button>

			<button type="button" class="l-entry-carousel__nav--next btn btn--tertiary btn--icon btn--xs" disabled>
				<?php
				SVG::get_instance()->render_svg(
					'assets/svg/icon-caret-right.svg',
					[
						'width'  => 16,
						'height' => 16,
					]
				);
				?>
				<span class="screen-reader-text">
					<?php esc_html_e( 'Next', 'eutopia-theme' ); ?>
				</span>
			</button>
		</div>
	</div>
	<div class="l-entry-carousel__wrapper">
