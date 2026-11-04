<?php
/**
 * Block patterns for editable About / Contact pages.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register pattern category and patterns.
 */
function puns_register_block_patterns() {
	register_block_pattern_category(
		'puns-pages',
		array(
			'label' => __( 'Blog Theme Pages', 'puns' ),
		)
	);

	register_block_pattern(
		'puns/about-page',
		array(
			'title'       => __( 'About Us page', 'puns' ),
			'description' => __( 'Fully editable About layout: hero, mission, value cards, and CTA.', 'puns' ),
			'categories'  => array( 'puns-pages' ),
			'content'     => puns_get_about_default_content(),
		)
	);

	register_block_pattern(
		'puns/contact-page',
		array(
			'title'       => __( 'Contact Us page', 'puns' ),
			'description' => __( 'Fully editable Contact layout: hero, form shortcode, and info cards.', 'puns' ),
			'categories'  => array( 'puns-pages' ),
			'content'     => puns_get_contact_default_content(),
		)
	);
}
add_action( 'init', 'puns_register_block_patterns' );

/**
 * Default About page block markup.
 *
 * @return string
 */
function puns_get_about_default_content() {
	$site_name = get_bloginfo( 'name', 'display' );
	if ( '' === trim( (string) $site_name ) ) {
		$site_name = __( 'Blog Theme', 'puns' );
	}

	$lede = sprintf(
		/* translators: %s: site name */
		__( '%s is a home for stories, ideas, and writing that sticks with you long after you leave the page.', 'puns' ),
		$site_name
	);

	return '<!-- wp:group {"className":"puns-static__hero","layout":{"type":"default"}} -->
<div class="wp-block-group puns-static__hero"><!-- wp:group {"className":"puns-static__eyebrow","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group puns-static__eyebrow"><!-- wp:html -->
<span class="puns-static__eyebrow-line" aria-hidden="true"></span>
<!-- /wp:html -->

<!-- wp:paragraph {"className":"puns-static__eyebrow-text"} -->
<p class="puns-static__eyebrow-text">' . esc_html__( 'Our story', 'puns' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:post-title {"level":1,"className":"puns-article__title puns-static__title"} /-->

<!-- wp:paragraph {"className":"puns-static__lede"} -->
<p class="puns-static__lede">' . esc_html( $lede ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"puns-static__section","layout":{"type":"default"}} -->
<div class="wp-block-group puns-static__section"><!-- wp:group {"className":"puns-static__panel","layout":{"type":"default"}} -->
<div class="wp-block-group puns-static__panel"><!-- wp:heading {"level":2,"className":"puns-static__heading"} -->
<h2 class="wp-block-heading puns-static__heading">' . esc_html__( 'Why we publish', 'puns' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"puns-static__text"} -->
<p class="puns-static__text">' . esc_html__( 'We believe writing is a craft. Every story and idea here is published to inform, entertain, and celebrate the writers who make that happen.', 'puns' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"puns-static__section","layout":{"type":"default"}} -->
<div class="wp-block-group puns-static__section"><!-- wp:heading {"level":2,"className":"puns-static__heading"} -->
<h2 class="wp-block-heading puns-static__heading">' . esc_html__( 'What we stand for', 'puns' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"puns-static__grid"} -->
<div class="wp-block-columns puns-static__grid"><!-- wp:column {"className":"puns-static__card"} -->
<div class="wp-block-column puns-static__card"><!-- wp:html -->
<span class="puns-static__card-icon puns-icon" aria-hidden="true">sentiment_very_satisfied</span>
<!-- /wp:html -->

<!-- wp:heading {"level":3,"className":"puns-static__card-title"} -->
<h3 class="wp-block-heading puns-static__card-title">' . esc_html__( 'Joy first', 'puns' ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"puns-static__card-text"} -->
<p class="puns-static__card-text">' . esc_html__( 'If it is not enjoyable to read, it does not ship. Cleverness without kindness is not our style.', 'puns' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"puns-static__card"} -->
<div class="wp-block-column puns-static__card"><!-- wp:html -->
<span class="puns-static__card-icon puns-icon" aria-hidden="true">edit_note</span>
<!-- /wp:html -->

<!-- wp:heading {"level":3,"className":"puns-static__card-title"} -->
<h3 class="wp-block-heading puns-static__card-title">' . esc_html__( 'Crafted words', 'puns' ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"puns-static__card-text"} -->
<p class="puns-static__card-text">' . esc_html__( 'Tight openings, clear ideas, and headlines that earn the click — then reward it.', 'puns' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"puns-static__card"} -->
<div class="wp-block-column puns-static__card"><!-- wp:html -->
<span class="puns-static__card-icon puns-icon" aria-hidden="true">groups</span>
<!-- /wp:html -->

<!-- wp:heading {"level":3,"className":"puns-static__card-title"} -->
<h3 class="wp-block-heading puns-static__card-title">' . esc_html__( 'Community voices', 'puns' ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"puns-static__card-text"} -->
<p class="puns-static__card-text">' . esc_html__( 'Readers and writers shape this place together. Fresh voices keep the feed feeling alive.', 'puns' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"puns-static__section puns-static__cta","layout":{"type":"default"}} -->
<div class="wp-block-group puns-static__section puns-static__cta"><!-- wp:group {"className":"puns-static__cta-inner","layout":{"type":"default"}} -->
<div class="wp-block-group puns-static__cta-inner"><!-- wp:heading {"level":2,"className":"puns-static__heading puns-static__heading--on-accent"} -->
<h2 class="wp-block-heading puns-static__heading puns-static__heading--on-accent">' . esc_html__( 'Want to work with us?', 'puns' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"puns-static__text puns-static__text--on-accent"} -->
<p class="puns-static__text puns-static__text--on-accent">' . esc_html__( 'Pitch a story, a series, or an idea. We are always looking for new voices.', 'puns' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"puns-static__actions"} -->
<div class="wp-block-buttons puns-static__actions"><!-- wp:button {"className":"puns-btn puns-btn--solid puns-static__btn-light is-style-fill"} -->
<div class="wp-block-button puns-btn puns-btn--solid puns-static__btn-light is-style-fill"><a class="wp-block-button__link wp-element-button" href="/write-with-us/">' . esc_html__( 'Write with Us', 'puns' ) . '</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"puns-btn puns-btn--outline puns-static__btn-ghost is-style-outline"} -->
<div class="wp-block-button puns-btn puns-btn--outline puns-static__btn-ghost is-style-outline"><a class="wp-block-button__link wp-element-button" href="/contact/">' . esc_html__( 'Contact Us', 'puns' ) . '</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->';
}

