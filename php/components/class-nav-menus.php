<?php
/**
 * Eutopia_Theme\Components\Nav_Menus class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use WP_Post;

/**
 * Class for managing navigation menus.
 */
class Nav_Menus extends Component {


	/**
	 * Primary nav slug.
	 */
	const PRIMARY_NAV_MENU_SLUG = 'primary';

	/**
	 * Social nav slug.
	 */
	const SOCIAL_NAV_MENU_SLUG = 'social';

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'action_register_nav_menus' ] );
		add_filter( 'walker_nav_menu_start_el', [ $this, 'filter_primary_nav_menu_dropdown_symbol' ], 10, 4 );
		add_filter( 'nav_menu_item_args', [ $this, 'filter_nav_menu_social_symbol' ], 10, 2 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'filter_mobile_nav_menu_close_modal' ], 10, 3 );
	}

	/**
	 * Registers the navigation menus.
	 *
	 * @return void
	 */
	public function action_register_nav_menus() {
		register_nav_menus(
			[
				static::PRIMARY_NAV_MENU_SLUG => esc_html__( 'Primary', 'eutopia-theme' ),
				static::SOCIAL_NAV_MENU_SLUG  => esc_html__( 'Social', 'eutopia-theme' ),
			]
		);
	}

	/**
	 * Adds a dropdown symbol to nav menu items with children.
	 *
	 * Adds the dropdown markup after the menu link element,
	 * before the submenu.
	 *
	 * Javascript converts the symbol to a toggle button.
	 *
	 * @TODO:
	 * - This doesn't work for the page menu because it
	 *   doesn't have a similar filter. So the dropdown symbol
	 *   is only being added for page menus if JS is enabled.
	 *   Create a ticket to add to core?
	 *
	 * @param string  $item_output The menu item's starting HTML output.
	 * @param WP_Post $item        Menu item data object.
	 * @param int     $depth       Depth of menu item. Used for padding.
	 * @param object  $args        An object of wp_nav_menu() arguments.
	 * @return string Modified nav menu HTML.
	 */
	public function filter_primary_nav_menu_dropdown_symbol( $item_output, WP_Post $item, $depth, $args ) {
		// Only for our primary menu location.
		if ( empty( $args->theme_location ) || static::PRIMARY_NAV_MENU_SLUG !== $args->theme_location ) {
			return $item_output;
		}

		// Add the dropdown for items that have children.
		if ( ! empty( $item->classes ) && in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$chrevron_icon = SVG::get_instance()->get_svg(
				'icon-chevron',
				[
					'class'  => 'icon icon-chevron-down',
					'height' => 12,
					'width'  => 12,
				]
			);

			return $item_output . '<span class="dropdown">' . $chrevron_icon . '</span>';
		}

		return $item_output;
	}

	/**
	 * Checks whether the primary navigation menu is active.
	 *
	 * @return bool True if the primary navigation menu is active, false otherwise.
	 */
	public function is_primary_nav_menu_active() {
		return (bool) has_nav_menu( static::PRIMARY_NAV_MENU_SLUG );
	}

	/**
	 * Checks whether the social navigation menu is active.
	 *
	 * @return bool True if the social navigation menu is active, false otherwise.
	 */
	public function is_social_nav_menu_active() {
		return (bool) has_nav_menu( static::SOCIAL_NAV_MENU_SLUG );
	}

	/**
	 * Displays the primary navigation menu.
	 *
	 * @param array $args {
	 *     Optional. Array of nav menu arguments.
	 *
	 *     @type int|string|\WP_Term $menu                 Desired menu. Accepts a menu ID, slug, name, or object.
	 *                                                    Default empty.
	 *     @type string             $menu_class           CSS class to use for the ul element which forms the menu.
	 *                                                    Default 'menu'.
	 *     @type string             $menu_id              The ID that is applied to the ul element which forms the menu.
	 *                                                    Default is the menu slug, incremented.
	 *     @type string             $container            Whether to wrap the ul, and what to wrap it with.
	 *                                                    Default 'div'.
	 *     @type string             $container_class      Class that is applied to the container.
	 *                                                    Default 'menu-{menu slug}-container'.
	 *     @type string             $container_id         The ID that is applied to the container. Default empty.
	 *     @type string             $container_aria_label The aria-label attribute that is applied to the container
	 *                                                    when it's a nav element. Default empty.
	 *     @type callable|false     $fallback_cb          If the menu doesn't exist, a callback function will fire.
	 *                                                    Default is 'wp_page_menu'. Set to false for no fallback.
	 *     @type string             $before               Text before the link markup. Default empty.
	 *     @type string             $after                Text after the link markup. Default empty.
	 *     @type string             $link_before          Text before the link text. Default empty.
	 *     @type string             $link_after           Text after the link text. Default empty.
	 *     @type bool               $echo                 Whether to echo the menu or return it. Default true.
	 *     @type int                $depth                How many levels of the hierarchy are to be included.
	 *                                                    0 means all. Default 0.
	 *                                                    Default 0.
	 *     @type object             $walker               Instance of a custom walker class. Default empty.
	 *     @type string             $items_wrap           How the list items should be wrapped. Uses printf() format with
	 *                                                    numbered placeholders. Default is a ul with an id and class.
	 *     @type string             $item_spacing         Whether to preserve whitespace within the menu's HTML.
	 *                                                    Accepts 'preserve' or 'discard'. Default 'preserve'.
	 * }
	 *
	 * @return void
	 */
	public function display_primary_nav_menu( array $args = [] ) {
		if ( ! isset( $args['container'] ) ) {
			$args['container'] = '';
		}

		$args['theme_location'] = static::PRIMARY_NAV_MENU_SLUG;

		wp_nav_menu( $args );
	}

	/**
	 * Displays the social navigation menu.
	 *
	 * @param array $args {
	 *     Optional. Array of nav menu arguments.
	 *
	 *     @type int|string|\WP_Term $menu                 Desired menu. Accepts a menu ID, slug, name, or object.
	 *                                                    Default empty.
	 *     @type string             $menu_class           CSS class to use for the ul element which forms the menu.
	 *                                                    Default 'menu'.
	 *     @type string             $menu_id              The ID that is applied to the ul element which forms the menu.
	 *                                                    Default is the menu slug, incremented.
	 *     @type string             $container            Whether to wrap the ul, and what to wrap it with.
	 *                                                    Default 'div'.
	 *     @type string             $container_class      Class that is applied to the container.
	 *                                                    Default 'menu-{menu slug}-container'.
	 *     @type string             $container_id         The ID that is applied to the container. Default empty.
	 *     @type string             $container_aria_label The aria-label attribute that is applied to the container
	 *                                                    when it's a nav element. Default empty.
	 *     @type callable|false     $fallback_cb          If the menu doesn't exist, a callback function will fire.
	 *                                                    Default is 'wp_page_menu'. Set to false for no fallback.
	 *     @type string             $before               Text before the link markup. Default empty.
	 *     @type string             $after                Text after the link markup. Default empty.
	 *     @type string             $link_before          Text before the link text. Default empty.
	 *     @type string             $link_after           Text after the link text. Default empty.
	 *     @type bool               $echo                 Whether to echo the menu or return it. Default true.
	 *     @type int                $depth                How many levels of the hierarchy are to be included.
	 *                                                    0 means all. Default 0.
	 *                                                    Default 0.
	 *     @type object             $walker               Instance of a custom walker class. Default empty.
	 *     @type string             $items_wrap           How the list items should be wrapped. Uses printf() format with
	 *                                                    numbered placeholders. Default is a ul with an id and class.
	 *     @type string             $item_spacing         Whether to preserve whitespace within the menu's HTML.
	 *                                                    Accepts 'preserve' or 'discard'. Default 'preserve'.
	 * }
	 *
	 * @return void
	 */
	public function display_social_nav_menu( array $args = [] ) {
		if ( ! isset( $args['container'] ) ) {
			$args['container'] = '';
		}

		$args['theme_location'] = static::SOCIAL_NAV_MENU_SLUG;

		wp_nav_menu( $args );
	}

	/**
	 * Add the social network icon for social menu items that have SVG inline.
	 *
	 * @param \stdClass $args  An object of wp_nav_menu() arguments.
	 * @param \WP_Post  $item  Menu item data object.
	 *
	 * @return \stdClass wp_nav_menu arguments.
	 */
	public function filter_nav_menu_social_symbol( $args, $item ) {
		// Only for social menu location.
		if ( empty( $args->theme_location ) || static::SOCIAL_NAV_MENU_SLUG !== $args->theme_location ) {
			return $args;
		}

		// Get the icon.
		$item_icon = SVG::get_instance()->get_svg(
			sprintf( 'assets/svg/icon-%s.svg', sanitize_key( $item->post_name ) ),
			[
				'width'  => 24,
				'height' => 24,
			]
		);

		// Add the icon only, if available.
		if ( ! empty( $item_icon ) ) {
			$args->link_before  = '<span class="icon--l icon--social">' . $item_icon . '</span>';
			$args->link_before .= '<span class="screen-reader-text">';
			$args->link_after   = '</span>';
		}

		return $args;
	}

	/**
	 * Returns the footer social menu name.
	 *
	 * @return string The footer nav menu name.
	 */
	public function get_footer_social_menu_name(): string {
		return wp_get_nav_menu_name( static::SOCIAL_NAV_MENU_SLUG );
	}

	/**
	 * Adds the `data-dismiss` attribute to close the mobile nav menu modal.
	 *
	 * @param array   $atts The HTML attributes applied to the menu item's `<a>` element.
	 * @param WP_Post $item The current menu item.
	 * @param object  $args An object of wp_nav_menu() arguments.
	 * @return array Modified HTML attributes.
	 */
	public function filter_mobile_nav_menu_close_modal( array $atts, WP_Post $item, object $args ): array {
		if ( empty( $args->theme_location ) || static::PRIMARY_NAV_MENU_SLUG !== $args->theme_location ) {
			return $atts;
		}

		if ( ! str_contains( $args->menu_class ?? '', 'menu--slidein' ) ) {
			return $atts;
		}

		$atts['data-dismiss'] = 'modal';

		return $atts;
	}
}
