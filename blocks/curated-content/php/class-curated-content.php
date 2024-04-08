<?php
/**
 * Eutopia_Theme\Curated_Content class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Curated_Content;

use Eutopia_Theme\Curated_Content\Components\Block;
use Eutopia_Theme\Curated_Content\Components\Source;
use Eutopia_Theme\Curated_Content\Components\Template;

/**
 * Class for creating dynamic blocks in PHP.
 */
class Curated_Content {

	/**
	 * Flag to indicate script hook is enabled.
	 *
	 * @var bool
	 */
	protected static $enabled = false;

	/**
	 * Holds the Attributes.
	 *
	 * @var array
	 */
	protected $attributes = [];

	/**
	 * Holds the block instance.
	 *
	 * @var Block
	 */
	protected $block;

	/**
	 * Holds the list of sources.
	 *
	 * @var Source[]
	 */
	protected $sources = [];

	/**
	 * Holds the list of templates.
	 *
	 * @var Template[]
	 */
	protected $templates = [];

	/**
	 * Used item ID's
	 *
	 * @var int[]
	 */
	public $used = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'action_enqueue_blocks_scripts' ] );
		do_action( 'curated_content_init', $this );
		$this->action_register_block();
	}

	/**
	 * Filters the metadata.
	 *
	 * @param array $metadata The block metadata.
	 *
	 * @return array
	 */
	public function filter_metadata( $metadata ) {
		$this->setup_controls();
		$metadata['render_callback'] = [ $this, 'render' ];
		$metadata                    = $this->block->register_args( $metadata );

		// Remove filter.
		remove_filter( 'block_type_metadata_settings', [ $this, 'filter_metadata' ] );

		return $metadata;
	}

	/**
	 * Render the Curated Content Block.
	 *
	 * @param array     $attributes The attributes.
	 * @param string    $content    The content.
	 * @param \WP_Block $block      The block object.
	 *
	 * @return string
	 */
	public function render( $attributes, $content, $block ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		$source = $this->get_source( $attributes['source'] );
		if ( ! $source ) {
			return __( 'No source selected', 'eutopia-theme' );
		}

		$template = $this->get_template( $attributes['template'] );
		if ( ! $template || $template->get_source_type() !== $source->get_source_type() ) {
			return __( 'No template / Incompatible template', 'eutopia-theme' );
		}

		$template->set_source( $source );
		$template->set_attributes( $attributes );

		ob_start();
		$template->render();
		$content = ob_get_clean();

		$wrapper_attributes = get_block_wrapper_attributes(
			[
				'class'     => $template->get_classes(
					[
						'c-ccb',
						sprintf( 'c-ccb--%s', $attributes['template'] ),
						sprintf( 'c-ccb--source-%s', $attributes['source'] ),
					],
				),
				'data-used' => wp_json_encode( $this->block->used ),
			],
		);

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return sprintf(
			'<div %1$s>%2$s</div>',
			$wrapper_attributes,
			$content,
		);
	}

	/**
	 * Register all custom blocks.
	 */
	public function action_register_block() {

		$this->block = new Block( $this );
		add_filter( 'block_type_metadata_settings', [ $this, 'filter_metadata' ] );
	}

	/**
	 * Add the register to the editor.
	 */
	public function action_enqueue_blocks_scripts() {

		$content_atts = [
			'panels'      => $this->block->build_inspector_panels(),
			'sourceTypes' => [],
		];

		foreach ( $this->sources as $slug => $source ) {
			$content_atts['sourceTypes'][ $slug ] = $source->get_source_type();
		}
		$data = 'var CURATED_CONTENT_ATTS = ' . wp_json_encode( $content_atts ) . ';';
		wp_add_inline_script( 'wp-block-editor', $data, 'before' );
	}

	/**
	 * Set up the blocks controls.
	 */
	protected function setup_controls() {

		// Setup sources.
		$this->setup_sources();

		// Setup visual panel.
		$this->setup_visual();
	}

	/**
	 * Class Loader.
	 *
	 * @param string $space The namespace for the loader.
	 */
	protected function load_class_files( $space ) {
		$sources = glob( gdt_theme_meta()->path( 'blocks', 'curated-content', 'php', 'components', $space, '*' ) );
		foreach ( $sources as $source ) {
			$slug                    = str_replace( 'class-', '', pathinfo( $source, PATHINFO_FILENAME ) );
			$parts                   = str_replace( '-', ' ', $slug );
			$spaced_name             = ucwords( $parts );
			$name                    = str_replace( ' ', '_', $spaced_name );
			$source_class            = __NAMESPACE__ . '\Components\\' . ucfirst( $space ) . "\\{$name}";
			$this->{$space}[ $slug ] = new $source_class( $this->block );
		}
	}

	/**
	 * Assign panels and control sets.
	 *
	 * @param string              $name              The name of the panel set.
	 * @param string              $set_slug          The slug of the set.
	 * @param Source[]|Template[] $sets              The object to be setting for.
	 * @param string              $parent_panel_slug The slug of the parent panel.
	 */
	protected function setup_panel_sets( $name, $set_slug, $sets, $parent_panel_slug = '_default' ) {
		$args  = [
			'label'         => $name,
			'control_type'  => 'SourceTypeSelect',
			'parent_panel'  => $parent_panel_slug,
			'control_props' => [
				'options' => [
					[
						// Translators: Placeholder is the title of the panel.
						'label' => sprintf( __( 'Select a %s', 'eutopia-theme' ), strtolower( $name ) ),
						'value' => '',
					],
				],
			],
		];
		$helps = [];
		if ( ! empty( $sets ) ) {
			foreach ( $sets as $slug => $item ) {
				$set_args = [
					'label' => $item->get_name(),
					'value' => $slug,
				];
				if ( 'template' === $set_slug ) {
					$set_args['source_type'] = $item->get_source_type();
				}
				$args['control_props']['options'][] = $set_args;

				$helps[ $slug ] = $item->get_help();
			}
		}
		if ( 1 < count( $sets ) ) {
			$this->block->add_control( $set_slug, $args );
		}
		foreach ( $helps as $help_slug => $help_text ) {
			$args = [
				'control_type'  => 'HelpText',
				'control_props' => [
					'text' => $help_text,
				],
			];
			$help = $this->block->add_control( $set_slug . '_help_' . $help_slug, $args );
			$help->set_condition( $set_slug, $help_slug );
		}
		// Create source controls and panels.
		foreach ( $sets as $slug => $item ) {
			$panels = $item->get_panels();
			if ( ! empty( $panels ) ) {
				foreach ( $panels as $panel_slug => $panel ) {
					$panel_object = $this->block->add_inspector_panel( $panel_slug, $panel );
					$panel_object->set_condition( $set_slug, $slug );
				}
			}
			$controls = $item->get_controls();
			if ( ! empty( $controls ) ) {
				foreach ( $controls as $control_slug => $control ) {
					if ( empty( $control['parent_panel'] ) ) {
						$control['parent_panel'] = $parent_panel_slug;
					}
					$control_object = $this->block->add_control( $control_slug, $control );
					$control_object->set_condition( $set_slug, $slug );
				}
			}
		}
	}

	/**
	 * Set up the sources controls.
	 */
	protected function setup_sources() {
		$this->load_class_files( 'sources' );
		$this->setup_panel_sets( __( 'Source', 'eutopia-theme' ), 'source', $this->sources );
	}

	/**
	 * Set up the visual panel and controls.
	 */
	protected function setup_visual() {
		$this->load_class_files( 'templates' );
		$this->setup_panel_sets( __( 'Template', 'eutopia-theme' ), 'template', $this->templates, 'visual' );
	}

	/**
	 * Get a Source.
	 *
	 * @param string $source The source to get.
	 *
	 * @return Source|null
	 */
	public function get_source( $source ) {
		return isset( $this->sources[ $source ] ) ? $this->sources[ $source ] : null;
	}

	/**
	 * Get all Sources slugs.
	 *
	 * @return array
	 */
	public function get_sources_slugs() {
		return array_keys( $this->sources );
	}

	/**
	 * Get a Template.
	 *
	 * @param string $template The template to get.
	 *
	 * @return Template|null
	 */
	public function get_template( $template ) {
		return isset( $this->templates[ $template ] ) ? $this->templates[ $template ] : null;
	}

	/**
	 * Get the instance.
	 *
	 * @return self
	 */
	public static function get_instance() {
		static $instance;
		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Set a single attribute.
	 *
	 * @param string $slug         The attribute slug.
	 * @param string $type         The type of attribute.
	 * @param mixed  $attr_default The default value of the attribute.
	 */
	public function set_attribute( $slug, $type = 'string', $attr_default = null ) {
		$attributes                  = isset( $this->attributes ) ? $this->attributes : [];
		$attributes[ $slug ]['type'] = $type;
		if ( ! empty( $attr_default ) ) {
			$attributes[ $slug ]['default'] = $attr_default;
		}
		$this->attributes = $attributes;
	}
}
