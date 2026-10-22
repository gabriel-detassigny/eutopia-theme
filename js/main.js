/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';

/**
 * Internal dependencies
 */
import { StickySiteHeader } from './modules/sticky-site-header';
import { Search } from './modules/search';
import { Navigation } from './modules/navigation';
import { PrimaryMenu } from './modules/primary-menu';

domReady( async () => {
	// Critical UI components.
	StickySiteHeader();
	Search.init();
	Navigation.init();

	// Non-critical UI components.
	setTimeout( PrimaryMenu.init, 0 );

	const overlayTrigger = document.querySelectorAll( '[data-target*="#"]' );
	if ( overlayTrigger.length ) {
		const { OverlayHelper } = await import(
			// webpackChunkName: 'overlay'
			'./modules/overlay-helper' );
		OverlayHelper.init();
	}
} );
