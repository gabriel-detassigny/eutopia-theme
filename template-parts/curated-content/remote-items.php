<?php
// phpcs:ignoreFile
/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eutopia_Theme
 *
 * @var array $args The template part args.
 */

$item = $args['item'];
$atts = $args['atts'];
?>
<h3><?php echo esc_html( $item['title']['rendered'] ); ?></h3>
<?php if ( $atts['thumbnail_on'] ): ?>
	<img src="<?php echo esc_attr( $item['thumbnails']['high']['url'] ); ?>">
<?php endif; ?>
<div><?php echo esc_html( $item['description'] ); ?></div>
<hr>
