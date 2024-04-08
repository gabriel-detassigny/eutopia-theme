#!/usr/bin/env node

/**
 * External dependencies
 */
import path from 'path';
import { fileURLToPath } from 'url';
import { Plop, run } from 'plop';
import minimist from 'minimist';

const args = process.argv.slice( 2 );
const argv = minimist( args );

const __dirname = path.dirname( fileURLToPath( import.meta.url ) );

Plop.prepare( {
	cwd: argv.cwd,
	configPath: path.join( __dirname, 'plopfile.mjs' ),
	preload: argv.preload || [],
	completion: argv.completion,
}, ( env ) => Plop.execute( env, run ) );
