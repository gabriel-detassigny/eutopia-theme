/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';

/**
 * Internal dependencies
 */
import initSwiper from './js/init-swiper';

//eslint-disable-next-line no-console
console.log( 'demo-acf-carousel: this is executed only on the frontend' );

domReady( () => {
	document.querySelectorAll( '[data-swiper]' ).forEach( initSwiper );
} );
