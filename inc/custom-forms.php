<?php
/**
 * Custom AJAX forms: Contact Us & Write with Us.
 *
 * CPT storage, admin/user emails, reCAPTCHA, IP rate limiting.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Option key for all custom form settings.
 */
define( 'PUNS_FORMS_OPTION', 'puns_custom_forms_settings' );

/**
 * Default settings (merged with saved option).
 *
 * @return array<string, mixed>
 */
function puns_forms_default_settings() {
	return array(
		'admin_email'            => get_option( 'admin_email' ),
		'recaptcha_enabled'      => true,
		'recaptcha_site_key'     => '',
		'recaptcha_secret_key'   => '',
		'recaptcha_version'      => 'v2', // v2|v3
		'recaptcha_v3_threshold' => 0.5,
		'rate_limit_minute'      => 3,
		'rate_limit_month'       => 20,
		'contact'                => array(
			'admin_subject'         => __( '[{site}] New contact message from {name}', 'puns' ),
			'autoresponder_subject' => __( 'Thanks for contacting {site}', 'puns' ),
			'autoresponder_body'    => __( "Hi {name},\n\nThank you for reaching out. We received your message and will get back to you within 1–2 business days.\n\n— The {site} team", 'puns' ),
			'notification_emails'   => '',
			'fields'                => array(
				'name'    => array(
					'label'    => __( 'Name', 'puns' ),
					'type'     => 'text',
					'required' => true,
					'enabled'  => true,
				),
				'email'   => array(
					'label'    => __( 'Email', 'puns' ),
					'type'     => 'email',
					'required' => true,
					'enabled'  => true,
				),
				'subject' => array(
					'label'    => __( 'Subject', 'puns' ),
					'type'     => 'text',
					'required' => true,
					'enabled'  => true,
				),
				'phone'   => array(
					'label'    => __( 'Phone (optional)', 'puns' ),
					'type'     => 'tel',
					'required' => false,
					'enabled'  => true,
				),
				'message' => array(
					'label'    => __( 'Message', 'puns' ),
					'type'     => 'textarea',
					'required' => true,
					'enabled'  => true,
				),
			),
		),
		'write'                  => array(
			'admin_subject'         => __( '[{site}] New writer pitch from {name}', 'puns' ),
			'autoresponder_subject' => __( 'Thanks for your pitch — {site}', 'puns' ),
			'autoresponder_body'    => __( "Hi {name},\n\nThanks for sharing your idea with us. Our editors will review your pitch and reply if it is a fit.\n\n— The {site} team", 'puns' ),
			'notification_emails'   => '',
			'fields'                => array(
				'name'          => array(
					'label'    => __( 'Name', 'puns' ),
					'type'     => 'text',
					'required' => true,
					'enabled'  => true,
				),
				'email'         => array(
					'label'    => __( 'Email', 'puns' ),
					'type'     => 'email',
					'required' => true,
					'enabled'  => true,
				),
				'pitch'         => array(
					'label'    => __( 'Your pitch', 'puns' ),
					'type'     => 'textarea',
					'required' => true,
					'enabled'  => true,
				),
				'portfolio_url' => array(
					'label'    => __( 'Portfolio or writing samples (URL)', 'puns' ),
					'type'     => 'url',
					'required' => false,
					'enabled'  => true,
				),
			),
		),
	);
}

/**
 * Get merged form settings.
 *
 * @return array<string, mixed>
 */
