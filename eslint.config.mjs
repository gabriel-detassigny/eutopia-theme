/**
 * External dependencies
 */
import globals from 'globals';

/**
 * WordPress dependencies
 */
import wordpress from '@wordpress/eslint-plugin';

export default [
	{
		ignores: [ 'build/', 'vendor/', 'tools/wordpress/', 'tools/wp-content/' ],
	},
	...wordpress.configs[ 'recommended-with-formatting' ],
	{
		files: [ 'js/**' ],
		languageOptions: {
			globals: globals.browser,
		},
	},
	{
		rules: {
			'@wordpress/no-global-event-listener': 'off',
			'jsdoc/check-indentation': 'error',
			'@wordpress/dependency-group': 'error',
			'jsdoc/require-param': 'off',
			'jsdoc/require-param-type': 'off',
			'jsdoc/check-param-names': 'off',
		},
	},
];
