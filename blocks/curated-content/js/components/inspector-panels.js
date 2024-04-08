/**
 * WordPress dependencies
 */
import { PanelBody } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { validateCondition } from '../utils';
import Controls from './inspector-controls';

export default function Panels( props ) {
	const { panels, attributes } = props;

	return (
		Object.entries( panels ).map( ( [ slug, panel ], panelIndex ) => {
			const { title, initialOpen, controls, conditions } = panel;

			if ( ! controls.length || ( conditions && ! validateCondition( conditions, attributes ) ) ) {
				return null;
			}
			return (
				<PanelBody
					key={ `custom-panel-${ slug }-${ panelIndex }` }
					title={ title }
					initialOpen={ initialOpen ?? false }
				>
					<Controls { ...props } controls={ controls } />
				</PanelBody>
			);
		} )
	);
}

