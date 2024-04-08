<?php
/**
 * Eutopia_Theme\Components\Factory\Block class
 *
 * @package Eutopia_Theme
 */

// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
namespace Eutopia_Theme\Curated_Content\Components;

use Eutopia_Theme\Curated_Content\Curated_Content;

/**
 * Class for defining inspector controls.
 */
class Block {

	use Args;

	/**
	 * Holds the Inspector Panels.
	 *
	 * @var Inspector_Panel[]
	 */
	protected $panels = [];

	/**
	 * Holds the controls.
	 *
	 * @var Inspector_Control[]
	 */
	protected $controls = [];

	/**
	 * Holds the sources.
	 *
	 * @var Curated_Content
	 */
	protected $curated_content;

	/**
	 * List of used ID's.
	 *
	 * @var int[]
	 */
	public $used = [];

	/**
	 * Constructor.
	 *
	 * @param Curated_Content $curated_content Main instance.
	 */
	public function __construct( $curated_content ) {
		$this->curated_content = $curated_content;
		// Add a default panel.
		$this->add_inspector_panel(
			'_default',
			[
				'title'        => __( 'Data', 'eutopia-theme' ),
				'initial_open' => true,
			]
		);
		// Create the Visual Panel.
		$this->add_inspector_panel( 'visual', [ 'title' => __( 'Visual', 'eutopia-theme' ) ] );
	}

	/**
	 * Get a template.
	 *
	 * @param string $slug The template Slug.
	 */
	public function get_template( $slug ) {
		return $this->curated_content->get_template( $slug );
	}

	/**
	 * Gets the register args for registering a block.
	 *
	 * @param array $args The array of block metadata settings.
	 *
	 * @return array
	 */
	public function register_args( $args ) {

		// Set attributes.
		$attributes = [];
		foreach ( $this->controls as $slug => $control ) {
			$default             = $control->default;
			$attributes[ $slug ] = [
				'type'    => $control->value_type,
				'default' => ! empty( $default ) ? $default : $this->get_type_default( $control->value_type ),
			];
		}

		// Add attributes to args.
		$args['attributes'] = array_merge( $args['attributes'], $attributes );

		return $args;
	}

	/**
	 * Get a default for type.
	 *
	 * @param string $type The type to get.
	 *
	 * @return mixed
	 */
	protected function get_type_default( $type ) {

		$defaults = [
			'string'  => '',
			'object'  => new \stdClass(),
			'boolean' => false,
			'number'  => 0,
		];

		return isset( $defaults[ $type ] ) ? $defaults[ $type ] : null;
	}

	/**
	 * Add an inspector panel.
	 *
	 * @param string $slug The panel Slug.
	 * @param array  $args The panel args.
	 *
	 * @return Inspector_Panel
	 */
	public function add_inspector_panel( $slug, array $args = [] ) {
		if ( empty( $this->panels[ $slug ] ) ) {
			$default = [
				'title' => $slug,
			];
			$args    = wp_parse_args( $args, $default );
			$panel   = new Inspector_Panel( $slug );
			$panel->set_args( $args );
			$this->panels[ $slug ] = $panel;
		}

		return $this->panels[ $slug ];
	}

	/**
	 * Add a control to the block.
	 *
	 * @param string $slug The control slug (also the attribute slug ).
	 * @param array  $args The extended control args.
	 *
	 * @return Inspector_Control
	 */
	public function add_control( $slug, array $args = [] ) {
		if ( empty( $this->controls[ $slug ] ) ) {
			$default = [
				'label'         => $slug,
				'default'       => null,
				'slug'          => $slug,
				'parent_panel'  => '_default',
				'value_type'    => 'string',
				'value_prop'    => 'value',
				'change_prop'   => 'onChange',
				'control_type'  => 'TextControl',
				'control_props' => [],
			];

			$args = wp_parse_args( $args, $default );

			$default_control_props = [
				'label' => $args['label'],
			];

			$args['control_props'] = wp_parse_args( $args['control_props'], $default_control_props );
			$control               = new Inspector_Control( $args['slug'] );
			// Set the control args.
			$control->set_args( $args );
			if ( empty( $this->panels[ $args['parent_panel'] ] ) ) {
				$this->panels[ $args['parent_panel'] ] = new Inspector_Panel( $args['parent_panel'] );
			}
			$this->panels[ $args['parent_panel'] ]->add_control( $control );
			$this->controls[ $slug ] = $control;
		}

		return $this->controls[ $slug ];
	}

	/**
	 * Build inspector Panels.
	 *
	 * @return array
	 */
	public function build_inspector_panels() {
		$panels = [];
		foreach ( $this->panels as $slug => $panel ) {
			$panels[ $slug ] = $panel->build_config();
		}

		return $panels;
	}
}
