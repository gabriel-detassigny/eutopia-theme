<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Remote_WordPress  class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components\Sources;

use Eutopia_Theme\Curated_Content\Components\Source;

/**
 * Class for defining abstract inspector.
 */
class Remote_WordPress extends Dynamic {

	/**
	 * Get the source name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Remote WordPress', 'eutopia-theme' );
	}

	/**
	 * Get the items.
	 *
	 * @param array $attributes The attributes for the source.
	 *
	 * @return array|\WP_Post[]
	 */
	public function get_items( array $attributes ) {
		$query             = $this->get_query_arguments( $attributes );
		$query['order']    = strtolower( $query['order'] );
		$query['per_page'] = $query['posts_per_page'];
		$url               = add_query_arg( array_filter( $query ), $attributes['remote_url'] . '/wp-json/wp/v2/posts' );
		$request           = wp_remote_get( $url ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.wp_remote_get_wp_remote_get
		$data              = [];
		if ( ! is_wp_error( $request ) ) {
			$data = json_decode( wp_remote_retrieve_body( $request ), true );
		}

		return $data;
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
		return $items;
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {
		$main_controls = parent::get_controls();
		$controls      = [
			'remote_url' => [
				'label'        => __( 'Remote URL', 'eutopia-theme' ),
				'default'      => '',
				'control_type' => 'TextControl',
			],
		];

		return array_merge( $controls, $main_controls );
	}

	/**
	 * Get the source type. I.E wp_post, wp_user, array, etc.
	 *
	 * @return string
	 */
	public function get_source_type() {
		return 'array';
	}
}
