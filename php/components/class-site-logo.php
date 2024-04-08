<?php
/**
 * Eutopia_Theme\Components\Site_Logo class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

use Eutopia_Theme\Theme_Option;

/**
 * Class for handling the site logo.
 */
class Site_Logo extends Component {
	/**
	 * @var Theme_Option Site logo in header.
	 */
	protected Theme_Option $header;

	/**
	 * @var Theme_Option  Site logo in footer.
	 */
	protected Theme_Option $footer;

	/**
	 * @var Theme_Option Display logo only.
	 */
	protected Theme_Option $logo_only;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->header = new Theme_Option(
			$this->get_option_key( 'header' ),
			[
				'label'         => __( 'Header Logo', 'eutopia-theme' ),
				'type'          => 'image',
				'mime_types'    => 'jpg, jpeg, png, svg', // Allowed file types.
				'min_width'     => 100,
				'max_width'     => 500,
				'min_height'    => 100,
				'max_height'    => 500,
				'placeholder'   => '',
				'return_format' => 'ID',
				'instructions'  => sprintf(
					__( 'Upload a logo for site header.', 'eutopia-theme' )
				),
			],
		);

		$this->footer = new Theme_Option(
			$this->get_option_key( 'footer' ),
			[
				'label'         => __( 'Footer Logo', 'eutopia-theme' ),
				'type'          => 'image',
				'mime_types'    => 'jpg, jpeg, png, svg', // Allowed file types.
				'min_width'     => 100,
				'max_width'     => 500,
				'min_height'    => 100,
				'max_height'    => 500,
				'placeholder'   => '',
				'return_format' => 'ID',
				'instructions'  => sprintf(
					__( 'Upload a logo for site footer. (Header logo will be used by default if footer logo is not provided.)', 'eutopia-theme' ),
				),
			],
		);

		$this->logo_only = new Theme_Option(
			$this->get_option_key( 'logo_only' ),
			[
				'label'         => __( 'Display Logo Only', 'eutopia-theme' ),
				'type'          => 'true_false',
				'ui'            => 1, // Display the "On" and "Off" labels for the checkbox.
				'default_value' => 0,
				'instructions'  => sprintf(
					__( 'Toggle display screen reader text for site title and tagline.', 'eutopia-theme' )
				),
			],
		);

		$this->register_theme_options(
			__( 'Site Logo', 'eutopia-theme' ),
			[
				$this->header,
				$this->footer,
				$this->logo_only,
			],
		);
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {}

	/**
	 * Check if show logo only or title/tagline as well.
	 *
	 * @return bool
	 */
	public function show_logo_only(): bool {
		return $this->logo_only->get();
	}

	/**
	 * Check if header logo is set.
	 *
	 * @return bool
	 */
	public function has_header_logo(): bool {
		return ! empty( $this->get_header_logo() );
	}

	/**
	 * Check if footer logo is set.
	 *
	 * @return bool
	 */
	public function has_footer_logo(): bool {
		return ! empty( $this->get_footer_logo() );
	}

	/**
	 * Get header logo field value.
	 *
	 * @return string Logo attachment ID.
	 */
	public function get_header_logo(): string {
		return $this->header->get();
	}

	/**
	 * Get footer logo field value.
	 *
	 * @return string Logo attachment ID.
	 */
	public function get_footer_logo(): string {
		return $this->footer->get();
	}

	/**
	 * Render header logo.
	 *
	 * @param string $size Image size.
	 */
	public function render_header_logo( $size = 'full' ): void {
		if ( $this->has_header_logo() ) {
			echo wp_get_attachment_image( $this->get_header_logo(), $size, false, [ 'class' => 'branding-logo' ] );
		}
	}

	/**
	 * Render footer logo.
	 *
	 * @param array $size Image size.
	 */
	public function render_footer_logo( $size = [ 64, 64 ] ): void {
		if ( $this->has_footer_logo() ) {
			echo wp_get_attachment_image( $this->get_footer_logo(), $size, false, [ 'class' => 'branding-logo' ] );
		}
	}
}
