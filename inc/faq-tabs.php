<?php
/**
 * Convert FAQ heading sections in post content into tabs.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether text is an FAQ section title.
 *
 * @param string $text Heading text.
 * @return bool
 */
function puns_is_faq_heading( $text ) {
	$text = trim( wp_strip_all_tags( (string) $text ) );

	return (bool) preg_match( '/\b(faqs?|frequently\s+asked\s+questions?)\b/i', $text );
}

/**
 * Whether text looks like an FAQ question.
 *
 * @param string $text Heading text.
 * @return bool
 */
function puns_is_faq_question( $text ) {
	$text = trim( wp_strip_all_tags( (string) $text ) );

	if ( '' === $text || puns_is_faq_heading( $text ) ) {
		return false;
	}

	return (bool) preg_match( '/\?\s*$/u', $text ) || (bool) preg_match( '/^q[:.\s]/i', $text );
}

/**
 * Clean question label for tab button.
 *
 * @param string $text Raw question.
 * @return string
 */
function puns_faq_tab_label( $text ) {
	$text = trim( wp_strip_all_tags( (string) $text ) );
	$text = preg_replace( '/^q[:.\s]+/i', '', $text );

	return trim( (string) $text );
}

/**
 * Build FAQ tabs markup.
 *
 * @param string                                           $title     Section title inner HTML.
 * @param string                                           $title_tag Heading tag.
 * @param string                                           $intro     Optional intro HTML.
 * @param array<int, array{question:string,answer:string}> $pairs     Q&A pairs.
 * @return string
 */
function puns_faq_tabs_html( $title, $title_tag, $intro, $pairs ) {
	static $instance = 0;
	++$instance;

	$id_base = 'puns-faq-tabs-' . $instance;
	$nav     = '';
	$panels  = '';

	foreach ( $pairs as $index => $pair ) {
		$tab_id   = $id_base . '-tab-' . $index;
		$panel_id = $id_base . '-panel-' . $index;
		$selected = 0 === $index;
		$label    = puns_faq_tab_label( $pair['question'] );

		$nav .= sprintf(
			'<button class="puns-faq-tabs__tab%1$s" type="button" role="tab" id="%2$s" aria-controls="%3$s" aria-selected="%4$s" tabindex="%5$s">%6$s</button>',
			$selected ? ' is-active' : '',
			esc_attr( $tab_id ),
			esc_attr( $panel_id ),
			$selected ? 'true' : 'false',
			$selected ? '0' : '-1',
			esc_html( $label )
		);

		$panels .= sprintf(
			'<div class="puns-faq-tabs__panel%1$s" role="tabpanel" id="%2$s" aria-labelledby="%3$s"%4$s>%5$s</div>',
			$selected ? ' is-active' : '',
			esc_attr( $panel_id ),
			esc_attr( $tab_id ),
			$selected ? '' : ' hidden',
			$pair['answer']
		);
	}

	$title_tag = in_array( $title_tag, array( 'h2', 'h3', 'h4' ), true ) ? $title_tag : 'h2';
	$intro_html = '' !== trim( $intro )
		? '<div class="puns-faq-tabs__intro">' . $intro . '</div>'
		: '';

	return sprintf(
		'<section class="puns-faq-tabs" data-puns-faq-tabs>
			<%1$s class="puns-faq-tabs__title">%2$s</%1$s>
			%3$s
			<div class="puns-faq-tabs__layout">
				<div class="puns-faq-tabs__nav" role="tablist" aria-label="%4$s">%5$s</div>
				<div class="puns-faq-tabs__panels">%6$s</div>
			</div>
		</section>',
		$title_tag,
		$title,
		$intro_html,
		esc_attr__( 'Frequently asked questions', 'puns' ),
		$nav,
		$panels
	);
}

/**
 * Convert FAQ sections in HTML to tabs.
 *
 * @param string $content Post HTML.
 * @return string
 */
