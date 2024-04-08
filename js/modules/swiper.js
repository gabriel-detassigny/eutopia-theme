/**
 * External dependencies
 */
import Swiper from 'swiper';

export async function initSwiper( { container, swiperWrapper = null, swiperItems, prevButton, nextButton, pagination = null, settings } ) {
	// Setup container
	if ( ! container.classList.contains( 'swiper' ) ) {
		container.classList.add( 'swiper' );
	}

	// Setup swiper items wrapper
	if ( swiperWrapper && ! swiperWrapper.classList.contains( 'swiper-wrapper' ) ) {
		swiperWrapper.classList.add( 'swiper-wrapper' );
	}

	// Setup swiper items
	[ ...swiperItems ].forEach( ( swiperItem ) => {
		if ( ! swiperItem.classList.contains( 'swiper-slide' ) ) {
			swiperItem.classList.add( 'swiper-slide' );
		}
	} );

	if ( prevButton ) {
		prevButton.addEventListener( 'click', ( event ) => {
			event.preventDefault();
			swiper.slidePrev();
		} );
	}

	if ( nextButton ) {
		nextButton.addEventListener( 'click', ( event ) => {
			event.preventDefault();
			swiper.slideNext();
		} );
	}

	const updateNav = () => {
		if ( prevButton ) {
			prevButton.disabled = swiper.isBeginning;
		}

		if ( nextButton ) {
			nextButton.disabled = swiper.isEnd;
		}
	};

	const args = {
		modules: [],
		on: {
			init: () => {
				// Remove horizontal scroll from swiper wrapper when swiper is initialized.
				// The overflowX is added initially for fallback.
				swiperWrapper.style.overflowX = 'initial';

				// Remove flex gap from swiper wrapper when swiper is initialized.
				// The reason being, if we use flex gap and do not use spaceBetween property of Swiper, Swiper does not consider the gap while calculating slide item width -
				// - causing issues (showing a part of slide of previous page).
				// If we use spaceBetween property along with gap, then gap + spaceBetween value is applied between the items causing more space between two items which is not desired.

				// Could be done via CSS as well, but I did not want to add reference of swiper in carousel CSS.
				swiperWrapper.style.gap = '0px';
			},
			afterInit: () => setTimeout( updateNav ),
			slideChange: updateNav,
		},
		speed: 400,
		slidesPerGroup: 1,
		grabCursor: true,
		...settings,
	};

	if ( null !== pagination ) {
		const { Pagination } = await import(
			// webpackChunkName: 'swiper'
			'swiper/modules' );

		args.modules.push( Pagination );

		args.pagination = {
			el: pagination,
			clickable: true,
		};
	}

	const swiper = new Swiper( container, args );
}

