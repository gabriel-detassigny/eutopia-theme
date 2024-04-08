/**
 * WordPress dependencies
 */
import { Button, ExternalLink, Flex, FlexBlock, Notice, PanelBody, Popover } from '@wordpress/components';
//eslint-disable-next-line @wordpress/no-unsafe-wp-apis
import { __experimentalLinkControl as LinkControl } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

export const LinkInspectorControl = ( props ) => {
	const { attributes, setAttributes } = props;
	const { linkUrl } = attributes;

	const [ isOpen, setIsOpen ] = useState( false );
	const [ popoverAnchor, setPopoverAnchor ] = useState( null );

	const closePopover = () => {
		setIsOpen( false );
	};
	const togglePopover = () => {
		setIsOpen( ( state ) => ! state );
	};

	const onChange = ( link ) => {
		setAttributes( { linkUrl: link?.url || '' } );
		closePopover();
	};

	return (
		<PanelBody
			title={ __( 'Link URL', 'eutopia-theme' ) }
			className="hero-section-link-inspector-control"
		>
			<Flex direction="column" gap="3">
				<FlexBlock>
					{ linkUrl ? (
						<ExternalLink href={ linkUrl }>
							{ linkUrl }
						</ExternalLink>
					) : (
						<Notice
							isDismissible={ false }
							status="warning"
						>
							{ __( 'No link URL has been selected.', 'eutopia-theme' ) }
						</Notice>
					) }
				</FlexBlock>
				<FlexBlock>
					<Button
						variant="secondary"
						aria-expanded={ isOpen }
						onClick={ togglePopover }
						ref={ setPopoverAnchor }
					>
						{ linkUrl
							? __( 'Change Link', 'eutopia-theme' )
							: __( 'Select Link', 'eutopia-theme' ) }
					</Button>
				</FlexBlock>
			</Flex>
			{ isOpen && (
				<Popover
					anchor={ popoverAnchor }
					focusOnMount
					onClose={ closePopover }
				>
					<LinkControl
						value={ { url: linkUrl } }
						showInitialSuggestions
						onChange={ onChange }
						onRemove={ () => onChange() }
						settings={ [] }
					/>
				</Popover>
			) }
		</PanelBody>
	);
};
