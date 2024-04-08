<?php
/**
 * Eutopia_Theme\Components\Comments class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Class for managing comments UI.
 */
class Comments extends Component {


	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function init(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_comment_reply_script' ] );
	}

	/**
	 * Enqueues the WordPress core 'comment-reply' script as necessary.
	 *
	 * This applies to singular post and page templates only.
	 *
	 * @return void
	 */
	public function action_enqueue_comment_reply_script() {
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Filters the comment form default arguments.
	 *
	 * Change the heading level to h2 when there are no comments.
	 *
	 * @param array $args The default comment form arguments.
	 * @return array      Modified comment form arguments.
	 */
	public function filter_comment_form_defaults( array $args ) {
		if ( ! get_comments_number() ) {
			$args['title_reply_before'] = '<h2 id="reply-title" class="comment-reply-title">';
			$args['title_reply_after']  = '</h2>';
		}

		return $args;
	}

	/**
	 * Displays the list of comments for the current post.
	 *
	 * Internally this method calls `wp_list_comments()`. However, in addition to that it will render the wrapping
	 * element for the list, so that must not be added manually.
	 *
	 * @param array $args Optional. Array of arguments. See `wp_list_comments()` documentation for a list of supported
	 *                    arguments.
	 *
	 * @return void
	 */
	public function the_comments( array $args = [] ) {
		$args = array_merge(
			$args,
			[
				'style'      => 'ol',
				'short_ping' => true,
			]
		);

		?>
		<ol class="comment-list">
			<?php wp_list_comments( $args ); ?>
		</ol><!-- .comment-list -->
		<?php

		the_comments_navigation();
	}
}
