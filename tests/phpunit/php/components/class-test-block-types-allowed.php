<?php
/**
 * Block_Types_Allowed tests.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Test Block_Types_Allowed.
 */
class Test_Block_Types_Allowed extends \WP_UnitTestCase {

	/**
	 * Test class instance.
	 *
	 * @var Block_Types_Allowed
	 */
	private $instance;

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function set_up() {
		parent::set_up();

		$this->instance = new Block_Types_Allowed();
	}

	/**
	 * Tests filter init logic.
	 */
	public function test_init() {
		if ( version_compare( $GLOBALS['wp_version'], '5.8-alpha-1', '<' ) ) {
			$this->assertFalse( has_filter( 'allowed_block_types', [ $this->instance, 'filter_maybe_disable_block_types' ] ) );
			$this->instance->init();
			$this->assertEquals( 10, has_filter( 'allowed_block_types', [ $this->instance, 'filter_maybe_disable_block_types' ] ) );
		} else {
			$this->assertFalse( has_filter( 'allowed_block_types_all', [ $this->instance, 'filter_maybe_disable_block_types' ] ) );
			$this->instance->init();
			$this->assertEquals( 10, has_filter( 'allowed_block_types_all', [ $this->instance, 'filter_maybe_disable_block_types' ] ) );
		}
	}

	/**
	 * Allowed core blocks contain expected values.
	 */
	public function test_get_allowed_core_block_types() {
		$allowed_core_blocks = $this->instance->get_allowed_core_block_types();

		$this->assertIsArray( $allowed_core_blocks );
		$this->assertNotContains( 'core/columns', $allowed_core_blocks );
		$this->assertContains( 'core/paragraph', $allowed_core_blocks );

		add_filter(
			'eutopia_theme_allowed_core_block_types',
			function ( $block_types ) {
				$block_types[] = 'core/columns';
				return $block_types;
			}
		);

		$this->assertContains(
			'core/columns',
			$this->instance->get_allowed_core_block_types(),
			'Filter can enable other core blocks'
		);
	}

	/**
	 * Filter removes blocks.
	 */
	public function test_filter_maybe_disable_block_types() {
		$allowed_blocks = $this->instance->filter_maybe_disable_block_types();

		$this->assertIsArray( $allowed_blocks );
		$this->assertNotContains( 'jetpack/random-block', $allowed_blocks );
		$this->assertContains( 'core/paragraph', $allowed_blocks );

		add_filter(
			'eutopia_theme_allowed_block_types',
			function ( $block_types ) {
				$block_types[] = 'jetpack/random-block';
				return $block_types;
			}
		);

		$this->assertContains(
			'jetpack/random-block',
			$this->instance->filter_maybe_disable_block_types(),
			'Filter can enable any other block'
		);
	}
}
