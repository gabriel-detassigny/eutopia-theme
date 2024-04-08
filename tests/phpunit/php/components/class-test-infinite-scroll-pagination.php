<?php
/**
 * Infinite scroll pagination tests.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Ininite_Scroll Class.
 *
 * @coversDefaultClass \Eutopia_Theme\Components\Infinite_Scroll
 */
class Test_Infinite_Scroll_Pagination extends \WP_UnitTestCase {
	/**
	 * Test class instance.
	 *
	 * @var Infinite_Scroll
	 */
	private $instance;

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function set_up() {
		parent::set_up();

		$this->instance = new Infinite_Scroll();
	}

	/**
	 * Tests Infinite_Scroll::init
	 *
	 * @covers ::init()
	 */
	public function test_init() {
		$this->assertSame( false, has_filter( 'query_vars', [ $this->instance, 'filter_add_new_query_var' ] ) );
		$this->assertSame( false, has_filter( 'init', [ $this->instance, 'action_add_new_url_pattern' ] ) );
		$this->instance->init();
		$this->assertSame( 9, has_filter( 'query_vars', [ $this->instance, 'filter_add_new_query_var' ] ) );
		$this->assertSame( 11, has_filter( 'init', [ $this->instance, 'action_add_new_url_pattern' ] ) );
	}

	/**
	 * Tests Infinite_Scroll::is_infinite_scroll_page
	 *
	 * @covers ::is_infinite_scroll_page()
	 */
	public function test_is_infinite_scroll_page() {
		// Test that the method returns true when the query var is set to 1.
		$this->go_to( '/?infinite-scroll-pagination=1' );
		$this->assertTrue( $this->instance->is_infinite_scroll_page() );

		// Test that the method returns false when the query var is not set to 1.
		$this->go_to( '/' );
		$this->assertFalse( $this->instance->is_infinite_scroll_page() );
	}
}
