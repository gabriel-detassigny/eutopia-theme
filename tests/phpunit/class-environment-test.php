<?php
/**
 * Environment tests.
 *
 * @package Eutopia_Theme
 */

/**
 * Test Environment.
 */
class Environment extends WP_UnitTestCase {

	/**
	 * Ensure that WP is loaded.
	 */
	public function test_wordpress_and_plugin_are_loaded() {
		$this->assertTrue( function_exists( 'do_action' ) );
	}
}
