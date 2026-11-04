<?php
/**
 * Subscribe newsletter popup.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div
	class="puns-subscribe-modal"
	id="puns-subscribe-modal"
	aria-hidden="true"
	role="dialog"
	aria-modal="true"
	aria-labelledby="puns-subscribe-title"
>
	<div class="puns-subscribe-modal__overlay" data-puns-subscribe-close tabindex="-1"></div>

	<div class="puns-subscribe-modal__dialog">
		<button class="puns-subscribe-modal__close puns-icon" type="button" data-puns-subscribe-close aria-label="<?php esc_attr_e( 'Close subscribe popup', 'puns' ); ?>">close</button>

		<div class="puns-subscribe-modal__panel">
			<h2 class="puns-subscribe-modal__title" id="puns-subscribe-title"><?php esc_html_e( 'The Power of Silence', 'puns' ); ?></h2>
			<p class="puns-subscribe-modal__desc"><?php esc_html_e( 'The latest news, articles, and resources, sent to your inbox weekly.', 'puns' ); ?></p>

			<form class="puns-subscribe-modal__form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post" novalidate>
				<label class="screen-reader-text" for="puns-subscribe-email"><?php esc_html_e( 'Email address', 'puns' ); ?></label>
				<input
					class="puns-subscribe-modal__input"
					id="puns-subscribe-email"
					type="email"
					name="newsletter_email"
					placeholder="<?php esc_attr_e( 'Your email address', 'puns' ); ?>"
					required
					autocomplete="email"
				>
				<button class="puns-subscribe-modal__submit" type="submit"><?php esc_html_e( 'Get Started', 'puns' ); ?></button>
			</form>

			<p class="puns-subscribe-modal__success" hidden><?php esc_html_e( 'Thanks for subscribing!', 'puns' ); ?></p>

			<a class="puns-subscribe-modal__rss" href="<?php echo esc_url( get_bloginfo( 'rss2_url' ) ); ?>">
				<?php esc_html_e( 'Or subscribe via RSS Feed', 'puns' ); ?>
			</a>
		</div>
	</div>
</div>
