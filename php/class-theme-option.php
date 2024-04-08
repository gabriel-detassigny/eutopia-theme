<?php
/**
 * Class Theme_Option.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme;

/**
 * Single theme option model.
 */
class Theme_Option {
	/**
	 * Constructor.
	 *
	 * @param string        $key      Field key.
	 * @param array         $args     Field arguments.
	 * @param callable|null $validate Optional. Validation callback.
	 */
	public function __construct( protected string $key, protected array $args, ?callable $validate = null ) {
		if ( $validate ) {
			add_filter( sprintf( 'acf/validate_value/key=%s', $this->key ), $validate, 10, 3 );
		}
	}

	/**
	 * Get field configuration (ACF).
	 *
	 * @return array
	 */
	public function config(): array {
		return array_merge(
			[
				'key'  => $this->key,
				'name' => $this->key,
			],
			$this->args,
		);
	}

	/**
	 * Get the option value.
	 *
	 * @return mixed
	 */
	public function get(): mixed {
		if ( ! function_exists( 'get_field' ) ) {
			return null;
		}

		return get_field( $this->key, 'option' );
	}
}
