<?php
/**
 * Editable homepage blocks / shortcodes.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Featured hero shortcode.
 *
 * [puns_featured_hero eyebrow="Editor's Choice" button="Read More"]
 *
 * @param array<string, string> $atts Attributes.
 * @return string
 */
function puns_featured_hero_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'eyebrow' => __( "Editor's Choice", 'puns' ),
			'button'  => __( 'Read More', 'puns' ),
		),
		$atts,
		'puns_featured_hero'
	);

	ob_start();
	get_template_part(
		'template-parts/home',
		'featured',
		array(
			'eyebrow' => $atts['eyebrow'],
			'button'  => $atts['button'],
		)
	);
	return (string) ob_get_clean();
}
add_shortcode( 'puns_featured_hero', 'puns_featured_hero_shortcode' );

/**
 * Latest posts grid shortcode.
 *
 * [puns_latest_posts title="Latest Posts" show_title="1"]
 *
 * @param array<string, string> $atts Attributes.
 * @return string
 */
function puns_latest_posts_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'title'      => '',
			'show_title' => '1',
		),
		$atts,
		'puns_latest_posts'
	);

	$show_title = ! in_array( strtolower( (string) $atts['show_title'] ), array( '0', 'false', 'no' ), true );

	ob_start();
	get_template_part(
		'template-parts/home',
		'posts',
		array(
			'title'      => $atts['title'],
			'show_title' => $show_title,
		)
	);
	return (string) ob_get_clean();
}
add_shortcode( 'puns_latest_posts', 'puns_latest_posts_shortcode' );

/**
 * Default Home page block markup.
 *
 * @return string
 */
function puns_get_home_default_content() {
	return '<!-- wp:shortcode -->
[puns_featured_hero]
<!-- /wp:shortcode -->

<!-- wp:group {"className":"puns-home-editable","layout":{"type":"constrained"}} -->
<div class="wp-block-group puns-home-editable"><!-- wp:paragraph {"align":"center","className":"puns-home-editable__note"} -->
<p class="has-text-align-center puns-home-editable__note">' . esc_html__( 'Add any intro text, banners, or blocks above your post grid. Everything in this page editor is editable.', 'puns' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:shortcode -->
[puns_latest_posts]
<!-- /wp:shortcode -->';
}

/**
 * Whether the current front page is the editable Home page.
 *
 * @return bool
 */
function puns_is_editable_front_page() {
	return is_front_page()
		&& 'page' === get_option( 'show_on_front' )
		&& (int) get_option( 'page_on_front' ) > 0;
}

/**
 * Ensure a Gutenberg-editable Home page exists and is the front page.
 */
