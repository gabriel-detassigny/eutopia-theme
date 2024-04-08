<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Dynamic  class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components\Sources;

use Eutopia_Theme\Components\Dynamic_Query_Exclusions;
use Eutopia_Theme\Curated_Content\Components\Source;

/**
 * Class for defining abstract inspector.
 */
class Dynamic extends Source {

	/**
	 * Get the source help.
	 *
	 * @return string
	 */
	public function get_help() {
		return __( 'Posts of selected post type are retrieved automatically based on the selected criteria.', 'eutopia-theme' );
	}

	/**
	 * Get the query arguments.
	 *
	 * @param array $attributes The attributes.
	 *
	 * @return array
	 */
	public function get_query_arguments( array $attributes ) {

		$args = [
			'post_type'      => $attributes['post_type'],
			'post_status'    => $attributes['post_status'],
			'posts_per_page' => min( 100, $attributes['posts_per_page'] ), // phpcs:ignore
			'orderby'        => $attributes['orderby'],
			'order'          => $attributes['order'],
		];

		if ( ! empty( $attributes['taxonomy'] ) ) {
			if ( ! empty( $attributes['taxonomy_in'] ) ) {
				// If a specific term is selected, use it in the query.
				$ids = [];
				foreach ( $attributes['taxonomy_in'] as $tax_in ) {
					$ids[] = $tax_in['id'];
				}
				$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => $attributes['taxonomy'],
						'field'    => 'term_id',
						'terms'    => $ids,
					],
				];
			} else {
				// If a specific term is not selected, get all terms in the taxonomy and use them in the query.
				$terms             = get_terms(
					[
						'taxonomy' => $attributes['taxonomy'],
					]
				);
				$term_ids          = wp_list_pluck( $terms, 'term_id' );
				$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => $attributes['taxonomy'],
						'terms'    => $term_ids,
					],
				];
			}
		}

		$excluded_terms = Dynamic_Query_Exclusions::get_instance()?->get_terms();

		if ( ! empty( $excluded_terms ) ) {
			$args['tax_query'][] = [
				'taxonomy' => Dynamic_Query_Exclusions::TAXONOMY,
				'terms'    => $excluded_terms,
				'field'    => 'term_id',
				'operator' => 'NOT IN',
			];
		}

		return $args;
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {

		$controls = [
			'preview_notice' => [
				'label'         => __( 'Preview Notice', 'eutopia-theme' ),
				'control_type'  => 'Notice',
				'control_props' => [
					'status'         => 'warning',
					'children'       => __( 'Queries may be different in the frontend, please preview the page before publishing', 'eutopia-theme' ),
					'is_dismissible' => false,
					'class_name'     => 'cc-notice',
				],
			],
			'post_type'      => [
				'label'         => __( 'Post Type', 'eutopia-theme' ),
				'default'       => 'post',
				'control_type'  => 'SelectControl',
				'control_props' => [
					'options' => $this->get_post_type_options(),
				],
			],
			'post_status'    => [
				'label'         => __( 'Post Status', 'eutopia-theme' ),
				'default'       => 'publish',
				'control_type'  => 'SelectControl',
				'control_props' => [
					'options' => $this->get_post_status_options(),
				],
			],
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
				'conditions'   => [
					'taxonomy_check' => [
						'taxonomy',
					],
				],
			],
			'orderby'        => [
				'label'         => __( 'Order by', 'eutopia-theme' ),
				'default'       => '',
				'control_type'  => 'SelectControl',
				'control_props' => [
					'options' => [
						[
							'label' => 'none',
							'value' => '',
						],
						[
							'label' => 'ID',
							'value' => 'ID',
						],
						[
							'label' => 'author',
							'value' => 'author',
						],
						[
							'label' => 'title',
							'value' => 'title',
						],
						[
							'label' => 'name',
							'value' => 'name',
						],
						[
							'label' => 'date',
							'value' => 'date',
						],
						[
							'label' => 'modified',
							'value' => 'modified',
						],
						[
							'label' => 'rand',
							'value' => 'rand',
						],
						[
							'label' => 'comment_count',
							'value' => 'comment_count',
						],
					],
				],
			],
			'order'          => [
				'label'         => __( 'Direction', 'eutopia-theme' ),
				'control_type'  => 'SelectControl',
				'default'       => 'ASC',
				'control_props' => [
					'options' => [
						[
							'label' => __( 'Descending', 'eutopia-theme' ),
							'value' => 'DESC',
						],
						[
							'label' => __( 'Ascending', 'eutopia-theme' ),
							'value' => 'ASC',
						],
					],
				],
				'conditions'    => [
					'order_check' => [
						'orderby',
					],
				],
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
	 * Get the Post Type options.
	 *
	 * @return array
	 */
	protected function get_post_type_options() {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$options    = [
			[
				'label' => __( 'Select a source', 'eutopia-theme' ),
				'value' => '',
			],
		];
		foreach ( $post_types as $post_type ) {
			$options[] = [
				'label' => $post_type->label,
				'value' => $post_type->name,
			];
		}

		return $options;
	}

	/**
	 * Get post status options.
	 */
	protected function get_post_status_options() {
		$states        = get_post_statuses();
		$state_options = [
			[
				'label' => 'Inherit',
				'value' => 'inherit',
			],
		];
		foreach ( $states as $state => $label ) {
			$state_options[] = [
				'label' => $label,
				'value' => $state,
			];
		}

		return $state_options;
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
}
