<?php
/**
 * Technical SEO: meta tags, Open Graph, schema, breadcrumbs, and performance hints.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Detect active SEO plugins to avoid duplicate meta output.
 *
 * @return bool
 */
function puns_has_seo_plugin() {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'AIOSEO_VERSION' )
		|| class_exists( 'The_SEO_Framework\\Load', false );
}

/**
 * Meta description for the current request.
 *
 * @return string
 */
function puns_get_meta_description() {
	if ( is_singular() ) {
		$post_id = get_queried_object_id();
		$post    = get_post( $post_id );

		if ( $post instanceof WP_Post && has_excerpt( $post ) ) {
			return wp_strip_all_tags( get_the_excerpt( $post ) );
		}

		if ( $post instanceof WP_Post ) {
			return wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '…' );
		}
	}

	if ( is_category() || is_tag() || is_tax() ) {
		$description = term_description();

		if ( $description ) {
			return wp_strip_all_tags( $description );
		}
	}

	if ( is_author() ) {
		$description = get_the_author_meta( 'description' );

		if ( $description ) {
			return wp_strip_all_tags( $description );
		}
	}

	if ( is_search() ) {
		return sprintf(
			/* translators: %s: search query */
			__( 'Search results for "%s".', 'puns' ),
			get_search_query()
		);
	}

	if ( is_404() ) {
		return __( 'The page you are looking for does not exist or has been moved.', 'puns' );
	}

	return wp_strip_all_tags( get_bloginfo( 'description', 'display' ) );
}

/**
 * Canonical URL for the current request.
 *
 * @return string
 */
function puns_get_canonical_url() {
	if ( is_singular() ) {
		return get_permalink();
	}

	if ( is_front_page() ) {
		return home_url( '/' );
	}

	if ( is_home() ) {
		$posts_page = (int) get_option( 'page_for_posts' );

		if ( $posts_page ) {
			return get_permalink( $posts_page );
		}

		return home_url( '/' );
	}

	if ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();

		if ( $term instanceof WP_Term ) {
			$link = get_term_link( $term );

			if ( ! is_wp_error( $link ) ) {
				return $link;
			}
		}
	}

	if ( is_author() ) {
		return get_author_posts_url( (int) get_queried_object_id() );
	}

	if ( is_search() ) {
		return get_search_link( get_search_query( false ) );
	}

	if ( is_post_type_archive() ) {
		return get_post_type_archive_link( get_post_type() );
	}

	return home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) );
}

/**
 * Social sharing image URL.
 *
 * @return string
 */
function puns_get_social_image_url() {
	if ( is_singular() && has_post_thumbnail() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );

		if ( ! empty( $image[0] ) ) {
			return $image[0];
		}
	}

	$custom = (int) get_theme_mod( 'puns_seo_default_image', 0 );

	if ( $custom ) {
		$image = wp_get_attachment_image_url( $custom, 'large' );

		if ( $image ) {
			return $image;
		}
	}

	if ( has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$image   = wp_get_attachment_image_url( $logo_id, 'full' );

		if ( $image ) {
			return $image;
		}
	}

	$icon = get_site_icon_url( 512 );

	return $icon ? $icon : '';
}

/**
 * Open Graph type for the current request.
 *
 * @return string
 */
function puns_get_og_type() {
	if ( is_singular( 'post' ) ) {
		return 'article';
	}

	if ( is_singular() ) {
		return 'website';
	}

	return 'website';
}

/**
 * Robots directive content.
 *
 * @return string
 */
function puns_get_robots_content() {
	if ( is_404() || is_search() ) {
		return 'noindex, follow';
	}

	if ( is_feed() ) {
		return 'noindex, follow';
	}

	return 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
}

/**
 * Organization / publisher logo URL.
 *
 * @return string
 */
function puns_get_publisher_logo_url() {
	if ( has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$url     = wp_get_attachment_image_url( $logo_id, 'full' );

		if ( $url ) {
			return $url;
		}
	}

	$footer_logo_id = (int) get_theme_mod( 'puns_footer_logo', 0 );

	if ( $footer_logo_id ) {
		$url = wp_get_attachment_image_url( $footer_logo_id, 'full' );

		if ( $url ) {
			return $url;
		}
	}

	if ( function_exists( 'puns_get_brand_logo_url' ) ) {
		return puns_get_brand_logo_url();
	}

	return get_site_icon_url( 512 ) ?: '';
}

