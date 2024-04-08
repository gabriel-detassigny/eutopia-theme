<?php
/**
 * Script_Custom_Attributes_Handler tests.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Test_Script_Custom_Attributes_Handler Class.
 *
 * @coversDefaultClass \Eutopia_Theme\Components\Script_Custom_Attributes_Handler
 */
class Test_Script_Custom_Attributes_Handler extends \WP_UnitTestCase {

	/**
	 * Test class instance.
	 *
	 * @var Script_Custom_Attributes_Handler
	 */
	private $instance;

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function set_up() {
		parent::set_up();

		$this->instance = new Script_Custom_Attributes_Handler();
		$this->add_theme_requirements();
	}

	/**
	 * Add in the hooks and filters which would have been included in the theme.
	 *
	 * @return void
	 */
	private function add_theme_requirements() {
		// We need to manually include the theme support for html5.
		// This is included in Eutopia_Theme\Components\Foundation.
		add_theme_support( 'html5', [ 'script' ] );
	}

	/**
	 * Replace double quotes with single quotes and remove line breaks and tabs.
	 *
	 * @param string $script The script tag.
	 * @return string The script tag with single quotes and no line breaks or tabs.
	 */
	private function normalize_script_tag( $script ) {

		$script = str_replace( '"', '\'', $script );
		$script = str_replace( [ "\n", "\r", "\t" ], '', $script );

		return $script;
	}

	/**
	 * Call a private method as if it was public.
	 *
	 * @param object|string $object_or_name Object instance or class string to call the method of.
	 * @param string        $method_name    Name of the method to call.
	 * @param array         $args           Optional. Array of arguments to pass to the method.
	 *
	 * @return mixed Return value of the method call.
	 * @throws \ReflectionException If the object could not be reflected upon.
	 */
	private function call_private_method( $object_or_name, $method_name, $args = [] ) {
		$method = ( new \ReflectionClass( $object_or_name ) )->getMethod( $method_name );
		$method->setAccessible( true );

		return $method->invokeArgs( $object_or_name, $args );
	}

	/**
	 * Tests Script_Custom_Attributes_Handler::init
	 *
	 * @covers ::init()
	 */
	public function test_init() {
		$this->assertSame( false, has_filter( 'script_loader_tag', [ $this->instance, 'filter_script_tag' ] ) );
		$this->instance->init();
		$this->assertSame( PHP_INT_MAX, has_filter( 'script_loader_tag', [ $this->instance, 'filter_script_tag' ] ) );
	}

	/**
	 * Provides data for ::test_filter_script_tag.
	 *
	 * @return array
	 */
	public function filter_script_tag_data() {
		return [
			'no_custom_attribute'              => [
				'set_up'   => '__return_null',
				'expected' => "<script src='https://example.com/main.js?ver=1.0.0' id='main-js'></script>",
			],
			'set_defer'                        => [
				'set_up'   => static function () {
					wp_script_add_data( 'main', 'defer', true );
				},
				'expected' => "<script src='https://example.com/main.js?ver=1.0.0' id='main-js' defer></script>",
			],
			'set_async'                        => [
				'set_up'   => static function () {
					wp_script_add_data( 'main', 'async', true );
				},
				'expected' => "<script src='https://example.com/main.js?ver=1.0.0' id='main-js' async></script>",
			],
			'set_async_and_defer'              => [
				'set_up'   => static function () {
					wp_script_add_data( 'main', 'defer', true );
					wp_script_add_data( 'main', 'async', true );
				},
				'expected' => "<script src='https://example.com/main.js?ver=1.0.0' id='main-js' defer></script>",
			],
			'set_type_module'                  => [
				'set_up'   => static function () {
					wp_script_add_data( 'main', 'type', 'module' );
				},
				'expected' => "<script src='https://example.com/main.js?ver=1.0.0' id='main-js' type='module'></script>",
			],
			'set_nomodule'                     => [
				'set_up'   => static function () {
					wp_script_add_data( 'main', 'nomodule', true );
				},
				'expected' => "<script src='https://example.com/main.js?ver=1.0.0' id='main-js' nomodule></script>",
			],
			'set_nomodule_and_type_module'     => [
				'set_up'   => static function () {
					wp_script_add_data( 'main', 'nomodule', true );
					wp_script_add_data( 'main', 'type', 'module' );
				},
				'expected' => "<script src='https://example.com/main.js?ver=1.0.0' id='main-js' type='module'></script>",
			],
			'set_defer_nomodule'               => [
				'set_up'   => static function () {
					wp_script_add_data( 'main', 'defer', true );
					wp_script_add_data( 'main', 'nomodule', true );
				},
				'expected' => "<script src='https://example.com/main.js?ver=1.0.0' id='main-js' defer nomodule></script>",
			],
			'set_async_nomodule'               => [
				'set_up'   => static function () {
					wp_script_add_data( 'main', 'async', true );
					wp_script_add_data( 'main', 'nomodule', true );
				},
				'expected' => "<script src='https://example.com/main.js?ver=1.0.0' id='main-js' async nomodule></script>",
			],
			'handle_attributes_set_via_filter' => [
				'set_up'   => static function () {
					add_filter(
						'script_loader_tag',
						static function ( $tag, $handle ) {
							if ( 'main' === $handle ) {
								return str_replace( '<script ', "<script type='module' async ", $tag );
							}

							return $tag;
						},
						10,
						2
					);
					wp_script_add_data( 'main', 'defer', true );
				},
				'expected' => "<script type='module' src='https://example.com/main.js?ver=1.0.0' id='main-js' defer></script>",
			],
			'filter_only_main_script_tag'      => [
				'set_up'   => static function () {
					wp_add_inline_script( 'main', 'var foo="bar";', 'before' );
					wp_script_add_data( 'main', 'defer', true );
					wp_add_inline_script( 'main', 'var baz="bar";', 'after' );
				},
				'expected' => "
					<script id=\"main-js-before\">\n
					var foo=\"bar\";\n
					</script>\n
					<script src='https://example.com/main.js?ver=1.0.0' id='main-js' defer></script>
					<script id=\"main-js-after\">
					var baz=\"bar\";
					</script>
				",
			],
		];
	}

