<?php
/**
 * Compact search result item for AJAX search modal.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<li class="puns-search-results__item">
	<a class="puns-search-results__link" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'puns-sidebar', array( 'class' => 'puns-search-results__thumb' ) ); ?>
		<?php else : ?>
			<div class="puns-search-results__thumb puns-search-results__thumb--placeholder" aria-hidden="true"></div>
		<?php endif; ?>
		<span class="puns-search-results__content">
			<span class="puns-search-results__title"><?php the_title(); ?></span>
			<span class="puns-search-results__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 14 ) ); ?></span>
			<time class="puns-search-results__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
		</span>
	</a>
</li>
