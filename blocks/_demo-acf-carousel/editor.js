/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';

/**
 * Internal dependencies
 */
import './editor.scss';
import metadata from './block.json';
import SwiperRegistry from './js/swiper-registry';
import initSwiper from './js/init-swiper';

domReady( () => {
	const swiperRegistry = new SwiperRegistry( {
		parentBlockName: metadata.name,
		swiperClassname: 'swiper',
		initCallback: initSwiper,
	} );

	const observer = new MutationObserver( ( mutationList ) => {
		mutationList.forEach( ( mutation ) => {
			// Initialize Swiper whenever Swiper markup is added to the DOM.
			mutation.addedNodes.forEach( ( element ) => {
				swiperRegistry.add( element );
			} );

			// Destroy Swiper instance whenever block containing Swiper markup is removed from DOM.
			mutation.removedNodes.forEach( ( element ) => {
				swiperRegistry.remove( element );
			} );

			// Update Swiper if attributes changed.
			if ( mutation.type === 'attributes' ) {
				swiperRegistry.reset( mutation.target );
			}
		} );
	} );

	// Start observing the target node for configured mutations
	observer.observe(
		document.querySelector( '#editor' ),
		{
			childList: true,
			subtree: true,
			attributes: true,
			attributeFilter: [ 'data-swiper' ],
		}
	);
} );
