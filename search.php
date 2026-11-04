<?php
/**
 * Search results template.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

<main id="puns-main-content" class="puns-main">

	<section class="puns-posts puns-container">
		<header class="puns-posts__header">
			<h1 class="puns-posts__title">
				<?php
				/* translators: %s: search query */
				printf( esc_html__( 'Search: %s', 'puns' ), get_search_query() );
				?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="puns-posts__grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'card' );
				endwhile;
				?>
			</div>

			<?php
			global $wp_query;
			if ( $wp_query->max_num_pages > 1 ) :
				?>
				<div class="puns-posts__more">
					<button class="puns-btn puns-btn--solid puns-load-more" type="button">
						<?php esc_html_e( 'Load More', 'puns' ); ?>
					</button>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No results found. Try a different search term.', 'puns' ); ?></p>
		<?php endif; ?>
	</section>

</main>

<?php
get_footer();
