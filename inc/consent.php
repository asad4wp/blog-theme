<?php
/**
 * Google CMP and Consent Mode integration for AdSense.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether consent management is enabled.
 *
 * @return bool
 */
function puns_consent_is_enabled() {
	return (bool) get_theme_mod( 'puns_consent_enabled', false );
}

/**
 * CMP provider mode.
 *
 * @return string google|alternative
 */
function puns_consent_cmp_mode() {
	$mode = (string) get_theme_mod( 'puns_consent_cmp_mode', 'google' );

	return in_array( $mode, array( 'google', 'alternative' ), true ) ? $mode : 'google';
}

/**
 * Google CMP message layout configured in AdSense Privacy & messaging.
 *
 * @return string two|three
 */
function puns_consent_google_layout() {
	$layout = (string) get_theme_mod( 'puns_consent_google_layout', 'two' );

	return in_array( $layout, array( 'two', 'three' ), true ) ? $layout : 'two';
}

/**
 * Sanitize and return the AdSense publisher ID (ca-pub-XXXXXXXX).
 *
 * @param string $value Raw value.
 * @return string
 */
function puns_consent_sanitize_publisher_id( $value ) {
	$value = strtolower( trim( (string) $value ) );

	if ( '' === $value ) {
		return '';
	}

	if ( preg_match( '/^ca-pub-\d+$/', $value ) ) {
		return $value;
	}

	if ( preg_match( '/^pub-\d+$/', $value ) ) {
		return 'ca-' . $value;
	}

	if ( preg_match( '/^\d+$/', $value ) ) {
		return 'ca-pub-' . $value;
	}

	return '';
}

/**
 * Get the configured AdSense publisher ID.
 *
 * @return string
 */
function puns_consent_get_publisher_id() {
	return puns_consent_sanitize_publisher_id( get_theme_mod( 'puns_consent_publisher_id', '' ) );
}

/**
 * Numeric publisher ID for Funding Choices URLs.
 *
 * @return string
 */
function puns_consent_get_publisher_number() {
	$publisher_id = puns_consent_get_publisher_id();

	if ( '' === $publisher_id ) {
		return '';
	}

	if ( preg_match( '/^ca-pub-(\d+)$/', $publisher_id, $matches ) ) {
		return $matches[1];
	}

	return '';
}

/**
 * Whether AdSense auto ads script should load.
 *
 * @return bool
 */
function puns_consent_load_adsense() {
	return puns_consent_is_enabled() && (bool) get_theme_mod( 'puns_consent_load_adsense', true );
}

/**
 * Sanitize alternative CMP script snippet from the Customizer.
 *
 * @param mixed $value Submitted value.
 * @return string
 */
function puns_consent_sanitize_cmp_script( $value ) {
	$value = trim( (string) $value );

	if ( '' === $value ) {
		return '';
	}

	// Customizer is admin-only; allow script tags from trusted CMP vendors.
	$allowed = array(
		'script' => array(
			'async'   => true,
			'defer'   => true,
			'src'     => true,
			'type'    => true,
			'id'      => true,
			'charset' => true,
		),
	);

	return wp_kses( $value, $allowed );
}

/**
 * Output Consent Mode v2 defaults before any Google tags.
 */
function puns_consent_output_defaults() {
	if ( ! puns_consent_is_enabled() ) {
		return;
	}

	?>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('consent', 'default', {
			'ad_storage': 'denied',
			'ad_user_data': 'denied',
			'ad_personalization': 'denied',
			'analytics_storage': 'denied',
			'wait_for_update': 500
		});
	</script>
	<?php
}
add_action( 'wp_head', 'puns_consent_output_defaults', 1 );

/**
 * Pause AdSense requests until consent is resolved.
 */
function puns_consent_pause_ad_requests() {
	if ( ! puns_consent_is_enabled() || ! puns_consent_load_adsense() ) {
		return;
	}

	?>
	<script>
		(window.adsbygoogle = window.adsbygoogle || []).pauseAdRequests = 1;
	</script>
	<?php
}
add_action( 'wp_head', 'puns_consent_pause_ad_requests', 2 );

