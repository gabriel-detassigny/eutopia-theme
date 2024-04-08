/**
 * External dependencies
 */
import path from 'node:path';
import url from 'node:url';

/**
 * Replace string pairs in a string.
 *
 * @param {string} contents   Where to replace the strings.
 * @param {Object} replaceMap Key-value pairs of strings to replace.
 * @return {string} String with contents replaced.
 */
export const replaceStrings = ( contents, replaceMap ) => {
	for ( const lookupString in replaceMap ) {
		contents = contents.replace( new RegExp( lookupString, 'g' ), replaceMap[ lookupString ] );
	}

	return contents;
};

/**
 * Get absolute path to the directory of a file.
 *
 * @param {string} fileUrl File URL.
 * @return {string} Absolute path to a directory of a file.
 */
export const pathToFileDirectory = ( fileUrl ) => path.dirname( url.fileURLToPath( fileUrl ) );
