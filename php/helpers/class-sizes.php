<?php
/**
 * Eutopia_Theme\Helpers\Sizes class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Helpers;

/**
 * Class for managing sizes used in the theme.
 *
 * Keep those list in sync with the SCSS variables stored in `/css/tools/_scss-variables.scss`.
 */
class Sizes {
	/**
	 * Theme sizes (content widths).
	 */
	const CONTENT      = 792;
	const CONTENT_WIDE = 1200;

	/**
	 * CSS breakpoints.
	 */
	const BREAK_MOBILE  = 0;
	const BREAK_TABLET  = 792; // = CONTENT.
	const BREAK_DESKTOP = 872;
	const BREAK_WIDE    = 1232; // = CONTENT_WIDE + inline spacing.
}
