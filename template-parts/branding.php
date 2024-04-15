<?php
/**
 * Template part for displaying the header branding
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\SVG;

?>
<div class="site-branding">
	<a class="branding-logo" href="/" rel="home" aria-label="<?php esc_attr_e( 'homepage', 'eutopia-theme' ); ?>">
		<?php
		SVG::get_instance()->render_svg(
			'assets/svg/logo-baseline.svg',
			[
				'width'  => 186,
				'height' => 62,
			]
		);
		?>
	</a>
</div><!-- .site-branding -->
