<?php
/**
 * Template Name: Home
 * Template Post Type: page
 *
 * Editable Gutenberg homepage.
 * Featured hero + latest posts always render from theme helpers.
 * Any other blocks on the page stay fully editable in the middle.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="puns-main-content" class="puns-main puns-main--home">
	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/home', 'featured' );

		$content = get_the_content();
		$content = function_exists( 'puns_normalize_shortcode_quotes' )
			? puns_normalize_shortcode_quotes( $content )
			: $content;
		$content = apply_filters( 'the_content', $content );

		// Remove leftover raw shortcode paragraphs from older editor content.
		$content = preg_replace(
			'/<p[^>]*>\s*\[(?:puns_featured_hero|puns_latest_posts)[^\]]*\]\s*<\/p>/i',
			'',
			$content
		);
		$content = preg_replace(
			'/\[(?:puns_featured_hero|puns_latest_posts)[^\]]*\]/i',
			'',
			$content
		);
		$content = trim( (string) $content );

		if ( '' !== $content && false === strpos( wp_strip_all_tags( $content ), 'No posts found' ) ) {
			?>
			<div class="puns-home entry-content">
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered block content. ?>
			</div>
			<?php
		}

		get_template_part( 'template-parts/home', 'posts' );
	endwhile;
	?>
</main>

<?php
get_footer();
