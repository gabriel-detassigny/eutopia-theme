<?php
/**
 * Theme_Meta tests.
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Theme_Meta;

/**
 * Test Environment.
 */
class Theme_Meta_Test extends WP_UnitTestCase {

	/**
	 * Can resolve URLs relative to the theme directory.
	 */
	public function test_resolve_urls() {
		$theme_meta = new Theme_Meta( '1.2.3', get_template_directory() );

		$this->assertEquals(
			get_theme_file_uri( 'dist/css/style.css' ),
			$theme_meta->url( 'dist/css/style.css' ),
			'Can append to URL by chunk'
		);

		$this->assertEquals(
			get_theme_file_uri( 'dist/css/style.css' ),
			$theme_meta->url( 'dist', 'css', 'style.css' ),
			'Can append to URL by parts'
		);
	}

	/**
	 * Can resolve paths relative to the theme root directory.
	 */
	public function test_can_resolve_path() {
		$theme_meta = new Theme_Meta( '1.2.3', '/path/to/theme-directory' );

		$this->assertEquals(
			$this->path( '/path/to/theme-directory' ),
			$theme_meta->path(),
			'Returns root path by default'
		);

		$this->assertEquals(
			$this->path( '/path/to/theme-directory/css/dist/style.css' ),
			$theme_meta->path( 'css/dist/style.css' ),
			'Can append paths'
		);

		$this->assertEquals(
			$this->path( '/path/to/theme-directory/js/dist/script.js' ),
			$theme_meta->path( 'js', 'dist', 'script.js' ),
			'Can resolve by parts'
		);
	}

	/**
	 * Can resolve relative paths to theme root directory.
	 */
	public function test_can_resolve_paths_to_root() {
		$theme_meta = new Theme_Meta( '1.2.3', '/path/to/theme-directory' );

		$this->assertEquals(
			$this->path( '../../..' ),
			$theme_meta->path_to_root( '/path/to/theme-directory/css/dist/style.css' ),
			'Can resolve down to theme root'
		);

		$this->assertEquals(
			$this->path( 'to/theme-directory' ),
			$theme_meta->path_to_root( '/path/' ),
			'Can resolve up to theme root'
		);

		$this->assertNull(
			$theme_meta->path_to_root( '/not-matching-path' ),
			'Ingores paths outside of the known tree'
		);
	}

	/**
	 * Can resolve paths relative from the theme root directory.
	 */
	public function test_can_resolve_paths_from_root() {
		$theme_meta = new Theme_Meta( '1.2.3', '/path/to/theme-directory' );

		$this->assertEquals(
			$this->path( 'css/dist/style.css' ),
			$theme_meta->path_from_root( '/path/to/theme-directory/css/dist/style.css' ),
			'Can resolve down to theme root'
		);

		$this->assertNull(
			$theme_meta->path_from_root( '/path/to' ),
			'Ingores paths under the root directory'
		);
	}

	/**
	 * Account for tests needing different mocks based on OS used.
	 *
	 * @param string $path Any path fragment.
	 *
	 * @return string Normalized path fragment using DIRECTORY_SEPARATOR for directory separation.
	 */
	protected function path( $path ) {
		return str_replace( '/', DIRECTORY_SEPARATOR, $path );
	}
}
