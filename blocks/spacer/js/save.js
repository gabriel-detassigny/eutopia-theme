/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';

export default function save( props ) {
	const { height, tabletHeight, mobileHeight } = props.attributes;

	return (
		<div
			{ ...useBlockProps.save( {
				style: {
					'--spacing-mobile': `var(--spacing--${ mobileHeight })`,
					'--spacing-tablet': `var(--spacing--${ tabletHeight })`,
					'--spacing-desktop': `var(--spacing--${ height })`,
				},
				'aria-hidden': true,
			} ) }
		/>
	);
}
