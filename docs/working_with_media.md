Working with Media

:back: [Home](../README.md)

Table of Contents

- [SVGs](#svgs)
- [Images](#images)
	- [Don't Worry About The Pixels, Aspect Ratio Is What Matters](#dont-worry-about-the-pixels-aspect-ratio-is-what-matters)
	- [One Size *Doesn't* Fit All](#one-size-doesnt-fit-all)

## SVGs

The [SVG Component](../php/components/class-svg.php) introduces 2 template methods, `get_svg()` and `render_svg()`, following the WordPress conventions. To add a new SVG to the theme:

1. Add the SVG file to the [`assets/svg` directory](assets/svg). Make sure to pass the markup through [SVGOMG optimizer](https://jakearchibald.github.io/svgomg/) and to remove any hardcoded values for the `fill` attribute.

2. Use the helper methods to display them on the site (complete list of arguments found in the [component](../php/components/class-svg.php)). These methods can be used wherever PHP is allowed, making them suitable for adding icons to dynamic blocks.

Note that any subsequent calls to `get_svg()` or `render_svg()` for the same SVG file will return an SVG link to the previously rendered SVG DOM element.

```php
// As a variable:
$svg = \Eutopia_Theme\Components\SVG::get_instance()->get_svg( 'relative/path/image.svg', [ 'class'  => 'c-icon' ] );
echo $svg; // phpcs:ignore This is safe.

// Directly output the svg.
\Eutopia_Theme\Components\SVG::get_instance()->render_svg( 'relative/path/image.svg', [ 'class'  => 'c-icon' ] );
```

## Images

Handling images in WordPress can be hard. Defining a reasonable set of image sizes that would be used to populate the `<img>` tag's `srcset` attribute is one thing. Another one is to provide a `sizes` attribute that would be tailored to the specific location of an image in a page layout.

**Helpful resources:**
- [Responsive Image Linter :arrow_upper_right:](https://chrome.google.com/webstore/detail/responsive-image-linter/mnddginionlghpblkimpdalcecpnbjln)
- [The Humble `<img>` Element And Core Web Vitals :arrow_upper_right:](https://www.smashingmagazine.com/2021/04/humble-img-element-core-web-vitals/)
- [The Guide To Responsive Design In 2023 and Beyond :arrow_upper_right:](https://ishadeed.com/article/responsive-design/)

### Don't Worry About The Pixels, Aspect Ratio Is What Matters

Consider a rather typical page layout: 

<div style="max-width:600px;margin:auto;">

![Diagram showing page layout with several images using 3 different pixel sizes](../image/Various Image Sizes.png)

</div>

What you would normally do is to register custom image sizes in WordPress based on an image location, e.g.:

```php
add_image_size( 'featured', 640 );
add_image_size( 'default', 300 );
add_image_size( 'small', 150 );
```

With that, whenever you render an image like

```php
// entry-card-default.php
the_post_thumbnail( 'default' );

// entry-card-featured.php
the_post_thumbnail( 'featured' );
```

WordPress will print an `<img>` tag with an auto-generated `srcset` attribute. It will contain the custom and core source image URLs (custom `default` or `featured`, and core `thumbnail`, `medium`, `medium_large`, `large`, `full`, `1536x1536`, `2048x2048`).

Everything works fine, we can move on to the next task, right? Not really. Think of what happens when an image uploaded by a user doesn't match the size of an image container in the page layout? The worst case scenario is that the rendered image will not fit into that container which will result in an element shrinking or expanding unexpectedly. This can be fixed with some CSS by soft-cropping an image using the `aspect-ratio` and `object-fit` tandem.

Okay, now we are done and can move on, correct? No, we can do better. Soft-cropping with CSS means that the images served to the user are either too large or too small and in both cases it will negatively impact the Web Core Vitals score. We should seek a better solution.

How about looking at the same page layout but from a different perspective:

<div style="max-width:600px;margin:auto;">

![Diagram showing the same page layout as above with an emphasis put on the fact that every image shares the same aspect ratio](../image/Various Image Sizes Same Aspect Ratio.png)

</div>

While all of those images differ in size, they share the same aspect ratio. It means that their `srcset` could generally be the same and the decision about the best image size choice could be left to the browser (after all, it's the browser who knows the best what is the device's screen size, pixel ratio, etc.). The only thing we would care about is to pick the correct aspect ratio.

With this assumption, we can define image sizes based on aspect ratios used in the designs making sure that for every ratio there are a few resized images created (so that the browser has some options in the `srcset` attribute), e.g.:

```php
add_image_size( '3:2--384', 384, 256, true );
add_image_size( '3:2--768', 768, 512, true );
add_image_size( '3:2--1024', 1024, 683, true );
add_image_size( '3:2--1536', 1536, 1024, true );
add_image_size( '3:2--2048', 2048, 1365, true );
```

With the magic sauce located in the [`Images`](../php/components/class-images.php) class, you don't even have to manually add image sizes. You're only responsible for defining the aspect ratio constants like `RATIO_3X2` and making sure the list of `IMAGE_WIDTHS` makes sense.

Now you can render an image using that aspect ratio constant like:

```php
// entry-card-default.php
the_post_thumbnail( Images::RATIO_3X2 );

// entry-card-featured.php
the_post_thumbnail( Images::RATIO_3X2 );
```

[`Images`](../php/components/class-images.php) will take care of picking the right image sources for a given aspect ratio, adding them to the `<img>` tag and setting the `width` and `height` attributes accordingly. Moreover, an inline CSS will be added to the page which will ensure the `<img>` tag expands horizontally to fill the available space while keeping the aspect ratio intact.

### One Size *Doesn't* Fit All

Unfortunately, web development is not as easy as:

```php
the_post_thumbnail( Images::RATIO_3X2 );
```

So far, I only talked about `srcset` but I didn't mention `sizes`.

Let's have another look at the sample page layout:

<div style="max-width:600px;margin:auto;">

![Diagram showing page layout with several images using 3 different pixel sizes](../assets/image/Various Image Sizes.png)

</div>

For every image we can now use our elegant aspect-ratio-based size, `Images::RATIO_3X2`, and be sure WordPress will populate the `srcset` attribute with the correct image sources. Remember when I said that the browser knows best which image to choose? I lied (a little bit).

In order for the browser to know which image to select from the source set, it needs one more piece of information: the size of an image in the page layout which in the majority of case is variable and depends on the screen size (i.e. it's responsive). The browser can determine that value but only after the entire page has been loaded and rendered (so that the actual dimensions can be measured). However, fetching images can - and usually is - done earlier, before the CSS has been parsed and applied.

This is when the `sizes` attribute comes in. Thanks to that, we – as web developers - can tell the browser what to expect from that particular image instance based on the layout we're implementing.

Let's assume the image in the featured entry card (the top one) in the sample layout above is full width on mobile and tablet while on desktops it occupies 2 out of 3 columns. Our `sizes` attribute would be like this (`vw` is a unit that represents 1% of the viewport width):

```html
<img ... sizes="(min-width: 960px) 66vw, 100vw" />
```

Usually, the page container has some max width set, e.g. `1200px`. We need to consider this in the `sizes` definition:

```html
<img ... sizes="(min-width: 1200px) 800px, (min-width: 960px) 66vw, 100vw" />
```

Here are a few explanatory notes:

- According to the HTML specs, the browser will parse the `sizes` attribute from left to right up to the point when it encounters a matching condition; then it stops. Because of this, we should start with the largest breakpoint first.
- If the screen width is more than our container size, the image width will reach its maximum value, i.e. 2/3 of 1200 px which is 800 px.
- In real-life scenarios, you should also consider spacing in the calculations, e.g. `(min-width: 1200px) calc(800px - 1rem), ...`.

The hard part about `sizes` is that it is tightly coupled to the breakpoint values which are traditionally a CSS territory. Also, they are dependent on the context. For instance, if an image is used inside a custom Gutenberg block, the `sizes` attribute will be different if the block is wide-aligned, full-width or if it's inside another block. 

The bad news is that currently there is no way to automate the `sizes` attribute definition. However, the [`Images`](../php/components/class-images.php) component and the new  class provide some tools that make it at least a little bit easier.

The [`Sizes`](../php/helpers/class-sizes.php) helper class defines a set of constants for various sizes used in the theme like breakpoints or content widths. **Note that those constants must be kept in sync with the values defined in the stylesheets manually.**

On the other hand, the [`Images`](../php/components/class-images.php) class exposes a static method, `Images::get_sizes_attr()`, which generates a `sizes` attribute value based on the provided array. The method takes care of the attribute formatting. It will also order the breakpoints from largest to smallest so that you can use the more natural, mobile-first order when defining the array.

Using those tools, here's how we can now render the featured image:

```php
the_post_thumbnail(
	Images::RATIO_3X2,
	[
		'sizes' => Images::get_sizes_attr(
			[
				Sizes::BREAK_MOBILE  => '100vw',
				Sizes::BREAK_DESKTOP => '66vw',
				Sizes::CONTENT_WIDTH => sprintf( '%dpx', absint( Sizes::CONTENT_WIDTH * 0.66 ) ),
			],
		),
	],
);
```
