<?php
/**
 * Single post content partial.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$author_id = (int) get_the_author_meta( 'ID' );
$tags      = get_the_tags();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'puns-article' ); ?>>
	<h1 class="puns-article__title"><?php the_title(); ?></h1>

	<div class="puns-article__meta">
		<div class="puns-article__meta-author">
			<img
				class="puns-article__meta-avatar"
				src="<?php echo esc_url( puns_author_avatar_url( $author_id ) ); ?>"
				alt=""
				width="36"
				height="36"
				loading="lazy"
				decoding="async"
			>
			<div>
				<p class="puns-article__meta-name">
					<a rel="author">
						<?php the_author(); ?>
					</a>
				</p>
				<p class="puns-article__meta-date">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"> <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
					<span class="puns-article__meta-sep" aria-hidden="true">&bull;</span>
					<span>
						<?php
						/* translators: %d: reading time in minutes */
						printf( esc_html__( '%d min read', 'puns' ), puns_reading_time() );
						?>
					</span>
					<?php if ( get_the_modified_date( 'c' ) !== get_the_date( 'c' ) ) : ?>
						<span class="puns-article__meta-sep" aria-hidden="true">&bull;</span>
						<span class="puns-article__updated">
							<?php esc_html_e( 'Updated', 'puns' ); ?>
							<time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"> <?php echo esc_html( get_the_modified_date( 'M j, Y' ) ); ?></time>
						</span>
					<?php endif; ?>
				</p>
			</div>
		</div>
		<button class="puns-article__meta-share puns-icon" type="button" aria-label="<?php esc_attr_e( 'Share', 'puns' ); ?>" onclick="navigator.share && navigator.share({title: document.title, url: location.href})">share</button>
	</div>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="puns-article__figure">
			<?php
			the_post_thumbnail(
				'large',
				array(
					'class'    => 'puns-article__figure-image',
					'itemprop' => 'image',
				)
			);
			?>
		</figure>
	<?php endif; ?>

	<div class="puns-article__body entry-content">
		<?php the_content(); ?>

		<?php if ( $tags ) : ?>
			<div class="puns-article__tags">
				<?php foreach ( $tags as $tag ) : ?>
					<a class="puns-article__tag" href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="puns-article__bio">
			<img
				class="puns-article__bio-avatar"
				src="<?php echo esc_url( puns_author_avatar_url( $author_id ) ); ?>"
				alt="<?php echo esc_attr( get_the_author() ); ?>"
				width="64"
				height="64"
			>
			<div>
				<h4 class="puns-article__bio-name"><?php the_author(); ?></h4>
				<div class="puns-article__bio-text"><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></div>
			</div>
		</div>
	</div>
</article>
