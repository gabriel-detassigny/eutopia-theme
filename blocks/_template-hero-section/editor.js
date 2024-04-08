/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import Edit from './js/edit';
import { IconLayout5050 } from './js/icons';
import './editor.scss';

const { name } = metadata;

registerBlockType(
	name,
	{
		icon: <IconLayout5050 viewBox="6 10 34 26" />,
		edit: Edit,
		save: () => null,
	}
);
