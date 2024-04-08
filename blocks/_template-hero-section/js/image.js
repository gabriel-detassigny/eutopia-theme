/**
 * WordPress dependencies
 */
import { Spinner } from '@wordpress/components';

export function Image( props ) {
	const {
		media,
		isLoading = false,
		size = 'full',
		focalPoint = undefined,
		...rest
	} = props;

	if ( ! Boolean( media ) ) {
		return null;
	}

	if ( isLoading ) {
		return <Spinner />;
	}

	const imageUrl = media?.media_details?.sizes[ size ]?.source_url ?? media?.source_url;
	const altText = media?.alt_text;

	if ( Boolean( focalPoint ) ) {
		const focalPointStyle = {
			objectFit: 'cover',
			objectPosition: `${ focalPoint.x * 100 }% ${ focalPoint.y * 100 }%`,
		};

		rest.style = {
			...rest.style,
			...focalPointStyle,
		};
	}

	return <img src={ imageUrl } alt={ altText } { ...rest } />;
}
