<?php
/**
 * Eutopia_Theme\Components\Factory\Inspector_Control class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components;

/**
 * Class for defining inspector controls.
 *
 * @property callable|mixed|null $default
 * @property callable|mixed|null $value_type
 * @property string              $type
 * @property string              $slug
 */
class Inspector_Control extends Inspector {

	/**
	 * The constructor.
	 *
	 * @param string|null $slug The optional slug.
	 */
	public function __construct( $slug = null ) {
		parent::__construct( $slug );
		$this->type = 'TextControl';
	}

	/**
	 * Build the editor config.
	 *
	 * @return array
	 */
	public function build_config() {
		return $this->prep_args( $this->args );
	}
}
