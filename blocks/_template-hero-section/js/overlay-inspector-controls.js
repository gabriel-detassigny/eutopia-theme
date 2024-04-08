/* eslint-disable @wordpress/no-unsafe-wp-apis */
/**
 * WordPress dependencies
 */
import {
	__experimentalUseGradient,
	__experimentalPanelColorGradientSettings as PanelColorGradientSettings,
} from '@wordpress/block-editor';
import { Flex, FlexBlock, Notice, RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export function OverlayInspectorControls( props ) {
	const {
		attributes,
		setAttributes,
		overlayColor,
		setOverlayColor,
		overlayBackgroundColor,
		setOverlayBackgroundColor,
	} = props;

	const { dimRatio } = attributes;
	const { gradientValue, setGradient } = __experimentalUseGradient( {
		gradientAttribute: 'overlayBackgroundGradient',
		customGradientAttribute: 'customOverlayBackgroundGradient',
	} );

	return (
		<PanelColorGradientSettings
			__experimentalHasMultipleOrigins
			__experimentalIsRenderedInSidebar
			title={ __( 'Overlay', 'eutopia-theme' ) }
			className="hero-section-overlay-inspector-control"
			initialOpen
			settings={ [
				{
					colorValue: overlayColor.color,
					onColorChange: setOverlayColor,
					label: __( 'Color', 'eutopia-theme' ),
				},
				{
					colorValue: overlayBackgroundColor.color,
					gradientValue,
					onColorChange: setOverlayBackgroundColor,
					onGradientChange: setGradient,
					label: __( 'Background', 'eutopia-theme' ),
				},
			] }
		>
			<Flex direction="column" gap="4">
				<FlexBlock>
					<Notice
						isDismissible={ false }
						status="info"
					>
						{ __( 'These colors are used only on large screens.', 'eutopia-theme' ) }
					</Notice>
				</FlexBlock>
				<FlexBlock>
					<RangeControl
						label={ __( 'Overlay opacity', 'eutopia-theme' ) }
						value={ dimRatio }
						onChange={ ( newDimRation ) =>
							setAttributes( {
								dimRatio: newDimRation,
							} )
						}
						min={ 0 }
						max={ 100 }
						step={ 10 }
						required
					/>
				</FlexBlock>
			</Flex>
		</PanelColorGradientSettings>
	);
}
