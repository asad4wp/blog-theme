<?php
/**
 * Theme Customizer settings.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Customizer settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function puns_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'puns_header',
		array(
			'title'       => __( 'Header', 'puns' ),
			'description' => __( 'Upload a logo image for the site header. If no logo is set, the site title is shown as text.', 'puns' ),
			'priority'    => 30,
		)
	);

	$logo_control = $wp_customize->get_control( 'custom_logo' );

	if ( $logo_control ) {
		$logo_control->section  = 'puns_header';
		$logo_control->priority = 10;
		$logo_control->label    = __( 'Header Logo', 'puns' );
	}

	$wp_customize->add_setting(
		'puns_logo_max_height',
		array(
			'default'           => 40,
			'sanitize_callback' => 'puns_sanitize_logo_max_height',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'puns_logo_max_height',
		array(
			'label'       => __( 'Logo Max Height (px)', 'puns' ),
			'description' => __( 'Controls how tall the header logo appears on the front end.', 'puns' ),
			'section'     => 'puns_header',
			'type'        => 'number',
			'priority'    => 20,
			'input_attrs' => array(
				'min'  => 24,
				'max'  => 120,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_section(
		'puns_footer',
		array(
			'title'       => __( 'Footer', 'puns' ),
			'description' => __( 'Customize the footer logo, copyright, and brand social profiles.', 'puns' ),
			'priority'    => 40,
		)
	);

	$wp_customize->add_setting(
		'puns_footer_logo',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'puns_footer_logo',
			array(
				'label'         => __( 'Footer Logo', 'puns' ),
				'description'   => __( 'Upload a logo image for the footer. If no footer logo is set, the header logo is used.', 'puns' ),
				'section'       => 'puns_footer',
				'priority'      => 10,
				'height'        => 48,
				'width'         => 200,
				'flex_height'   => true,
				'flex_width'    => true,
				'button_labels' => array(
					'select'       => __( 'Select footer logo', 'puns' ),
					'change'       => __( 'Change footer logo', 'puns' ),
					'remove'       => __( 'Remove', 'puns' ),
					'default'      => __( 'Default', 'puns' ),
					'placeholder'  => __( 'No logo selected', 'puns' ),
					'frame_title'  => __( 'Select footer logo', 'puns' ),
					'frame_button' => __( 'Choose logo', 'puns' ),
				),
			)
		)
	);

	$wp_customize->add_setting(
		'puns_footer_logo_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'puns_footer_logo_text',
		array(
			'label'       => __( 'Footer Logo Text', 'puns' ),
			'description' => __( 'Shown when no logo image is set. Leave empty to use the site title.', 'puns' ),
			'section'     => 'puns_footer',
			'type'        => 'text',
			'priority'    => 20,
		)
	);

	$wp_customize->add_setting(
		'puns_footer_logo_max_height',
		array(
			'default'           => 40,
			'sanitize_callback' => 'puns_sanitize_logo_max_height',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'puns_footer_logo_max_height',
		array(
			'label'       => __( 'Logo Max Height (px)', 'puns' ),
			'description' => __( 'Controls how tall the footer logo appears on the front end.', 'puns' ),
			'section'     => 'puns_footer',
			'type'        => 'number',
			'priority'    => 30,
			'input_attrs' => array(
				'min'  => 24,
				'max'  => 120,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'puns_footer_copyright',
		array(
			'default'           => '',
			'sanitize_callback' => 'puns_sanitize_footer_copyright',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'puns_footer_copyright',
		array(
			'label'       => __( 'Copyright Text', 'puns' ),
			'description' => __( 'Use {year} for the current year and {site_name} for the site title.', 'puns' ),
			'section'     => 'puns_footer',
			'type'        => 'textarea',
			'priority'    => 40,
		)
	);

	foreach ( puns_get_social_network_fields() as $key => $field ) {
		$wp_customize->add_setting(
			'puns_social_' . $key,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'puns_social_' . $key,
			array(
				'label'       => $field['label'],
				'description' => $field['description'],
				'section'     => 'puns_footer',
				'type'        => 'url',
				'priority'    => (int) $field['priority'],
			)
		);
	}

	$wp_customize->add_section(
		'puns_homepage',
		array(
			'title'       => __( 'Homepage', 'puns' ),
			'description' => __( 'Customize the homepage posts section.', 'puns' ),
			'priority'    => 35,
		)
	);

	$wp_customize->add_setting(
		'puns_home_posts_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		'puns_home_posts_title',
		array(
			'label'       => __( 'Posts Title', 'puns' ),
			'description' => __( 'Heading above the homepage post grid. Leave empty to use "Latest Posts".', 'puns' ),
			'section'     => 'puns_homepage',
			'type'        => 'text',
			'priority'    => 10,
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'puns_footer_brand',
			array(
				'selector'            => '.puns-footer__brand',
				'container_inclusive' => true,
				'settings'            => array(
					'puns_footer_logo',
					'puns_footer_logo_text',
					'puns_footer_copyright',
				),
				'render_callback'     => 'puns_render_footer_brand_partial',
			)
		);
	}
}
add_action( 'customize_register', 'puns_customize_register' );

/**
 * Homepage posts section title.
 *
 * @return string
 */
