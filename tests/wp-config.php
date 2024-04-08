<?php
/**
 * WP config file used during the unit tests.
 * Note that wp-env replaces this with its own config file.
 *
 * @package Eutopia_Theme
 *
 * phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited
 */

// Load wp-env config, if present.
if ( is_readable( '/wordpress-phpunit/wp-tests-config.php' ) ) {
	require_once '/wordpress-phpunit/wp-tests-config.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	return;
}

define( 'ABSPATH', dirname( __DIR__ ) . '/tools/wordpress/' );

define( 'WP_DEFAULT_THEME', basename( dirname( __DIR__ ) ) );
define( 'WP_DEBUG', true );

define( 'DB_NAME', getenv( 'WP_DB_NAME' ) ? getenv( 'WP_DB_NAME' ) : 'wp_phpunit_tests' );
define( 'DB_USER', getenv( 'WP_DB_USER' ) ? getenv( 'WP_DB_USER' ) : 'root' );
define( 'DB_PASSWORD', getenv( 'WP_DB_PASS' ) ? getenv( 'WP_DB_PASS' ) : '' );
define( 'DB_HOST', getenv( 'WP_DB_HOST' ) ? getenv( 'WP_DB_HOST' ) : 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

$table_prefix = 'wpphpunittests_';

define( 'WP_TESTS_DOMAIN', 'example.org' );
define( 'WP_TESTS_EMAIL', 'admin@example.org' );
define( 'WP_TESTS_TITLE', 'Test Blog' );

define( 'WP_PHP_BINARY', 'php' );

define( 'WPLANG', '' );
