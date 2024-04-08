<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Templates\Entry_List class.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Curated_Content\Components\Templates;

use Eutopia_Theme\Curated_Content\Components\Template;

/**
 * Class for defining abstract inspector.
 */
class Entry_List extends Template {
	/**
	 * Get the template name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Entry List', 'eutopia-theme' );
	}

	/**
	 * Render markup that should appear before the items.
	 *
	 * @return void
	 */
	public function before_items() {
		get_template_part( 'template-parts/curated-content/entry-list/before-items' );
	}

	/**
	 * Render markup that should appear after the items.
	 *
	 * @return void
	 */
	public function after_items() {
		get_template_part( 'template-parts/curated-content/entry-list/after-items' );
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

		get_template_part(
			'template-parts/list-entry/base',
			null,
			$this->get_attributes(),
		);
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {
		return [
			'size'                => [
				'label'         => __( 'Entry Size', 'eutopia-theme' ),
				'default'       => 'm',
				'control_type'  => 'SelectControl',
				'control_props' => [
					'options' => [
						[
							'label' => _x( 'Large', 'Size based on t-shirt L size', 'eutopia-theme' ),
							'value' => 'l',
						],
						[
							'label' => _x( 'Medium', 'Size based on t-shirt M size', 'eutopia-theme' ),
							'value' => 'm',
						],
						[
							'label' => _x( 'Small', 'Size based on t-shirt S size', 'eutopia-theme' ),
							'value' => 's',
						],
						[
							'label' => _x( 'Extra Small', 'Size based on t-shirt XS size', 'eutopia-theme' ),
							'value' => 'xs',
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
			'flip_layout'         => [
				'label'        => __( 'Flip Layout (Image on Right)', 'eutopia-theme' ),
				'default'      => false,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
				'conditions'   => [
					'label_condition' => [
						'show_media' => true,
					],
				],
			],
			'media_style'         => [
				'label'         => __( 'Thumbnail Style', 'eutopia-theme' ),
				'default'       => 'rounded',
				'control_type'  => 'SelectControl',
				'control_props' => [
					'options' => [
						[
							'label' => __( 'Rounded Corners', 'eutopia-theme' ),
							'value' => 'rounded',
						],
						[
							'label' => __( 'Circle', 'eutopia-theme' ),
							'value' => 'circle',
						],
					],
				],
				'conditions'    => [
					'label_condition' => [
						'show_media' => true,
					],
				],
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
