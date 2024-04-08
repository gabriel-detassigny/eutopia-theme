/**
 * Swiper registry.
 *
 * This registry keeps Swiper instance objects mapped to blocks in the editor.
 */
class SwiperRegistry {
	constructor( args = {} ) {
		this.parentBlockName = args.parentBlockName;
		this.swiperClassname = args.swiperClassname;
		this.initCallback = args.initCallback;

		if ( ! this.parentBlockName || ! this.swiperClassname || typeof this.initCallback !== 'function' ) {
			throw new Error( 'Some constructor arguments are missing.' );
		}

		this.registry = new Map();
	}

	findParentBlock( element ) {
		if ( ! ( element instanceof HTMLElement ) ) {
			return null;
		}

		if ( element.dataset?.type === this.parentBlockName && element.dataset?.block ) {
			return element;
		} else if ( element.classList.contains( this.swiperClassname ) ) {
			return element.closest( `.wp-block[data-block][data-type="${ this.parentBlockName }"]` );
		}

		return null;
	}

	add( element ) {
		const parentBlock = this.findParentBlock( element );

		if ( parentBlock ) {
			this.registry.set( parentBlock.dataset.block, this.initCallback( element ) );
		}
	}

	remove( element ) {
		const parentBlock = this.findParentBlock( element );

		if ( parentBlock && this.registry.has( parentBlock.dataset.block ) ) {
			this.registry.get( parentBlock.dataset.block ).destroy();
			this.registry.delete( parentBlock.dataset.block );
		}
	}

	reset( element ) {
		const parentBlock = this.findParentBlock( element );

		if ( parentBlock ) {
			this.remove( element );
			this.add( element );
		}
	}
}

export default SwiperRegistry;
