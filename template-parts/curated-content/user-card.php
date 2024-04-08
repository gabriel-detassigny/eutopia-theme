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

<article id="user-<?php echo esc_attr( $item->ID ); ?>">
	<div class="author vcard">
		<?php echo get_avatar( $item->ID ); ?>
		<?php
		echo $item->data->display_name;
		?>
	</div><!-- .entry-content -->
</article>
