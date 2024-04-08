/**
 * Internal dependencies
 */
import { DEVICE_NAMES } from './constants';

describe( 'constants', () => {
	it( 'DEVICE_NAMES', () => {
		expect( Object.keys( DEVICE_NAMES ) ).toStrictEqual(
			[
				'desktop',
				'tablet',
				'mobile',
			]
		);
	} );
} );
