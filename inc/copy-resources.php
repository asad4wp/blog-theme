<?php
/**
 * Auto copyable resource rows for captions, jokes, and short snippets.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Max characters for auto-detected copy lines. */
if ( ! defined( 'PUNS_COPY_LINE_MAX' ) ) {
	define( 'PUNS_COPY_LINE_MAX', 180 );
}

/**
 * Build a single copy row matching blog-single.html.
 *
 * @param string $text Row text.
 * @return string
 */
function puns_copy_row_html( $text ) {
	$text = trim( (string) $text );
	if ( '' === $text ) {
		return '';
	}

	return sprintf(
		'<div class="puns-article__resource" role="group" tabindex="0"><span class="puns-article__resource-text">%1$s</span><button class="puns-article__copy-btn" type="button" aria-label="%3$s"><span class="puns-article__copy-btn-idle"><span class="puns-icon" aria-hidden="true">content_copy</span><span class="puns-article__copy-btn-label">%2$s</span></span><span class="puns-article__copy-btn-done"><span class="puns-icon" aria-hidden="true">check</span><span class="puns-article__copy-btn-label">%4$s</span></span></button></div>',
		esc_html( $text ),
		esc_html__( 'Copy', 'puns' ),
		esc_attr__( 'Copy text', 'puns' ),
		esc_html__( 'Copied', 'puns' )
	);
}

/**
 * Build the resources wrapper with one row per line.
 *
 * @param string[] $lines Copy lines.
 * @return string
 */
function puns_copy_resources_html( $lines ) {
	$rows = array();

	foreach ( (array) $lines as $line ) {
		$row = puns_copy_row_html( $line );
		if ( '' !== $row ) {
			$rows[] = $row;
		}
	}

	if ( empty( $rows ) ) {
		return '';
	}

	return '<div class="puns-article__resources">' . implode( '', $rows ) . '</div>';
}

/**
 * Whether plain text should become a copy row.
 *
 * @param string $text Text content.
 * @return bool
 */
function puns_is_copy_line( $text ) {
	$text = trim( wp_strip_all_tags( (string) $text ) );

	if ( '' === $text ) {
		return false;
	}

	if ( mb_strlen( $text ) > PUNS_COPY_LINE_MAX ) {
		return false;
	}

	return true;
}

/**
 * Extract list item text from HTML list markup.
 *
 * @param string $html List HTML.
 * @return string[]
 */
function puns_copy_lines_from_list_html( $html ) {
	preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $html, $matches );
	$lines = array();

	foreach ( $matches[1] as $item ) {
		$line = trim( wp_strip_all_tags( $item ) );
		if ( '' !== $line ) {
			$lines[] = $line;
		}
	}

	return $lines;
}

/**
 * Whether a byte offset in $content sits before the first heading (intro area).
 *
 * @param string $content Full post HTML.
 * @param int    $offset  Byte offset of the matched list.
 * @return bool
 */
function puns_position_is_in_intro( $content, $offset ) {
	$before = substr( $content, 0, $offset );

	return ! preg_match( '/<h[1-6]\b/i', $before );
}

/**
 * Container tags whose opening markup marks a "do not touch" zone —
 * e.g. Rank Math's Table of Contents block, or any element the editor
 * has manually flagged with the "no-copy-convert" class.
 *
 * @return string[]
 */
function puns_skip_container_patterns() {
	return apply_filters(
		'puns_skip_container_patterns',
		array(
			// Rank Math Table of Contents (block or shortcode output).
			'/<(div|nav|section|aside)\b[^>]*\bid=["\']rank-math-toc[^"\']*["\'][^>]*>/i',
			'/<(div|nav|section|aside)\b[^>]*\bclass=["\'][^"\']*\brank-math-toc\b[^"\']*["\'][^>]*>/i',
			// Generic manual opt-out — wrap ANY block in this class to protect it.
			'/<(div|nav|section|aside|figure)\b[^>]*\bclass=["\'][^"\']*\bno-copy-convert\b[^"\']*["\'][^>]*>/i',
		)
	);
}

