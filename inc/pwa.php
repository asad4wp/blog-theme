<?php
/**
 * Progressive Web App support.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether PWA features are enabled.
 *
 * @return bool
 */
function puns_pwa_is_enabled() {
	return (bool) get_theme_mod( 'puns_pwa_enabled', true );
}

/**
 * Register PWA query variable.
 *
 * @param string[] $vars Query vars.
 * @return string[]
 */
function puns_pwa_query_vars( $vars ) {
	$vars[] = 'puns_pwa';

	return $vars;
}
add_filter( 'query_vars', 'puns_pwa_query_vars' );

/**
 * Manifest endpoint URL.
 *
 * @return string
 */
function puns_pwa_manifest_url() {
	return add_query_arg( 'puns_pwa', 'manifest', home_url( '/' ) );
}

/**
 * Service worker endpoint URL.
 *
 * @return string
 */
function puns_pwa_sw_url() {
	return add_query_arg( 'puns_pwa', 'sw', home_url( '/' ) );
}

/**
 * PWA theme colors.
 *
 * @return array{background:string,theme:string}
 */
function puns_pwa_colors() {
	return array(
		'background' => '#F8FAFF',
		'theme'      => '#3858E9',
	);
}

/**
 * Build manifest icon list.
 *
 * @return array<int, array<string, string>>
 */
function puns_pwa_get_icons() {
	$icons  = array();
	$sizes  = array( 192, 512 );
	$fallback = get_template_directory_uri() . '/assets/images/pwa-icon.svg';

	foreach ( $sizes as $size ) {
		$url = get_site_icon_url( $size );

		if ( $url ) {
			$icons[] = array(
				'src'     => $url,
				'sizes'   => $size . 'x' . $size,
				'type'    => 'image/png',
				'purpose' => 'any maskable',
			);
		}
	}

	if ( empty( $icons ) ) {
		$icons[] = array(
			'src'     => $fallback,
			'sizes'   => '512x512',
			'type'    => 'image/svg+xml',
			'purpose' => 'any maskable',
		);
	}

	return $icons;
}

/**
 * URLs to precache in the service worker.
 *
 * @return string[]
 */
function puns_pwa_precache_urls() {
	$urls = array(
		home_url( '/' ),
		get_template_directory_uri() . '/assets/css/style.css',
		get_template_directory_uri() . '/assets/js/main.js',
		get_template_directory_uri() . '/assets/images/pwa-icon.svg',
	);

	if ( has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );

		if ( $logo_id ) {
			$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );

			if ( $logo_url ) {
				$urls[] = $logo_url;
			}
		}
	}

	return array_values( array_unique( array_filter( $urls ) ) );
}

/**
 * App display name for manifest.
 *
 * @return string
 */
function puns_pwa_app_name() {
	$name = trim( (string) get_theme_mod( 'puns_pwa_app_name', '' ) );

	if ( '' !== $name ) {
		return $name;
	}

	return get_bloginfo( 'name', 'display' );
}

/**
 * Short app name for manifest.
 *
 * @return string
 */
function puns_pwa_app_short_name() {
	$short = trim( (string) get_theme_mod( 'puns_pwa_app_short_name', '' ) );

	if ( '' !== $short ) {
		return $short;
	}

	$name = puns_pwa_app_name();

	return mb_strlen( $name ) > 12 ? mb_substr( $name, 0, 12 ) : $name;
}

/**
 * Build web app manifest data.
 *
 * @return array<string, mixed>
 */
function puns_pwa_manifest_data() {
	$colors = puns_pwa_colors();

	return array(
		'name'             => puns_pwa_app_name(),
		'short_name'       => puns_pwa_app_short_name(),
		'description'      => get_bloginfo( 'description', 'display' ),
		'start_url'        => home_url( '/' ),
		'scope'            => home_url( '/' ),
		'display'          => 'standalone',
		'orientation'      => 'portrait-primary',
		'background_color' => $colors['background'],
		'theme_color'      => $colors['theme'],
		'lang'             => str_replace( '_', '-', get_bloginfo( 'language' ) ),
		'icons'            => puns_pwa_get_icons(),
	);
}

/**
 * Serve manifest or service worker responses.
 */
