# Working with Block Patterns

:back: [Home](../README.md)

Table of Contents

- [Working with Block Patterns](#working-with-block-patterns)
	- [How to Add New Patterns](#how-to-add-new-patterns)
	- [Allowed Core Blocks Pattern](#allowed-core-blocks-pattern)
	- [Styleguide Pattern](#styleguide-pattern)

> Block Patterns are predefined block layouts, available from the patterns tab of the block inserter. Once inserted into content, the blocks are ready for additional or modified content and configuration.

Source: <https://developer.wordpress.org/block-editor/reference-guides/block-api/block-patterns/>

Block patterns should be used as starter templates for content creation. Once inserted in post content they can be changed so anything that requires consistent markup or future updates must use dynamic blocks instead.

## How to Add New Patterns

Create a new PHP file in this `patterns` directory and use the comment header to specify the pattern meta data. All patterns use the `eutopia-theme` category by default and can register custom categories in `php/components/class-block-patterns.php`.

## Allowed Core Blocks Pattern

This pattern display all the core blocks and their variations.  
By default this pattern is not available, `WP_DEBUG` must be set to true.  
When this is done, the pattern will be available under the category `Testing`

## Styleguide Pattern

Like the previous pattern, `WP_DEBUG` must be enabled.  
This pattern display all the typographic elements only (like the different headings).
