/**
 * WordPress dependencies
 */
import { ColorPalette, PanelRow } from '@wordpress/components';

export const Color = ( props ) => {
	const { label } = props;
	return (
		<>
			<h4>{ label }</h4>
			<PanelRow>
				<ColorPalette { ...props } />
			</PanelRow>
		</>
	);
};
