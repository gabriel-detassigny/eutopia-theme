<?php
/**
 * Template part for displaying the footer branding
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\SVG;

?>

<div class="site-footer__branding">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-footer-link" rel="home" aria-label="homepage">
		<?php
		SVG::get_instance()->render_svg(
			'assets/svg/logo-baseline-canard.svg',
			[
				'width'  => 186,
				'height' => 62,
			]
		);
		?>
	</a>
</div><!-- .site-footer__branding -->
