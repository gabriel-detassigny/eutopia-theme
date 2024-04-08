<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Templates\Terms class.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Curated_Content\Components\Templates;

use Eutopia_Theme\Curated_Content\Components\Template;

/**
 * Class for defining abstract inspector.
 */
class Terms extends Template {
	/**
	 * Get the template name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Terms', 'eutopia-theme' );
	}

	/**
	 * Get the data source type for the template.
	 *
	 * @return string
	 */
	public function get_source_type() {
		return 'wp_term';
	}

	/**
	 * Render markup that should appear before the items.
	 *
	 * @return void
	 */
	public function before_items() {
		get_template_part( 'template-parts/curated-content/terms/before-items' );
	}

	/**
	 * Render markup that should appear after the items.
	 *
	 * @return void
	 */
	public function after_items() {
		get_template_part( 'template-parts/curated-content/terms/after-items' );
	}

	/**
	 * Do a single item.
	 *
	 * @param mixed $item  The item to do.
	 * @param int   $index The index of the item.
	 */
	public function do_item( $item, int $index ) {
		get_template_part(
			'template-parts/curated-content/terms/term',
			null,
			[
				'item' => $item,
				'atts' => $this->get_attributes(),
			],
		);
	}
}
