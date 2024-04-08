<?php
/**
 * Eutopia_Theme\Components\Factory\Inspector abstract class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components;

/**
 * Class for defining abstract inspector.
 *
 * @property string $slug
 */
abstract class Inspector {

	use Args;

	/**
	 * Holds the panel args.
	 *
	 * @var array
	 */
	protected $args = [];

	/**
	 * The constructor.
	 *
	 * @param string|null $slug The optional slug.
	 */
	public function __construct( $slug = null ) {
		if ( ! empty( $slug ) ) {
			$this->slug = $slug;
		}
	}

	/**
	 * Set up the arguments.
	 *
	 * @param array $args The arguments to set.
	 */
	public function set_args( $args ) {
		$this->args = array_merge( $args, $this->args );
	}

	/**
	 * Set a condition.
	 *
	 * @param string $slug  The slug of the condition.
	 * @param mixed  $value The value of the condition.
	 */
	public function set_condition( $slug, $value ) {
		$this->args['conditions'][ $slug . 'Condition' ][] = [
			$slug => $value,
		];
	}
}
