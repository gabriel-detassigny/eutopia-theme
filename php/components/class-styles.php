<?php
/**
 * Eutopia_Theme\Components\Styles class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use WP_Block;
use Eutopia_Theme\Helpers\Utils;

/**
 * Class for managing stylesheets.
 *
 * @phpstan-type CSSFileEntry array{file: string, global: bool, preload: string, media: string, preload_callback: callable}
 */
class Styles extends Component {
	/**
	 * Associative array of CSS files, as $handle => $data pairs.
	 * $data must be an array with keys 'file' (file path relative to 'assets/css' directory), and optionally 'global'
	 * (whether the file should immediately be enqueued instead of just being registered) and 'preload_callback'
	 * (callback function determining whether the file should be preloaded for the current request).
	 *
	 * Do not access this property directly, instead use the `get_css_files()` method.
	 *
	 * @var array<string<CSSFileEntry|string>>
	 */
	protected $css_files; // @phpstan-ignore-line See https://github.com/phpstan/phpstan/issues/7825

	/**
	 * Associative array of Google Fonts to load, as $font_name => $font_variants pairs.
	 *
	 * Do not access this property directly, instead use the `get_google_fonts()` method.
	 *
	 * @var array<string, array<string>|string>
	 */
	protected $google_fonts;

	/**
	 * Holds the original core block render callback.
	 *
	 * @var non-empty-array<callable-string,non-empty-array<int|string>|string>
	 */
	protected $callbacks;

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_styles' ] );
		add_action( 'wp_head', [ $this, 'action_preload_styles' ], 5 );
		add_action( 'wp_head', [ $this, 'action_preload_fonts' ], 5 );
		add_filter( 'wp_resource_hints', [ $this, 'filter_resource_hints' ], 10, 2 );
		add_filter( 'style_loader_tag', [ $this, 'defer_stylesheet' ], 10, 4 );
		add_action( 'wp_body_open', [ $this, 'init_inline_styles' ] );
		add_action( 'after_setup_theme', [ $this, 'add_editor_style_support' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'action_enqueue_editor_styles' ] );
		add_action( 'after_setup_theme', [ $this, 'action_remove_duotone' ] );
		// Ensure not used via API.
		if ( ! Utils::is_api_call() ) {
			add_filter( 'block_type_metadata_settings', [ $this, 'filter_block_type_metadata' ], 10, 2 );
		}
	}

	/**
	 * Override core block render that enqueue support styles within the render. i.e. core/gallery.
	 *
	 * @param array<array<callable-string>> $settings The block settings.
	 * @param array<callable-string>        $metadata The original block metadata.
	 *
	 * @return array<array<'render_core_block'|Styles|callable-string>>
	 */
	public function filter_block_type_metadata( $settings, $metadata ) {
		if ( str_contains( $metadata['name'], 'core/' ) && ! empty( $settings['render_callback'] ) ) {
			$this->callbacks[ $metadata['name'] ] = $settings['render_callback'];
			$settings['render_callback']          = [ $this, 'render_core_block' ];
		}

		return $settings;
	}

	/**
	 * Render the block using the original call back and check the footer for changed styles.
	 *
	 * @param array<string> $attributes The block attributes.
	 * @param string        $content    The block content.
	 * @param WP_Block      $block      The block object.
	 *
	 * @return mixed
	 */
	public function render_core_block( $attributes, $content, $block ) {
		global $wp_filter;
		// Duplicate the wp_footer hook.
		$temp_holder = $wp_filter['wp_footer'];

		// Remove the old wp_footer.
		unset( $wp_filter['wp_footer'] );

		if ( ! is_callable( $this->callbacks[ $block->name ] ) ) {
			return $content;
		}

		// Render the content with the original callback.
		$content = call_user_func( $this->callbacks[ $block->name ], $attributes, $content, $block );

		// If the original callback enqueued an action to wp_footer, it will now exist.
		if ( ! empty( $wp_filter['wp_footer'] ) ) {

			// Do the action to output the styles/scripts.
			do_action( 'wp_footer' );

			// Reset it again.
			unset( $wp_filter['wp_footer'] );
		}

		// Replace the previous wp_footer hooks.
		foreach ( $temp_holder->callbacks as $priority => $hooks ) {
			foreach ( $hooks as $hook ) {
				add_action( 'wp_footer', $hook['function'], $priority, $hook['accepted_args'] );
			}
		}

		return $content;
	}

	/**
	 * Init the style capture and handler.
	 *
	 * @return void
	 */
	public function init_inline_styles() {
		// Bail if we're not using default styles.
		if ( ! current_theme_supports( 'wp-block-styles' ) ) {
			return;
		}

		// Remove the wp_render_layout_support_flag from render_block, because we want to control it.
		remove_filter( 'render_block', 'wp_render_layout_support_flag' );
		// Replace with our own handler.
		add_filter( 'render_block', [ $this, 'layout_render' ], 10, 2 );

		// Ensure globals are here at the top of the page.
		wp_enqueue_global_styles();
		$this->render_inline_styles();
	}

	/**
	 * Render inline styles that have been enqueued since the last run.
	 *
	 * @return void
	 */
	public function render_inline_styles() {
		$styles = wp_styles();

		// Send the current queue to all_deps. This will populate the `to_do` for new items.
		$styles->all_deps( $styles->queue );
		if ( ! empty( $styles->to_do ) ) {
			// Copy the queue to a temp var.
			$temp = $styles->queue;

			// Set the queue as the to_do list.
			array_map( [ $styles, 'dequeue' ], $temp );
			array_map( [ $styles, 'enqueue' ], $styles->to_do );

			/*
			 * This will use the current queue and add inline scripts for each that fit.
			 * calling it repeatedly would append the same inline CSS on each run, so we only give it the new items.
			 */
			wp_maybe_inline_styles();

			// Replace the original queue.
			array_map( [ $styles, 'dequeue' ], $styles->to_do );
			array_map( [ $styles, 'enqueue' ], $temp );

			// Do items that are new.
			$styles->do_items();
		}
	}

	/**
	 * Handler the render styles.
	 *
	 * @param string        $block_content Rendered block content.
	 * @param array<string> $block         Block object.
	 *
	 * @return string Filtered block content.
	 */
	public function layout_render( $block_content, $block ) {
		static $counter = 0;

		// Call the layout support.
		$block_content = wp_render_layout_support_flag( $block_content, $block );
		$store         = \WP_Style_Engine::get_store( 'block-supports' );
		$rules         = $store->get_all_rules();

		// Render inline and remove rule from footer.
		if ( ! empty( $rules ) ) {
			$styles = wp_style_engine_get_stylesheet_from_context( 'block-supports' );
			if ( ! empty( $styles ) ) {
				++$counter;
				$style_tag_id = 'block-support-' . $counter;
				wp_register_style( $style_tag_id, false, [], true, 'all' );
				wp_add_inline_style( $style_tag_id, $styles );
				wp_enqueue_style( $style_tag_id );
			}
			array_map( [ $store, 'remove_rule' ], array_keys( $rules ) );
		}

		// Render any new styles enqueued, will most likely be this block we're rendering.
		$this->render_inline_styles();

		return $block_content;
	}

	/**
	 * Registers or enqueues stylesheets.
	 *
	 * Stylesheets that are global are enqueued. All other stylesheets are only registered, to be enqueued later.
	 *
	 * @throws \RuntimeException Throws if assets aren't built.
	 *
	 * @return void
	 */
	public function action_enqueue_styles() {
		$dependencies = [];

		// Enqueue Google Fonts.
		$google_fonts_url = $this->get_google_fonts_url();

		if ( ! empty( $google_fonts_url ) ) {
			wp_register_style( 'eutopia-theme-fonts|defer', $google_fonts_url, [], null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			$dependencies[] = 'eutopia-theme-fonts|defer';
		}

		$preloading_styles_enabled = $this->preloading_styles_enabled();

		foreach ( $this->get_css_files() as $handle => $data ) {
			$asset_meta = gdt_theme_meta()->asset_meta( sprintf( 'build/css/%s', $data['file'] ) );

			if ( empty( $asset_meta['path'] ) ) {
				throw new \RuntimeException( 'Built JavaScript assets not found. Please run `npm run build`' );
			}

			wp_register_style(
				$handle,
				$asset_meta['url'],
				$dependencies,
				$asset_meta['version'],
				$data['media']
			);

			/*
			 * Enqueue global stylesheets immediately and register the other ones for later use
			 * (unless preloading stylesheets is disabled, in which case stylesheets should be immediately
			 * enqueued based on whether they are necessary for the page content).
			 */
			if (
				$data['global']
				|| (
					! $preloading_styles_enabled && is_callable( $data['preload_callback'] )
					&& call_user_func( $data['preload_callback'] )
				)
			) {
				wp_enqueue_style( $handle );
			}

			// Inline all small files.
			wp_style_add_data( $handle, 'path', $asset_meta['path'] );

			// Enqueue development dependencies, if present.
			$runtime_js_file = sprintf( 'build/css/%s.js', pathinfo( $data['file'], PATHINFO_FILENAME ) );

			if ( is_readable( gdt_theme_meta()->path( $runtime_js_file ) ) ) {
				wp_enqueue_script(
					sprintf( '%s-dev', $handle ),
					gdt_theme_meta()->url( $runtime_js_file ),
					[ Scripts::SCRIPT_HANDLE_WEBPACK_RUNTIME ],
					$asset_meta['version'], // Inherit the version from the CSS file.
					true
				);
			}
		}
	}

	/**
	 * Preloads in-body stylesheets depending on what templates are being used.
	 *
	 * Only stylesheets that have a 'preload_callback' provided will be considered. If that callback evaluates to true
	 * for the current request, the stylesheet will be preloaded.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Preloading_content
	 * @return void
	 */
	public function action_preload_styles() {

		// If preloading styles is disabled, return early.
		if ( ! $this->preloading_styles_enabled() ) {
			return;
		}

		$wp_styles = wp_styles();

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {

			// Skip if stylesheet not registered.
			if ( ! isset( $wp_styles->registered[ $handle ] ) ) {
				continue;
			}

			// Skip if no preload callback provided.
			if ( ! is_callable( $data['preload_callback'] ) ) {
				continue;
			}

			// Skip if preloading is not necessary for this request.
			if ( ! call_user_func( $data['preload_callback'] ) ) {
				continue;
			}

			$preload_uri = $wp_styles->registered[ $handle ]->src . '?ver=' . $wp_styles->registered[ $handle ]->ver;

			echo '<link rel="preload" id="' . esc_attr( $handle ) . '-preload" href="' . esc_url( $preload_uri ) . '" as="style">';
			echo "\n";
		}
	}

	/**
	 * Preloads critical font files.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Preloading_content
	 * @return void
	 */
	public function action_preload_fonts() {

		// Supports remote URIs & local paths.
		// Example: 'assets/font/open-sans-v34-latin-regular.woff2'.
		$critical_font_paths = [];

		if ( count( $critical_font_paths ) !== false ) {
			foreach ( $critical_font_paths as $critical_font_path ) {
				$preload_uri = $critical_font_path;

				// Support for local paths.
				$host = wp_parse_url( $critical_font_path, PHP_URL_HOST );
				if ( null === $host ) {
					$preload_uri = trailingslashit( get_stylesheet_directory_uri() ) . $critical_font_path;
				}

				echo '<link rel="preload" href="' . esc_url( $preload_uri ) . '" as="font"  type="font/woff2" crossorigin>';
			}
		}
	}

	/**
	 * Adds preconnect resource hint for Google Fonts.
	 *
	 * @param array<array<string, string>> $urls          URLs to print for resource hints.
	 * @param string                       $relation_type The relation type the URLs are printed.
	 *
	 * @return array<array<int|string, string>> URLs to print for resource hints.
	 */
	public function filter_resource_hints( array $urls, $relation_type ) {
		if ( 'preconnect' === $relation_type && wp_style_is( 'eutopia-theme-fonts|defer', 'queue' ) ) {
			$urls[] = [
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			];
		}

		return $urls;
	}

	/**
	 * Prints stylesheet link tags directly.
	 *
	 * This should be used for stylesheets that aren't global and thus should only be loaded if the HTML markup
	 * they are responsible for is actually present. Template parts should use this method when the related markup
	 * requires a specific stylesheet to be loaded. If preloading stylesheets is disabled, this method will not do
	 * anything.
	 *
	 * If the `<link>` tag for a given stylesheet has already been printed, it will be skipped.
	 *
	 * @param string ...$handles One or more stylesheet handles.
	 * @return void
	 */
	public function print_styles( ...$handles ) {
		// If preloading styles is disabled (and thus they have already been enqueued), return early.
		if ( ! $this->preloading_styles_enabled() ) {
			return;
		}

		$css_files = $this->get_css_files();
		$handles   = array_filter(
			$handles,
			function ( $handle ) use ( $css_files ) {
				$is_valid = isset( $css_files[ $handle ] ) && ! $css_files[ $handle ]['global'];
				if ( ! $is_valid ) {
					_doing_it_wrong(
						__CLASS__ . '::print_styles()',
						/* translators: %s: stylesheet handle */
						esc_html( sprintf( __( 'Invalid theme stylesheet handle: %s', 'eutopia-theme' ), $handle ) ),
						'Eutopia Theme 1.0'
					);
				}

				return $is_valid;
			}
		);

		if ( empty( $handles ) ) {
			return;
		}

		wp_print_styles( $handles );
	}

	/**
	 * Determines whether to preload stylesheets and inject their link tags directly within the page content.
	 *
	 * Using this technique generally improves performance, however may not be preferred under certain circumstances.
	 * By default, this method returns true. The {@see 'eutopia_theme_preloading_styles_enabled'} filter can be
	 * used to tweak the return value.
	 *
	 * @return bool True if preloading stylesheets and injecting them is enabled, false otherwise.
	 */
	protected function preloading_styles_enabled() {
		/**
		 * Filters whether to preload stylesheets and inject their link tags within the page content.
		 *
		 * @param bool $preloading_styles_enabled Whether preloading stylesheets and injecting them is enabled.
		 */
		return (bool) apply_filters( 'eutopia_theme_preloading_styles_enabled', true );
	}

	/**
	 * Gets all CSS files.
	 *
	 * @return array<string<CSSFileEntry|string>> Associative array of $handle => $data pairs.
	 */
	public function get_css_files() {
		if ( is_array( $this->css_files ) && ! empty( $this->css_files ) ) {
			return $this->css_files;
		}

		// Keep in sync with js/css.js to enable hot-reload.
		$css_files = [
			'eutopia-theme-global'            => [
				'file'             => 'style.css',
				'global'           => true,
				'preload_callback' => '__return_true',
			],
			'eutopia-theme-global-lazy|defer' => [
				'file'   => 'lazy.css',
				'global' => true,
			],
		];

		/**
		 * Filters default CSS files.
		 *
		 * @param array<string<CSSFileEntry|string>> $css_files Associative array of CSS files, as $handle => $data pairs.
		 *                         $data must be an array with keys 'file' (file path relative to 'css'
		 *                         directory), and optionally 'global' (whether the file should immediately be
		 *                         enqueued instead of just being registered) and 'preload_callback' (callback)
		 *                         function determining whether the file should be preloaded for the current request).
		 */
		$css_files = apply_filters( 'eutopia_theme_css_files', $css_files );

		$this->css_files = [];
		foreach ( $css_files as $handle => $data ) {
			if ( is_string( $data ) ) {
				$data = [ 'file' => $data ];
			}

			if ( ! is_array( $data ) ) {
				continue;
			}

			if ( empty( $data['file'] ) ) {
				continue;
			}

			$this->css_files[ $handle ] = array_merge(
				[
					'global'           => false,
					'preload_callback' => null,
					'media'            => 'all',
				],
				$data
			);
		}

		return $this->css_files;
	}

	/**
	 * Returns Google Fonts used in theme.
	 *
	 * @return array<string, array<string>|string> Associative array of $font_name => $font_variants pairs.
	 */
	protected function get_google_fonts() {
		if ( is_array( $this->google_fonts ) && ! empty( $this->google_fonts ) ) {
			return $this->google_fonts;
		}

		$google_fonts = [
			'Roboto'       => [ '400', '400i', '700', '700i' ],
			'Merriweather' => [ '400', '400i', '700', '700i' ],
		];

		/**
		 * Filters default Google Fonts.
		 *
		 * @param array<string, array<string>|string> $google_fonts Associative array of $font_name => $font_variants pairs.
		 */
		$this->google_fonts = apply_filters( 'eutopia_theme_google_fonts', $google_fonts );

		return $this->google_fonts;
	}

	/**
	 * Returns the Google Fonts URL to use for enqueuing Google Fonts CSS.
	 *
	 * Uses `latin` subset by default. To use other subsets, add a `subset` key to $query_args and the desired value.
	 *
	 * @return string Google Fonts URL, or empty string if no Google Fonts should be used.
	 */
	protected function get_google_fonts_url() {
		$google_fonts = $this->get_google_fonts();

		if ( empty( $google_fonts ) ) {
			return '';
		}

		$font_families = [];

		foreach ( $google_fonts as $font_name => $font_variants ) {
			if ( ! empty( $font_variants ) ) {
				if ( is_string( $font_variants ) ) {
					$font_variants = explode( ',', str_replace( ' ', '', $font_variants ) );
				}

				$font_families[] = $font_name . ':' . implode( ',', $font_variants );
				continue;
			}

			$font_families[] = $font_name;
		}

		$query_args = [
			'family'  => implode( '|', $font_families ),
			'display' => 'swap',
		];

		return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	/**
	 * Defer stylesheets loading.
	 *
	 * @param string $tag    Entire <link> tag.
	 * @param string $handle The link registration handle.
	 * @param string $href   The link's href attribute.
	 * @param string $media  The link's media attribute.
	 *
	 * @return string Updated link tag.
	 */
	public function defer_stylesheet( $tag, $handle, $href, $media ) {
		$should_be_deferred = true === str_contains( $handle, '|defer' );

		if ( $should_be_deferred && ( false === strpos( $tag, "media='print'" ) ) ) {
			$new_tag  = '<noscript>' . str_replace( " id='", " id='fallback-", $tag ) . '</noscript>';
			$new_tag .= str_replace( " media='{$media}'", " media='print' onload='this.media=\"all\"; this.onload=null;'", $tag );

			return $new_tag;
		}

		return $tag;
	}

	/**
	 * Enqueues editor stylesheet.
	 *
	 * This function assumes there will be only one CSS file
	 * enqueued for the editor.
	 */
	public function add_editor_style_support() {
		add_theme_support( 'editor-styles' );
		add_editor_style( 'build/css/editor.css' );
	}

	/**
	 * Enqueues block editor assets.
	 *
	 * Enqueue :root variable defitions as a seperate file because
	 * add_editor_style() breaks those definitions by adding a wrapper
	 * to all selectors.
	 *
	 * @return void
	 */
	public function action_enqueue_editor_styles() {
		$asset = gdt_theme_meta()->asset_meta( 'build/css/editor-variables.css' );

		if ( empty( $asset['path'] ) ) {
			return;
		}

		wp_enqueue_style(
			'gabriel-detassigny-editor-variables-style',
			$asset['url'],
			[],
			$asset['version'],
			'all'
		);
	}

	/**
	 * Removes core duotone SVGs.
	 *
	 * This function disables Gutenberg's Duotone SVG inlining.
	 *
	 * @return void
	 */
	public function action_remove_duotone() {
		remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
		remove_action( 'in_admin_header', 'wp_global_styles_render_svg_filters' );
	}
}
