<?php
/**
 * Static marketing pages: About, Write with Us, Contact.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Known static page template files (relative to theme).
 *
 * @return array<string, string> Map of template file => body modifier class.
 */
function puns_static_page_templates() {
	return array(
		'page-templates/about.php'        => 'puns-page-about',
		'page-templates/write-with-us.php' => 'puns-page-write',
		'page-templates/contact.php'      => 'puns-page-contact',
	);
}

/**
 * Body class modifier for the current static page template, if any.
 *
 * @return string
 */
function puns_get_static_page_modifier() {
	if ( ! is_page() ) {
		return '';
	}

	$slug_map = array(
		'about'         => 'puns-page-about',
		'about-us'      => 'puns-page-about',
		'write-with-us' => 'puns-page-write',
		'write'         => 'puns-page-write',
		'contact'       => 'puns-page-contact',
		'contact-us'    => 'puns-page-contact',
	);

	$slug = get_post_field( 'post_name', get_queried_object_id() );
	if ( $slug && isset( $slug_map[ $slug ] ) ) {
		return $slug_map[ $slug ];
	}

	$template = get_page_template_slug( get_queried_object_id() );
	$map      = puns_static_page_templates();

	return ( $template && isset( $map[ $template ] ) ) ? $map[ $template ] : '';
}

/**
 * Whether the current view is one of the designed static pages.
 *
 * @return bool
 */
function puns_is_static_designed_page() {
	return '' !== puns_get_static_page_modifier();
}

/**
 * Add article + static modifiers so pages inherit design tokens and layouts.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function puns_static_page_body_classes( $classes ) {
	if ( ! is_page() ) {
		return $classes;
	}

	$classes[] = 'puns-page-article';
	$classes[] = 'puns-page-static';

	$modifier = puns_get_static_page_modifier();
	if ( $modifier ) {
		$classes[] = $modifier;
	}

	return $classes;
}
add_filter( 'body_class', 'puns_static_page_body_classes', 15 );
