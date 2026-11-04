<?php
/**
 * Post card partial for grids.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_the_category();
$cat        = ! empty( $categories ) ? $categories[0] : null;
$tag_class  = puns_category_tag_class( $cat );
?>

<a class="puns-card" href="<?php the_permalink(); ?>">
	<div class="puns-card__media">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'puns-card', array( 'class' => 'puns-card__image' ) ); ?>
		<?php else : ?>
			<div class="puns-card__image" style="background-color: var(--puns-mint); min-height: 100%;"></div>
		<?php endif; ?>
	</div>
	<div class="puns-card__body">
		<?php if ( $cat ) : ?>
			<span class="puns-card__tag <?php echo esc_attr( $tag_class ); ?>"><?php echo esc_html( $cat->name ); ?></span>
		<?php endif; ?>
		<h3 class="puns-card__title"><?php the_title(); ?></h3>
		<p class="puns-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<div class="puns-card__meta">
			<div class="puns-card__meta-left">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'M j' ) ); ?></time>
				<span class="puns-card__meta-dot" aria-hidden="true"></span>
				<span>
					<?php
					/* translators: %d: reading time in minutes */
					printf( esc_html__( '%d min read', 'puns' ), puns_reading_time() );
					?>
				</span>
			</div>
			<span class="puns-card__arrow puns-icon" aria-hidden="true">arrow_forward</span>
		</div>
	</div>
</a>
