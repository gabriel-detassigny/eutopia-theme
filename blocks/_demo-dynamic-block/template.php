<?php
/**
 * Block template.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 *
 * @package Eutopia_Theme
 */

/* Block level classes and styles */
$wrapper_attributes = get_block_wrapper_attributes();

/* Get Allowed Tags */
$allowed_tags = allowed_tags();
?>

<div <?php echo wp_kses( $wrapper_attributes, [] ); ?>>
	<h2><?php echo wp_kses( $attributes['title'], $allowed_tags ); ?></h2>
</div>
