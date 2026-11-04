<?php
/**
 * Static page template.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

<main id="puns-main-content" class="puns-main puns-article-layout puns-container">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'puns-article' ); ?>>
			<h1 class="puns-article__title"><?php the_title(); ?></h1>
			<div class="puns-article__body entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php
get_footer();
