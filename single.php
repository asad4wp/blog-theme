<?php
/**
 * Single post template.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="puns-main-content" class="puns-main puns-article-layout puns-container">
	<div class="puns-article-layout__grid">

		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'single' );
		endwhile;
		?>

		<?php get_template_part( 'template-parts/sidebar' ); ?>

	</div>
</main>

<?php get_template_part( 'template-parts/recommended-posts' ); ?>

<?php
get_footer();