/**
 * Build breadcrumb trail for the current request.
 *
 * @return array<int, array{name:string,url:string}>
 */
function puns_get_breadcrumbs() {
	$crumbs   = array();
	$crumbs[] = array(
		'name' => __( 'Home', 'puns' ),
		'url'  => home_url( '/' ),
	);

	if ( is_front_page() ) {
		return $crumbs;
	}

	if ( is_home() ) {
		$crumbs[] = array(
			'name' => __( 'Blog', 'puns' ),
			'url'  => puns_get_canonical_url(),
		);

		return $crumbs;
	}

	if ( is_singular( 'post' ) ) {
		$categories = get_the_category();

		if ( ! empty( $categories ) ) {
			$cat      = $categories[0];
			$cat_link = get_category_link( $cat->term_id );

			if ( ! is_wp_error( $cat_link ) ) {
				$crumbs[] = array(
					'name' => $cat->name,
					'url'  => $cat_link,
				);
			}
		}

		$crumbs[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);

		return $crumbs;
	}

	if ( is_page() ) {
		$ancestors = array_reverse( get_post_ancestors( get_queried_object_id() ) );

		foreach ( $ancestors as $ancestor_id ) {
			$crumbs[] = array(
				'name' => get_the_title( $ancestor_id ),
				'url'  => get_permalink( $ancestor_id ),
			);
		}

		$crumbs[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);

		return $crumbs;
	}

	if ( is_category() ) {
		$term = get_queried_object();

		if ( $term instanceof WP_Term ) {
			$crumbs[] = array(
				'name' => $term->name,
				'url'  => get_term_link( $term ),
			);
		}

		return $crumbs;
	}

	if ( is_tag() ) {
		$term = get_queried_object();

		if ( $term instanceof WP_Term ) {
			$crumbs[] = array(
				'name' => sprintf(
					/* translators: %s: tag name */
					__( 'Tag: %s', 'puns' ),
					$term->name
				),
				'url'  => get_term_link( $term ),
			);
		}

		return $crumbs;
	}

	if ( is_author() ) {
		$crumbs[] = array(
			'name' => get_the_author(),
			'url'  => get_author_posts_url( (int) get_queried_object_id() ),
		);

		return $crumbs;
	}

	if ( is_search() ) {
		$crumbs[] = array(
			'name' => sprintf(
				/* translators: %s: search query */
				__( 'Search: %s', 'puns' ),
				get_search_query()
			),
			'url'  => puns_get_canonical_url(),
		);

		return $crumbs;
	}

	if ( is_404() ) {
		$crumbs[] = array(
			'name' => __( 'Page Not Found', 'puns' ),
			'url'  => home_url( add_query_arg( array() ) ),
		);

		return $crumbs;
	}

	if ( is_archive() ) {
		$name = puns_get_archive_breadcrumb_name();

		if ( $name ) {
			$crumbs[] = array(
				'name' => $name,
				'url'  => puns_get_canonical_url(),
			);
		}
	}

	return $crumbs;
}

/**
 * Clean archive title for breadcrumbs and schema.
 *
 * @return string
 */
function puns_get_archive_breadcrumb_name() {
	if ( is_category() ) {
		return single_cat_title( '', false );
	}

	if ( is_tag() ) {
		return single_tag_title( '', false );
	}

	if ( is_author() ) {
		return get_the_author();
	}

	if ( is_post_type_archive() ) {
		return post_type_archive_title( '', false );
	}

	if ( is_date() ) {
		if ( is_year() ) {
			return get_the_date( 'Y' );
		}

		if ( is_month() ) {
			return get_the_date( 'F Y' );
		}

		if ( is_day() ) {
			return get_the_date();
		}
	}

	return wp_strip_all_tags( get_the_archive_title() );
}

/**
 * Output theme meta tags when no SEO plugin is active.
 */
function puns_output_meta_tags() {
	if ( puns_has_seo_plugin() ) {
		return;
	}

	$description = puns_get_meta_description();

	if ( $description ) {
		printf(
			'<meta name="description" content="%s">' . "\n",
			esc_attr( $description )
		);
	}

	printf(
		'<meta name="robots" content="%s">' . "\n",
		esc_attr( puns_get_robots_content() )
	);

	$canonical = puns_get_canonical_url();

	if ( $canonical ) {
		printf(
			'<link rel="canonical" href="%s">' . "\n",
			esc_url( $canonical )
		);
	}
}
add_action( 'wp_head', 'puns_output_meta_tags', 1 );

