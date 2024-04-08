<?php
/**
 * Eutopia_Theme\Components\Dynamic_Query_Exclusions class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use Eutopia_Theme\Theme_Option;

/**
 * Class Dynamic_Query_Exclusions.
 */
class Dynamic_Query_Exclusions extends Component {
	/**
	 * Taxonomy name.
	 */
	const TAXONOMY = 'metadata';

	/**
	 * Theme option for excluded terms.
	 *
	 * @var Theme_Option
	 */
	protected Theme_Option $terms;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->terms = new Theme_Option(
			$this->get_option_key( 'metadata_terms' ),
			[
				'label'      => __( 'Terms to exclude', 'eutopia-theme' ),
				'message'    => __( 'Posts containing the following metadata terms will be hidden from dynamic queries', 'eutopia-theme' ),
				'type'       => 'taxonomy',
				'taxonomy'   => self::TAXONOMY,
				'field_type' => 'multi_select',
			],
		);

		$this->register_theme_options(
			__( 'Dynamic Query Exclusions', 'eutopia-theme' ),
			[
				$this->terms,
			],
		);
	}

	/**
	 * Register any needed hooks/filters.
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'register_taxonomy' ] );
	}

	/**
	 * Register the metadata taxonomy.
	 *
	 * @return void
	 */
	public function register_taxonomy() {
		register_taxonomy(
			self::TAXONOMY,
			[ 'post' ],
			[
				'labels'             => [
					'name'                       => _x( 'Metadata', 'Taxonomy General Name', 'eutopia-theme' ),
					'singular_name'              => _x( 'Metadata', 'Taxonomy Singular Name', 'eutopia-theme' ),
					'menu_name'                  => __( 'Metadata', 'eutopia-theme' ),
					'all_items'                  => __( 'All Metadata', 'eutopia-theme' ),
					'parent_item'                => __( 'Parent Metadata', 'eutopia-theme' ),
					'parent_item_colon'          => __( 'Parent Metadata:', 'eutopia-theme' ),
					'new_item_name'              => __( 'New Metadata Name', 'eutopia-theme' ),
					'add_new_item'               => __( 'Add New Metadata', 'eutopia-theme' ),
					'edit_item'                  => __( 'Edit Metadata', 'eutopia-theme' ),
					'update_item'                => __( 'Update Metadata', 'eutopia-theme' ),
					'view_item'                  => __( 'View Metadata', 'eutopia-theme' ),
					'separate_items_with_commas' => __( 'Separate Metadata with commas', 'eutopia-theme' ),
					'add_or_remove_items'        => __( 'Add or remove Metadata', 'eutopia-theme' ),
					'choose_from_most_used'      => __( 'Choose from the most used', 'eutopia-theme' ),
					'popular_items'              => __( 'Popular Metadata', 'eutopia-theme' ),
					'search_items'               => __( 'Search Metadata', 'eutopia-theme' ),
					'not_found'                  => __( 'Not Found', 'eutopia-theme' ),
					'no_terms'                   => __( 'No Metadata', 'eutopia-theme' ),
					'items_list'                 => __( 'Metadata list', 'eutopia-theme' ),
					'items_list_navigation'      => __( 'Metadata list navigation', 'eutopia-theme' ),
				],
				'rewrite'            => [
					'slug' => self::TAXONOMY,
				],
				'hierarchical'       => true,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'show_in_rest'       => true,
				'show_tagcloud'      => false,
				'show_in_quick_edit' => false,
			],
		);
	}

	/**
	 * Get terms to exclude from dynamic queries.
	 *
	 * @return mixed
	 */
	public function get_terms(): mixed {
		return $this->terms->get();
	}
}
