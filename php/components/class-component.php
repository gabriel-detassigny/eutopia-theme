<?php
/**
 * Component base.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use Eutopia_Theme\Theme_Option;

/**
 * Component base.
 */
abstract class Component {
	/**
	 * Options page slug.
	 *
	 * @var string
	 */
	const OPTIONS_PAGE_MENU_SLUG = 'theme-options';

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

	/**
	 * Register theme options for this component.
	 *
	 * @param string         $title  Title of the options page.
	 * @param Theme_Option[] $fields List of fields to add.
	 *
	 * @return void
	 */
	public function register_theme_options( string $title, array $fields ): void {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$theme_options_config = [
			'key'      => $this->get_option_key(),
			'title'    => $title,
			'fields'   => [],
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => self::OPTIONS_PAGE_MENU_SLUG,
					],
				],
			],
		];

		foreach ( $fields as $field ) {
			if ( $field instanceof Theme_Option ) {
				$theme_options_config['fields'][] = $field->config();
			}
		}

		add_action(
			'acf/init',
			function () use ( $theme_options_config ) {
				if ( ! acf_get_options_page( self::OPTIONS_PAGE_MENU_SLUG ) ) {
					acf_add_options_page(
						[
							'menu_slug'   => self::OPTIONS_PAGE_MENU_SLUG,
							'page_title'  => __( 'Theme Options', 'eutopia-theme' ),
							'menu_title'  => __( 'Theme Options', 'eutopia-theme' ),
							'parent_slug' => 'options-general.php',
							'position'    => 5,
							'redirect'    => false,
						]
					);
				}

				acf_add_local_field_group( $theme_options_config );
			},
		);
	}


	/**
	 * Get an option key.
	 *
	 * @param string|null $field_name Field name.
	 *
	 * @return string
	 */
	protected function get_option_key( ?string $field_name = null ): string {
		$key = str_replace(
			[ '-', '\\' ],
			[ '_', '__' ],
			strtolower( static::class ),
		);

		if ( ! empty( $field_name ) ) {
			$key = sprintf( '%s__%s', $key, $field_name );
		}

		return $key;
	}
}
