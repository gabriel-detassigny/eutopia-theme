/**
 * WordPress dependencies
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Disabled } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import Panels from './components/inspector-panels';
import ContentLoading from './components/loading';
// eslint-disable-next-line no-undef
const atts = CURATED_CONTENT_ATTS ?? {};

/**
 * Edit
 *
 * @param {Object} props
 */
export default function Edit( props ) {
	const blockProps = useBlockProps();
	const { clientId, attributes } = props;

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<Panels { ...props } panels={ atts.panels } sourceTypes={ atts.sourceTypes } />
			</InspectorControls>

			<Disabled id={ props.clientId }>
				<ServerSideRender
					block={ props.name }
					attributes={ attributes }
					clientId={ clientId }
					LoadingResponsePlaceholder={ ContentLoading }
				/>
			</Disabled>
		</div>
	);
}