function puns_get_home_posts_title() {
	$title = trim( (string) get_theme_mod( 'puns_home_posts_title', '' ) );

	if ( '' !== $title ) {
		return $title;
	}

	return __( 'Latest Posts', 'puns' );
}

/**
 * Sanitize logo max height value.
 *
 * @param mixed $value Submitted value.
 * @return int
 */
function puns_sanitize_logo_max_height( $value ) {
	$value = (int) $value;

	if ( $value < 24 ) {
		return 24;
	}

	if ( $value > 120 ) {
		return 120;
	}

	return $value;
}

/**
 * Sanitize footer logo max height value.
 *
 * @param mixed $value Submitted value.
 * @return int
 */
function puns_sanitize_footer_logo_max_height( $value ) {
	return puns_sanitize_logo_max_height( $value );
}

/**
 * Sanitize footer copyright text.
 *
 * @param mixed $value Submitted value.
 * @return string
 */
function puns_sanitize_footer_copyright( $value ) {
	return sanitize_textarea_field( (string) $value );
}

/**
 * Default footer copyright template.
 *
 * @return string
 */
function puns_default_footer_copyright() {
	return sprintf(
		'© {year} {site_name}. %s',
		__( 'Dedicated to the written word.', 'puns' )
	);
}

/**
 * Replace footer copyright placeholders.
 *
 * @param string $text Copyright template.
 * @return string
 */
function puns_parse_footer_copyright( $text ) {
	$text = trim( (string) $text );

	if ( '' === $text ) {
		$text = puns_default_footer_copyright();
	}

	return str_replace(
		array(
			'{year}',
			'{site_name}',
		),
		array(
			gmdate( 'Y' ),
			get_bloginfo( 'name', 'display' ),
		),
		$text
	);
}

/**
 * Footer logo text fallback.
 *
 * @return string
 */
function puns_get_footer_logo_text() {
	$text = trim( (string) get_theme_mod( 'puns_footer_logo_text', '' ) );

	if ( '' !== $text ) {
		return $text;
	}

	return get_bloginfo( 'name', 'display' );
}

/**
 * Theme brand mark SVG URL (icon only).
 *
 * @return string
 */
function puns_get_brand_logo_url() {
	return get_template_directory_uri() . '/assets/images/logo-puns-mark.svg';
}

/**
 * Full brand wordmark URL (legacy full SVG).
 *
 * @return string
 */
function puns_get_brand_wordmark_url() {
	return get_template_directory_uri() . '/assets/images/logo-puns.svg';
}

/**
 * Render Puns brand logo (icon + wordmark).
 *
 * @param string $extra_class Extra classes for the outer mark/wordmark wrap.
 * @param string $size       Size key: header|footer.
 * @return string
 */
