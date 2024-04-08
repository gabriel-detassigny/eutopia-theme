/**
 * External dependencies
 */
import classNames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	BaseControl,
	Button,
	ButtonGroup,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import { useDeviceType } from '../js/hooks/use-device-type';
import { RESPONSIVE_DEVICES } from '../js/constants';

export default function ResponsiveControls( { devices = RESPONSIVE_DEVICES, children } ) {
	const { deviceType, setDeviceType } = useDeviceType();
	return (
		<>
			<div className="eutopia-theme-components-panel__title-bar">
				<BaseControl>
					<BaseControl.VisualLabel className="eutopia-theme-components-panel__label">{ __( 'Device', 'eutopia-theme' ) }</BaseControl.VisualLabel>
					<ButtonGroup className="eutopia-theme-responsive-controls">
						{ devices.map( ( { deviceName, iconComp, ...rest } ) => {
							const classes = classNames( rest?.deviceClass, 'eutopia-theme-responsive-control', {
								active: deviceName === deviceType,
							} );

							return (
								<Button key={ deviceName } className={ classes } label={ deviceName } onClick={ () => setDeviceType( deviceName ) } { ...rest }>
									{ iconComp }
								</Button>
							);
						} ) }
					</ButtonGroup>
				</BaseControl>
			</div>
			{ children }
		</>
	);
}
