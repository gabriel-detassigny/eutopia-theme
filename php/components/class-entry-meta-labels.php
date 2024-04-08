<?php
/**
 * Eutopia_Theme\Components\Entry_Meta_Labels class
 *
 * @package Eutopia_Theme
 */

namespace Eutopia_Theme\Components;

/**
 * Class for handling entry labels.
 */
class Entry_Meta_Labels extends Component {
	/**
	 * Get the entry post type label.
	 *
	 * @param \WP_Post|int|null $post The post object or ID.
	 *
	 * @return string
	 */
	public function get_entry_post_type_label( $post = null ): string {
		$post = get_post( $post );
		if ( ! ( $post instanceof \WP_Post ) ) {
			return '';
		}

		$post_type_object = get_post_type_object( get_post_type( $post ) );
		if ( ! ( $post_type_object instanceof \WP_Post_Type ) ) {
			return '';
		}

		return $post_type_object->labels->name ?? $post_type_object->name;
	}

	/**
	 * Get the entry category label.
	 *
	 * @param \WP_Post|int|null $post The post object or ID.
	 *
	 * @return string
	 */
	public function get_entry_category_label( $post = null ): string {
		$category = $this->get_entry_primary_category_object( $post );

		return $category->name ?? '';
	}

	/**
	 * Get the entry category archive link.
	 *
	 * @param \WP_Post|int|null $post The post object or ID.
	 *
	 * @return string
	 */
	public function get_entry_category_archive_link( $post = null ): string {
		$category = $this->get_entry_primary_category_object( $post );

		if ( ! $category ) {
			return '';
		}

		return get_category_link( $category );
	}

	/**
	 * Render the entry category label.
	 *
	 * @param array|string      $classes The classes to add to the link.
	 * @param \WP_Post|int|null $post    The post object.
	 *
	 * @return void
	 */
	public function the_entry_category_label( $classes = [], $post = null ): void {
		$category_label = $this->get_entry_category_label( $post );

		if ( empty( $category_label ) ) {
			return;
		}

		$classes = implode( ' ', (array) $classes );

		printf(
			'<span%1$s>%2$s</span>',
			empty( $classes ) ? '' : sprintf( ' class="%s"', esc_attr( $classes ) ),
			esc_html( $category_label ),
		);
	}

	/**
	 * Render the entry category label link.
	 *
	 * @param array|string      $classes The classes to add to the link.
	 * @param \WP_Post|int|null $post    The post object.
	 *
	 * @return void
	 */
	public function the_entry_category_label_link( $classes = [], $post = null ): void {
		$category_archive_link = $this->get_entry_category_archive_link( $post );
		$category_label        = $this->get_entry_category_label( $post );

		if ( empty( $category_archive_link ) || empty( $category_label ) ) {
			return;
		}

		$classes = implode( ' ', (array) $classes );

		printf(
			'<a href="%1$s" title="%2$s"%3$s rel="tag">%4$s</a>',
			esc_url( $category_archive_link ),
			esc_attr(
				sprintf(
					// translators: %s is the category name.
					__( 'Posts in the "%s" category', 'eutopia-theme' ),
					$category_label,
				),
			),
			empty( $classes ) ? '' : sprintf( ' class="%s"', esc_attr( $classes ) ),
			esc_html( $category_label ),
		);
	}

	/**
	 * Get the entry published date label.
	 *
	 * @param string            $format The date format (optional).
	 * @param \WP_Post|int|null $post   The post object or ID.
	 *
	 * @return string
	 */
	public function get_entry_date_label( string $format = '', $post = null ): string {
		$post = get_post( $post );
		if ( ! ( $post instanceof \WP_Post ) || empty( $format ) ) {
			return '';
		}

		return get_the_date( $format, $post );
	}

