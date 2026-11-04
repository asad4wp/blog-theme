<?php
/**
 * Sidebar partial for single posts.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<aside class="puns-sidebar">
	<div class="puns-sidebar__sticky">
		<section class="puns-sidebar__newsletter" aria-labelledby="puns-sidebar-newsletter-title">
			<h3 class="puns-sidebar__newsletter-title" id="puns-sidebar-newsletter-title"><?php esc_html_e( 'The Power of Silence', 'puns' ); ?></h3>
			<p class="puns-sidebar__newsletter-desc"><?php esc_html_e( 'The latest news, articles, and resources, sent to your inbox weekly.', 'puns' ); ?></p>
			<form class="puns-sidebar__newsletter-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post" novalidate>
				<label class="screen-reader-text" for="puns-sidebar-newsletter-email"><?php esc_html_e( 'Email address', 'puns' ); ?></label>
				<input
					class="puns-sidebar__input"
					id="puns-sidebar-newsletter-email"
					type="email"
					name="newsletter_email"
					placeholder="<?php esc_attr_e( 'Your email address', 'puns' ); ?>"
					autocomplete="email"
					required
				>
				<button class="puns-sidebar__submit" type="submit"><?php esc_html_e( 'Get Started', 'puns' ); ?></button>
			</form>
		</section>
	</div>
</aside>
