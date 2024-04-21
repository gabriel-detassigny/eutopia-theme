<?php
/**
 * Block_Types_Allowed class.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use WP_Block_Type_Registry;

/**
 * Class for disabling certain block types.
 */
class Block_Types_Allowed extends Component {


	/**
	 * List of allowed WP core block types.
	 *
	 * @var array<string>
	 */
	const ALLOWED_CORE_BLOCK_TYPES = [
		'core/audio'           => 'core/audio',
		'core/block'           => 'core/block',
		'core/button'          => 'core/button',
		'core/buttons'         => 'core/buttons',
		'core/columns'         => 'core/columns',
		'core/cover'           => 'core/cover',
		'core/embed'           => 'core/embed',
		'core/file'            => 'core/file',
		'core/gallery'         => 'core/gallery',
		'core/group'           => 'core/group',
		'core/heading'         => 'core/heading',
		'core/html'            => 'core/html',
		'core/image'           => 'core/image',
		'core/list'            => 'core/list',
		'core/list-item'       => 'core/list-item',
		'core/paragraph'       => 'core/paragraph',
		'core/pattern'         => 'core/pattern',
		'core/pullquote'       => 'core/pullquote',
		'core/quote'           => 'core/quote',
		'core/reusable-blocks' => 'core/reusable-blocks',
		'core/separator'       => 'core/separator',
		'core/video'           => 'core/video',
		'core/post-title'      => 'core/post-title',
		'core/legacy-widget'   => 'core/legacy-widget',
	];

	/**
	 * Register any needed hooks/filters.
	 */
	public function init(): void {
		if ( version_compare( $GLOBALS['wp_version'], '5.8-alpha-1', '<' ) ) {
			add_filter( 'allowed_block_types', [ $this, 'filter_maybe_disable_block_types' ] );
		} else {
			add_filter( 'allowed_block_types_all', [ $this, 'filter_maybe_disable_block_types' ] );
		}
	}

	/**
	 * Get all allowed core block types.
	 *
	 * @return array<string>
	 */
	public function get_allowed_core_block_types() {
		/**
		 * Filters the list of allowed core block types.
		 *
		 * @param array<string> $allowed_core_block_types List of allowed core block types.
		 */
		return apply_filters( 'eutopia_theme_allowed_core_block_types', self::ALLOWED_CORE_BLOCK_TYPES );
	}

	/**
	 * Filter the list of allowed blocks.
	 *
	 * @return array<string> Allowed blocks types.
	 */
	public function filter_maybe_disable_block_types() {
		$registered_block_types   = WP_Block_Type_Registry::get_instance()->get_all_registered();
		$allowed_core_block_types = $this->get_allowed_core_block_types();

		$allowed_block_types = [];
		foreach ( $registered_block_types as $block_type => $_ ) {
			// Disable all WP core blocks not in the allow-list.
			if ( false === str_contains( $block_type, 'core/' ) || isset( $allowed_core_block_types[ $block_type ] ) ) {
				$allowed_block_types[] = $block_type;
			}
		}

		/**
		 * Filters the list of allowed block types.
		 *
		 * @param array<string> $allowed_block_types List of allowed block types.
		 */
		return apply_filters( 'eutopia_theme_allowed_block_types', $allowed_block_types );
	}
}