function puns_get_brand_logo_img( $extra_class = '', $size = 'header' ) {
	$size = ( 'footer' === $size ) ? 'footer' : 'header';
	$alt  = get_bloginfo( 'name', 'display' );

	if ( '' === trim( (string) $alt ) ) {
		$alt = __( 'Blog Theme', 'puns' );
	}

	$mark_path = get_template_directory() . '/assets/images/logo-puns-mark.svg';
	$mark_svg  = '';

	if ( is_readable( $mark_path ) ) {
		$mark_svg = (string) file_get_contents( $mark_path );
		$mark_svg = preg_replace( '/<\?xml[^>]*>/', '', $mark_svg );
		$mark_svg = preg_replace( '/\saria-hidden="true"/', '', $mark_svg, 1 );
		$mark_svg = preg_replace(
			'/<svg\b/',
			'<svg class="puns-brand-logo__mark" aria-hidden="true" focusable="false"',
			$mark_svg,
			1
		);
	}

	return sprintf(
		'<span class="puns-brand-logo puns-brand-logo--%1$s %2$s" role="img" aria-label="%3$s">%4$s<span class="puns-brand-logo__word"><span class="puns-brand-logo__text">%5$s</span></span></span>',
		esc_attr( $size ),
		esc_attr( trim( $extra_class ) ),
		esc_attr( $alt ),
		$mark_svg, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- theme SVG asset.
		esc_html( $alt )
	);
}

/**
 * Render footer logo markup (same Customizer treatment as the header logo).
 */
function puns_the_footer_logo() {
	$footer_logo = (int) get_theme_mod( 'puns_footer_logo' );

	if ( ! $footer_logo && has_custom_logo() ) {
		$footer_logo = (int) get_theme_mod( 'custom_logo' );
	}

	if ( $footer_logo ) {
		echo '<a class="puns-footer__logo puns-footer__logo--image" href="' . esc_url( home_url( '/' ) ) . '">';
		echo wp_get_attachment_image(
			$footer_logo,
			'full',
			false,
			array(
				'class' => 'puns-footer__logo-image',
				'alt'   => get_bloginfo( 'name' ),
			)
		);
		echo '</a>';

		return;
	}

	printf(
		'<a class="puns-footer__logo puns-footer__logo--svg" href="%1$s">%2$s</a>',
		esc_url( home_url( '/' ) ),
		puns_get_brand_logo_img( '', 'footer' )
	);
}

/**
 * Render footer copyright markup.
 */
function puns_the_footer_copyright() {
	printf(
		'<p class="puns-footer__copyright">%s</p>',
		esc_html( puns_parse_footer_copyright( get_theme_mod( 'puns_footer_copyright', '' ) ) )
	);
}

/**
 * Social profile fields shown in the Customizer.
 *
 * @return array<string, array{label:string,description:string,priority:int}>
 */
function puns_get_social_network_fields() {
	return array(
		'pinterest'  => array(
			'label'       => __( 'Pinterest', 'puns' ),
			'description' => __( 'Brand Pinterest profile URL. Pin each post from day one.', 'puns' ),
			'priority'    => 50,
		),
		'facebook'   => array(
			'label'       => __( 'Facebook Page', 'puns' ),
			'description' => __( 'Facebook page URL. Put the site link in the page bio.', 'puns' ),
			'priority'    => 51,
		),
		'x'          => array(
			'label'       => __( 'X (Twitter)', 'puns' ),
			'description' => __( 'X / Twitter profile URL.', 'puns' ),
			'priority'    => 52,
		),
		'instagram'  => array(
			'label'       => __( 'Instagram', 'puns' ),
			'description' => __( 'Instagram profile URL. Put the site link in the bio.', 'puns' ),
			'priority'    => 53,
		),
	);
}

/**
 * Saved social profile URLs with a value.
 *
 * @return array<string, string>
 */
function puns_get_social_profiles() {
	$profiles = array();

	foreach ( array_keys( puns_get_social_network_fields() ) as $key ) {
		$url = esc_url_raw( (string) get_theme_mod( 'puns_social_' . $key, '' ) );

		if ( '' !== $url ) {
			$profiles[ $key ] = $url;
		}
	}

	return $profiles;
}

