/**
 * WordPress dependencies
 */
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button, Flex, FlexBlock, FlexItem, FocalPointPicker, Notice, PanelBody, Spinner } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export function ImageInspectorControl( props ) {
	const { attributes, setAttributes, media, isLoading } = props;
	const { imageId, focalPoint = undefined } = attributes;
	const hasImage = Boolean( imageId );
	const hasFocalPoint = Boolean( focalPoint );
	const imageUrl = media?.media_details?.sizes.medium?.source_url ?? media?.source_url;

	function setImage( image ) {
		setAttributes( { imageId: image.id } );
	}

	function removeImage() {
		setAttributes( { imageId: '' } );
	}

	return (
		<PanelBody
			title={ __( 'Image', 'eutopia-theme' ) }
			className="hero-section-image-inspector-control"
		>
			{ isLoading ? (
				<Spinner />
			) : (
				<Flex direction="column" gap="4">
					{ ! hasImage && (
						<FlexBlock>
							<Notice isDismissible={ false } status="warning">
								{ __( 'No image has been selected.', 'eutopia-theme' ) }
							</Notice>
						</FlexBlock>
					) }
					<FlexBlock>
						<MediaUploadCheck>
							<Flex direction="row" justify="start" gap="3">
								<FlexItem>
									<MediaUpload
										onSelect={ setImage }
										render={ ( { open } ) => (
											<Button onClick={ open } variant="secondary">
												{ hasImage
													? __( 'Replace Image', 'eutopia-theme' )
													: __( 'Add Image', 'eutopia-theme' ) }
											</Button>
										) }
									/>
								</FlexItem>
								{ hasImage && (
									<FlexItem>
										<Button
											isDestructive
											variant="link"
											onClick={ removeImage }
										>
											{ __( 'Remove Image', 'eutopia-theme' ) }
										</Button>
									</FlexItem>
								) }
							</Flex>
						</MediaUploadCheck>
					</FlexBlock>
					{ hasImage && hasFocalPoint && ! isLoading && (
						<FlexBlock>
							<FocalPointPicker
								label={ __( 'Focal point', 'eutopia-theme' ) }
								url={ imageUrl }
								value={ focalPoint }
								onChange={ ( value ) => {
									setAttributes( { focalPoint: value } );
								} }
							/>
						</FlexBlock>
					) }
				</Flex>
			) }
		</PanelBody>
	);
}
