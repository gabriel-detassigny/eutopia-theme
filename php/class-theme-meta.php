<?php
/**
 * Class Theme_Meta
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme;

/**
 * This class is responsible for holding theme meta information, such as version, path, etc.
 */
class Theme_Meta {

	/**
	 * Seperator used for URLs.
	 *
	 * @var string
	 */
	protected const URL_SEPARATOR = '/';

	/**
	 * Absolute path to the theme root directory.
	 *
	 * @var string
	 */
	private string $path;

	/**
	 * URL to the theme root directory.
	 *
	 * @var string
	 */
	private string $url;

	/**
	 * Theme_Meta constructor.
	 *
	 * @param string|null $version The theme version.
	 * @param string      $path    The theme path.
	 */
	public function __construct( private ?string $version, string $path ) {
		$this->path = $this->normalize_path( $path );

		// Account for WP_CONTENT_DIR being a symbolic link (symlink).
		$this->url = content_url( $this->path_to_root( realpath( WP_CONTENT_DIR ) ) );
	}

	/**
	 * Get the theme version.
	 *
	 * @return string
	 */
	public function version() {
		return $this->version;
	}

	/**
	 * Get path with normalized directory seperators.
	 *
	 * @param string $path Path.
	 *
	 * @return string
	 */
	protected function normalize_path( $path ) {
		return preg_replace( '/[\\/]+/', DIRECTORY_SEPARATOR, $path );
	}

	/**
	 * Get the theme path.
	 *
	 * @param string ...$append_to_path Path to append to the end of the theme path.
	 *
	 * @return string
	 */
	public function path( ...$append_to_path ) {
		$path = [
			$this->path,
		];

		// Remove any empty path fragments.
		$append_to_path = array_filter( $append_to_path, 'strlen' );

		if ( count( $append_to_path ) > 0 ) {
			$path = array_merge( $path, $append_to_path );
		}

		return implode( DIRECTORY_SEPARATOR, $path );
	}

	/**
	 * Resolve relative path back to the root directory.
	 *
	 * @param string $path Absolute or relative path to a file or directory.
	 *
	 * @return string|null
	 */
	public function path_to_root( $path ) {
		$path_from_root = $this->path_from_root( $path );

		// Resolve paths after the root directory.
		if ( is_string( $path_from_root ) && strlen( $path_from_root ) ) {
			// Trim any trailing slashes before counting directories.
			$nest_level = substr_count( trim( $path_from_root, DIRECTORY_SEPARATOR ), DIRECTORY_SEPARATOR ) + 1;

			return implode( DIRECTORY_SEPARATOR, array_fill( 0, $nest_level, '..' ) );
		}

		$path = $this->normalize_path( $path );

		// Account for paths before the root directory.
		if ( str_starts_with( $this->path, $path ) ) {
			return ltrim( str_replace( $path, '', $this->path ), DIRECTORY_SEPARATOR );
		}

		return null;
	}

	/**
	 * Resolve absolute path relative to the theme directory.
	 *
	 * @param string $path Absolute path to an asset in the root directory.
	 *
	 * @return string|null
	 */
	public function path_from_root( $path ) {
		$path = $this->normalize_path( $path );

		if ( str_starts_with( $path, $this->path ) ) {
			return ltrim( str_replace( $this->path, '', $path ), DIRECTORY_SEPARATOR );
		}

		return null;
	}

	/**
	 * Retrieve the URL of the current path.
	 *
	 * @param string ...$append_to_path Path to append to the end of the theme path.
	 *
	 * @return string
	 */
	public function url( ...$append_to_path ) {
		$url = [
			$this->url,
		];

		// Remove any empty path fragments.
		$append_to_path = array_filter( $append_to_path, 'strlen' );

		if ( count( $append_to_path ) > 0 ) {
			$url = array_merge( $url, $append_to_path );
		}

		return implode( self::URL_SEPARATOR, $url );
	}

	/**
	 * Get asset meta.
	 *
	 * @param string $path Path to the asset relative to theme root.
	 *
	 * @return array
	 */
	public function asset_meta( $path ) {
		$meta = [
			'dependencies' => [],
			'version'      => $this->version(),
			'url'          => null,
			'path'         => null,
		];

		$asset_php_path = $this->path(
			dirname( $path ),
			sprintf( '%s.asset.php', pathinfo( $path, PATHINFO_FILENAME ) )
		);

		if ( is_readable( $asset_php_path ) ) {
			$asset_php = require $asset_php_path;

			if ( isset( $asset_php['version'] ) ) {
				$meta = array_merge( $meta, $asset_php );
			}
		}

		$path_absolute = $this->path( $path );

		if ( is_readable( $path_absolute ) ) {
			$meta['path'] = $path_absolute;
			$meta['url']  = $this->url( $path );
		}

		return $meta;
	}
}