	/**
	 * Render the entry category label.
	 *
	 * @param array|string      $classes The classes to add to the link.
	 * @param string            $format  The date format (optional).
	 * @param \WP_Post|int|null $post    The post object.
	 *
	 * @return void
	 */
	public function the_entry_date_label( $classes = [], string $format = '', $post = null ): void {
		$date_label = $this->get_entry_date_label( $format, $post );

		if ( empty( $date_label ) ) {
			return;
		}

		$classes = implode( ' ', (array) $classes );

		printf(
			'<time datetime="%1$s"%2$s>%3$s</time>',
			// The "c" format stands for ISO 8601 date, e.g. "2004-02-12T15:19:21+00:00".
			esc_attr( get_the_date( 'c', $post ) ),
			empty( $classes ) ? '' : sprintf( ' class="%s"', esc_attr( $classes ) ),
			esc_html( $date_label ),
		);
	}

	/**
	 * Render the entry date label link.
	 *
	 * @param array|string      $classes The classes to add to the link.
	 * @param string            $format  The date format (optional).
	 * @param \WP_Post|int|null $post    The post object.
	 *
	 * @return void
	 */
	public function the_entry_date_label_link( $classes = [], string $format = '', $post = null ): void {
		$date_label = $this->get_entry_date_label( $format, $post );

		if ( empty( $date_label ) ) {
			return;
		}

		$classes = implode( ' ', (array) $classes );

		printf(
			'<a href="%1$s" rel="bookmark"%2$s>',
			esc_url( get_permalink( $post ) ),
			empty( $classes ) ? '' : sprintf( ' class="%s"', esc_attr( $classes ) ),
		);

		$this->the_entry_date_label( '', $format, $post );

		echo '</a>';
	}

	/**
	 * Get the entry author label.
	 *
	 * @param \WP_Post|int|null $post The post object or ID.
	 *
	 * @return string
	 */
	public function get_entry_author_label( $post = null ): string {
		$author_id = $this->get_entry_author_id( $post );
		if ( empty( $author_id ) ) {
			return '';
		}

		return get_the_author_meta( 'display_name', $author_id );
	}

	/**
	 * Get the entry author archive URL.
	 *
	 * @param \WP_Post|int|null $post The post object or ID.
	 *
	 * @return string
	 */
	public function get_entry_author_archive_link( $post = null ): string {
		$author_id = $this->get_entry_author_id( $post );
		if ( empty( $author_id ) ) {
			return '';
		}

		return get_author_posts_url( $author_id );
	}

	/**
	 * Render the entry author label link.
	 *
	 * @param array|string      $classes The classes to add to the link.
	 * @param \WP_Post|int|null $post    The post object.
	 *
	 * @return void
	 */
	public function the_entry_author_label_link( $classes = [], $post = null ): void {
		$author_archive_link = $this->get_entry_author_archive_link( $post );
		$author_label        = $this->get_entry_author_label( $post );

		if ( empty( $author_archive_link ) || empty( $author_label ) ) {
			return;
		}

		$classes = [
			...(array) $classes,
			'author',
			'url',
			'fn',
		];

		printf(
			'<a href="%1$s" title="%2$s" class="%3$s" rel="author">%4$s</a>',
			esc_url( $author_archive_link ),
			esc_attr(
				sprintf(
				// translators: %s is the author name.
					__( 'Posts by %s', 'eutopia-theme' ),
					$author_label,
				),
			),
			esc_attr( implode( ' ', $classes ) ),
			esc_html( $author_label ),
		);
	}

	/**
	 * Get the entry primary category object.
	 *
	 * @param \WP_Post|int|null $post The post object or ID.
	 *
	 * @return \WP_Term|null
	 */
	protected function get_entry_primary_category_object( $post = null ): ?\WP_Term {
		$post = get_post( $post );
		if ( ! ( $post instanceof \WP_Post ) ) {
			return null;
		}

		// Selecting the primary category may be dependent on a specific business logic.
		$categories = wp_get_post_categories(
			$post->ID,
			[
				'fields' => 'all',
				'count'  => 1,
			],
		);

		if ( empty( $categories[0] ) || ! ( $categories[0] instanceof \WP_Term ) ) {
			return null;
		}

		return $categories[0];
	}

	/**
	 * Get the entry author ID.
	 *
	 * @param \WP_Post|int|null $post The post object or ID.
	 *
	 * @return int
	 */
	protected function get_entry_author_id( $post = null ): int {
		$post = get_post( $post );
		if ( ! ( $post instanceof \WP_Post ) ) {
			return 0;
		}

		return absint( $post->post_author ) ?? 0;
	}
}
