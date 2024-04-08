/**
 * WordPress dependencies
 */
import { columns as icon } from '@wordpress/icons';
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import Edit from './js/edit';
import Save from './js/save';
import variations from './js/variations';
import transforms from './js/transforms';

registerBlockType(
	metadata.name,
	{
		icon,
		variations,
		transforms,
		edit: Edit,
		save: Save,
		example: {
			viewportWidth: 600, // Columns collapse "@media (max-width: 599px)".
			innerBlocks: [
				{
					name: 'gabriel-detassigny/column',
					innerBlocks: [
						{
							name: 'core/paragraph',
							attributes: {
								/* translators: example text. */
								content: __(
									'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent et eros eu felis.'
								),
							},
						},
						{
							name: 'core/image',
							attributes: {
								url: 'https://s.w.org/images/core/5.3/Windbuchencom.jpg',
							},
						},
						{
							name: 'core/paragraph',
							attributes: {
								/* translators: example text. */
								content: __(
									'Suspendisse commodo neque lacus, a dictum orci interdum et.'
								),
							},
						},
					],
				},
				{
					name: 'gabriel-detassigny/column',
					innerBlocks: [
						{
							name: 'core/paragraph',
							attributes: {
								/* translators: example text. */
								content: __(
									'Etiam et egestas lorem. Vivamus sagittis sit amet dolor quis lobortis. Integer sed fermentum arcu, id vulputate lacus. Etiam fermentum sem eu quam hendrerit.'
								),
							},
						},
						{
							name: 'core/paragraph',
							attributes: {
								/* translators: example text. */
								content: __(
									'Nam risus massa, ullamcorper consectetur eros fermentum, porta aliquet ligula. Sed vel mauris nec enim.'
								),
							},
						},
					],
				},
			],
		},
	}
);