/**
 * Output Google Funding Choices CMP script.
 */
function puns_consent_output_google_cmp() {
	if ( ! puns_consent_is_enabled() || 'google' !== puns_consent_cmp_mode() ) {
		return;
	}

	$publisher_number = puns_consent_get_publisher_number();

	if ( '' === $publisher_number ) {
		return;
	}

	$url = sprintf(
		'https://fundingchoicesmessages.google.com/i/pub-%s?ers=1',
		rawurlencode( $publisher_number )
	);
	?>
	<script async src="<?php echo esc_url( $url ); ?>" nonce="<?php echo esc_attr( puns_consent_get_script_nonce() ); ?>"></script>
	<?php
}
add_action( 'wp_head', 'puns_consent_output_google_cmp', 3 );

/**
 * Output alternative CMP scripts configured in the Customizer.
 */
function puns_consent_output_alternative_cmp() {
	if ( ! puns_consent_is_enabled() || 'alternative' !== puns_consent_cmp_mode() ) {
		return;
	}

	$script = puns_consent_sanitize_cmp_script( get_theme_mod( 'puns_consent_alternative_script', '' ) );

	if ( '' === $script ) {
		return;
	}

	echo $script; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- sanitized CMP snippet.
}
add_action( 'wp_head', 'puns_consent_output_alternative_cmp', 3 );

/**
 * Output the AdSense loader script.
 */
function puns_consent_output_adsense_script() {
	if ( ! puns_consent_load_adsense() ) {
		return;
	}

	$publisher_id = puns_consent_get_publisher_id();

	if ( '' === $publisher_id ) {
		return;
	}

	$url = add_query_arg(
		'client',
		rawurlencode( $publisher_id ),
		'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'
	);
	?>
	<script async src="<?php echo esc_url( $url ); ?>" crossorigin="anonymous" nonce="<?php echo esc_attr( puns_consent_get_script_nonce() ); ?>"></script>
	<?php
}
add_action( 'wp_head', 'puns_consent_output_adsense_script', 4 );

/**
 * CSP nonce attribute helper (empty when not used).
 *
 * @return string
 */
function puns_consent_get_script_nonce() {
	/**
	 * Filter the nonce value used on consent-related script tags.
	 *
	 * @param string $nonce Nonce string.
	 */
	return (string) apply_filters( 'puns_consent_script_nonce', '' );
}

/**
 * Render a footer link to reopen consent preferences.
 */
function puns_the_consent_settings_link() {
	if ( ! puns_consent_is_enabled() ) {
		return;
	}

	printf(
		'<a class="puns-footer__link puns-consent-settings" href="#" data-puns-consent-open>%s</a>',
		esc_html__( 'Cookie settings', 'puns' )
	);
}

/**
 * Inline script to reopen Google CMP or call the TCF API.
 */
function puns_consent_footer_script() {
	if ( ! puns_consent_is_enabled() ) {
		return;
	}

	?>
	<script>
		(function () {
			function openConsentSettings(event) {
				if (event) {
					event.preventDefault();
				}

				if (typeof window.googlefc !== 'undefined' && typeof window.googlefc.showRevocationMessage === 'function') {
					window.googlefc.showRevocationMessage();
					return;
				}

				if (typeof window.__tcfapi === 'function') {
					window.__tcfapi('displayConsentUi', 2, function () {});
				}
			}

			document.addEventListener('click', function (event) {
				if (event.target.closest('[data-puns-consent-open]')) {
					openConsentSettings(event);
				}
			});
		})();
	</script>
	<?php
}
add_action( 'wp_footer', 'puns_consent_footer_script', 20 );

