<?php
/**
 * Theme footer.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
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

<?php get_template_part( 'template-parts/subscribe', 'modal' ); ?>
<?php get_template_part( 'template-parts/search', 'modal' ); ?>

<?php wp_footer(); ?>
</body>
</html>
