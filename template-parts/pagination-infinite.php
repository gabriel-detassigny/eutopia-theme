<?php
/**
 * Template part for displaying the infinite pagination button.
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Infinite_Scroll;

?>

<div class="pagination pagination--infinite post-navigation<?php echo Infinite_Scroll::get_instance()?->supports_seamless_archive_pagination() ? ' pagination--automatic' : ''; ?>">
	<?php
	the_posts_navigation(
		[
			'class'     => 'infinite-scroll-pagination',
			'prev_text' => __( 'Load more…', 'eutopia-theme' ),
		]
	);
	?>
</div>
