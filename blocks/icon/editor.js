/**
 * Block type editor script definition.
 * It will only be enqueued in the context of the editor.
 */

/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import Edit from './edit';
import Save from './save';

// Stylesheets.
import './editor.scss';
import { registerDashicon } from './dashicon';

registerBlockType(
	metadata.name,
	{
		edit: Edit,
		save: Save,
	}
);

domReady( () => {
	registerDashicon();
} );