	/**
	 * Tests Script_Custom_Attributes_Handler::filter_script_tag.
	 *
	 * @dataProvider filter_script_tag_data
	 * @covers ::filter_script_tag()
	 *
	 * @param callable $set_up   Set up function.
	 * @param string   $expected Expected string.
	 */
	public function test_filter_script_tag( callable $set_up, $expected ) {

		wp_enqueue_script( 'main', 'https://example.com/main.js', [], '1.0.0', true );
		$set_up();

		ob_start();

		wp_scripts()->do_item( 'main' );

		$this->assertStringContainsString(
			$this->normalize_script_tag( $expected ),
			$this->normalize_script_tag( ob_get_clean() ),
		);

		wp_deregister_script( 'main' );
	}

	/**
	 * Provides data for ::test_parse_script_attrs.
	 *
	 * @return array
	 */
	public function parse_script_attrs_data() {
		return [
			'parse_single_attr'                    => [
				'attrs_string' => "src='main.js'",
				'expected'     => [
					'src' => 'main.js',
				],
			],
			'parse_multiple_attrs'                 => [
				'attrs_string' => "src='main.js' defer nomodule id='main-js'",
				'expected'     => [
					'src'      => 'main.js',
					'defer'    => '',
					'nomodule' => '',
					'id'       => 'main-js',
				],
			],
			'parse_type_module'                    => [
				'attrs_string' => "src='main.js' type='module'",
				'expected'     => [
					'src'  => 'main.js',
					'type' => 'module',
				],
			],
			'parse_mixed_single_and_double_quotes' => [
				'attrs_string' => "src='main.js' type=\"module\"",
				'expected'     => [
					'src'  => 'main.js',
					'type' => 'module',
				],
			],
		];
	}

	/**
	 * Tests Script_Custom_Attributes_Handler::parse_script_attrs.
	 *
	 * @dataProvider parse_script_attrs_data
	 * @covers ::parse_script_attrs()
	 *
	 * @param string $attrs_string Attributes string.
	 * @param array  $expected     Expected attributes.
	 */
	public function test_parse_script_attrs( $attrs_string, $expected ) {
		$result = $this->call_private_method( $this->instance, 'parse_script_attrs', [ $attrs_string ] );
		$this->assertSame( $expected, $result );
	}

	/**
	 * Provides data for ::test_stringify_script_attrs.
	 *
	 * @return array
	 */
	public function stringify_script_attrs_data() {
		return [
			'stringify_single_attr'    => [
				'attrs'    => [
					'src' => 'main.js',
				],
				'expected' => "src='main.js'",
			],
			'stringify_multiple_attrs' => [
				'attrs'    => [
					'src'      => 'main.js',
					'defer'    => '',
					'nomodule' => 'nomodule',
					'id'       => 'main-js',
				],
				'expected' => "src='main.js' defer nomodule id='main-js'",
			],
			'stringify_type_module'    => [
				'attrs'    => [
					'src'  => 'main.js',
					'type' => 'module',
				],
				'expected' => "src='main.js' type='module'",
			],
			'stringify_boolean_attr'   => [
				'attrs'    => [
					'src'   => 'main.js',
					'defer' => true,
				],
				'expected' => "src='main.js' defer",
			],
		];
	}

	/**
	 * Tests Script_Custom_Attributes_Handler::stringify_script_attrs.
	 *
	 * @dataProvider stringify_script_attrs_data
	 * @covers ::stringify_script_attrs()
	 *
	 * @param array  $attrs    Script tag attributes.
	 * @param string $expected Expected string.
	 */
	public function test_stringify_script_attrs( $attrs, $expected ) {
		$result = $this->call_private_method( $this->instance, 'stringify_script_attrs', [ $attrs ] );
		$this->assertSame( $expected, $result );
	}
}
