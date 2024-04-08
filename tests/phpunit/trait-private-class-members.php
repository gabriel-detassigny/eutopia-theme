<?php
/**
 * Private_Class_Members trait.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme;

/**
 * Private_Class_Members trait.
 */
trait Private_Class_Members {
	/**
	 * Call private method.
	 *
	 * @param object $obj  Class instance.
	 * @param string $name Private Method name.
	 * @param array  $args Arguments to pass to the private method.
	 *
	 * @return mixed
	 * @throws \ReflectionException If class does not exist.
	 */
	public static function call_private_method( object $obj, string $name, array $args ) {
		$class  = new \ReflectionClass( $obj );
		$method = $class->getMethod( $name );
		$method->setAccessible( true );

		return $method->invokeArgs( $obj, $args );
	}

	/**
	 * Set private property value.
	 *
	 * @param object $obj   Class instance.
	 * @param string $name  Private property name.
	 * @param mixed  $value Property value to set.
	 *
	 * @return void
	 * @throws \ReflectionException If class does not exist.
	 */
	public static function set_private_property( object $obj, string $name, $value ) {
		$class    = new \ReflectionClass( $obj );
		$property = $class->getProperty( $name );
		$property->setAccessible( true );
		$property->setValue( $obj, $value );
	}

	/**
	 * Get private property value.
	 *
	 * @param object $obj   Class instance.
	 * @param string $name  Private property name.
	 *
	 * @return mixed
	 * @throws \ReflectionException If class does not exist.
	 */
	public static function get_private_property( object $obj, string $name ) {
		$class    = new \ReflectionClass( $obj );
		$property = $class->getProperty( $name );
		$property->setAccessible( true );

		return $property->getValue( $obj );
	}
}
