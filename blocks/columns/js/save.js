/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { useInnerBlocksProps, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { verticalAlignment, variationClass } = attributes;

	const className = classnames( `grid ${ variationClass }`, {
		[ `are-vertically-aligned-${ verticalAlignment }` ]: verticalAlignment,
	} );

	const blockProps = useBlockProps.save( {
		className,
	} );
	const innerBlocksProps = useInnerBlocksProps.save( blockProps );

	return <div { ...innerBlocksProps } />;
}
