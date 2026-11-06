<?php
/**
 * Blog index / home template.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$featured_post = puns_get_featured_post();
$featured_id   = $featured_post ? $featured_post->ID : 0;
?>

<main class="puns-main">
	<h1 class="puns-page-title"><?php echo esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) ?: get_bloginfo( 'name' ) ); ?></h1>

	<?php if ( $featured_post ) : ?>
		<?php
		setup_postdata( $featured_post );
		$categories = get_the_category( $featured_id );
		$cat        = ! empty( $categories ) ? $categories[0] : null;
		?>
		<a class="puns-hero puns-container" href="<?php echo esc_url( get_permalink( $featured_id ) ); ?>">
			<div class="puns-hero__grid">
				<div class="puns-hero__content">
					<div class="puns-hero__eyebrow">
						<span class="puns-hero__eyebrow-text"><?php esc_html_e( "Editor's Choice", 'puns' ); ?></span>
						<span class="puns-hero__eyebrow-line" aria-hidden="true"></span>
					</div>
					<h2 class="puns-hero__title"><?php echo esc_html( get_the_title( $featured_id ) ); ?></h2>
					<p class="puns-hero__description"><?php echo esc_html( get_the_excerpt( $featured_id ) ); ?></p>
					<div class="puns-hero__author">
						<img
							class="puns-hero__avatar"
							src="<?php echo esc_url( puns_author_avatar_url( (int) get_post_field( 'post_author', $featured_id ) ) ); ?>"
							alt="<?php echo esc_attr( get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $featured_id ) ) ); ?>"
							width="44"
							height="44"
						>
						<div class="puns-hero__author-info">
							<span class="puns-hero__author-name"><?php echo esc_html( get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $featured_id ) ) ); ?></span>
							<span class="puns-hero__author-meta">
								<?php
								if ( $cat ) {
									echo esc_html( $cat->name );
									echo ' &bull; ';
								}
								/* translators: %d: reading time in minutes */
								printf( esc_html__( '%d min read', 'puns' ), puns_reading_time( $featured_id ) );
								?>
							</span>
						</div>
					</div>
					<span class="puns-btn puns-btn--outline">
						<?php esc_html_e( 'Read More', 'puns' ); ?>
					</span>
				</div>
				<figure class="puns-hero__media">
					<?php if ( has_post_thumbnail( $featured_id ) ) { ?>
						<?php echo get_the_post_thumbnail( $featured_id, 'large', array( 'class' => 'puns-article__figure-image' ) ); ?>
					<?php } ?>
				</figure>
			</div>
		</a>
		<?php
		wp_reset_postdata();
	endif;
	?>

	<section class="puns-posts puns-container">
		<header class="puns-posts__header">
			<h2 class="puns-posts__title"><?php echo esc_html( puns_get_home_posts_title() ); ?></h2>
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
			<p><?php esc_html_e( 'No posts found. Publish your first essay to get started.', 'puns' ); ?></p>
		<?php endif; ?>
	</section>

</main>

<?php
get_footer();
