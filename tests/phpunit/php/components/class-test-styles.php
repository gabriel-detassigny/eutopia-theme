<?php
/**
 * Styles tests.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Test_Styles Class.
 */
class Test_Styles extends \WP_UnitTestCase {

	/**
	 * Actions for enqueueing styles are registered.
	 */
	public function test_init() {
		$this->assertSame( 10, has_action( 'wp_enqueue_scripts', [ Styles::get_instance(), 'action_enqueue_styles' ] ) );
	}

	/**
	 * Test that all styles have been enqueued.
	 */
	public function test_styles_enqueued_and_loaded() {
		Styles::get_instance()->action_enqueue_styles(); // Trigger the enqueue logic.

		$wp_styles = wp_styles();
		$styles    = Styles::get_instance()->get_css_files();

		$this->assertNotEmpty( $styles, 'Style files provided' );

		foreach ( $styles as $handle => $style_data ) {
			$this->assertEquals(
				sprintf( '%s/build/css/%s', get_stylesheet_directory(), $style_data['file'] ),
				wp_styles()->get_data( $handle, 'path' ),
				'Path to style file is provided for optional inlining'
			);

			ob_start();
			$wp_styles->do_item( $handle );
			$tag = ob_get_clean();

			$this->assertStringContainsString( $handle, $tag, 'Style handle is used in the output' );
			$this->assertStringContainsString( $wp_styles->registered[ $handle ]->src, $tag, 'Style URL is part of the output' );
		}
	}
}
