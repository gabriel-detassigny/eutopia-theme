<?php
/**
 * Template part for displaying the header branding
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\SVG;

?>
<div class="site-branding">
	<?php
		SVG::get_instance()->render_svg(
			'assets/svg/logo-baseline-pervenche.svg',
			[
				'width'  => 186,
				'height' => 62,
			]
		);
		?>
</div><!-- .site-branding -->
