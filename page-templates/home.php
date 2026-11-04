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

		// Use raw post content and strip theme shortcodes BEFORE the_content,
		// otherwise [puns_featured_hero] / [puns_latest_posts] render a second copy.
		$post    = get_post();
		$content = $post instanceof WP_Post ? (string) $post->post_content : '';
		$content = function_exists( 'puns_normalize_shortcode_quotes' )
			? puns_normalize_shortcode_quotes( $content )
			: $content;

		$content = preg_replace(
			'/<!--\s*wp:shortcode\s*-->\s*\[(?:puns_featured_hero|puns_latest_posts)[^\]]*\]\s*<!--\s*\/wp:shortcode\s*-->/i',
			'',
			$content
		);
		$content = preg_replace(
			'/\[(?:puns_featured_hero|puns_latest_posts)[^\]]*\]/i',
			'',
			$content
		);
		$content = trim( (string) $content );

		if ( '' !== $content ) {
			$content = apply_filters( 'the_content', $content );
			$content = trim( (string) $content );
		}

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
