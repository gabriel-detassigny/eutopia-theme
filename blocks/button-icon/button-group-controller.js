/**
 * WordPress dependencies
 */
import { Button, ButtonGroup, PanelBody } from '@wordpress/components';

function ButtonGroupController( { selectedOption, options, onClick, title, isSmall = false } ) {
	return (
		<PanelBody title={ title }>
			<ButtonGroup aria-label={ title }>
				{ options.map( ( option ) => {
					return (
						<Button
							key={ option.name }
							isSmall={ isSmall }
							variant={
								option.name === selectedOption
									? 'primary'
									: undefined
							}
							onClick={ () => onClick( option.name ) }
						>
							{ option.label }
						</Button>
					);
				} ) }
			</ButtonGroup>
		</PanelBody>
	);
}

export default ButtonGroupController;
