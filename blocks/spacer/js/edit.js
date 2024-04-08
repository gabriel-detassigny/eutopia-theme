/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import SpacerControls from './controls';
import { useDeviceType } from './hooks/use-device-type';
import { DEVICE_TYPE_ATTR_MAPPING } from './constants';

const SpacerEdit = ( {
	attributes,
	setAttributes,
} ) => {
	const { deviceType } = useDeviceType();
	const deviceTypeAttribute = DEVICE_TYPE_ATTR_MAPPING[ deviceType ];
	const heightValue = attributes[ deviceTypeAttribute ] ?? attributes.height;

	const style = {
		height: `var(--spacing--${ attributes[ deviceTypeAttribute ] })`,
	};

	const handleSpacingChange = ( value ) => {
		setAttributes( { [ deviceTypeAttribute ]: value } );
	};

	return (
		<>
			<div { ...useBlockProps( { style } ) }></div>
			<SpacerControls
				spacingChange={ handleSpacingChange }
				height={ heightValue }
			/>
		</>
	);
};

export default SpacerEdit;
