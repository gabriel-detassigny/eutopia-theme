export const Navigation = {
	init() {
		this.triggers = document.querySelectorAll( '.navigation-slidein .dropdown' );

		if ( ! this.triggers.length ) {
			return;
		}

		this.onMenuClick();
	},

	onMenuClick() {
		const self = this;

		self.triggers.forEach( ( trigger ) => {
			trigger.addEventListener( 'click', ( event ) => {
				const parentMenu = event.target.closest( '.menu-item' );

				if ( ! parentMenu ) {
					return;
				}

				if ( parentMenu.classList.contains( 'is-open' ) ) {
					self.closeMenu( parentMenu );
				} else {
					self.openMenu( parentMenu );
				}
			} );
		} );
	},

	closeMenu( menu ) {
		menu.classList.remove( 'is-open' );
		const link = menu.querySelector( 'a' );

		if ( link ) {
			link.setAttribute( 'aria-expanded', 'false' );
		}
	},

	openMenu( menu ) {
		menu.classList.add( 'is-open' );
		const link = menu.querySelector( 'a' );

		if ( link ) {
			link.setAttribute( 'aria-expanded', 'true' );
		}
	},
};
