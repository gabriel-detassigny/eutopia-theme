<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Infinite_Scroll;
use Eutopia_Theme\Components\Nav_Menus;
use Eutopia_Theme\Components\Sidebar;

if ( Infinite_Scroll::get_instance()?->is_infinite_scroll_page() ) {
	return;
}

?>

	<footer id="colophon" class="site-footer">
		<div class="site-footer__container">
			<div class="site-footer__meta">
				<?php get_template_part( 'template-parts/branding-footer' ); ?>

				<?php if ( Nav_Menus::get_instance()?->is_social_nav_menu_active() ) : ?>
					<nav class="site-footer__social">
						<p class="site-footer__social-header">
							<?php echo esc_html( Nav_Menus::get_instance()?->get_footer_social_menu_name() ); ?>
						</p>
						<?php Nav_Menus::get_instance()?->display_social_nav_menu(); ?>
					</nav>
				<?php endif; ?>
			</div>

			<?php if ( Sidebar::get_instance()?->is_footer_sidebar_active() ) : ?>
				<div class="site-footer__navigation">
					<?php Sidebar::get_instance()?->display_footer_sidebar(); ?>
				</div><!-- .site-footer__navigation -->
			<?php endif; ?>
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
