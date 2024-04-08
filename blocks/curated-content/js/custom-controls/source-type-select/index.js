/**
 * WordPress dependencies
 */
import { SelectControl } from '@wordpress/components';

export const SourceTypeSelect = ( props ) => {
	const { value, onChange, attributes: { source }, sourceTypes } = props;
	const newProps = { ...props };
	newProps.options = props.options.filter( ( option ) => {
		if ( option.sourceType && option.sourceType !== sourceTypes[ source ] ) {
			if ( value === option.value ) {
				onChange( '' );
			}
			return false;
		}
		return true;
	} );

	return (
		<SelectControl { ...newProps } />
	);
};