function puns_seed_editable_home_page() {
	$front_id = (int) get_option( 'page_on_front' );
	$front    = $front_id ? get_post( $front_id ) : null;

	if ( $front instanceof WP_Post && 'page' === $front->post_type ) {
		$content = trim( (string) $front->post_content );
		if ( '' === $content || ! has_blocks( $content ) ) {
			wp_update_post(
				array(
					'ID'           => $front->ID,
					'post_content' => puns_get_home_default_content(),
				)
			);
		}

		update_post_meta( $front->ID, '_wp_page_template', 'page-templates/home.php' );

		if ( 'page' !== get_option( 'show_on_front' ) ) {
			update_option( 'show_on_front', 'page' );
		}

		return;
	}

	$existing = get_page_by_path( 'home' );
	if ( ! $existing ) {
		$found = get_posts(
			array(
				'post_type'              => 'page',
				'title'                  => __( 'Home', 'puns' ),
				'post_status'            => 'any',
				'numberposts'            => 1,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
		$existing = ! empty( $found[0] ) ? $found[0] : null;
	}

	if ( $existing instanceof WP_Post ) {
		$page_id = (int) $existing->ID;
		$content = trim( (string) $existing->post_content );
		if ( '' === $content || ! has_blocks( $content ) ) {
			wp_update_post(
				array(
					'ID'           => $page_id,
					'post_content' => puns_get_home_default_content(),
				)
			);
		}
	} else {
		$page_id = wp_insert_post(
			array(
				'post_title'   => __( 'Home', 'puns' ),
				'post_name'    => 'home',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => puns_get_home_default_content(),
			),
			true
		);

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			return;
		}
	}

	update_post_meta( (int) $page_id, '_wp_page_template', 'page-templates/home.php' );
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', (int) $page_id );

	// Keep a Posts page for the blog index when missing.
	$posts_id = (int) get_option( 'page_for_posts' );
	if ( ! $posts_id ) {
		$posts = get_page_by_path( 'blog' );
		if ( ! $posts ) {
			$posts_id = wp_insert_post(
				array(
					'post_title'  => __( 'Blog', 'puns' ),
					'post_name'   => 'blog',
					'post_status' => 'publish',
					'post_type'   => 'page',
				),
				true
			);
			if ( ! is_wp_error( $posts_id ) && $posts_id ) {
				update_option( 'page_for_posts', (int) $posts_id );
			}
		} else {
			update_option( 'page_for_posts', (int) $posts->ID );
		}
	}
}
add_action( 'after_switch_theme', 'puns_seed_editable_home_page' );

/**
 * One-time seed for already-active installs.
 */
function puns_maybe_seed_editable_home_page() {
	if ( get_option( 'puns_seeded_editable_home' ) ) {
		return;
	}

	puns_seed_editable_home_page();
	update_option( 'puns_seeded_editable_home', PUNS_VERSION, false );
}
add_action( 'init', 'puns_maybe_seed_editable_home_page', 25 );

/**
 * Normalize curly quotes in content so shortcodes still parse.
 *
 * @param string $content Post content.
 * @return string
 */
function puns_normalize_shortcode_quotes( $content ) {
	if ( ! is_string( $content ) || '' === $content ) {
		return $content;
	}

	$search = array(
		'“',
		'”',
		'‘',
		'’',
		'&#8220;',
		'&#8221;',
		'&#8216;',
		'&#8217;',
		'&ldquo;',
		'&rdquo;',
		'&lsquo;',
		'&rsquo;',
	);

	$replace = array(
		'"',
		'"',
		"'",
		"'",
		'"',
		'"',
		"'",
		"'",
		'"',
		'"',
		"'",
		"'",
	);

	return str_replace( $search, $replace, $content );
}
add_filter( 'the_content', 'puns_normalize_shortcode_quotes', 8 );

/**
 * Keep theme shortcodes out of wptexturize so quotes stay valid.
 *
 * @param string[] $shortcodes Shortcode tags.
 * @return string[]
 */
function puns_no_texturize_shortcodes( $shortcodes ) {
	$shortcodes[] = 'puns_featured_hero';
	$shortcodes[] = 'puns_latest_posts';
	$shortcodes[] = 'puns_form';
	return $shortcodes;
}
add_filter( 'no_texturize_shortcodes', 'puns_no_texturize_shortcodes' );

/**
 * Normalize quotes inside Shortcode blocks before render.
 *
 * @param array $block Block data.
 * @return array
 */
function puns_normalize_shortcode_block_quotes( $block ) {
	if ( empty( $block['blockName'] ) || 'core/shortcode' !== $block['blockName'] ) {
		return $block;
	}

	if ( ! empty( $block['innerHTML'] ) ) {
		$block['innerHTML'] = puns_normalize_shortcode_quotes( $block['innerHTML'] );
	}

	if ( ! empty( $block['innerContent'] ) && is_array( $block['innerContent'] ) ) {
		$block['innerContent'] = array_map(
			static function ( $chunk ) {
				return is_string( $chunk ) ? puns_normalize_shortcode_quotes( $chunk ) : $chunk;
			},
			$block['innerContent']
		);
	}

	return $block;
}
add_filter( 'render_block_data', 'puns_normalize_shortcode_block_quotes' );

/**
 * Repair Home page shortcodes that were saved with curly quotes.
 */
function puns_repair_home_shortcodes() {
	if ( '4' === (string) get_option( 'puns_repaired_home_shortcodes' ) ) {
		return;
	}

	$front_id = (int) get_option( 'page_on_front' );
	if ( $front_id ) {
		wp_update_post(
			array(
				'ID'           => $front_id,
				'post_content' => puns_get_home_default_content(),
			)
		);
	}

	update_option( 'puns_repaired_home_shortcodes', '4', false );
}
add_action( 'init', 'puns_repair_home_shortcodes', 30 );
