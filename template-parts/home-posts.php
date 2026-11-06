<?php
/**
 * Homepage latest posts grid.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_title = ! isset( $args['show_title'] ) || ! empty( $args['show_title'] );
$title      = isset( $args['title'] ) ? (string) $args['title'] : '';
$exclude_featured = ! isset( $args['exclude_featured'] ) || ! empty( $args['exclude_featured'] );

if ( '' === trim( $title ) && function_exists( 'puns_get_home_posts_title' ) ) {
	$title = puns_get_home_posts_title();
}

$exclude = 0;
if ( $exclude_featured && function_exists( 'puns_home_featured_post_id' ) ) {
	$exclude = puns_home_featured_post_id();
}

$per = function_exists( 'puns_posts_per_page' ) ? puns_posts_per_page() : 9;

$query_args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => $per,
	'ignore_sticky_posts' => true,
	'paged'               => max( 1, (int) get_query_var( 'paged' ) ),
);

if ( $exclude ) {
	$query_args['post__not_in'] = array( $exclude );
}

$posts_query = new WP_Query( $query_args );
?>
<section class="puns-posts puns-container">
	<?php if ( $show_title && '' !== trim( $title ) ) : ?>
		<header class="puns-posts__header">
			<h2 class="puns-posts__title"><?php echo esc_html( $title ); ?></h2>
		</header>
	<?php endif; ?>

	<?php if ( $posts_query->have_posts() ) : ?>
		<div class="puns-posts__grid">
			<?php
			while ( $posts_query->have_posts() ) :
				$posts_query->the_post();
				get_template_part( 'template-parts/content', 'card' );
			endwhile;
			?>
		</div>

		<?php if ( (int) $posts_query->max_num_pages > 1 ) : ?>
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
<?php
wp_reset_postdata();
