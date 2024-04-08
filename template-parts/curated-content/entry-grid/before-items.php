<?php
/**
 * Template part for displaying content before CCB entry items.
 *
 * @package Eutopia_Theme
 *
 * @var array $args The template part args.
 */

$column_count = absint( $args['grid_column_count'] ?? 0 );

if ( ! in_array( $column_count, [ 2, 3, 4, 6 ], true ) ) {
	$column_count = 3;
}

?>
<div class="<?php echo esc_attr( "l-entry-grid l-entry-grid--{$column_count}-cols" ); ?>">
