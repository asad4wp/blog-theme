<?php
/**
 * Load More posts for homepage, search, and archives (AJAX).
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Posts per batch in post grids.
 *
 * @return int
 */
function puns_posts_per_page() {
	return 9;
}

/**
 * Featured post ID to exclude from the homepage grid.
 *
 * @return int
 */
function puns_home_featured_post_id() {
	$featured = puns_get_featured_post();

	return $featured ? (int) $featured->ID : 0;
}

/**
 * Whether the current page supports Load More.
 *
 * @return bool
 */
function puns_load_more_is_supported_page() {
	return is_home() || is_front_page() || is_search() || is_archive();
}

/**
 * Build Load More params for the current request.
 *
 * @return array<string, mixed>
 */
function puns_get_load_more_params() {
	if ( is_search() ) {
		return array(
			'context' => 'search',
			'search'  => get_search_query(),
		);
	}

	if ( is_category() ) {
		return array(
			'context'      => 'archive',
			'archive_type' => 'category',
			'term_id'      => get_queried_object_id(),
		);
	}

	if ( is_tag() ) {
		return array(
			'context'      => 'archive',
			'archive_type' => 'tag',
			'term_id'      => get_queried_object_id(),
		);
	}

	if ( is_author() ) {
		return array(
			'context'      => 'archive',
			'archive_type' => 'author',
			'author_id'    => get_queried_object_id(),
		);
	}

	if ( is_date() ) {
		$params = array(
			'context'      => 'archive',
			'archive_type' => 'date',
			'year'         => (int) get_query_var( 'year' ),
		);

		if ( is_month() || is_day() ) {
			$params['monthnum'] = (int) get_query_var( 'monthnum' );
		}

		if ( is_day() ) {
			$params['day'] = (int) get_query_var( 'day' );
		}

		return $params;
	}

	if ( is_post_type_archive() ) {
		return array(
			'context'      => 'archive',
			'archive_type' => 'post_type',
			'post_type'    => get_post_type(),
		);
	}

	return array(
		'context' => 'home',
	);
}

/**
 * Parse Load More params from an AJAX request.
 *
 * @return array<string, mixed>
 */
function puns_parse_load_more_request_params() {
	$context = isset( $_POST['context'] ) ? sanitize_key( wp_unslash( $_POST['context'] ) ) : 'home';

	if ( ! in_array( $context, array( 'home', 'search', 'archive' ), true ) ) {
		$context = 'home';
	}

	$params = array(
		'context' => $context,
	);

	if ( 'search' === $context ) {
		$params['search'] = isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '';
		return $params;
	}

	if ( 'archive' === $context ) {
		$params['archive_type'] = isset( $_POST['archive_type'] ) ? sanitize_key( wp_unslash( $_POST['archive_type'] ) ) : '';
		$params['term_id']      = isset( $_POST['term_id'] ) ? absint( $_POST['term_id'] ) : 0;
		$params['author_id']    = isset( $_POST['author_id'] ) ? absint( $_POST['author_id'] ) : 0;
		$params['year']         = isset( $_POST['year'] ) ? absint( $_POST['year'] ) : 0;
		$params['monthnum']     = isset( $_POST['monthnum'] ) ? absint( $_POST['monthnum'] ) : 0;
		$params['day']          = isset( $_POST['day'] ) ? absint( $_POST['day'] ) : 0;
		$params['post_type']    = isset( $_POST['post_type'] ) ? sanitize_key( wp_unslash( $_POST['post_type'] ) ) : 'post';
	}

	return $params;
}

/**
 * Apply archive filters to a query args array.
 *
 * @param array<string, mixed> $args   Query args.
 * @param array<string, mixed> $params Load More params.
 */
function puns_apply_archive_load_more_args( array &$args, array $params ) {
	switch ( $params['archive_type'] ?? '' ) {
		case 'category':
			if ( ! empty( $params['term_id'] ) ) {
				$args['cat'] = (int) $params['term_id'];
			}
			break;

		case 'tag':
			if ( ! empty( $params['term_id'] ) ) {
				$args['tag_id'] = (int) $params['term_id'];
			}
			break;

		case 'author':
			if ( ! empty( $params['author_id'] ) ) {
				$args['author'] = (int) $params['author_id'];
			}
			break;

		case 'date':
			if ( ! empty( $params['year'] ) ) {
				$args['year'] = (int) $params['year'];
			}
			if ( ! empty( $params['monthnum'] ) ) {
				$args['monthnum'] = (int) $params['monthnum'];
			}
			if ( ! empty( $params['day'] ) ) {
				$args['day'] = (int) $params['day'];
			}
			break;

		case 'post_type':
			if ( ! empty( $params['post_type'] ) ) {
				$args['post_type'] = $params['post_type'];
			}
			break;
	}
}

/**
 * Query args for Load More requests.
 *
 * @param int                  $page   Page number.
 * @param array<string, mixed> $params Load More params.
 * @return array<string, mixed>
 */
