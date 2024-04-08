<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Templates\Entry_Featured_List class.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Curated_Content\Components\Templates;

use Eutopia_Theme\Curated_Content\Components\Template;

/**
 * Class for defining abstract inspector.
 */
class Entry_Featured_List extends Template {
	/**
	 * Get the template name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Entry Featured & List', 'eutopia-theme' );
	}

	/**
	 * Render markup that should appear before the items.
	 *
	 * @return void
	 */
	public function before_items() {
		get_template_part( 'template-parts/curated-content/entry-featured-list/before-items' );
	}

	/**
	 * Render markup that should appear after the items.
	 *
	 * @return void
	 */
	public function after_items() {
		get_template_part( 'template-parts/curated-content/entry-featured-list/after-items' );
	}

	/**
	 * Render markup that should appear before a single item.
	 *
	 * @return void
	 */
	public function before_item() {
		get_template_part( 'template-parts/curated-content/entry-featured-list/before-item' );
	}

	/**
	 * Render markup that should appear after a single item.
	 *
	 * @return void
	 */
	public function after_item() {
		get_template_part( 'template-parts/curated-content/entry-featured-list/after-item' );
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

		// Show first post as featured grid item.
		if ( 0 === $index ) {
			$this->render_grid_item();
		} else {
			// Show a list heading above the rest of the list items in side.
			if ( 1 === $index ) {
				$this->render_list_heading();
			}

			$this->render_list_item();
		}
	}

	/**
	 * Render grid style item first item in the collection.
	 *
	 * @return void
	 */
	protected function render_grid_item() {
		// Override attributes to make sure the featured/grid item always shows an image, author name and excerpt.
		$attributes                 = $this->get_attributes();
		$attributes['show_media']   = true;
		$attributes['show_author']  = true;
		$attributes['size']         = 'l';
		$attributes['show_excerpt'] = true;

		$this->before_item();
		get_template_part(
			'template-parts/grid-entry/base',
			null,
			$attributes,
		);
		$this->after_item();
	}

	/**
	 * Render list style item for the rest of the collection.
	 *
	 * @return void
	 */
	protected function render_list_item() {
		// Do not show author by default for list items.
		$attributes                = $this->get_attributes();
		$attributes['show_author'] = false;

		$this->before_item();
		get_template_part(
			'template-parts/list-entry/base',
			null,
			$attributes,
		);
		$this->after_item();
	}

	/**
	 * Render list heading.
	 *
	 * @return void
	 */
	protected function render_list_heading() {
		$this->before_item();
		get_template_part(
			'template-parts/curated-content/entry-featured-list/list-heading',
			null,
			$this->get_attributes(),
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
			'list_heading'     => [
				'label'        => __( 'List Items Heading', 'eutopia-theme' ),
				'default'      => __( 'Latest News', 'eutopia-theme' ),
				'control_type' => 'TextControl',
			],
			'show_media'       => [
				'label'        => __( 'Show Image', 'eutopia-theme' ),
				'default'      => true,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'show_category'    => [
				'label'        => __( 'Show Category', 'eutopia-theme' ),
				'default'      => true,
				'control_type' => 'ToggleControl',
				'value_type'   => 'boolean',
				'value_prop'   => 'checked',
			],
			'category_is_link' => [
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
		];
	}
}
