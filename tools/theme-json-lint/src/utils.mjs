/**
 * External dependencies
 */
import { readFile } from 'fs/promises';

/**
 * ANSI color escape codes.
 *
 * @see https://en.wikipedia.org/wiki/ANSI_escape_code#3-bit_and_4-bit
 */
const ANSI_COLORS = {
	black: 30,
	red: 91,
	green: 32,
	yellow: 33,
	blue: 34,
	magenta: 35,
	cyan: 36,
	white: 37,
	gray: 90,
	reset: 0,
};

/**
 * Returns a colorized string for terminal output.
 *
 * @param {string} str   The string to colorize
 * @param {string} color A supported color name from the `ANSI_COLORS` map
 * @return {string} The colorized string
 */
export function ansiColor( str, color = 'reset' ) {
	return `\u001b[${ ANSI_COLORS[ color ] }m${ str }\u001b[0m`;
}

/**
 * Reads a JSON file from the file system.
 *
 * @param {string} file The JSON file to read
 * @return {Promise<Object>} The parsed JSON data
 */
export async function readJSON( file ) {
	return JSON.parse( await readFile( file, 'utf8' ) );
}
