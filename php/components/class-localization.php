<?php
/**
 * Eutopia_Theme\Components\Localization class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Class for managing localization.
 */
class Localization extends Component {


	/**
	 * Absolute path to the translation directory.
	 *
	 * @var string
	 */
	public $translation_directory = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Define the translation directory.
		$this->translation_directory = gdt_theme_meta()->path( 'languages' );
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'action_load_textdomain' ], 1 );
	}

	/**
	 * Loads the theme textdomain.
	 *
	 * @return void
	 */
	public function action_load_textdomain() {
		/*
		 * Make the theme available for translation. Translations can be filed in the /languages/ directory.
		 *
		 * If you want to distribute your theme on wordpress.org and use their language packs feature, you
		 * should not bundle translations in your theme. In that case you also need to get rid of the
		 * second parameter in the following function call.
		 */
		load_theme_textdomain( 'eutopia-theme', $this->translation_directory );
	}
}
