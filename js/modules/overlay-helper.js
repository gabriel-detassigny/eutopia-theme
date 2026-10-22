/*
 * Usage:
 * Add an button using this template:
 * <button data-target="#TARGET_ID" class="js-use-overlay">Open</button>
 * Match the TARGET_ID with the value of the id attribute of the element that should be loaded over the overlay (can be a hidden element).
 */

/**
 * Internal dependencies
 */
import { getAllFocusableElements } from '../utils/get-all-focusable-element';

export const OverlayHelper = {
	init() {
		// Initialises Overlay.
		this.body = document.body;
		this.overlay = document.querySelector( '.js-site-overlay-backdrop' );
		this.overlayContentCloseTrigger = document.querySelectorAll( '[data-dismiss="modal"]' );

		if ( ! this.overlay ) {
			const overlayElement = document.createElement( 'div' );
			overlayElement.classList.add( 'site-overlay', 'js-site-overlay-backdrop' );
			overlayElement.setAttribute( 'aria-hidden', 'true' );
			document.body.appendChild( overlayElement );
			this.overlay = overlayElement;
		}
		this.overlayTriggers = document.querySelectorAll( '.js-use-overlay' );

		[].forEach.call( this.overlayTriggers,
			function( e ) {
				e.addEventListener( 'click', OverlayHelper.openOverlay, false );
			},
		);

		this.onBackdropClick();
		this.onEsc();
		this.onOverlayContentClose();
	},

	/**
	 * Open overlay from trigger.
	 *
	 * @param {event} e Click event.
	 *
	 * @return {void}
	 */
	openOverlay( e ) {
		if ( e.target && e.target.dataset.target ) {
			e.target.setAttribute( 'aria-expanded', 'true' );
			const targetElement = document.querySelector( e.target.dataset.target );
			const bodyTargetClass = targetElement.id + '-is-open';
			// Add the ID of the target + suffix to the body classes
			// This class could be used to style variation of the overlay
			document.body.classList.add( bodyTargetClass );
			targetElement.classList.add( 'is-overlaid' );
			OverlayHelper.overlay.dataset.overlay = true;

			// Set the focus on a specic element.
			// If nothing is set, the first focusable element will have the focus
			if ( e.target.dataset.focus ) {
				document.querySelector( e.target.dataset.focus ).focus();
			} else {
				const elementTofocus = getAllFocusableElements( targetElement )[ 0 ];
				elementTofocus.focus();
			}

			// Prevent the body to scroll if data-scroll is not set
			if ( ! e.target.dataset.scroll ) {
				document.body.classList.add( 'is-not-scrollable' );
			}
			// Disables the backdrop if data-backdrop is set to false.
			if ( 'false' === e.target.dataset.backdrop ) {
				document.body.classList.add( 'no-backdrop' );
			}
		}
	},

	/**
	 * close overlay action.
	 */
	closeOverlay() {
		delete OverlayHelper.overlay.dataset.overlay;
		const popups = document.body.querySelectorAll( '.is-overlaid' );
		popups.forEach(
			function( popup ) {
				const bodyClassToRemove = popup.id + '-is-open';
				document.body.classList.remove( bodyClassToRemove );
				popup.classList.remove( 'is-overlaid' );
				// Set back the aria-expanded on the trigger.
				document.querySelector( '[data-target="#' + popup.id + '"]' ).setAttribute( 'aria-expanded', 'false' );
			},
		);
		document.body.classList.remove( 'is-not-scrollable' );
		document.body.classList.remove( 'no-backdrop' );
	},

	/**
	 * Close the overlay when clicking on the backdrop.
	 */
	onBackdropClick() {
		const self = this;

		self.overlay.addEventListener( 'click', () => {
			self.closeOverlay( self );
		} );
	},

	/**
	 * Close overlay with Escape key
	 */
	onEsc() {
		const self = this;

		document.addEventListener( 'keydown', ( e ) => {
			if ( e.key === 'Escape' && self.overlay.hasAttribute( 'data-overlay' ) ) {
				self.closeOverlay( self );
			}
		} );
	},

	/**
	 * Close overlay content with a click on a trigger.
	 */
	onOverlayContentClose() {
		const self = this;

		this.overlayContentCloseTrigger.forEach( ( trigger ) => {
			trigger.addEventListener( 'click', () => {
				self.closeOverlay( self );
			} );
		} );
	},
};