/**
 * Register consent Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function puns_consent_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'puns_consent',
		array(
			'title'       => __( 'Consent & AdSense', 'puns' ),
			'description' => __( 'Configure Google-certified consent management for visitors in the EEA, UK, and Switzerland. After enabling Google CMP, finish setup in AdSense under Privacy & messaging and publish your consent message.', 'puns' ),
			'priority'    => 160,
		)
	);

	$wp_customize->add_setting(
		'puns_consent_enabled',
		array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'puns_consent_enabled',
		array(
			'label'       => __( 'Enable consent management', 'puns' ),
			'description' => __( 'Loads Consent Mode defaults and your CMP before AdSense tags.', 'puns' ),
			'section'     => 'puns_consent',
			'type'        => 'checkbox',
			'priority'    => 10,
		)
	);

	$wp_customize->add_setting(
		'puns_consent_publisher_id',
		array(
			'default'           => '',
			'sanitize_callback' => 'puns_consent_sanitize_publisher_id',
		)
	);

	$wp_customize->add_control(
		'puns_consent_publisher_id',
		array(
			'label'       => __( 'AdSense Publisher ID', 'puns' ),
			'description' => __( 'Example: ca-pub-1234567890123456', 'puns' ),
			'section'     => 'puns_consent',
			'type'        => 'text',
			'priority'    => 20,
		)
	);

	$wp_customize->add_setting(
		'puns_consent_cmp_mode',
		array(
			'default'           => 'google',
			'sanitize_callback' => 'puns_consent_sanitize_cmp_mode',
		)
	);

	$wp_customize->add_control(
		'puns_consent_cmp_mode',
		array(
			'label'   => __( 'Consent platform', 'puns' ),
			'section' => 'puns_consent',
			'type'    => 'radio',
			'choices' => array(
				'google'      => __( "Use Google's certified CMP", 'puns' ),
				'alternative' => __( 'Use an alternative Google-certified CMP', 'puns' ),
			),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'puns_consent_google_layout',
		array(
			'default'           => 'two',
			'sanitize_callback' => 'puns_consent_sanitize_google_layout',
		)
	);

	$wp_customize->add_control(
		'puns_consent_google_layout',
		array(
			'label'       => __( 'Google CMP message layout', 'puns' ),
			'description' => __( 'Choose the same layout you publish in AdSense → Privacy & messaging. The banner design is controlled there; this setting is for your reference only.', 'puns' ),
			'section'     => 'puns_consent',
			'type'        => 'radio',
			'choices'     => array(
				'two'   => __( 'Two choices (Consent and Manage options)', 'puns' ),
				'three' => __( 'Three choices (Consent, Do not consent, and Manage options)', 'puns' ),
			),
			'priority'    => 40,
		)
	);

	$wp_customize->add_setting(
		'puns_consent_alternative_script',
		array(
			'default'           => '',
			'sanitize_callback' => 'puns_consent_sanitize_cmp_script',
		)
	);

	$wp_customize->add_control(
		'puns_consent_alternative_script',
		array(
			'label'       => __( 'Alternative CMP script', 'puns' ),
			'description' => __( 'Paste the script tag(s) from your Google-certified CMP provider. Only needed when using an alternative CMP.', 'puns' ),
			'section'     => 'puns_consent',
			'type'        => 'textarea',
			'priority'    => 50,
		)
	);

	$wp_customize->add_setting(
		'puns_consent_load_adsense',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		)
	);

	$wp_customize->add_control(
		'puns_consent_load_adsense',
		array(
			'label'       => __( 'Load AdSense script', 'puns' ),
			'description' => __( 'Disable if you already load AdSense through a plugin or tag manager.', 'puns' ),
			'section'     => 'puns_consent',
			'type'        => 'checkbox',
			'priority'    => 60,
		)
	);
}
add_action( 'customize_register', 'puns_consent_customize_register', 20 );

/**
 * Sanitize CMP mode value.
 *
 * @param mixed $value Submitted value.
 * @return string
 */
function puns_consent_sanitize_cmp_mode( $value ) {
	$value = (string) $value;

	return in_array( $value, array( 'google', 'alternative' ), true ) ? $value : 'google';
}

/**
 * Sanitize Google CMP layout value.
 *
 * @param mixed $value Submitted value.
 * @return string
 */
function puns_consent_sanitize_google_layout( $value ) {
	$value = (string) $value;

	return in_array( $value, array( 'two', 'three' ), true ) ? $value : 'two';
}
