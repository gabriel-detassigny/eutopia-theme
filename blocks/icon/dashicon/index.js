/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { registerIcons } from '../api';
import icons from './icons';

export const getDashiconIcons = () => {
	return {
		name: 'core/dashicon',
		label: __( 'Dashicon', 'eutopia-theme' ),
		icons,
	};
};

export const registerDashicon = () => {
	registerIcons( getDashiconIcons() );
};