/**
 * Find the byte offset right after the matching closing tag for a container
 * that opens at $start_pos, correctly handling nested elements of the same
 * tag name (e.g. nested <div>...<div>...</div>...</div>).
 *
 * @param string $content   Full post HTML.
 * @param int    $start_pos Byte offset where the opening tag starts.
 * @param string $tag_name  Lowercase tag name (div, nav, section, aside, figure).
 * @return int|false Offset right after the closing tag, or false if unbalanced.
 */
function puns_find_container_end( $content, $start_pos, $tag_name ) {
	$open_tag_end = strpos( $content, '>', $start_pos );
	if ( false === $open_tag_end ) {
		return false;
	}

	$cursor   = $open_tag_end + 1;
	$depth    = 1;
	$len      = strlen( $content );
	$open_re  = '/<' . $tag_name . '(?:\s[^>]*)?>/i';
	$close_re = '/<\/' . $tag_name . '\s*>/i';

	while ( $depth > 0 && $cursor < $len ) {
		$has_open  = preg_match( $open_re, $content, $om, PREG_OFFSET_CAPTURE, $cursor );
		$has_close = preg_match( $close_re, $content, $cm, PREG_OFFSET_CAPTURE, $cursor );

		if ( ! $has_open && ! $has_close ) {
			return false; // Unbalanced markup — bail out safely.
		}

		$next_open  = $has_open ? $om[0][1] : PHP_INT_MAX;
		$next_close = $has_close ? $cm[0][1] : PHP_INT_MAX;

		if ( $next_open < $next_close ) {
			$depth++;
			$cursor = $next_open + strlen( $om[0][0] );
		} else {
			$depth--;
			$cursor = $next_close + strlen( $cm[0][0] );
			if ( 0 === $depth ) {
				return $cursor;
			}
		}
	}

	return false;
}

/**
 * Compute all [start, end) byte ranges in $content that must be left
 * completely untouched (Rank Math TOC, "no-copy-convert" wrappers, etc.).
 *
 * @param string $content Full post HTML.
 * @return array Array of [start, end] pairs.
 */
function puns_get_skip_ranges( $content ) {
	$ranges = array();

	foreach ( puns_skip_container_patterns() as $pattern ) {
		if ( ! preg_match_all( $pattern, $content, $matches, PREG_OFFSET_CAPTURE ) ) {
			continue;
		}

		foreach ( $matches[0] as $i => $match ) {
			$tag_name = strtolower( $matches[1][ $i ][0] );
			$start    = $match[1];
			$end      = puns_find_container_end( $content, $start, $tag_name );

			if ( false !== $end ) {
				$ranges[] = array( $start, $end );
			}
		}
	}

	return $ranges;
}

/**
 * Whether a byte offset falls inside any skip range.
 *
 * @param int   $offset Byte offset to test.
 * @param array $ranges Array of [start, end] pairs.
 * @return bool
 */
function puns_offset_in_skip_range( $offset, $ranges ) {
	foreach ( $ranges as $range ) {
		if ( $offset >= $range[0] && $offset < $range[1] ) {
			return true;
		}
	}

	return false;
}

/**
 * Auto-convert lists to copy rows.
 *
 * Ordered lists (ol): every non-empty li becomes a copy tile (except intro lists).
 * Unordered lists (ul): only short, copy-friendly lines (2+ items), never in intro.
 * Any list sitting inside a skip container (Rank Math TOC, `.no-copy-convert`,
 * or anything added via the `puns_skip_container_patterns` filter) is
 * left completely untouched.
 *
 * @param string $content Post content HTML.
 * @return string
 */
