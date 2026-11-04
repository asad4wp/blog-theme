<?php
/**
 * Breadcrumb navigation.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! puns_show_breadcrumbs() ) {
	return;
}

if ( puns_rank_math_is_active() && puns_rank_math_breadcrumbs_enabled() ) {
	puns_the_rank_math_breadcrumbs();
	return;
}

$crumbs = puns_get_breadcrumbs();
$last   = count( $crumbs ) - 1;
?>

<nav class="puns-breadcrumbs puns-container" aria-label="<?php esc_attr_e( 'Breadcrumb', 'puns' ); ?>">
	<ol class="puns-breadcrumbs__list">
		<?php foreach ( $crumbs as $index => $crumb ) : ?>
			<li class="puns-breadcrumbs__item">
				<?php if ( $index < $last ) : ?>
					<a class="puns-breadcrumbs__link" href="<?php echo esc_url( $crumb['url'] ); ?>">
						<?php echo esc_html( $crumb['name'] ); ?>
					</a>
				<?php else : ?>
					<span class="puns-breadcrumbs__current" aria-current="page">
						<?php echo esc_html( $crumb['name'] ); ?>
					</span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
