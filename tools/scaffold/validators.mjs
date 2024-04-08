export const chainValidators = ( input, validateFns = [] ) => {
	let result = true;

	for ( const validateFn of validateFns ) {
		result = validateFn( input );

		if ( result !== true ) {
			return result;
		}
	}

	return result;
};

export const inputRequire = ( input ) =>
	input.length ? true : 'Please specify the required input!';

export const inputHasNoWhitespace = ( input ) =>
	/\s/.test( input ) ? 'The input should contain no whitespace' : true;

export const inputHasNoDashes = ( input ) =>
	/-/.test( input ) ? 'The input should contain no dashes' : true;

export const inputHasNoUnderscores = ( input ) =>
	/_/.test( input ) ? 'The input should contain no underscores' : true;

export const inputIsUrl = ( input ) =>
	/[-a-zA-Z0-9@:%._+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_+.~#?&/=]*)/.test( input ) ? true : 'The input should be a valid URL';
