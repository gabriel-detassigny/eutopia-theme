/**
 * WordPress dependencies
 */
import { Button, SelectControl } from '@wordpress/components';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export const MediaControl = ( props ) => {
	const Preview = ( { onClick } ) => {
		if ( ! props.value ) {
			return null;
		}
		const { id, thumbnail } = props.value;
		// eslint-disable-next-line jsx-a11y/click-events-have-key-events,jsx-a11y/no-noninteractive-element-interactions
		return ( <img style={ { cursor: 'pointer' } } onClick={ onClick } alt={ id } src={ thumbnail } /> );
	};

	const setMedia = ( value, selected ) => {
		const media = {
			id: value.id,
			thumbnail: selected ?? value.sizes.medium ? value.sizes.medium.url : value.sizes.full.url,
			sizes: value.sizes,
		};
		media.selected = selected ?? media.thumbnail;
		props.onChange( media );
	};

	const sizeOptions = ( value ) => {
		const options = [];
		Object.entries( value.sizes ).forEach( ( [ key, size ] ) => {
			options.push( { label: key, value: size.url } );
		} );

		return options;
	};
	return (
		<MediaUploadCheck>
			<h4>{ props.label }</h4>
			<MediaUpload
				{ ...props }
				onSelect={ setMedia }
				render={ ( { open } ) => (
					<>
						{ props.value &&
							<>
								<Preview onClick={ open } />
								<SelectControl value={ props.value.selected } options={ sizeOptions( props.value ) } onChange={ ( selected ) => setMedia( props.value, selected ) } />
							</>
						}
						<div style={ { display: 'flex', justifyContent: 'space-between' } }>
							<Button onClick={ open } variant={ 'primary' }>{ props.value ? __( 'Change', 'eutopia-theme' ) : __( 'Open Media Library', 'eutopia-theme' ) } </Button>
							{ props.value &&
								<Button onClick={ () => props.onChange( null ) } variant={ 'secondary' }>{ __( 'Remove', 'eutopia-theme' ) } </Button>
							}
						</div>
					</>
				) }
			/>
		</MediaUploadCheck>
	);
};
