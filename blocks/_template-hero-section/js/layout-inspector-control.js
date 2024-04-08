/**
 * WordPress dependencies
 */
import { Button, Flex, FlexBlock, FlexItem, PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { IconLayout5050, IconLayoutFullWidth } from './icons';

const LAYOUT = {
	FIFTY_FIFTY: '50-50',
	FULL_WIDTH: 'full-width',
};

export function LayoutInspectorControl( props ) {
	const { attributes, setAttributes } = props;
	const { layout } = attributes;

	return (
		<PanelBody title={ __( 'Layout', 'eutopia-theme' ) }>
			<Flex justify="center">
				<FlexItem>
					<Button
						variant={ layout === LAYOUT.FIFTY_FIFTY ? 'secondary' : 'default' }
						onClick={ () => {
							setAttributes( {
								layout: LAYOUT.FIFTY_FIFTY,
							} );
						} }
						style={ { height: 'auto' } }
					>
						<Flex direction="column" gap="0">
							<FlexBlock as="span">
								<IconLayout5050 />
							</FlexBlock>
							<FlexBlock as="span">
								{ __( '50/50 layout', 'eutopia-theme' ) }
							</FlexBlock>
						</Flex>
					</Button>
				</FlexItem>
				<FlexItem>
					<Button
						variant={ layout === LAYOUT.FULL_WIDTH ? 'secondary' : 'default' }
						onClick={ () => {
							setAttributes( {
								layout: LAYOUT.FULL_WIDTH,
							} );
						} }
						style={ { height: 'auto' } }
					>
						<Flex direction="column" gap="0">
							<FlexBlock as="span">
								<IconLayoutFullWidth />
							</FlexBlock>
							<FlexBlock as="span">
								{ __( 'Full-width layout', 'eutopia-theme' ) }
							</FlexBlock>
						</Flex>
					</Button>
				</FlexItem>
			</Flex>
		</PanelBody>
	);
}
