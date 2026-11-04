<?php
/**
 * Theme header.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="puns-skip-link screen-reader-text" href="#puns-main-content"><?php esc_html_e( 'Skip to content', 'puns' ); ?></a>

<header class="puns-header">
	<div class="puns-container puns-header__inner">
		<?php puns_the_header_logo(); ?>

		<nav class="puns-header__nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'puns' ); ?>">
			<?php puns_the_primary_nav_links( 'puns-header__nav-link' ); ?>
		</nav>

		<div class="puns-header__actions">
			<button
				class="puns-header__menu-toggle"
				type="button"
				aria-label="<?php esc_attr_e( 'Open menu', 'puns' ); ?>"
				aria-expanded="false"
				aria-controls="puns-mobile-nav"
				data-puns-menu-toggle
				data-label-open="<?php esc_attr_e( 'Open menu', 'puns' ); ?>"
				data-label-close="<?php esc_attr_e( 'Close menu', 'puns' ); ?>"
			>
				<span class="puns-header__menu-bars" aria-hidden="true">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>
			<button class="puns-header__search puns-icon puns-search-open" type="button" aria-label="<?php esc_attr_e( 'Search', 'puns' ); ?>">search</button>
			<button class="puns-btn puns-btn--solid puns-subscribe-open" type="button">
				<?php esc_html_e( 'Subscribe', 'puns' ); ?>
			</button>
		</div>
	</div>
</header>

<div class="puns-mobile-nav" id="puns-mobile-nav" hidden>
	<nav class="puns-mobile-nav__panel" aria-label="<?php esc_attr_e( 'Mobile navigation', 'puns' ); ?>">
		<div class="puns-mobile-nav__top">
			<div class="puns-mobile-nav__brand">
				<?php puns_the_header_logo(); ?>
			</div>
			<button
				class="puns-mobile-nav__close"
				type="button"
				aria-label="<?php esc_attr_e( 'Close menu', 'puns' ); ?>"
				data-puns-menu-close
			>
				<span aria-hidden="true"></span>
				<span aria-hidden="true"></span>
			</button>
		</div>

		<div class="puns-mobile-nav__links">
			<?php puns_the_primary_nav_links( 'puns-mobile-nav__link' ); ?>
		</div>

		<button class="puns-mobile-nav__subscribe puns-subscribe-open" type="button">
			<?php esc_html_e( 'Subscribe', 'puns' ); ?>
		</button>
	</nav>
</div>
