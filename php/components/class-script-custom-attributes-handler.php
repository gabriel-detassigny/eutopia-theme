<?php
/**
 * Eutopia_Theme\Components\Script_Custom_Attributes_Handler class.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Class for managing script tag custom attributes.
 */
class Script_Custom_Attributes_Handler extends Component {


	/**
	 * Script custom attributes.
	 *
	 * @type array
	 */
	const ATTRIBUTES = [
		'async'    => true,
		'defer'    => true,
		'nomodule' => true,
		'type'     => 'module',
	];

	/**
	 * Add action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_filter( 'script_loader_tag', [ $this, 'filter_script_tag' ], PHP_INT_MAX, 2 );
	}

	/**
	 * Filter script tag and apply appropriate attributes based on script data.
	 *
	 * @param string $tag    The HTML tag.
	 * @param string $handle The script handle.
	 *
	 * @return string
	 */
	public function filter_script_tag( $tag, $handle ) {
		$new_script_attributes = [];

		foreach ( self::ATTRIBUTES as $attr => $value ) {
			if ( wp_scripts()->get_data( $handle, $attr ) === $value ) {
				$new_script_attributes[ $attr ] = $value;
			}
		}

		// Bail out early no attributes are set.
		if ( empty( $new_script_attributes ) ) {
			return $tag;
		}

		return (string) preg_replace_callback(
			'/<script\s(?P<attrs_string>(?:[^>]*)src=(?:[^>]*))>/',
			function ( $matches ) use ( $new_script_attributes ) {
				// Combine the original attributes with the new attributes.
				$script_attrs = array_merge(
					$this->parse_script_attrs( $matches['attrs_string'] ),
					$new_script_attributes
				);

				// Remove conflicting attributes.
				if ( isset( $script_attrs['defer'], $script_attrs['async'] ) ) {
					unset( $script_attrs['async'] );
				}
				if ( isset( $script_attrs['nomodule'], $script_attrs['type'] ) && 'module' === $script_attrs['type'] ) {
					unset( $script_attrs['nomodule'] );
				}

				// Rebuild the script tag with the new attributes.
				return sprintf( '<script %s>', $this->stringify_script_attrs( $script_attrs ) );
			},
			$tag,
			1
		);
	}

	/**
	 * Parse a script attributes string into an array.
	 *
	 * @param string $attrs_string Script attributes string.
	 *
	 * @return array<string> Attributes and values.
	 */
	protected function parse_script_attrs( $attrs_string ) {
		$attrs            = wp_kses_hair( $attrs_string, wp_allowed_protocols() );
		$normalized_attrs = array_column( $attrs, 'value', 'name' );

		return $normalized_attrs;
	}

	/**
	 * Build a script tag based on the provided attributes array.
	 *
	 * @param array<empty|bool|string> $attrs Tag attributes.
	 *
	 * @return string Script tag.
	 */
	protected function stringify_script_attrs( $attrs ) {
		$attrs_vals = [];

		foreach ( $attrs as $attr => $value ) {
			if ( empty( $value ) || $value === $attr || true === $value ) {
				$attrs_vals[] = $attr;
			} else {
				$attrs_vals[] = "$attr='$value'";
			}
		}

		return implode( ' ', $attrs_vals );
	}
}
