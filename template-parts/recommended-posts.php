<?php
/**
 * Recommended posts section for single posts.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$recommended = new WP_Query(
	array(
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
		'post_status'         => 'publish',
	)
);

if ( ! $recommended->have_posts() ) {
	return;
}
?>

<section class="puns-recommended">
	<div class="puns-container">
		<div class="puns-recommended__header">
			<h2 class="puns-recommended__title"><?php esc_html_e( 'Recommended for You', 'puns' ); ?></h2>
			<a class="puns-recommended__view-all" href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/' ) ); ?>">
				<?php esc_html_e( 'View All', 'puns' ); ?>
			</a>
		</div>
		<div class="puns-posts__grid">
			<?php
			while ( $recommended->have_posts() ) :
				$recommended->the_post();
				get_template_part( 'template-parts/content', 'card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
