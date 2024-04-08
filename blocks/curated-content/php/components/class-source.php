<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Source  class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components;

/**
 * Class for defining abstract source.
 */
abstract class Source {

	/**
	 * Used ID's
	 *
	 * @var int[]
	 */
	public $used = [];

	/**
	 * Holds the current post in order to exclude it.
	 *
	 * @var \WP_Post
	 */
	protected $current_post;

	/**
	 * Holds the Curated Content block.
	 *
	 * @var Block
	 */
	protected $block;

	/**
	 * The constructor.
	 *
	 * @param Block $block The block object.
	 */
	public function __construct( $block ) {
		$this->block = $block;
	}

	/**
	 * Get the items.
	 *
	 * @param array $attributes The attributes for the source.
	 *
	 * @return \WP_Post[]
	 */
	protected function get_items( array $attributes ) {
		$query = new \WP_Query( $this->get_query_arguments( $attributes ) );

		return $query->get_posts();
	}

	/**
	 * Get the query arguments.
	 *
	 * @param array $attributes The attributes.
	 *
	 * @return array
	 */
	protected function get_query_arguments( array $attributes ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		return [];
	}

	/**
	 * Get the panels.
	 *
	 * @return array
	 */
	public function get_panels() {
		return [];
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {
		return [];
	}

	/**
	 * Get the source name.
	 *
	 * @return string
	 */
	public function get_name() {
		$parts = explode( '\\', get_called_class() );

		return array_pop( $parts );
	}

	/**
	 * Get the source help.
	 *
	 * @return string
	 */
	public function get_help() {
		return '';
	}

	/**
	 * Get the number of items to render.
	 *
	 * @param array $attributes The attributes.
	 *
	 * @return int
	 */
	protected function get_number_of_items( array $attributes ) {
		// Make sure the `posts_per_page` is key is set with a reasonable fallback.
		// If it is already set, the original value will be used.
		$attributes = wp_parse_args(
			$attributes,
			[
				'posts_per_page' => 30,
			]
		);

		return intval( $attributes['posts_per_page'] );
	}

	/**
	 * Prepare attributes for use.
	 *
	 * @param array $attributes The attributes to prepare.
	 *
	 * @return array
	 */
	protected function prep_attributes( $attributes ) {
		// Get Excludes for editor.
		$exclude = filter_input( INPUT_GET, 'exclude', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY );
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $exclude ) ) {
			$this->block->used = array_merge( $this->block->used, array_map( 'intval', $exclude ) );
		}

		$attributes['posts_per_page'] += count( $this->block->used ); // Increase the count to enable removal.

		return $attributes;
	}

	/**
	 * Render a source.
	 *
	 * @param array $attributes The source attributes.
	 *
	 * @return \WP_Post[]
	 */
	public function items( array $attributes ) {
		// Setup Item Limits this uses the unmodified count, so it must run first.
		$item_limit = $this->get_number_of_items( $attributes );
		// Prep attributes.
		$attributes = $this->prep_attributes( $attributes );
		// Get Items.
		$items = $this->get_items( $attributes );

		return $this->prep_items( $items, $item_limit );
	}

	/**
	 * Set up the source.
	 */
	public function setup() {
		global $post;
		$this->current_post = $post;

		// Exclude current post.
		if ( ! empty( $post ) ) {
			$this->block->used[] = $post->ID;
			$this->block->used   = array_unique( $this->block->used );
		}
	}

	/**
	 * Clean up the source after running.
	 */
	public function cleanup() {
		global $post;
		$post = $this->current_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	/**
	 * Prepare the items to use and limit to item_limit.
	 *
	 * @param array $items      The items to prepare.
	 * @param int   $item_limit The limit of items.
	 *
	 * @return array
	 */
	protected function prep_items( $items, $item_limit ) {
		$valid_items = [];
		$items       = array_filter( $items );
		foreach ( $items as $item ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			if ( ! in_array( $item->ID, $this->block->used, true ) && count( $valid_items ) < $item_limit ) {
				$valid_items[]       = $item;
				$this->block->used[] = $item->ID;
			}
		}

		return $valid_items;
	}

	/**
	 * Get the source type. I.E wp_post, wp_user, array, etc.
	 *
	 * @return string
	 */
	public function get_source_type() {
		return 'wp_post';
	}
}
