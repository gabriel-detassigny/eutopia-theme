<?php
/**
 * Eutopia_Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Theme;
use Eutopia_Theme\Theme_Meta;
use Eutopia_Theme\Helpers\Autoload;

require_once __DIR__ . '/php/helpers/class-autoload.php';

// Load Composer autoloader if available.
if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Autoloader serves for `Eutopia_Theme` namespace and autoload all files under the php directory.
$autoload = new Autoload();
$autoload->add( 'Eutopia_Theme', sprintf( '%s/php', __DIR__ ) );

/**
 * Set the theme version here.
 */
$theme_version = '1.0';

/**
 * Retrieves the theme meta data.
 *
 * @return Theme_Meta
 */
function gdt_theme_meta() {
	global $theme_version;
	static $theme_meta = null;

	if ( ! $theme_meta ) {
		$theme_meta = new Theme_Meta( $theme_version, __DIR__ );
	}

	return $theme_meta;
}

/**
 * Retrieves an instance of the theme.
 *
 * @return Theme
 */
function gdt_theme() {
	static $theme = null;

	if ( ! $theme ) {
		$theme = new Theme(
			[
				new Eutopia_Theme\Components\ACF_Block_Registry(),
				new Eutopia_Theme\Components\Accessibility(),
				new Eutopia_Theme\Components\Block_Patterns(),
				new Eutopia_Theme\Components\Block_Registry(),
				new Eutopia_Theme\Components\Block_Styles(),
				new Eutopia_Theme\Components\Block_Types_Allowed(),
				new Eutopia_Theme\Components\Comments(),
				new Eutopia_Theme\Components\Custom_Header(),
				new Eutopia_Theme\Components\Editor(),
				new Eutopia_Theme\Components\Entry_Meta_Labels(),
				new Eutopia_Theme\Components\Foundation(),
				new Eutopia_Theme\Components\Images(),
				new Eutopia_Theme\Components\Localization(),
				new Eutopia_Theme\Components\Nav_Menus(),
				new Eutopia_Theme\Components\Reading_Settings(),
				new Eutopia_Theme\Components\Script_Custom_Attributes_Handler(),
				new Eutopia_Theme\Components\Scripts(),
				new Eutopia_Theme\Components\Sidebar(),
				new Eutopia_Theme\Components\Styles(),
				new Eutopia_Theme\Components\SVG(
					[
						__DIR__ . '/assets/svg',
						__DIR__, // Also allow SVGs relative to the theme root.
					]
				),
			]
		);
	}

	return $theme;
}

gdt_theme()->init();

if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 *
	 * @return void
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

unset( $theme_version );
