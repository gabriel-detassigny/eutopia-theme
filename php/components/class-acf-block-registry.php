<?php
/**
 * Eutopia_Theme\Components\ACF_Block_Registry class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Class ACF_Block_Registry.
 */
class ACF_Block_Registry extends Component {


	/**
	 * ACF blocks registry.
	 *
	 * @var array
	 */
	protected array $registry = [];

	/**
	 * Initialize.
	 */
	public function init(): void {
		// Trigger this action after the theme block registry has been
		// initialized (with default priority of 10).
		add_action( 'init', [ $this, 'maybe_add_block_local_field_groups' ], 11 );
	}

	/**
	 * Add block local field groups only if ACF has been already initialized.
	 *
	 * The `acf/init` action is fired on WP `init` action with priority of 5.
	 *
	 * @return void
	 */
	public function maybe_add_block_local_field_groups() {
		if ( ! did_action( 'acf/init' ) ) {
			return;
		}

		$this->add_block_local_field_groups();
	}

	/**
	 * Register an ACF block.
	 *
	 * @param string $block_name  Block name.
	 * @param array  $field_group Field group definition.
	 *
	 * @see https://www.advancedcustomfields.com/resources/register-fields-via-php/
	 *
	 * @return void
	 */
	public function register_block( string $block_name, array $field_group ) {
		/**
		 * Note that the following field group properties don't
		 * apply to blocks: position, style, label_placement,
		 * instruction_placement and hide_on_screen.
		 */
		$field_group = wp_parse_args(
			$field_group,
			[
				'key'        => $this->get_key( 'group', $block_name ),
				'title'      => $this->get_block_title( $block_name ),
				'location'   => [
					[
						[
							'param'    => 'block',
							'operator' => '==',
							'value'    => $block_name,
						],
					],
				],
				'menu_order' => 0,
				'fields'     => [],
			]
		);

		$field_group['fields'] = $this->alter_field_key( $block_name, $field_group['fields'] );

		$this->registry[ $block_name ] = $field_group;
	}

	/**
	 * Add local ACF field groups for every registered block.
	 *
	 * @return void
	 */
	protected function add_block_local_field_groups() {
		foreach ( $this->registry as $field_group ) {
			acf_add_local_field_group( $field_group );
		}
	}

	/**
	 * Alter keys in the fields array.
	 *
	 * ACF field key needs to be unique across the site. In order to simplify
	 * block field group definition, we modify every key (or parent key) found
	 * so that they get unique suffixes.
	 *
	 * If no key is provided while there is a name property, a key is
	 * auto-generated based on the name.
	 *
	 * @param string $block_name Block name.
	 * @param array  $fields     Field group definition.
	 *
	 * @return array Fields definition with altered keys.
	 */
	protected function alter_field_key( string $block_name, array $fields ): array {
		// Use field name as a key if there is no specified.
		if ( empty( $fields['key'] ) && ! empty( $fields['name'] ) ) {
			$fields['key'] = $fields['name'];
		}

		$keys_to_alter = [ 'key', 'parent', 'parent_repeater', 'parent_layout' ];

		foreach ( $fields as $key => $value ) {
			// For any key or parent_repeater field, append an identifier.
			if ( in_array( $key, $keys_to_alter, true ) ) {
				$fields[ $key ] = $this->get_key( 'field', $block_name, $value );
				continue;
			}

			if ( is_array( $value ) ) {
				$fields[ $key ] = $this->alter_field_key( $block_name, $value );
			}
		}

		return $fields;
	}

	/**
	 * Get block title from block type registry.
	 *
	 * @param string $block_name Block name.
	 *
	 * @return string Block title.
	 */
	protected function get_block_title( string $block_name ): string {
		$block_registry = \WP_Block_Type_Registry::get_instance();
		$block_data     = $block_registry->get_registered( $block_name );

		if ( $block_data instanceof \WP_Block_Type ) {
			return $block_data->title;
		}

		return '';
	}

	/**
	 * Generate a key for use with ACF.
	 *
	 * @param string $prefix     Key prefix, e.g. `field` or `group`.
	 * @param string $block_name Block name.
	 * @param string $field_name Field name.
	 *
	 * @return string ACF key.
	 */
	protected function get_key( string $prefix, string $block_name, string $field_name = '' ): string {
		return implode(
			'_',
			[
				$prefix,
				str_replace( [ '-', '/' ], '_', $block_name ),
				str_replace( [ '-', '/' ], '_', $field_name ),
			]
		);
	}
}
