<?php
/**
 * Template part for displaying content before CCB entry items.
 *
 * @package Eutopia_Theme
 */

$heading = ! empty( $args['heading'] ) ? $args['heading'] : __( 'Title', 'eutopia-theme' );
?>
<div class="l-entry-featured-grid">
	<h3 class="l-entry-featured-grid__heading"><?php echo esc_html( $heading ); ?></h3>
