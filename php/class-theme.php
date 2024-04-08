<?php
/**
 * Class Theme
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme;

/**
 * Class Theme.
 */
class Theme {
	/**
	 * List of components.
	 *
	 * @var Components\Component[]
	 */
	private array $components = [];

	/**
	 * Theme constructor.
	 *
	 * @param Components\Component[] $components List of components to register.
	 */
	public function __construct( $components = [] ) {
		foreach ( $components as $component ) {
			$this->add_component( $component );
		}
	}

	/**
	 * Register a component.
	 *
	 * @param Components\Component $component Component instance.
	 *
	 * @return Components\Component Returns the Component instance.
	 */
	protected function add_component( Components\Component $component ): Components\Component {
		$this->components[ $component::class ] = $component;

		return $component;
	}

	/**
	 * Get component instance by class name.
	 *
	 * @param string $component_slug Component class name.
	 *
	 * @return Components\Component|null
	 */
	public function get_component( string $component_slug ): ?Components\Component {
		return $this->components[ $component_slug ] ?? null;
	}

	/**
	 * Init the theme and its components.
	 *
	 * @return void
	 */
	public function init(): void {
		foreach ( $this->components as $component ) {
			if ( $component->enabled() ) {
				$component->init();
			}
		}
	}
}
