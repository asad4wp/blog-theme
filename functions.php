<?php
/**
 * puns theme functions.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PUNS_VERSION', '1.4.4' );

require get_template_directory() . '/inc/nav-walkers.php';
require get_template_directory() . '/inc/copy-resources.php';
require get_template_directory() . '/inc/faq-tabs.php';
require get_template_directory() . '/inc/design-options.php';
require get_template_directory() . '/inc/load-more.php';
require get_template_directory() . '/inc/ajax-search.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/pwa.php';
require get_template_directory() . '/inc/seo.php';
require get_template_directory() . '/inc/rank-math.php';
require get_template_directory() . '/inc/consent.php';
require get_template_directory() . '/inc/cookies.php';
require get_template_directory() . '/inc/author-image.php';
require get_template_directory() . '/inc/static-pages.php';
require get_template_directory() . '/inc/block-patterns.php';
require get_template_directory() . '/inc/home-blocks.php';
require get_template_directory() . '/inc/gravity-forms.php';
require get_template_directory() . '/inc/custom-forms.php';

/**
 * Theme setup.
 */
function puns_setup() {
	load_theme_textdomain( 'puns', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 48,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	add_image_size( 'puns-card', 640, 360, true );
	add_image_size( 'puns-hero', 960, 1200, true );
	add_image_size( 'puns-sidebar', 104, 104, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'puns' ),
			'footer'  => __( 'Footer Menu', 'puns' ),
		)
	);
}
add_action( 'after_setup_theme', 'puns_setup' );

/**
 * Enqueue styles and scripts.
 */
function puns_enqueue_assets() {
	wp_enqueue_style(
		'puns',
		get_template_directory_uri() . '/assets/css/style.css',
		array(),
		PUNS_VERSION
	);

	wp_enqueue_script(
		'puns-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		PUNS_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'puns_enqueue_assets' );

/**
 * Estimated reading time in minutes.
 *
 * @param int|null $post_id Post ID.
 * @return int
 */
function puns_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );

	return $minutes;
}

/**
 * Map category slug to card tag modifier class.
 *
 * @param WP_Term|null $term Category term.
 * @return string
 */
function puns_category_tag_class( $term = null ) {
	if ( ! $term instanceof WP_Term ) {
		return 'puns-card__tag--creativity';
	}

	$map = array(
		'philosophy'  => 'puns-card__tag--philosophy',
		'environment' => 'puns-card__tag--environment',
		'creativity'  => 'puns-card__tag--creativity',
		'design'      => 'puns-card__tag--creativity',
		'technology'  => 'puns-card__tag--philosophy',
		'health'      => 'puns-card__tag--environment',
	);

	$slug = sanitize_title( $term->slug );

	return isset( $map[ $slug ] ) ? $map[ $slug ] : 'puns-card__tag--creativity';
}

/**
 * Featured post for the home hero (sticky post or latest).
 *
 * @return WP_Post|null
 */
function puns_get_featured_post() {
	$sticky = get_option( 'sticky_posts', array() );

	if ( ! empty( $sticky ) ) {
		$query = new WP_Query(
			array(
				'post__in'            => $sticky,
				'posts_per_page'      => 1,
				'ignore_sticky_posts' => 1,
				'post_status'         => 'publish',
			)
		);

		if ( $query->have_posts() ) {
			return $query->posts[0];
		}
	}

	$query = new WP_Query(
		array(
			'posts_per_page' => 1,
			'post_status'    => 'publish',
		)
	);

	return $query->have_posts() ? $query->posts[0] : null;
}

/**
 * Category icon class for sidebar.
 *
 * @param WP_Term $term Category term.
 * @return string
 */
function puns_category_icon_class( $term ) {
	$map = array(
		'digital-design'   => 'puns-sidebar__category-icon--teal',
		'web-development'  => 'puns-sidebar__category-icon--blue',
		'cyber-security'   => 'puns-sidebar__category-icon--amber',
		'philosophy'       => 'puns-sidebar__category-icon--amber',
		'creativity'       => 'puns-sidebar__category-icon--teal',
		'environment'      => 'puns-sidebar__category-icon--blue',
		'technology'       => 'puns-sidebar__category-icon--blue',
		'design'           => 'puns-sidebar__category-icon--teal',
	);

	$slug = sanitize_title( $term->slug );

	return isset( $map[ $slug ] ) ? $map[ $slug ] : 'puns-sidebar__category-icon--teal';
}

/**
 * Material icon name for category.
 *
 * @param WP_Term $term Category term.
 * @return string
 */
function puns_category_icon_name( $term ) {
	$map = array(
		'digital-design'  => 'palette',
		'web-development' => 'code',
		'cyber-security'  => 'shield',
		'philosophy'      => 'psychology',
		'creativity'      => 'palette',
		'environment'     => 'eco',
		'technology'      => 'code',
		'design'          => 'palette',
	);

	$slug = sanitize_title( $term->slug );

	return isset( $map[ $slug ] ) ? $map[ $slug ] : 'folder';
}

/**
 * Posts per page on the blog index (excluding hero featured post).
 *
 * @param WP_Query $query Main query.
 */
function puns_pre_get_posts( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_home() ) {
		$query->set( 'posts_per_page', puns_posts_per_page() );

		$featured_id = puns_home_featured_post_id();
		if ( $featured_id ) {
			$query->set( 'post__not_in', array( $featured_id ) );
		}
	}

	if ( $query->is_search() ) {
		$query->set( 'posts_per_page', puns_posts_per_page() );
	}

	if ( $query->is_archive() ) {
		$query->set( 'posts_per_page', puns_posts_per_page() );
	}
}
add_action( 'pre_get_posts', 'puns_pre_get_posts' );

/**
 * Add Lumina page body classes from the HTML design.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function puns_body_classes( $classes ) {
	if ( is_singular( 'post' ) ) {
		$classes[] = 'puns-page-article';
	} elseif ( is_home() || is_front_page() || is_archive() || is_search() || is_404() ) {
		$classes[] = 'puns-page-home';
	}

	if ( is_archive() ) {
		$classes[] = 'puns-page-archive';
	}

	if ( is_404() ) {
		$classes[] = 'puns-page-not-found';
	}

	return $classes;
}
add_filter( 'body_class', 'puns_body_classes' );

/**
 * Remove "Category:", "Tag:", etc. prefixes from archive titles.
 *
 * @param string $title Archive title.
 * @return string
 */
function puns_clean_archive_title( $title ) {
	if ( is_category() ) {
		return single_cat_title( '', false );
	}

	if ( is_tag() ) {
		return single_tag_title( '', false );
	}

	if ( is_author() ) {
		return get_the_author();
	}

	if ( is_tax() ) {
		return single_term_title( '', false );
	}

	if ( is_post_type_archive() ) {
		return post_type_archive_title( '', false );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'puns_clean_archive_title' );

/**
 * Fallback fonts when local assets are not bundled.
 */
function puns_enqueue_fonts() {
	$inter = get_template_directory() . '/assets/fonts/inter-400.ttf';
	if ( is_readable( $inter ) ) {
		return;
	}

	wp_enqueue_style(
		'puns-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'puns-icons',
		'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'puns_enqueue_fonts', 5 );
