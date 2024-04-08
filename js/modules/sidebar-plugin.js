/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';
import { Panel, PanelBody, PanelRow } from '@wordpress/components';

export function SidebarPlugin() {
	return (
		<>
			<PluginSidebarMoreMenuItem
				target="gabriel-detassigny-sidebar"
			>
				{ __( 'Eutopia Theme', 'eutopia-theme' ) }
			</PluginSidebarMoreMenuItem>
			<PluginSidebar
				name="gabriel-detassigny-sidebar"
				title={ __( 'Eutopia Theme', 'eutopia-theme' ) }
			>
				<Panel>
					<PanelBody
						title={ __( 'Sidebar Panel', 'eutopia-theme' ) }
						initialOpen={ true }
					>
						<PanelRow>
							{ __( 'Hello World!', 'eutopia-theme' ) }
						</PanelRow>
					</PanelBody>
				</Panel>
			</PluginSidebar>
		</>
	);
}
