<?php
/**
 * Eutopia_Theme\Components\Block_Patterns class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Class for implementing block patterns.
 *
 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/
 */
class Block_Patterns extends Component {

	/**
	 * Collection of custom block pattern category meta.
	 *
	 * @var array<string,array{label?: string}>>
	 */
	protected $categories = [];

	/**
	 * Bootstrap the class.
	 *
	 * @return void
	 */
	public function __construct() {
		/**
		 * Set the default pattern categories enabled by this theme.
		 *
		 * @see https://developer.wordpress.org/reference/classes/wp_block_pattern_categories_registry/register/
		 */
		$this->categories = [
			'gdt' => [
				'label' => __( 'Gabriel de Tassigny', 'eutopia-theme' ),
			],
		];

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$this->categories['testing'] = [
				'label' => __( 'Testing', 'eutopia-theme' ),
			];
		}
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'register_categories' ], 9 );

		/**
		 * Disable fetching remote patterns.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/should_load_remote_block_patterns/
		 */
		add_filter( 'should_load_remote_block_patterns', '__return_false' );
	}

	/**
	 * Registers block patterns categories.
	 *
	 * @return void
	 */
	public function register_categories() {
		/**
		 * Filter registering the block pattern categories.
		 *
		 * @param array<string<array{label?: string}>> $categories Categories.
		 */
		$categories = apply_filters( 'eutopia_theme_block_pattern_categories', $this->categories );

		if ( is_array( $categories ) ) {
			foreach ( $categories as $name => $properties ) {
				register_block_pattern_category( $name, $properties );
			}
		}
	}
}
