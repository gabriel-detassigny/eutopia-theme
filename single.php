<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Infinite_Scroll;

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', (string) get_post_type() );

			// Display post navigation only if infinite scroll is disabled.
			if ( ! Infinite_Scroll::get_instance()?->supports_single() ) {
				the_post_navigation(
					[
						'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'eutopia-theme' ) . '</span> <span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'eutopia-theme' ) . '</span> <span class="nav-title">%title</span>',
					]
				);
			}

			// If comments are open, or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
get_template_part( 'template-parts/entry-infinite-scroll' );
get_sidebar();
get_footer();
