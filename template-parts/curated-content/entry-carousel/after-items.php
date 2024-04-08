<?php
/**
 * Template part for displaying content after CCB entry items.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eutopia_Theme
 */

$show_pagination = ! empty( $args['show_pagination'] ) ? $args['show_pagination'] : 0;
?>
</div><!-- .l-entry-carousel__wrapper -->
<?php if ( $show_pagination ) { ?>
<div class="l-entry-carousel__pagination"></div><!-- .l-entry-carousel__pagination -->
<?php } ?>
</div><!-- .l-entry-carousel -->
