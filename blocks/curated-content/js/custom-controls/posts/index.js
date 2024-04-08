/**
 * External dependencies
 */
import { ContentPicker } from '@10up/block-components';
/**
 * WordPress dependencies
 */
import { v4 as uuidv4 } from 'uuid';
import { Button, Spinner, Tip } from '@wordpress/components';
import { useEffect, useMemo, useState } from '@wordpress/element';

import { addQueryArgs } from '@wordpress/url';
import apiFetch from '@wordpress/api-fetch';

/* eslint-disable camelcase */

/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';

export const SearchPosts = ( props ) => {
	const {
		value,
		attributes: { source, post_type, posts_in = [] },
	} = props;

	const setSelected = ( posts ) => {
		const newValue = [ ...posts ] ?? [];
		props.onChange( newValue );
	};

	const [ isLoadingPostSuggestion, setLoadingPostSuggestion ] = useState( false );
	const [ isEmptyQueryMessageVisible, setEmptyQueryMessageVisible ] = useState( false );

	// Mechanism to display the info message when there are no posts found in query.
	useEffect( () => {
		let timeout;
		if ( isEmptyQueryMessageVisible ) {
			timeout = setTimeout( () => setEmptyQueryMessageVisible( false ), 900 );
		}
		return () => clearTimeout( timeout );
	}, [ isEmptyQueryMessageVisible ] );

	// Used to generate the UUID required by the `ContentPicker` component.
	// Such UUID is generated internally by the component, and since we are
	// injecting into its state, it must satisfy its structure.
	const allocUUID = useMemo( () => () => {
		let newid;
		do {
			newid = uuidv4();
		} while ( posts_in.some( ( { uuid } ) => uuid === newid ) );
		return newid;
	}, [ posts_in ] );

	// Get the information we need for the given post type, such as the label
	// we need in the "Add" button, as well as the query endpoint we need to
	// use to get the content.
	const { postTypeLabel, queryEndpoint } = useMemo( () => {
		let endpoint, label;
		switch ( post_type ) {
			case 'page':
				label = __( 'Page', 'eutopia-theme' );
				endpoint = '/wp/v2/pages';
				break;
			case 'attachment':
				label = __( 'Media', 'eutopia-theme' );
				endpoint = '/wp/v2/media';
				break;
			default:
				label = __( 'Post', 'eutopia-theme' );
				endpoint = '/wp/v2/posts';
				break;
		}
		return (
			{ postTypeLabel: label, queryEndpoint: endpoint }
		);
	}, [ post_type ] );

	// Get a new post suggestion, within the same post type and excluding
	// the posts that have already been selected.
	const getPostSuggestion = useMemo( () => ( callback ) => {
		const query = addQueryArgs( queryEndpoint, {
			exclude: posts_in.map( ( { id } ) => id ).join( ',' ),
			type: post_type,
			per_page: 1,
			orderby: 'date',
			order: 'desc',
			_fields: 'id,type',
		} );
		apiFetch( { path: query } ).then( ( data ) => {
			callback( data.map( ( { id, type } ) => ( { id, uuid: allocUUID(), type } ) ).pop() ?? null );
		} );
	}, [ posts_in, post_type, queryEndpoint, allocUUID ] );

	return (
		<>
			<ContentPicker
				content={ value }
				onPickChange={ setSelected }
				mode="post"
				contentTypes={ [ post_type ] }
				label={ __( 'Please select content:', 'eutopia-theme' ) }
				isOrderable={ true }
				maxContentItems={ 100 }
			/>
			{ 'manual' === source && isEmptyQueryMessageVisible && (
				<>
					<Tip>{
						/* translators: %s: The label of the post type. */
						sprintf( __( 'No %s items were found.', 'eutopia-theme' ), postTypeLabel )
					}</Tip>
					<br />
				</>
			) }
			{ 'manual' === source && (
				<Button
					variant="primary"
					icon="plus"
					iconSize={ 16 }
					isSmall={ true }
					disabled={ isLoadingPostSuggestion || isEmptyQueryMessageVisible }
					onClick={ () => {
						setLoadingPostSuggestion( true );
						getPostSuggestion( ( post ) => {
							setLoadingPostSuggestion( false );
							if ( post ) {
								setSelected( posts_in.concat( post ) );
							} else {
								setEmptyQueryMessageVisible( true );
							}
						} );
					} }
				>
					{
					/* translators: %s: The label of the post type. */
						sprintf( __( 'Add Recent %s', 'eutopia-theme' ), postTypeLabel )
					}
				</Button>
			) }
			{ 'manual' === source && isLoadingPostSuggestion && <Spinner /> }
		</>
	);
};
