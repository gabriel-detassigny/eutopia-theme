<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Infinite_Scroll;

get_header();
?>

	<main id="primary" class="site-main">
		<?php
		if ( have_posts() ) {
			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;
			?>

			<div class="posts">
				<?php
				/* Start the Loop */
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', (string) get_post_type() );
				}
				?>
			</div><!-- .posts -->

			<?php
			$variation = '';

			if ( Infinite_Scroll::get_instance()?->supports_archives() ) {
				$variation = 'infinite';
			}

			get_template_part( 'template-parts/pagination', $variation );
		} else {
			get_template_part( 'template-parts/content', 'none' );
		}
		?>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
