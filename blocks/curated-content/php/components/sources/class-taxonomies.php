<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Sources\Taxonomies class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components\Sources;

use Eutopia_Theme\Curated_Content\Components\Source;

/**
 * Class for defining `Taxonomies` source.
 */
class Taxonomies extends Source {

	/**
	 * Get the source name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Taxonomies', 'eutopia-theme' );
	}

	/**
	 * Get the source help.
	 *
	 * @return string
	 */
	public function get_help() {
		return __( 'Gets a list of Terms.', 'eutopia-theme' );
	}

	/**
	 * Get the items.
	 *
	 * @param array $attributes The attributes for the source.
	 *
	 * @return \WP_Term[]
	 */
	protected function get_items( array $attributes ) {
		$terms = [];

		// To avoid php errors, make sure we get keys from a control, even though they are set as null.
		$desired_attributes = wp_parse_args(
			$attributes,
			array_fill_keys( array_keys( $this->get_controls() ), null )
		);

		// Bail it's not source's attributes.
		if ( empty( $desired_attributes ) ) {
			return $terms;
		}

		if ( ! empty( $attributes['taxonomy_in'] ) ) {
			// Get the terms from the selected taxonomy.
			return array_reduce(
				$attributes['taxonomy_in'],
				static function ( $terms, $atts ) {
					$t = get_term( $atts['id'] );

					if ( ! is_wp_error( $t ) ) {
						$terms[] = $t;
					}

					return $terms;
				},
				$terms
			);
		}

		// Over time the taxonomy can be removed from the codebase,
		// but the block can still keep the reference to it within the block's attributes.
		if ( ! taxonomy_exists( $attributes['taxonomy'] ) ) {
			return $terms;
		}

		$terms = get_terms(
			[
				'taxonomy'   => $attributes['taxonomy'],
				'hide_empty' => true,
			]
		);

		if ( is_wp_error( $terms ) ) {
			return [];
		}

		return $terms;
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {

		$controls = [
			'taxonomy'       => [
				'label'         => __( 'Taxonomy', 'eutopia-theme' ),
				'control_type'  => 'SelectControl',
				'control_props' => [
					'options' => $this->get_taxonomy_options(),
				],
			],
			'taxonomy_in'    => [
				'label'        => __( 'Taxonomy Selection', 'eutopia-theme' ),
				'default'      => [],
				'control_type' => 'SearchTaxonomy',
				'value_type'   => 'array',
			],
			'posts_per_page' => [ // phpcs:ignore
				'label'         => __( 'Number of posts', 'eutopia-theme' ),
				'control_type'  => 'RangeControl',
				'value_type'    => 'number',
				'default'       => 6,
				'control_props' => [
					'label_position' => 'edge',
					'min'            => 1,
					'max'            => 30,
				],
			],
		];

		return $controls;
	}

	/**
	 * Get the taxonomy options.
	 *
	 * @return array Taxonomy options.
	 */
	protected function get_taxonomy_options() {

		// Set default options.
		$default_options = [
			[
				'label' => __( 'none', 'eutopia-theme' ),
				'value' => '',
			],
			[
				'label' => __( 'Categories', 'eutopia-theme' ),
				'value' => 'category',
			],
			[
				'label' => __( 'Tags', 'eutopia-theme' ),
				'value' => 'post_tag',
			],
		];

		// Taxonomy query args.
		$args = [
			'public'   => true,
			'_builtin' => false,
		];

		$taxonomies = get_taxonomies( $args, 'objects' );

		// Empty options array to set from taxonomies.
		$options = [];

		foreach ( $taxonomies as $taxonomy ) {
			$options[] = [
				'label' => $taxonomy->label,
				'value' => $taxonomy->name,
			];
		}

		// Merge default options with taxonomies.
		return wp_parse_args( $options, $default_options );
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
	 * Get the source type. I.E wp_post, wp_user, array, etc.
	 *
	 * @return string
	 */
	public function get_source_type() {
		return 'wp_term';
	}

	/**
	 * Get the number of items to retrieve.
	 *
	 * @param array $attributes The attributes.
	 *
	 * @return int
	 */
	public function get_number_of_items( $attributes ) {
		return 0; // Depends on a manual selection.
	}
}
