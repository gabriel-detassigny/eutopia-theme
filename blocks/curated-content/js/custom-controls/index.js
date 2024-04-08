/**
 * Dynamically get custom input controls.
 */
const context = require.context( './', true, /(?<!(test|dist)\/)index\.js$/ );
const registerCustomControls = () => {
	const CustomControls = {};

	context.keys().forEach( ( modulePath ) => {
		const Control = context( modulePath );

		Object.entries( Control ).forEach( ( [ name, control ] ) => {
			CustomControls[ name ] = control;
		} );
	} );

	return CustomControls;
};

export default registerCustomControls;
