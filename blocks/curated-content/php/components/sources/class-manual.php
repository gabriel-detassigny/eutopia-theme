<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Source\Dynamic  class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components\Sources;

/**
 * Class for defining abstract inspector.
 */
class Manual extends Dynamic {

	/**
	 * Get the source help.
	 *
	 * @return string
	 */
	public function get_help() {
		return __( 'Manually select items to display.', 'eutopia-theme' );
	}

	/**
	 * Get the number of items to render.
	 *
	 * @param array $attributes The attributes.
	 *
	 * @return int
	 */
	protected function get_posts_per_page( array $attributes ) {
		return count( $attributes['posts_in'] );
	}

	/**
	 * Get the items.
	 *
	 * @param array $attributes The attributes for the source.
	 * @param int   $page       The optional page number.
	 *
	 * @return array
	 */
	public function get_items( array $attributes, $page = 1 ) {

		$posts = [];
		foreach ( $attributes['posts_in'] as $attribute ) {
			$posts[] = get_post( $attribute['id'] );
		}

		return $posts;
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
		return $items; // Manual selection doesn't need prepping.
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {

		$controls = [
			'post_type' => [
				'label'         => __( 'Post Type', 'eutopia-theme' ),
				'default'       => 'post',
				'control_type'  => 'SelectControl',
				'control_props' => [
					'options' => $this->get_post_type_options(),
				],
			],
			'posts_in'  => [
				'label'         => __( 'Post Selection', 'eutopia-theme' ),
				'default'       => [],
				'control_type'  => 'SearchPosts',
				'value_type'    => 'array',
				'control_props' => [
					'options' => $this->get_post_type_options(),
				],
			],
		];

		return $controls;
	}
}
