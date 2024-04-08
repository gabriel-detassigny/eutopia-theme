/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import Edit from './js/edit';

//eslint-disable-next-line no-console
console.log( 'demo-dynamic-block: this is executed only in the editor' );

registerBlockType(
	metadata.name,
	{
		edit: Edit,
	}
);
