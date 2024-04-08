/* eslint-disable @wordpress/no-unsafe-wp-apis */
/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { link, linkOff } from '@wordpress/icons';
import { __ } from '@wordpress/i18n';
import { useEffect, useState, useRef } from '@wordpress/element';
import {
	TextControl,
	ToolbarButton,
	Popover,
	RangeControl,
	PanelBody,
} from '@wordpress/components';
import {
	AlignmentControl,
	BlockControls,
	InspectorControls,
	RichText,
	useBlockProps,
	__experimentalUseBorderProps as useBorderProps,
	__experimentalUseColorProps as useColorProps,
	__experimentalGetSpacingClassesAndStyles as useSpacingProps,
	__experimentalLinkControl as LinkControl,
	__experimentalGetElementClassName,
} from '@wordpress/block-editor';
import { displayShortcut, isKeyboardEvent } from '@wordpress/keycodes';
import { createBlock } from '@wordpress/blocks';
import { useMergeRefs } from '@wordpress/compose';
/**
 * Internal dependencies
 */
import IconCollectionControl from '../icon/icon-collection-control';
import { Icon } from '../icon/icon';
import ButtonGroupController from './button-group-controller';

const NEW_TAB_REL = 'noreferrer noopener';

function WidthPanel( { selectedWidth, setAttributes } ) {
	function handleChange( newWidth ) {
		// Check if we are toggling the width off
		const width = selectedWidth === newWidth ? undefined : newWidth;

		// Update attributes.
		setAttributes( { width } );
	}

	const options = [ 25, 50, 75, 100 ].map( ( widthValue ) => {
		return {
			name: widthValue,
			label: `${ widthValue }%`,
		};
	} );

	return (
		<ButtonGroupController
			selectedOption={ selectedWidth }
			options={ options }
			onClick={ ( name ) => handleChange( name ) }
			title={ __( 'Width settings', 'eutopia-theme' ) }
			isSmall={ true }
		/>
	);
}

function ButtonStylePanel( { selectedStyle, setAttributes } ) {
	const options = [
		{
			name: 'default',
			label: __( 'Text', 'eutopia-theme' ),
		},
		{
			name: 'iconTextButton',
			label: __( 'Text + Icon', 'eutopia-theme' ),
		},
		{
			name: 'icon',
			label: __( 'Icon', 'eutopia-theme' ),
		},
	];
	return (
		<ButtonGroupController
			selectedOption={ selectedStyle }
			options={ options }
			onClick={ ( name ) => setAttributes( { buttonStyle: name } ) }
			title={ __( 'Button style', 'eutopia-theme' ) }
		/>
	);
}

function IconPositionPanel( { currentPosition, setAttributes } ) {
	const options = [
		{
			name: 'before',
			label: __( 'Before', 'eutopia-theme' ),
		},
		{
			name: 'after',
			label: __( 'After', 'eutopia-theme' ),
		},
	];
	return (
		<ButtonGroupController
			selectedOption={ currentPosition }
			options={ options }
			onClick={ ( name ) => setAttributes( { iconPosition: name } ) }
			title={ __( 'Icon position', 'eutopia-theme' ) }
		/>
	);
}

