/**
 * WordPress dependencies
 */
import { useBlockProps, RichText, InspectorControls, withColors } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { compose } from '@wordpress/compose';
import { useSelect } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { LinkInspectorControl } from './link-inspector-control';
import { OverlayInspectorControls } from './overlay-inspector-controls';
import { LayoutInspectorControl } from './layout-inspector-control';
import { ImageInspectorControl } from './image-inspector-control';
import { Image } from './image';

const ALLOWED_FORMATS = [
	'core/annotation',
	'core/bold',
	'core/italic',
	'core/strikethrough',
	'core/underline',
	'core/text-color',
	'core/subscript',
	'core/superscript',
];

function Edit( props ) {
	const { attributes, setAttributes, isSelected } = props;
	const {
		imageId,
		focalPoint,
		heading,
		meta,
		description,
		layout,
		dimRatio,
		overlayColor,
		customOverlayColor,
		overlayBackgroundColor,
		customOverlayBackgroundColor,
		overlayBackgroundGradient,
		customOverlayBackgroundGradient,
	} = attributes;

	const isFullWidth = layout === 'full-width';

	const { media, isResolvingMedia } = useSelect(
		( select ) => {
			const mediaParameters = [ imageId, { context: 'view' } ];
			return {
				media: select( 'core' ).getMedia( ...mediaParameters ),
				isResolvingMedia: select( 'core' ).isResolving( 'getMedia', mediaParameters ),
			};
		},
		[ imageId ],
	);

	const style = {};
	if ( isFullWidth ) {
		style[ '--hero-section--dim-ratio' ] = `${ dimRatio }%`;

		if ( overlayBackgroundGradient ) {
			style[ '--hero-section--overlay-background' ] = `var(--wp--preset--gradient--${ overlayBackgroundGradient })`;
		} else if ( customOverlayBackgroundGradient ) {
			style[ '--hero-section--overlay-background' ] = customOverlayBackgroundGradient;
		} else if ( overlayBackgroundColor ) {
			style[ '--hero-section--overlay-background' ] = `var(--wp--preset--color--${ overlayBackgroundColor })`;
		} else if ( customOverlayBackgroundColor ) {
			style[ '--hero-section--overlay-background' ] = customOverlayBackgroundColor;
		}

		if ( overlayColor ) {
			style[ '--hero-section--overlay-color' ] = `var(--wp--preset--color--${ overlayColor })`;
		} else if ( customOverlayColor ) {
			style[ '--hero-section--overlay-color' ] = customOverlayColor;
		}
	}

	const blockProps = useBlockProps( {
		className: `hero-section hero-section--layout-${ layout }`,
		style,
	} );

	return (
		<>
			<InspectorControls>
				<LayoutInspectorControl { ...props } />
				{ isFullWidth && <OverlayInspectorControls { ...props } /> }
				<LinkInspectorControl { ...props } />
				<ImageInspectorControl
					media={ media }
					isLoading={ isResolvingMedia }
					{ ...props }
				/>
			</InspectorControls>
			<section { ...blockProps }>
				<div className="hero-section__wrap">
					<figure className="hero-section__image">
						<Image
							isLoading={ isResolvingMedia }
							media={ media }
							size="full"
							focalPoint={ focalPoint }
						/>
					</figure>
					<div className="hero-section__content">
						{ ( Boolean( meta ) || isSelected ) && (
							<div className="hero-section__meta">
								<RichText
									tagName="p"
									placeholder={ __( 'Add meta here', 'eutopia-theme' ) }
									value={ meta }
									onChange={ ( value ) => {
										setAttributes( { meta: value } );
									} }
									multiline
									allowedFormats={ ALLOWED_FORMATS }
								/>
							</div>
						) }
						{ ( Boolean( heading ) || isSelected ) && (
							<RichText
								className="hero-section__heading"
								tagName="h2"
								placeholder={ __( 'Add a heading here', 'eutopia-theme' ) }
								value={ heading }
								onChange={ ( value ) => {
									setAttributes( { heading: value } );
								} }
								allowedFormats={ ALLOWED_FORMATS }
							/>
						) }
						{ ( Boolean( description ) || isSelected ) && (
							<div className="hero-section__description">
								<RichText
									tagName="p"
									placeholder={ __( 'Add short description here.', 'eutopia-theme' ) }
									value={ description }
									onChange={ ( value ) => {
										setAttributes( { description: value } );
									} }
									multiline
									allowedFormats={ ALLOWED_FORMATS }
								/>
							</div>
						) }
					</div>
					{ isFullWidth && dimRatio > 0 && (
						<div className="hero-section__overlay" />
					) }
				</div>
			</section>
		</>
	);
}

export default compose( [
	withColors( {
		overlayColor: 'overlay-color',
		overlayBackgroundColor: 'overlay-background-color',
	} ),
] )( Edit );
