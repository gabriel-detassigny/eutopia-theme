<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Source\Users  class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components\Sources;

use Eutopia_Theme\Curated_Content\Components\Source;

/**
 * Class for defining abstract inspector.
 */
class Users extends Source {

	/**
	 * Get the source name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Users', 'eutopia-theme' );
	}

	/**
	 * Get the source help.
	 *
	 * @return string
	 */
	public function get_help() {
		return __( 'Gets a list of users.', 'eutopia-theme' );
	}

	/**
	 * Get the query arguments.
	 *
	 * @param array $attributes The attributes.
	 *
	 * @return array
	 */
	public function get_query_arguments( array $attributes ) {
		return [
			'role__in' => $attributes['user_roles'] ?? '',
			'number'   => $this->get_number_of_items( $attributes ),
		];
	}

	/**
	 * Get the items.
	 *
	 * @param array $attributes The attributes for the source.
	 *
	 * @return \WP_Post[]
	 */
	protected function get_items( array $attributes ) {
		$query = new \WP_User_Query( $this->get_query_arguments( $attributes ) );

		return $query->get_results();
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {

		$all_roles = wp_roles();
		$roles     = array_values( $all_roles->get_names() );

		$controls['user_roles'] = [
			'label'         => __( 'Role', 'eutopia-theme' ),
			'value_type'    => 'array',
			'default'       => [],
			'control_type'  => 'FormTokenField',
			'control_props' => [
				'suggestions'  => $roles,
				'experimental' => [
					'__experimentalExpandOnFocus',
					'__experimentalAutoSelectFirstMatch',
				],
			],
		];

		return $controls;
	}

	/**
	 * Get the source type. I.E wp_post, wp_user, array, etc.
	 *
	 * @return string
	 */
	public function get_source_type() {
		return 'wp_user';
	}
}