function puns_load_more_query_args( $page = 1, $params = array() ) {
	$context = $params['context'] ?? 'home';

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => puns_posts_per_page(),
		'paged'               => max( 1, (int) $page ),
		'ignore_sticky_posts' => true,
	);

	if ( 'search' === $context ) {
		$args['s'] = $params['search'] ?? '';
	} elseif ( 'archive' === $context ) {
		puns_apply_archive_load_more_args( $args, $params );
	} else {
		$exclude = puns_home_featured_post_id();
		if ( $exclude ) {
			$args['post__not_in'] = array( $exclude );
		}
	}

	return $args;
}

/**
 * Render post cards HTML for a Load More query page.
 *
 * @param int                  $page   Page number.
 * @param array<string, mixed> $params Load More params.
 * @return array{html:string,has_more:bool,max_pages:int}
 */
function puns_get_posts_batch( $page = 1, $params = array() ) {
	if ( is_string( $params ) ) {
		$params = array(
			'context' => 'search',
			'search'  => $params,
		);
	}

	$query = new WP_Query( puns_load_more_query_args( $page, $params ) );
	$html  = '';

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			ob_start();
			get_template_part( 'template-parts/content', 'card' );
			$html .= ob_get_clean();
		}
	}

	wp_reset_postdata();

	$page      = max( 1, (int) $page );
	$max_pages = (int) $query->max_num_pages;
	$has_more  = $page < $max_pages;

	return array(
		'html'      => $html,
		'has_more'  => $has_more,
		'max_pages' => $max_pages,
	);
}

/**
 * Validate Load More request params.
 *
 * @param array<string, mixed> $params Load More params.
 * @return true|WP_Error
 */
function puns_validate_load_more_params( $params ) {
	if ( 'search' === ( $params['context'] ?? '' ) && empty( $params['search'] ) ) {
		return new WP_Error( 'missing_search', __( 'Missing search query.', 'puns' ) );
	}

	if ( 'archive' !== ( $params['context'] ?? '' ) ) {
		return true;
	}

	switch ( $params['archive_type'] ?? '' ) {
		case 'category':
		case 'tag':
			if ( empty( $params['term_id'] ) ) {
				return new WP_Error( 'missing_term', __( 'Missing archive term.', 'puns' ) );
			}
			break;

		case 'author':
			if ( empty( $params['author_id'] ) ) {
				return new WP_Error( 'missing_author', __( 'Missing archive author.', 'puns' ) );
			}
			break;

		case 'date':
			if ( empty( $params['year'] ) ) {
				return new WP_Error( 'missing_date', __( 'Missing archive date.', 'puns' ) );
			}
			break;

		case 'post_type':
			if ( empty( $params['post_type'] ) ) {
				return new WP_Error( 'missing_post_type', __( 'Missing archive post type.', 'puns' ) );
			}
			break;

		default:
			return new WP_Error( 'invalid_archive', __( 'Invalid archive type.', 'puns' ) );
	}

	return true;
}

/**
 * AJAX: load next posts batch.
 */
function puns_ajax_load_more_posts() {
	check_ajax_referer( 'puns_load_more', 'nonce' );

	$page   = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
	$params = puns_parse_load_more_request_params();
	$valid  = puns_validate_load_more_params( $params );

	if ( is_wp_error( $valid ) ) {
		wp_send_json_error(
			array(
				'message' => $valid->get_error_message(),
			)
		);
	}

	$result = puns_get_posts_batch( $page, $params );

	if ( '' === $result['html'] ) {
		wp_send_json_error(
			array(
				'message' => __( 'No more posts found.', 'puns' ),
			)
		);
	}

	wp_send_json_success(
		array(
			'html'      => $result['html'],
			'has_more'  => $result['has_more'],
			'max_pages' => $result['max_pages'],
			'page'      => $page,
		)
	);
}
add_action( 'wp_ajax_puns_load_more', 'puns_ajax_load_more_posts' );
add_action( 'wp_ajax_nopriv_puns_load_more', 'puns_ajax_load_more_posts' );

/**
 * Enqueue Load More script on supported pages.
 */
function puns_enqueue_load_more() {
	if ( ! puns_load_more_is_supported_page() ) {
		return;
	}

	wp_enqueue_script(
		'puns-load-more',
		get_template_directory_uri() . '/assets/js/load-more.js',
		array(),
		PUNS_VERSION,
		true
	);

	global $wp_query;

	$config = array_merge(
		array(
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'puns_load_more' ),
			'action'    => 'puns_load_more',
			'page'      => 1,
			'maxPages'  => (int) $wp_query->max_num_pages,
			'loading'   => __( 'Loading...', 'puns' ),
			'loadMore'  => __( 'Load More', 'puns' ),
			'loadError' => __( 'Could not load posts. Try again.', 'puns' ),
			'skeletons' => 3,
		),
		puns_get_load_more_params()
	);

	wp_localize_script( 'puns-load-more', 'punsBlogLoadMore', $config );
}
add_action( 'wp_enqueue_scripts', 'puns_enqueue_load_more' );

/**
 * Backwards-compatible alias used in theme templates.
 *
 * @return int
 */
function puns_home_posts_per_page() {
	return puns_posts_per_page();
}

/**
 * Backwards-compatible alias used in theme templates.
 *
 * @param int $page Page number.
 * @return array{html:string,has_more:bool,max_pages:int}
 */
function puns_get_home_posts_batch( $page = 1 ) {
	return puns_get_posts_batch( $page, array( 'context' => 'home' ) );
}
