<?php
/**
 * Eutopia_Theme\Curated_Content\Components\Template class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Curated_Content\Components;

/**
 * Class for defining abstract inspector.
 */
abstract class Template {

	/**
	 * Holds the Curated Content block.
	 *
	 * @var Block
	 */
	protected $block;

	/**
	 * Holds the Source.
	 *
	 * @var Source
	 */
	protected $source;

	/**
	 * Holds the template attributes.
	 *
	 * @var array
	 */
	public $attributes;

	/**
	 * The constructor.
	 *
	 * @param Block $block The block object.
	 */
	public function __construct( $block ) {
		$this->block = $block;
	}

	/**
	 * Set the source.
	 *
	 * @param Source $source The source to set.
	 */
	public function set_source( $source ) {
		$this->source = $source;
	}

	/**
	 * Set the attributes.
	 *
	 * @param array $attributes The attributes to set.
	 */
	public function set_attributes( $attributes ) {
		$this->attributes = $attributes;
	}

	/**
	 * Get the block attributes.
	 *
	 * @return array
	 */
	public function get_attributes() {
		return array_merge(
			$this->attributes,
			[
				'template_object' => $this,
			],
		);
	}

	/**
	 * Get the panels.
	 *
	 * @return array
	 */
	public function get_panels() {
		return [];
	}

	/**
	 * Get the controls.
	 *
	 * @return array
	 */
	public function get_controls() {
		return [];
	}

	/**
	 * Get the source name.
	 *
	 * @return string
	 */
	public function get_name() {
		$parts = explode( '\\', get_called_class() );

		return array_pop( $parts );
	}

	/**
	 * Get the help text.
	 */
	public function get_help() {
		return '';
	}

	/**
	 * Render markup that should appear before the items.
	 *
	 * @return void
	 */
	public function before_items() {
		// This can be used by child classes to add markup before the items.
	}

	/**
	 * Render markup that should appear after the items.
	 *
	 * @return void
	 */
	public function after_items() {
		// This can be used by child classes to add markup after the items.
	}

	/**
	 * Do a single item.
	 *
	 * @param mixed $item  The item to do.
	 * @param int   $index Current index.
	 */
	abstract public function do_item( $item, int $index );

	/**
	 * Get the source type. I.E wp_post, wp_user, array, etc.
	 *
	 * @return string
	 */
	public function get_source_type() {
		return 'wp_post';
	}

	/**
	 * Get the template wrapper classes.
	 *
	 * @param string[] $classes The classes to add.
	 *
	 * @return string
	 */
	public function get_classes( array $classes = [] ): string {
		if ( ! empty( $this->attributes['className'] ) ) {
			$classes[] = $this->attributes['className'];
		}

		return empty( $classes ) ? '' : implode( ' ', $classes );
	}

	/**
	 * Render the template.
	 */
	public function render() {
		$this->attributes['index'] = 0;

		$this->source->setup();
		$items = $this->source->items( $this->get_attributes() );

		$this->before_items();

		foreach ( $items as $item ) {
			$this->do_item( $item, $this->attributes['index'] );
			++$this->attributes['index'];
		}

		$this->after_items();

		$this->source->cleanup();
	}
}
