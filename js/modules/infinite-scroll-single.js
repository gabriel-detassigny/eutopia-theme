/**
 * Infinite Scroll (Single)
 */

// @TODO: Edit these selectors to match your theme.
const SELECTORS = {
	contentSelector: '.site-main', // The posts' layout container, usually parent of pagination.
	asideSelector: '#secondary', // The sidebar.
	loaderSelector: '.infinite-scroll-single', // The container with the load more data.
};

export const InfiniteScrollSingle = {
	loader: null,
	fetching: false,
	locations: {},
	init() {
		this.loader = document.querySelector( SELECTORS.loaderSelector );
		if ( this.loader ) {
			window.addEventListener( 'scroll', () => this.checkLocation() );
			this.checkLocation();
			this.setLocationTitle( document.querySelector( SELECTORS.contentSelector ), document.location, document.title );
		}
	},
	checkLocation() {
		if ( this.loader && false === this.fetching ) {
			const bounds = this.loader.getBoundingClientRect();
			const height = window.innerHeight;
			// Must be adjusted based on how early you want to load next post.
			const buffer = 400;

			/**
			 * This triggers when the user has almost reached
			 * the end of the last post.
			 */
			if ( bounds.top <= height + buffer ) {
				this.fetching = true;
				this.buildNextPost();
			}
			this.checkLocationTitle();
		}
	},
	checkLocationTitle() {
		const scrollHeight = window.scrollY;
		const midpoint = scrollHeight + ( window.innerHeight / 2 );
		for ( const [ link, { top, bottom, title } ] of Object.entries( this.locations ) ) {
			/**
			 * Update the browser tab title and URL if the post
			 * takes up most of the viewport.
			 */
			if ( midpoint > top && midpoint < bottom && title !== document.title ) {
				document.title = title;
				window.history.pushState( {}, '', link );
				break;
			}
		}
	},
	setLocationTitle( article, link, title ) {
		if ( this.locations.hasOwnProperty( link ) ) {
			return;
		}

		const bounds = article.getBoundingClientRect();

		/**
		 * Maintains a map of a link to the
		 * title and the bounds of the post.
		 * Note that these are absolute lengths
		 * from the top of the document and not
		 * relative to the viewport.
		 */
		this.locations[ link ] = {
			top: bounds.top + window.scrollY,
			bottom: bounds.bottom + window.scrollY,
			title,
		};
	},
	buildNextPost() {
		const nextPostLink = this.loader.dataset.next;

		if ( '' === nextPostLink ) {
			// Nothing to fetch.
			this.fetching = false;
			return;
		}

		fetch( nextPostLink )
			.then( ( response ) => response.text() )
			.then( ( data ) => {
				const parser = new DOMParser();
				const doc = parser.parseFromString( data, 'text/html' );
				const parent = this.loader.parentNode;
				const content = doc.querySelector( SELECTORS.contentSelector );
				const aside = doc.querySelector( SELECTORS.asideSelector );

				// element that will be wrapped
				const els = content.querySelectorAll( 'table' );

				if ( els.length ) {
					els.forEach( function( el ) {
						// create wrapper container
						const wrapper = document.createElement( 'figure' );

						// insert wrapper before el in the DOM tree
						el.parentNode.insertBefore( wrapper, el );

						// move el into wrapper
						wrapper.appendChild( el );
						wrapper.classList.add( 'wp-block-table' );
					} );
				}

				const newLoader = doc.querySelector( SELECTORS.loaderSelector );
				parent.insertBefore( content, this.loader );
				this.setLocationTitle( content, nextPostLink, doc.title );

				if ( aside ) {
					aside.parentNode.replaceChild( document.createElement( 'aside' ), aside );
				}

				// Update loader data if next post is availale, else set it to empty.
				this.loader.dataset.next = newLoader?.dataset?.next ?? '';
				this.fetching = false;
			} );
	},
};

export default InfiniteScrollSingle;
