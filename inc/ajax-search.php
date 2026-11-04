<?php
/**
 * AJAX live search.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Minimum characters required before searching.
 *
 * @return int
 */
function puns_search_min_chars() {
	return 2;
}

/**
 * Maximum AJAX search results.
 *
 * @return int
 */
function puns_search_results_limit() {
	return 8;
}

/**
 * Render AJAX search results HTML.
 *
 * @param string $query Search query.
 * @return array{html:string,count:int}
 */
function puns_get_search_results_html( $query ) {
	$query = trim( (string) $query );

	if ( mb_strlen( $query ) < puns_search_min_chars() ) {
		return array(
			'html'  => '<p class="puns-search-results__hint">' . esc_html__( 'Type at least 2 characters to search.', 'puns' ) . '</p>',
			'count' => 0,
		);
	}

	$search = new WP_Query(
		array(
			's'              => $query,
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => puns_search_results_limit(),
		)
	);

	ob_start();

	if ( $search->have_posts() ) {
		echo '<ul class="puns-search-results__list">';

		while ( $search->have_posts() ) {
			$search->the_post();
			get_template_part( 'template-parts/content', 'search-result' );
		}

		echo '</ul>';

		if ( (int) $search->found_posts > puns_search_results_limit() ) {
			printf(
				'<a class="puns-search-results__all" href="%1$s">%2$s</a>',
				esc_url(
					add_query_arg(
						array(
							's' => $query,
						),
						home_url( '/' )
					)
				),
				esc_html(
					sprintf(
						/* translators: %d: total result count */
						__( 'View all %d results', 'puns' ),
						(int) $search->found_posts
					)
				)
			);
		}
	} else {
		echo '<p class="puns-search-results__empty">' . esc_html__( 'No results found. Try a different search term.', 'puns' ) . '</p>';
	}

	wp_reset_postdata();

	return array(
		'html'  => ob_get_clean(),
		'count' => (int) $search->found_posts,
	);
}

/**
 * AJAX search handler.
 */
function puns_ajax_search_posts() {
	check_ajax_referer( 'puns_search', 'nonce' );

	$query  = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
	$result = puns_get_search_results_html( $query );

	wp_send_json_success( $result );
}
add_action( 'wp_ajax_puns_search', 'puns_ajax_search_posts' );
add_action( 'wp_ajax_nopriv_puns_search', 'puns_ajax_search_posts' );

/**
 * Enqueue AJAX search assets.
 */
function puns_enqueue_ajax_search() {
	wp_enqueue_script(
		'puns-ajax-search',
		get_template_directory_uri() . '/assets/js/ajax-search.js',
		array(),
		PUNS_VERSION,
		true
	);

	wp_localize_script(
		'puns-ajax-search',
		'punsBlogSearch',
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'puns_search' ),
			'action'    => 'puns_search',
			'minChars'  => puns_search_min_chars(),
			'skeletons' => 4,
			'loading'      => __( 'Searching...', 'puns' ),
			'placeholder'  => __( 'Search essays, topics, keywords...', 'puns' ),
			'emptyHint'    => __( 'Start typing to find posts.', 'puns' ),
			'error'        => __( 'Could not search right now. Try again.', 'puns' ),
			'initialQuery' => is_search() ? get_search_query() : '',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'puns_enqueue_ajax_search' );
