<?php
/**
 * Eutopia_Theme\Components\Custom_Header class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Class for adding custom header support.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 */
class Custom_Header extends Component {


	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'action_add_custom_header_support' ] );
	}

	/**
	 * Adds support for the Custom Logo feature.
	 *
	 * @return void
	 */
	public function action_add_custom_header_support() {
		add_theme_support(
			'custom-header',
			apply_filters(
				'eutopia_theme_custom_header_args',
				[
					'default-image'      => '',
					'default-text-color' => '000000',
					'width'              => 1600,
					'height'             => 250,
					'flex-height'        => true,
					'wp-head-callback'   => [ $this, 'wp_head_callback' ],
				]
			)
		);
	}

	/**
	 * Outputs extra styles for the custom header, if necessary.
	 *
	 * @return void
	 */
	public function wp_head_callback() {
		$header_text_color = get_header_textcolor();

		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		if ( ! display_header_text() ) {
			echo '<style type="text/css">.site-title, .site-description { position: absolute; clip: rect(1px, 1px, 1px, 1px); }</style>';
			return;
		}

		echo '<style type="text/css">.site-title a, .site-description { color: #' . esc_attr( $header_text_color ) . '; }</style>';
	}
}
