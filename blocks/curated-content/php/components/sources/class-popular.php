<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Source\Dynamic  class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components\Sources;

use Eutopia_Theme\Curated_Content\Components\Source;

/**
 * Class for defining abstract inspector.
 */
class Popular extends Query {

	/**
	 * Get the source name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Popular posts', 'eutopia-theme' );
	}

	/**
	 * Get the source help.
	 *
	 * @return string
	 */
	public function get_help() {
		return __( 'Like the basic query but with the addition of popularity.', 'eutopia-theme' );
	}

	/**
	 * Get the query arguments.
	 *
	 * @param array $attributes The attributes.
	 *
	 * @return array
	 */
	protected function get_query_arguments( array $attributes ) {
		$args = parent::get_query_arguments( $attributes );
		if ( 'comments' === $attributes['popular_type'] ) {
			$args['orderby'] = 'comment_count';
		}
		// @todo: integrate jetpack.

		return $args;
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {
		$controls = parent::get_controls();

		$controls['popular_type'] = [
			'label'         => __( 'Popularity Type', 'eutopia-theme' ),
			'control_type'  => 'SelectControl',
			'control_props' => [
				'options' => [
					[
						'label' => __( 'Comments', 'eutopia-theme' ),
						'value' => 'comments',
					],
					[
						'label' => __( 'Views (requires Jetpack)', 'eutopia-theme' ),
						'value' => 'views',
					],
				],
			],
		];

		return $controls;
	}
}
