/**
 * Provides custom validators for theme.json
 * The validators return the same format as AJV.
 *
 * @see https://ajv.js.org/
 */

/**
 * Ensures usage of theme-specific CSS variables on the
 * `settings` section of the theme.json data.
 *
 * @param {Object} data The parsed theme.json data
 * @return {Array} Validation errors
 */
export function validateStylesheetVariables( data ) {
	const propsRegex = /^(text|color|size|background|fontFamily|lineHeight|(content|wide|font)Size)$/;
	const cssVarRegex = /^\s*var\(\s*--[a-zA-Z0-9-]+\s*\)\s*;?\s*$/;
	return walk( ( { key, prop }, path, acc ) => {
		if ( propsRegex.test( key ) && 'string' === typeof prop && ! cssVarRegex.test( prop ) ) {
			return [ ...acc, {
				instancePath: `/${ path.join( '/' ) }`,
				schemaPath: '#',
				keyword: 'enforceThemeCSSVariables',
				params: { invalidProperty: prop },
				message: 'property needs to be specified as a theme-specific CSS variable',
			} ];
		}
		return acc;
	}, data );
}

/**
 * Recursively traverses an object's properties, reducing to an accumulator.
 *
 * @param {Function}     walker      The walker function
 * @param {Object}       data        The object to traverse
 * @param {Array}        path        Array containing the intermediate path
 * @param {Array|Object} accumulator Initial accumulator data
 * @return {Array|Object} The final accumulator data
 */
export function walk( walker, data, path = [], accumulator = [] ) {
	return Object.keys( data ).reduce( ( acc, key ) => {
		const prop = data[ key ];
		if ( 'object' === typeof prop && prop ) { // Extra checking for `prop` not null.
			return walk( walker, prop, [ ...path, key ], acc );
		}
		return walker( { key, prop }, [ ...path, key ], acc );
	}, accumulator );
}