/**
 * Output Open Graph and Twitter Card tags.
 */
function puns_output_social_tags() {
	if ( puns_has_seo_plugin() ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = puns_get_meta_description();
	$url         = puns_get_canonical_url();
	$image       = puns_get_social_image_url();
	$og_type     = puns_get_og_type();

	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( str_replace( '_', '-', get_locale() ) ) );
	printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $og_type ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( wp_strip_all_tags( $title ) ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name', 'display' ) ) );

	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	}

	if ( is_singular( 'post' ) ) {
		printf(
			'<meta property="article:published_time" content="%s">' . "\n",
			esc_attr( get_the_date( 'c' ) )
		);
		printf(
			'<meta property="article:modified_time" content="%s">' . "\n",
			esc_attr( get_the_modified_date( 'c' ) )
		);

		$categories = get_the_category();

		foreach ( $categories as $category ) {
			printf(
				'<meta property="article:section" content="%s">' . "\n",
				esc_attr( $category->name )
			);
		}

		$tags = get_the_tags();

		if ( $tags ) {
			foreach ( $tags as $tag ) {
				printf(
					'<meta property="article:tag" content="%s">' . "\n",
					esc_attr( $tag->name )
				);
			}
		}
	}

	printf( '<meta name="twitter:card" content="summary_large_image">' . "\n" );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( wp_strip_all_tags( $title ) ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );

	if ( $image ) {
		printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
	}

	$twitter = trim( (string) get_theme_mod( 'puns_twitter_handle', '' ) );

	if ( $twitter ) {
		$twitter = ltrim( $twitter, '@' );
		printf( '<meta name="twitter:site" content="@%s">' . "\n", esc_attr( $twitter ) );
	}
}
add_action( 'wp_head', 'puns_output_social_tags', 5 );

/**
 * Pagination prev/next link tags.
 */
function puns_output_pagination_links() {
	if ( puns_has_seo_plugin() ) {
		return;
	}

	global $wp_query;

	if ( ! is_paged() && ! ( $wp_query->max_num_pages > 1 ) ) {
		return;
	}

	$paged = max( 1, (int) get_query_var( 'paged' ) );

	if ( $paged > 1 ) {
		printf(
			'<link rel="prev" href="%s">' . "\n",
			esc_url( get_pagenum_link( $paged - 1 ) )
		);
	}

	if ( $paged < (int) $wp_query->max_num_pages ) {
		printf(
			'<link rel="next" href="%s">' . "\n",
			esc_url( get_pagenum_link( $paged + 1 ) )
		);
	}
}
add_action( 'wp_head', 'puns_output_pagination_links', 6 );

/**
 * Performance hints for external resources.
 */
function puns_output_resource_hints() {
	if ( ! is_readable( get_template_directory() . '/assets/fonts/inter-400.ttf' ) ) {
		echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	}
}
add_action( 'wp_head', 'puns_output_resource_hints', 2 );

/**
 * Build JSON-LD schema graph.
 *
 * @return array<int, array<string, mixed>>
 */
