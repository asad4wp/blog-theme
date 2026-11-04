<?php
/**
 * Homepage featured hero.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$featured_post = isset( $args['post'] ) && $args['post'] instanceof WP_Post
	? $args['post']
	: puns_get_featured_post();

if ( ! $featured_post ) {
	return;
}

$featured_id = (int) $featured_post->ID;
$eyebrow     = isset( $args['eyebrow'] ) ? (string) $args['eyebrow'] : __( "Editor's Choice", 'puns' );
$button      = isset( $args['button'] ) ? (string) $args['button'] : __( 'Read More', 'puns' );

setup_postdata( $featured_post );
$categories = get_the_category( $featured_id );
$cat        = ! empty( $categories ) ? $categories[0] : null;
?>
<a class="puns-hero puns-container" href="<?php echo esc_url( get_permalink( $featured_id ) ); ?>">
	<div class="puns-hero__grid">
		<div class="puns-hero__content">
			<div class="puns-hero__eyebrow">
				<span class="puns-hero__eyebrow-text"><?php echo esc_html( $eyebrow ); ?></span>
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
				<?php echo esc_html( $button ); ?>
			</span>
		</div>
		<figure class="puns-hero__media">
			<?php if ( has_post_thumbnail( $featured_id ) ) : ?>
				<?php echo get_the_post_thumbnail( $featured_id, 'large', array( 'class' => 'puns-article__figure-image' ) ); ?>
			<?php endif; ?>
		</figure>
	</div>
</a>
<?php
wp_reset_postdata();
