<?php
/**
 * Custom nav walkers for puns menus.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fallback primary nav links when no menu is assigned.
 *
 * @return array<int, array{label:string,url:string}>
 */
function puns_get_fallback_nav_links() {
	$links = array(
		array(
			'label' => __( 'Home', 'puns' ),
			'url'   => home_url( '/' ),
		),
	);

	$candidates = array(
		array( 'about', 'about-us' ),
		array( 'blog', 'articles', 'posts' ),
		array( 'contact', 'contact-us' ),
		array( 'write-with-us', 'write' ),
	);

	$labels = array(
		'about'         => __( 'About', 'puns' ),
		'about-us'      => __( 'About', 'puns' ),
		'blog'          => __( 'Blog', 'puns' ),
		'articles'      => __( 'Blog', 'puns' ),
		'posts'         => __( 'Blog', 'puns' ),
		'contact'       => __( 'Contact', 'puns' ),
		'contact-us'    => __( 'Contact', 'puns' ),
		'write-with-us' => __( 'Write with Us', 'puns' ),
		'write'         => __( 'Write with Us', 'puns' ),
	);

	foreach ( $candidates as $slugs ) {
		foreach ( $slugs as $slug ) {
			$page = get_page_by_path( $slug );
			if ( $page instanceof WP_Post ) {
				$links[] = array(
					'label' => isset( $labels[ $slug ] ) ? $labels[ $slug ] : get_the_title( $page ),
					'url'   => get_permalink( $page ),
				);
				break;
			}
		}
	}

	$posts_page = (int) get_option( 'page_for_posts' );
	if ( $posts_page ) {
		$blog_url = get_permalink( $posts_page );
		$has_blog = false;
		foreach ( $links as $link ) {
			if ( untrailingslashit( $link['url'] ) === untrailingslashit( (string) $blog_url ) ) {
				$has_blog = true;
				break;
			}
		}
		if ( ! $has_blog && $blog_url ) {
			$links[] = array(
				'label' => __( 'Blog', 'puns' ),
				'url'   => $blog_url,
			);
		}
	}

	return $links;
}

/**
 * Print primary or fallback nav links.
 *
 * @param string $link_class CSS class for anchors.
 */
function puns_the_primary_nav_links( $link_class = 'puns-header__nav-link' ) {
	if ( has_nav_menu( 'primary' ) ) {
		$walker = ( 'puns-mobile-nav__link' === $link_class )
			? new Puns_Mobile_Nav_Walker()
			: new Puns_Nav_Walker();

		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'depth'          => 1,
				'fallback_cb'    => false,
				'walker'         => $walker,
			)
		);
		return;
	}

	foreach ( puns_get_fallback_nav_links() as $link ) {
		printf(
			'<a class="%1$s" href="%2$s">%3$s</a>',
			esc_attr( $link_class ),
			esc_url( $link['url'] ),
			esc_html( $link['label'] )
		);
	}
}

/**
 * Primary navigation walker.
 */
class Puns_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * @param string   $output Output.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 * @param int      $id     Item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$atts           = array();
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		$atts['class']  = 'puns-header__nav-link';
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
			}
		}

		$output .= '<a' . $attributes . '>';
		$output .= esc_html( $item->title );
		$output .= '</a>';
	}
}

/**
 * Mobile navigation walker.
 */
class Puns_Mobile_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * @param string   $output Output.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 * @param int      $id     Item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$atts           = array();
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		$atts['class']  = 'puns-mobile-nav__link';
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
			}
		}

		$output .= '<a' . $attributes . '>';
		$output .= esc_html( $item->title );
		$output .= '</a>';
	}
}

/**
 * Footer navigation walker.
 */
class Puns_Footer_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * @param string   $output Output.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 * @param int      $id     Item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$atts           = array();
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		$atts['class']  = 'puns-footer__link';
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
			}
		}

		$output .= '<a' . $attributes . '>';
		$output .= esc_html( $item->title );
		$output .= '</a>';
	}
}