/**
 * Inline SVG for a social network.
 *
 * @param string $network Network key.
 * @return string
 */
function puns_get_social_icon_svg( $network ) {
	$icons = array(
		'pinterest' => '<svg class="puns-footer__social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12.04 2C6.52 2 3 6.08 3 10.86c0 3.08 1.74 4.9 2.72 4.9.4 0 .64-1.12.64-1.44 0-.38-1-1.2-1-2.8 0-3.3 2.5-6.3 6.44-6.3 3.12 0 5.44 1.78 5.44 5.04 0 2.42-1.04 6.96-4.4 6.96-1.2 0-2.22-.88-1.92-1.96.36-1.54 1.06-3.2 1.06-4.32 0-1-1.7-1.86-1.7.7 0 1.46.5 3.46.5 4.9 0 1.7-.86 3.5-3.54 3.5-2.8 0-4.64-2.7-4.64-6.54C3.86 6.2 7.34 2.9 12.1 2.9c4.86 0 8.5 3.46 8.5 8.08 0 4.82-3.04 8.7-7.26 8.7-1.42 0-2.76-.74-3.22-1.6l-.88 3.34C8.4 22.5 10.16 23 12.04 23 17.56 23 21 18.2 21 11.86 21 6.08 16.86 2 12.04 2z"/></svg>',
		'facebook'  => '<svg class="puns-footer__social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M22 12.07C22 6.5 17.52 2 12 2S2 6.5 2 12.07C2 17.1 5.66 21.24 10.44 22v-7.01H7.9v-2.92h2.54V9.84c0-2.52 1.49-3.91 3.78-3.91 1.1 0 2.24.2 2.24.2v2.48h-1.26c-1.24 0-1.63.78-1.63 1.57v1.89h2.78l-.44 2.92h-2.34V22C18.34 21.24 22 17.1 22 12.07z"/></svg>',
		'x'         => '<svg class="puns-footer__social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M18.24 3H21l-6.51 7.44L22 21h-6.17l-4.82-6.3L5.4 21H2.62l6.97-7.97L2 3h6.32l4.35 5.75L18.24 3zm-2.16 16.2h1.7L7.84 4.7H6.01l10.07 14.5z"/></svg>',
		'instagram' => '<svg class="puns-footer__social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M7.75 3h8.5A4.75 4.75 0 0 1 21 7.75v8.5A4.75 4.75 0 0 1 16.25 21h-8.5A4.75 4.75 0 0 1 3 16.25v-8.5A4.75 4.75 0 0 1 7.75 3zm0 1.5A3.25 3.25 0 0 0 4.5 7.75v8.5A3.25 3.25 0 0 0 7.75 19.5h8.5a3.25 3.25 0 0 0 3.25-3.25v-8.5A3.25 3.25 0 0 0 16.25 4.5h-8.5zM12 8.25A3.75 3.75 0 1 1 8.25 12 3.75 3.75 0 0 1 12 8.25zm0 1.5A2.25 2.25 0 1 0 14.25 12 2.25 2.25 0 0 0 12 9.75zM17.13 7.12a.88.88 0 1 1-.88.88.88.88 0 0 1 .88-.88z"/></svg>',
	);

	return isset( $icons[ $network ] ) ? $icons[ $network ] : '';
}

/**
 * Render footer social profile links.
 */
function puns_the_footer_social() {
	$profiles = puns_get_social_profiles();
	$labels   = array(
		'pinterest' => __( 'Pinterest', 'puns' ),
		'facebook'  => __( 'Facebook', 'puns' ),
		'x'         => __( 'X', 'puns' ),
		'instagram' => __( 'Instagram', 'puns' ),
	);

	if ( empty( $profiles ) ) {
		return;
	}

	echo '<nav class="puns-footer__social" aria-label="' . esc_attr__( 'Social profiles', 'puns' ) . '">';

	foreach ( $profiles as $network => $url ) {
		$label = isset( $labels[ $network ] ) ? $labels[ $network ] : $network;
		$icon  = puns_get_social_icon_svg( $network );

		if ( '' === $icon ) {
			continue;
		}

		printf(
			'<a class="puns-footer__social-link puns-footer__social-link--%1$s" href="%2$s" target="_blank" rel="noopener noreferrer me" aria-label="%3$s">%4$s</a>',
			esc_attr( $network ),
			esc_url( $url ),
			esc_attr( $label ),
			$icon // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG.
		);
	}

	echo '</nav>';
}

