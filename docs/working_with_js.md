Working with JS

:back: [Home](../README.md)

Table of Contents

- [Enqueuing Scripts](#enqueuing-scripts)
	- [Custom `<script>` Attributes](#custom-script-attributes)
		- [Notes on using `type="module"`, `nomodule`, and `defer`/`async`](#notes-on-using-typemodule-nomodule-and-deferasync)
	- [Adding a new JS entry](#adding-a-new-js-entry)
	- [Adding a new Webpack config object](#adding-a-new-webpack-config-object)
- [Testing](#testing)
- [Browser polyfills](#browser-polyfills)
- [Dynamic code splitting](#dynamic-code-splitting)
- [Helpers](#helpers)
	- [Overlay helper](#overlay-helper)
	- [Open overlay](#open-overlay)
	- [Focus](#focus)
	- [Body Class](#body-class)
	- [Close overlay](#close-overlay)
	- [HTML example](#html-example)
	- [Content overlay CSS](#content-overlay-css)

## Enqueuing Scripts

In order to register or enqueue a script when using Eutopia Theme, use the Core methods like `wp_register_script` and `wp_enqueue_script`. You may find the following methods useful:

- `gdt_theme_meta()->url()` for retrieving a URL of an asset
- `gdt_theme_meta()->path()` for retrieving an absolute path of an asset

Typically, when using the bundled Webpack setup, you will want to use the auto-generated `.asset.php` file when enqueueing a script. It exposes a list of dependencies and a version hash that should be used in the `wp_register_script`/`wp_enqueue_script` call.

```php
$main_js_url        = gdt_theme_meta()->url( 'build', 'js', 'main.js' );
$main_js_asset_path = gdt_theme_meta()->path( 'build', 'js', 'main.asset.php' );
$main_js_asset      = $this->get_script_asset( $main_js_asset_path );
wp_enqueue_script(
	'main',
	$main_js_url,
	$main_js_asset['dependencies'],
	$main_js_asset['version'],
	true
);
```

### Custom `<script>` Attributes

Eutopia Theme supports the following `<script>` attributes:

- `async`
- `defer`
- `type="module"`
- `nomodule`

They can be added to any registered script by using a Core function `wp_script_add_data`:

```php
wp_script_add_data( 'main', 'defer', true );
wp_script_add_data( 'main', 'async', true );
wp_script_add_data( 'main', 'nomodule', true );
wp_script_add_data( 'main', 'type', 'module' );
```

There's currently [no official WP support :arrow_upper_right:](https://core.trac.wordpress.org/ticket/12009) to include these HTML5 attributes to the script tag, but Eutopia Theme allows you to do so.

#### Notes on using `type="module"`, `nomodule`, and `defer`/`async`

According to [MDN docs :arrow_upper_right:](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/script#attr-defer):

> The `defer` attribute has no effect on module scripts — they defer by default.

On the other hand, scripts marked as `nomodule` may (and usually should) `defer` or be loaded in an `async` fashion.

### Adding a new JS entry

If you would like to add a new JS entry, all you need to do is to create a new JS file in the `js` folder, e.g.:

```bash
/js/new-entry.js`
```

When you rerun Webpack build script, the new entry file will be automatically picked up and compiled.

**Note:** Any file with a `.js` extension that is located at the root level of the `js` folder will be treated as a separate entry file. Keep your JS modules in the sub-folders.

Don't forget to enqueue the new script. This is usually done in the Scripts class ([`php/components/class-scripts.php`](../php/components/class-scripts.php)).

### Adding a new Webpack config object

In rare cases, you may have to define a separate Webpack build workflow for your files.

In order to do that, clone the `scripts` config object and do the necessary changes there, e.g.:

```js
const custom = {
	...defaultConfig,
	entry: './path/to/my/entry/file.js',
	plugins: [
		...defaultConfig.plugins,
		new WebpackBar( {
			name: 'Custom',
			color: '#00ff00',
		} ),
	],
}
```

Don't forget to export it as another array item from `webpack.config.js`:

```js
module.exports = [ blocks, scripts, styles, custom ];
```

## Testing

JS files can have matching `*.test.js` files in the same directory containing the [Jest unit tests](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/#test-unit-js) for the respective files. The test files will be automatically picked up by `wp-scripts test-unit-js` during the `npm run test` command.

You can run `npm run test:js` to only run the js tests; this does not require that the local Docker environment is running.

During development, it can be helpful to watch the files and run the tests on changes using `npm run dev`.


## Browser polyfills

In general, most of the modern Javascript features are supported well across today's browsers.
However, there are cases when a particular browser API still needs polyfilling. For instance, in 2023 `window.requestIdleCallback` function is still not [supported in Safari :arrow_upper_right:](https://caniuse.com/requestidlecallback).

For such cases, you can make use of the `Polyfills.js` module. Right now, we use it to polyfill the `requestIdleCallback` method only for browsers that do not support it. We utilize dynamic module loading so that only older browsers are "penalized" for not supporting the modern feature:

```js
export async function maybePolyfillRequestIdleCallback() {
	if ( ! window.requestIdleCallback ) {
		await import(
			// webpackChunkName: 'requestidlecallback-polyfill'
			'requestidlecallback-polyfill' );
	}
}
```

## Dynamic code splitting

Support for [dynamic imports :arrow_upper_right:](https://webpack.js.org/guides/code-splitting/#dynamic-imports) is present.

Example usage of loading a dynamic module inside an async function:

```js
const { default: DynamicModuleExample } = await import(
// webpackChunkName: 'module-example'
'./modules/DynamicModuleExample.js' );
DynamicModuleExample.init();
```

Loading a dynamic module when the mark-up requiring it is present in the DOM. This supports dynamically injected elements as well:

```js
const loadCarouselWhenNeeded = async () => {
	const carouselSelector = '.carousel';

	const loadCarousel = async () => {
		const carouselElmsToObserve = document.querySelector( carouselSelector );
			if (carouselElmsToObserve) {
				const { default: carouselModule } = await import(
				// webpackChunkName: 'carousel'
				'./module/carousel.js' );
				carouselModule.init( carouselSelector );
			}
		};

	// Also load when more carousels are added dynamically to the DOM.
	const carouselObserver = new MutationObserver( loadCarousel );
	carouselObserver.observe( document, { subtree: true, childList: true } );
};

loadCarouselWhenNeeded();
```

## Helpers

### Overlay helper

```bash
/js/src/modules/overlay-helper.js`
```

The goal of this helper is to create the necessary events for opening and closing an overlay, such as a mobile navigation or a search panel. If you need to create a dialog box, or a similar interactive component, it would be better to use the [dialog element :arrow_upper_right:](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/dialog).

### Open overlay

The trigger needs to be a button element:

```html
<button data-target="#overlaid" class="js-use-overlay">Open</button>
```

When this trigger is clicked:

- the element with the ID specified inside `data-target` will receive the class `is-overlaid`.
- An overlay will be displayed on the page.
- The body will not be scrollable anymore (default behaviour).

If you want to allow the user to scroll the page, set the data-scroll attribute to true on the trigger element: `data-scroll="true"`.

If you want to turn off the backdrop, set the data-backdrop attribute to false on the trigger element: `data-backdrop="false"`.

```html
<button data-target="#search-box" data-scroll="true" data-backdrop="false" class="js-use-overlay">Open Search</button>

<div id="search-box" class="search-box"
	role="search" aria-modal="true"
	aria-live="assertive" aria-labelledby="Search Box" >
	<p>Search</p>
	<button type="button" data-dismiss="modal" aria-labelledby="close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
```

An element with the class `js-site-overlay-backdrop` will be created dynamically.

This overlay backdrop element is sized to occupy the entire viewport, and is displayed with a transition and a background color.
The style of this "overlay" is set inside `/css/src/utilities/_overlay.scss`.
The background color and the transition should be modified to fit your design.

### Focus

By default, the first focusable element of the content of the overlay will have the focus. It's possible to select a specific element to have the focus. This is done bt adding a data attribute to the trigger: `data-focus=".class"` or `data-focus="#ID"`.

### Body Class

When you open an element, the body will receive a class. This class is the ID of the target + `-is-open`. This class will let you modify the style of the overlay. For example if the overlay needs to begin after the mobile header, like the search box.

This class could also be used to set a different style to the trigger once the overlay is active.

### Close overlay

This overlay element could be closed with hitting of the `ESC` key or with a click on the overlay backdrop. There is also a method available to close the current overlay with a specific button.

```html
<button type="button" data-dismiss="modal" aria-label="Close">
	<span aria-hidden="true">&times;</span>
</button>
```

This could be for example the close icon in a search panel or a mobile navigation. A click on it will close the overlay.

### HTML example

```html
<button data-target="#overlaid" class="js-use-overlay">Open</button>

<div id="overlaid" class="is-centered" aria-modal="true" aria-live="assertive">
	<p>Overlay!</p>
	<button type="button" data-dismiss="modal" aria-labelledby="close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
```

### Content overlay CSS

The CSS is minimal. The overlay element receives the class `is-overlaid` when interacting with the trigger, which sets it to receive a fixed position style and a z-index value superior to the overlay backdrop element. The elements to be used as overlays require the styling for hiding and configuring the transition animation.