/**
 * Default Contact page block markup.
 *
 * @return string
 */
function puns_get_contact_default_content() {
	$admin_mail = antispambot( get_option( 'admin_email' ) );

	return '<!-- wp:group {"className":"puns-static__hero","layout":{"type":"default"}} -->
<div class="wp-block-group puns-static__hero"><!-- wp:group {"className":"puns-static__eyebrow","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group puns-static__eyebrow"><!-- wp:html -->
<span class="puns-static__eyebrow-line" aria-hidden="true"></span>
<!-- /wp:html -->

<!-- wp:paragraph {"className":"puns-static__eyebrow-text"} -->
<p class="puns-static__eyebrow-text">' . esc_html__( 'Say hello', 'puns' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:post-title {"level":1,"className":"puns-article__title puns-static__title"} /-->

<!-- wp:paragraph {"className":"puns-static__lede"} -->
<p class="puns-static__lede">' . esc_html__( 'Questions, feedback, partnerships, or just a note you want to share — drop us a message.', 'puns' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"puns-static__section puns-contact__form-section","layout":{"type":"default"}} -->
<div class="wp-block-group puns-static__section puns-contact__form-section"><!-- wp:group {"className":"puns-static__panel puns-static__panel--form puns-contact__form-panel","layout":{"type":"default"}} -->
<div class="wp-block-group puns-static__panel puns-static__panel--form puns-contact__form-panel"><!-- wp:shortcode -->
[puns_form form="contact" heading="' . esc_attr__( 'Send a message', 'puns' ) . '" intro="' . esc_attr__( 'Fill this in and we will get back to you by email.', 'puns' ) . '"]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"puns-static__section puns-contact__info","layout":{"type":"default"}} -->
<div class="wp-block-group puns-static__section puns-contact__info"><!-- wp:heading {"level":2,"className":"puns-static__heading puns-contact__info-heading"} -->
<h2 class="wp-block-heading puns-static__heading puns-contact__info-heading">' . esc_html__( 'Other ways to reach us', 'puns' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"puns-contact__info-grid"} -->
<div class="wp-block-columns puns-contact__info-grid"><!-- wp:column {"className":"puns-contact__info-item"} -->
<div class="wp-block-column puns-contact__info-item"><!-- wp:html -->
<span class="puns-static__card-icon puns-icon" aria-hidden="true">mail</span>
<!-- /wp:html -->

<!-- wp:group {"className":"puns-contact__info-copy","layout":{"type":"default"}} -->
<div class="wp-block-group puns-contact__info-copy"><!-- wp:heading {"level":3,"className":"puns-static__card-title"} -->
<h3 class="wp-block-heading puns-static__card-title">' . esc_html__( 'Email', 'puns' ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"puns-static__card-text"} -->
<p class="puns-static__card-text"><a class="puns-static__link" href="mailto:' . esc_attr( $admin_mail ) . '">' . esc_html( $admin_mail ) . '</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"puns-contact__info-item"} -->
<div class="wp-block-column puns-contact__info-item"><!-- wp:html -->
<span class="puns-static__card-icon puns-icon" aria-hidden="true">schedule</span>
<!-- /wp:html -->

