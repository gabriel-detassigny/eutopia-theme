<?php
/**
 * Title: Allowed Core Blocks
 * Description: Display all allowed core blocks.
 * Slug: gabriel-detassigny/allowed-core-blocks
 * Categories: testing
 * Keywords: core
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/#register_block_pattern
 *
 * @package eutopia_theme
 */

if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG || ! function_exists( 'gdt_theme_meta' ) ) {
	return;
}
?>

<!-- wp:heading {"level":1} -->
<h1>Heading level 1</h1>
<!-- /wp:heading -->

<!-- wp:heading -->
<h2>Paragraph</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Default</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>This is a paragraph.</strong> <a href="https://github.com/gabriel-detassigny">It should not have any alignment of any kind.</a> It should just flow like you would normally expect. Nothing fancy. Just straight up text, free flowing, with love. Completely neutral and not picking a side or sitting on the fence. It just is. It just freaking is. It likes where it is. It does not feel compelled to pick a side. Leave him be. It will just be better that way. Trust me.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Left Align</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"left"} -->
<p class="has-text-align-left"><em>This is a paragraph.</em> <kbd>It is left aligned. </kbd>Because of this, it is a bit more liberal in it’s views. It’s favorite color is green. Left align tends to be more eco-friendly, but it provides no concrete evidence that it really is. Even though it likes share the wealth evenly, it leaves the equal distribution up to justified alignment.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Center Align</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><s>This is a paragraph.</s> It is center aligned. Center is, but nature, a fence sitter. A flip flopper. It has a difficult time making up its mind. It wants to pick a side. Really, it does. It has the best intentions, but it tends to complicate matters more than help. The best you can do is try to win it over and hope for the best. I hear center align does take bribes.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>Right Align</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"right"} -->
<p class="has-text-align-right">This is a paragraph. <sub>It is right aligned.</sub> It is a bit more conservative in its views. It’s prefers to not be told what to do or how to do it. Right align totally owns a slew of guns and loves to head to the range for some practice. Which is cool and all. I mean, it’s a pretty good shot from at least four or five football fields away. Dead on. So boss.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>List</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Ordered – Unordered – Ordered</h3>
<!-- /wp:heading -->

<!-- wp:list {"ordered":true} -->
<ol><!-- wp:list-item -->
<li>ordered <sup>item</sup></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>ordered item<!-- wp:list -->
<ul><!-- wp:list-item -->
<li><strong>unordered</strong></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>unordered</strong><!-- wp:list {"ordered":true} -->
<ol><!-- wp:list-item -->
<li>ordered item</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>ordered item</li>
<!-- /wp:list-item --></ol>
<!-- /wp:list --></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>ordered item</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>ordered item</li>
<!-- /wp:list-item --></ol>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Ordered – Unordered – Unordered</h3>
<!-- /wp:heading -->

<!-- wp:list {"ordered":true} -->
<ol><!-- wp:list-item -->
<li>ordered item</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>ordered item<!-- wp:list -->
<ul><!-- wp:list-item -->
<li><strong>unordered</strong></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>unordered</strong><!-- wp:list -->
<ul><!-- wp:list-item -->
<li>unordered item</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>unordered item</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>ordered item</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>ordered item</li>
<!-- /wp:list-item --></ol>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Unordered – Ordered – Unordered</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><!-- wp:list-item -->
<li>unordered item</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>unordered item<!-- wp:list {"ordered":true} -->
<ol><!-- wp:list-item -->
<li>ordered</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>ordered<!-- wp:list -->
<ul><!-- wp:list-item -->
<li>unordered item</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>unordered item</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></li>
<!-- /wp:list-item --></ol>
<!-- /wp:list --></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>unordered item</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>unordered item</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:heading {"level":3} -->
<h3>Unordered – Unordered – Ordered</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><!-- wp:list-item -->
<li>unordered item</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>unordered item<!-- wp:list -->
<ul><!-- wp:list-item -->
<li>unordered</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>unordered<!-- wp:list {"ordered":true} -->
<ol><!-- wp:list-item -->
<li><strong>ordered item</strong></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><strong>ordered item</strong></li>
<!-- /wp:list-item --></ol>
<!-- /wp:list --></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>unordered item</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>unordered item</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>Quote</h2>
<!-- /wp:heading -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>Laughter is the sensation of feeling good all over and showing it principally in one place.</p>
<!-- /wp:paragraph --><cite>Josh Billings</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:heading -->
<h2>Audio</h2>
<!-- /wp:heading -->

