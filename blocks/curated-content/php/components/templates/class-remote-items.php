<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Templates\Remote_Items class.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Curated_Content\Components\Templates;

use Eutopia_Theme\Curated_Content\Components\Template;

/**
 * Class for defining abstract inspector.
 */
class Remote_Items extends Template {

	/**
	 * Get the template name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'Remote Items', 'eutopia-theme' );
	}

	/**
	 * Do a single item.
	 *
	 * @param mixed $item  The item to do.
	 * @param int   $index The index of the item.
	 */
	public function do_item( $item, int $index ) {
		get_template_part(
			'template-parts/curated-content/remote-items',
			null,
			[
				'item' => $item,
				'atts' => $this->get_attributes(),
			],
		);
	}

	/**
	 * GEt the data source type for the template.
	 *
	 * @return string
	 */
	public function get_source_type() {
		return 'array';
	}
}
