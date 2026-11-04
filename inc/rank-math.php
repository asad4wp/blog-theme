<?php
/**
 * Rank Math SEO compatibility.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether Rank Math SEO is active.
 *
 * @return bool
 */
function puns_rank_math_is_active() {
	return defined( 'RANK_MATH_VERSION' );
}

/**
 * Whether Rank Math breadcrumbs are enabled.
 *
 * @return bool
 */
function puns_rank_math_breadcrumbs_enabled() {
	if ( ! puns_rank_math_is_active() ) {
		return false;
	}

	if ( class_exists( 'RankMath\Helper', false ) && method_exists( 'RankMath\Helper', 'is_breadcrumbs_enabled' ) ) {
		return (bool) RankMath\Helper::is_breadcrumbs_enabled();
	}

	$options = get_option( 'rank-math-options-general', array() );

	return ! empty( $options['breadcrumbs'] );
}

/**
 * Register native Rank Math breadcrumbs support.
 */
function puns_rank_math_setup() {
	if ( ! puns_rank_math_is_active() ) {
		return;
	}

	add_theme_support( 'rank-math-breadcrumbs' );
}
add_action( 'after_setup_theme', 'puns_rank_math_setup', 20 );

/**
 * Wrap Rank Math breadcrumb output with theme classes.
 *
 * @param string $html Breadcrumb HTML.
 * @return string
 */
function puns_rank_math_breadcrumb_html( $html ) {
	if ( '' === trim( $html ) ) {
		return $html;
	}

	return '<div class="puns-breadcrumbs__rank-math">' . $html . '</div>';
}
add_filter( 'rank_math/frontend/breadcrumb/html', 'puns_rank_math_breadcrumb_html' );

/**
 * Render Rank Math breadcrumbs using theme markup.
 */
function puns_the_rank_math_breadcrumbs() {
	if ( ! puns_rank_math_is_active() || ! puns_rank_math_breadcrumbs_enabled() ) {
		return;
	}

	if ( ! function_exists( 'rank_math_the_breadcrumbs' ) ) {
		return;
	}

	echo '<nav class="puns-breadcrumbs puns-container" aria-label="' . esc_attr__( 'Breadcrumb', 'puns' ) . '">';
	rank_math_the_breadcrumbs();
	echo '</nav>';
}

/**
 * Disable standalone theme JSON-LD when Rank Math handles schema.
 */
function puns_rank_math_disable_theme_schema_output() {
	if ( ! puns_rank_math_is_active() ) {
		return;
	}

	remove_action( 'wp_head', 'puns_output_schema', 20 );
}
add_action( 'wp', 'puns_rank_math_disable_theme_schema_output' );
