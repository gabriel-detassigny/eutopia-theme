export const HelpText = ( props ) => {
	const { text } = props;
	return (
		<>
			{ text &&
				<div className={ 'components-base-control__help cc-help-line' }>
					{ text }
				</div>
			}
		</>
	);
};
