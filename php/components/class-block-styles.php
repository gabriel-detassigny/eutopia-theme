<?php
/**
 * Eutopia_Theme\Components\Block_Styles class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Class for managing block stylesheets.
 */
class Block_Styles extends Component {


	/**
	 * List of style handles outside the "allowed list"
	 *
	 * WordPress will enqueue these even if we're not whitelisting them
	 *
	 * @var array
	 */
	private $styles_to_deregister = [
		'wp-block-library',
		'wp-block-library-theme',
	];

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'action_deregister_block_styles' ] );
	}

	/**
	 * Get core (non-block) styles to deregister.
	 *
	 * @return array
	 */
	public function get_static_styles_to_deregister() {
		return $this->styles_to_deregister;
	}

	/**
	 * Executed by the action `wp_enqueue_scripts` to deregister block styles.
	 *
	 * @return void
	 */
	public function action_deregister_block_styles() {
		$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

		// Deregister static items.
		foreach ( $this->styles_to_deregister as $handle ) {
			wp_deregister_style( $handle );
		}

		// Deregister dynamic items.
		foreach ( $registered_blocks as $key => $_ ) {
			$handle = $this->create_handle( $key );
			wp_deregister_style( $handle );
		}
	}

	/**
	 * Turn block's name into a handle readable by wp_deregister_style
	 *
	 * @param string $block_name Block's name.
	 *
	 * @return string Readable handle.
	 */
	public function create_handle( $block_name ) {
		if ( strpos( $block_name, 'core/' ) !== 0 ) {
			return '';
		}

		return str_replace( 'core/', 'wp-block-', $block_name );
	}
}
