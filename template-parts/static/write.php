<?php
/**
 * Write with Us designed page content.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$excerpt = has_excerpt() ? get_the_excerpt() : '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'puns-article puns-static puns-static--write' ); ?>>
	<header class="puns-static__hero">
		<div class="puns-static__eyebrow">
			<span class="puns-static__eyebrow-line" aria-hidden="true"></span>
			<span class="puns-static__eyebrow-text"><?php esc_html_e( 'Contributors', 'puns' ); ?></span>
		</div>
		<h1 class="puns-article__title puns-static__title"><?php the_title(); ?></h1>
		<p class="puns-static__lede">
			<?php
			echo $excerpt
				? esc_html( $excerpt )
				: esc_html__( 'Share original puns, jokes, and short humor pieces with our readers. Pitch an idea — if it fits, we’ll help you polish and publish it.', 'puns' );
			?>
		</p>
	</header>

	<?php if ( get_the_content() ) : ?>
		<div class="puns-article__body entry-content puns-static__editor">
			<?php the_content(); ?>
		</div>
	<?php endif; ?>

	<section class="puns-static__section" aria-labelledby="puns-write-steps">
		<h2 id="puns-write-steps" class="puns-static__heading"><?php esc_html_e( 'How it works', 'puns' ); ?></h2>
		<ol class="puns-static__steps">
			<li class="puns-static__step">
				<span class="puns-static__step-num" aria-hidden="true">1</span>
				<div>
					<h3 class="puns-static__card-title"><?php esc_html_e( 'Pitch your idea', 'puns' ); ?></h3>
					<p class="puns-static__card-text"><?php esc_html_e( 'Tell us the premise, format, and why it will make someone laugh out loud.', 'puns' ); ?></p>
				</div>
			</li>
			<li class="puns-static__step">
				<span class="puns-static__step-num" aria-hidden="true">2</span>
				<div>
					<h3 class="puns-static__card-title"><?php esc_html_e( 'We review fast', 'puns' ); ?></h3>
					<p class="puns-static__card-text"><?php esc_html_e( 'Our editors look for originality, clarity, and a clean punchline — not just shock value.', 'puns' ); ?></p>
				</div>
			</li>
			<li class="puns-static__step">
				<span class="puns-static__step-num" aria-hidden="true">3</span>
				<div>
					<h3 class="puns-static__card-title"><?php esc_html_e( 'Publish together', 'puns' ); ?></h3>
					<p class="puns-static__card-text"><?php esc_html_e( 'If it’s a fit, we’ll collaborate on edits, byline, and the best day to go live.', 'puns' ); ?></p>
				</div>
			</li>
		</ol>
	</section>

	<section class="puns-static__section" aria-labelledby="puns-write-guidelines">
		<h2 id="puns-write-guidelines" class="puns-static__heading"><?php esc_html_e( 'What we’re looking for', 'puns' ); ?></h2>
		<div class="puns-static__grid puns-static__grid--two">
			<article class="puns-static__card">
				<span class="puns-static__card-icon puns-icon" aria-hidden="true">check_circle</span>
				<h3 class="puns-static__card-title"><?php esc_html_e( 'Yes, please', 'puns' ); ?></h3>
				<ul class="puns-static__list">
					<li><?php esc_html_e( 'Original puns and joke lists', 'puns' ); ?></li>
					<li><?php esc_html_e( 'Short humor essays and observation pieces', 'puns' ); ?></li>
					<li><?php esc_html_e( 'Series ideas you can sustain', 'puns' ); ?></li>
				</ul>
			</article>
			<article class="puns-static__card">
				<span class="puns-static__card-icon puns-icon" aria-hidden="true">cancel</span>
				<h3 class="puns-static__card-title"><?php esc_html_e( 'Not a fit', 'puns' ); ?></h3>
				<ul class="puns-static__list">
					<li><?php esc_html_e( 'Copied jokes or AI dumps without craft', 'puns' ); ?></li>
					<li><?php esc_html_e( 'Hate, harassment, or mean-spirited targeting', 'puns' ); ?></li>
					<li><?php esc_html_e( 'Heavy promo / affiliate pitches', 'puns' ); ?></li>
				</ul>
			</article>
		</div>
	</section>

	<section class="puns-static__section" aria-labelledby="puns-write-form-title">
		<div class="puns-static__panel puns-static__panel--form">
			<span id="puns-write-form-title" class="screen-reader-text"><?php esc_html_e( 'Send your pitch', 'puns' ); ?></span>
			<?php
			puns_the_custom_form(
				'write',
				__( 'Send your pitch', 'puns' ),
				__( 'A few sentences is enough. Include links to your writing if you have them.', 'puns' )
			);
			?>
		</div>
	</section>
</article>
