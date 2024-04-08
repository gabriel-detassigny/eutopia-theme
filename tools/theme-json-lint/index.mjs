#!/usr/bin/env node

/**
 * External dependencies
 */
import { dirname, resolve } from 'path';
import { fileURLToPath } from 'url';
import Ajv from 'ajv';
import ajvErrors from 'ajv-errors';

/**
 * Internal dependencies
 */
import { ansiColor, readJSON } from './src/utils.mjs';
import { validateStylesheetVariables } from './src/theme-json-linter.mjs';

const __dirname = dirname( fileURLToPath( import.meta.url ) );

const ajv = ajvErrors( new Ajv( {
	allowMatchingProperties: true,
	allErrors: true,
} ) );

const schemas = await Promise.all( [
	'theme-json-official-schema',
	'theme-json-extra-validation',
].map( ( name ) => (
	readJSON( resolve( __dirname, `schemas/${ name }.json` ) )
) ) );

const themeJsonPath = resolve( __dirname, '../../', 'theme.json' );

let data = null;

try {
	data = await readJSON( themeJsonPath );
} catch ( _ ) {
	/* eslint-disable no-console */
	console.log( `${ ansiColor( '✖', 'red' ) } ${ ansiColor( 'There were errors parsing the `theme.json` file', 'red' ) }` );
	process.exit( 1 );
}

// Basic schema validation.
const errors = Object.values( schemas ).reduce( ( acc, schema ) => {
	delete schema.$schema; // https://github.com/ajv-validator/ajv/issues/1254
	const valid = ajv.validate( schema, data );
	return valid ? acc : acc.concat( ajv.errors );
}, [] ).map( ( err ) => {
	return { ...err, message: err.message.replace( / NOT /g, ' not ' ) };
} ).concat(
	validateStylesheetVariables( data )
).map( ( err ) => {
	switch ( err.keyword ) {
		case 'additionalProperties':
			return { ...err, message: `${ err.message } ${ ansiColor( `(${ err.params.additionalProperty })`, 'gray' ) }` };
		case 'enforceThemeCSSVariables':
			return { ...err, message: `${ err.message } ${ ansiColor( `(${ err.params.invalidProperty })`, 'gray' ) }` };
	}
	return err;
} );

const totalErrors = errors.length;

/* eslint-disable no-console */
if ( totalErrors > 0 ) {
	console.log( themeJsonPath );
	errors.forEach( ( { instancePath, message } ) => {
		const prop = instancePath.slice( 1 ).replace( /\//g, '.' );
		console.log( `${ ansiColor( '✖', 'red' ) } ${ ansiColor( prop, 'red' ) } - ${ message }` );
	} );
	console.log( ansiColor( `\n${ totalErrors } error${ 1 !== totalErrors ? 's' : '' } found\n`, 'red' ) );
	process.exit( 1 );
}
