<?php
/**
 * Template part for displaying the header branding
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Site_Logo;
use Eutopia_Theme\Components\SVG;

?>
<div class="site-branding">
	<?php if ( Site_Logo::get_instance()?->has_header_logo() ) : ?>
		<?php Site_Logo::get_instance()?->render_header_logo(); ?>
	<?php else : ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
			<?php
			SVG::get_instance()->render_svg(
				'assets/svg/logo.svg',
				[
					'width'  => 186,
					'height' => 35,
				],
			);
			?>
		</a>
	<?php endif; ?>

	<?php
	if ( ! Site_Logo::get_instance()?->show_logo_only() ) {
		if ( is_front_page() && is_home() ) {
			?>
			<h1 class="screen-reader-text"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php
		} else {
			?>
			<p class="screen-reader-text"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
		}
	}
	?>
</div><!-- .site-branding -->