<!-- wp:audio {"id":10} -->
<figure class="wp-block-audio"><audio controls src="http://codeskulptor-demos.commondatastorage.googleapis.com/descent/background%20music.mp3"></audio></figure>
<!-- /wp:audio -->

<!-- wp:heading -->
<h2>Button Group &amp; Button</h2>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Press me!</a></div>
<!-- /wp:button -->

<!-- wp:button {"backgroundColor":"tertiary"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-tertiary-background-color has-background wp-element-button">Press me too!</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:heading -->
<h2>Cover</h2>
<!-- /wp:heading -->

<!-- wp:cover {"url":"https://spaceholder.cc/i/800/600","id":15,"dimRatio":50,"isDark":false} -->
<div class="wp-block-cover is-light"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><img class="wp-block-cover__image-background wp-image-15" alt="" src="https://spaceholder.cc/i/800/600" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","placeholder":"Write title…","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">Hello World!</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->

<!-- wp:heading -->
<h2>Embed</h2>
<!-- /wp:heading -->

<!-- wp:embed {"url":"https://www.youtube.com/watch?v=CY7t8ow2gOM","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
https://www.youtube.com/watch?v=CY7t8ow2gOM
</div></figure>
<!-- /wp:embed -->

<!-- wp:heading -->
<h2>Gallery</h2>
<!-- /wp:heading -->

<!-- wp:gallery {"linkTo":"none"} -->
<figure class="wp-block-gallery has-nested-images columns-default is-cropped"><!-- wp:image {"id":15,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://spaceholder.cc/i/800/600" alt="" class="wp-image-15"/></figure>
<!-- /wp:image -->

<!-- wp:image {"id":16,"sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="https://spaceholder.cc/i/800/600" alt="" class="wp-image-16"/></figure>
<!-- /wp:image --></figure>
<!-- /wp:gallery -->

<!-- wp:heading -->
<h2>Group</h2>
<!-- /wp:heading -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p>Some text inside a group</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:heading -->
<h2>Image</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>No Alignment</h3>
<!-- /wp:heading -->

<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://spaceholder.cc/i/800/600" alt=""/><figcaption class="wp-element-caption">I have a caption.</figcaption></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3>Wide Width Alignment</h3>
<!-- /wp:heading -->

<!-- wp:image {"align":"wide","sizeSlug":"large"} -->
<figure class="wp-block-image alignwide size-large"><img src="https://spaceholder.cc/i/800/600" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3>Full Width Alignment</h3>
<!-- /wp:heading -->

<!-- wp:image {"align":"full","sizeSlug":"large"} -->
<figure class="wp-block-image alignfull size-large"><img src="https://spaceholder.cc/i/800/600" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3>Left/Right/Center Alignment</h3>
<!-- /wp:heading -->

<!-- wp:image {"align":"left","sizeSlug":"large"} -->
<figure class="wp-block-image alignleft size-large"><img src="https://spaceholder.cc/i/800/600" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:image {"align":"right","sizeSlug":"large"} -->
<figure class="wp-block-image alignright size-large"><img src="https://spaceholder.cc/i/800/600" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:image {"align":"center","sizeSlug":"large"} -->
<figure class="wp-block-image aligncenter size-large"><img src="https://spaceholder.cc/i/800/600" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading -->
<h2>Pull Quote</h2>
<!-- /wp:heading -->

<!-- wp:pullquote -->
<figure class="wp-block-pullquote"><blockquote><p>This is a pullquote</p><cite>By Cat</cite></blockquote></figure>
<!-- /wp:pullquote -->

<!-- wp:heading -->
<h2>Separator</h2>
<!-- /wp:heading -->

<!-- wp:separator -->
<hr class="wp-block-separator has-alpha-channel-opacity"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2>Video</h2>
<!-- /wp:heading -->

<!-- wp:video {"id":17} -->
<figure class="wp-block-video"><video controls src="https://files.urldocs.com/share/demo-video/demo-video.mp4"></video></figure>
<!-- /wp:video -->
