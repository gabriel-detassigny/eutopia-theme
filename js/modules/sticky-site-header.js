/**
 * External dependencies
 */
import throttle from 'lodash/throttle';

/**
 * Internal dependencies
 */
import { getCssMediaQuery } from '../utils/get-css-media-query';

export const StickySiteHeader = () => {
	const header = document.querySelector( '.site-header' );

	if ( ! header ) {
		return;
	}

	let sentinel = document.querySelector( '.site-header-sentinel' );

	if ( ! sentinel ) {
		sentinel = document.createElement( 'div' );
		sentinel.classList.add( 'site-header-sentinel' );
		header.parentElement.insertBefore( sentinel, header );
	}

	let lastWindowScrollY;

	function handleStickyHeader() {
		const isMobile = getCssMediaQuery() === 'mobile';

		let shouldBeHidden = header.classList.contains( 'is-hidden' );
		let shouldBeStuck = header.classList.contains( 'is-stuck' );
		let shouldBeAnimated = header.classList.contains( 'is-animated' );

		const {
			top: sentinelTop,
			height: sentinelHeight,
		} = sentinel.getBoundingClientRect();

		const isStuck = shouldBeStuck;
		const isScrollingDown = lastWindowScrollY <= window.scrollY;

		const hasReachedSentinel = sentinelTop < 0;
		const hasPassedSentinel = sentinelTop + sentinelHeight < 0;

		if ( ! isMobile ) {
			// On desktop, it should be sticky below the sentinel.
			shouldBeStuck = hasReachedSentinel;
			shouldBeHidden = false;
			shouldBeAnimated = false;
		} else if ( isScrollingDown ) {
			// Scrolling down on mobile.

			if ( ! isStuck ) {
				// Do not animate static header when scrolling down to prevent
				// collapsing it when switching over to visible and sticky.
				shouldBeAnimated = false;

				// If is not stuck and below the sentinel, hide it.
				if ( hasReachedSentinel ) {
					shouldBeHidden = true;
				}

				// Make sticky only after passing the sentinel and site header so
				// that no box shadow is visible.
				if ( hasPassedSentinel ) {
					shouldBeStuck = true;
				}
			} else if ( hasPassedSentinel ) {
				// If is stuck while scrolling down and below the borderline, hide.
				shouldBeHidden = true;
				// Now it's safe to turn the animation back on.
				shouldBeAnimated = true;
			}
		} else {
			// Scrolling up on mobile.
			const isAboveSiteHeader = sentinelTop - sentinelHeight < 0; // Sentinel is of the header height.

			if ( isStuck ) {
				// While scrolling up with a sticky header, it should not be hidden.
				shouldBeHidden = false;
				shouldBeAnimated = true;

				// If already stuck and above the static site header, it should not
				// be sticky anymore.
				if ( ! isAboveSiteHeader ) {
					shouldBeStuck = false;
					shouldBeAnimated = false;
				}
			} else if ( ! hasPassedSentinel ) {
				// When scrolling up with static header, and we're above the
				// borderline, it should not be hidden.
				shouldBeHidden = false;
			}
		}

		requestAnimationFrame( () => {
			header.classList.toggle( 'is-hidden', shouldBeHidden );
			header.classList.toggle( 'is-stuck', shouldBeStuck );
			header.classList.toggle( 'is-animated', shouldBeAnimated );
		} );

		lastWindowScrollY = window.scrollY;
	}

	window.addEventListener( 'resize', throttle( handleStickyHeader, 100 ), false );
	window.addEventListener( 'scroll', throttle( handleStickyHeader, 100 ), false );

	handleStickyHeader();
};
