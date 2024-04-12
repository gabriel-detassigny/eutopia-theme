<?php
/**
 * Holds common utility functions.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Helpers;

/**
 * Class Utils
 */
class Utils {
	/**
	 * Check whether a class implements an interface. If not, throw exception.
	 *
	 * @param string|object $class_being_checked Class being checked.
	 * @param string        $target_interface    The interface to check against.
	 *
	 * @throws \RuntimeException Throws exception if condition passes.
	 * @return void
	 */
	public static function throw_if_not_of_type( $class_being_checked, $target_interface ) {
		if ( is_object( $class_being_checked ) && ! ( $class_being_checked instanceof $target_interface ) ) {
			throw new \RuntimeException( esc_html( get_class( $class_being_checked ) . ' must implement ' . $target_interface ) );
		}

		if ( is_object( $class_being_checked ) && ! is_a( $class_being_checked, $target_interface, true ) ) {
			throw new \RuntimeException( esc_html( get_class( $class_being_checked ) . ' must implement ' . $target_interface ) );
		}
	}

	/**
	 * Reliably detect if the request is an API or Ajax request.
	 *
	 * @return bool
	 */
	public static function is_api_call() {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}

		if ( wp_doing_ajax() ) {
			return true;
		}

		return false;
	}

	/**
	 * Is WP debug mode enabled.
	 *
	 * @return boolean
	 */
	public static function is_debug() {
		return ( defined( 'WP_DEBUG' ) && WP_DEBUG );
	}

	/**
	 * Is WP script debug mode enabled.
	 *
	 * @return boolean
	 */
	public static function is_script_debug() {
		return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
	}

	/**
	 * Get cached results.
	 *
	 * Note that is impossible to cache a `false` value so
	 * use a different value to represent a `false` case.
	 *
	 * @param string          $key               Globally unique cache key.
	 * @param callable|string $group_or_callback Cache group or callback for a fresh response.
	 * @param callable|null   $callback          Callback for a fresh response.
	 * @param integer         $expire            Cache response expiry.
	 *
	 * @return mixed|false
	 */
	public static function with_cache( string $key, callable|string $group_or_callback, ?callable $callback = null, int $expire = 0 ): mixed {
		$group = '';

		if ( is_string( $group_or_callback ) ) {
			$group = $group_or_callback;
		} elseif ( is_callable( $group_or_callback ) ) {
			$callback = $group_or_callback;
		}

		$value = wp_cache_get( $key, $group );

		if ( false === $value && is_callable( $callback ) ) {
			$value = call_user_func( $callback );
			wp_cache_set( $key, $value, $group, $expire ); // phpcs:ignore WordPressVIPMinimum.Performance.LowExpiryCacheTime.CacheTimeUndetermined
		}

		return $value;
	}

	/**
	 * Build HTML attributes string based on array of attributes.
	 *
	 * @param array $attrs HTML attributes.
	 *
	 * @return string
	 */
	public static function build_html_attrs_string( array $attrs = [] ): string {
		if ( empty( $attrs ) ) {
			return '';
		}

		return implode(
			' ',
			array_map(
				function ( $key ) use ( $attrs ) {
					if ( is_bool( $attrs[ $key ] ) ) {
						return $attrs[ $key ] ? esc_attr( $key ) : '';
					}

					return sprintf(
						'%s="%s"',
						esc_attr( $key ),
						esc_attr( $attrs[ $key ] ),
					);
				},
				array_keys( $attrs ),
			),
		);
	}
}
