<?php
/**
 * The template for displaying search box
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\SVG;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$search_query    = get_search_query();
$button_disabled = false;

if ( empty( $search_query ) ) {
	$button_disabled = true;
}

?>

<form role="search" method="get" class="search-form flex is-content-justification-space-between" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Search Form', 'eutopia-theme' ); ?>">
	<label class="search-form__label flex are-vertically-aligned-center">
		<?php SVG::get_instance()->render_svg( 'assets/svg/icon-search-magnifier.svg' ); ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'eutopia-theme' ); ?></span>

		<input type="search" class="search-form__field" placeholder="<?php esc_attr_e( 'What are you looking for?', 'eutopia-theme' ); ?>" value="<?php echo esc_attr( $search_query ); ?>" name="s">

		<button type="button" class="search-form__clear btn btn--ghost btn--icon" <?php echo $button_disabled ? 'disabled' : ''; ?>>
			<?php
			SVG::get_instance()->render_svg(
				'assets/svg/icon-search-clear.svg',
				[
					'width'  => 20,
					'height' => 20,
					'class'  => 'search-form__clear-icon',
				]
			);
			?>
			<span class="screen-reader-text"><?php esc_html_e( 'Clear search query', 'eutopia-theme' ); ?></span>
		</button>
	</label>

	<button type="submit" class="search-form__submit btn btn--primary" <?php echo $button_disabled ? 'disabled' : ''; ?>>
		<?php esc_html_e( 'Search', 'eutopia-theme' ); ?>
	</button>
</form>
