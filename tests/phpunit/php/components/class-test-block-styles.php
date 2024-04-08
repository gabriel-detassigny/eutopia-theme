<?php
/**
 * Styles tests.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Block Styles Class.
 *
 * @coversDefaultClass \Eutopia_Theme\Components\Block_Styles
 */
class Test_Block_Styles extends \WP_UnitTestCase {

	/**
	 * Test class instance.
	 *
	 * @var Styles
	 */
	private $instance;

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function set_up() {
		parent::set_up();

		$this->instance = new Block_Styles();
	}

	/**
	 * Tests Styles::init
	 *
	 * @covers ::init()
	 */
	public function test_init() {
		$this->assertSame( false, has_filter( 'wp_enqueue_scripts', [ $this->instance, 'action_deregister_block_styles' ] ) );
		$this->instance->init();
		$this->assertSame( 10, has_filter( 'wp_enqueue_scripts', [ $this->instance, 'action_deregister_block_styles' ] ) );
	}

	/**
	 * Tests Styles::get_deregistered_block_styles
	 *
	 * @covers ::create_handle()
	 */
	public function test_create_handle() {
		$success_handle = $this->instance->create_handle( 'core/test' );
		$this->assertSame( 'wp-block-test', $success_handle );

		$fail_handle = $this->instance->create_handle( 'eutopia-theme/test' );
		$this->assertSame( '', $fail_handle );
	}

	/**
	 * Tests Styles::get_static_styles_to_deregister
	 *
	 * @covers ::get_static_styles_to_deregister()
	 */
	public function test_get_static_styles_to_deregister() {
		$styles = $this->instance->get_static_styles_to_deregister();

		$this->assertIsArray( $styles );
		$this->assertContains( 'wp-block-library', $styles );
		$this->assertContains( 'wp-block-library-theme', $styles );
	}
}
