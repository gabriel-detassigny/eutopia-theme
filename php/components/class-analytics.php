<?php
/**
 * Eutopia_Theme\Components\Analytics class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use Eutopia_Theme\Theme_Option;

/**
 * Class Analytics.
 */
class Analytics extends Component {
	/**
	 * Google Analytics 4 ID field name.
	 *
	 * @var string
	 */
	const ANALYTICS_SCRIPT_ID = 'analytics_script';

	/**
	 * Theme option for Google Analytics (GA4) ID.
	 *
	 * @var Theme_Option
	 */
	protected Theme_Option $ga4_id;

	/**
	 * Theme option for Google Universal Analytics ID.
	 *
	 * @var Theme_Option
	 */
	protected Theme_Option $ua_id;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->ga4_id = new Theme_Option(
			$this->get_option_key( 'ga4_id' ),
			[
				'label'        => __( 'Google Analytics 4 ID', 'eutopia-theme' ),
				'type'         => 'text',
				'required'     => 0,
				'maxlength'    => 12,
				'placeholder'  => __( 'G-XXXXXXXXXX', 'eutopia-theme' ),
				'instructions' => sprintf(
					__( 'The format of the ID is G-1234567890. Leaving this blank disables the functionality.', 'eutopia-theme' )
				),
			],
			[ $this, 'validate_ga4_id' ],
		);

		$this->ua_id = new Theme_Option(
			$this->get_option_key( 'ua_id' ),
			[
				'label'        => __( 'Google Universal Analytics ID', 'eutopia-theme' ),
				'type'         => 'text',
				'required'     => 0,
				'maxlength'    => 14,
				'placeholder'  => __( 'UA-XXXXXXXX-XX', 'eutopia-theme' ),
				'instructions' => sprintf(
				// Translators: URL to Google support article.
					__( 'The format of the ID is UA-123456-7. Leaving this blank disables the functionality.</br><a href="%s" target="_blank">Read deprecation notice</a>.', 'eutopia-theme' ),
					esc_url( 'https://support.google.com/analytics/answer/11583528?hl=en' )
				),
			],
			[ $this, 'validate_ua_id' ],
		);

		$this->register_theme_options(
			__( 'Analytics', 'eutopia-theme' ),
			[
				$this->ga4_id,
				$this->ua_id,
			],
		);
	}

	/**
	 * Register any needed hooks/filters.
	 */
	public function init(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'add_analytics_code' ] );
	}

	/**
	 * Validate GA4 ID ACF field.
	 *
	 * @link https://www.advancedcustomfields.com/resources/acf-validate_value/
	 *
	 * @param mixed $valid Whether or not the value is valid (boolean) or a custom error message (string).
	 * @param mixed $value The field value.
	 *
	 * @return bool|string Returns either true on valid, or error message on invalid.
	 */
	public function validate_ga4_id( $valid, $value ) {
		// Bail early if value is already invalid.
		if ( true !== $valid ) {
			return $valid;
		}

		// If value is not submitted, pass.
		if ( empty( $value ) ) {
			return true;
		}

		if ( preg_match( '/\b(G-[A-Z0-9]{7,10})\b/x', $value ) ) {
			return true;
		}

		return __( 'Please insert a valid ID.', 'eutopia-theme' );
	}

	/**
	 * Validate UA ID ACF field.
	 *
	 * @param mixed $valid Whether or not the value is valid (boolean) or a custom error message (string).
	 * @param mixed $value The field value.
	 *
	 * @return bool|string Returns either true on valid, or error message on invalid.
	 */
	public function validate_ua_id( $valid, $value ) {
		// Bail early if value is already invalid.
		if ( true !== $valid ) {
			return $valid;
		}

		// If value is not submitted, pass.
		if ( empty( $value ) ) {
			return true;
		}

		if ( preg_match( '/\b(UA-[A-Z0-9]{5,8}-[A-Z0-9]{1,2})\b/x', $value ) ) {
			return true;
		}

		return __( 'Please insert a valid ID.', 'eutopia-theme' );
	}

	/**
	 * Add analytics code to the site footer.
	 *
	 * @return void
	 */
	public function add_analytics_code(): void {
		// Use GA4 in the first place.
		$analytics_id = $this->ga4_id->get();

		if ( empty( $analytics_id ) ) {
			$analytics_id = $this->ua_id->get();
		}

		if ( empty( $analytics_id ) ) {
			return;
		}

		wp_register_script(
			self::ANALYTICS_SCRIPT_ID,
			add_query_arg(
				[
					'id' => $analytics_id,
				],
				'https://www.googletagmanager.com/gtag/js'
			),
			[],
			null, // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			false
		);

		wp_script_add_data( self::ANALYTICS_SCRIPT_ID, 'defer', true );
		wp_enqueue_script( self::ANALYTICS_SCRIPT_ID );
		wp_add_inline_script(
			self::ANALYTICS_SCRIPT_ID,
			sprintf(
				'window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag("js", new Date()); gtag("config", %s);',
				wp_json_encode( $analytics_id ),
			),
		);
	}
}
