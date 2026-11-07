<?php
/**
 * Block template parts helpers (editable header / footer).
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Brand logo shortcode for template parts.
 *
 * [puns_brand_logo location="header|footer"]
 *
 * @param array<string, string> $atts Attributes.
 * @return string
 */
function puns_brand_logo_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'location' => 'header',
		),
		$atts,
		'puns_brand_logo'
	);

	$location = 'footer' === $atts['location'] ? 'footer' : 'header';

	ob_start();

	if ( 'footer' === $location ) {
		puns_the_footer_logo();
	} elseif ( has_custom_logo() ) {
		the_custom_logo();
	} else {
		printf(
			'<a class="puns-header__logo puns-header__logo--svg" href="%1$s">%2$s</a>',
			esc_url( home_url( '/' ) ),
			puns_get_brand_logo_img( '', 'header' )
		);
	}

	return (string) ob_get_clean();
}
add_shortcode( 'puns_brand_logo', 'puns_brand_logo_shortcode' );

/**
 * Header search button shortcode.
 *
 * @return string
 */
function puns_header_search_shortcode() {
	return sprintf(
		'<button class="puns-header__search puns-icon puns-search-open" type="button" aria-label="%s">search</button>',
		esc_attr__( 'Search', 'puns' )
	);
}
add_shortcode( 'puns_header_search', 'puns_header_search_shortcode' );

/**
 * Header subscribe button shortcode.
 *
 * @return string
 */
function puns_header_subscribe_shortcode() {
	return sprintf(
		'<button class="puns-btn puns-btn--solid puns-subscribe-open" type="button">%s</button>',
		esc_html__( 'Subscribe', 'puns' )
	);
}
add_shortcode( 'puns_header_subscribe', 'puns_header_subscribe_shortcode' );

/**
 * Footer copyright shortcode.
 *
 * @return string
 */
function puns_footer_copyright_shortcode() {
	ob_start();
	puns_the_footer_copyright();
	return (string) ob_get_clean();
}
add_shortcode( 'puns_footer_copyright', 'puns_footer_copyright_shortcode' );

/**
 * Whether block template parts are available.
 *
 * @return bool
 */
function puns_supports_block_template_parts() {
	return function_exists( 'block_header_area' )
		&& function_exists( 'block_footer_area' )
		&& current_theme_supports( 'block-template-parts' );
}

/**
 * Render editable header template part (with classic fallback).
 */
function puns_render_header_area() {
	if ( puns_supports_block_template_parts() ) {
		block_header_area();
		return;
	}

	puns_render_classic_header();
}

/**
 * Render editable footer template part (with classic fallback).
 */
function puns_render_footer_area() {
	if ( puns_supports_block_template_parts() ) {
		block_footer_area();
		return;
	}

	puns_render_classic_footer();
}

/**
 * Classic PHP header markup (fallback).
 */
function puns_render_classic_header() {
	?>
	<header class="puns-header">
		<div class="puns-container puns-header__inner">
			<?php puns_the_header_logo(); ?>

			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav class="puns-header__nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'puns' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'items_wrap'     => '%3$s',
							'depth'          => 1,
							'fallback_cb'    => false,
							'walker'         => new Puns_Nav_Walker(),
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<div class="puns-header__actions">
				<?php if ( has_nav_menu( 'primary' ) ) : ?>
					<button
						class="puns-header__menu-toggle puns-icon"
						type="button"
						aria-label="<?php esc_attr_e( 'Open menu', 'puns' ); ?>"
						aria-expanded="false"
						aria-controls="puns-mobile-nav"
						data-puns-menu-toggle
						data-label-open="<?php esc_attr_e( 'Open menu', 'puns' ); ?>"
						data-label-close="<?php esc_attr_e( 'Close menu', 'puns' ); ?>"
					>menu</button>
				<?php endif; ?>
				<?php echo puns_header_search_shortcode(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo puns_header_subscribe_shortcode(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</header>

	<?php if ( has_nav_menu( 'primary' ) ) : ?>
		<div class="puns-mobile-nav" id="puns-mobile-nav" hidden>
			<div class="puns-mobile-nav__backdrop" data-puns-menu-close tabindex="-1" aria-hidden="true"></div>
			<nav class="puns-mobile-nav__panel" aria-label="<?php esc_attr_e( 'Mobile navigation', 'puns' ); ?>">
				<div class="puns-mobile-nav__head">
					<span class="puns-mobile-nav__title"><?php esc_html_e( 'Menu', 'puns' ); ?></span>
					<button class="puns-mobile-nav__close puns-icon" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'puns' ); ?>" data-puns-menu-close>close</button>
				</div>
				<div class="puns-mobile-nav__links">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'items_wrap'     => '%3$s',
							'depth'          => 1,
							'fallback_cb'    => false,
							'walker'         => new Puns_Mobile_Nav_Walker(),
						)
					);
					?>
				</div>
			</nav>
		</div>
	<?php endif; ?>
	<?php
}

/**
 * Classic PHP footer markup (fallback).
 */
function puns_render_classic_footer() {
	?>
	<footer class="puns-footer">
		<div class="puns-container puns-footer__inner">
			<div class="puns-footer__row">
				<div class="puns-footer__brand">
					<?php puns_the_footer_logo(); ?>
					<?php puns_the_footer_copyright(); ?>
				</div>

				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<nav class="puns-footer__links" aria-label="<?php esc_attr_e( 'Footer links', 'puns' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer',
								'container'      => false,
								'items_wrap'     => '%3$s',
								'depth'          => 1,
								'fallback_cb'    => false,
								'walker'         => new Puns_Footer_Nav_Walker(),
							)
						);
						?>
					</nav>
				<?php else : ?>
					<nav class="puns-footer__links" aria-label="<?php esc_attr_e( 'Footer links', 'puns' ); ?>">
						<a class="puns-footer__link" href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'puns' ); ?></a>
						<a class="puns-footer__link" href="<?php echo esc_url( get_bloginfo( 'rss2_url' ) ); ?>"><?php esc_html_e( 'RSS Feed', 'puns' ); ?></a>
					</nav>
				<?php endif; ?>

				<?php puns_the_footer_social(); ?>
			</div>
		</div>
	</footer>
	<?php
}
