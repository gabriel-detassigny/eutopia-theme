<?php
/**
 * Eutopia_Theme\Components\SVG class.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use enshrined\svgSanitize\Sanitizer;
use Eutopia_Theme\Helpers\Utils;

/**
 * Class for handling SVGs.
 *
 * It assumes that the any call to `render_svg` or `get_svg` will
 * add the markup to the output so the next call to the same SVG will
 * result in a reference render to the previous DOM ID.
 */
class SVG extends Component {

	/**
	 * Sanitizer instance.
	 *
	 * @var Sanitizer
	 */
	private Sanitizer $sanitizer;

	/**
	 * Paths to folders for SVG lookup.
	 *
	 * @var string[]
	 */
	private array $paths = [];

	/**
	 * List of resolved paths by relative request path.
	 *
	 * @var string[string]
	 */
	private array $resolved_paths = [];

	/**
	 * Keep track of rendered SVGs to return a reference to
	 * previously rendered DOM IDs.
	 *
	 * SVGs are indexed by their slugs. Stores the viewBox attribute
	 * or boolean `false` if the SVG is not found.
	 *
	 * @var string[]|false[]
	 */
	private array $rendered = [];

	/**
	 * Setup the SVG component.
	 *
	 * @param array $paths List of absolute paths to folders containing SVGs.
	 */
	public function __construct( array $paths = [] ) {
		// Setup the SVG sanitizer.
		$this->sanitizer = new Sanitizer();
		$this->sanitizer->removeXMLTag( true );
		$this->sanitizer->minify( true );

		if ( ! empty( $paths ) ) {
			foreach ( $paths as $path ) {
				$this->add_path( $path );
			}
		}
	}

	/**
	 * Add a path to the list of folders containing SVGs.
	 *
	 * @param string $path Absolute path to the folder containing SVGs.
	 */
	public function add_path( string $path ) {
		$this->paths[] = rtrim( $path, '/\\' ); // Remove trailing slashes.
	}

	/**
	 * Get the absolute path to an SVG file.
	 *
	 * @param string $relative_path Relative path to an SVG file.
	 *
	 * @return string|null
	 */
	private function get_resolved_path( $relative_path ): ?string {
		if ( isset( $this->resolved_paths[ $relative_path ] ) ) {
			return $this->resolved_paths[ $relative_path ] ?? null;
		}

		// Build the potential paths to check relative to the base paths.
		$potential_paths = array_map(
			function ( $base ) use ( $relative_path ) {
				return sprintf( '%s/%s', $base, $relative_path );
			},
			$this->paths
		);

		$potential_paths = array_filter( $potential_paths, 'file_exists' );

		$this->resolved_paths[ $relative_path ] = reset( $potential_paths );

		return $this->resolved_paths[ $relative_path ];
	}

	/**
	 * Render SVG from template parts.
	 *
	 * @param string $relative_path Path to SVG file relative to registered root paths.
	 * @param array  $attrs SVG attributes.
	 *
	 * @return void
	 */
	public function render_svg( string $relative_path, array $attrs = [] ): void {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG already sanitized.
		echo $this->get_svg( $relative_path, $attrs );
	}

	/**
	 * Get SVG from template parts.
	 *
	 * @param string $relative_path Path to SVG file relative to registered root paths.
	 * @param array  $attrs SVG attributes.
	 *
	 * @return string
	 */
	public function get_svg( string $relative_path, array $attrs = [] ): string {
		if ( empty( $relative_path ) || empty( $this->paths ) ) {
			return '';
		}

		// Ensure we can append to our base paths.
		$relative_path = ltrim( $relative_path, '/\\' );

		$svg_id = sprintf( 'svg-%s', sanitize_key( basename( $relative_path, '.svg' ) ) );

		// If there is a cache entry for the SVG, use it if it's defined or bail out if it's not.
		if ( isset( $this->rendered[ $svg_id ] ) ) {
			if ( false === $this->rendered[ $svg_id ] ) {
				return '';
			}

			$attrs = wp_parse_args(
				$attrs,
				[
					'class'   => '',
					'viewBox' => $this->rendered[ $svg_id ],
					'xmlns'   => 'http://www.w3.org/2000/svg',
				],
			);

			// Ensure the SVG ID is added to the class attribute.
			$attrs['class'] = empty( $attrs['class'] ) ? $svg_id : "$svg_id " . $attrs['class'];

			return sprintf(
				'<svg %1$s><use xlink:href="#%2$s"></use></svg>',
				Utils::build_html_attrs_string( $attrs ),
				esc_attr( $svg_id ),
			);
		}

		// Get the first resolved path.
		$path = $this->get_resolved_path( $relative_path );

		if ( ! empty( $path ) ) {
			return Utils::with_cache(
				$svg_id . md5( wp_json_encode( $attrs ) ),
				function () use ( $path, $svg_id, $attrs ) {
					// phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown, reason: we are fetching a local file.
					$svg = file_get_contents( $path );

					// Process the SVG to get the viewBox attribute and also set the ID and class to match the pattern.
					$processor = new \WP_HTML_Tag_Processor( trim( $svg ) );
					$processor->next_tag( 'svg' );

					// Set the cache entry to the viewBox attribute.
					$this->rendered[ $svg_id ] = $processor->get_attribute( 'viewBox' );

					$attrs['id']    = $svg_id;
					$attrs['class'] = empty( $attrs['class'] ) ? $svg_id : "$svg_id " . $attrs['class'];

					foreach ( $attrs as $attr => $value ) {
						$processor->set_attribute( $attr, $value );
					}

					return $this->sanitizer->sanitize( $processor->get_updated_html() );
				}
			);
		}

		// Mark this SVG as not resolvable.
		$this->rendered[ $svg_id ] = false;

		return '';
	}
}
