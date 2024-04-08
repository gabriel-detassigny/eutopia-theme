/**
 * Internal dependencies
 */
import { ModuleName } from './module-name';

describe( 'ModuleName', () => {
	it( 'logs output', () => {
		ModuleName();

		// Rely on https://developer.wordpress.org/block-editor/reference-guides/packages/packages-jest-console/.
		expect( console ).toHaveLoggedWith( 'ModuleName init' );
	} );
} );
