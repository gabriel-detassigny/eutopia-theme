export const Search = {
	init() {
		// For the search box.
		this.searchInput = document.querySelector( '.search-form__field' );
		this.clearSearchButton = document.querySelector( '.search-form__clear' );
		this.formSubmitButton = document.querySelector( '.search-form__submit' );

		// For the search box inside the search result pages.
		this.pageField = document.querySelector( '.search-page .search-form__field' );
		this.pageSubmit = document.querySelector( '.search-page .search-form__submit' );
		this.pageClear = document.querySelector( '.search-page .search-form__clear' );

		this.onSearchType();
		this.onClearClick();
	},

	/**
	 * Enable clear & submit button when the search is not empty.
	 */
	onSearchType() {
		const self = this;

		self.searchInput.addEventListener( 'input', () => {
			if ( self.searchInput.value !== '' ) {
				self.formSubmitButton.disabled = false;
				self.clearSearchButton.disabled = false;
			} else {
				self.formSubmitButton.disabled = true;
				self.clearSearchButton.disabled = true;
			}
		} );

		// Skip the next addEventListener if we are not on the search result page.
		if ( ! this.pageField || ! this.pageSubmit || ! this.pageClear ) {
			return;
		}

		// When we are on the form inside the search result page
		self.pageField.addEventListener( 'input', () => {
			if ( self.pageField.value !== '' ) {
				self.pageSubmit.disabled = false;
				self.pageClear.disabled = false;
			} else {
				self.pageSubmit.disabled = true;
				self.pageClear.disabled = true;
			}
		} );
	},

	/**
	 * On clear click, set back to default state.
	 */
	onClearClick() {
		const self = this;

		self.clearSearchButton.addEventListener( 'click', ( e ) => {
			e.preventDefault();

			self.searchInput.value = '';
			self.formSubmitButton.disabled = true;
			self.clearSearchButton.disabled = true;
			self.searchInput.focus();
		} );

		// Skip the next addEventListener if we are not on the search result page.
		if ( ! this.pageField || ! this.pageSubmit || ! this.pageClear ) {
			return;
		}

		// When we are on the form inside the search result page
		self.pageClear.addEventListener( 'click', ( e ) => {
			e.preventDefault();

			self.pageField.value = '';
			self.pageSubmit.disabled = true;
			self.pageClear.disabled = true;
			self.pageField.focus();
		} );
	},
};