function puns_auto_convert_lists( $content ) {
	$content = (string) $content;

	if ( '' === $content || strlen( $content ) > 500000 ) {
		return $content;
	}

	$skip_ranges = puns_get_skip_ranges( $content );

	if ( ! preg_match_all( '/<(ul|ol)\b[^>]*>/i', $content, $opens, PREG_OFFSET_CAPTURE ) ) {
		return $content;
	}

	$lists = array();

	foreach ( $opens[0] as $i => $match ) {
		$tag    = strtolower( $opens[1][ $i ][0] );
		$offset = $match[1];
		$end    = puns_find_container_end( $content, $offset, $tag );

		if ( false === $end ) {
			continue;
		}

		$lists[] = array(
			'tag'    => $tag,
			'offset' => $offset,
			'end'    => $end,
			'html'   => substr( $content, $offset, $end - $offset ),
		);
	}

	if ( empty( $lists ) ) {
		return $content;
	}

	// Walk back-to-front so earlier byte offsets stay valid while splicing.
	for ( $i = count( $lists ) - 1; $i >= 0; $i-- ) {
		$list   = $lists[ $i ];
		$tag    = $list['tag'];
		$html   = $list['html'];
		$offset = $list['offset'];

		// Leave nested lists inside a parent ul/ol alone.
		$nested = false;
		for ( $j = 0; $j < $i; $j++ ) {
			if ( $offset > $lists[ $j ]['offset'] && $list['end'] <= $lists[ $j ]['end'] ) {
				$nested = true;
				break;
			}
		}

		if ( $nested ) {
			continue;
		}

		// Never touch Rank Math TOC, .no-copy-convert wrappers, etc.
		if ( puns_offset_in_skip_range( $offset, $skip_ranges ) ) {
			continue;
		}

		$lines = puns_copy_lines_from_list_html( $html );
		if ( empty( $lines ) ) {
			continue;
		}

		// Keep intro lists as normal lists (opening prose / bullet intros).
		if ( puns_position_is_in_intro( $content, $offset ) ) {
			continue;
		}

		if ( 'ol' === $tag ) {
			$replacement = puns_copy_resources_html( $lines );
		} else {
			if ( count( $lines ) < 2 ) {
				continue;
			}

			$all_short = true;
			foreach ( $lines as $line ) {
				if ( ! puns_is_copy_line( $line ) ) {
					$all_short = false;
					break;
				}
			}

			if ( ! $all_short ) {
				continue;
			}

			$replacement = puns_copy_resources_html( $lines );
		}

		if ( '' === $replacement ) {
			continue;
		}

		$content = substr_replace( $content, $replacement, $offset, $list['end'] - $offset );
	}

	return $content;
}

/**
 * Wrap "Did You Know?" heading + following paragraphs in a callout tile.
 *
 * @param string $content Post content HTML.
 * @return string
 */
function puns_wrap_did_you_know( $content ) {
	if ( false === stripos( $content, 'Did You Know' ) ) {
		return $content;
	}

	$wrapped = preg_replace_callback(
		'/<aside class="puns-article__did-you-know"[^>]*>.*?<\/aside>(*SKIP)(*FAIL)|<(h[2-4])(\s[^>]*)?>((?:(?!<\/h[2-4]>).)*?Did\s+You\s+Know\??(?:(?!<\/h[2-4]>).)*?)<\/\1>\s*((?:<(?:p|ul|ol|blockquote)(?:\s[^>]*)?>.*?<\/(?:p|ul|ol|blockquote)>\s*)+)/is',
		static function ( $matches ) {
			$tag     = $matches[1];
			$attrs   = isset( $matches[2] ) ? $matches[2] : '';
			$heading = $matches[3];
			$body    = $matches[4];

			return sprintf(
				'<aside class="puns-article__did-you-know"><%1$s%2$s>%3$s</%1$s>%4$s</aside>',
				$tag,
				$attrs,
				$heading,
				$body
			);
		},
		$content
	);

	if ( ! is_string( $wrapped ) || PREG_NO_ERROR !== preg_last_error() ) {
		return $content;
	}

	return $wrapped;
}

/**
 * Automatically transform captions/jokes in post content.
 *
 * Paragraphs are never auto-converted — intros were false positives.
 * Put jokes/captions in lists (ul/ol) after a heading to get copy tiles.
 * Wrap any list you want to protect (TOC, FAQ, comparison table, etc.) in
 * a container with the "no-copy-convert" class to opt it out manually.
 *
 * @param string $content Post content HTML.
 * @return string
 */
function puns_auto_copy_resources( $content ) {
	static $doing = false;

	if ( $doing || ! is_singular( 'post' ) || '' === trim( (string) $content ) ) {
		return $content;
	}

	$doing = true;
	$content = puns_wrap_did_you_know( $content );
	$content = puns_auto_convert_lists( $content );
	$doing = false;

	return $content;
}
add_filter( 'the_content', 'puns_auto_copy_resources', 12 );
