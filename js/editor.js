/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';

/**
 * Internal dependencies
 */
import '../css/editor.scss';
import { UnregisterStyles } from './modules/unregister-styles';

domReady( () => {
	UnregisterStyles();
} );
