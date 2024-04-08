<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Templates\Entry_Carousel class.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Curated_Content\Components\Templates;

use Eutopia_Theme\Curated_Content\Components\Template;

/**
 * Class for defining abstract inspector.
 */
class Entry_Carousel extends Template {
	/**
	 * Get the template name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Entry Carousel', 'eutopia-theme' );
	}

	/**
	 * Render markup that should appear before the items.
	 *
	 * @return void
	 */
	public function before_items() {
		get_template_part(
			'template-parts/curated-content/entry-carousel/before-items',
			null,
			$this->get_attributes(),
		);
	}

	/**
	 * Render markup that should appear after the items.
	 *
	 * @return void
	 */
	public function after_items() {
		get_template_part(
			'template-parts/curated-content/entry-carousel/after-items',
			null,
			$this->get_attributes(),
		);
	}

	/**
	 * Render markup that should appear before a single item.
	 *
	 * @return void
	 */
	public function before_item() {
		get_template_part( 'template-parts/curated-content/entry-carousel/before-item' );
	}

	/**
	 * Render markup that should appear after a single item.
	 *
	 * @return void
	 */
	public function after_item() {
		get_template_part( 'template-parts/curated-content/entry-carousel/after-item' );
	}

	/**
	 * Do a single item.
	 *
	 * @param mixed $item  The item to do.
	 * @param int   $index Current index.
	 */
	public function do_item( $item, int $index ) {
		global $post;

		$post = $item; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		$this->render_grid_item();
	}

	/**
	 * Render a single grid item.
	 *
	 * @return void
	 */
	protected function render_grid_item() {
		// Override attributes to make sure the featured/grid item always shows an image, author name and excerpt.
		$attributes         = $this->get_attributes();
		$attributes['size'] = 's';

		$this->before_item();
		get_template_part(
			'template-parts/grid-entry/base',
			null,
			$attributes,
		);
		$this->after_item();
	}

	/**
	 * Get the controls.
	 *
	 * A complete list of supported entry card settings/controls can be discovered in the List or Grid templates".
	 *
	 * @return array
	 */
	public function get_controls() {
		return [
			'heading'               => [
				'label'        => __( 'Heading', 'eutopia-theme' ),
				'default'      => __( 'Title', 'eutopia-theme' ),
				'control_type' => 'TextControl',
			],
			'carousel_column_count' => [
				'label'         => __( 'Column Count', 'eutopia-theme' ),
				'default'       => 3,
				'control_type'  => 'SelectControl',
				'control_props' => [
					'options' => [
						[
							'label' => __( '2 Columns', 'eutopia-theme' ),
							'value' => 2,
						],
						[
							'label' => __( '3 Columns', 'eutopia-theme' ),
							'value' => 3,
						],
						[
							'label' => __( '4 Columns', 'eutopia-theme' ),
							'value' => 4,
						],
					],
				],
			],
			'show_pagination'       => [
				'label'        => __( 'Show Pagination', 'eutopia-theme' ),
				'default'      => false,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'show_media'            => [
				'label'        => __( 'Show Image', 'eutopia-theme' ),
				'default'      => true,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'show_category'         => [
				'label'        => __( 'Show Category', 'eutopia-theme' ),
				'default'      => true,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'category_is_link'      => [
				'label'        => __( 'Link to Category Archive', 'eutopia-theme' ),
				'default'      => true,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
				'conditions'   => [
					'label_condition' => [
						'show_category' => true,
					],
				],
			],
			'show_excerpt'          => [
				'label'        => __( 'Show Excerpt', 'eutopia-theme' ),
				'default'      => false,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'show_author'           => [
				'label'        => __( 'Show Author', 'eutopia-theme' ),
				'default'      => false,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'show_published_date'   => [
				'label'        => __( 'Show Published Date', 'eutopia-theme' ),
				'default'      => false,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'date_format'           => [
				'label'        => __( 'Date format', 'eutopia-theme' ),
				'default'      => get_option( 'date_format', 'l, dS F, Y' ),
				'control_type' => 'TextControl',
				'conditions'   => [
					'label_condition' => [
						'show_published_date' => true,
					],
				],
			],
		];
	}
}
