/**
 * WordPress dependencies
 */
import { unregisterBlockStyle } from '@wordpress/blocks';

const BLOCK_STYLES = [
	{
		block: 'core/button',
		styles: [
			'fill',
			'outline',
		],
	},
	{
		block: 'core/quote',
		styles: [
			'default',
			'plain',
		],
	},
	{
		block: 'core/separator',
		styles: [
			'default',
			'wide',
			'dots',
		],
	},
];

export function UnregisterStyles() {
	BLOCK_STYLES.forEach( ( { block, styles } ) => {
		styles.forEach( ( style ) => {
			unregisterBlockStyle( block, style );
		} );
	} );
}
