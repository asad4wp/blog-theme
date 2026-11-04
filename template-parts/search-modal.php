<?php
/**
 * AJAX search modal.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div
	class="puns-search-modal"
	id="puns-search-modal"
	aria-hidden="true"
	role="dialog"
	aria-modal="true"
	aria-labelledby="puns-search-title"
>
	<div class="puns-search-modal__overlay" data-puns-search-close tabindex="-1"></div>

	<div class="puns-search-modal__dialog">
		<button class="puns-search-modal__close puns-icon" type="button" data-puns-search-close aria-label="<?php esc_attr_e( 'Close search', 'puns' ); ?>">close</button>

		<div class="puns-search-modal__panel">
			<h2 class="puns-search-modal__title" id="puns-search-title"><?php esc_html_e( 'Search', 'puns' ); ?></h2>

			<form class="puns-search-modal__form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search">
				<label class="screen-reader-text" for="puns-search-input"><?php esc_html_e( 'Search for:', 'puns' ); ?></label>
				<span class="puns-search-modal__field">
					<span class="puns-search-modal__icon puns-icon" aria-hidden="true">search</span>
					<input
						class="puns-search-modal__input"
						id="puns-search-input"
						type="search"
						name="s"
						placeholder="<?php esc_attr_e( 'Search essays, topics, keywords...', 'puns' ); ?>"
						autocomplete="off"
						spellcheck="false"
					>
				</span>
			</form>

			<div class="puns-search-modal__results" id="puns-search-results" aria-live="polite">
				<p class="puns-search-results__hint"><?php esc_html_e( 'Start typing to find posts.', 'puns' ); ?></p>
			</div>
		</div>
	</div>
</div>
