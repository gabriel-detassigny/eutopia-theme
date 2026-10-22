<?php
/**
 * Eutopia_Theme\Components\Scripts class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use RuntimeException;

/**
 * Class for managing scripts.
 */
class Scripts extends Component {


	/**
	 * Asset handle for the Webpack runtime during development.
	 *
	 * @var string
	 */
	const SCRIPT_HANDLE_WEBPACK_RUNTIME = 'webpack-runtime';

	/**
	 * Handles for shared dependencies such as the webpack runtime.
	 *
	 * @var array
	 */
	private $dependencies = [];

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		// Register the shared runtime before any other scripts.
		add_action( 'enqueue_block_editor_assets', [ $this, 'register_shared_runtime' ], 8 );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_shared_runtime' ], 8 );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_shared_runtime' ], 8 );

		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'configure_core_scripts' ] );
		add_action( 'wp_head', [ $this, 'remove_no_js_class_from_html' ], PHP_INT_MIN );
		add_action( 'init', [ $this, 'disable_legacy_emoji_support' ] );
	}

	/**
	 * Register the shared runtime script.
	 *
	 * @return void
	 */
	public function register_shared_runtime() {
		/**
		 * The webpack runtime is ejected into own file (per Webpack config)
		 * so all other scripts must register it as a dependency.
		 */
		$webpack_runtime_asset = gdt_theme_meta()->asset_meta( 'build/webpack/runtime.js' );

		if ( $webpack_runtime_asset['path'] ) {
			wp_register_script(
				self::SCRIPT_HANDLE_WEBPACK_RUNTIME,
				$webpack_runtime_asset['url'],
				$webpack_runtime_asset['dependencies'],
				$webpack_runtime_asset['version'],
				true
			);

			$this->dependencies[] = self::SCRIPT_HANDLE_WEBPACK_RUNTIME;
		}
	}

	/**
	 * Enqueues the main theme script, and the CSS hot reload script during development.
	 *
	 * @throws \RuntimeException Throws if assets aren't built.
	 *
	 * @return void
	 */
	public function action_enqueue_scripts() {
		$main_js_asset = gdt_theme_meta()->asset_meta( 'build/js/main.js' );

		if ( ! $main_js_asset['path'] ) {
			throw new \RuntimeException( 'Built JavaScript assets not found. Please run `npm run build`' );
		}

		wp_enqueue_script(
			'gabriel-detassigny-main',
			$main_js_asset['url'],
			array_merge( $main_js_asset['dependencies'], $this->dependencies ),
			$main_js_asset['version'],
			true
		);

		wp_script_add_data( 'gabriel-detassigny-main', 'defer', true );

		$css_js_asset = gdt_theme_meta()->asset_meta( 'build/js/css.js' );

		// Register CSS hot reload only during development.
		if ( $css_js_asset['path'] ) {
			wp_enqueue_script(
				'gabriel-detassigny-css',
				$css_js_asset['url'],
				array_merge( $css_js_asset['dependencies'], $this->dependencies ),
				$css_js_asset['version'],
				true
			);
		}
	}

	/**
	 * Add custom attributes to core scripts.
	 *
	 * @return void
	 */
	public function configure_core_scripts() {
		wp_script_add_data( 'wp-polyfill', 'nomodule', true );
		wp_script_add_data( 'wp-polyfill', 'defer', true );
		wp_script_add_data( 'regenerator-runtime', 'nomodule', true );
		wp_script_add_data( 'regenerator-runtime', 'defer', true );
		wp_script_add_data( 'comment-reply', 'defer', true );
		wp_script_add_data( 'wp-embed', 'defer', true );
	}

	/**
	 * Remove the no-js class from the <html> tag if JS support is detected.
	 *
	 * Do it in a way that doesn't block rendering.
	 *
	 * @see https://www.phpied.com/asynchronous-inline-scripts-via-data-urls/
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/Data_URIs
	 * @return void
	 */
	public function remove_no_js_class_from_html() {
		$script_src = sprintf(
			'data:text/javascript,%s',
			rawurlencode( 'document.documentElement.classList.remove("no-js");' )
		);

		printf( '<script async src="%s"></script>', esc_url( $script_src, [ 'data' ] ) );
		echo PHP_EOL;
	}

	/**
	 * Removes legacy emoji support scripts & styles.
	 *
	 * @return void
	 */
	public function disable_legacy_emoji_support() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}
}