function ButtonEdit( props ) {
	const {
		attributes,
		setAttributes,
		className,
		isSelected,
		onReplace,
		mergeBlocks,
	} = props;
	const {
		textAlign,
		textColor,
		linkTarget,
		placeholder,
		rel,
		style,
		text,
		url,
		width,
		buttonStyle,
		iconPosition,
		icon,
		iconCollection,
		ariaLabel,
		iconSize,
	} = attributes;

	function onToggleOpenInNewTab( value ) {
		const newLinkTarget = value ? '_blank' : undefined;

		let updatedRel = rel;
		if ( newLinkTarget && ! rel ) {
			updatedRel = NEW_TAB_REL;
		} else if ( ! newLinkTarget && rel === NEW_TAB_REL ) {
			updatedRel = undefined;
		}

		setAttributes( {
			linkTarget: newLinkTarget,
			rel: updatedRel,
		} );
	}

	function setButtonText( newText ) {
		// Remove anchor tags from button text content.
		setAttributes( { text: newText.replace( /<\/?a[^>]*>/g, '' ) } );
	}

	function onKeyDown( event ) {
		if ( isKeyboardEvent.primary( event, 'k' ) ) {
			startEditing( event );
		} else if ( isKeyboardEvent.primaryShift( event, 'k' ) ) {
			unlink();
			richTextRef.current?.focus();
		}
	}

	// Use internal state instead of a ref to make sure that the component
	// re-renders when the popover's anchor updates.
	const [ popoverAnchor, setPopoverAnchor ] = useState( null );

	const borderProps = useBorderProps( attributes );
	const colorProps = useColorProps( attributes );
	const textColorProps = useColorProps( { textColor } );
	const spacingProps = useSpacingProps( attributes );
	const ref = useRef();
	const richTextRef = useRef();
	const blockProps = useBlockProps( {
		ref: useMergeRefs( [ setPopoverAnchor, ref ] ),
		onKeyDown,
	} );

	const [ isEditingURL, setIsEditingURL ] = useState( false );
	const isURLSet = !! url;
	const opensInNewTab = linkTarget === '_blank';

	function startEditing( event ) {
		event.preventDefault();
		setIsEditingURL( true );
	}

	function unlink() {
		setAttributes( {
			url: undefined,
			linkTarget: undefined,
			rel: undefined,
		} );
		setIsEditingURL( false );
	}

	useEffect( () => {
		if ( ! isSelected ) {
			setIsEditingURL( false );
		}
	}, [ isSelected ] );

	return (
		<>
			<div
				{ ...blockProps }
				className={ classnames( blockProps.className, {
					[ `has-custom-width wp-block-gabriel-detassigny-button__width-${ width }` ]:
						width,
					[ `has-custom-font-size` ]: blockProps.style.fontSize,
				} ) }
			>
				<div
					className={ classnames( 'wp-block-gabriel-detassigny-button__link',
						borderProps.className,
						__experimentalGetElementClassName( 'button' ),
						colorProps.className,
					) }
					style={ {
						...colorProps.style,
						...spacingProps.style,
						...borderProps.style,
					} }
				>
					{ buttonStyle !== 'icon' &&
						<RichText
							ref={ richTextRef }
							aria-label={ __( 'Button text', 'eutopia-theme' ) }
							placeholder={ placeholder || __( 'Add text…', 'eutopia-theme' ) }
							value={ text }
							onChange={ ( value ) => setButtonText( value ) }
							withoutInteractiveFormatting
							className={ classnames(
								className,
								'wp-block-gabriel-detassigny-button__link-text',
								textColorProps.className,
								{
									[ `has-text-align-${ textAlign }` ]: textAlign,
									// For backwards compatibility add style that isn't
									// provided via block support.
									'no-border-radius': style?.border?.radius === 0,
								},
							) }
							style={ {
								...colorProps.style,
								...spacingProps.style,
							} }
							onSplit={ ( value ) =>
								createBlock( 'gabriel-detassigny/button', {
									...attributes,
									text: value,
								} )
							}
							onReplace={ onReplace }
							onMerge={ mergeBlocks }
							identifier="text"
						/>
					}
					{ buttonStyle !== 'default' &&
						<Icon
							className={ classnames( 'wp-block-gabriel-detassigny-button__link-icon',
								`has-icon-position-${ iconPosition }`,
								textColorProps.className,
							) }
							iconCollection={ iconCollection }
							name={ icon }
							style={ { height: `${ iconSize }px`, width: `${ iconSize }px` } }
						/>
					}
				</div>
			</div>
			<BlockControls group="block">
				<AlignmentControl
					value={ textAlign }
					onChange={ ( nextAlign ) => {
						setAttributes( { textAlign: nextAlign } );
					} }
				/>
				{ ! isURLSet && (
					<ToolbarButton
						name="link"
						icon={ link }
						title={ __( 'Link', 'eutopia-theme' ) }
						shortcut={ displayShortcut.primary( 'k' ) }
						onClick={ startEditing }
					/>
				) }
				{ isURLSet && (
					<ToolbarButton
						name="link"
						icon={ linkOff }
						title={ __( 'Unlink', 'eutopia-theme' ) }
						shortcut={ displayShortcut.primaryShift( 'k' ) }
						onClick={ unlink }
						isActive={ true }
					/>
				) }
			</BlockControls>
			{ isSelected && ( isEditingURL || isURLSet ) && (
				<Popover
					placement="bottom"
					onClose={ () => {
						setIsEditingURL( false );
						richTextRef.current?.focus();
					} }
					anchor={ popoverAnchor }
					focusOnMount={ isEditingURL ? 'firstElement' : false }
					__unstableSlotName={ '__unstable-block-tools-after' }
					shift
				>
					<LinkControl
						className="wp-block-navigation-link__inline-link-input"
						value={ { url, opensInNewTab } }
						onChange={ ( {
							url: newURL = '',
							opensInNewTab: newOpensInNewTab,
						} ) => {
							setAttributes( { url: newURL } );

							if ( opensInNewTab !== newOpensInNewTab ) {
								onToggleOpenInNewTab( newOpensInNewTab );
							}
						} }
						onRemove={ () => {
							unlink();
							richTextRef.current?.focus();
						} }
						forceIsEditingLink={ isEditingURL }
					/>
				</Popover>
			) }
			<InspectorControls>
				<ButtonStylePanel
					selectedStyle={ buttonStyle }
					setAttributes={ setAttributes }
				/>

				{ buttonStyle === 'iconTextButton' && (
					<IconPositionPanel
						currentPosition={ iconPosition }
						setAttributes={ setAttributes }
					/>
				) }

				{ buttonStyle !== 'default' && (
					<>
						<IconCollectionControl
							icon={ icon }
							iconCollection={ iconCollection }
							onChange={ ( _icon ) => {
								setAttributes( {
									icon: _icon.name,
									source: _icon.source,
									iconCollection: _icon.iconCollection,
								} );
							} }
						/>
						<PanelBody title={ __( 'Icon size', 'eutopia-theme' ) } initialOpen={ true }>
							<RangeControl
								className={ 'wp-block-gabriel-detassigny-button__custom-range-control' }
								label={ __( 'Pick custom size(in px)', 'eutopia-theme' ) }
								value={ iconSize }
								onChange={ ( size ) => setAttributes( { iconSize: size } ) }
								min={ 10 }
								initialPosition={ iconSize }
								max={ 50 }
							/>
						</PanelBody>
					</>
				) }
				<WidthPanel
					selectedWidth={ width }
					setAttributes={ setAttributes }
				/>
			</InspectorControls>
			<InspectorControls __experimentalGroup="advanced">
				<TextControl
					label={ __( 'Aria label', 'eutopia-theme' ) }
					value={ ariaLabel || '' }
					onChange={ ( newAriaLabel ) => setAttributes( { ariaLabel: newAriaLabel } ) }
				/>
				<TextControl
					__nextHasNoMarginBottom
					label={ __( 'Link rel', 'eutopia-theme' ) }
					value={ rel || '' }
					onChange={ ( newRel ) => setAttributes( { rel: newRel } ) }
				/>
			</InspectorControls>
		</>
	);
}

export default ButtonEdit;
