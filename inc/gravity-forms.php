<?php
/**
 * Gravity Forms integration for Contact / Write pages.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether Gravity Forms is available.
 *
 * @return bool
 */
function puns_gravity_forms_active() {
	return class_exists( 'GFCommon' ) || function_exists( 'gravity_form' );
}

/**
 * Get configured Gravity Form ID for a page key.
 *
 * @param string $key contact|write.
 * @return int
 */
function puns_get_gravity_form_id( $key ) {
	$map = array(
		'contact' => 'puns_gf_contact_id',
		'write'   => 'puns_gf_write_id',
	);

	if ( ! isset( $map[ $key ] ) ) {
		return 0;
	}

	return absint( get_theme_mod( $map[ $key ], 0 ) );
}

/**
 * Choices list of Gravity Forms for the Customizer.
 *
 * @return array<int|string, string>
 */
function puns_gravity_form_choices() {
	$choices = array(
		0 => __( '— Select a form —', 'puns' ),
	);

	if ( ! class_exists( 'GFAPI' ) ) {
		return $choices;
	}

	$forms = GFAPI::get_forms( true );
	if ( empty( $forms ) || ! is_array( $forms ) ) {
		return $choices;
	}

	foreach ( $forms as $form ) {
		$id = isset( $form['id'] ) ? (int) $form['id'] : 0;
		if ( $id < 1 ) {
			continue;
		}
		$title             = isset( $form['title'] ) ? $form['title'] : sprintf( 'Form %d', $id );
		$choices[ $id ] = sprintf( '#%1$d — %2$s', $id, $title );
	}

	return $choices;
}

/**
 * Render a themed Gravity Form block (or admin hint).
 *
 * @param string $key     contact|write.
 * @param string $heading Optional heading above the form.
 * @param string $intro   Optional intro text.
 * @return void
 */
function puns_the_gravity_form( $key, $heading = '', $intro = '' ) {
	$form_id = puns_get_gravity_form_id( $key );
	?>
	<div class="puns-gf">
		<?php if ( $heading || $intro ) : ?>
			<div class="puns-contact__form-intro puns-gf__intro">
				<?php if ( $heading ) : ?>
					<h2 class="puns-static__heading"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $intro ) : ?>
					<p class="puns-static__text"><?php echo esc_html( $intro ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! puns_gravity_forms_active() ) : ?>
			<?php if ( current_user_can( 'manage_options' ) ) : ?>
				<div class="puns-static__notice puns-static__notice--error" role="status">
					<?php esc_html_e( 'Install and activate Gravity Forms, then pick a form under Appearance → Customize → Forms.', 'puns' ); ?>
				</div>
			<?php endif; ?>
		<?php elseif ( $form_id < 1 ) : ?>
			<?php if ( current_user_can( 'manage_options' ) ) : ?>
				<div class="puns-static__notice puns-static__notice--error" role="status">
					<?php esc_html_e( 'No Gravity Form selected yet. Go to Appearance → Customize → Forms and choose a form for this page.', 'puns' ); ?>
				</div>
			<?php endif; ?>
		<?php elseif ( function_exists( 'gravity_form' ) ) : ?>
			<div class="puns-gf__form">
				<?php
				// title=false ajax=true — theme supplies heading; AJAX keeps page design intact.
				gravity_form( $form_id, false, false, false, null, true, 0, true );
				?>
			</div>
		<?php else : ?>
			<div class="puns-gf__form">
				<?php echo do_shortcode( sprintf( '[gravityform id="%d" title="false" description="false" ajax="true"]', $form_id ) ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Customizer: Gravity Forms selectors.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 * @return void
 */
function puns_gravity_forms_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'puns_forms',
		array(
			'title'       => __( 'Forms', 'puns' ),
			'description' => __( 'Connect Gravity Forms to the Contact and Write with Us page templates. Forms inherit the active design colors automatically.', 'puns' ),
			'priority'    => 50,
		)
	);

	$choices = puns_gravity_form_choices();

	$wp_customize->add_setting(
		'puns_gf_contact_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'puns_gf_contact_id',
		array(
			'label'       => __( 'Contact Us form', 'puns' ),
			'description' => puns_gravity_forms_active()
				? __( 'Shown on the Contact Us page template.', 'puns' )
				: __( 'Gravity Forms is not active on this site.', 'puns' ),
			'section'     => 'puns_forms',
			'type'        => 'select',
			'choices'     => $choices,
		)
	);

	$wp_customize->add_setting(
		'puns_gf_write_id',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'puns_gf_write_id',
		array(
			'label'       => __( 'Write with Us form', 'puns' ),
			'description' => __( 'Shown on the Write with Us page template.', 'puns' ),
			'section'     => 'puns_forms',
			'type'        => 'select',
			'choices'     => $choices,
		)
	);
}
add_action( 'customize_register', 'puns_gravity_forms_customize_register', 25 );
