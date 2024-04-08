<?php
/**
 * Template part for displaying a post's comment and edit links
 *
 * @package Eutopia_Theme
 *
 * @var \WP_Post $post
 */

$current_post_type = (string) get_post_type( $post );
$taxonomies        = wp_list_filter(
	get_object_taxonomies( $post, 'objects' ),
	[
		'public' => true,
	]
);
?>
<div class="entry-taxonomies">
	<?php
	// Show terms for all taxonomies associated with the post.
	foreach ( $taxonomies as $taxonomy ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		/* translators: separator between taxonomy terms */
		$separator = _x( ', ', 'list item separator', 'eutopia-theme' );

		switch ( $taxonomy->name ) {
			case 'category':
				$class = 'category-links term-links';
				$list  = get_the_category_list( esc_html( $separator ), '', $post->ID );
				/* translators: %s: list of taxonomy terms */
				$placeholder_text = __( 'Posted in %s', 'eutopia-theme' );
				break;
			case 'post_tag':
				$class = 'tag-links term-links';
				$list  = get_the_tag_list( '', esc_html( $separator ), '', $post->ID );
				/* translators: %s: list of taxonomy terms */
				$placeholder_text = __( 'Tagged %s', 'eutopia-theme' );
				break;
			default:
				$class            = str_replace( '_', '-', $taxonomy->name ) . '-links term-links';
				$list             = get_the_term_list( $post->ID, $taxonomy->name, '', esc_html( $separator ), '' );
				$placeholder_text = sprintf(
				/* translators: %s: taxonomy name */
					__( '%s:', 'eutopia-theme' ),
					$taxonomy->labels->name // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
		}

		if ( empty( $list ) || $list instanceof WP_Error ) {
			continue;
		}
		?>
		<span class="<?php echo esc_attr( $class ); ?>">
			<?php
			printf(
				esc_html( $placeholder_text ),
				$list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</span>
		<?php
	}
	?>
</div><!-- .entry-taxonomies -->
<div class="entry-actions">
	<?php
	if ( ! post_password_required() && comments_open() && ! is_singular( $current_post_type ) && post_type_supports( $current_post_type, 'comments' ) ) {
		?>
		<span class="comments-link">
			<?php
			comments_popup_link(
				sprintf(
					wp_kses(
					/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'eutopia-theme' ),
						[
							'span' => [
								'class' => [],
							],
						]
					),
					get_the_title()
				)
			);
			?>
		</span>
		<?php
	}

	edit_post_link(
		sprintf(
			wp_kses(
			/* translators: %s: Name of current post; only visible to screen readers. */
				__( 'Edit <span class="screen-reader-text">%s</span>', 'eutopia-theme' ),
				[
					'span' => [
						'class' => [],
					],
				]
			),
			get_the_title()
		),
		'<span class="edit-link">',
		' </span>'
	);
	?>
</div><!-- .entry-actions -->
