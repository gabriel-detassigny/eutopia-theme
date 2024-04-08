<?php
// phpcs:ignoreFile
/**
 * Template part for displaying posts
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eutopia_Theme
 *
 * @var array $args {
 *     The template part args.
 *
 *     @type \WP_Term $item The term item.
 *     @type array $atts The block attributes.
 * }
 */

// If the passed args contain an item attribute, it is a single item.
if ( isset( $args['item'] ) ) {
	$all_terms = [ $args ];
} else {
	$all_terms = $args;
}

// Loop through the array and display.
foreach ( $all_terms as $one_term ) {
	$item      = $one_term['item'];
	$permalink = get_term_link( $item );

	if ( is_wp_error( $permalink ) ) {
		continue;
	}
	?>

	<li class="c-entry-list-term__item" id="term-<?php echo esc_attr( $item->term_id ); ?>">
		<a class="c-entry-list-term__link" href="<?php echo esc_url( $permalink ); ?>">
			<h4 class="c-entry__title">
				<?php echo esc_html( $item->name ); ?>
			</h4>
		</a>
	</li>
	<?
}