<!-- wp:group {"className":"puns-contact__info-copy","layout":{"type":"default"}} -->
<div class="wp-block-group puns-contact__info-copy"><!-- wp:heading {"level":3,"className":"puns-static__card-title"} -->
<h3 class="wp-block-heading puns-static__card-title">' . esc_html__( 'Response time', 'puns' ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"puns-static__card-text"} -->
<p class="puns-static__card-text">' . esc_html__( 'We usually reply within 1–2 business days.', 'puns' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"puns-contact__info-item"} -->
<div class="wp-block-column puns-contact__info-item"><!-- wp:html -->
<span class="puns-static__card-icon puns-icon" aria-hidden="true">handshake</span>
<!-- /wp:html -->

<!-- wp:group {"className":"puns-contact__info-copy","layout":{"type":"default"}} -->
<div class="wp-block-group puns-contact__info-copy"><!-- wp:heading {"level":3,"className":"puns-static__card-title"} -->
<h3 class="wp-block-heading puns-static__card-title">' . esc_html__( 'Press &amp; partners', 'puns' ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"puns-static__card-text"} -->
<p class="puns-static__card-text">' . esc_html__( 'Mention “Partnership” in the subject so we can route it quickly.', 'puns' ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->';
}

/**
 * Ensure About / Contact pages exist with editable block content.
 */
function puns_seed_editable_static_pages() {
	$pages = array(
		'about'   => array(
			'title'    => __( 'About Us', 'puns' ),
			'slugs'    => array( 'about', 'about-us' ),
			'template' => 'page-templates/about.php',
			'content'  => 'puns_get_about_default_content',
		),
		'contact' => array(
			'title'    => __( 'Contact Us', 'puns' ),
			'slugs'    => array( 'contact', 'contact-us' ),
			'template' => 'page-templates/contact.php',
			'content'  => 'puns_get_contact_default_content',
		),
	);

	foreach ( $pages as $config ) {
		$page = null;

		foreach ( $config['slugs'] as $slug ) {
			$found = get_page_by_path( $slug );
			if ( $found instanceof WP_Post ) {
				$page = $found;
				break;
			}
		}

		if ( ! $page ) {
			$page_id = wp_insert_post(
				array(
					'post_title'   => $config['title'],
					'post_name'    => $config['slugs'][0],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_content' => call_user_func( $config['content'] ),
				),
				true
			);

			if ( ! is_wp_error( $page_id ) && $page_id ) {
				update_post_meta( $page_id, '_wp_page_template', $config['template'] );
			}

			continue;
		}

		$current_template = get_page_template_slug( $page->ID );
		if ( $config['template'] !== $current_template ) {
			update_post_meta( $page->ID, '_wp_page_template', $config['template'] );
		}

		// Only seed when the page has no real block content yet.
		$content = trim( (string) $page->post_content );
		if ( '' === $content || ! has_blocks( $content ) ) {
			wp_update_post(
				array(
					'ID'           => $page->ID,
					'post_content' => call_user_func( $config['content'] ),
				)
			);
		}
	}
}
add_action( 'after_switch_theme', 'puns_seed_editable_static_pages' );

/**
 * One-time seed for already-active theme installs.
 */
function puns_maybe_seed_editable_static_pages() {
	if ( get_option( 'puns_seeded_editable_pages' ) ) {
		return;
	}

	puns_seed_editable_static_pages();
	update_option( 'puns_seeded_editable_pages', PUNS_VERSION, false );
}
add_action( 'init', 'puns_maybe_seed_editable_static_pages', 20 );
