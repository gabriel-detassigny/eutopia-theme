/**
 * WordPress dependencies
 */
import { useBlockProps, RichText } from '@wordpress/block-editor';

/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @param {Object} props
 * @param {Object} props.attributes
 * @return {Element} Element to render.
 */
export default function Save( { attributes } ) {
	const { title } = attributes;

	return (
		<RichText.Content
			{ ...useBlockProps.save() }
			tagName="h2"
			value={ title }
		/>
	);
}
