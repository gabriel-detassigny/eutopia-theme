/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Dashicon } from '@wordpress/components';

export const DEVICE_NAMES = {
	desktop: __( 'Desktop', 'eutopia-theme' ),
	tablet: __( 'Tablet', 'eutopia-theme' ),
	mobile: __( 'Mobile', 'eutopia-theme' ),
};

export const RESPONSIVE_DEVICES = [
	{
		deviceName: DEVICE_NAMES.desktop,
		iconComp: <Dashicon icon="desktop" size="24" />,
	},
	{
		deviceName: DEVICE_NAMES.tablet,
		iconComp: <Dashicon icon="tablet" size="24" />,
	},
	{
		deviceName: DEVICE_NAMES.mobile,
		iconComp: <Dashicon icon="smartphone" size="24" />,
	},
];

export const SPACING_OPTIONS = [
	{
		size: 's',
		name: __( 'Small', 'eutopia-theme' ),
		slug: 'small',
	},
	{
		size: 'm',
		name: __( 'Medium', 'eutopia-theme' ),
		slug: 'medium',
	},
	{
		size: 'l',
		name: __( 'Large', 'eutopia-theme' ),
		slug: 'large',
	},
	{
		size: 'xl',
		name: __( 'Extra Large', 'eutopia-theme' ),
		slug: 'extra-large',
	},
];

export const DEVICE_TYPE_ATTR_MAPPING = {
	[ DEVICE_NAMES.desktop ]: 'height',
	[ DEVICE_NAMES.tablet ]: 'tabletHeight',
	[ DEVICE_NAMES.mobile ]: 'mobileHeight',
};
