/**
 * WordPress dependencies
 */
import { useBlockProps, RichText } from '@wordpress/block-editor';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 * @param {Object} props Block properties
 * @return {Element} Element to render.
 */
export default function Edit( props ) {
	const { attributes, setAttributes } = props;

	/* Access attributes */
	const { content, slug } = attributes;

	const blockProps = useBlockProps( {
		className: `eutopia-theme__${ slug }`,
	} );

	function setContent( newContent ) {
		setAttributes( { content: newContent } );
	}

	return (
		<div { ...blockProps } >
			<RichText
				value={ content }
				onChange={ setContent }
				tagName="h2"
				placeholder={ content }
			/>
		</div>
	);
}
