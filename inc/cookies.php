<?php
/**
 * Theme cookie consent banner.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the theme cookie banner should render.
 *
 * Skips when Google/alternative CMP is already handling consent.
 *
 * @return bool
 */
function puns_cookie_banner_is_enabled() {
	if ( function_exists( 'puns_consent_is_enabled' ) && puns_consent_is_enabled() ) {
		return false;
	}

	return (bool) get_theme_mod( 'puns_cookie_banner_enabled', true );
}

/**
 * Cookie banner message text.
 *
 * @return string
 */
function puns_cookie_banner_message() {
	$message = trim( (string) get_theme_mod( 'puns_cookie_banner_message', '' ) );

	if ( '' !== $message ) {
		return $message;
	}

	return __( 'We use cookies to improve your experience and understand how the site is used. By continuing, you agree to our use of cookies.', 'puns' );
}

/**
 * Privacy policy URL for the banner link.
 *
 * @return string
 */
function puns_cookie_banner_privacy_url() {
	$url = trim( (string) get_theme_mod( 'puns_cookie_banner_privacy_url', '' ) );

	if ( '' !== $url ) {
		return esc_url_raw( $url );
	}

	$privacy_id = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( $privacy_id ) {
		$permalink = get_permalink( $privacy_id );
		if ( $permalink ) {
			return $permalink;
		}
	}

	return home_url( '/privacy-policy/' );
}

/**
 * Enqueue cookie banner script.
 */
function puns_cookie_banner_assets() {
	if ( ! puns_cookie_banner_is_enabled() || is_admin() ) {
		return;
	}

	wp_enqueue_script(
		'puns-cookies',
		get_template_directory_uri() . '/assets/js/cookies.js',
		array(),
		PUNS_VERSION,
		true
	);

	wp_localize_script(
		'puns-cookies',
		'punsCookies',
		array(
			'storageKey' => 'puns_cookie_consent',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'puns_cookie_banner_assets', 25 );

/**
 * Render the cookie banner markup.
 */
function puns_cookie_banner_render() {
	if ( ! puns_cookie_banner_is_enabled() ) {
		return;
	}

	$privacy_url = puns_cookie_banner_privacy_url();
	?>
	<div
		id="puns-cookie-banner"
		class="puns-cookie-banner"
		role="dialog"
		aria-live="polite"
		aria-label="<?php esc_attr_e( 'Cookie consent', 'puns' ); ?>"
		hidden
	>
		<div class="puns-cookie-banner__inner puns-container">
			<div class="puns-cookie-banner__copy">
				<p class="puns-cookie-banner__text"><?php echo esc_html( puns_cookie_banner_message() ); ?></p>
				<?php if ( $privacy_url ) : ?>
					<a class="puns-cookie-banner__link" href="<?php echo esc_url( $privacy_url ); ?>">
						<?php esc_html_e( 'Privacy Policy', 'puns' ); ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="puns-cookie-banner__actions">
				<button type="button" class="puns-btn puns-btn--outline puns-cookie-banner__decline" data-puns-cookie="decline">
					<?php esc_html_e( 'Decline', 'puns' ); ?>
				</button>
				<button type="button" class="puns-btn puns-btn--solid puns-cookie-banner__accept" data-puns-cookie="accept">
					<?php esc_html_e( 'Accept', 'puns' ); ?>
				</button>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'puns_cookie_banner_render', 5 );

/**
 * Footer link to reopen the cookie banner.
 */
function puns_the_cookie_settings_link() {
	if ( ! puns_cookie_banner_is_enabled() ) {
		return;
	}

	printf(
		'<a class="puns-footer__link puns-cookie-settings" href="#" data-puns-cookie-open>%s</a>',
		esc_html__( 'Cookie settings', 'puns' )
	);
}

/**
 * Customizer settings for the cookie banner.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function puns_cookie_banner_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'puns_cookies',
		array(
			'title'       => __( 'Cookie Banner', 'puns' ),
			'description' => __( 'Simple cookie notice for visitors. Hidden automatically when Consent & AdSense CMP is enabled.', 'puns' ),
			'priority'    => 155,
		)
	);

	$wp_customize->add_setting(
		'puns_cookie_banner_enabled',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'puns_cookie_banner_enabled',
		array(
			'label'   => __( 'Show cookie banner', 'puns' ),
			'section' => 'puns_cookies',
			'type'    => 'checkbox',
			'priority'=> 10,
		)
	);

	$wp_customize->add_setting(
		'puns_cookie_banner_message',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'puns_cookie_banner_message',
		array(
			'label'       => __( 'Banner message', 'puns' ),
			'description' => __( 'Leave empty to use the default message.', 'puns' ),
			'section'     => 'puns_cookies',
			'type'        => 'textarea',
			'priority'    => 20,
		)
	);

	$wp_customize->add_setting(
		'puns_cookie_banner_privacy_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'puns_cookie_banner_privacy_url',
		array(
			'label'       => __( 'Privacy Policy URL', 'puns' ),
			'description' => __( 'Leave empty to use the WordPress Privacy Policy page, or /privacy-policy/.', 'puns' ),
			'section'     => 'puns_cookies',
			'type'        => 'url',
			'priority'    => 30,
		)
	);
}
add_action( 'customize_register', 'puns_cookie_banner_customize_register', 25 );
