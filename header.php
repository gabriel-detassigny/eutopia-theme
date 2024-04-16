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

use Eutopia_Theme\Helpers\Utils;

$header_classes = [ 'site-header' ];
if ( Utils::supports_navbar_overlay() ) {
	$header_classes[] = 'site-header--inverted';
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

	<header id="masthead" class="<?php echo esc_attr( implode( ' ', $header_classes ) ); ?>">
		<div class="site-header__container">
			<div class="site-header__branding">
				<?php get_template_part( 'template-parts/branding' ); ?>
			</div>
			<div class="site-header__navigation">
				<?php get_template_part( 'template-parts/navigation' ); ?>
			</div>
			<div class="site-header__main-menu-toggle">
				<?php get_template_part( 'template-parts/main-menu-toggle' ); ?>
			</div>
		</div>
	</header><!-- #masthead -->
