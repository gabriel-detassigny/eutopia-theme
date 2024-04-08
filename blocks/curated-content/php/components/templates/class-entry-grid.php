<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Templates\Entry_Grid class.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Curated_Content\Components\Templates;

use Eutopia_Theme\Components\Images;
use Eutopia_Theme\Curated_Content\Components\Template;
use Eutopia_Theme\Helpers\Sizes;

/**
 * Class for defining abstract inspector.
 */
class Entry_Grid extends Template {
	/**
	 * Get the template name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Entry Grid', 'eutopia-theme' );
	}

	/**
	 * Render markup that should appear before the items.
	 *
	 * @return void
	 */
	public function before_items() {
		get_template_part(
			'template-parts/curated-content/entry-grid/before-items',
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
		get_template_part( 'template-parts/curated-content/entry-grid/after-items' );
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

		$attributes   = $this->get_attributes();
		$column_count = $attributes['grid_column_count'] ?? 3;

		switch ( $column_count ) {
			case 2:
				$this->render_2_cols_item();
				break;
			case 3:
				$this->render_3_cols_item();
				break;
			case 4:
				$this->render_4_cols_item();
				break;
			case 6:
				$this->render_6_cols_item();
				break;
		}
	}

	/**
	 * Render item for 2 column layout.
	 *
	 * @return void
	 */
	protected function render_2_cols_item() {
		$attributes = $this->get_attributes();

		$image_sizes[ Sizes::BREAK_MOBILE ] = 'calc(100vw - 1rem)';
		$image_sizes[ Sizes::BREAK_TABLET ] = 'calc(50vw - 1rem)';

		if ( empty( $attributes['align'] ) ) {
			$image_sizes[ Sizes::CONTENT ] = sprintf( 'calc(%dpx - 1rem)', absint( Sizes::CONTENT / 2 ) );
		} elseif ( 'wide' === $attributes['align'] ) {
			$image_sizes[ Sizes::CONTENT_WIDE ] = sprintf( 'calc(%dpx - 1rem)', absint( Sizes::CONTENT_WIDE / 2 ) );
		}

		$attributes['image_sizes_attr'] = Images::get_sizes_attr( $image_sizes );
		$attributes['show_excerpt']     = true;

		get_template_part( 'template-parts/grid-entry/size-m', null, $attributes );
	}

	/**
	 * Render item for 3 column layout.
	 *
	 * @return void
	 */
	protected function render_3_cols_item() {
		$attributes = $this->get_attributes();

		$image_sizes[ Sizes::BREAK_MOBILE ] = 'calc(100vw - 1rem)';
		$image_sizes[ Sizes::BREAK_TABLET ] = 'calc(33.333vw - 1rem)';

		if ( empty( $attributes['align'] ) ) {
			$image_sizes[ Sizes::CONTENT ] = sprintf( 'calc(%dpx - 1rem)', absint( Sizes::CONTENT / 3 ) );
		} elseif ( 'wide' === $attributes['align'] ) {
			$image_sizes[ Sizes::CONTENT_WIDE ] = sprintf( 'calc(%dpx - 1rem)', absint( Sizes::CONTENT_WIDE / 3 ) );
		}

		$attributes['image_sizes_attr'] = Images::get_sizes_attr( $image_sizes );
		$attributes['show_excerpt']     = true;

		get_template_part( 'template-parts/grid-entry/size-s', null, $attributes );
	}

	/**
	 * Render item for 4 column layout.
	 *
	 * @return void
	 */
	protected function render_4_cols_item() {
		$attributes = $this->get_attributes();

		$image_sizes[ Sizes::BREAK_MOBILE ] = 'calc(50vw - 1rem)';
		$image_sizes[ Sizes::BREAK_TABLET ] = 'calc(25vw - 1rem)';

		if ( empty( $attributes['align'] ) ) {
			$image_sizes[ Sizes::CONTENT ] = sprintf( 'calc(%dpx - 1rem)', absint( Sizes::CONTENT / 4 ) );
		} elseif ( 'wide' === $attributes['align'] ) {
			$image_sizes[ Sizes::CONTENT_WIDE ] = sprintf( 'calc(%dpx - 1rem)', absint( Sizes::CONTENT_WIDE / 4 ) );
		}

		$attributes['image_sizes_attr'] = Images::get_sizes_attr( $image_sizes );
		$attributes['show_excerpt']     = false;

		get_template_part( 'template-parts/grid-entry/size-s', null, $attributes );
	}

	/**
	 * Render item for 6 column layout.
	 *
	 * @return void
	 */
	protected function render_6_cols_item() {
		$attributes = $this->get_attributes();

		$image_sizes[ Sizes::BREAK_MOBILE ] = 'calc(50vw - 1rem)';
		$image_sizes[ Sizes::BREAK_TABLET ] = 'calc(16.666vw - 1rem)';

		if ( empty( $attributes['align'] ) ) {
			$image_sizes[ Sizes::CONTENT ] = sprintf( 'calc(%dpx - 1rem)', absint( Sizes::CONTENT / 6 ) );
		} elseif ( 'wide' === $attributes['align'] ) {
			$image_sizes[ Sizes::CONTENT_WIDE ] = sprintf( 'calc(%dpx - 1rem)', absint( Sizes::CONTENT_WIDE / 6 ) );
		}

		$attributes['image_sizes_attr'] = Images::get_sizes_attr( $image_sizes );
		$attributes['show_excerpt']     = false;

		get_template_part( 'template-parts/grid-entry/size-xs', null, $attributes );
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {
		return [
			'grid_column_count'   => [
				'label'            => __( 'Column Count', 'eutopia-theme' ),
				'default'          => 3,
				'control_type'     => 'SelectControl',
				'control_priority' => 1,
				'control_props'    => [
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
						[
							'label' => __( '6 Columns', 'eutopia-theme' ),
							'value' => 6,
						],
					],
				],
			],
			'show_media'          => [
				'label'        => __( 'Show Image', 'eutopia-theme' ),
				'default'      => false,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'show_category'       => [
				'label'        => __( 'Show Category', 'eutopia-theme' ),
				'default'      => true,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'category_is_link'    => [
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
			'show_author'         => [
				'label'        => __( 'Show Author', 'eutopia-theme' ),
				'default'      => true,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'show_published_date' => [
				'label'        => __( 'Show Published Date', 'eutopia-theme' ),
				'default'      => true,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'date_format'         => [
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
