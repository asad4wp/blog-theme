<?php
/**
 * 404 template.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$recent_posts = new WP_Query(
	array(
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'post_status'         => 'publish',
	)
);
?>

<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

<main id="puns-main-content" class="puns-main">
	<section class="puns-not-found puns-container">
		<div class="puns-not-found__panel">
			<div class="puns-not-found__eyebrow">
				<span class="puns-not-found__eyebrow-text"><?php esc_html_e( 'Lost in the archives', 'puns' ); ?></span>
				<span class="puns-not-found__eyebrow-line" aria-hidden="true"></span>
			</div>

			<p class="puns-not-found__code" aria-hidden="true">404</p>

			<h1 class="puns-not-found__title"><?php esc_html_e( 'Page Not Found', 'puns' ); ?></h1>

			<p class="puns-not-found__description">
				<?php esc_html_e( 'The page you are looking for does not exist or has been moved.', 'puns' ); ?>
			</p>

			<div class="puns-not-found__actions">
				<a class="puns-btn puns-btn--solid" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php esc_html_e( 'Back to Home', 'puns' ); ?>
				</a>
			</div>
		</div>
	</section>

	<?php if ( $recent_posts->have_posts() ) : ?>
		<section class="puns-posts puns-container">
			<header class="puns-posts__header">
				<h2 class="puns-posts__title"><?php esc_html_e( 'Maybe try these', 'puns' ); ?></h2>
			</header>

			<div class="puns-posts__grid">
				<?php
				while ( $recent_posts->have_posts() ) :
					$recent_posts->the_post();
					get_template_part( 'template-parts/content', 'card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php
get_footer();
