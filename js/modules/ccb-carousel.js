/**
 * Internal dependencies
 */
import { initSwiper } from './swiper';

/**
 * Handles Carousel Block slider.
 */
export const CCBCarousel = {
	init() {
		const carousels = document.querySelectorAll( '.c-ccb--entry-carousel' );

		carousels.forEach( ( carousel ) => {
			const container = carousel.querySelector( '.l-entry-carousel' );
			const swiperWrapper	= carousel.querySelector( '.l-entry-carousel__wrapper' );
			const swiperItems	= carousel.querySelectorAll( '.l-entry-carousel__item' );
			const prevButton	= carousel.querySelector( '.l-entry-carousel__nav--prev' );
			const nextButton	= carousel.querySelector( '.l-entry-carousel__nav--next' );
			const pagination	= parseInt( container.dataset.pagination ) ? carousel.querySelector( '.l-entry-carousel__pagination' ) : null;

			const itemCountPerView = parseInt( container.dataset.columns ) || 3;

			const settings = {
				slidesPerView: 1.5, // Show 1 full card and part of next card below tablet breakpoint.
				slidesPerGroup: 1,
				spaceBetween: 16, // Same as the CSS grid gap applied to the carousel wrapper.
				breakpoints: {
					792: { // Tablet breakpoint in the theme.
						slidesPerView: itemCountPerView,
						slidesPerGroup: itemCountPerView,
					},
				},
			};

			const swiperArgs = {
				container,
				swiperWrapper,
				swiperItems,
				prevButton,
				nextButton,
				pagination,
				settings,
			};

			// Initialize Swiper JS.
			initSwiper( swiperArgs );
		} );
	},
};
