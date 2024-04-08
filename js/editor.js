/**
 * WordPress dependencies
 */
import { globe } from '@wordpress/icons';
import domReady from '@wordpress/dom-ready';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal dependencies
 */
import '../css/editor.scss';

/**
 * Internal dependencies
 */
import { SidebarPlugin } from './modules/sidebar-plugin';
import { UnregisterStyles } from './modules/unregister-styles';

domReady( () => {
	registerPlugin( 'gabriel-detassigny-sidebar-plugin', {
		icon: globe,
		render: SidebarPlugin,
	} );

	UnregisterStyles();
} );
