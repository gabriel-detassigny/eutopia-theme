/**
 * WordPress dependencies
 */
import {
	PanelBody,
	SelectControl,
	ToggleControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	RichText,
} from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 * @param {Object} props Block properties
 * @return {Element} Element to render.
 */

export default function Edit( props ) {
	const { setAttributes, attributes } = props;
	const { summary, summaryTag, content, openDetail } = attributes;

	const onSummaryChange = ( value ) => {
		setAttributes( { summary: value } );
	};

	const onContentChange = ( value ) => {
		setAttributes( { content: value } );
	};

	const onChangeHeading = ( value ) => {
		setAttributes( { summaryTag: value } );
	};

	const onChangeOpen = () => {
		setAttributes( { openDetail: ! openDetail } );
	};

	const propsObject = {};
	propsObject.className = 'c-details';
	if ( true === openDetail ) {
		propsObject.open = true;
	}
	const blockProps = useBlockProps( propsObject );

	return (
		<>
			<details { ...blockProps }>
				<summary>
					<RichText
						tagName={ summaryTag }
						value={ summary }
						onChange={ onSummaryChange }
						placeholder={ __( 'Summary', 'eutopia-theme' ) }
					/>
				</summary>
				<RichText
					tagName="div"
					className="c-details__content"
					multiline="p"
					value={ content }
					onChange={ onContentChange }
					placeholder={ __( 'Content', 'eutopia-theme' ) }
				/>
			</details>
			<InspectorControls>
				<PanelBody
					title={ __( 'Settings', 'eutopia-theme' ) }
					initialOpen={ true }
				>
					<SelectControl
						label={ __( 'Summary tag', 'eutopia-theme' ) }
						help={ __(
							'The tag span is the default one',
							'eutopia-theme'
						) }
						value={ summaryTag }
						options={ [
							{
								label: __( 'span', 'eutopia-theme' ),
								value: 'span',
							},
							{ label: __( 'Heading 1', 'eutopia-theme' ), value: 'h1' },
							{ label: __( 'Heading 2', 'eutopia-theme' ), value: 'h2' },
							{ label: __( 'Heading 3', 'eutopia-theme' ), value: 'h3' },
							{ label: __( 'Heading 4', 'eutopia-theme' ), value: 'h4' },
						] }
						onChange={ onChangeHeading }
					/>
					<ToggleControl
						label={ __(
							'Open the detail by default',
							'eutopia-theme'
						) }
						checked={ openDetail }
						onChange={ onChangeOpen }
					/>
				</PanelBody>
			</InspectorControls>
		</>
	);
}
