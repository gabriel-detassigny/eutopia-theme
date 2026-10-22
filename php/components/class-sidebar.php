<?php
/**
 * Eutopia_Theme\Components\Sidebar class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Class for managing sidebars.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/
 */
class Sidebar extends Component {


	const PRIMARY_SIDEBAR_SLUG = 'sidebar-1';

	/**
	 * @var string Footer sidebar slug.
	 */
	const FOOTER_SIDEBAR_SLUG = 'footer-sidebar';

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'widgets_init', [ $this, 'action_register_sidebars' ] );
		add_filter( 'body_class', [ $this, 'filter_body_classes' ] );
	}

	/**
	 * Registers the sidebars.
	 *
	 * @return void
	 */
	public function action_register_sidebars() {
		register_sidebar(
			[
				'name'          => esc_html__( 'Sidebar', 'eutopia-theme' ),
				'id'            => static::PRIMARY_SIDEBAR_SLUG,
				'description'   => esc_html__( 'Add widgets here.', 'eutopia-theme' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);

		register_sidebar(
			[
				'name'          => esc_html__( 'Footer Sidebar', 'eutopia-theme' ),
				'id'            => static::FOOTER_SIDEBAR_SLUG,
				'description'   => esc_html__( 'Add widgets here.', 'eutopia-theme' ),
				'before_widget' => '<section id="%1$s" class="site-footer__menu widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			]
		);
	}

	/**
	 * Adds custom classes to indicate whether a sidebar is present to the array of body classes.
	 *
	 * @param array<string> $classes Classes for the body element.
	 * @return array<string> Filtered body classes.
	 */
	public function filter_body_classes( array $classes ) {
		global $template;
		$is_template_with_sidebar = ! in_array( basename( $template ), [ 'front-page.php', '404.php', '500.php', 'offline.php' ] );

		if ( $is_template_with_sidebar && $this->is_primary_sidebar_active() ) {
			$classes[] = 'has-sidebar';
		} else {
			$classes[] = 'no-sidebar';
		}

		return $classes;
	}

	/**
	 * Checks whether the primary sidebar is active.
	 *
	 * @return bool True if the primary sidebar is active, false otherwise.
	 */
	public function is_primary_sidebar_active() {
		return is_active_sidebar( static::PRIMARY_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the footer sidebar is active.
	 *
	 * @return bool True if the footer sidebar is active, false otherwise.
	 */
	public function is_footer_sidebar_active() {
		return is_active_sidebar( static::FOOTER_SIDEBAR_SLUG );
	}

	/**
	 * Displays the primary sidebar.
	 *
	 * @return void
	 */
	public function display_primary_sidebar() {
		dynamic_sidebar( static::PRIMARY_SIDEBAR_SLUG );
	}

	/**
	 * Displays the footer sidebar.
	 *
	 * @return void
	 */
	public function display_footer_sidebar() {
		dynamic_sidebar( static::FOOTER_SIDEBAR_SLUG );
	}
}
