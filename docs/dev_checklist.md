Developer Checklist

:back: [Home](../README.md)

Getting started guide for the Eutopia Theme.

After scaffolding your new theme, go through the following steps.

Update every `[ ]` to `[x]` for the completed steps, such that the progress is clear.

Table of Contents

- [Initial Setup](#initial-setup)
- [Local environment](#local-environment)
- [Style guide](#style-guide)
	- [Color scheme](#color-scheme)
	- [Typography](#typography)
		- [Font styles](#font-styles)
		- [Headings](#headings)
		- [WebFonts](#webfonts)
		- [Common elements](#common-elements)
	- [Shadows](#shadows)
- [Layout](#layout)
	- [Sizes](#sizes)
	- [Spacings](#spacings)
- [Set the template structure](#set-the-template-structure)
	- [Header](#header)
	- [Footer](#footer)
	- [Widget areas](#widget-areas)
- [Block Editor](#block-editor)
	- [Scaffold your first block](#scaffold-your-first-block)
	- [Scaffold a Hero block](#scaffold-a-hero-block)
	- [Configure the icons available in the Icon block](#configure-the-icons-available-in-the-icon-block)
- [Javascript Modules](#javascript-modules)
	- [Infinite scroll (pagination)](#infinite-scroll-pagination)
	- [Infinite scroll (single)](#infinite-scroll-single)
- [Delivery clean-up](#delivery-clean-up)
	- [Remove demo blocks](#remove-demo-blocks)
- [Deployment Automation](#deployment-automation)

## Initial Setup

This applies to projects where the theme is added to an existing project monorepo:

- [ ] Use the scaffolding tool documented in `README.md` to generate a theme under the `wp-content/themes` directory of your project.
- [ ] Define the newly created theme as an NPM workspace to the project's root `package.json` and as a Composer local path dependency to the root `composer.json` by [following this guide :arrow_upper_right:](#).
- [ ] If your project requires phpstan typechecking, add `"@composer lint:phpstan"` to [composer.json](../composer.json) `scripts` \ `lint` commands array.

## Local environment

- [ ] Confirmed with all team members using OSX that they have gone through the [macOS Environment Notes :arrow_upper_right:](#) to ensure they are having a performant development environment.
- [ ] Confirmed with all team members that both the pre-commit (coding style) and the pre-push hooks are configured and running.

## Style guide

This information is sourced from the style guide.
If a style guide is not available, the commonly used colors & typography will be extracted manually by the front-end engineer from the provided designs.

### Color scheme

- [ ] Update all `--color` prefixed variables ([_css-variables.scss](../css/utilities/_css-variables.scss)), as [HSL :arrow_upper_right:](https://www.w3schools.com/colors/colors_hsl.asp). Tip: [Convert colors in VSCode with a click :arrow_upper_right:](https://d33wubrfki0l68.cloudfront.net/373be93c20f8dca701fdb7697fe89e2ecf2080fe/c327c/img/uploads/change-mode.gif).
- [ ] Add any new Main Colors to the `settings` / `color` / `pallette` section of [theme.json](../theme.json), or remove unused ones. Always maintain references to the CSS variables from the JSON file, and therefore maintain a single source of truth in variables.sass.
- [ ] Delete unused `--color` prefixed variables from both files
- [ ] Set the global body background & text color to the corresponding variable ([_body.scss](../body.scss)) (use foreground / background and their respective alternatives)
- [ ] Set the default color of links ([_css-variables.scss](../css/utilities/_css-variables.scss))
- [ ] By default, the [duotone filters :arrow_upper_right:](https://wordpress.org/news/2021/05/coloring-your-images-with-duotone-filters/) are disabled through the [theme.json](../theme.json) configuration. If the design makes use of them, selectively enable the theme.json for the blocks requiring it following the [settings/blocks/block-id/color/duotone :arrow_upper_right:](https://fullsiteediting.com/lessons/theme-json-color-options/#h-which-wordpress-blocks-support-duotone\:~:text=And%20for%20a%20single%20block%3A) options structure.

### Typography

#### Font styles

- [ ] Set the used `--font-family` stacks, remove unused from [_css-variables.scss](../css/utilities/_css-variables.scss) & [theme.json](../theme.json).
- [ ] Set the used `--font-sizes`, remove unused from [_css-variables.scss](../css/utilities/_css-variables.scss) & [theme.json](../theme.json).
- [ ] Set the fluid font-sizes `--f-font-size`, in [_css-variables.scss](../css/utilities/_css-variables.scss) using the [Fluid Typography Calculator :arrow_upper_right:](https://royalfig.github.io/fluid-typography-calculator/).
- [ ] Set the used `--line-heights` ([_css-variables.scss](../css/utilities/_css-variables.scss)), remove unused.
- [ ] Set the `--letter-spacing` variations ([_css-variables.scss](../css/utilities/_css-variables.scss)). Remove completely if unused.
- [ ] Set the used `--font-weight` variations ([_css-variables.scss](../css/utilities/_css-variables.scss)). Remove unused font-weights.
- [ ] Set the font-weight for the b, strong elements in ([_typography.scss](../css/elements/_typography.scss)).
- [ ] Set the default primary font size & spacing via `--p--font-size` and `--p--line-height` ([_css-variables.scss](../css/utilities/_css-variables.scss)).

#### Headings

- [ ] Set the heading base ([mobile-first :arrow_upper_right:](https://css-tricks.com/how-to-develop-and-test-a-mobile-first-design-in-2021/#aa-what-is-a-mobile-first-design)) font-sizes `--hX--font-size` & line heights `--hX--line-height` ([_css-variables.scss](../css/utilities/_css-variables.scss)).
- [ ] Set heading styles, with optional responsive overrides ([_typography.scss](../css/elements/_typography.scss)).

#### WebFonts

- [ ] Replace `$google_fonts` ([class-styles.php](../php/components/class-styles.php)) with the required Google Font Families and specifically used font weights and styles.
- [ ] Identify the critical web fonts, by verifying the font family & style used in the largest texts on mobile's common templates.
- [ ] Define custom webfonts & self-host critical Google webfonts ([_webfonts.scss](../css/elements/_webfonts.scss)).
- [ ] Remove self-hosted Google fonts from `$google_fonts` ([class-styles.php](../php/components/class-styles.php)).
- [ ] Fill in the `$critical_font_paths` ([class-styles.php](../php/components/class-styles.php)) with up to two critical web fonts URLs. If the webfont asset URL is dynamic, no preloading is possible, `preconnect crossorigin` via the `filter_resource_hints` function as a fallback. 
- [ ] Remove `fonts.gstatic.com` from the `filter_resource_hints` in ([class-styles.php](../php/components/class-styles.php)) if you're not using remote loading of Google fonts.
- [ ] Set fallback [font stacks :arrow_upper_right:](https://www.cssfontstack.com/) for the webfonts ([_css-variables.scss](../css/utilities/_css-variables.scss)) using [Font style matcher :arrow_upper_right:](https://meowni.ca/font-style-matcher/) to minimize text shifting when the web font loads.
- [ ] Subset fonts using [subsetter :arrow_upper_right:](https://everythingfonts.com/subsetter) for woff2 font files that exceed 50KB in size, as this could be a sign of including too many potentially unused glyphs, start with the Basic Latin, Latin-1 Supplement and Advanced Punctuation and test the results, expanding as necessary to include more glyphs.

#### Common elements

- [ ] Set the default link styles using the placeholder selector ([_links.scss](../css/elements/_links.scss)).
- [ ] Set the default button styles using the mixins in ([_buttons.scss](../css/tools/_buttons.scss)).
  - [ ] Optionally extend the core button block styling using it.
- [ ] Set the default list styles ([_lists.scss](../css/elements/_lists.scss)), and undo the added styles in `%list-reset`.

### Shadows

- [ ] Set the elevation `$elevation--` variables [_scss-variables.scss](../css/tools/_scss-variables.scss) with the shadow types in the style guide, discard the unused ones.

## Layout

### Sizes

- [ ] If needed, adjust the breakpoint values (starting with `$breakpoint--`) in [_scss-variables.scss](../css/tools/_scss-variables.scss)
- [ ] Set the `$size--content` to the width of the main content area (where the post content coming from Gutenberg lives) from the designs in [_scss-variables.scss](../css/tools/_scss-variables.scss). It is usually ~2/3 of the entire page width.
- [ ] Set the `$size--wide` to the page width from Figma in [_scss-variables.scss](../css/tools/_scss-variables.scss)
- [ ] Update the size constants in [`class-sizes.php`](../php/helpers/class-sizes.php) so that they match the ones defined in SCSS.
- [ ] If needed, update the image aspect ratio constants in [`class-images.php`](../php/components/class-images.php) to match the image sizes defined in the design.
- [ ] For the class `has-sidebar` to be present correctly on the body element for 2 column layouts, update `$is_template_with_sidebar` inside of [class-sidebar.php](../php/components/class-sidebar.php) with the templates that have it. Wide alignment won't be possible on these templates!
- [ ] If wide block alignment should not be supported by the theme, follow these steps:
  - [ ] Remove the variable `--layout--wide-size` from [_css-variables.scss](../css/utilities/_css-variables.scss)
  - [ ] Remove align-wide support from [class-editor.php](../php/components/class-editor.php)
  - [ ] Remove the `settings` / `layout` / `wideSize` from [theme.json](../theme.json)
  - [ ] Stop using the [_block-container.scss](../css/utilities/_block-container.scss) utility, delete the "block-container" classes from the template fils [content.php](../template-parts/content.php), [content-page.php](../template-parts/content-page.php), [content-none.php](../template-parts/content-none.php), [404.php](../404.php), [search.php](../search.php).
  - [ ] Add a new div class="container" wrapper around the content (`div class="site-main"`) & sidebar (for all templates with a sidebar [single.php](../single.php))
  - [ ] After ensuring no images use the `Sizes::CONTENT_WIDE` constant, remove it from [`class-sizes.php`](../php/helpers/class-sizes.php).

### Spacings

- [ ] Set the `$size--base-spacing` value in [_scss-variables.scss](../css/tools/_scss-variables.scss). It will be used to generate the CSS custom properties for spacings.
- [ ] Set the `--layout--block-gap` ([_css-variables.scss](../css/utilities/_css-variables.scss))
- [ ] Set the `--layout--grid--gutter` ([_css-variables.scss](../css/utilities/_css-variables.scss)), used by utility defined in [_grid.scss](../css/utilities/_grid.scss)
- [ ] Set the `--base-spacing` variables according to the grid system used ([_css-variables.scss](../css/utilities/_css-variables.scss)). Discard unused (too big or too small) unused variables.
- [ ] Set the fluid spacing `--f-spacing`-prefixed variables, in [_css-variables.scss](../css/utilities/_css-variables.scss) using the [Fluid Space Calculator :arrow_upper_right:](https://utopia.fyi/space/calculator/), discard unused fluid spacing variables.

## Set the template structure

- [ ] Add wrappers with a layout purpose to [header.php](../header.php), [footer.php](../footer.php) and individual template files:
  - [ ] Front page - [index.php](../index.php)
  - [ ] Single [single.php](../single.php)
  - [ ] Page [page.php](../page.php)
  - [ ] Archive [archive.php](../archive.php)
  - [ ] Search results [search.php](../search.php)
  - [ ] Configure the 404 Page from Settings > Reading > Page for 404 option to allow easy overrides. ([404.php](../404.php) should be considered as a basic fallback).

### Header

- [ ] Update [header.php](../header.php) with needed wrappers.
- [ ] Update [navigation.php](../template-parts/navigation.php) with removal / addition of menus.
- [ ] Sticky header used: Set `--header-sticky--height` and related variables in [_site-header.scss](../css/components/_site-header.scss).
- [ ] Sticky header not used: remove the stickiness style rules and `--header-sticky--height` variable from [_site-header.scss](../css/components/_site-header.scss); remove the `StickySiteHeader` import & initialization from [main.js](../js/main.js).
- [ ] Top Bar: If elements like advertisements above the header are not needed, remove [_top-bar.scss](../css/components/_top-bar.scss) and [template-parts/top-bar.php](../template-parts/top-bar.php).
- [ ] Update the width & height of the supported logo dimensions in the `action_add_custom_logo_support` from [class-site-logo.php](../php/components/class-site-logo.php)
- [ ] If the SVG logo source is available, optimize it with [SVGOMG :arrow_upper_right:](https://jakearchibald.github.io/svgomg/), and add it to the [`assets` directory](../assets/svg/), and it's dimensions in [branding.php](../template-parts/branding.php).
- [ ] Search: Update the styling or discard the magnifying glass toggle button and its search form overlay. The removal consists of:
  - [ ] remove the `template-parts/search-box` template import and deleting the `.site-header__search-toggle` element from [header.php](../header.php)
  - [ ] removal of the modules/search import and it's initialization from [main.js](../js/main.js).
  - [ ] the [_site-header.scss](../css/components/_site-header.scss) file needs to be updated to remove the `--header-search-toggle--width` variable and the `site-header__search-toggle` element styles. Update the grid-template for the `.site-header__container`.

### Footer

- [ ] Update [footer.php](../footer.php) with needed wrappers.

### Widget areas

- [ ] Define or remove widget areas under `register_sidebar` in [class-sidebar.php](../php/components/class-sidebar.php)
- [ ] Add or remove the templates with a 2 column layout the `filter_body_classes` in [class-sidebar.php](../php/components/class-sidebar.php), as that adds the `has-sidebar` class to the body according to it.

## Block Editor

Prefer dynamic blocks over static blocks.

### Scaffold your first block

- [ ] Select a design element that’s often re-used by the editors inside the content.
- [ ] Pick an icon from the [dashicons library :arrow_upper_right:](https://developer.wordpress.org/resource/dashicons/) for it, note its slug.
- [ ] Scaffold a new dynamic block with the `npm run scaffold` command, and choosing `create-wp-block` option.
- [ ] Style the block using the `style.scss` in the newly scaffolded block placed from the `blocks` directory.
- [ ] Build the assets (`npm run dev` or `npm run build`).

### Scaffold a Hero block

If your project requires a fully editor-controlled hero block, take the Hero Block template as a starting point, as it includes several commonly needed features like: a layout chooser, focal point selection and several text & background color customization options.

- [ ] Scaffold a new Hero Block with the `npm run scaffold` command, and choosing `create-wp-block` option, then select the `_template-hero-section`.
- [ ] Customize the block's functionality and styles according to the project needs.

### Configure the icons available in the Icon block

- [ ] Configure SVGs according to the documentation in the Icon block's [README.md](../blocks/icon/README.md) by registering new icons, or unregistering dashicons as necessary.

## Javascript Modules

### Infinite scroll (pagination)

- [ ] If applicable, update the pagination's infinite scroll `SELECTORS` in [infinite-scroll-pagination.js](../js/modules/infinite-scroll-pagination.js).
- [ ] Include the `template-parts/pagination-infinite.php` file to all applicable archives.
  - [ ] Include the `template-parts/pagination` file to all *not* infinite scrolling archives.

### Infinite scroll (single)

- [ ] If applicable, update the infinite scroll `SELECTORS` in [infinite-scroll-single.js](../js/modules/infinite-scroll-single.js).
- [ ] Include the `template-parts/entry-infinite-scroll.php` file to all applicable templates.

## Delivery clean-up

### Remove demo blocks

- [ ] Remove blocks/demo-* folders.
- [ ] Remove unused custom blocks.

## Deployment Automation

- [ ] Setup hosting-specific GitHub Actions deployment workflows.
- [ ] Update the [README.md](../README.md#environment-and-branch-mapping) with the environment and branch mapping.
- [ ] Update the [deployments.md]](docs/deployments.md) documentation with the host-specific details.