function puns_forms_get_settings() {
	$saved = get_option( PUNS_FORMS_OPTION, array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$settings = wp_parse_args( $saved, puns_forms_default_settings() );

	if ( defined( 'PUNS_RECAPTCHA_SITE_KEY' ) ) {
		$settings['recaptcha_site_key'] = PUNS_RECAPTCHA_SITE_KEY;
	}
	if ( defined( 'PUNS_RECAPTCHA_SECRET_KEY' ) ) {
		$settings['recaptcha_secret_key'] = PUNS_RECAPTCHA_SECRET_KEY;
	}
	if ( defined( 'PUNS_FORMS_ADMIN_EMAIL' ) ) {
		$settings['admin_email'] = PUNS_FORMS_ADMIN_EMAIL;
	}

	return $settings;
}

/**
 * Google reCAPTCHA test keys (always pass — local/dev only).
 *
 * @return array{site:string,secret:string}
 */
function puns_forms_recaptcha_test_keys() {
	return array(
		'site'   => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
		'secret' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
	);
}

/**
 * Whether to fall back to Google test reCAPTCHA keys.
 *
 * @return bool
 */
function puns_forms_use_recaptcha_test_keys() {
	if ( defined( 'PUNS_FORMS_RECAPTCHA_TEST' ) && PUNS_FORMS_RECAPTCHA_TEST ) {
		return true;
	}

	return function_exists( 'wp_get_environment_type' ) && 'local' === wp_get_environment_type();
}

/**
 * Resolve effective reCAPTCHA keys (constants → saved → local test keys).
 *
 * @param array<string,mixed> $settings Settings array.
 * @return array{site:string,secret:string}
 */
function puns_forms_get_recaptcha_keys( $settings = null ) {
	if ( null === $settings ) {
		$settings = puns_forms_get_settings();
	}

	$site   = trim( (string) $settings['recaptcha_site_key'] );
	$secret = trim( (string) $settings['recaptcha_secret_key'] );

	if ( defined( 'PUNS_RECAPTCHA_SITE_KEY' ) ) {
		$site = PUNS_RECAPTCHA_SITE_KEY;
	}
	if ( defined( 'PUNS_RECAPTCHA_SECRET_KEY' ) ) {
		$secret = PUNS_RECAPTCHA_SECRET_KEY;
	}

	if ( ( '' === $site || '' === $secret ) && puns_forms_use_recaptcha_test_keys() && ! empty( $settings['recaptcha_enabled'] ) ) {
		$test   = puns_forms_recaptcha_test_keys();
		$site   = $test['site'];
		$secret = $test['secret'];
	}

	return array(
		'site'   => $site,
		'secret' => $secret,
	);
}

/**
 * Merge saved field config with defaults (keeps new fields when settings were saved earlier).
 *
 * @param array<string,array<string,mixed>> $saved    Saved fields.
 * @param array<string,array<string,mixed>> $defaults Default fields.
 * @return array<string,array<string,mixed>>
 */
function puns_forms_merge_field_defs( $saved, $defaults ) {
	$merged = array();

	foreach ( $defaults as $field_key => $default_field ) {
		if ( isset( $saved[ $field_key ] ) && is_array( $saved[ $field_key ] ) ) {
			$merged[ $field_key ] = wp_parse_args( $saved[ $field_key ], $default_field );
		} else {
			$merged[ $field_key ] = $default_field;
		}
	}

	return $merged;
}

/**
 * Parse comma/semicolon/space-separated email list.
 *
 * @param string $raw Raw list.
 * @return string[]
 */
function puns_forms_parse_email_list( $raw ) {
	$emails = array();

	foreach ( preg_split( '/[\s,;]+/', (string) $raw ) as $part ) {
		$part = sanitize_email( trim( $part ) );
		if ( is_email( $part ) ) {
			$emails[] = $part;
		}
	}

	return array_values( array_unique( $emails ) );
}

/**
 * CSS layout class for a form field.
 *
 * @param string $form_key  contact|write.
 * @param string $field_key Field key.
 * @param string $field_type Field type.
 * @return string
 */
function puns_forms_field_layout_class( $form_key, $field_key, $field_type ) {
	if ( 'textarea' === $field_type || 'subject' === $field_key || 'message' === $field_key || 'pitch' === $field_key ) {
		return ' puns-cform__field--full';
	}

	if ( 'contact' === $form_key && in_array( $field_key, array( 'name', 'email', 'phone' ), true ) ) {
		return ' puns-cform__field--half';
	}

	if ( 'write' === $form_key ) {
		return ' puns-cform__field--full';
	}

	return '';
}

/**
 * Get a single form definition.
 *
 * @param string $key contact|write.
 * @return array<string, mixed>|null
 */
function puns_forms_get_form( $key ) {
	$settings = puns_forms_get_settings();
	$key      = sanitize_key( $key );

	if ( ! in_array( $key, array( 'contact', 'write' ), true ) ) {
		return null;
	}

	$defaults = puns_forms_default_settings();
	$form     = isset( $settings[ $key ] ) && is_array( $settings[ $key ] )
		? wp_parse_args( $settings[ $key ], $defaults[ $key ] )
		: $defaults[ $key ];

	$form['fields'] = puns_forms_merge_field_defs(
		isset( $form['fields'] ) && is_array( $form['fields'] ) ? $form['fields'] : array(),
		$defaults[ $key ]['fields']
	);

	return $form;
}

/**
 * Whether reCAPTCHA is configured.
 *
 * @return bool
 */
function puns_forms_recaptcha_enabled() {
	$settings = puns_forms_get_settings();

	if ( empty( $settings['recaptcha_enabled'] ) ) {
		return false;
	}

	$keys = puns_forms_get_recaptcha_keys( $settings );

	return '' !== $keys['site'] && '' !== $keys['secret'];
}

/**
 * Register submission CPT.
 */
function puns_forms_register_cpt() {
	register_post_type(
		'puns_submission',
		array(
			'labels'              => array(
				'name'               => __( 'Form Submissions', 'puns' ),
				'singular_name'      => __( 'Form Submission', 'puns' ),
				'menu_name'          => __( 'Submissions', 'puns' ),
				'all_items'          => __( 'All Submissions', 'puns' ),
				'view_item'          => __( 'View Submission', 'puns' ),
				'search_items'       => __( 'Search Submissions', 'puns' ),
				'not_found'          => __( 'No submissions found.', 'puns' ),
				'not_found_in_trash' => __( 'No submissions found in Trash.', 'puns' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-email-alt',
			'menu_position'       => 26,
			'capability_type'     => 'post',
			'capabilities'        => array(
				'create_posts' => 'do_not_allow',
			),
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
		)
	);
}
add_action( 'init', 'puns_forms_register_cpt' );

/**
 * Admin list columns.
 *
 * @param string[] $columns Columns.
 * @return string[]
 */
function puns_forms_admin_columns( $columns ) {
	$new = array(
		'cb'    => $columns['cb'],
		'title' => __( 'Submission', 'puns' ),
		'type'  => __( 'Form', 'puns' ),
		'name'  => __( 'Name', 'puns' ),
		'email' => __( 'Email', 'puns' ),
		'ip'    => __( 'IP', 'puns' ),
		'date'  => __( 'Submitted', 'puns' ),
	);

	return $new;
}
add_filter( 'manage_puns_submission_posts_columns', 'puns_forms_admin_columns' );

/**
 * Render admin list column values.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function puns_forms_admin_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'type':
			$type = get_post_meta( $post_id, '_puns_form_type', true );
			echo esc_html( 'write' === $type ? __( 'Write with Us', 'puns' ) : __( 'Contact Us', 'puns' ) );
			break;
		case 'name':
			echo esc_html( (string) get_post_meta( $post_id, '_puns_submitter_name', true ) );
			break;
		case 'email':
			$email = (string) get_post_meta( $post_id, '_puns_submitter_email', true );
			if ( $email && is_email( $email ) ) {
				printf(
					'<a href="mailto:%1$s">%2$s</a>',
					esc_attr( antispambot( $email ) ),
					esc_html( antispambot( $email ) )
				);
			}
			break;
		case 'ip':
			echo esc_html( (string) get_post_meta( $post_id, '_puns_submitter_ip', true ) );
			break;
	}
}
add_action( 'manage_puns_submission_posts_custom_column', 'puns_forms_admin_column_content', 10, 2 );

/**
 * Meta box with full submission payload.
 */
function puns_forms_add_meta_boxes() {
	add_meta_box(
		'puns_submission_details',
		__( 'Submission Details', 'puns' ),
		'puns_forms_render_details_metabox',
		'puns_submission',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'puns_forms_add_meta_boxes' );

/**
 * @param WP_Post $post Post.
 */
function puns_forms_render_details_metabox( $post ) {
	$fields = get_post_meta( $post->ID, '_puns_submission_fields', true );
	if ( ! is_array( $fields ) ) {
		$fields = array();
	}

	$rows = array(
		__( 'Form', 'puns' )         => get_post_meta( $post->ID, '_puns_form_type', true ),
		__( 'Name', 'puns' )         => get_post_meta( $post->ID, '_puns_submitter_name', true ),
		__( 'Email', 'puns' )        => get_post_meta( $post->ID, '_puns_submitter_email', true ),
		__( 'IP address', 'puns' )   => get_post_meta( $post->ID, '_puns_submitter_ip', true ),
		__( 'User agent', 'puns' )   => get_post_meta( $post->ID, '_puns_submitter_ua', true ),
		__( 'Submitted at', 'puns' ) => get_post_meta( $post->ID, '_puns_submitted_at', true ),
	);

	echo '<table class="widefat striped"><tbody>';

	foreach ( $rows as $label => $value ) {
		if ( '' === (string) $value ) {
			continue;
		}
		printf(
			'<tr><th scope="row" style="width:160px;">%1$s</th><td>%2$s</td></tr>',
			esc_html( $label ),
			esc_html( (string) $value )
		);
	}

	foreach ( $fields as $field_key => $field_value ) {
		if ( in_array( $field_key, array( 'name', 'email' ), true ) ) {
			continue;
		}
		printf(
			'<tr><th scope="row" style="width:160px;">%1$s</th><td>%2$s</td></tr>',
			esc_html( ucwords( str_replace( '_', ' ', $field_key ) ) ),
			nl2br( esc_html( (string) $field_value ) )
		);
	}

	echo '</tbody></table>';
}

/**
 * Client IP (respects common proxy headers when present).
 *
 * @return string
 */
function puns_forms_get_client_ip() {
	$candidates = array(
		'HTTP_CF_CONNECTING_IP',
		'HTTP_X_FORWARDED_FOR',
		'REMOTE_ADDR',
	);

	foreach ( $candidates as $key ) {
		if ( empty( $_SERVER[ $key ] ) ) {
			continue;
		}

		$raw = sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );
		if ( 'HTTP_X_FORWARDED_FOR' === $key && str_contains( $raw, ',' ) ) {
			$raw = trim( explode( ',', $raw )[0] );
		}

		if ( filter_var( $raw, FILTER_VALIDATE_IP ) ) {
			return $raw;
		}
	}

	return '0.0.0.0';
}

/**
 * Hash IP for transient keys.
 *
 * @param string $ip IP address.
 * @return string
 */
function puns_forms_ip_hash( $ip ) {
	return substr( hash_hmac( 'sha256', $ip, wp_salt( 'auth' ) ), 0, 32 );
}

/**
 * Check and increment rate limits.
 *
 * @return true|WP_Error
 */
function puns_forms_check_rate_limit() {
	$settings = puns_forms_get_settings();
	$ip       = puns_forms_get_client_ip();
	$hash     = puns_forms_ip_hash( $ip );

	$minute_key = 'puns_rl_min_' . $hash;
	$month_key  = 'puns_rl_mon_' . $hash;

	$minute_count = (int) get_transient( $minute_key );
	$month_count  = (int) get_transient( $month_key );

	$minute_limit = max( 1, (int) $settings['rate_limit_minute'] );
	$month_limit  = max( 1, (int) $settings['rate_limit_month'] );

	if ( $minute_count >= $minute_limit ) {
		return new WP_Error(
			'rate_limit_minute',
			__( 'Too many submissions. Please wait a minute and try again.', 'puns' )
		);
	}

	if ( $month_count >= $month_limit ) {
		return new WP_Error(
			'rate_limit_month',
			__( 'Submission limit reached for this month. Please contact us by email instead.', 'puns' )
		);
	}

	set_transient( $minute_key, $minute_count + 1, MINUTE_IN_SECONDS );
	set_transient( $month_key, $month_count + 1, 30 * DAY_IN_SECONDS );

	return true;
}

/**
 * Verify Google reCAPTCHA response.
 *
 * @param string $token   Response token.
 * @param string $version v2|v3.
 * @return true|WP_Error
 */
function puns_forms_verify_recaptcha( $token, $version = 'v2' ) {
	if ( ! puns_forms_recaptcha_enabled() ) {
		return true;
	}

	$token = trim( (string) $token );
	if ( '' === $token ) {
		return new WP_Error( 'recaptcha_missing', __( 'Please complete the reCAPTCHA challenge.', 'puns' ) );
	}

	$settings = puns_forms_get_settings();
	$keys     = puns_forms_get_recaptcha_keys( $settings );
	$secret   = $keys['secret'];

	$response = wp_remote_post(
		'https://www.google.com/recaptcha/api/siteverify',
		array(
			'timeout' => 12,
			'body'    => array(
				'secret'   => $secret,
				'response' => $token,
				'remoteip' => puns_forms_get_client_ip(),
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error( 'recaptcha_http', __( 'Could not verify reCAPTCHA. Please try again.', 'puns' ) );
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( empty( $body['success'] ) ) {
		return new WP_Error( 'recaptcha_failed', __( 'reCAPTCHA verification failed. Please try again.', 'puns' ) );
	}

	if ( 'v3' === $version ) {
		$score     = isset( $body['score'] ) ? (float) $body['score'] : 0;
		$threshold = (float) $settings['recaptcha_v3_threshold'];
		if ( $score < $threshold ) {
			return new WP_Error( 'recaptcha_score', __( 'reCAPTCHA score too low. Please try again.', 'puns' ) );
		}
	}

	return true;
}

/**
 * Replace template tags in email strings.
 *
 * @param string               $template Template.
 * @param array<string,string> $tags     Tags.
 * @return string
 */
function puns_forms_replace_tags( $template, $tags ) {
	$template = (string) $template;
	foreach ( $tags as $key => $value ) {
		$template = str_replace( '{' . $key . '}', (string) $value, $template );
	}
	return $template;
}

/**
 * Validate and sanitize submitted field values.
 *
 * @param string               $form_key contact|write.
 * @param array<string,string> $raw      Raw POST fields.
 * @return array{values: array<string,string>, errors: array<string,string>}|WP_Error
 */
function puns_forms_validate_submission( $form_key, $raw ) {
	$form = puns_forms_get_form( $form_key );
	if ( ! $form || empty( $form['fields'] ) || ! is_array( $form['fields'] ) ) {
		return new WP_Error( 'invalid_form', __( 'Invalid form.', 'puns' ) );
	}

	// Honeypot — bots fill hidden field.
	if ( ! empty( $raw['puns_hp'] ) ) {
		return new WP_Error( 'spam', __( 'Submission rejected.', 'puns' ) );
	}

	$values = array();
	$errors = array();

	foreach ( $form['fields'] as $field_key => $field_config ) {
		if ( empty( $field_config['enabled'] ) ) {
			continue;
		}

		$field_key = sanitize_key( $field_key );
		$value     = isset( $raw[ $field_key ] ) ? wp_unslash( $raw[ $field_key ] ) : '';
		$required  = ! empty( $field_config['required'] );
		$type      = isset( $field_config['type'] ) ? $field_config['type'] : 'text';

		if ( is_array( $value ) ) {
			$value = '';
		}

		switch ( $type ) {
			case 'email':
				$value = sanitize_email( $value );
				if ( $required && ! is_email( $value ) ) {
					$errors[ $field_key ] = __( 'Please enter a valid email address.', 'puns' );
				}
				break;
			case 'url':
				$value = esc_url_raw( $value );
				if ( $required && '' === $value ) {
					$errors[ $field_key ] = __( 'Please enter a valid URL.', 'puns' );
				}
				break;
			case 'tel':
				$value = sanitize_text_field( $value );
				if ( $required && '' === trim( $value ) ) {
					$errors[ $field_key ] = __( 'This field is required.', 'puns' );
				}
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $value );
				if ( $required && '' === trim( $value ) ) {
					$errors[ $field_key ] = __( 'This field is required.', 'puns' );
				}
				break;
			default:
				$value = sanitize_text_field( $value );
				if ( $required && '' === trim( $value ) ) {
					$errors[ $field_key ] = __( 'This field is required.', 'puns' );
				}
		}

		$values[ $field_key ] = $value;
	}

	if ( ! empty( $errors ) ) {
		return array(
			'values' => $values,
			'errors' => $errors,
		);
	}

	return array(
		'values' => $values,
		'errors' => array(),
	);
}

/**
 * Primary message body field key per form.
 *
 * @param string $form_key Form key.
 * @return string
 */
function puns_forms_primary_body_key( $form_key ) {
	return 'write' === $form_key ? 'pitch' : 'message';
}

/**
 * Save submission to CPT.
 *
 * @param string               $form_key Form key.
 * @param array<string,string> $values   Sanitized values.
 * @return int|WP_Error Post ID.
 */
function puns_forms_save_submission( $form_key, $values ) {
	$name  = isset( $values['name'] ) ? $values['name'] : '';
	$email = isset( $values['email'] ) ? $values['email'] : '';
	$body  = isset( $values[ puns_forms_primary_body_key( $form_key ) ] )
		? $values[ puns_forms_primary_body_key( $form_key ) ]
		: '';

	$type_label = 'write' === $form_key ? __( 'Write', 'puns' ) : __( 'Contact', 'puns' );
	$title      = sprintf( '%1$s — %2$s', $type_label, $name ? $name : __( 'Anonymous', 'puns' ) );

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'puns_submission',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_content' => $body,
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	update_post_meta( $post_id, '_puns_form_type', $form_key );
	update_post_meta( $post_id, '_puns_submitter_name', $name );
	update_post_meta( $post_id, '_puns_submitter_email', $email );
	update_post_meta( $post_id, '_puns_submitter_ip', puns_forms_get_client_ip() );
	update_post_meta( $post_id, '_puns_submitter_ua', isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' );
	update_post_meta( $post_id, '_puns_submitted_at', current_time( 'mysql' ) );
	update_post_meta( $post_id, '_puns_submission_fields', $values );

	return $post_id;
}

/**
 * Send admin + user notification emails.
 *
 * @param string               $form_key Form key.
 * @param array<string,string> $values   Field values.
 * @param int                  $post_id  Submission post ID.
 */
function puns_forms_send_emails( $form_key, $values, $post_id ) {
	$settings   = puns_forms_get_settings();
	$form       = puns_forms_get_form( $form_key );
	$site_name  = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$admin_mail = sanitize_email( $settings['admin_email'] );

	if ( ! is_email( $admin_mail ) ) {
		$admin_mail = sanitize_email( get_option( 'admin_email' ) );
	}

	$name  = isset( $values['name'] ) ? $values['name'] : '';
	$email = isset( $values['email'] ) ? $values['email'] : '';

	$tags = array(
		'site' => $site_name,
		'name' => $name,
	);

	$admin_subject = puns_forms_replace_tags( $form['admin_subject'], $tags );

	$admin_lines = array(
		sprintf( __( 'New %1$s submission (#%2$d)', 'puns' ), 'write' === $form_key ? 'Write with Us' : 'Contact Us', $post_id ),
		'',
		sprintf( __( 'Name: %s', 'puns' ), $name ),
		sprintf( __( 'Email: %s', 'puns' ), $email ),
		'',
	);

	foreach ( $values as $key => $val ) {
		if ( in_array( $key, array( 'name', 'email' ), true ) || '' === $val ) {
			continue;
		}
		$admin_lines[] = ucwords( str_replace( '_', ' ', $key ) ) . ':';
		$admin_lines[] = $val;
		$admin_lines[] = '';
	}

	$admin_lines[] = sprintf( __( 'View in dashboard: %s', 'puns' ), admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $email ) ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}

	$recipients = array( $admin_mail );
	if ( ! empty( $form['notification_emails'] ) ) {
		$recipients = array_merge( $recipients, puns_forms_parse_email_list( $form['notification_emails'] ) );
	}
	$recipients = array_values( array_unique( array_filter( $recipients, 'is_email' ) ) );

	foreach ( $recipients as $recipient ) {
		wp_mail( $recipient, $admin_subject, implode( "\n", $admin_lines ), $headers );
	}

	if ( is_email( $email ) ) {
		$user_subject = puns_forms_replace_tags( $form['autoresponder_subject'], $tags );
		$user_body    = puns_forms_replace_tags( $form['autoresponder_body'], $tags );

		wp_mail(
			$email,
			$user_subject,
			$user_body,
			array( 'Content-Type: text/plain; charset=UTF-8' )
		);
	}
}

/**
 * AJAX form handler.
 */
function puns_forms_ajax_submit() {
	check_ajax_referer( 'puns_custom_form', 'nonce' );

	$form_key = isset( $_POST['form_key'] ) ? sanitize_key( wp_unslash( $_POST['form_key'] ) ) : '';
	if ( ! in_array( $form_key, array( 'contact', 'write' ), true ) ) {
		wp_send_json_error(
			array( 'message' => __( 'Invalid form.', 'puns' ) ),
			400
		);
	}

	$rate = puns_forms_check_rate_limit();
	if ( is_wp_error( $rate ) ) {
		wp_send_json_error( array( 'message' => $rate->get_error_message() ), 429 );
	}

	$settings = puns_forms_get_settings();
	$version  = 'v3' === $settings['recaptcha_version'] ? 'v3' : 'v2';
	$token    = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';

	$recaptcha = puns_forms_verify_recaptcha( $token, $version );
	if ( is_wp_error( $recaptcha ) ) {
		wp_send_json_error( array( 'message' => $recaptcha->get_error_message() ), 400 );
	}

	$raw_fields = isset( $_POST['fields'] ) && is_array( $_POST['fields'] )
		? array_map(
			static function ( $v ) {
				return is_string( $v ) ? $v : '';
			},
			wp_unslash( $_POST['fields'] )
		)
		: array();

	if ( isset( $_POST['puns_hp'] ) ) {
		$raw_fields['puns_hp'] = sanitize_text_field( wp_unslash( $_POST['puns_hp'] ) );
	}

	$validation = puns_forms_validate_submission( $form_key, $raw_fields );
	if ( is_wp_error( $validation ) ) {
		wp_send_json_error( array( 'message' => $validation->get_error_message() ), 400 );
	}

	if ( ! empty( $validation['errors'] ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Please fix the highlighted fields.', 'puns' ),
				'fields'  => $validation['errors'],
			),
			422
		);
	}

	$values  = $validation['values'];
	$post_id = puns_forms_save_submission( $form_key, $values );

	if ( is_wp_error( $post_id ) ) {
		wp_send_json_error(
			array( 'message' => __( 'Could not save your submission. Please try again.', 'puns' ) ),
			500
		);
	}

	puns_forms_send_emails( $form_key, $values, $post_id );

	wp_send_json_success(
		array(
			'message' => 'write' === $form_key
				? __( 'Thanks! Your pitch was sent. We will be in touch if it is a fit.', 'puns' )
				: __( 'Thanks! Your message was sent. We will reply within 1–2 business days.', 'puns' ),
		)
	);
}
add_action( 'wp_ajax_puns_submit_form', 'puns_forms_ajax_submit' );
add_action( 'wp_ajax_nopriv_puns_submit_form', 'puns_forms_ajax_submit' );

/**
 * Whether the current page content includes a themed form shortcode.
 *
 * @param string $form_key Optional form key filter (contact|write).
 * @return bool
 */
function puns_forms_page_has_shortcode( $form_key = '' ) {
	if ( ! is_singular() ) {
		return false;
	}

	$post = get_post();
	if ( ! $post instanceof WP_Post || ! has_shortcode( $post->post_content, 'puns_form' ) ) {
		return false;
	}

	if ( '' === $form_key ) {
		return true;
	}

	return (bool) preg_match(
		'/\[puns_form[^\]]*\bform=["\']?' . preg_quote( $form_key, '/' ) . '["\']?/',
		$post->post_content
	);
}

/**
 * Shortcode: [puns_form form="contact" heading="" intro=""].
 *
 * @param array<string, string> $atts Shortcode attributes.
 * @return string
 */
function puns_form_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'form'    => 'contact',
			'heading' => '',
			'intro'   => '',
		),
		$atts,
		'puns_form'
	);

	$form = sanitize_key( $atts['form'] );
	if ( ! in_array( $form, array( 'contact', 'write' ), true ) ) {
		return '';
	}

	ob_start();
	puns_the_custom_form( $form, $atts['heading'], $atts['intro'] );
	return (string) ob_get_clean();
}
add_shortcode( 'puns_form', 'puns_form_shortcode' );

/**
 * Enqueue form assets on static pages.
 */
function puns_forms_enqueue_assets() {
	if ( ! is_page() ) {
		return;
	}

	$is_contact = is_page_template( 'page-templates/contact.php' )
		|| is_page( array( 'contact', 'contact-us' ) )
		|| puns_forms_page_has_shortcode( 'contact' );
	$is_write   = is_page_template( 'page-templates/write-with-us.php' )
		|| is_page( array( 'write-with-us', 'write' ) )
		|| puns_forms_page_has_shortcode( 'write' );

	if ( ! $is_contact && ! $is_write ) {
		return;
	}

	$settings = puns_forms_get_settings();
	$version  = 'v3' === $settings['recaptcha_version'] ? 'v3' : 'v2';
	$keys     = puns_forms_get_recaptcha_keys( $settings );

	wp_enqueue_script(
		'puns-custom-forms',
		get_template_directory_uri() . '/assets/js/custom-forms.js',
		array(),
		PUNS_VERSION,
		true
	);

	if ( puns_forms_recaptcha_enabled() ) {
		if ( 'v3' === $version ) {
			wp_enqueue_script(
				'google-recaptcha',
				'https://www.google.com/recaptcha/api.js?render=' . rawurlencode( $keys['site'] ),
				array(),
				null,
				true
			);
		} else {
			wp_enqueue_script(
				'google-recaptcha',
				'https://www.google.com/recaptcha/api.js',
				array(),
				null,
				true
			);
		}
	}

	wp_localize_script(
		'puns-custom-forms',
		'punsCustomForms',
		array(
			'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
			'nonce'            => wp_create_nonce( 'puns_custom_form' ),
			'action'           => 'puns_submit_form',
			'recaptchaEnabled' => puns_forms_recaptcha_enabled(),
			'recaptchaVersion' => $version,
			'recaptchaSiteKey' => $keys['site'],
			'i18n'             => array(
				'sending'            => __( 'Sending…', 'puns' ),
				'submit'             => __( 'Send message', 'puns' ),
				'submitWrite'        => __( 'Send pitch', 'puns' ),
				'error'              => __( 'Something went wrong. Please try again.', 'puns' ),
				'recaptcha'          => __( 'Please complete the reCAPTCHA.', 'puns' ),
				'requiredField'      => __( 'This field is required.', 'puns' ),
				'invalidEmail'       => __( 'Please enter a valid email address.', 'puns' ),
				'invalidUrl'         => __( 'Please enter a valid URL.', 'puns' ),
				'validationSummary'  => __( 'There was a problem with your submission. Please review the fields below.', 'puns' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'puns_forms_enqueue_assets', 20 );

/**
 * Render a custom themed form.
 *
 * @param string $key     contact|write.
 * @param string $heading Heading.
 * @param string $intro   Intro text.
 */
function puns_the_custom_form( $key, $heading = '', $intro = '' ) {
	$form = puns_forms_get_form( $key );
	if ( ! $form ) {
		return;
	}

	$submit_label = 'write' === $key
		? __( 'Send pitch', 'puns' )
		: __( 'Send message', 'puns' );

	$form_id = 'puns-cform-' . sanitize_html_class( $key );
	$recaptcha_keys = puns_forms_get_recaptcha_keys();
	?>
	<div class="puns-cform-wrap puns-cform-wrap--<?php echo esc_attr( $key ); ?>">
		<?php if ( $heading || $intro ) : ?>
			<div class="puns-contact__form-intro puns-cform__intro">
				<?php if ( $heading ) : ?>
					<h2 class="puns-static__heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $intro ) : ?>
					<p class="puns-static__text"><?php echo esc_html( $intro ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="puns-cform__notice" role="alert" aria-live="polite" hidden></div>

		<form
			id="<?php echo esc_attr( $form_id ); ?>"
			class="puns-cform"
			method="post"
			action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
			data-form-key="<?php echo esc_attr( $key ); ?>"
			novalidate
		>
			<input type="hidden" name="action" value="puns_submit_form" />
			<input type="hidden" name="form_key" value="<?php echo esc_attr( $key ); ?>" />

			<div class="puns-cform__hp" aria-hidden="true">
				<label for="<?php echo esc_attr( $form_id ); ?>-hp"><?php esc_html_e( 'Leave empty', 'puns' ); ?></label>
				<input type="text" id="<?php echo esc_attr( $form_id ); ?>-hp" name="puns_hp" tabindex="-1" autocomplete="off" />
			</div>

			<div class="puns-cform__fields">
				<?php
				foreach ( $form['fields'] as $field_key => $field_config ) :
					if ( empty( $field_config['enabled'] ) ) {
						continue;
					}

					$field_key  = sanitize_key( $field_key );
					$field_id   = $form_id . '-' . $field_key;
					$field_type = isset( $field_config['type'] ) ? $field_config['type'] : 'text';
					$required   = ! empty( $field_config['required'] );
					$label      = isset( $field_config['label'] ) ? $field_config['label'] : ucfirst( $field_key );
					$layout     = puns_forms_field_layout_class( $key, $field_key, $field_type );
					$input_type = 'text';
					if ( 'email' === $field_type ) {
						$input_type = 'email';
					} elseif ( 'url' === $field_type ) {
						$input_type = 'url';
					} elseif ( 'tel' === $field_type ) {
						$input_type = 'tel';
					}
					?>
					<div class="puns-cform__field<?php echo esc_attr( $layout ); ?>" data-field="<?php echo esc_attr( $field_key ); ?>">
						<label class="puns-cform__label" for="<?php echo esc_attr( $field_id ); ?>">
							<span class="puns-cform__label-text"><?php echo esc_html( $label ); ?></span>
							<?php if ( $required ) : ?>
								<span class="puns-cform__required" aria-hidden="true">*</span>
								<span class="screen-reader-text"><?php esc_html_e( '(Required)', 'puns' ); ?></span>
							<?php endif; ?>
						</label>

						<?php if ( 'textarea' === $field_type ) : ?>
							<textarea
								class="puns-cform__control"
								id="<?php echo esc_attr( $field_id ); ?>"
								name="fields[<?php echo esc_attr( $field_key ); ?>]"
								rows="8"
								<?php echo $required ? 'required aria-required="true"' : ''; ?>
							></textarea>
						<?php else : ?>
							<input
								class="puns-cform__control"
								id="<?php echo esc_attr( $field_id ); ?>"
								type="<?php echo esc_attr( $input_type ); ?>"
								name="fields[<?php echo esc_attr( $field_key ); ?>]"
								<?php echo $required ? 'required aria-required="true"' : ''; ?>
								<?php echo in_array( $field_type, array( 'url', 'tel' ), true ) ? 'inputmode="' . esc_attr( 'tel' === $field_type ? 'tel' : 'url' ) . '"' : ''; ?>
							/>
						<?php endif; ?>

						<p class="puns-cform__error" role="alert" hidden></p>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( puns_forms_recaptcha_enabled() && 'v2' === puns_forms_get_settings()['recaptcha_version'] ) : ?>
				<div class="puns-cform__recaptcha g-recaptcha" data-sitekey="<?php echo esc_attr( $recaptcha_keys['site'] ); ?>"></div>
			<?php endif; ?>

			<div class="puns-cform__actions">
				<button type="submit" class="puns-cform__submit puns-btn puns-btn--primary">
					<?php echo esc_html( $submit_label ); ?>
				</button>
			</div>
		</form>
	</div>
	<?php
}

/**
 * Register settings page.
 */
function puns_forms_register_settings_page() {
	add_submenu_page(
		'edit.php?post_type=puns_submission',
		__( 'Form Settings', 'puns' ),
		__( 'Settings', 'puns' ),
		'manage_options',
		'puns-form-settings',
		'puns_forms_render_settings_page'
	);
}
add_action( 'admin_menu', 'puns_forms_register_settings_page' );

/**
 * Register settings.
 */
function puns_forms_register_settings() {
	register_setting(
		'puns_forms_settings_group',
		PUNS_FORMS_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'puns_forms_sanitize_settings',
			'default'           => puns_forms_default_settings(),
		)
	);
}
add_action( 'admin_init', 'puns_forms_register_settings' );

/**
 * Sanitize settings on save.
 *
 * @param mixed $input Raw input.
 * @return array<string, mixed>
 */
function puns_forms_sanitize_settings( $input ) {
	if ( ! is_array( $input ) ) {
		return puns_forms_default_settings();
	}

	$defaults = puns_forms_default_settings();
	$output   = wp_parse_args( $input, $defaults );

	$output['admin_email']             = sanitize_email( $output['admin_email'] );
	$output['recaptcha_enabled']       = ! empty( $output['recaptcha_enabled'] );
	$output['recaptcha_site_key']      = sanitize_text_field( $output['recaptcha_site_key'] );
	$output['recaptcha_secret_key']    = sanitize_text_field( $output['recaptcha_secret_key'] );
	$output['recaptcha_version']      = in_array( $output['recaptcha_version'], array( 'v2', 'v3' ), true ) ? $output['recaptcha_version'] : 'v2';
	$output['recaptcha_v3_threshold'] = min( 1, max( 0, (float) $output['recaptcha_v3_threshold'] ) );
	$output['rate_limit_minute']      = max( 1, (int) $output['rate_limit_minute'] );
	$output['rate_limit_month']       = max( 1, (int) $output['rate_limit_month'] );

	foreach ( array( 'contact', 'write' ) as $form_key ) {
		if ( ! isset( $output[ $form_key ] ) || ! is_array( $output[ $form_key ] ) ) {
			$output[ $form_key ] = $defaults[ $form_key ];
			continue;
		}

		$output[ $form_key ]['admin_subject']          = sanitize_text_field( $output[ $form_key ]['admin_subject'] );
		$output[ $form_key ]['autoresponder_subject']  = sanitize_text_field( $output[ $form_key ]['autoresponder_subject'] );
		$output[ $form_key ]['autoresponder_body']     = sanitize_textarea_field( $output[ $form_key ]['autoresponder_body'] );
		$output[ $form_key ]['notification_emails']    = sanitize_textarea_field( $output[ $form_key ]['notification_emails'] );

		if ( isset( $output[ $form_key ]['fields'] ) && is_array( $output[ $form_key ]['fields'] ) ) {
			$output[ $form_key ]['fields'] = puns_forms_merge_field_defs(
				$output[ $form_key ]['fields'],
				$defaults[ $form_key ]['fields']
			);
			foreach ( $output[ $form_key ]['fields'] as $field_key => $field ) {
				if ( ! is_array( $field ) ) {
					continue;
				}
				$output[ $form_key ]['fields'][ $field_key ]['label']    = sanitize_text_field( $field['label'] );
				$output[ $form_key ]['fields'][ $field_key ]['required'] = ! empty( $field['required'] );
				$output[ $form_key ]['fields'][ $field_key ]['enabled']  = ! empty( $field['enabled'] );
			}
		}
	}

	return $output;
}

/**
 * Settings page markup.
 */
function puns_forms_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = puns_forms_get_settings();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Custom Form Settings', 'puns' ); ?></h1>
		<p><?php esc_html_e( 'Configure notifications, reCAPTCHA, rate limits, and editable field labels for Contact Us and Write with Us forms.', 'puns' ); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields( 'puns_forms_settings_group' ); ?>

			<h2><?php esc_html_e( 'General', 'puns' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="puns_admin_email"><?php esc_html_e( 'Admin notification email', 'puns' ); ?></label></th>
					<td><input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[admin_email]" type="email" id="puns_admin_email" value="<?php echo esc_attr( $settings['admin_email'] ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="puns_rl_min"><?php esc_html_e( 'Rate limit (per minute, per IP)', 'puns' ); ?></label></th>
					<td><input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[rate_limit_minute]" type="number" id="puns_rl_min" min="1" value="<?php echo esc_attr( (int) $settings['rate_limit_minute'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="puns_rl_mon"><?php esc_html_e( 'Rate limit (per month, per IP)', 'puns' ); ?></label></th>
					<td><input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[rate_limit_month]" type="number" id="puns_rl_mon" min="1" value="<?php echo esc_attr( (int) $settings['rate_limit_month'] ); ?>" /></td>
				</tr>
			</table>

			<h2><?php esc_html_e( 'Google reCAPTCHA', 'puns' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Enable reCAPTCHA to protect forms from spam. On local sites, Google test keys are used automatically when your own keys are empty. Add production keys before going live.', 'puns' ); ?>
			</p>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Enable reCAPTCHA', 'puns' ); ?></th>
					<td>
						<label>
							<input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[recaptcha_enabled]" type="checkbox" value="1" <?php checked( ! empty( $settings['recaptcha_enabled'] ) ); ?> />
							<?php esc_html_e( 'Show reCAPTCHA on Contact and Write forms', 'puns' ); ?>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="puns_recaptcha_version"><?php esc_html_e( 'Version', 'puns' ); ?></label></th>
					<td>
						<select name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[recaptcha_version]" id="puns_recaptcha_version">
							<option value="v2" <?php selected( $settings['recaptcha_version'], 'v2' ); ?>><?php esc_html_e( 'v2 (checkbox)', 'puns' ); ?></option>
							<option value="v3" <?php selected( $settings['recaptcha_version'], 'v3' ); ?>><?php esc_html_e( 'v3 (invisible)', 'puns' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="puns_recaptcha_site"><?php esc_html_e( 'Site key', 'puns' ); ?></label></th>
					<td><input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[recaptcha_site_key]" type="text" id="puns_recaptcha_site" value="<?php echo esc_attr( $settings['recaptcha_site_key'] ); ?>" class="large-text code" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="puns_recaptcha_secret"><?php esc_html_e( 'Secret key', 'puns' ); ?></label></th>
					<td><input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[recaptcha_secret_key]" type="password" id="puns_recaptcha_secret" value="<?php echo esc_attr( $settings['recaptcha_secret_key'] ); ?>" class="large-text code" autocomplete="new-password" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="puns_recaptcha_threshold"><?php esc_html_e( 'v3 score threshold', 'puns' ); ?></label></th>
					<td><input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[recaptcha_v3_threshold]" type="number" step="0.1" min="0" max="1" id="puns_recaptcha_threshold" value="<?php echo esc_attr( $settings['recaptcha_v3_threshold'] ); ?>" /></td>
				</tr>
			</table>

			<?php foreach ( array( 'contact' => __( 'Contact Us', 'puns' ), 'write' => __( 'Write with Us', 'puns' ) ) as $form_key => $form_label ) : ?>
				<h2><?php echo esc_html( $form_label ); ?></h2>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label><?php esc_html_e( 'Extra notification emails', 'puns' ); ?></label></th>
						<td>
							<textarea name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[<?php echo esc_attr( $form_key ); ?>][notification_emails]" rows="2" class="large-text code"><?php echo esc_textarea( $settings[ $form_key ]['notification_emails'] ); ?></textarea>
							<p class="description"><?php esc_html_e( 'Optional. Comma-separated emails that also receive the admin notification (in addition to the main admin email above).', 'puns' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php esc_html_e( 'Admin email subject', 'puns' ); ?></label></th>
						<td><input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[<?php echo esc_attr( $form_key ); ?>][admin_subject]" type="text" value="<?php echo esc_attr( $settings[ $form_key ]['admin_subject'] ); ?>" class="large-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label><?php esc_html_e( 'Auto-reply subject', 'puns' ); ?></label></th>
						<td><input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[<?php echo esc_attr( $form_key ); ?>][autoresponder_subject]" type="text" value="<?php echo esc_attr( $settings[ $form_key ]['autoresponder_subject'] ); ?>" class="large-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label><?php esc_html_e( 'Auto-reply body', 'puns' ); ?></label></th>
						<td><textarea name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[<?php echo esc_attr( $form_key ); ?>][autoresponder_body]" rows="5" class="large-text code"><?php echo esc_textarea( $settings[ $form_key ]['autoresponder_body'] ); ?></textarea></td>
					</tr>
				</table>

				<h3><?php esc_html_e( 'Form fields', 'puns' ); ?></h3>
				<table class="widefat striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Field', 'puns' ); ?></th>
							<th><?php esc_html_e( 'Label', 'puns' ); ?></th>
							<th><?php esc_html_e( 'Required', 'puns' ); ?></th>
							<th><?php esc_html_e( 'Enabled', 'puns' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $settings[ $form_key ]['fields'] as $field_key => $field ) : ?>
							<tr>
								<td><code><?php echo esc_html( $field_key ); ?></code></td>
								<td>
									<input
										name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[<?php echo esc_attr( $form_key ); ?>][fields][<?php echo esc_attr( $field_key ); ?>][label]"
										type="text"
										value="<?php echo esc_attr( $field['label'] ); ?>"
										class="regular-text"
									/>
									<input type="hidden" name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[<?php echo esc_attr( $form_key ); ?>][fields][<?php echo esc_attr( $field_key ); ?>][type]" value="<?php echo esc_attr( $field['type'] ); ?>" />
								</td>
								<td>
									<input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[<?php echo esc_attr( $form_key ); ?>][fields][<?php echo esc_attr( $field_key ); ?>][required]" type="checkbox" value="1" <?php checked( ! empty( $field['required'] ) ); ?> />
								</td>
								<td>
									<input name="<?php echo esc_attr( PUNS_FORMS_OPTION ); ?>[<?php echo esc_attr( $form_key ); ?>][fields][<?php echo esc_attr( $field_key ); ?>][enabled]" type="checkbox" value="1" <?php checked( ! empty( $field['enabled'] ) ); ?> />
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endforeach; ?>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
