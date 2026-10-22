<?php
/**
 * Component base.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Component base.
 */
abstract class Component {

	/**
	 * Get the instance of the class.
	 *
	 * @return static|null
	 */
	public static function get_instance(): ?static {
		/** @var static $component */
		$component = gdt_theme()->get_component( static::class );

		if ( $component->enabled() ) {
			return $component;
		}

		return null;
	}

	/**
	 * When true returned, the component will be loaded.
	 *
	 * @return bool
	 */
	public function enabled(): bool {
		return true;
	}

	/**
	 * Init the component. Hooks go in here.
	 *
	 * @return void
	 */
	public function init(): void {
		// no-op.
	}
}
