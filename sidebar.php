<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme;

use Eutopia_Theme\Components\Sidebar;


if ( ! Sidebar::get_instance()?->is_primary_sidebar_active() ) {
	return;
}
?>
<aside id="secondary" class="primary-sidebar widget-area">
	<h2 class="screen-reader-text"><?php esc_html_e( 'Asides', 'eutopia-theme' ); ?></h2>
	<?php Sidebar::get_instance()?->display_primary_sidebar(); ?>
</aside><!-- #secondary -->
