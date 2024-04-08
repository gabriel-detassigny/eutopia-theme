<?php
/**
 * Images tests.
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Test_Images Class.
 *
 * @coversDefaultClass \Eutopia_Theme\Components\Images
 */
class Test_Images extends \WP_UnitTestCase {
	/**
	 * Test image path.
	 *
	 * `test-image-large.jpg` intrinsic size is 3000x2250px.
	 *
	 * @link https://core.trac.wordpress.org/browser/branches/6.1/tests/phpunit/data/images/test-image-large.jpg
	 * @var string
	 */
	const TEST_IMAGE_PATH = __DIR__ . '/../../data/images/test-image-large.jpg';

	/**
	 * Test class instance.
	 *
	 * @var Images
	 */
	private Images $instance;

	/**
	 * Test attachment ID.
	 *
	 * @var int|null
	 */
	private static ?int $attachment_id;

	/**
	 * Setup run once before all tests in the suite.
	 *
	 * Creates a test attachment if one does not already exist. This is done in a static method for performance reasons.
	 *
	 * @return void
	 */
	public static function set_up_before_class() {
		parent::set_up_before_class();

		if ( isset( self::$attachment_id ) ) {
			return;
		}

		// Check if the uploads directory is writable. If not, some test will be skipped.
		$uploads_dir = wp_upload_dir();
		if ( isset( $uploads_dir['error'] ) && false !== $uploads_dir['error'] ) {
			self::$attachment_id = null;
			return;
		}

		self::$attachment_id = self::factory()->attachment->create_upload_object(
			self::TEST_IMAGE_PATH,
			self::factory()->post->create_and_get()->ID,
		);
	}

	/**
	 * Tear down run once after all tests in the suite.
	 *
	 * @return void
	 */
	public static function tear_down_after_class() {
		// This must be called before `parent::tear_down_after_class()`. If not, `self::$attachment_id` will be null by
		// the time we're attempting to delete the attachment and new attachments are going to be created on every run.
		if ( isset( self::$attachment_id ) && null !== self::$attachment_id ) {
			wp_delete_attachment( self::$attachment_id, true );
		}

		parent::tear_down_after_class();
	}

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function set_up() {
		parent::set_up();

		$this->instance = new Images();
	}

	/**
	 * Tests Images::init
	 *
	 * @covers ::init()
	 */
	public function test_init() {
		$this->assertFalse( has_filter( 'wp_content_img_tag', [ $this->instance, 'filter_content_image_tag' ] ) );
		$this->assertFalse(
			has_filter(
				'wp_get_attachment_image',
				[
					$this->instance,
					'alter_attachment_image_attributes',
				],
			)
		);
		$this->instance->init();
		$this->assertSame( 10, has_filter( 'wp_content_img_tag', [ $this->instance, 'filter_content_image_tag' ] ) );
		$this->assertSame(
			10,
			has_filter(
				'wp_get_attachment_image',
				[
					$this->instance,
					'alter_attachment_image_attributes',
				],
			)
		);
	}

	/**
	 * Tests Images::inline_custom_image_sizes_css
	 *
	 * @covers ::inline_custom_image_sizes_css()
	 */
	public function test_inline_custom_image_sizes_css() {
		$this->assertFalse( wp_style_is( Images::INLINE_STYLESHEET_HANDLE ) );
		$this->instance->inline_custom_image_sizes_css();
		$this->assertTrue( wp_style_is( Images::INLINE_STYLESHEET_HANDLE ) );
	}

	/** @return array */
	public function get_data_for_test_alter_attachment_image_attributes(): array {
		return [
			'1:1'  => [
				Images::RATIO_1X1,
				[
					'384w'  => '384x384',
					'768w'  => '768x768',
					'1024w' => '1024x1024',
					'1536w' => '1536x1536',
					'2048w' => '2048x2048',
				],
				'size-1:1',
			],
			'4:3'  => [
				Images::RATIO_4X3,
				[
					'384w'  => '384x288',
					'768w'  => '768x576',
					'1024w' => '1024x768',
					'1536w' => '1536x1152',
					'2048w' => '2048x1536',
				],
				'size-4:3',
			],
			'3:4'  => [
				Images::RATIO_3X4,
				[
					'384w'  => '384x512',
					'768w'  => '768x1024',
					'1024w' => '1024x1365',
					'1536w' => '1536x2048',
					// `test-image-large.jpg` is 3000x2250px so the srcset is expected to be capped at those dimensions.
					'2048w' => '2048x2250',
				],
				'size-3:4',
			],
			'16:9' => [
				Images::RATIO_16X9,
				[
					'384w'  => '384x216',
					'768w'  => '768x432',
					'1024w' => '1024x576',
					'1536w' => '1536x864',
					'2048w' => '2048x1152',
				],
				'size-16:9',
			],
		];
	}

