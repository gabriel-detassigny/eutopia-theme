<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eutopia_Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( (string) get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			get_template_part( 'template-parts/entry-meta' );
		endif;
		?>
	</header><!-- .entry-header -->

	<?php get_template_part( 'template-parts/entry-thumbnail' ); ?>

	<div class="entry-content block-container">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'eutopia-theme' ),
					[
						'span' => [
							'class' => [],
						],
					]
				),
				wp_kses_post( get_the_title() )
			)
		);

		wp_link_pages(
			[
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'eutopia-theme' ),
				'after'  => '</div>',
			]
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php get_template_part( 'template-parts/entry-footer' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
