/**
 * External dependencies
 */
import Swiper from 'swiper';
import { Scrollbar } from 'swiper/modules';

function initSwiper( element ) {
	let options = {};

	if ( element?.dataset?.swiper ) {
		options = JSON.parse( element.dataset.swiper );
	}

	return new Swiper( element, {
		scrollbar: {
			el: '.swiper-scrollbar',
			draggable: true,
		},
		modules: [ Scrollbar ],
		...options,
	} );
}

export default initSwiper;
