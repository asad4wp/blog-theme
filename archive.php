<?php
/**
 * Archive template (categories, tags, dates, author).
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$archive_eyebrow = __( 'Archive', 'puns' );

if ( is_category() ) {
	$archive_eyebrow = __( 'Category', 'puns' );
} elseif ( is_tag() ) {
	$archive_eyebrow = __( 'Tag', 'puns' );
} elseif ( is_author() ) {
	$archive_eyebrow = __( 'Author', 'puns' );
} elseif ( is_year() || is_month() || is_day() ) {
	$archive_eyebrow = __( 'Date', 'puns' );
} elseif ( is_post_type_archive() ) {
	$archive_eyebrow = __( 'Archive', 'puns' );
}
?>

<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

<main id="puns-main-content" class="puns-main">

	<section class="puns-archive-hero puns-container">
		<header class="puns-archive-hero__header">
			<div class="puns-archive-hero__eyebrow">
				<span class="puns-archive-hero__eyebrow-line" aria-hidden="true"></span>
				<span class="puns-archive-hero__eyebrow-text"><?php echo esc_html( $archive_eyebrow ); ?></span>
			</div>
			<h1 class="puns-archive-hero__title"><?php the_archive_title(); ?></h1>
			<?php the_archive_description( '<p class="puns-archive-hero__description">', '</p>' ); ?>
		</header>
	</section>

	<section class="puns-posts puns-container puns-posts--archive">
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
			<p><?php esc_html_e( 'No posts found in this archive.', 'puns' ); ?></p>
		<?php endif; ?>
	</section>

</main>

<?php
get_footer();
