<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Infinite_Scroll;
use Eutopia_Theme\Components\SVG;

if ( Infinite_Scroll::get_instance()?->is_infinite_scroll_page() ) {
	return;
}

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'eutopia-theme' ); ?></a>

	<!-- The search box is on the top to be positioned in absolute at 0px  -->
	<?php get_template_part( 'template-parts/search-box' ); ?>

	<!-- The mobile navigation to overlay -->
	<?php get_template_part( 'template-parts/navigation-slidein' ); ?>

	<?php get_template_part( 'template-parts/top-bar' ); ?>

	<header id="masthead" class="site-header">
		<div class="site-header__container">
			<div class="site-header__branding">
				<?php get_template_part( 'template-parts/branding' ); ?>
			</div>
			<div class="site-header__navigation">
				<?php get_template_part( 'template-parts/navigation' ); ?>
			</div>
			<div class="site-header__search-toggle">
				<button
					class="js-use-overlay search-toggle btn btn--ghost btn--icon btn--icon-sm"
					aria-label="<?php esc_attr_e( 'Open search', 'eutopia-theme' ); ?>"
					aria-expanded="false"
					data-scroll="true"
					data-target="#search-box"
					data-backdrop="true"
					data-focus=".search-form__field"
				>
					<?php SVG::get_instance()->render_svg( 'assets/svg/icon-search-magnifier.svg' ); ?>
				</button>
				<!--
					If the design hides the search trigger,
					once the search is open, remove this button
					and update css/components/_site-header.scss
				-->
				<button
					class="close-search-toggle btn--ghost btn--icon btn--icon-sm"
					aria-label="<?php esc_attr_e( 'Close search', 'eutopia-theme' ); ?>"
					data-dismiss="modal"
				>
					<?php
					SVG::get_instance()->render_svg(
						'assets/svg/icon-close.svg',
						[
							'width'  => 24,
							'height' => 25,
						]
					);
					?>
				</button>
			</div>
			<div class="site-header__main-menu-toggle">
				<?php get_template_part( 'template-parts/main-menu-toggle' ); ?>
			</div>
		</div>
	</header><!-- #masthead -->
