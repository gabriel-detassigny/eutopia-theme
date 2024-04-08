<?php
/**
 * Eutopia_Theme\Components\Factory\Inspector_Panel class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components;

/**
 * Class for defining Inspector Panels.
 */
class Inspector_Panel extends Inspector {

	/**
	 * Holds the controls.
	 *
	 * @var Inspector_Control[]
	 */
	protected $controls = [];

	/**
	 * Add a control to the panel.
	 *
	 * @param Inspector_Control $control The control to add.
	 */
	public function add_control( Inspector_Control $control ) {
		$this->controls[ $control->slug ] = $control;
	}

	/**
	 * Sorts controls based on the `control_priority` parameter.
	 *
	 * @param array $controls The controls to sort.
	 * @return array
	 */
	public function sort_controls( $controls ) {
		$sorted = array_fill_keys( array_keys( $controls ), [] );
		foreach ( $controls as $i => $control ) {
			$sorted[ $control['controlPriority'] ?? $i ][] = $control;
		}
		return array_merge( ...$sorted );
	}

	/**
	 * Build the editor config.
	 *
	 * @return array
	 */
	public function build_config() {
		$panel    = $this->prep_args( $this->args );
		$controls = [];
		foreach ( $this->controls as $control ) {
			$controls[] = $control->build_config();
		}

		$controls          = $this->sort_controls( $controls );
		$panel['controls'] = $controls;

		return $panel;
	}
}