function puns_build_schema_graph() {
	if ( puns_has_seo_plugin() ) {
		return array();
	}

	$graph   = array();
	$site_id = home_url( '/#website' );
	$org_id  = home_url( '/#organization' );

	$graph[] = array(
		'@type'           => 'WebSite',
		'@id'             => $site_id,
		'url'             => home_url( '/' ),
		'name'            => get_bloginfo( 'name', 'display' ),
		'description'     => get_bloginfo( 'description', 'display' ),
		'publisher'       => array( '@id' => $org_id ),
		'inLanguage'      => get_bloginfo( 'language' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);

	$organization = array(
		'@type' => 'Organization',
		'@id'   => $org_id,
		'name'  => get_bloginfo( 'name', 'display' ),
		'url'   => home_url( '/' ),
	);

	$logo = puns_get_publisher_logo_url();

	if ( $logo ) {
		$organization['logo'] = array(
			'@type' => 'ImageObject',
			'url'   => $logo,
		);
	}

	if ( function_exists( 'puns_get_social_profiles' ) ) {
		$same_as = array_values( puns_get_social_profiles() );

		if ( ! empty( $same_as ) ) {
			$organization['sameAs'] = $same_as;
		}
	}

	$graph[] = $organization;

	$crumbs = puns_get_breadcrumbs();

	if ( count( $crumbs ) > 1 ) {
		$items = array();

		foreach ( $crumbs as $index => $crumb ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $index + 1,
				'name'     => wp_strip_all_tags( $crumb['name'] ),
				'item'     => $crumb['url'],
			);
		}

		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => puns_get_canonical_url() . '#breadcrumb',
			'itemListElement' => $items,
		);
	}

	if ( is_singular( 'post' ) ) {
		$post_id  = get_queried_object_id();
		$author   = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
		$image    = puns_get_social_image_url();
		$article  = array(
			'@type'            => 'BlogPosting',
			'@id'              => get_permalink( $post_id ) . '#article',
			'headline'         => get_the_title( $post_id ),
			'description'      => puns_get_meta_description(),
			'datePublished'    => get_the_date( 'c', $post_id ),
			'dateModified'     => get_the_modified_date( 'c', $post_id ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => $author,
			),
			'publisher'        => array( '@id' => $org_id ),
			'mainEntityOfPage' => get_permalink( $post_id ),
			'inLanguage'       => get_bloginfo( 'language' ),
			'wordCount'        => str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) ),
		);

		if ( $image ) {
			$article['image'] = array( $image );
		}

		$categories = get_the_category( $post_id );

		if ( ! empty( $categories ) ) {
			$article['articleSection'] = $categories[0]->name;
		}

		$graph[] = $article;
	} elseif ( is_singular( 'page' ) ) {
		$graph[] = array(
			'@type'       => 'WebPage',
			'@id'         => get_permalink() . '#webpage',
			'url'         => get_permalink(),
			'name'        => get_the_title(),
			'description' => puns_get_meta_description(),
			'inLanguage'  => get_bloginfo( 'language' ),
			'isPartOf'    => array( '@id' => $site_id ),
		);
	} elseif ( is_home() || is_archive() || is_search() ) {
		$graph[] = array(
			'@type'       => 'CollectionPage',
			'@id'         => puns_get_canonical_url() . '#collection',
			'url'         => puns_get_canonical_url(),
			'name'        => wp_get_document_title(),
			'description' => puns_get_meta_description(),
			'inLanguage'  => get_bloginfo( 'language' ),
			'isPartOf'    => array( '@id' => $site_id ),
		);
	}

	return $graph;
}

/**
 * Output JSON-LD structured data.
 */
function puns_output_schema() {
	$graph = puns_build_schema_graph();

	if ( empty( $graph ) ) {
		return;
	}

	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'puns_output_schema', 20 );

/**
 * Improve default image attributes for performance and SEO.
 *
 * @param array<string, string> $attr       Attributes.
 * @param WP_Post               $attachment Attachment post.
 * @return array<string, string>
 */
function puns_image_attributes( $attr, $attachment ) {
	unset( $attachment );

	if ( empty( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}

	if ( empty( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'puns_image_attributes', 10, 2 );

/**
 * Register SEO Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function puns_seo_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'puns_seo',
		array(
			'title'       => __( 'SEO', 'puns' ),
			'description' => puns_rank_math_is_active()
				? __( 'Rank Math handles meta tags and schema. This theme adds styled breadcrumbs.', 'puns' )
				: ( puns_has_seo_plugin()
					? __( 'Theme SEO supplements your SEO plugin with breadcrumbs. Social defaults apply when the plugin is disabled.', 'puns' )
					: __( 'Built-in technical SEO for meta tags, social sharing, and structured data.', 'puns' ) ),
			'priority'    => 45,
		)
	);

	$wp_customize->add_setting(
		'puns_twitter_handle',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'puns_twitter_handle',
		array(
			'label'       => __( 'Twitter / X Handle', 'puns' ),
			'description' => __( 'Example: puns_posts (without @)', 'puns' ),
			'section'     => 'puns_seo',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting(
		'puns_seo_default_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'puns_seo_default_image',
			array(
				'label'       => __( 'Default Social Image', 'puns' ),
				'description' => __( 'Used for Open Graph/Twitter when a page has no featured image.', 'puns' ),
				'section'     => 'puns_seo',
				'mime_type'   => 'image',
			)
		)
	);
}
add_action( 'customize_register', 'puns_seo_customize_register', 25 );

/**
 * Whether breadcrumbs should render on the current page.
 *
 * @return bool
 */
function puns_show_breadcrumbs() {
	if ( is_front_page() ) {
		return false;
	}

	if ( puns_rank_math_is_active() && puns_rank_math_breadcrumbs_enabled() ) {
		return function_exists( 'rank_math_the_breadcrumbs' );
	}

	return count( puns_get_breadcrumbs() ) > 1;
}
