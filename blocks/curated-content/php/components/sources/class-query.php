<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Source\Query  class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components\Sources;

use Eutopia_Theme\Curated_Content\Components\Source;

/**
 * Class for defining abstract inspector.
 */
class Query extends Source {

	/**
	 * Get the source name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Basic loop', 'eutopia-theme' );
	}

	/**
	 * Get the source help.
	 *
	 * @return string
	 */
	public function get_help() {
		return __( 'Do a simple loop on posts.', 'eutopia-theme' );
	}

	/**
	 * Get the number of items to render.
	 *
	 * @param array $attributes The attributes.
	 *
	 * @return int
	 */
	protected function get_posts_per_page( array $attributes ) {
		return ! empty( $attributes['query']['numberOfItems'] ) ? $attributes['query']['numberOfItems'] : 10;
	}

	/**
	 * Get the query arguments.
	 *
	 * @param array $attributes The attributes.
	 *
	 * @return array
	 */
	protected function get_query_arguments( array $attributes ) {
		$args = [
			'post_type' => 'post',
		];

		$query = $attributes['query'];

		if ( ! empty( $query['category'] ) ) {
			$args['cat'] = $query['category'];
		}
		if ( ! empty( $query['author'] ) ) {
			$args['author'] = $query['author'];
		}
		if ( ! empty( $query['orderBy'] ) ) {
			$args['orderby'] = $query['orderBy'];
		}
		if ( ! empty( $query['order'] ) ) {
			$args['order'] = $query['order'];
		}

		return $args;
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {

		$terms   = get_terms( [ 'taxonomy' => 'category' ] );
		$options = [];
		foreach ( $terms as $term ) {
			$options[] = [
				'id'     => $term->term_id,
				'name'   => $term->name,
				'parent' => $term->parent,
			];
		}

		$users   = get_users( [ 'capability' => 'edit_posts' ] );
		$authors = [];
		foreach ( $users as $user ) {
			$authors[] = [
				'id'   => $user->data->ID,
				'name' => $user->data->user_nicename,
			];
		}

		$controls = [
			'preview_notice' => [
				'label'         => __( 'Preview Notice', 'eutopia-theme' ),
				'control_type'  => 'Notice',
				'control_props' => [
					'status'         => 'warning',
					'content'        => __( 'Queries may be different in the frontend, please preview the page before publishing', 'eutopia-theme' ),
					'is_dismissible' => false,
					'class_name'     => 'cc-notice',
				],
			],
			'query'          => [
				'label'         => __( 'Query', 'eutopia-theme' ),
				'default'       => [
					'maxItems'      => 20,
					'minItems'      => 1,
					'numberOfItems' => 10,
					'order'         => 'asc',
					'orderBy'       => 'title',
				],
				'control_type'  => 'QueryControl',
				'value_type'    => 'object',
				'control_props' => [
					'categories_list' => $options,
					'author_list'     => $authors,
				],
			],
		];

		return $controls;
	}
}
