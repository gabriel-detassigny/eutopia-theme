/**
 * Get current CSS media query (breakpoint) value.
 *
 * @return {string} Current breakpoint value.
 */
export function getCssMediaQuery() {
	return window.getComputedStyle( document.documentElement, '::after' ).content.replaceAll( /["']/g, '' );
}
