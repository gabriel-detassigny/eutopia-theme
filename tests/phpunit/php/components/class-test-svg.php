<?php
/**
 * SVG tests.
 *
 * @package Eutopia_Theme
 */

namespace phpunit\php\components;

use Eutopia_Theme\Components\Svg;

/**
 * Test_Svg Class.
 *
 * @coversDefaultClass \Eutopia_Theme\Components\Svg
 */
class Test_Svg extends \WP_UnitTestCase {

	/**
	 * Can resolve SVGs relative to the defined directory.
	 */
	public function test_can_resolve_svg_with_args() {
		$svg = new Svg( [ realpath( __DIR__ . '/../../data' ) ] );

		$this->assertEquals(
			'<svg xmlns="http://www.w3.org/2000/svg" class="svg-line" id="svg-line" viewBox="0 0 1 1"><line x1="0" y1="0" x2="1" y2="1"></line></svg>',
			$svg->get_svg( 'svg/line.svg' ),
			'Can resolve line.svg file with inline SVG content'
		);

		$this->assertEquals(
			'<svg class="svg-line foo-bar" viewBox="0 0 1 1" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#svg-line"></use></svg>',
			$svg->get_svg( 'svg/line.svg', [ 'class' => 'foo-bar' ] ),
			'Can resolve line.svg file with custom class attribute value appended'
		);

		$this->assertEquals(
			'<svg class="svg-line" viewBox="0 0 1 1" xmlns="http://www.w3.org/2000/svg"><use xlink:href="#svg-line"></use></svg>',
			$svg->get_svg( 'svg/line.svg' ),
			'Can resolve line.svg again with attribution'
		);

		$this->assertEquals(
			'',
			$svg->get_svg( 'none/missing.svg' ),
			'Missing file is returned as empty string'
		);
	}
}
