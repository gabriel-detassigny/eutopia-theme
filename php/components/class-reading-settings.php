<?php
/**
 * Eutopia_Theme\Components\Reading_Settings class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use WP_Post;

/**
 * Customize reading settings
 */
class Reading_Settings extends Component {


	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'admin_init', [ $this, 'admin_init' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'rest_api_init', [ $this, 'register_settings' ] );
		add_action( 'template_redirect', [ $this, 'redirect_404_access' ], 10 );
		add_action( 'update_option_page_for_404', [ $this, 'update_option_page_for_404' ], 10, 3 );
		add_filter( 'display_post_states', [ $this, 'display_post_states' ], 10, 2 );
		add_filter( 'wp_robots', [ $this, 'filter_wp_robots' ] );
		add_filter( 'wp_sitemaps_posts_query_args', [ $this, 'sitemap_query_filter' ], 10, 2 );
	}

	/**
	 * Register custom settings
	 */
	public function register_settings() {
		register_setting(
			'reading',
			'page_for_404',
			[
				'type'              => 'integer',
				'sanitize_callback' => 'intval',
				'default'           => null,
				'show_in_rest'      => true,
			]
		);
	}

	/**
	 * Register settings field
	 */
	public function admin_init() {
		add_settings_field(
			'page_for_404',
			__( 'Page for 404', 'eutopia-theme' ),
			[ $this, 'page_for_404_callback' ],
			'reading',
			'default',
			[ 'label_for' => 'page_for_404' ]
		);
	}

	/**
	 * Settings callback
	 */
	public function page_for_404_callback() {
		$page_for_404_id = get_option( 'page_for_404' );

		// Both draft and published pages are eligible for a 404-page template.
		$items = get_posts(
			[
				'posts_per_page' => - 1,
				'orderby'        => 'name',
				'order'          => 'ASC',
				'post_type'      => 'page',
				'post_status'    => [ 'publish', 'draft' ],
			]
		);

		echo '<select id="page_for_404" name="page_for_404">';
		echo '<option value="0">' . esc_html__( '— Select —', 'eutopia-theme' ) . '</option>';

		foreach ( $items as $item ) {
			printf(
				'<option value="%d" %s>%s</option>',
				(int) $item->ID,
				selected( (int) $page_for_404_id, $item->ID, false ),
				esc_html( $item->post_title )
			);
		}

		echo '</select>';
	}

	/**
	 * Filters the default post display states used in the posts list table.
	 *
	 * @param string[] $post_states An array of post display states.
	 * @param WP_Post  $post        The current post object.
	 */
	public function display_post_states( $post_states, $post ) {
		$page_for_404_id = get_option( 'page_for_404' );

		if ( $post->ID == $page_for_404_id ) {
			$post_states[] = __( '404 Page', 'eutopia-theme' );
		}

		return $post_states;
	}

	/**
	 * Filters the directives to be included in the 'robots' meta tag.
	 * Adds "noindex" to the 404 page's 'robots' meta tag.
	 *
	 * Note: This filter isn't required if the 404 page is set as a draft.
	 *
	 * @param array $robots Associative array of directives. Every key must be the name of the directive, and the
	 *                      corresponding value must either be a string to provide as value for the directive or a
	 *                      boolean `true` if it is a boolean directive, i.e. without a value.
	 */
	public function filter_wp_robots( $robots ) {

		// Return early for all non-singular pages.
		if ( ! is_singular() ) {
			return $robots;
		}

		$page_for_404_id = get_option( 'page_for_404' );

		if ( $page_for_404_id && get_the_ID() === (int) $page_for_404_id ) {
			$robots['noindex'] = true;
		}

		return $robots;
	}

	/**
	 * Filters the query arguments for the posts sitemap.
	 *
	 * Note: This filter isn't required if the 404 page is set as a draft.
	 *
	 * @param array  $args      Array of WP_Query arguments.
	 * @param string $post_type Post type name.
	 */
	public function sitemap_query_filter( $args, $post_type ) {
		$page_for_404_id = get_option( 'page_for_404' );

		// Do not show the 404 page in the sitemap.
		if ( ! empty( $page_for_404_id ) ) {
			$args['post__not_in'] = [ $page_for_404_id ]; // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
		}

		return $args;
	}

	/**
	 * Redirects 404-page access to the 404 template if it is not a 404 request.
	 *
	 * Note: This filter isn't required if the 404 page is set as a draft.
	 *
	 * @return void
	 */
	public function redirect_404_access() {
		global $wp_query;

		if ( ! is_404() ) {
			$page_for_404_id = get_option( 'page_for_404' );

			// Logged in users who can edit this page will still have access.
			if ( ! empty( $page_for_404_id ) &&
				is_page( $page_for_404_id ) &&
				! current_user_can( 'edit_post', $page_for_404_id ) ) {
				// Set 404 status and display 404 template instead of 404 page.
				$wp_query->set_404();
				status_header( 404 );
				get_template_part( '404' );
				exit;
			}
		}
	}

	/**
	 * Update the select 404 page to draft when the 404 option is updated.
	 *
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $value The new option value.
	 * @param string $option Option name.
	 *
	 * @return void
	 */
	public function update_option_page_for_404( $old_value, $value, $option ) {

		// Ensure the value is an appropriate post ID.
		if ( empty( $value ) || ! is_numeric( $value ) ) {
			return;
		}

		// Update post status to draft.
		$args = [
			'ID'          => $value,
			'post_status' => 'draft',
		];

		wp_update_post( $args );
	}
}
