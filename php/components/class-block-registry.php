<?php
/**
 * Eutopia_Theme\Components\Block_Registry class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Components;

use Eutopia_Theme\Helpers\Utils;

/**
 * Class for registering custom blocks from the `blocks/` directory.
 *
 * @link https://developer.wordpress.org/block-editor/getting-started/fundamentals/registration-of-a-block/
 */
class Block_Registry extends Component {


	/**
	 * Block directories with this prefixed are registered only during development.
	 *
	 * @var string
	 */
	const TEMPLATE_DIRECTORY_PREFIX = '_';

	/**
	 * Block.json asset path keys that we'll rewrite to point to build directory.
	 *
	 * @var array
	 */
	const ASSET_PATH_KEYS = [
		'script',
		'viewScript',
		'editorScript',
		'style',
		'editorStyle',
	];

	/**
	 * Block source directory relative to the theme root.
	 *
	 * We register the build directory with WP and rewrite any
	 * render template paths back to the source directory since
	 * wp-scripts doesn't move any other PHP files to the
	 * build directory.
	 *
	 * @var string
	 */
	const SOURCE_DIRECTORY = 'blocks';

	/**
	 * Block distribution directory relative to the theme root.
	 *
	 * Contains block.json and all the built JS and CSS. All PHP
	 * is loaded from the source directory instead.
	 *
	 * @var string
	 */
	const BUILD_DIRECTORY = 'build/blocks';

	/**
	 * Register any needed hooks/filters.
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'action_register_blocks' ] );
		add_filter( 'block_type_metadata', [ $this, 'maybe_rewrite_block_asset_paths' ] );
		add_filter( 'block_categories_all', [ $this, 'action_add_block_category' ], 10 );
	}

	/**
	 * Register all blocks in the source directory to account
	 * for wp-scripts not moving all PHP scripts during the build.
	 *
	 * All block asset paths are rewriten to point to the build
	 * directory dynamically.
	 *
	 * @return void
	 *
	 * @throws \Exception Error when no blocks are found.
	 */
	public function action_register_blocks() {
		// Lookup by block.json since we have other non-block directories in the same folder.
		$block_json_paths = glob( gdt_theme_meta()->path( self::SOURCE_DIRECTORY, '*', 'block.json' ) );

		if ( ! is_array( $block_json_paths ) ) {
			throw new \Exception( 'Failed to find any blocks.' );
		}

		$block_directories = array_map( 'dirname', $block_json_paths );

		// Register block templates only during development.
		if ( ! Utils::is_debug() ) {
			$block_directories = array_filter(
				$block_directories,
				function ( $block_directory ) {
					return ! str_starts_with( basename( $block_directory ), self::TEMPLATE_DIRECTORY_PREFIX );
				}
			);
		}

		foreach ( $block_directories as $directory ) {
			// Allow blocks to run any custom logic before registration (to allow hooking into the registration).
			$block_init_path = gdt_theme_meta()->path( 'blocks', basename( $directory ), 'init.php' );
			if ( is_readable( $block_init_path ) ) {
				require_once $block_init_path;
			}

			// Register the block.
			$block = register_block_type_from_metadata( $directory );

			// Allow blocks to run any custom logic during registration.
			$block_functions_path = sprintf( '%s/functions.php', $directory );

			if ( $block instanceof \WP_Block_Type && is_readable( $block_functions_path ) ) {
				require_once $block_functions_path;
			}
		}
	}

	/**
	 * Rewrite PHP template path if it's specified.
	 *
	 * The wp-scripts doesn't move all PHP files to the build directory
	 * during the build like it does with CSS and JS files so we keep
	 * all PHP assets under the original block source directory.
	 *
	 * @param array $metadata Registered block metadata.
	 *
	 * @return array Modified metadata.
	 */
	public function maybe_rewrite_block_asset_paths( array $metadata ): array {
		if ( ! empty( $metadata['file'] ) && $this->is_block_in_theme( $metadata['file'] ) ) {
			$block_path = dirname( $metadata['file'] );

			foreach ( self::ASSET_PATH_KEYS as $asset_type ) {
				if ( ! empty( $metadata[ $asset_type ] ) ) {
					$metadata[ $asset_type ] = $this->rewrite_assets_paths( $metadata[ $asset_type ], $block_path );
				}
			}
		}

		return $metadata;
	}

	/**
	 * If the full path of a block asset belongs to a block in this theme.
	 *
	 * @param string $block_path Absolute path to any block asset.
	 *
	 * @return boolean
	 */
	public function is_block_in_theme( $block_path ) {
		return ! empty( gdt_theme_meta()->path_from_root( $block_path ) );
	}

	/**
	 * Rewrite assets paths to point to the built assets.
	 *
	 * @param string|string[] $assets     Single or array of asset paths or handles.
	 * @param string          $block_path Path to block directory.
	 *
	 * @return string[] Rewritten assets paths.
	 */
	protected function rewrite_assets_paths( $assets, string $block_path ): array {
		if ( is_string( $assets ) ) {
			$assets = [ $assets ];
		}

		// Resolve block path relative to the theme root directory.
		$path_to_root = gdt_theme_meta()->path_to_root( $block_path );

		if ( ! $path_to_root ) {
			return $assets;
		}

		foreach ( $assets as $key => $asset ) {
			$filename = remove_block_asset_path_prefix( $asset );

			// Rewrite only paths (not handle IDs).
			if ( $filename !== $asset ) {
				$assets[ $key ] = sprintf(
					'file:%s/%s/%s/%s',
					$path_to_root,
					self::BUILD_DIRECTORY,
					basename( $block_path ),
					$filename
				);
			}
		}

		return $assets;
	}

	/**
	 * Adding a new (custom) block category.
	 *
	 * @param array $block_categories Array of categories for block types.
	 *
	 * @return array List of available block categories.
	 */
	public function action_add_block_category( array $block_categories ): array {
		return array_merge(
			$block_categories,
			[
				[
					'slug'  => 'gabriel-detassigny-blocks',
					'title' => __( 'Eutopia Blocks', 'eutopia-theme' ),
				],
			]
		);
	}

	/**
	 * Get block object.
	 *
	 * @param string $block_folder Block folder.
	 *
	 * @return object
	 */
	public static function get_block_object( $block_folder ): object {
		$block_file = rtrim( $block_folder, '/\\' ) . '/block.json';

		if ( ! file_exists( $block_file ) ) {
			return (object) [];
		}

		// phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown
		return json_decode( file_get_contents( $block_file ) );
	}
}
