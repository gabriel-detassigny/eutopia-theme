/**
 * External dependencies
 */
import path from 'node:path';
import fs from 'node:fs';
import { Plop } from 'plop';

/**
 * Internal dependencies
 */
import { pathToFileDirectory } from './utils.mjs';

import createWpBlock from './generators/create-wp-block/index.mjs';

/**
 * @param { Plop } plop
 */
export default ( plop ) => {
	plop.config = {
		rootDir: path.resolve( pathToFileDirectory( import.meta.url ), '../..' ),
	};

	plop.setActionType( 'copy', function( answers, config ) {
		const src = plop.renderString( config.src, answers );
		const dest = plop.renderString( config.dest, answers );

		const dirname = path.dirname( dest );

		fs.mkdirSync( dirname, { recursive: true } );
		fs.copyFileSync( src, dest );

		// Without resolving the promise, action fails.
		return Promise.resolve();
	} );

	// Register all generators.

	plop.setGenerator( 'create-wp-block', createWpBlock( plop ) );
};
