<?php
/**
 * Template part for displaying the footer branding
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Site_Logo;

?>

<div class="site-footer__branding">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-footer-link" rel="home">
		<?php if ( Site_Logo::get_instance()?->has_footer_logo() ) : ?>
			<?php Site_Logo::get_instance()?->render_footer_logo(); ?>
		<?php else : ?>
			<?php if ( Site_Logo::get_instance()?->has_header_logo() ) : ?>
				<?php Site_Logo::get_instance()?->render_header_logo(); ?>
			<?php else : ?>
				<img width="64" height="64" src="<?php echo esc_url( gdt_theme_meta()->url( 'assets/svg/logo-small.svg' ) ); ?>">
			<?php endif; ?>
		<?php endif; ?>
	</a>
</div><!-- .site-footer__branding -->
