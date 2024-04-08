<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Templates\User_Cards class.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Curated_Content\Components\Templates;

use Eutopia_Theme\Curated_Content\Components\Template;

/**
 * Class for defining abstract inspector.
 */
class User_Cards extends Template {
	/**
	 * Get the template name.
	 *
	 * @return string
	 */
	public function get_name() {
		return __( 'User cards', 'eutopia-theme' );
	}

	/**
	 * Get the data source type for the template.
	 *
	 * @return string
	 */
	public function get_source_type() {
		return 'wp_user';
	}

	/**
	 * Do a single item.
	 *
	 * @param mixed $item  The item to do.
	 * @param int   $index The index of the item.
	 */
	public function do_item( $item, int $index ) {
		get_template_part(
			'template-parts/curated-content/user-card',
			null,
			[
				'item' => $item,
				'atts' => $this->get_attributes(),
			],
		);
	}
}
