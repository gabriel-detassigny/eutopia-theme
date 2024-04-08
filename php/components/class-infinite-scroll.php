<?php
/**
 * Eutopia_Theme\Components\Infinite_Scroll class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use Eutopia_Theme\Theme_Option;

/**
 * Class for managing infinite scroll features
 */
class Infinite_Scroll extends Component {


	/**
	 * URL parameter.
	 */
	const PARAM = 'infinite-scroll-pagination';

	/**
	 * @var Theme_Option Supports infinite scroll on single posts.
	 */
	protected Theme_Option $supports_single;

	/**
	 * @var Theme_Option Supports infinite scroll on archives.
	 */
	protected Theme_Option $supports_archives;

	/**
	 * @var Theme_Option Supports seamless (automatic) pagination on archives.
	 */
	protected Theme_Option $supports_seamless_archive_pagination;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->supports_single = new Theme_Option(
			$this->get_option_key( 'supports_single' ),
			[
				'label'   => __( 'Infinite scroll for single posts', 'eutopia-theme' ),
				'type'    => 'true_false',
				'message' => __( 'This allows the user to scroll infinitely through posts without manually navigating.', 'eutopia-theme' ),
			],
		);

		$this->supports_archives = new Theme_Option(
			$this->get_option_key( 'supports_archives' ),
			[
				'label'   => __( 'Infinite scroll for archive pages', 'eutopia-theme' ),
				'type'    => 'true_false',
				'message' => __( 'This allows the user to scroll infinitely through archive pages.', 'eutopia-theme' ),
			],
		);

		$this->supports_seamless_archive_pagination = new Theme_Option(
			$this->get_option_key( 'supports_seamless_archive_pagination' ),
			[
				'label'             => __( 'Infinite scroll for archive pages - seamless', 'eutopia-theme' ),
				'type'              => 'true_false',
				'message'           => __( 'This allows the user to scroll infinitely through archive pages.', 'eutopia-theme' ),
				'conditional_logic' => [
					[
						[
							'field'    => $this->get_option_key( 'supports_archives' ),
							'operator' => '==',
							'value'    => 1,
						],
					],
				],
			],
		);

		$this->register_theme_options(
			__( 'Infinite Scroll', 'eutopia-theme' ),
			[
				$this->supports_single,
				$this->supports_archives,
				$this->supports_seamless_archive_pagination,
			],
		);
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 *
	 * @return void
	 */
	public function init(): void {
		add_filter( 'query_vars', [ $this, 'filter_add_new_query_var' ], 9 );
		add_action( 'init', [ $this, 'action_add_new_url_pattern' ], 11 );
		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_scripts_variables' ], 11 );
		add_action( 'init', [ $this, 'action_add_taxonomy_url_pattern' ], 11 );
	}

	/**
	 * Adds the new URL parameter to the main query vars
	 *
	 * @param string[] $public_query_vars The array of allowed query variable names.
	 *
	 * @return string[] $public_query_vars The array of allowed query variable names.
	 */
	public function filter_add_new_query_var( $public_query_vars ): array {
		$public_query_vars[] = static::PARAM;

		return $public_query_vars;
	}

	/**
	 * Adds new URL pattern for paginated archive pages that
	 * should not have head, header, footer and sidebar
	 */
	public function action_add_new_url_pattern(): void {
		global $wp_rewrite;

		$parameter = static::PARAM;

		// Search.
		add_rewrite_rule( "({$parameter})/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", 'index.php?$matches[1]=1&paged=$matches[2]', 'top' );

		// Author.
		add_rewrite_rule( "author/([^/]+)/({$parameter})/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", 'index.php?author_name=$matches[1]&paged=$matches[3]' );

		// Date archives.
		add_rewrite_rule( "([0-9]{4})/({$parameter})/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", 'index.php?year=$matches[1]&paged=$matches[3]' );
		add_rewrite_rule( "([0-9]{4})/([0-9]{1,2})/({$parameter})/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", 'index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[4]' );

		// Get all registered post types.
		$post_types = get_post_types( [], 'objects' );

		// Add rewrite rule for post types with archive.
		foreach ( $post_types as $i => $obj ) {
			if ( ! empty( $obj->has_archive ) ) {
				add_rewrite_rule( "{$obj->rewrite['slug']}/({$parameter})/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", "index.php?\$matches[1]=1&post_type={$obj->name}&paged=\$matches[2]", 'top' );
			}
		}
	}

	/**
	 * Adds new URL pattern for paginated archive pages that
	 * should not have head, header, footer and sidebar
	 *
	 * Needs to run after CPT is registered.
	 *
	 * @return void
	 */
	public function action_add_taxonomy_url_pattern(): void {
		global $wp_rewrite;

		$parameter = static::PARAM;

		// Get all registered taxonomies.
		$taxonomies = get_taxonomies(
			[
				'publicly_queryable' => true,
				'show_ui'            => true,
			]
		);

		// Add rewrite rule for taxonomies with archive.
		foreach ( $taxonomies as $i => $slug ) {
			if ( 'category' === $slug ) {
				$tax_key = 'category_name';
			} elseif ( 'post_tag' === $slug ) {
				$slug    = 'tag';
				$tax_key = 'tag';
			} else {
				$taxonomy = get_taxonomy( $slug );

				if ( empty( $taxonomy ) ) {
					$tax_key = $slug;
				} else {
					$tax_key = $taxonomy->query_var;
					$slug    = $taxonomy->rewrite['slug'];
				}
			}

			add_rewrite_rule( "^{$slug}/([^/]+)/({$parameter})/{$wp_rewrite->pagination_base}/([0-9]{1,})/?$", "index.php?\$matches[2]=1&{$tax_key}=\$matches[1]&paged=\$matches[3]", 'top' );
		}
	}

	/**
	 * Add script variables to the main theme script.
	 */
	public function action_enqueue_scripts_variables() {
		wp_localize_script(
			'gabriel-detassigny-main', // Theme's main script file.
			'eutopiaInfiniteScrollPagination',
			[
				'text_loading' => __( 'Loading…', 'eutopia-theme' ),
			]
		);
	}

	/**
	 * Checks whether the current page is loaded from a infinite scroll CTA.
	 *
	 * @return bool True if the current page is loaded from a infinite scroll CTA, false otherwise.
	 */
	public function is_infinite_scroll_page(): bool {
		return '1' === get_query_var( static::PARAM );
	}

	/**
	 * Supports infinite scroll on single posts.
	 *
	 * @return bool
	 */
	public function supports_single(): bool {
		return (bool) $this->supports_single->get();
	}

	/**
	 * Supports infinite scroll on archives.
	 *
	 * @return bool
	 */
	public function supports_archives(): bool {
		return (bool) $this->supports_archives->get();
	}

	/**
	 * Supports seamless (automatic) pagination on archives.
	 *
	 * @return bool
	 */
	public function supports_seamless_archive_pagination(): bool {
		return (bool) $this->supports_seamless_archive_pagination->get();
	}
}
