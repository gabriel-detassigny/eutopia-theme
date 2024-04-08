<?php
/**
 * Template part for displaying an entry meta list.
 *
 * @package Eutopia_Theme
 *
 * @var array $args The template part args.
 */

use Eutopia_Theme\Components\Entry_Meta_Labels;

if ( empty( $args['meta_items'] ) ) {
	return;
}

$entry_id = $args['post_id'] ?? get_the_ID();

$supported_meta = [ 'author', 'published_date' ];
$meta_items     = array_intersect( $args['meta_items'], $supported_meta );
if ( empty( $meta_items ) ) {
	return;
}

// Get and sanitize the size.
$size = $args['size'] ?? 'm';
if ( ! in_array( $size, [ 'l', 'm' ], true ) ) {
	$size = 'm';
}
?>
<ul class="<?php echo esc_attr( "c-entry-meta-list c-entry-meta-list--size-$size" ); ?>">
	<?php
	$index = 0;

	foreach ( $meta_items as $meta_item ) {
		$classname = 'c-entry-meta-list__item';
		if ( 0 === $index ) {
			$classname .= ' c-entry-meta-list__item--primary';
		}

		++$index;
		?>
		<li class="<?php echo esc_attr( $classname ); ?>">
			<?php
			if ( 'author' === $meta_item ) {
				Entry_Meta_Labels::get_instance()?->the_entry_author_label_link( '', $entry_id );
			} elseif ( 'published_date' === $meta_item ) {
				Entry_Meta_Labels::get_instance()?->the_entry_date_label_link( '', $args['date_format'] ?? 'l, dS F, Y', $entry_id );
			}
			?>
		</li>
		<?php
	}
	?>
</ul><!-- .c-entry-meta-list -->
