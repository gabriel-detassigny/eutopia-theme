<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Eutopia_Theme
 */

get_header();
$page_for_404_id = get_option( 'page_for_404' );
?>

	<main id="primary" class="site-main">
		<?php if ( $page_for_404_id ) : ?>
			<article id="post-<?php echo esc_attr( $page_for_404_id ); ?>" <?php post_class( '', $page_for_404_id ); ?>>
				<div class="entry-content">
					<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo apply_filters( 'the_content', get_post_field( 'post_content', $page_for_404_id ) );
					?>
				</div><!-- .entry-content -->
			</article><!-- #post-<?php echo esc_attr( $page_for_404_id ); ?> -->
		<?php else : ?>
			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'eutopia-theme' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content block-container">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'eutopia-theme' ); ?></p>
						<?php
						get_search_form();
						the_widget( 'WP_Widget_Recent_Posts' );
						?>

						<div class="widget widget_categories">
							<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'eutopia-theme' ); ?></h2>
							<ul>
								<?php
								wp_list_categories(
									[
										'orderby'    => 'count',
										'order'      => 'DESC',
										'show_count' => 1,
										'title_li'   => '',
										'number'     => 10,
									]
								);
								?>
							</ul>
						</div><!-- .widget -->

						<?php
						/* translators: %1$s: smiley */
						$eutopia_theme_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'eutopia-theme' ), convert_smilies( ':)' ) ) . '</p>';
						the_widget( 'WP_Widget_Archives', [ 'dropdown=1' ], [ 'after_title' => "</h2>$eutopia_theme_archive_content" ] );
						the_widget( 'WP_Widget_Tag_Cloud' );
						?>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->
		<?php endif; ?>
	</main><!-- #main -->

<?php
get_footer();
