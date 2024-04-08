/* eslint-disable @wordpress/no-unsafe-wp-apis */

/**
 * WordPress dependencies
 */
import { useSelect, useDispatch } from '@wordpress/data';
import { store as blockEditorStore } from '@wordpress/edit-post';

export function useDeviceType() {
	const deviceType = useSelect( ( select ) => select( blockEditorStore ).__experimentalGetPreviewDeviceType(), [] );
	const { __experimentalSetPreviewDeviceType: setDeviceType } = useDispatch( blockEditorStore );

	return { deviceType, setDeviceType };
}