function puns_pwa_template_redirect() {
	if ( ! puns_pwa_is_enabled() ) {
		return;
	}

	$type = get_query_var( 'puns_pwa' );

	if ( 'manifest' === $type ) {
		nocache_headers();
		header( 'Content-Type: application/manifest+json; charset=utf-8' );

		echo wp_json_encode(
			puns_pwa_manifest_data(),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		);
		exit;
	}

	if ( 'sw' === $type ) {
		$template = get_template_directory() . '/assets/js/service-worker.tpl.js';

		if ( ! is_readable( $template ) ) {
			status_header( 404 );
			exit;
		}

		$contents = file_get_contents( $template ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		$contents = str_replace( '__CACHE_VERSION__', PUNS_VERSION, $contents );
		$contents = str_replace( '__OFFLINE_URL__', esc_url_raw( home_url( '/' ) ), $contents );
		$contents = str_replace(
			'__PRECACHE_URLS__',
			wp_json_encode( puns_pwa_precache_urls(), JSON_UNESCAPED_SLASHES ),
			$contents
		);

		nocache_headers();
		header( 'Content-Type: application/javascript; charset=utf-8' );
		header( 'Service-Worker-Allowed: /' );

		echo $contents; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}
}
add_action( 'template_redirect', 'puns_pwa_template_redirect' );

/**
 * Output PWA meta tags and manifest link.
 */
function puns_pwa_head_tags() {
	if ( ! puns_pwa_is_enabled() ) {
		return;
	}

	$colors = puns_pwa_colors();

	printf(
		'<link rel="manifest" href="%1$s">' . "\n",
		esc_url( puns_pwa_manifest_url() )
	);

	printf(
		'<meta name="theme-color" content="%1$s">' . "\n",
		esc_attr( $colors['theme'] )
	);

	printf(
		'<meta name="mobile-web-app-capable" content="yes">' . "\n"
	);

	printf(
		'<meta name="apple-mobile-web-app-capable" content="yes">' . "\n"
	);

	printf(
		'<meta name="apple-mobile-web-app-status-bar-style" content="default">' . "\n"
	);

	printf(
		'<meta name="apple-mobile-web-app-title" content="%1$s">' . "\n",
		esc_attr( puns_pwa_app_short_name() )
	);

	$icon_192 = get_site_icon_url( 192 );
	$icon_url = $icon_192 ? $icon_192 : get_template_directory_uri() . '/assets/images/pwa-icon.svg';

	printf(
		'<link rel="apple-touch-icon" href="%1$s">' . "\n",
		esc_url( $icon_url )
	);
}
add_action( 'wp_head', 'puns_pwa_head_tags', 5 );

/**
 * Register service worker script.
 */
function puns_pwa_enqueue_scripts() {
	if ( ! puns_pwa_is_enabled() ) {
		return;
	}

	wp_enqueue_script(
		'puns-pwa',
		get_template_directory_uri() . '/assets/js/pwa.js',
		array(),
		PUNS_VERSION,
		true
	);

	wp_localize_script(
		'puns-pwa',
		'punsBlogPwa',
		array(
			'swUrl' => puns_pwa_sw_url(),
			'scope' => trailingslashit( home_url( '/' ) ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'puns_pwa_enqueue_scripts', 20 );

/**
 * Register PWA Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function puns_pwa_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'puns_pwa',
		array(
			'title'       => __( 'PWA', 'puns' ),
			'description' => __( 'Install this site as an app on phones and desktops. Set a Site Icon under Site Identity for best results.', 'puns' ),
			'priority'    => 50,
		)
	);

	$wp_customize->add_setting(
		'puns_pwa_enabled',
		array(
			'default'           => true,
			'sanitize_callback' => 'puns_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'puns_pwa_enabled',
		array(
			'label'   => __( 'Enable Progressive Web App', 'puns' ),
			'section' => 'puns_pwa',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'puns_pwa_app_name',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'puns_pwa_app_name',
		array(
			'label'       => __( 'App Name', 'puns' ),
			'description' => __( 'Leave empty to use the site title.', 'puns' ),
			'section'     => 'puns_pwa',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'puns_pwa_app_short_name',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'puns_pwa_app_short_name',
		array(
			'label'       => __( 'Short App Name', 'puns' ),
			'description' => __( 'Shown on home screen. Max ~12 characters recommended.', 'puns' ),
			'section'     => 'puns_pwa',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'puns_pwa_customize_register', 20 );

/**
 * Sanitize checkbox values.
 *
 * @param mixed $value Submitted value.
 * @return bool
 */
function puns_sanitize_checkbox( $value ) {
	return (bool) $value;
}