/**
 * Selective refresh callback for the footer brand block.
 *
 * @return string
 */
function puns_render_footer_brand_partial() {
	ob_start();
	?>
	<div class="puns-footer__brand">
		<?php puns_the_footer_logo(); ?>
		<?php puns_the_footer_copyright(); ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Add theme classes to the custom logo markup.
 *
 * @param string $html Custom logo HTML.
 * @return string
 */
function puns_custom_logo_html( $html ) {
	if ( empty( $html ) ) {
		return $html;
	}

	if ( false !== strpos( $html, 'puns-header__logo' ) ) {
		return $html;
	}

	if ( preg_match( '/class="([^"]*)"/', $html ) ) {
		$html = preg_replace(
			'/class="([^"]*)"/',
			'class="puns-header__logo $1"',
			$html,
			1
		);
	} else {
		$html = preg_replace( '/<a\s/', '<a class="puns-header__logo" ', $html, 1 );
	}

	return $html;
}
add_filter( 'get_custom_logo', 'puns_custom_logo_html' );

/**
 * Customizer live preview script.
 */
function puns_customize_preview_js() {
	wp_enqueue_script(
		'puns-customizer',
		get_template_directory_uri() . '/assets/js/customizer.js',
		array( 'customize-preview' ),
		PUNS_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'puns_customize_preview_js' );

/**
 * Output saved header/footer logo max-height on the front end.
 */
function puns_logo_inline_styles() {
	$header_height = puns_sanitize_logo_max_height( get_theme_mod( 'puns_logo_max_height', 40 ) );
	$footer_height = puns_sanitize_logo_max_height( get_theme_mod( 'puns_footer_logo_max_height', 40 ) );

	$css = sprintf(
		'.puns-header__logo img,.puns-header .custom-logo{max-height:%1$dpx;width:auto;height:auto;}' .
		'.puns-footer__logo img,.puns-footer__logo-image{max-height:%2$dpx;width:auto;height:auto;}' .
		'.puns-footer .puns-brand-logo__mark{width:%2$dpx;height:%2$dpx;}',
		$header_height,
		$footer_height
	);

	wp_add_inline_style( 'puns', $css );
}
add_action( 'wp_enqueue_scripts', 'puns_logo_inline_styles', 20 );

/**
 * Render the header logo (Puns brand).
 */
function puns_the_header_logo() {

    if ( has_custom_logo() ) {
        the_custom_logo();
        return;
    }

    printf(
        '<a class="puns-header__logo puns-header__logo--svg" href="%1$s">%2$s</a>',
        esc_url( home_url( '/' ) ),
        puns_get_brand_logo_img( '', 'header' )
    );
}

/**
 * Theme favicon URL.
 *
 * @return string
 */
function puns_get_favicon_url() {
	return get_template_directory_uri() . '/assets/images/favicon.svg';
}

/**
 * Output favicon when no Customizer Site Icon is set.
 */
function puns_output_favicon() {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}

	printf(
		'<link rel="icon" href="%1$s" type="image/svg+xml">' . "\n",
		esc_url( puns_get_favicon_url() )
	);

	// PWA head tags already output apple-touch-icon when enabled.
	if ( function_exists( 'puns_pwa_is_enabled' ) && puns_pwa_is_enabled() ) {
		return;
	}

	printf(
		'<link rel="apple-touch-icon" href="%1$s">' . "\n",
		esc_url( get_template_directory_uri() . '/assets/images/pwa-icon.svg' )
	);
}
add_action( 'wp_head', 'puns_output_favicon', 2 );
add_action( 'admin_head', 'puns_output_favicon', 2 );
add_action( 'login_head', 'puns_output_favicon', 2 );
