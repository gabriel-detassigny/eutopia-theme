<?php
/**
 * Template part for displaying the mobile navigation menu
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Nav_Menus;

if ( ! Nav_Menus::get_instance()?->is_primary_nav_menu_active() ) {
	return;
}

?>

<nav id="navigation-slidein" class="navigation-slidein" aria-label="<?php esc_attr_e( 'Main menu', 'eutopia-theme' ); ?>">
	<?php Nav_Menus::get_instance()?->display_primary_nav_menu( [ 'menu_id' => 'primary-menu' ] ); ?>
</nav><!-- #site-navigation -->
