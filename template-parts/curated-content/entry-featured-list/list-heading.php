<?php
/**
 * Template part for displaying heading above the list items.
 *
 * @package Eutopia_Theme
 *
 * @var array $args The template part args.
 */

$list_heading = $args['list_heading'] ?? __( 'Latest News', 'eutopia-theme' );

?>
<h3 class="l-entry-featured-list__item-heading"><?php echo esc_html( $list_heading ); ?></h3>
