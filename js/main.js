/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';

/**
 * Internal dependencies
 */
import { maybePolyfillRequestIdleCallback } from './modules/polyfills';
import { ModuleName } from './modules/module-name';
import { StickySiteHeader } from './modules/sticky-site-header';
import { Search } from './modules/search';
import { LazyComponent } from './modules/lazy-component';
import { Navigation } from './modules/navigation';
import { PrimaryMenu } from './modules/primary-menu';

domReady( async () => {
	// Polyfills
	await maybePolyfillRequestIdleCallback();

	// Critical UI components.
	ModuleName();
	StickySiteHeader();
	Search.init();
	Navigation.init();

	// Non-critical UI components.
	setTimeout( PrimaryMenu.init, 0 );

	// Nice to have components.
	requestIdleCallback( LazyComponent.init );

	// Load dynamic modules on interaction / when the DOM elements requiring it are present on the page.
	setTimeout( async () => {
		const { DynamicModuleExample } = await import( './modules/dynamic-module-example.js' );
		DynamicModuleExample.init();
	}, 1000 );

	const overlayTrigger = document.querySelectorAll( '[data-target*="#"]' );
	if ( overlayTrigger.length ) {
		const { OverlayHelper } = await import(
			// webpackChunkName: 'overlay'
			'./modules/overlay-helper' );
		OverlayHelper.init();
	}

	const infiniteScrollPaginationTrigger = document.querySelector( '.infinite-scroll-pagination' );
	if ( infiniteScrollPaginationTrigger ) {
		const { InfiniteScrollPagination } = await import(
			// webpackChunkName: 'infinite-scroll-pagination'
			'./modules/infinite-scroll-pagination' );
		InfiniteScrollPagination.init();
	}

	const infiniteScrollSingleTrigger = document.querySelector( '.infinite-scroll-single' );
	if ( infiniteScrollSingleTrigger ) {
		const { InfiniteScrollSingle } = await import(
			// webpackChunkName: 'infinite-scroll-single'
			'./modules/infinite-scroll-single' );
		InfiniteScrollSingle.init();
	}
} );
