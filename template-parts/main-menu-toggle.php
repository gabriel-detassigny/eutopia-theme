<?php
/**
 * Template part for displaying the main menu toggle in the header.
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Nav_Menus;
use Eutopia_Theme\Components\SVG;

if ( ! Nav_Menus::get_instance()?->is_primary_nav_menu_active() ) {
	return;
}

?>

<button class="main-menu-toggle js-use-overlay" aria-label="<?php esc_attr_e( 'Open menu', 'eutopia-theme' ); ?>" aria-controls="navigation-slidein" data-target="#navigation-slidein" aria-expanded="false" data-backdrop="true">
	<?php
	SVG::get_instance()->render_svg(
		'assets/svg/icon-hamburger.svg',
		[
			'width'  => 20,
			'height' => 20,
		]
	);
	?>
</button>

<button aria-label="<?php esc_attr_e( 'Close menu', 'eutopia-theme' ); ?>" data-dismiss="modal" class="close-navigation-slidein">
	<?php
	SVG::get_instance()->render_svg(
		'assets/svg/icon-close.svg',
		[
			'width'  => 20,
			'height' => 20,
		]
	);
	?>
</button>
