/**
 * External dependencies
 */
import { ContentPicker } from '@10up/block-components';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

export const SearchTaxonomy = ( props ) => {
	const { value, attributes: { taxonomy, source, posts_per_page } } = props; // eslint-disable-line camelcase
	const maxContentItems = 'taxonomies' === source ? posts_per_page : 1; // eslint-disable-line camelcase

	const setSelected = ( terms ) => {
		const newValue = [ ...terms ] ?? [];
		props.onChange( newValue );
	};

	return (
		<ContentPicker
			content={ value }
			onPickChange={ setSelected }
			mode="term"
			label={ __( 'Please select content:', 'eutopia-theme' ) }
			isOrderable={ true }
			contentTypes={ [ taxonomy ] }
			maxContentItems={ maxContentItems }
		/>
	);
};
