/**
 * Infinite Scroll (Pagination)
 */

const LOADING_CLASS = 'is-infinite-scroll-loading';
const DISABLED_CLASS = 'is-disabled';

// @TODO: Edit these selectors to match your theme.
const SELECTORS = {
	page: '#page', // The page wrapper.
	wrapperSelector: '.site-main', // The posts' layout container, usually parent of pagination.
	buttonSelector: '.infinite-scroll-pagination .nav-previous > a[href]', // The load more button.
	entriesSelector: '.posts', // The container where the new posts are, usually sibling og pagination.
	buttonWrapperSelector: '.infinite-scroll-pagination', // The container of the load more button.
};

export const InfiniteScrollPagination = {
	/**
	 * Initializer
	 */
	init() {
		this.param = 'infinite-scroll-pagination';
		this.wrapperSelector = SELECTORS.wrapperSelector;
		this.buttonSelector = SELECTORS.buttonSelector;
		this.entriesSelector = SELECTORS.entriesSelector;
		this.buttonWrapperSelector = SELECTORS.buttonWrapperSelector;
		this.buttonWrapper = document.querySelector( this.buttonWrapperSelector );
		this.loading = false;
		this.page = document.querySelector( SELECTORS.page );
		this.buttons = document.querySelectorAll( this.buttonSelector );

		if ( ! this.page || this.buttons.length === 0 ) {
			return;
		}

		this.updateMarkup( this.buttons );
		this.addClickHandler( this.buttons );
		this.maybeAddScrollListener();
	},

	/**
	 * If Automatic Infinite scroll is selected, posts will be loaded
	 * as we reach the end of the page.
	 */
	maybeAddScrollListener() {
		// This means that pagination must happen on scroll and not wait
		// for the user to press 'load more'.
		if ( this.buttonWrapper?.closest( '.pagination--automatic' ) ) {
			window.addEventListener( 'scroll', () => {
				const { top } = this.buttonWrapper.getBoundingClientRect();
				const buffer = 400;
				if ( ! this.loading && top <= window.innerHeight + buffer ) {
					const button = this.buttonWrapper.querySelector( 'a' );

					if ( button.href ) {
						return;
					}

					button.click();
				}
			} );
		}
	},

	/**
	 * On page load, update the markup to prevent the "open in a new tab" UX issue
	 *
	 * @param {Array} buttons Array of load more buttons
	 */
	updateMarkup( buttons ) {
		buttons.forEach( ( button ) => {
			const findParam = new RegExp( `/${ this.param }/`, 'gi' );

			if ( ! findParam.test( button.href ) ) {
				button.href = button.href.replace(
					/(\/page\/[0-9]+\/)/gi,
					`/${ this.param }$1`
				);
			}

			button.dataset.link = button.href;
			button.removeAttribute( 'href' );
		} );
	},

	/**
	 * On load more button click
	 *
	 * @param {Array} buttons Array of load more buttons
	 */
	addClickHandler( buttons ) {
		buttons.forEach( ( button ) => {
			button.addEventListener( 'click', ( event ) => this.clickHandler( event ) );
		} );
	},

	/**
	 * Event listener that loads posts when 'load more' is clicked.
	 *
	 * @param {Event} event
	 */
	clickHandler( event ) {
		const button = event.target;

		const container = button
			.closest( this.wrapperSelector )
			.querySelector( this.entriesSelector );

		if ( ! container ) {
			return;
		}

		event.preventDefault();

		this.enableLoading( this, button );

		return fetch( button.dataset.link, {
			method: 'GET',
		} )
			.then( ( response ) => {
				if ( response.ok ) {
					return response.text();
				}

				throw new Error( 'Something went wrong' );
			} )
			.then( ( data ) => {
				const parser = new DOMParser();

				const html = parser.parseFromString(
					data,
					'text/html'
				);

				const entries = html.querySelector(
					this.entriesSelector
				)?.children;

				if ( ! entries || entries?.length === 0 ) {
					return;
				}

				const fragment = document.createDocumentFragment();

				[ ...entries ].forEach( ( entry ) => {
					if ( entry ) {
						fragment.append( entry );
					}
				} );

				container.append( fragment );

				const newButton = html.querySelector(
					this.buttonWrapperSelector
				);

				this.buttonWrapper = newButton;

				button
					.closest( this.buttonWrapperSelector )
					.replaceWith( newButton );
				this.updateButtons( this );

				this.disableLoading( this );

				window.dispatchEvent(
					new CustomEvent( 'infinite-scroll-pagination:update' )
				);
			} )
			.catch( () => {
				this.disableLoading( this, button );
			} );
	},
	/**
	 * Enable loading
	 *
	 * @param {Object}  self   Class instance
	 * @param {Element} button Load more button
	 */
	enableLoading( self, button ) {
		this.loading = true;
		self.page.classList.add( LOADING_CLASS );
		button.classList.add( DISABLED_CLASS );
		button.dataset.text = button.textContent;
		button.textContent = window.eutopiaInfiniteScrollPagination.text_loading;
	},

	/**
	 * Disable loading
	 *
	 * @param {Object}  self   Class instance
	 * @param {Element} button Load more button
	 */
	disableLoading( self, button = null ) {
		this.loading = false;
		self.page.classList.remove( LOADING_CLASS );

		if ( button ) {
			button.classList.remove( DISABLED_CLASS );
			button.textContent = button.dataset.text;
			button.dataset.text = '';
		}
	},

	/**
	 * Once a new page is loaded by fetch, update the load more button
	 *
	 * @param {Object} self Class instance
	 */
	updateButtons( self ) {
		const newButtons = document.querySelectorAll( self.buttonSelector );

		self.updateMarkup( newButtons );
		self.addClickHandler( newButtons );

		self.buttons = [ ...self.buttons, ...newButtons ];
	},
};
