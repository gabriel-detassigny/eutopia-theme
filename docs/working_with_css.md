Working with CSS

:back: [Home](../README.md)

Table of Contents

- [Styles Enqueueing](#styles-enqueueing)
- [Progressive loading of CSS](#progressive-loading-of-css)
	- [Watching SCSS files](#watching-scss-files)
	- [Building CSS files](#building-css-files)
	- [Fix linting errors](#fix-linting-errors)
	- [Running the linter](#running-the-linter)
- [Structure of the CSS folder](#structure-of-the-css-folder)
	- [style.scss](#stylescss)
		- [/tools](#tools)
			- [/tools/colors](#toolscolors)
			- [/tools/elevations](#toolselevations)
			- [/elements/reset](#elementsreset)
		- [/elements](#elements)
			- [/elements/reset](#elementsreset-1)
			- [/elements/links](#elementslinks)
			- [/elements/lists](#elementslists)
	- [lazy.scss](#lazyscss)
- [Theme.json](#themejson)
	- [Colors palette](#colors-palette)
	- [Typography](#typography)
	- [Spacing](#spacing)
	- [Layout](#layout)
	- [Global styles](#global-styles)
		- [`styles/color`](#stylescolor)
		- [`elements`](#elements-1)
		- [`styles/typography`](#stylestypography)
- [PostCSS](#postcss)
- [Font Subsetting](#font-subsetting)

## Styles Enqueueing

Each individual stylesheet is registered in the `get_css_files()` function found in `./php/components/class-styles.php` under the `$css_files` array:

```php
$css_files = array(
  'eutopia-theme-global'     => [
    'file'             => 'style.css',
    'global'           => true,
    'preload_callback' => '__return_true',
  ]
);
```

Each entry in the `$css_files` array must follow the same convention:

```php
'eutopia-theme-[label]' => [
  'file' => '[filename].min.css', // required
  'preload_callback' => '[callback function or __return_true]', // optional if file should be preloaded
  'global' => true, // optional if file should be loaded in every template
]
```

The `preload_callback` value controls whether the stylesheet is preloaded in the `<head>` section of the document. This improves performance in some circumstances, but counteracts the performance benefits of using [in-body <link> tags :arrow_upper_right:](https://jakearchibald.com/2016/link-in-body/) because stylesheets are loaded _before_ they are used. For stylesheets that should _not_ be preloaded, use:

```php
'preload_callback' => '__return_false'
```

Enqueueing registered stylesheets via the print_styles call in the specific template:

```php
\Eutopia_Theme\Components\Styles::get_instance()?->print_styles( 'eutopia-theme-comments' );
```

## Progressive loading of CSS

Eutopia Theme employs progressive loading of CSS through [in-body <link> tags :arrow_upper_right:](https://jakearchibald.com/2016/link-in-body/). Module-specific styles are held in module-specific stylesheets and loaded at module level. This is fundamentally different from how traditional WordPress themes handle CSS, and it has several advantages:

- The main stylesheet file size is reduced.
- Styles are only loaded if and when a module is present in the view.
- Stylesheets are associated with their modules making them easier to work with.
- Leverages HTTP/2 multiplexing and can be extended to include preloading, server push etc.
- Module-level stylesheets are cached and can be individually updated without forcing reload of all styles on the site.

Only one stylesheet, `global.css` is always loaded by every template. All other stylesheets are loaded by their respective modules. This is done using the `\Eutopia_Theme\Components\Styles::get_instance()?->print_styles()` which takes a stylesheet handle as its parameter.

Example:

```php
\Eutopia_Theme\Components\Styles::get_instance()?->print_styles( 'eutopia-theme-content' );
```

Tip: When preloading a stylesheet, use the console in Chrome developer tools to ensure no unnecessary stylesheets are loaded. A warning will appear letting you know if a stylesheet is preloaded but not used.

### Watching SCSS files

To watch the SCSS stylesheets inside the `css` folder and generate CSS files in the `build/css`, run:

```bash
npm run dev
```

### Building CSS files

Run the following script in order to build minified assets:

```bash
npm run build
````

### Fix linting errors

Fix simple kind of errors, like:

```bash
Expected double colon
pseudo-element notation
```

`npm run format:css`

This formatting is run automatically in the precommit hook.

### Running the linter

The linter configuration [@wordpress/stylelint-config :arrow_upper_right:](https://developer.wordpress.org/block-editor/packages/packages-stylelint-config/) is compliant with the [WordPress CSS Coding Standards :arrow_upper_right:](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/).

A [rule :arrow_upper_right:](https://github.com/humanmade/coding-standards/issues/193) has been added to write code with the BEM notation.

If the CSS file of a plugin is not following the coding practice (like Gravity Forms), the linting can be disabled inside `.stylelintignore`.

`npm run lint:css`

The linter is checking also the usage of logical properties with [this stylelint rule :arrow_upper_right:](https://github.com/csstools/stylelint-use-logical).  
With the command `npm run format:css`, the physical properties will be replaced by the logical properties

---

## Structure of the CSS folder

The SASS/SCSS source files are stored in the `css/` folder.

On the other hand, built CSS files are saved in the `build/css` directory.

### style.scss

Import SCSS files for the layout of the site.

#### /tools

Contains tools that **must not** produce any CSS output. This includes things like SCSS mixins, functions, and variables
(but not CSS custom properties aka CSS variables since they are produce code that is outputted on a page).

The reason is that you may need some common tools (e.g. breakpoint mixins) in theme's custom blocks. Since custom blocks
are located in the `blocks` directory, their styles are not part of the main stylesheet, and thus we cannot use the
tools imported in `style.scss`. Any block stylesheet that needs common tools will should import the `tools/index` on its
own but this should not cause any side effects like duplicated CSS variable definitions.

##### /tools/colors

Includes color variables referenced from theme.json.

##### /tools/elevations

Includes variables and mixins for elevation tokens.

##### /elements/reset

Contains bare minimum such as:

- box-sizing
- [scroll padding :arrow_upper_right:](https://developer.mozilla.org/en-US/docs/Web/CSS/scroll-padding-top) in relation to the admin bar, supports extending for sticky headers
- always visible scrollbar to lower CLS
- font smoothing
- [smooth scroll :arrow_upper_right:](https://www.w3schools.com/howto/howto_css_smooth_scroll.asp)
- remove built-in form typography styles
- images max widths
- remove all animations and transitions for people that prefer not to see them
- fix style specificity issue for gallery block caption

#### /elements

Sets default styles of the smallest pieces like links, paragraphs, lists, etc.

##### /elements/reset

It aims at defining a common ground for default styles set by various browsers.

##### /elements/links

Sets default styles for A elements that don't have a class.

##### /elements/lists

Does not reset default styles for lists, which can be styled here.
Resets ul/ol style for the list elements with the role="list" attribute.

### lazy.scss

Import SCSS files for:

- @keyframes / animations & related variables. (`/tools/animations`)
- elements which are always below the fold, like the footer.
- SVGs / tiny assets which could not be inlined in the HTML
- styles related to overlays

## Theme.json

### Colors palette

The current variables are only an indication and must be updated with the design values.

Current color palette slugs: `primary`, `secondary`, `tertiary`, `quarternary`, `quinary`, `senary`, `foreground`, `foreground-alt`, `background` and `background-alt`.

These colors variables will create the `color` and `background` classes.

For `primary` color two new classes will be automatically created:

- `has-primary-color` - when used as element's text color, and
- `has-primary-background-color` - when used as element's background color.

Usage in CSS files is similar as for CSS variables.

`var(--color--{slug})`

```CSS
p {
  color: var(--color--primary);
  background-color: var(--color--background);
}
```

WordPress generates similar variables with the addition of a `wp--preset--` prefix, like so: `--wp--preset--color--primary`. Using them introduces unpleasant loading behavior, therefore use the variables defined in the stylesheets instead, which should follow the same naming pattern for consistency (`--color--primary`).

Not every color needs to be linked to the `theme.json` file. For example, semantic colors are supposed to be used only in the stylesheets to create UI elements and should not be part of the color palette in the editor.

### Typography

The CSS `tools/typography` defines the following custom typography variables:

- _line-height_: `none`, `xs`, `s`, `m`, `l` and `xl` (usage `var(--line-height--s)`),
- _letter-spacing_: `none`, `s`, `m` and `l` (usage `var(--letter-spacing--s)`),
- _font-weight_: `regular`, `medium`, `semibold` and `bold` (usage `var(--font-weight--regular)`),

They are cross-referenced from `theme.json`.
Activating the corresponding editor options requires switching the lineHeight, letterSpacing or fontWeight typography flags to true.

The following typography styles are defined in CSS and are available in the editor options.
The font slugs are for the fluid font sizes:

- _font families_: `primary` and `secondary` (usage `var(--font-family--secondary)`),
- _font sizes_: `xs`, `s`, `m`, `l`, `xl`, `2xl`, `3xl`, `4xl` and `5xl`

**Usage:**

- For fixed size:  `var(--font-size--5xl)`)
- For fluid size:  `var(--f-font-size--5xl)`)

### Spacing

There is a single CSS property that controls the base spacing value: `--base-spacing`. By default, it is set to 1 rem and it is assigned to the `--spacing--m` variable.

All other spacing CSS properties are calculated automatically based on the `--base-spacing` value. Thanks to that, if a different base for spacing has to be used in a theme, only the `--base-spacing` value needs to be adjusted.

Note that there are 2 kinds of spacing variables available: **fixed** and **fluid** (fluid variables are always prefixed with `f-`).  
The [fluid space calculator :arrow_upper_right:](https://utopia.fyi/space/calculator/) has been used here.

The fixed spacing variables consist of:

- `--spacing--2xs`
- `--spacing--xs`
- `--spacing--s`
- `--spacing--m`
- `--spacing--l`
- `--spacing--xl`
- `--spacing--2xl`
- `--spacing--3xl`
- `--spacing--4xl`
- `--spacing--5xl`
- `--spacing--6xl`

The fluid spacing variables are as follows:

- `--f-spacing--s`
- `--f-spacing--m`
- `--f-spacing--l`
- `--f-spacing--xl`
- `--f-spacing--2xl`
- `--f-spacing--3xl`
- `--f-spacing--4xl`
- `--f-spacing--5xl`

The custom spacings are defined in CSS, without being available in the editor options.

So for font-size and spacing, the fluid version of the custom properties have a **f** prefix.  
In this way, only fluid spacing and font-size will be suggested while typing `var(--f)`.

### Layout

Content size and wide size are set for the layout.

### Global styles

#### `styles/color`

Defines `text` and `background` colors of the body tag.

#### `elements`

`h1`, `h2`, `h3`, `h4`, `h5`, `h6`, and `link` have font family, weights and size, line height and color defined, as well as text decoration for the link.

#### `styles/typography`

Defines `font-family`, `line-height` and `font-size` colors of the body tag.  
So the body properties are define with this section of `theme.json` and the section `styles/color`.  
These properties must use the value of the paragraph.

Resources:

- [Global Settings & Styles (theme.json) :arrow_upper_right:](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/),
- [Theme.json :arrow_upper_right:](https://developer.wordpress.org/themes/advanced-topics/theme-json/)

---

## PostCSS

The theme build process uses the `postcss-preset-env` PostCSS plugin.

It converts modern CSS into something the browsers can understand, determining the polyfills you need based on your
targeted browsers or runtime environments.
[Features :arrow_upper_right:](http://preset-env.cssdb.org/features)
[Documentation :arrow_upper_right:](https://github.com/csstools/postcss-preset-env)

---

## Font Subsetting

Subsetting fonts take a large font file as input and create other smaller files with fewer characters or OpenType features. 

There are several reasons you may want to subset a font file, and one of the most important ones is related to performance.

See: [Confluence: Font Subsetting :arrow_upper_right:](#)
