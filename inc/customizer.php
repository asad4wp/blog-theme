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
			'description' => __( 'Customize the footer logo and copyright text.', 'puns' ),
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
