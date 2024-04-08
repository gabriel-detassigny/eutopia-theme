/**
 * WordPress dependencies
 */
import * as Components from '@wordpress/components';

/**
 * Internal dependencies
 */
import registerCustomControls from '../custom-controls';
import { validateCondition } from '../utils';

const CustomControls = registerCustomControls();

export default function Controls( props ) {
	const { attributes, setAttributes, controls, sourceTypes } = props;

	return (
		controls.map( ( control, controlIndex ) => {
			const { controlType, valueProp, changeProp, controlProps, slug, conditions } = control;
			const Control = CustomControls[ controlType ] ?? Components[ controlType ] ?? Components.TextControl;
			const newProps = { ...{ attributes, sourceTypes }, ...controlProps, CustomControls };

			// Set the change property.
			newProps[ changeProp ] = ( ( value ) => setAttributes( { [ slug ]: value } ) );
			newProps[ valueProp ] = attributes[ slug ] ?? control.default;

			if ( conditions && ! validateCondition( conditions, attributes ) ) {
				return null;
			}

			if ( controlProps.experimental && controlProps.experimental.length ) {
				controlProps.experimental.forEach( ( item ) => {
					newProps[ item ] = true;
				} );
				delete newProps.experimental;
			}

			return (
				<Control key={ `custom-control-${ slug }-${ controlIndex }` } { ...newProps } />
			);
		} )
	);
}

