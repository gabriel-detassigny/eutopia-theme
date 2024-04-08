/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import edit from './js/edit';
import './editor.scss';

registerBlockType(
	metadata.name,
	{
		edit,
	}
);
