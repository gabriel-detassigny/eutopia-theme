/**
 * External dependencies
 */
import path from 'node:path';
import { existsSync, readdirSync } from 'node:fs';

/**
 * Internal dependencies
 */
import {
	chainValidators,
	inputHasNoUnderscores,
	inputHasNoWhitespace,
	inputRequire,
} from '../../validators.mjs';

import { replaceStrings } from '../../utils.mjs';

/**
 * WP Block generator.
 *
 * @param { import('plop').NodePlopAPI } plop
 * @return { import('plop').PlopGeneratorConfig } Plop generator config.
 */
const createWpBlock = ( plop ) => {
	const blocksDir = 'blocks';
	const blocksDirExists = existsSync( blocksDir );
	const blocksPath = path.join( plop.config.rootDir, blocksDir );

	// Eject generated block into current working directory (inside `blocks/` if available).
	const targetPath = blocksDirExists ? `${ process.cwd() }/${ blocksDir }` : process.cwd();

	// Fetch all Block types in the /scaffold/ directory.
	const blockTypes = readdirSync( blocksPath, { withFileTypes: true } )
		.filter( ( dirent ) => dirent.isDirectory() && dirent.name.startsWith( '_template-' ) )
		.map( ( dirent ) => dirent.name );

	return {
		description: 'Custom Block',
		prompts: [
			{
				type: 'list',
				name: 'block-type',
				message: 'Block type:',
				choices: blockTypes,
			},
			{
				type: 'input',
				name: 'block-title',
				message: 'Block name such as `Tabbed Content`:',
				validate: inputRequire,
			},
			{
				type: 'input',
				name: 'block-slug',
				message: 'Block slug such as `tabbed-content`:',
				default: ( answers ) => plop.renderString( '{{ dashCase block-title }}', answers ),
				validate: ( input ) => chainValidators( input, [ inputRequire, inputHasNoWhitespace, inputHasNoUnderscores ] ),
			},
			{
				type: 'input',
				name: 'block-parent',
				message: 'Parent block name such as `gabriel-detassigny/tabbed-parent` (optional):',
				validate: ( input ) => chainValidators( input, [ inputHasNoWhitespace, inputHasNoUnderscores ] ),
			},
			{
				type: 'input',
				name: 'block-description',
				message: 'A short description of block functionality:',
				validate: inputRequire,
			},
			{
				type: 'input',
				name: 'block-icon',
				message: 'Dashicon slug to use as the block icon (https://developer.wordpress.org/resource/dashicons/), e.g. `list-view`:',
				validate: ( input ) => chainValidators( input, [ inputHasNoWhitespace, inputHasNoUnderscores ] ),
				default: 'smiley',
			},
			{
				type: 'input',
				name: 'block-category',
				message: 'Category the block belongs to:',
				validate: inputRequire,
				default: 'gabriel-detassigny-blocks',
			},
			{
				type: 'input',
				name: 'block-keywords',
				message: 'A comma-separated list of keywords to add to describe the block:',
				filter: ( input ) => input.split( ',' ).map( ( x ) => x.trim() ).filter( Boolean ),
			},
		],
		actions: [
			{
				type: 'addMany',
				base: path.join( blocksPath, '{{ block-type }}' ),
				templateFiles: path.join( blocksPath, '{{ block-type }}' ),
				destination: path.join( targetPath, '{{ block-slug }}' ),
				verbose: true,
				force: true, // Always override existing files in case we're regenerating with different prompts.
				transform: ( template, answers ) => replaceStrings(
					template,
					{
						'WP Block Template Title': plop.renderString( '{{ block-title }}', answers ),
						'Block template description.': plop.renderString( '{{ block-description }}', answers ),
						'wp-block-template': plop.renderString( '{{ block-slug }}', answers ),
						'wp-hero-block-template': plop.renderString( '{{ block-slug }}', answers ),
						'gabriel-detassigny-blocks': plop.renderString( '{{ block-category }}', answers ),
						'"icon": "smiley"': plop.renderString( '"icon": "{{ block-icon }}"', answers ),
						'wp-block-parent':
							answers[ 'block-parent' ]
								? plop.renderString( '- Parent block: *{{ block-parent }}*', answers )
								: '',
					}
				),
			},
			{
				type: 'modify',
				path: path.join( targetPath, '{{ block-slug }}', 'block.json' ),
				transform: ( content, answers ) => {
					const blockJson = JSON.parse( content );

					const [ namespace ] = blockJson.name.split( '/' );
					blockJson.name = `${ namespace }/${ answers[ 'block-slug' ] }`;

					blockJson.title = answers[ 'block-title' ];

					if ( answers[ 'block-keywords' ]?.length > 0 ) {
						blockJson.keywords = answers[ 'block-keywords' ];
					}

					if ( answers[ 'block-parent' ] ) {
						blockJson.parent = [ answers[ 'block-parent' ] ];
					}

					return JSON.stringify( blockJson, null, '\t' );
				},
			},
			( answers ) => plop.renderString(
				`Block "{{ block-title }}" was generated in the "${ blocksDirExists ? blocksDir + '/' : '' }{{ block-slug }}" directory. Please build the JS assets before using it.`,
				answers
			),
		],
	};
};

export default createWpBlock;
