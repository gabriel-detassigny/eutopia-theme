<?php
/**
 * Template part for displaying the infinite scroll
 *
 * @package Eutopia_Theme
 */

use Eutopia_Theme\Components\Infinite_Scroll;

$to_use = 'category';

$next = get_previous_post( true, '', $to_use ); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions

if (
	Infinite_Scroll::get_instance()?->supports_single()
	&&
	is_singular()
	&&
	! empty( $next )
) :
	?>
	<div class="additional-posts">
		<div class="infinite-scroll-single" data-next="<?php echo esc_url( get_permalink( $next ) ); ?>"></div>
	</div>
<?php endif; ?>
