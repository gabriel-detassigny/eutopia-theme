/**
 * Polyfill `window.requestIdleCallback` in Safari.
 */
export async function maybePolyfillRequestIdleCallback() {
	if ( ! window.requestIdleCallback ) {
		await import( 'requestidlecallback-polyfill' );
	}
}
