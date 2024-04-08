<?php
/**
 * Eutopia_Theme\Components\Editor class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use RuntimeException;

/**
 * Class for integrating with the block editor.
 *
 * @link https://wordpress.org/gutenberg/handbook/extensibility/theme-support/
 */
class Editor extends Component {


	/**
	 * Asset handle for the Webpack runtime during development.
	 *
	 * @var string
	 */
	const SCRIPT_HANDLE_WEBPACK_RUNTIME = 'webpack-runtime';

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'action_add_editor_support' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'editor_script_enqueue' ] );
	}

	/**
	 * Adds support for various editor features.
	 *
	 * @return void
	 */
	public function action_add_editor_support() {
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for default block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for wide-aligned images.
		add_theme_support( 'align-wide' );
	}

	/**
	 * Enqueue editor assets.
	 *
	 * @throws RuntimeException Throws if script asset is not found.
	 * @return void
	 */
	public function editor_script_enqueue() {

		$dependencies = [];

		// Enqueue the Webpack runtime shared between all bundles.
		$webpack_runtime_asset_path = gdt_theme_meta()->path( 'build/webpack/runtime.asset.php' );

		if ( is_readable( $webpack_runtime_asset_path ) ) {
			$webpack_runtime_asset = require $webpack_runtime_asset_path;

			wp_register_script(
				self::SCRIPT_HANDLE_WEBPACK_RUNTIME,
				gdt_theme_meta()->url( 'build/webpack/runtime.js' ),
				$webpack_runtime_asset['dependencies'],
				$webpack_runtime_asset['version'],
				true
			);

			$dependencies[] = self::SCRIPT_HANDLE_WEBPACK_RUNTIME;
		}

		$script_asset_path = gdt_theme_meta()->path( 'build/js/editor.asset.php' );

		if ( ! file_exists( $script_asset_path ) ) {
			throw new RuntimeException( 'Built JavaScript assets not found. Please run `npm run build`' );
		}

		$script_asset = require $script_asset_path; // phpcs:disable WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

		wp_enqueue_script(
			'gabriel-detassigny-editor',
			gdt_theme_meta()->url( 'build/js/editor.js' ),
			array_merge( $script_asset['dependencies'], $dependencies ),
			$script_asset['version'],
			true
		);

		$editor_data = apply_filters( 'eutopia_theme_editor_data', [] );

		if ( ! empty( $editor_data ) ) {
			wp_add_inline_script( 'gabriel-detassigny-editor', 'const eutopiaEditorObject = ' . wp_json_encode( $editor_data ), 'before' );
		}
	}
}