	/**
	 * Tests Images::alter_attachment_image_attributes
	 *
	 * @param string $size                    Image size.
	 * @param array  $expected_sources        Expected sources.
	 * @param string $expected_size_classname Expected size classname.
	 *
	 * @dataProvider get_data_for_test_alter_attachment_image_attributes()
	 * @covers ::alter_attachment_image_attributes()
	 */
	public function test_alter_attachment_image_attributes( string $size, array $expected_sources, string $expected_size_classname ) {
		if ( ! isset( self::$attachment_id ) || null === self::$attachment_id ) {
			$this->markTestSkipped( 'Attachment could not be created.' );
		}

		$this->instance->init();

		$dummy_img    = '<img src="#" alt="" />';
		$altered_html = $this->instance->alter_attachment_image_attributes( $dummy_img, self::$attachment_id, $size );

		$tag_processor = new \WP_HTML_Tag_Processor( $altered_html );
		$tag_processor->next_tag();

		// Assert a size classname has been added.
		$this->assertStringContainsString(
			$expected_size_classname,
			$tag_processor->get_attribute( 'class' ),
			'Expected size classname has been added to the image tag'
		);

		// Explode `srcset` it into individual sources and extract the relevant data from it.
		$srcset  = $tag_processor->get_attribute( 'srcset' );
		$srcset  = explode( ', ', $srcset );
		$sources = [];
		foreach ( $srcset as $single_srcset ) {
			// This matches the image size and the width descriptor, i.e. the `3000x2250px` and `3000w` parts in `test-image-large-3000x2250.jpg 3000w`.
			preg_match( '/-(\d+x\d+)\.\S*\s+(\d+w)/', $single_srcset, $matches );
			$sources[ $matches[2] ] = $matches[1];
		}

		// Assert sources are correct.
		$this->assertSameSets( $expected_sources, $sources, 'Srcset matches the definition' );

		// Assert src, width, and height attributes have been set.
		$test_image_filename = pathinfo( self::TEST_IMAGE_PATH, PATHINFO_FILENAME );
		$this->assertStringContainsString(
			$test_image_filename,
			$tag_processor->get_attribute( 'src' ),
			'Src attribute has been set'
		);

		$this->assertNotEmpty( $tag_processor->get_attribute( 'width' ), 'Has width' );
		$this->assertNotEmpty( $tag_processor->get_attribute( 'height' ), 'Has height' );
	}

	/** @return array */
	public function get_data_for_test_get_sizes_attr(): array {
		return [
			'no_sizes'                       => [
				[],
				'100vw',
			],
			'only_zero_size'                 => [
				[
					0 => '50vw',
				],
				'50vw',
			],
			'only_one_nonzero_size'          => [
				[
					300 => '50vw',
				],
				'(min-width: 300px) 50vw, 100vw',
			],
			'only_one_nonzero_size_at_100vw' => [
				[
					300 => '100vw',
				],
				'100vw',
			],
			'asc_order'                      => [
				[
					0   => '100vw',
					300 => '60vw',
					600 => '30vw',
				],
				'(min-width: 600px) 30vw, (min-width: 300px) 60vw, 100vw',
			],
			'desc_order'                     => [
				[
					600 => '30vw',
					300 => '60vw',
					0   => '100vw',
				],
				'(min-width: 600px) 30vw, (min-width: 300px) 60vw, 100vw',
			],
			'unordered'                      => [
				[
					600 => '30vw',
					0   => '100vw',
					300 => '60vw',
				],
				'(min-width: 600px) 30vw, (min-width: 300px) 60vw, 100vw',
			],
		];
	}

	/**
	 * Tests Images::get_sizes_attr
	 *
	 * @param array  $sizes               Sizes array.
	 * @param string $expected_sizes_attr Expected sizes attribute.
	 *
	 * @dataProvider get_data_for_test_get_sizes_attr()
	 * @covers ::get_sizes_attr()
	 */
	public function test_get_sizes_attr( array $sizes, string $expected_sizes_attr ) {
		$this->assertSame( $expected_sizes_attr, $this->instance->get_sizes_attr( $sizes ) );
	}
}
