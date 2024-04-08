<?php
/**
 * Eutopia_Theme\Components\Factory\Inspector abstract class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components;

/**
 * Trait for sharing functionality.
 */
trait Args {

	/**
	 * Holds the panel args.
	 *
	 * @var array
	 */
	protected $args = [];

	/**
	 * Get an argument.
	 *
	 * @param string $name The name of the arg to get.
	 *
	 * @return mixed|null|callable
	 */
	public function __get( $name ) {
		return isset( $this->args[ $name ] ) ? $this->args[ $name ] : null;
	}

	/**
	 * Set an argument value.
	 *
	 * @param string $name  The name of argument to set.
	 * @param mixed  $value The value to set.
	 */
	public function __set( $name, $value ) {
		$this->args[ $name ] = $value;
	}

	/**
	 * Get the block args.
	 *
	 * @return array
	 */
	public function get_args() {
		return $this->args;
	}

	/**
	 * Prep args for JS.
	 *
	 * @param array $args The args to prep for JS.
	 *
	 * @return array
	 */
	protected function prep_args( $args ) {
		$new_args = [];
		foreach ( $args as $key => $arg ) {
			if ( 'conditions' !== $key ) { // Conditions need to remain as php snake case.
				$key = $this->str_to_camel( $key );
				if ( is_array( $arg ) ) {
					$arg = $this->prep_args( $arg );
				}
			}
			$new_args[ $key ] = $arg;
		}

		return $new_args;
	}

	/**
	 * Snake_case to camelCase for JS.
	 *
	 * @param string $snake_string The string to change.
	 *
	 * @return string
	 */
	protected function str_to_camel( $snake_string ) {
		$snake_string = ucwords( str_replace( '_', ' ', $snake_string ) );
		$parts        = explode( ' ', $snake_string );
		$parts[0]     = strtolower( $parts[0] );

		return implode( $parts );
	}
}
