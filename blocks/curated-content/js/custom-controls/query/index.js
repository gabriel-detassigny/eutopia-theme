/**
 * WordPress dependencies
 */
import { QueryControls } from '@wordpress/components';

export const QueryControl = ( props ) => {
	const { onChange, value } = props;
	const { category, maxItems, minItems, numberOfItems, order, orderBy, author } = value;

	return (
		<QueryControls
			{ ...props }
			{ ...{ maxItems, minItems, numberOfItems, order, orderBy } }
			onOrderByChange={ ( newOrderBy ) => onChange( { ...value, orderBy: newOrderBy } ) }
			onOrderChange={ ( newOrder ) => onChange( { ...value, order: newOrder } ) }
			selectedCategoryId={ category }
			onCategoryChange={ ( newCategory ) => onChange( { ...value, category: newCategory } ) }
			onAuthorChange={ ( authorId ) => onChange( { ...value, author: authorId } ) }
			selectedAuthorId={ author }
			onNumberOfItemsChange={ ( newNumberOfItems ) =>
				onChange( { ...value, numberOfItems: newNumberOfItems } )
			}
		/>
	);
};
