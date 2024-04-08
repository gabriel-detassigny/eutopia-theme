<?php
/**
 * Template part for displaying the search box
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\SVG;

?>

<section id="search-box" class="search-box" tabindex="-1" aria-label="<?php esc_attr_e( 'Search Box', 'eutopia-theme' ); ?>" aria-modal="true" role="search" aria-live="assertive">
	<div class="container">
		<div class="search-box__top-grid flex is-content-justification-space-between are-vertically-aligned-center">
			<h1 class="search-box__title"><?php esc_html_e( 'Search', 'eutopia-theme' ); ?></h1>

			<button type="button" class="search-box__close btn--ghost btn--icon" aria-label="<?php esc_attr_e( 'Close search', 'eutopia-theme' ); ?>" aria-controls="search-box" aria-expanded="false" data-dismiss="modal">
				<?php
				SVG::get_instance()->render_svg(
					'assets/svg/icon-close.svg',
					[
						'width'  => 24,
						'height' => 25,
						'class'  => 'search-box__close-icon',
					]
				);
				?>
			</button><!-- .search-box__close -->
		</div><!-- .search-box__top-grid -->

		<?php get_search_form(); ?>
	</div><!-- .container -->
</section>
