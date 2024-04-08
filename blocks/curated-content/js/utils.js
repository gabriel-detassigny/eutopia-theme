const validateCondition = ( conditions, attributes, OR ) => {
	const passes = [];
	let count = 0;

	for ( const i in conditions ) {
		count++;
		if ( typeof conditions[ i ] === 'object' ) {
			if ( validateCondition( conditions[ i ], attributes, true ) ) {
				passes.push( true );
			}
		} else if ( attributes[ i ] && conditions[ i ] === attributes[ i ] ) {
			passes.push( true );
		} else if ( ! isNaN( parseInt( i ) ) && attributes[ conditions[ i ] ] ) {
			passes.push( true );
		}
	}
	return OR ? 0 < passes.length : count === passes.length;
};

export { validateCondition };

