/**
 * WordPress dependencies
 */
import { column as icon } from '@wordpress/icons';
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import Edit from './js/edit';
import metadata from './block.json';
import Save from './js/save';

registerBlockType(
	metadata.name,
	{
		edit: Edit,
		save: Save,
		icon,
	}
);
