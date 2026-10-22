export const PrimaryMenu = {
	init() {
		PrimaryMenu.initOverflowMenu( {
			element: '#site-navigation',
		} );
	},

	// Initialize the overflow menu.
	initOverflowMenu( options ) {
		this.element = document.querySelector( options.element );

		if ( ! this.element ) {
			return;
		}

		this.primaryMenu = this.element.querySelector( '#primary-menu' );
		this.toggleButton = this.element.querySelector( '#toggle-overflow-menu' );
		this.breakpoints = [];

		this.setupMenu();
		this.calculateBreakpoints();
		this.updateMenu();

		this.element.classList.add( 'js-overflow-menu-setup' );

		this.addBindings();
	},

	// Creates/returns a method bounded with 'this'. Used for creating
	// named event listeners that can easily be removed later.
	bindMethod( name ) {
		return this[ '_' + name + '_' ] || Object.defineProperty(
			this, '_' + name + '_', { value: this[ name ].bind( this ) },
		)[ '_' + name + '_' ];
	},

	// Creates the necessary markup and adds the necessary classes.
	setupMenu() {
		// Setup the toggle button.
		this.toggleButton = document.createElement( 'button' );
		this.toggleButton.id = 'toggle-overflow-menu';
		this.toggleButton.classList.add( 'is-hidden' );
		this.toggleButton.setAttribute( 'aria-haspopup', 'menu' );
		this.toggleButton.setAttribute( 'aria-label', 'More items' );
		this.element.appendChild( this.toggleButton );

		// Setup the overflow menu.
		this.overflowMenu = document.createElement( 'ul' );
		this.overflowMenu.id = 'overflow-menu';
		this.overflowMenu.classList.add( 'is-hidden', 'sub-menu' );
		this.element.appendChild( this.overflowMenu );
	},

	// For each navigation item, calculate how much space is needed
	// to accomodate it.
	calculateBreakpoints() {
		let childrenWidth = 0;

		for ( let i = 0; i < this.primaryMenu.children.length; i++ ) {
			childrenWidth += this.primaryMenu.children[ i ].offsetWidth;
			this.breakpoints[ i ] = childrenWidth;
		}
	},

	// Add event listeners.
	addBindings() {
		window.addEventListener( 'resize', this.bindMethod( 'updateMenu' ) );
		window.dispatchEvent( new Event( 'resize' ) ); // Trigger the resize event to initialize menu placement.
		this.toggleButton.addEventListener( 'click', this.bindMethod( 'toggleoverflowMenu' ) );
	},

	// Update the menu based on the available space.
	updateMenu() {
		const availableSpace = this.element.offsetWidth - ( this.toggleButton.offsetWidth );
		let itemsVisible = this.primaryMenu.children.length;

		// Check if there is not enough space for the primary menu items or
		// if there is space available for the items in overflow menu.
		if ( availableSpace < this.breakpoints[ itemsVisible - 1 ] ) {
			this.toggleButton.classList.remove( 'is-hidden' );

			while ( availableSpace < this.breakpoints[ itemsVisible - 1 ] ) {
				this.overflowMenu.insertBefore( this.primaryMenu.children[ itemsVisible - 1 ], this.overflowMenu.firstChild );
				itemsVisible--;
			}
		} else if ( availableSpace > this.breakpoints[ itemsVisible ] ) {
			while ( availableSpace > this.breakpoints[ itemsVisible ] ) {
				this.primaryMenu.appendChild( this.overflowMenu.removeChild( this.overflowMenu.firstChild ) );
				itemsVisible++;
			}
		}

		if ( ! this.overflowMenu.children.length ) {
			this.toggleButton.classList.add( 'is-hidden' );
		}
	},

	// Toggle the overflow menu on toggle button click.
	toggleoverflowMenu() {
		this.overflowMenu.classList.toggle( 'is-hidden' );
	},
};