function puns_render_faq_tabs( $content ) {
	static $doing = false;

	if ( $doing || ! is_singular( 'post' ) || is_admin() || '' === trim( (string) $content ) ) {
		return $content;
	}

	$doing = true;

	if ( ! puns_is_faq_heading( $content ) && false === stripos( $content, 'faq' ) ) {
		$doing = false;
		return $content;
	}

	if ( ! class_exists( 'DOMDocument' ) ) {
		$doing = false;
		return $content;
	}

	$dom      = new DOMDocument();
	$previous = libxml_use_internal_errors( true );
	$loaded   = $dom->loadHTML(
		'<?xml encoding="utf-8" ?><div id="puns-faq-root">' . $content . '</div>',
		LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
	);
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );

	if ( ! $loaded ) {
		$doing = false;
		return $content;
	}

	$root = $dom->getElementById( 'puns-faq-root' );

	if ( ! $root ) {
		$doing = false;
		return $content;
	}

	$changed = true;

	while ( $changed ) {
		$changed  = false;
		$children = array();

		foreach ( $root->childNodes as $child ) {
			$children[] = $child;
		}

		$count = count( $children );

		for ( $i = 0; $i < $count; $i++ ) {
			$node = $children[ $i ];

			if ( ! ( $node instanceof DOMElement ) || ! preg_match( '/^h[2-4]$/i', $node->tagName ) ) {
				continue;
			}

			$title_text = trim( $node->textContent );

			if ( ! puns_is_faq_heading( $title_text ) ) {
				continue;
			}

			// Skip already converted sections.
			if ( $node->parentNode instanceof DOMElement && $node->parentNode->hasAttribute( 'data-puns-faq-tabs' ) ) {
				continue;
			}

			$title_html = '';
			foreach ( $node->childNodes as $child ) {
				$title_html .= $dom->saveHTML( $child );
			}

			$title_tag = strtolower( $node->tagName );
			$intro     = '';
			$pairs     = array();
			$j         = $i + 1;

			while ( $j < $count ) {
				$current = $children[ $j ];

				if ( $current instanceof DOMElement && preg_match( '/^h[2-4]$/i', $current->tagName ) ) {
					$heading_text = trim( $current->textContent );

					if ( puns_is_faq_heading( $heading_text ) || ! puns_is_faq_question( $heading_text ) ) {
						break;
					}

					$answer = '';
					$k      = $j + 1;

					while ( $k < $count ) {
						$next = $children[ $k ];

						if ( $next instanceof DOMElement && preg_match( '/^h[2-4]$/i', $next->tagName ) ) {
							break;
						}

						$answer .= $dom->saveHTML( $next );
						++$k;
					}

					$answer = trim( $answer );

					if ( '' !== $answer ) {
						$pairs[] = array(
							'question' => $heading_text,
							'answer'   => $answer,
						);
					}

					$j = $k;
					continue;
				}

				if ( empty( $pairs ) ) {
					$intro .= $dom->saveHTML( $current );
					++$j;
					continue;
				}

				break;
			}

			if ( count( $pairs ) < 2 ) {
				continue;
			}

			$end = $j - 1;

			while ( $end > $i && $children[ $end ] instanceof DOMText && '' === trim( $children[ $end ]->textContent ) ) {
				--$end;
			}

			$tabs_html = puns_faq_tabs_html( $title_html, $title_tag, $intro, $pairs );

			$tmp      = new DOMDocument();
			$tmp_prev = libxml_use_internal_errors( true );
			$tmp->loadHTML( '<?xml encoding="utf-8" ?>' . $tabs_html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
			libxml_clear_errors();
			libxml_use_internal_errors( $tmp_prev );

			$tmp_root = $tmp->getElementsByTagName( 'section' )->item( 0 );

			if ( ! $tmp_root ) {
				continue;
			}

			$imported = $dom->importNode( $tmp_root, true );
			$root->insertBefore( $imported, $children[ $i ] );

			for ( $r = $i; $r <= $end; $r++ ) {
				if ( isset( $children[ $r ] ) && $children[ $r ]->parentNode === $root ) {
					$root->removeChild( $children[ $r ] );
				}
			}

			$changed = true;
			break;
		}
	}

	$html = '';
	foreach ( $root->childNodes as $child ) {
		$html .= $dom->saveHTML( $child );
	}

	$doing = false;

	return '' !== $html ? $html : $content;
}
add_filter( 'the_content', 'puns_render_faq_tabs', 13 );
