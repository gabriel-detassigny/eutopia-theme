/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import {
	FontSizePicker,
	PanelBody,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import ResponsiveControls from '../components/responsive-controls';
import { SPACING_OPTIONS } from './constants';

export default function SpacerControls( {
	height,
	spacingChange,
} ) {
	return (
		<InspectorControls>
			<PanelBody title={ __( 'Settings', 'eutopia-theme' ) }>
				<ResponsiveControls>
					<FontSizePicker
						fontSizes={ SPACING_OPTIONS }
						value={ height }
						fallbackFontSize="m" // Redundant, but required. The default value is set in the block.json.
						disableCustomFontSizes={ true }
						onChange={ spacingChange }
						__nextHasNoMarginBottom
					/>
				</ResponsiveControls>
			</PanelBody>
		</InspectorControls>
	);
}
