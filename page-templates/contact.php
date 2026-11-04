<?php
/**
 * Template Name: Contact Us
 * Template Post Type: page
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
		get_template_part( 'template-parts/static/contact' );
	endwhile;
	?>
</main>

<?php
get_footer();
