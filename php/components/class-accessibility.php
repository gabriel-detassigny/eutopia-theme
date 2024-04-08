<?php
/**
 * Eutopia_Theme\Components\Accessibility class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use WP_Post;

/**
 * Class for improving accessibility among various core features.
 */
class Accessibility extends Component {

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_filter( 'nav_menu_link_attributes', [ $this, 'filter_nav_menu_link_attributes_aria_current' ], 10, 2 );
		add_filter( 'page_menu_link_attributes', [ $this, 'filter_nav_menu_link_attributes_aria_current' ], 10, 2 );
	}

	/**
	 * Filters the HTML attributes applied to a menu item's anchor element.
	 *
	 * Checks if the menu item is the current menu item and adds the aria "current" attribute.
	 *
	 * @param array   $atts The HTML attributes applied to the menu item's `<a>` element.
	 * @param WP_Post $item The current menu item.
	 * @return array Modified HTML attributes
	 */
	public function filter_nav_menu_link_attributes_aria_current( array $atts, WP_Post $item ) {
		if ( isset( $item->current ) ) {
			if ( $item->current ) {
				$atts['aria-current'] = 'page';
			}
		} elseif ( ! empty( $item->ID ) ) {
			global $post;

			if ( ! empty( $post->ID ) && (int) $post->ID === (int) $item->ID ) {
				$atts['aria-current'] = 'page';
			}
		}

		return $atts;
	}
}
