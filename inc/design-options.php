<?php
/**
 * Global design options: website, home, single post, post tile.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Design option keys.
 *
 * @return string[]
 */
function puns_design_option_keys() {
	return array( 'global', 'fonts', 'home', 'single', 'tile' );
}

/**
 * Labels for each design area.
 *
 * @return array<string, string>
 */
function puns_design_area_labels() {
	return array(
		'global' => __( 'Website (global colors)', 'puns' ),
		'fonts'  => __( 'Fonts', 'puns' ),
		'home'   => __( 'Home page', 'puns' ),
		'single' => __( 'Single post', 'puns' ),
		'tile'   => __( 'Post tile / card', 'puns' ),
	);
}

/**
 * Max design number for an area.
 *
 * @param string $area global|home|single|tile
 * @return int
 */
function puns_design_max_for_area( $area ) {
	$area = sanitize_key( $area );

	if ( in_array( $area, array( 'global', 'fonts', 'home', 'single', 'tile' ), true ) ) {
		return 40;
	}

	return 20;
}

/**
 * All design choices per area.
 *
 * @param string $area global|home|single|tile
 * @return array<int, string>
 */
function puns_get_design_choices( $area ) {
	$choices = array(
		'global' => array(
			1  => __( 'Brand Blue', 'puns' ),
			2  => __( 'Ocean Blue', 'puns' ),
			3  => __( 'Forest Mint', 'puns' ),
			4  => __( 'Sunset Orange', 'puns' ),
			5  => __( 'Royal Purple', 'puns' ),
			6  => __( 'Slate Clean', 'puns' ),
			7  => __( 'Gold Classic', 'puns' ),
			8  => __( 'Coral Pop', 'puns' ),
			9  => __( 'Midnight', 'puns' ),
			10 => __( 'Candy Stripe', 'puns' ),
			11 => __( 'Lime Zest', 'puns' ),
			12 => __( 'Cherry Wine', 'puns' ),
			13 => __( 'Sky Pastel', 'puns' ),
			14 => __( 'Espresso', 'puns' ),
			15 => __( 'Neon Lime', 'puns' ),
			16 => __( 'Blush Paper', 'puns' ),
			17 => __( 'Arctic Ice', 'puns' ),
			18 => __( 'Bronze Ember', 'puns' ),
			19 => __( 'Lavender Dream', 'puns' ),
			20 => __( 'Ink & Paper', 'puns' ),
			21 => __( 'Indigo Night', 'puns' ),
			22 => __( 'Teal Lagoon', 'puns' ),
			23 => __( 'Ruby Flame', 'puns' ),
			24 => __( 'Sage Garden', 'puns' ),
			25 => __( 'Cobalt Bold', 'puns' ),
			26 => __( 'Amber Glow', 'puns' ),
			27 => __( 'Plum Velvet', 'puns' ),
			28 => __( 'Seafoam', 'puns' ),
			29 => __( 'Graphite Pro', 'puns' ),
			30 => __( 'Rose Quartz', 'puns' ),
			31 => __( 'Electric Cyan', 'puns' ),
			32 => __( 'Moss Trail', 'puns' ),
			33 => __( 'Crimson Ink', 'puns' ),
			34 => __( 'Sand Dune', 'puns' ),
			35 => __( 'Violet Pulse', 'puns' ),
			36 => __( 'Aqua Mist', 'puns' ),
			37 => __( 'Copper Craft', 'puns' ),
			38 => __( 'Navy Ledger', 'puns' ),
			39 => __( 'Peach Cream', 'puns' ),
			40 => __( 'Mono Signal', 'puns' ),
		),
		'fonts'  => array(
			1 => __( 'Inter Clean', 'puns' ),
			2 => __( 'Editorial Classic', 'puns' ),
			3 => __( 'Modern Editorial', 'puns' ),
			4 => __( 'Soft Contrast', 'puns' ),
			5 => __( 'Jakarta Ledger', 'puns' ),
			6 => __( 'Outfit Lora', 'puns' ),
			7 => __( 'Tech Editorial', 'puns' ),
			8 => __( 'Work Merriweather', 'puns' ),
			9 => __( 'Nunito Cormorant', 'puns' ),
			10 => __( 'Rubik Crimson', 'puns' ),
			11 => __( 'Figtree Literata', 'puns' ),
			12 => __( 'Sora Serif', 'puns' ),
			13 => __( 'Montserrat Playfair', 'puns' ),
			14 => __( 'Poppins Garamond', 'puns' ),
			15 => __( 'Raleway Spectral', 'puns' ),
			16 => __( 'Karla Lora', 'puns' ),
			17 => __( 'Mulish Bitter', 'puns' ),
			18 => __( 'Urbanist Fraunces', 'puns' ),
			19 => __( 'Instrument Pair', 'puns' ),
			20 => __( 'IBM Plex', 'puns' ),
			21 => __( 'Syne Display', 'puns' ),
			22 => __( 'Unbounded Onest', 'puns' ),
			23 => __( 'Oswald Open', 'puns' ),
			24 => __( 'Archivo PT', 'puns' ),
			25 => __( 'Cabin Alegreya', 'puns' ),
			26 => __( 'Fira Merriweather', 'puns' ),
			27 => __( 'Lexend Source', 'puns' ),
			28 => __( 'Accessible Read', 'puns' ),
			29 => __( 'Public Source', 'puns' ),
			30 => __( 'Bricolage Petrona', 'puns' ),
			31 => __( 'Young Outfit', 'puns' ),
			32 => __( 'Fraunces Soft', 'puns' ),
			33 => __( 'Libre Pair', 'puns' ),
			34 => __( 'Noto Universal', 'puns' ),
			35 => __( 'Roboto Pair', 'puns' ),
			36 => __( 'Epilogue News', 'puns' ),
			37 => __( 'Red Hat', 'puns' ),
			38 => __( 'Josefin Garamond', 'puns' ),
			39 => __( 'DM Serif Display', 'puns' ),
			40 => __( 'System Native', 'puns' ),
		),
		'home'   => array(
			1  => __( 'Classic Split', 'puns' ),
			2  => __( 'Centered Hero', 'puns' ),
			3  => __( 'Full Bleed Image', 'puns' ),
			4  => __( 'Grid Only', 'puns' ),
			5  => __( 'Stacked Hero', 'puns' ),
			6  => __( 'Two Column Grid', 'puns' ),
			7  => __( 'Wide Magazine', 'puns' ),
			8  => __( 'Horizontal List', 'puns' ),
			9  => __( 'Featured Large', 'puns' ),
			10 => __( 'Compact Minimal', 'puns' ),
			11 => __( 'Diagonal Hero', 'puns' ),
			12 => __( 'Card Hero', 'puns' ),
			13 => __( 'Masonry Grid', 'puns' ),
			14 => __( 'Dense Grid', 'puns' ),
			15 => __( 'Hero + List', 'puns' ),
			16 => __( 'Borderless Grid', 'puns' ),
			17 => __( 'Offset Hero', 'puns' ),
			18 => __( 'Triple Grid', 'puns' ),
			19 => __( 'Hero Banner', 'puns' ),
			20 => __( 'Editorial Stack', 'puns' ),
			21 => __( 'Split Banner', 'puns' ),
			22 => __( 'Hero Overlay', 'puns' ),
			23 => __( 'Quad Grid', 'puns' ),
			24 => __( 'Rail + Feature', 'puns' ),
			25 => __( 'Magazine Cover', 'puns' ),
			26 => __( 'Soft Panels', 'puns' ),
			27 => __( 'Tight Columns', 'puns' ),
			28 => __( 'Hero Strip', 'puns' ),
			29 => __( 'Asymmetric Grid', 'puns' ),
			30 => __( 'Focus Stage', 'puns' ),
			31 => __( 'Wide Feature Row', 'puns' ),
			32 => __( 'Nested Cards', 'puns' ),
			33 => __( 'Headline First', 'puns' ),
			34 => __( 'Media Left Stack', 'puns' ),
			35 => __( 'Gallery Home', 'puns' ),
			36 => __( 'Quiet List', 'puns' ),
			37 => __( 'Bold Intro', 'puns' ),
			38 => __( 'Ribbon Hero', 'puns' ),
			39 => __( 'Duo Spotlight', 'puns' ),
			40 => __( 'Stream Feed', 'puns' ),
		),
		'single' => array(
			1  => __( 'Classic Article', 'puns' ),
			2  => __( 'Narrow Centered', 'puns' ),
			3  => __( 'Full Width', 'puns' ),
			4  => __( 'Hero Top Image', 'puns' ),
			5  => __( 'With Sidebar', 'puns' ),
			6  => __( 'Wide Content', 'puns' ),
			7  => __( 'Magazine Style', 'puns' ),
			8  => __( 'Minimal Meta', 'puns' ),
			9  => __( 'Split Layout', 'puns' ),
			10 => __( 'Soft Card', 'puns' ),
			11 => __( 'Reading Mode', 'puns' ),
			12 => __( 'Newspaper', 'puns' ),
			13 => __( 'Serif Classic', 'puns' ),
			14 => __( 'Floating Title', 'puns' ),
			15 => __( 'Bordered Sections', 'puns' ),
			16 => __( 'Large Quote', 'puns' ),
			17 => __( 'Clean White', 'puns' ),
			18 => __( 'Sticky Title', 'puns' ),
			19 => __( 'Two Column Text', 'puns' ),
			20 => __( 'Boxed Content', 'puns' ),
			21 => __( 'Cover Banner', 'puns' ),
			22 => __( 'Drop Cap Lead', 'puns' ),
			23 => __( 'Quiet Margins', 'puns' ),
			24 => __( 'Accent Rule Title', 'puns' ),
			25 => __( 'Meta Capsule', 'puns' ),
			26 => __( 'Pull Quote Focus', 'puns' ),
			27 => __( 'Inline Rail', 'puns' ),
			28 => __( 'Soft Paper', 'puns' ),
			29 => __( 'Tight Measure', 'puns' ),
			30 => __( 'Open Leading', 'puns' ),
			31 => __( 'Frame Gallery', 'puns' ),
			32 => __( 'Underline Heads', 'puns' ),
			33 => __( 'Sidebar Compact', 'puns' ),
			34 => __( 'Hero Fade', 'puns' ),
			35 => __( 'Card Stack Meta', 'puns' ),
			36 => __( 'Borderless Body', 'puns' ),
			37 => __( 'Editorial Indent', 'puns' ),
			38 => __( 'Wide Title Band', 'puns' ),
			39 => __( 'Dense Reading', 'puns' ),
			40 => __( 'Signature Close', 'puns' ),
		),
		'tile'   => array(
			1  => __( 'Classic Card', 'puns' ),
			2  => __( 'Horizontal', 'puns' ),
			3  => __( 'Text Only', 'puns' ),
			4  => __( 'Image Overlay', 'puns' ),
			5  => __( 'Outlined', 'puns' ),
			6  => __( 'Soft Rounded', 'puns' ),
			7  => __( 'Elevated Shadow', 'puns' ),
			8  => __( 'Compact Row', 'puns' ),
			9  => __( 'Big Image', 'puns' ),
			10 => __( 'Magazine Tile', 'puns' ),
			11 => __( 'Polaroid', 'puns' ),
			12 => __( 'Circle Image', 'puns' ),
			13 => __( 'Left Border', 'puns' ),
			14 => __( 'Gradient Card', 'puns' ),
			15 => __( 'Split Color', 'puns' ),
			16 => __( 'Stacked Meta', 'puns' ),
			17 => __( 'Minimal Line', 'puns' ),
			18 => __( 'Photo Frame', 'puns' ),
			19 => __( 'Bold Title', 'puns' ),
			20 => __( 'Accent Strip', 'puns' ),
			21 => __( 'Soft Shadow Pad', 'puns' ),
			22 => __( 'Top Accent Bar', 'puns' ),
			23 => __( 'Inset Media', 'puns' ),
			24 => __( 'Chip Tag Card', 'puns' ),
			25 => __( 'Wide Media Ratio', 'puns' ),
			26 => __( 'Quiet Border', 'puns' ),
			27 => __( 'Lift on Hover', 'puns' ),
			28 => __( 'Square Media', 'puns' ),
			29 => __( 'Meta Capsule', 'puns' ),
			30 => __( 'Title Dominates', 'puns' ),
			31 => __( 'Glass Edge', 'puns' ),
			32 => __( 'Bottom Rule', 'puns' ),
			33 => __( 'Side Media Slim', 'puns' ),
			34 => __( 'Poster Crop', 'puns' ),
			35 => __( 'Soft Wash', 'puns' ),
			36 => __( 'Tight Padding', 'puns' ),
			37 => __( 'Open Frame', 'puns' ),
			38 => __( 'Ribbon Tag', 'puns' ),
			39 => __( 'Duo Tone Body', 'puns' ),
			40 => __( 'Editorial Clip', 'puns' ),
		),
	);

	return isset( $choices[ $area ] ) ? $choices[ $area ] : array();
}

/**
 * Default design options.
 *
 * @return array<string, int>
 */
function puns_default_design_options() {
	return array(
		'global' => 1,
		'fonts'  => 1,
		'home'   => 1,
		'single' => 1,
		'tile'   => 1,
	);
}

define( 'PUNS_DESIGN_MAX', 40 );

/**
 * Sanitize saved design options.
 *
 * @param mixed $input Raw input.
 * @return array<string, int>
 */
function puns_sanitize_design_options( $input ) {
	$defaults = puns_default_design_options();
	$output   = $defaults;

	if ( ! is_array( $input ) ) {
		return $output;
	}

	foreach ( puns_design_option_keys() as $key ) {
		if ( ! isset( $input[ $key ] ) ) {
			continue;
		}

		$value          = (int) $input[ $key ];
		$value          = max( 1, min( puns_design_max_for_area( $key ), $value ) );
		$output[ $key ] = $value;
	}

	return $output;
}

/**
 * Get saved design number for an area.
 *
 * @param string $area global|home|single|tile
 * @return int
 */
function puns_get_design( $area ) {
	$defaults = puns_default_design_options();
	$options  = get_option( 'puns_design_options', $defaults );

	if ( ! is_array( $options ) ) {
		$options = $defaults;
	}

	$area = sanitize_key( $area );

	if ( ! isset( $defaults[ $area ] ) ) {
		return 1;
	}

	$value = isset( $options[ $area ] ) ? (int) $options[ $area ] : $defaults[ $area ];

	return max( 1, min( puns_design_max_for_area( $area ), $value ) );
}

/**
 * CSS slug for a design (01–40).
 *
 * @param string $area global|home|single|tile
 * @return string
 */
function puns_design_slug( $area ) {
	return sprintf( '%02d', puns_get_design( $area ) );
}

/**
 * Body class for a design area.
 *
 * @param string $area global|home|single|tile
 * @return string
 */
function puns_design_class( $area ) {
	return 'puns-design-' . sanitize_key( $area ) . '-' . puns_design_slug( $area );
}

/**
 * Register settings and admin page.
 */
function puns_design_options_admin_init() {
	register_setting(
		'puns_designs',
		'puns_design_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'puns_sanitize_design_options',
			'default'           => puns_default_design_options(),
		)
	);
}
add_action( 'admin_init', 'puns_design_options_admin_init' );

/**
 * Add Designs page under Appearance.
 */
function puns_design_options_menu() {
	add_theme_page(
		__( 'Blog Theme Designs', 'puns' ),
		__( 'Designs', 'puns' ),
		'edit_theme_options',
		'puns-designs',
		'puns_render_design_options_page'
	);
}
add_action( 'admin_menu', 'puns_design_options_menu' );


/**
 * Font design presets (heading + body pairings).
 *
 * @return array<int, array<string, mixed>>
 */
function puns_get_font_presets() {
	return array(
		1 => array(
			'label'    => __( 'Inter Clean', 'puns' ),
			'heading'  => "'Inter', sans-serif",
			'body'     => "'Inter', sans-serif",
			'serif'    => "'Inter', sans-serif",
			'sample_h' => 'Inter',
			'sample_b' => 'Inter',
			'google'   => array( 'Inter:wght@400;500;600;700' ),
		),
		2 => array(
			'label'    => __( 'Editorial Classic', 'puns' ),
			'heading'  => "'Playfair Display', serif",
			'body'     => "'Inter', sans-serif",
			'serif'    => "'Playfair Display', serif",
			'sample_h' => 'Playfair Display',
			'sample_b' => 'Inter',
			'google'   => array( 'Playfair Display:wght@400;500;600;700', 'Inter:wght@400;500;600;700' ),
		),
		3 => array(
			'label'    => __( 'Modern Editorial', 'puns' ),
			'heading'  => "'DM Sans', sans-serif",
			'body'     => "'Source Serif 4', serif",
			'serif'    => "'Source Serif 4', serif",
			'sample_h' => 'DM Sans',
			'sample_b' => 'Source Serif 4',
			'google'   => array( 'DM Sans:wght@400;500;600;700', 'Source Serif 4:wght@400;500;600;700' ),
		),
		4 => array(
			'label'    => __( 'Soft Contrast', 'puns' ),
			'heading'  => "'Manrope', sans-serif",
			'body'     => "'Fraunces', serif",
			'serif'    => "'Fraunces', serif",
			'sample_h' => 'Manrope',
			'sample_b' => 'Fraunces',
			'google'   => array( 'Manrope:wght@400;500;600;700', 'Fraunces:wght@400;500;600;700' ),
		),
		5 => array(
			'label'    => __( 'Jakarta Ledger', 'puns' ),
			'heading'  => "'Plus Jakarta Sans', sans-serif",
			'body'     => "'Libre Baskerville', serif",
			'serif'    => "'Libre Baskerville', serif",
			'sample_h' => 'Plus Jakarta Sans',
			'sample_b' => 'Libre Baskerville',
			'google'   => array( 'Plus Jakarta Sans:wght@400;500;600;700', 'Libre Baskerville:wght@400;500;600;700' ),
		),
		6 => array(
			'label'    => __( 'Outfit Lora', 'puns' ),
			'heading'  => "'Outfit', sans-serif",
			'body'     => "'Lora', serif",
			'serif'    => "'Lora', serif",
			'sample_h' => 'Outfit',
			'sample_b' => 'Lora',
			'google'   => array( 'Outfit:wght@400;500;600;700', 'Lora:wght@400;500;600;700' ),
		),
		7 => array(
			'label'    => __( 'Tech Editorial', 'puns' ),
			'heading'  => "'Space Grotesk', sans-serif",
			'body'     => "'Newsreader', serif",
			'serif'    => "'Newsreader', serif",
			'sample_h' => 'Space Grotesk',
			'sample_b' => 'Newsreader',
			'google'   => array( 'Space Grotesk:wght@400;500;600;700', 'Newsreader:wght@400;500;600;700' ),
		),
		8 => array(
			'label'    => __( 'Work Merriweather', 'puns' ),
			'heading'  => "'Work Sans', sans-serif",
			'body'     => "'Merriweather', serif",
			'serif'    => "'Merriweather', serif",
			'sample_h' => 'Work Sans',
			'sample_b' => 'Merriweather',
			'google'   => array( 'Work Sans:wght@400;500;600;700', 'Merriweather:wght@400;500;600;700' ),
		),
		9 => array(
			'label'    => __( 'Nunito Cormorant', 'puns' ),
			'heading'  => "'Nunito Sans', sans-serif",
			'body'     => "'Cormorant Garamond', serif",
			'serif'    => "'Cormorant Garamond', serif",
			'sample_h' => 'Nunito Sans',
			'sample_b' => 'Cormorant Garamond',
			'google'   => array( 'Nunito Sans:wght@400;500;600;700', 'Cormorant Garamond:wght@400;500;600;700' ),
		),
		10 => array(
			'label'    => __( 'Rubik Crimson', 'puns' ),
			'heading'  => "'Rubik', sans-serif",
			'body'     => "'Crimson Pro', serif",
			'serif'    => "'Crimson Pro', serif",
			'sample_h' => 'Rubik',
			'sample_b' => 'Crimson Pro',
			'google'   => array( 'Rubik:wght@400;500;600;700', 'Crimson Pro:wght@400;500;600;700' ),
		),
		11 => array(
			'label'    => __( 'Figtree Literata', 'puns' ),
			'heading'  => "'Figtree', sans-serif",
			'body'     => "'Literata', serif",
			'serif'    => "'Literata', serif",
			'sample_h' => 'Figtree',
			'sample_b' => 'Literata',
			'google'   => array( 'Figtree:wght@400;500;600;700', 'Literata:wght@400;500;600;700' ),
		),
		12 => array(
			'label'    => __( 'Sora Serif', 'puns' ),
			'heading'  => "'Sora', sans-serif",
			'body'     => "'Source Serif 4', serif",
			'serif'    => "'Source Serif 4', serif",
			'sample_h' => 'Sora',
			'sample_b' => 'Source Serif 4',
			'google'   => array( 'Sora:wght@400;500;600;700', 'Source Serif 4:wght@400;500;600;700' ),
		),
		13 => array(
			'label'    => __( 'Montserrat Playfair', 'puns' ),
			'heading'  => "'Montserrat', sans-serif",
			'body'     => "'Playfair Display', serif",
			'serif'    => "'Playfair Display', serif",
			'sample_h' => 'Montserrat',
			'sample_b' => 'Playfair Display',
			'google'   => array( 'Montserrat:wght@400;500;600;700', 'Playfair Display:wght@400;500;600;700' ),
		),
		14 => array(
			'label'    => __( 'Poppins Garamond', 'puns' ),
			'heading'  => "'Poppins', sans-serif",
			'body'     => "'EB Garamond', serif",
			'serif'    => "'EB Garamond', serif",
			'sample_h' => 'Poppins',
			'sample_b' => 'EB Garamond',
			'google'   => array( 'Poppins:wght@400;500;600;700', 'EB Garamond:wght@400;500;600;700' ),
		),
		15 => array(
			'label'    => __( 'Raleway Spectral', 'puns' ),
			'heading'  => "'Raleway', sans-serif",
			'body'     => "'Spectral', serif",
			'serif'    => "'Spectral', serif",
			'sample_h' => 'Raleway',
			'sample_b' => 'Spectral',
			'google'   => array( 'Raleway:wght@400;500;600;700', 'Spectral:wght@400;500;600;700' ),
		),
		16 => array(
			'label'    => __( 'Karla Lora', 'puns' ),
			'heading'  => "'Karla', sans-serif",
			'body'     => "'Lora', serif",
			'serif'    => "'Lora', serif",
			'sample_h' => 'Karla',
			'sample_b' => 'Lora',
			'google'   => array( 'Karla:wght@400;500;600;700', 'Lora:wght@400;500;600;700' ),
		),
		17 => array(
			'label'    => __( 'Mulish Bitter', 'puns' ),
			'heading'  => "'Mulish', sans-serif",
			'body'     => "'Bitter', serif",
			'serif'    => "'Bitter', serif",
			'sample_h' => 'Mulish',
			'sample_b' => 'Bitter',
			'google'   => array( 'Mulish:wght@400;500;600;700', 'Bitter:wght@400;500;600;700' ),
		),
		18 => array(
			'label'    => __( 'Urbanist Fraunces', 'puns' ),
			'heading'  => "'Urbanist', sans-serif",
			'body'     => "'Fraunces', serif",
			'serif'    => "'Fraunces', serif",
			'sample_h' => 'Urbanist',
			'sample_b' => 'Fraunces',
			'google'   => array( 'Urbanist:wght@400;500;600;700', 'Fraunces:wght@400;500;600;700' ),
		),
		19 => array(
			'label'    => __( 'Instrument Pair', 'puns' ),
			'heading'  => "'Instrument Sans', sans-serif",
			'body'     => "'Instrument Serif', serif",
			'serif'    => "'Instrument Serif', serif",
			'sample_h' => 'Instrument Sans',
			'sample_b' => 'Instrument Serif',
			'google'   => array( 'Instrument Sans:wght@400;500;600;700', 'Instrument Serif:wght@400;500;600;700' ),
		),
		20 => array(
			'label'    => __( 'IBM Plex', 'puns' ),
			'heading'  => "'IBM Plex Sans', sans-serif",
			'body'     => "'IBM Plex Serif', serif",
			'serif'    => "'IBM Plex Serif', serif",
			'sample_h' => 'IBM Plex Sans',
			'sample_b' => 'IBM Plex Serif',
			'google'   => array( 'IBM Plex Sans:wght@400;500;600;700', 'IBM Plex Serif:wght@400;500;600;700' ),
		),
		21 => array(
			'label'    => __( 'Syne Display', 'puns' ),
			'heading'  => "'Syne', sans-serif",
			'body'     => "'Inter', sans-serif",
			'serif'    => "'Syne', sans-serif",
			'sample_h' => 'Syne',
			'sample_b' => 'Inter',
			'google'   => array( 'Syne:wght@400;500;600;700', 'Inter:wght@400;500;600;700' ),
		),
		22 => array(
			'label'    => __( 'Unbounded Onest', 'puns' ),
			'heading'  => "'Unbounded', sans-serif",
			'body'     => "'Onest', sans-serif",
			'serif'    => "'Unbounded', sans-serif",
			'sample_h' => 'Unbounded',
			'sample_b' => 'Onest',
			'google'   => array( 'Unbounded:wght@400;500;600;700', 'Onest:wght@400;500;600;700' ),
		),
		23 => array(
			'label'    => __( 'Oswald Open', 'puns' ),
			'heading'  => "'Oswald', sans-serif",
			'body'     => "'Open Sans', sans-serif",
			'serif'    => "'Oswald', sans-serif",
			'sample_h' => 'Oswald',
			'sample_b' => 'Open Sans',
			'google'   => array( 'Oswald:wght@400;500;600;700', 'Open Sans:wght@400;500;600;700' ),
		),
		24 => array(
			'label'    => __( 'Archivo PT', 'puns' ),
			'heading'  => "'Archivo', sans-serif",
			'body'     => "'PT Serif', serif",
			'serif'    => "'PT Serif', serif",
			'sample_h' => 'Archivo',
			'sample_b' => 'PT Serif',
			'google'   => array( 'Archivo:wght@400;500;600;700', 'PT Serif:wght@400;500;600;700' ),
		),
		25 => array(
			'label'    => __( 'Cabin Alegreya', 'puns' ),
			'heading'  => "'Cabin', sans-serif",
			'body'     => "'Alegreya', serif",
			'serif'    => "'Alegreya', serif",
			'sample_h' => 'Cabin',
			'sample_b' => 'Alegreya',
			'google'   => array( 'Cabin:wght@400;500;600;700', 'Alegreya:wght@400;500;600;700' ),
		),
		26 => array(
			'label'    => __( 'Fira Merriweather', 'puns' ),
			'heading'  => "'Fira Sans', sans-serif",
			'body'     => "'Merriweather', serif",
			'serif'    => "'Merriweather', serif",
			'sample_h' => 'Fira Sans',
			'sample_b' => 'Merriweather',
			'google'   => array( 'Fira Sans:wght@400;500;600;700', 'Merriweather:wght@400;500;600;700' ),
		),
		27 => array(
			'label'    => __( 'Lexend Source', 'puns' ),
			'heading'  => "'Lexend', sans-serif",
			'body'     => "'Source Sans 3', sans-serif",
			'serif'    => "'Lexend', sans-serif",
			'sample_h' => 'Lexend',
			'sample_b' => 'Source Sans 3',
			'google'   => array( 'Lexend:wght@400;500;600;700', 'Source Sans 3:wght@400;500;600;700' ),
		),
		28 => array(
			'label'    => __( 'Accessible Read', 'puns' ),
			'heading'  => "'Atkinson Hyperlegible', sans-serif",
			'body'     => "'Literata', serif",
			'serif'    => "'Literata', serif",
			'sample_h' => 'Atkinson Hyperlegible',
			'sample_b' => 'Literata',
			'google'   => array( 'Atkinson Hyperlegible:wght@400;500;600;700', 'Literata:wght@400;500;600;700' ),
		),
		29 => array(
			'label'    => __( 'Public Source', 'puns' ),
			'heading'  => "'Public Sans', sans-serif",
			'body'     => "'Source Serif 4', serif",
			'serif'    => "'Source Serif 4', serif",
			'sample_h' => 'Public Sans',
			'sample_b' => 'Source Serif 4',
			'google'   => array( 'Public Sans:wght@400;500;600;700', 'Source Serif 4:wght@400;500;600;700' ),
		),
		30 => array(
			'label'    => __( 'Bricolage Petrona', 'puns' ),
			'heading'  => "'Bricolage Grotesque', sans-serif",
			'body'     => "'Petrona', serif",
			'serif'    => "'Petrona', serif",
			'sample_h' => 'Bricolage Grotesque',
			'sample_b' => 'Petrona',
			'google'   => array( 'Bricolage Grotesque:wght@400;500;600;700', 'Petrona:wght@400;500;600;700' ),
		),
		31 => array(
			'label'    => __( 'Young Outfit', 'puns' ),
			'heading'  => "'Young Serif', serif",
			'body'     => "'Outfit', sans-serif",
			'serif'    => "'Young Serif', serif",
			'sample_h' => 'Young Serif',
			'sample_b' => 'Outfit',
			'google'   => array( 'Young Serif:wght@400;500;600;700', 'Outfit:wght@400;500;600;700' ),
		),
		32 => array(
			'label'    => __( 'Fraunces Soft', 'puns' ),
			'heading'  => "'Fraunces', serif",
			'body'     => "'Inter', sans-serif",
			'serif'    => "'Fraunces', serif",
			'sample_h' => 'Fraunces',
			'sample_b' => 'Inter',
			'google'   => array( 'Fraunces:wght@400;500;600;700', 'Inter:wght@400;500;600;700' ),
		),
		33 => array(
			'label'    => __( 'Libre Pair', 'puns' ),
			'heading'  => "'Libre Franklin', sans-serif",
			'body'     => "'Libre Baskerville', serif",
			'serif'    => "'Libre Baskerville', serif",
			'sample_h' => 'Libre Franklin',
			'sample_b' => 'Libre Baskerville',
			'google'   => array( 'Libre Franklin:wght@400;500;600;700', 'Libre Baskerville:wght@400;500;600;700' ),
		),
		34 => array(
			'label'    => __( 'Noto Universal', 'puns' ),
			'heading'  => "'Noto Sans', sans-serif",
			'body'     => "'Noto Serif', serif",
			'serif'    => "'Noto Serif', serif",
			'sample_h' => 'Noto Sans',
			'sample_b' => 'Noto Serif',
			'google'   => array( 'Noto Sans:wght@400;500;600;700', 'Noto Serif:wght@400;500;600;700' ),
		),
		35 => array(
			'label'    => __( 'Roboto Pair', 'puns' ),
			'heading'  => "'Roboto', sans-serif",
			'body'     => "'Roboto Serif', serif",
			'serif'    => "'Roboto Serif', serif",
			'sample_h' => 'Roboto',
			'sample_b' => 'Roboto Serif',
			'google'   => array( 'Roboto:wght@400;500;600;700', 'Roboto Serif:wght@400;500;600;700' ),
		),
		36 => array(
			'label'    => __( 'Epilogue News', 'puns' ),
			'heading'  => "'Epilogue', sans-serif",
			'body'     => "'Newsreader', serif",
			'serif'    => "'Newsreader', serif",
			'sample_h' => 'Epilogue',
			'sample_b' => 'Newsreader',
			'google'   => array( 'Epilogue:wght@400;500;600;700', 'Newsreader:wght@400;500;600;700' ),
		),
		37 => array(
			'label'    => __( 'Red Hat', 'puns' ),
			'heading'  => "'Red Hat Display', sans-serif",
			'body'     => "'Red Hat Text', sans-serif",
			'serif'    => "'Red Hat Display', sans-serif",
			'sample_h' => 'Red Hat Display',
			'sample_b' => 'Red Hat Text',
			'google'   => array( 'Red Hat Display:wght@400;500;600;700', 'Red Hat Text:wght@400;500;600;700' ),
		),
		38 => array(
			'label'    => __( 'Josefin Garamond', 'puns' ),
			'heading'  => "'Josefin Sans', sans-serif",
			'body'     => "'EB Garamond', serif",
			'serif'    => "'EB Garamond', serif",
			'sample_h' => 'Josefin Sans',
			'sample_b' => 'EB Garamond',
			'google'   => array( 'Josefin Sans:wght@400;500;600;700', 'EB Garamond:wght@400;500;600;700' ),
		),
		39 => array(
			'label'    => __( 'DM Serif Display', 'puns' ),
			'heading'  => "'DM Serif Display', serif",
			'body'     => "'DM Sans', sans-serif",
			'serif'    => "'DM Serif Display', serif",
			'sample_h' => 'DM Serif Display',
			'sample_b' => 'DM Sans',
			'google'   => array( 'DM Serif Display:wght@400;500;600;700', 'DM Sans:wght@400;500;600;700' ),
		),
		40 => array(
			'label'    => __( 'System Native', 'puns' ),
			'heading'  => "system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif",
			'body'     => "Georgia, 'Times New Roman', serif",
			'serif'    => "Georgia, 'Times New Roman', serif",
			'sample_h' => 'system-ui',
			'sample_b' => 'Georgia',
			'google'   => array(),
		),
	);
}

/**
 * Build a Google Fonts CSS2 URL for family specs.
 *
 * @param string[] $families Family specs like Inter:wght@400;700.
 * @return string
 */
function puns_font_google_url( $families ) {
	$families = array_filter( array_map( 'strval', (array) $families ) );
	if ( empty( $families ) ) {
		return '';
	}

	$parts = array();
	foreach ( $families as $family ) {
		// Keep : @ ; intact for the CSS2 API; only spaces become +.
		$parts[] = 'family=' . str_replace( ' ', '+', $family );
	}

	return 'https://fonts.googleapis.com/css2?' . implode( '&', $parts ) . '&display=swap';
}

/**
 * Get one font preset.
 *
 * @param int $number Preset number.
 * @return array<string, mixed>
 */
function puns_get_font_preset( $number ) {
	$presets = puns_get_font_presets();
	$number  = max( 1, min( 40, (int) $number ) );
	return isset( $presets[ $number ] ) ? $presets[ $number ] : $presets[1];
}

/**
 * Preview palette swatches for global designs.
 *
 * @return array<int, array{primary:string,accent:string,surface:string}>
 */
function puns_design_global_preview_colors() {
	return array(
		1  => array( 'primary' => '#3858E9', 'accent' => '#60A5FA', 'surface' => '#F8FAFF' ),
		2  => array( 'primary' => '#0c6e8a', 'accent' => '#38bdf8', 'surface' => '#f0f9ff' ),
		3  => array( 'primary' => '#0f766e', 'accent' => '#34d399', 'surface' => '#ecfdf5' ),
		4  => array( 'primary' => '#c2410c', 'accent' => '#fb923c', 'surface' => '#fff7ed' ),
		5  => array( 'primary' => '#6d28d9', 'accent' => '#c084fc', 'surface' => '#faf5ff' ),
		6  => array( 'primary' => '#334155', 'accent' => '#64748b', 'surface' => '#f8fafc' ),
		7  => array( 'primary' => '#92400e', 'accent' => '#d97706', 'surface' => '#fffbeb' ),
		8  => array( 'primary' => '#e11d48', 'accent' => '#fb7185', 'surface' => '#fff1f2' ),
		9  => array( 'primary' => '#f472b6', 'accent' => '#a78bfa', 'surface' => '#0f172a' ),
		10 => array( 'primary' => '#db2777', 'accent' => '#22d3ee', 'surface' => '#fdf2f8' ),
		11 => array( 'primary' => '#65a30d', 'accent' => '#a3e635', 'surface' => '#f7fee7' ),
		12 => array( 'primary' => '#9f1239', 'accent' => '#fb7185', 'surface' => '#fff1f2' ),
		13 => array( 'primary' => '#0284c7', 'accent' => '#7dd3fc', 'surface' => '#f0f9ff' ),
		14 => array( 'primary' => '#78350f', 'accent' => '#d6d3d1', 'surface' => '#fafaf9' ),
		15 => array( 'primary' => '#84cc16', 'accent' => '#22c55e', 'surface' => '#f7fee7' ),
		16 => array( 'primary' => '#be185d', 'accent' => '#f9a8d4', 'surface' => '#fdf2f8' ),
		17 => array( 'primary' => '#0ea5e9', 'accent' => '#e0f2fe', 'surface' => '#f8fafc' ),
		18 => array( 'primary' => '#b45309', 'accent' => '#f59e0b', 'surface' => '#fffbeb' ),
		19 => array( 'primary' => '#7c3aed', 'accent' => '#c4b5fd', 'surface' => '#f5f3ff' ),
		20 => array( 'primary' => '#111827', 'accent' => '#6b7280', 'surface' => '#f9fafb' ),
		21 => array( 'primary' => '#312e81', 'accent' => '#818cf8', 'surface' => '#eef2ff' ),
		22 => array( 'primary' => '#0f766e', 'accent' => '#5eead4', 'surface' => '#f0fdfa' ),
		23 => array( 'primary' => '#dc2626', 'accent' => '#fca5a5', 'surface' => '#fef2f2' ),
		24 => array( 'primary' => '#4d7c0f', 'accent' => '#bef264', 'surface' => '#f7fee7' ),
		25 => array( 'primary' => '#1d4ed8', 'accent' => '#93c5fd', 'surface' => '#eff6ff' ),
		26 => array( 'primary' => '#d97706', 'accent' => '#fcd34d', 'surface' => '#fffbeb' ),
		27 => array( 'primary' => '#6b21a8', 'accent' => '#d8b4fe', 'surface' => '#faf5ff' ),
		28 => array( 'primary' => '#0d9488', 'accent' => '#99f6e4', 'surface' => '#f0fdfa' ),
		29 => array( 'primary' => '#374151', 'accent' => '#9ca3af', 'surface' => '#f9fafb' ),
		30 => array( 'primary' => '#e11d48', 'accent' => '#fda4af', 'surface' => '#fff1f2' ),
		31 => array( 'primary' => '#06b6d4', 'accent' => '#67e8f9', 'surface' => '#ecfeff' ),
		32 => array( 'primary' => '#3f6212', 'accent' => '#a3e635', 'surface' => '#f7fee7' ),
		33 => array( 'primary' => '#991b1b', 'accent' => '#f87171', 'surface' => '#fef2f2' ),
		34 => array( 'primary' => '#a16207', 'accent' => '#fde68a', 'surface' => '#fffbeb' ),
		35 => array( 'primary' => '#5b21b6', 'accent' => '#c4b5fd', 'surface' => '#f5f3ff' ),
		36 => array( 'primary' => '#155e75', 'accent' => '#67e8f9', 'surface' => '#ecfeff' ),
		37 => array( 'primary' => '#9a3412', 'accent' => '#fdba74', 'surface' => '#fff7ed' ),
		38 => array( 'primary' => '#1e3a8a', 'accent' => '#60a5fa', 'surface' => '#eff6ff' ),
		39 => array( 'primary' => '#ea580c', 'accent' => '#fdba74', 'surface' => '#fff7ed' ),
		40 => array( 'primary' => '#0f172a', 'accent' => '#38bdf8', 'surface' => '#f8fafc' ),
	);
}

/**
 * Render a mini preview for a design choice.
 *
 * @param string $area   global|home|single|tile
 * @param int    $number Design number.
 * @return void
 */
function puns_render_design_preview( $area, $number ) {
	$area   = sanitize_key( $area );
	$number = max( 1, min( puns_design_max_for_area( $area ), (int) $number ) );

	if ( 'global' === $area ) {
		$colors = puns_design_global_preview_colors();
		$swatch = isset( $colors[ $number ] ) ? $colors[ $number ] : $colors[1];
		printf(
			'<span class="puns-design-admin__preview puns-design-admin__preview--global" aria-hidden="true" style="--p1:%1$s;--p2:%2$s;--p3:%3$s"><span class="puns-design-admin__swatch puns-design-admin__swatch--a"></span><span class="puns-design-admin__swatch puns-design-admin__swatch--b"></span><span class="puns-design-admin__swatch puns-design-admin__swatch--c"></span></span>',
			esc_attr( $swatch['primary'] ),
			esc_attr( $swatch['accent'] ),
			esc_attr( $swatch['surface'] )
		);
		return;
	}

	if ( 'fonts' === $area ) {
		$preset = puns_get_font_preset( $number );
		printf(
			'<span class="puns-design-admin__preview puns-design-admin__preview--fonts" aria-hidden="true"><span class="puns-design-admin__font-heading" style="font-family:%1$s">Ag</span><span class="puns-design-admin__font-body" style="font-family:%2$s">Body · %3$s</span></span>',
			esc_attr( $preset['heading'] ),
			esc_attr( $preset['body'] ),
			esc_html( $preset['sample_h'] )
		);
		return;
	}

	printf(
		'<span class="puns-design-admin__preview puns-design-admin__preview--%1$s puns-design-admin__preview--%1$s-%2$02d" aria-hidden="true"><span class="puns-design-admin__wire"></span></span>',
		esc_attr( $area ),
		$number
	);
}

/**
 * Admin styles for the design picker.
 *
 * @param string $hook_suffix Current admin page hook.
 */
function puns_design_options_admin_assets( $hook_suffix ) {
	if ( 'appearance_page_puns-designs' !== $hook_suffix ) {
		return;
	}

	$css = '
		.wrap:has(.puns-design-admin) { max-width: none; margin-right: 12px; }
		body.appearance_page_puns-designs #wpcontent { background: #f0f0f1; }
		.puns-design-admin {
			--pda-brand: #3858E9;
			--pda-ink: #404040;
			--pda-muted: #707070;
			--pda-line: #ddd;
			--pda-paper: #fff;
			max-width: 100%; box-sizing: border-box; padding-bottom: 24px; color: var(--pda-ink);
		}
		.puns-design-admin__layout {
			display: grid; grid-template-columns: 280px minmax(0, 1fr); gap: 0; align-items: start;
			background: var(--pda-paper); border: 1px solid var(--pda-line); box-shadow: 0 1px 2px rgba(0,0,0,.04);
			min-height: 620px;
		}
		.puns-design-admin__main { min-width: 0; display: flex; flex-direction: column; min-height: 620px; }
		.puns-design-admin__sidebar {
			background: #f7f7f7; padding: 0; position: sticky; top: 46px; align-self: stretch;
			border-right: 1px solid var(--pda-line);
		}
		.puns-design-admin__sidebar-head {
			padding: 16px 18px 12px; border-bottom: 1px solid var(--pda-line); background: #fff;
		}
		.puns-design-admin__sidebar-head strong {
			display: block; font-size: 12px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #1d2327;
		}
		.puns-design-admin__sidebar-head span { display: block; margin-top: 4px; font-size: 12px; color: var(--pda-muted); line-height: 1.4; }
		.puns-design-admin__nav { display: flex; flex-direction: column; margin: 0; padding: 8px 0; list-style: none; }
		.puns-design-admin__tab {
			display: flex; align-items: flex-start; justify-content: space-between; gap: 10px;
			margin: 0; padding: 14px 16px; text-decoration: none; color: var(--pda-ink);
			border: 0; border-right: 4px solid transparent; background: transparent;
			transition: background .12s ease, border-color .12s ease, color .12s ease;
		}
		.puns-design-admin__tab:hover { background: #fff; color: var(--pda-brand); }
		.puns-design-admin__tab.is-active,
		.puns-design-admin__tab.is-active:hover {
			background: #fff; border-right-color: var(--pda-brand); color: #1d2327;
			box-shadow: inset 0 0 0 1px #ececec;
		}
		.puns-design-admin__tab-copy { min-width: 0; }
		.puns-design-admin__tab-title { display: block; font-size: 14px; font-weight: 600; line-height: 1.25; }
		.puns-design-admin__tab-desc { display: block; margin-top: 3px; font-size: 12px; color: var(--pda-muted); line-height: 1.35; }
		.puns-design-admin__tab.is-active .puns-design-admin__tab-desc { color: #606060; }
		.puns-design-admin__tab-count {
			display: inline-flex; align-items: center; justify-content: center; min-width: 24px; height: 22px; padding: 0 6px;
			background: #e8e8e8; color: #1d2327; font-size: 11px; font-weight: 700; line-height: 1; flex-shrink: 0; margin-top: 1px;
		}
		.puns-design-admin__tab.is-active .puns-design-admin__tab-count { background: var(--pda-brand); color: #fff; }
		.puns-design-admin__panel { display: none; flex: 1; }
		.puns-design-admin__panel.is-active { display: flex; flex-direction: column; }
		.puns-design-admin__toolbar {
			display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; flex-wrap: wrap;
			padding: 20px 24px 16px; border-bottom: 1px solid #eee; background: #fff;
		}
		.puns-design-admin__toolbar h2 { margin: 0 0 6px; font-size: 1.3em; font-weight: 600; color: #1d2327; padding: 0; }
		.puns-design-admin__hint { margin: 0; font-size: 13px; color: var(--pda-muted); max-width: 42rem; line-height: 1.5; }
		.puns-design-admin__selected {
			display: inline-flex; align-items: center; gap: 8px; padding: 6px 10px 6px 6px;
			background: #f5f8ff; border: 1px solid #d5dff8; color: #1d2327; font-size: 12px; font-weight: 600;
		}
		.puns-design-admin__selected-num {
			display: inline-flex; align-items: center; justify-content: center; min-width: 26px; height: 26px; padding: 0 6px;
			background: var(--pda-brand); color: #fff; font-size: 11px; font-weight: 700;
		}
		.puns-design-admin__grid {
			display: grid; grid-template-columns: repeat(auto-fill, minmax(168px, 1fr)); gap: 14px; padding: 20px 24px 24px; flex: 1;
		}
		.puns-design-admin__choice { position: relative; display: block; margin: 0; cursor: pointer; }
		.puns-design-admin__choice input { position: absolute; opacity: 0; pointer-events: none; }
		.puns-design-admin__card {
			position: relative; border: 1px solid #dcdcde; padding: 10px; background: #fff;
			min-height: 148px; display: flex; flex-direction: column; gap: 10px;
			transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
		}
		.puns-design-admin__choice:hover .puns-design-admin__card {
			border-color: #a7b6ef; box-shadow: 0 2px 8px rgba(56,88,233,.10);
		}
		.puns-design-admin__choice input:focus-visible + .puns-design-admin__card {
			outline: 2px solid var(--pda-brand); outline-offset: 2px;
		}
		.puns-design-admin__choice input:checked + .puns-design-admin__card {
			border-color: var(--pda-brand); background: #f7f9ff;
			box-shadow: 0 0 0 1px var(--pda-brand);
		}
		.puns-design-admin__check {
			position: absolute; top: 8px; right: 8px; z-index: 2; width: 22px; height: 22px;
			background: var(--pda-brand); color: #fff; display: none; align-items: center; justify-content: center;
			font-size: 12px; font-weight: 700;
		}
		.puns-design-admin__choice input:checked + .puns-design-admin__card .puns-design-admin__check { display: inline-flex; }
		.puns-design-admin__preview {
			display: block; width: 100%; height: 84px; overflow: hidden;
			border: 1px solid #e2e4e7; background: #f0f0f1; position: relative;
		}
		.puns-design-admin__preview--global { display: grid; grid-template-columns: 1.4fr 1fr 1fr; gap: 0; background: var(--p3, #f8faff); }
		.puns-design-admin__swatch { display: block; height: 100%; }
		.puns-design-admin__swatch--a { background: var(--p1); }
		.puns-design-admin__swatch--b { background: var(--p2); }
		.puns-design-admin__swatch--c { background: var(--p3); box-shadow: inset 0 0 0 1px rgba(0,0,0,.06); }
		.puns-design-admin__wire { position: absolute; inset: 8px; display: block; }
		.puns-design-admin__wire::before,
		.puns-design-admin__wire::after { content: ""; position: absolute; background: #c3c4c7; }
		.puns-design-admin__meta { display: flex; align-items: flex-start; gap: 8px; }
		.puns-design-admin__num {
			display: inline-flex; align-items: center; justify-content: center; min-width: 26px; height: 26px; padding: 0 5px;
			background: #f0f0f1; font-weight: 700; font-size: 11px; color: #1d2327; flex-shrink: 0;
		}
		.puns-design-admin__choice input:checked + .puns-design-admin__card .puns-design-admin__num { background: var(--pda-brand); color: #fff; }
		.puns-design-admin__label { font-size: 12px; line-height: 1.35; color: #1d2327; font-weight: 600; padding-top: 4px; }
		.puns-design-admin__actions {
			display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
			margin: 0; padding: 14px 24px; background: #f7f7f7; border-top: 1px solid var(--pda-line);
		}
		.puns-design-admin__actions-copy { font-size: 12px; color: var(--pda-muted); }
		.puns-design-admin__actions .button-primary {
			background: var(--pda-brand) !important; border-color: #2f49c7 !important; border-radius: 3px !important;
			height: 32px !important; line-height: 30px !important; padding: 0 14px !important; font-weight: 600 !important;
			text-shadow: none !important; box-shadow: none !important;
		}
		.puns-design-admin__actions .button-primary:hover { background: #2f49c7 !important; }
		@media (max-width: 1100px) {
			.puns-design-admin__layout { grid-template-columns: 240px minmax(0, 1fr); }
		}
		@media (max-width: 900px) {
			.puns-design-admin__layout { grid-template-columns: 1fr; }
			.puns-design-admin__main { border-bottom: 0; min-height: 0; }
			.puns-design-admin__sidebar { position: static; border-right: 0; border-bottom: 1px solid var(--pda-line); }
			.puns-design-admin__nav { flex-direction: row; flex-wrap: wrap; padding: 8px; gap: 6px; }
			.puns-design-admin__tab { flex: 1 1 calc(50% - 6px); border-right: 0; border-bottom: 3px solid transparent; padding: 12px; background: #fff; border: 1px solid #e2e2e2; }
			.puns-design-admin__tab.is-active { border-color: var(--pda-brand); border-bottom-width: 3px; box-shadow: none; }
			.puns-design-admin__tab-desc { display: none; }
			.puns-design-admin__toolbar, .puns-design-admin__grid, .puns-design-admin__actions { padding-left: 16px; padding-right: 16px; }
			.puns-design-admin__grid { grid-template-columns: repeat(auto-fill, minmax(146px, 1fr)); gap: 10px; }
		}


		.puns-design-admin__preview--fonts {
			display: flex; flex-direction: column; justify-content: center; gap: 6px; padding: 12px 14px; background: #fff;
		}
		.puns-design-admin__font-heading { display: block; font-size: 28px; line-height: 1; font-weight: 700; color: #1d2327; }
		.puns-design-admin__font-body { display: block; font-size: 12px; line-height: 1.3; color: #646970; }

		/* Home layout wireframes */
		.puns-design-admin__preview--home .puns-design-admin__wire::before { left: 0; top: 0; width: 42%; height: 62%; background: #3858E9; opacity: .85; }
		.puns-design-admin__preview--home .puns-design-admin__wire::after { right: 0; top: 0; width: 52%; height: 62%; background: #d8dce3; }
		.puns-design-admin__preview--home-02 .puns-design-admin__wire::before { left: 18%; width: 64%; height: 34%; }
		.puns-design-admin__preview--home-02 .puns-design-admin__wire::after { left: 10%; right: 10%; top: 42%; width: auto; height: 40%; }
		.puns-design-admin__preview--home-03 .puns-design-admin__wire::before { display: none; }
		.puns-design-admin__preview--home-03 .puns-design-admin__wire::after { inset: 0 0 auto; width: auto; height: 48%; border-radius: 0; }
		.puns-design-admin__preview--home-04 .puns-design-admin__wire::before { display: none; }
		.puns-design-admin__preview--home-04 .puns-design-admin__wire::after { inset: 0; width: auto; height: auto; background:
			linear-gradient(#d8dce3 0 28%, transparent 28% 36%, #d8dce3 36% 64%, transparent 64% 72%, #d8dce3 72% 100%) 0 0 / 30% 100% no-repeat,
			linear-gradient(#d8dce3 0 28%, transparent 28% 36%, #d8dce3 36% 64%, transparent 64% 72%, #d8dce3 72% 100%) 35% 0 / 30% 100% no-repeat,
			linear-gradient(#d8dce3 0 28%, transparent 28% 36%, #d8dce3 36% 64%, transparent 64% 72%, #d8dce3 72% 100%) 70% 0 / 30% 100% no-repeat; }
		.puns-design-admin__preview--home-05 .puns-design-admin__wire::before { top: auto; bottom: 0; height: 38%; }
		.puns-design-admin__preview--home-05 .puns-design-admin__wire::after { top: 0; height: 52%; }
		.puns-design-admin__preview--home-06 .puns-design-admin__wire::before { width: 48%; height: 100%; }
		.puns-design-admin__preview--home-06 .puns-design-admin__wire::after { width: 48%; height: 100%; }
		.puns-design-admin__preview--home-08 .puns-design-admin__wire::before { width: 100%; height: 22%; }
		.puns-design-admin__preview--home-08 .puns-design-admin__wire::after { top: 30%; width: 100%; height: 22%; box-shadow: 0 28px 0 #d8dce3; }
		.puns-design-admin__preview--home-09 .puns-design-admin__wire::before { width: 100%; height: 42%; }
		.puns-design-admin__preview--home-09 .puns-design-admin__wire::after { top: auto; bottom: 0; width: 48%; height: 42%; box-shadow: 52% 0 0 #d8dce3; }
		.puns-design-admin__preview--home-10 .puns-design-admin__wire::before { height: 28%; width: 55%; }
		.puns-design-admin__preview--home-10 .puns-design-admin__wire::after { top: 40%; height: 48%; }
		.puns-design-admin__preview--home-21 .puns-design-admin__wire::before { width: 50%; height: 100%; }
		.puns-design-admin__preview--home-21 .puns-design-admin__wire::after { width: 44%; height: 100%; }
		.puns-design-admin__preview--home-22 .puns-design-admin__wire::before { display: none; }
		.puns-design-admin__preview--home-22 .puns-design-admin__wire::after { inset: 0; width: auto; height: auto; background: linear-gradient(180deg, rgba(56,88,233,.75), rgba(56,88,233,.2)), #9aa3b2; }
		.puns-design-admin__preview--home-23 .puns-design-admin__wire::before { display: none; }
		.puns-design-admin__preview--home-23 .puns-design-admin__wire::after { inset: 0; width: auto; height: auto; background:
			linear-gradient(#d8dce3,#d8dce3) 0 0 / 48% 46% no-repeat,
			linear-gradient(#d8dce3,#d8dce3) 52% 0 / 48% 46% no-repeat,
			linear-gradient(#d8dce3,#d8dce3) 0 54% / 48% 46% no-repeat,
			linear-gradient(#3858E9,#3858E9) 52% 54% / 48% 46% no-repeat; }
		.puns-design-admin__preview--home-24 .puns-design-admin__wire::before { width: 28%; height: 100%; }
		.puns-design-admin__preview--home-24 .puns-design-admin__wire::after { width: 66%; height: 100%; }
		.puns-design-admin__preview--home-25 .puns-design-admin__wire::before { width: 100%; height: 58%; }
		.puns-design-admin__preview--home-25 .puns-design-admin__wire::after { top: auto; bottom: 0; width: 100%; height: 32%; }
		.puns-design-admin__preview--home-26 .puns-design-admin__wire::before { width: 48%; height: 100%; border-radius: 10px; }
		.puns-design-admin__preview--home-26 .puns-design-admin__wire::after { width: 48%; height: 100%; border-radius: 10px; }
		.puns-design-admin__preview--home-27 .puns-design-admin__wire::before { display: none; }
		.puns-design-admin__preview--home-27 .puns-design-admin__wire::after { inset: 0; width: auto; height: auto; background: repeating-linear-gradient(90deg, #d8dce3 0 18%, transparent 18% 22%); }
		.puns-design-admin__preview--home-28 .puns-design-admin__wire::before { width: 100%; height: 28%; }
		.puns-design-admin__preview--home-28 .puns-design-admin__wire::after { top: 38%; width: 100%; height: 52%; }
		.puns-design-admin__preview--home-29 .puns-design-admin__wire::before { width: 62%; height: 100%; }
		.puns-design-admin__preview--home-29 .puns-design-admin__wire::after { width: 32%; height: 48%; box-shadow: 0 52% 0 #d8dce3; }
		.puns-design-admin__preview--home-30 .puns-design-admin__wire::before { left: 12%; width: 76%; height: 70%; }
		.puns-design-admin__preview--home-30 .puns-design-admin__wire::after { display: none; }
		.puns-design-admin__preview--home-31 .puns-design-admin__wire::before { width: 100%; height: 46%; }
		.puns-design-admin__preview--home-31 .puns-design-admin__wire::after { top: auto; bottom: 0; width: 100%; height: 40%; background: repeating-linear-gradient(90deg, #d8dce3 0 22%, transparent 22% 28%); }
		.puns-design-admin__preview--home-32 .puns-design-admin__wire { inset: 6px; border: 1px solid #dcdcde; border-radius: 8px; background: #fff; }
		.puns-design-admin__preview--home-32 .puns-design-admin__wire::before { left: 8%; width: 38%; height: 70%; top: 12%; }
		.puns-design-admin__preview--home-32 .puns-design-admin__wire::after { right: 8%; width: 38%; height: 70%; top: 12%; }
		.puns-design-admin__preview--home-33 .puns-design-admin__wire::before { width: 70%; height: 22%; }
		.puns-design-admin__preview--home-33 .puns-design-admin__wire::after { top: 34%; width: 100%; height: 56%; }
		.puns-design-admin__preview--home-34 .puns-design-admin__wire::before { width: 40%; height: 100%; }
		.puns-design-admin__preview--home-34 .puns-design-admin__wire::after { width: 52%; height: 28%; box-shadow: 0 36px 0 #d8dce3, 0 72px 0 #e5e7eb; }
		.puns-design-admin__preview--home-35 .puns-design-admin__wire::before { display: none; }
		.puns-design-admin__preview--home-35 .puns-design-admin__wire::after { inset: 0; width: auto; height: auto; background:
			linear-gradient(#d8dce3,#d8dce3) 0 0 / 32% 100% no-repeat,
			linear-gradient(#3858E9,#3858E9) 34% 0 / 32% 100% no-repeat,
			linear-gradient(#d8dce3,#d8dce3) 68% 0 / 32% 100% no-repeat; }
		.puns-design-admin__preview--home-36 .puns-design-admin__wire::before { width: 100%; height: 14%; }
		.puns-design-admin__preview--home-36 .puns-design-admin__wire::after { top: 24%; width: 100%; height: 14%; box-shadow: 0 22px 0 #d8dce3, 0 44px 0 #e5e7eb; }
		.puns-design-admin__preview--home-37 .puns-design-admin__wire::before { width: 88%; height: 36%; }
		.puns-design-admin__preview--home-37 .puns-design-admin__wire::after { top: 48%; width: 100%; height: 42%; }
		.puns-design-admin__preview--home-38 .puns-design-admin__wire::before { width: 100%; height: 34%; border-radius: 0; }
		.puns-design-admin__preview--home-38 .puns-design-admin__wire::after { top: 44%; width: 70%; height: 40%; }
		.puns-design-admin__preview--home-39 .puns-design-admin__wire::before { width: 48%; height: 100%; }
		.puns-design-admin__preview--home-39 .puns-design-admin__wire::after { width: 48%; height: 100%; background: #3858E9; opacity: .55; }
		.puns-design-admin__preview--home-40 .puns-design-admin__wire::before { width: 100%; height: 18%; }
		.puns-design-admin__preview--home-40 .puns-design-admin__wire::after { top: 28%; width: 100%; height: 18%; box-shadow: 0 26px 0 #d8dce3, 0 52px 0 #e5e7eb; }

		/* Single layout wireframes */
		.puns-design-admin__preview--single .puns-design-admin__wire::before { left: 18%; right: 18%; top: 6%; height: 14%; width: auto; background: #3858E9; }
		.puns-design-admin__preview--single .puns-design-admin__wire::after { left: 18%; right: 18%; top: 28%; bottom: 8%; width: auto; height: auto; background:
			repeating-linear-gradient(180deg, #d8dce3 0 5px, transparent 5px 11px); }
		.puns-design-admin__preview--single-02 .puns-design-admin__wire::before,
		.puns-design-admin__preview--single-02 .puns-design-admin__wire::after { left: 28%; right: 28%; }
		.puns-design-admin__preview--single-03 .puns-design-admin__wire::before,
		.puns-design-admin__preview--single-03 .puns-design-admin__wire::after { left: 6%; right: 6%; }
		.puns-design-admin__preview--single-04 .puns-design-admin__wire::before { left: 6%; right: 6%; height: 34%; top: 0; border-radius: 0; background: #d8dce3; }
		.puns-design-admin__preview--single-04 .puns-design-admin__wire::after { top: 42%; }
		.puns-design-admin__preview--single-05 .puns-design-admin__wire::before { left: 6%; right: 38%; }
		.puns-design-admin__preview--single-05 .puns-design-admin__wire::after { left: 6%; right: 38%; box-shadow: calc(100% + 10px) 0 0 #e8eaed; }
		.puns-design-admin__preview--single-10 .puns-design-admin__wire { inset: 6px; border: 1px solid #dcdcde; border-radius: 6px; background: #fff; }
		.puns-design-admin__preview--single-10 .puns-design-admin__wire::before { left: 10%; right: 10%; top: 10%; }
		.puns-design-admin__preview--single-10 .puns-design-admin__wire::after { left: 10%; right: 10%; top: 34%; bottom: 10%; }
		.puns-design-admin__preview--single-20 .puns-design-admin__wire { inset: 8px; border: 2px solid #3858E9; border-radius: 6px; }
		.puns-design-admin__preview--single-20 .puns-design-admin__wire::before { left: 12%; right: 12%; top: 12%; }
		.puns-design-admin__preview--single-20 .puns-design-admin__wire::after { left: 12%; right: 12%; top: 36%; bottom: 12%; }
		.puns-design-admin__preview--single-21 .puns-design-admin__wire::before { left: 6%; right: 6%; top: 0; height: 40%; background: #d8dce3; }
		.puns-design-admin__preview--single-21 .puns-design-admin__wire::after { top: 48%; }
		.puns-design-admin__preview--single-22 .puns-design-admin__wire::after { background: linear-gradient(#3858E9,#3858E9) 0 0 / 14px 18px no-repeat, repeating-linear-gradient(180deg, #d8dce3 0 5px, transparent 5px 11px); }
		.puns-design-admin__preview--single-24 .puns-design-admin__wire::before { height: 8px; top: 18%; background: #3858E9; }
		.puns-design-admin__preview--single-25 .puns-design-admin__wire::before { left: 18%; width: 36%; right: auto; height: 10px; border-radius: 999px; }
		.puns-design-admin__preview--single-27 .puns-design-admin__wire::before { left: 6%; right: 34%; }
		.puns-design-admin__preview--single-27 .puns-design-admin__wire::after { left: 6%; right: 34%; box-shadow: calc(100% + 10px) 0 0 #e8eaed; }
		.puns-design-admin__preview--single-28 .puns-design-admin__wire { background: #f3f4f6; }
		.puns-design-admin__preview--single-29 .puns-design-admin__wire::before,
		.puns-design-admin__preview--single-29 .puns-design-admin__wire::after { left: 30%; right: 30%; }
		.puns-design-admin__preview--single-33 .puns-design-admin__wire::before { left: 6%; right: 42%; }
		.puns-design-admin__preview--single-33 .puns-design-admin__wire::after { left: 6%; right: 42%; box-shadow: calc(100% + 8px) 0 0 #e8eaed; }
		.puns-design-admin__preview--single-38 .puns-design-admin__wire::before { left: 0; right: 0; width: auto; height: 22%; top: 0; }
		.puns-design-admin__preview--single-40 .puns-design-admin__wire::after { bottom: 10%; top: auto; height: 12px; background: #3858E9; }

		/* Tile / card wireframes */
		.puns-design-admin__preview--tile .puns-design-admin__wire::before { left: 0; right: 0; top: 0; height: 52%; width: auto; background: #d8dce3; }
		.puns-design-admin__preview--tile .puns-design-admin__wire::after { left: 8%; right: 20%; top: 62%; height: 8px; width: auto; background: #3858E9; box-shadow: 0 14px 0 #d8dce3, 0 26px 0 #e5e7eb; }
		.puns-design-admin__preview--tile-02 .puns-design-admin__wire::before { width: 38%; right: auto; height: auto; bottom: 0; }
		.puns-design-admin__preview--tile-02 .puns-design-admin__wire::after { left: 46%; right: 6%; top: 18%; }
		.puns-design-admin__preview--tile-03 .puns-design-admin__wire::before { display: none; }
		.puns-design-admin__preview--tile-03 .puns-design-admin__wire::after { top: 22%; }
		.puns-design-admin__preview--tile-04 .puns-design-admin__wire::before { inset: 0; width: auto; height: auto; background: linear-gradient(180deg, #c5cad3, #9aa3b2); }
		.puns-design-admin__preview--tile-04 .puns-design-admin__wire::after { left: 10%; right: 25%; top: auto; bottom: 14%; background: #fff; box-shadow: 0 12px 0 rgba(255,255,255,.7); }
		.puns-design-admin__preview--tile-05 .puns-design-admin__wire { border: 2px solid #3858E9; border-radius: 6px; }
		.puns-design-admin__preview--tile-06 .puns-design-admin__wire::before { border-radius: 16px 16px 0 0; }
		.puns-design-admin__preview--tile-07 .puns-design-admin__wire { box-shadow: 0 8px 16px rgba(15,23,42,.18); background: #fff; border-radius: 6px; }
		.puns-design-admin__preview--tile-08 .puns-design-admin__wire::before { width: 28%; right: auto; height: auto; bottom: 0; border-radius: 50%; }
		.puns-design-admin__preview--tile-08 .puns-design-admin__wire::after { left: 38%; }
		.puns-design-admin__preview--tile-09 .puns-design-admin__wire::before { height: 70%; }
		.puns-design-admin__preview--tile-12 .puns-design-admin__wire::before { left: 50%; top: 8%; width: 36px; height: 36px; right: auto; border-radius: 50%; transform: translateX(-50%); }
		.puns-design-admin__preview--tile-13 .puns-design-admin__wire { border-left: 4px solid #3858E9; }
		.puns-design-admin__preview--tile-14 .puns-design-admin__wire::before { background: linear-gradient(135deg, #3858E9, #93c5fd); }
		.puns-design-admin__preview--tile-20 .puns-design-admin__wire::before { width: 8px; right: auto; height: auto; bottom: 0; background: #3858E9; }
		.puns-design-admin__preview--tile-20 .puns-design-admin__wire::after { left: 18%; }
		.puns-design-admin__preview--tile-21 .puns-design-admin__wire { box-shadow: 0 6px 14px rgba(15,23,42,.12); background: #fff; border-radius: 8px; }
		.puns-design-admin__preview--tile-22 .puns-design-admin__wire::before { height: 8px; background: #3858E9; }
		.puns-design-admin__preview--tile-22 .puns-design-admin__wire::after { top: 22%; }
		.puns-design-admin__preview--tile-23 .puns-design-admin__wire::before { left: 8%; right: 8%; top: 8%; width: auto; height: 44%; border-radius: 6px; }
		.puns-design-admin__preview--tile-24 .puns-design-admin__wire::after { width: 28%; right: auto; height: 10px; border-radius: 999px; box-shadow: 0 16px 0 #d8dce3, 0 28px 0 #e5e7eb; }
		.puns-design-admin__preview--tile-25 .puns-design-admin__wire::before { height: 40%; }
		.puns-design-admin__preview--tile-26 .puns-design-admin__wire { border: 1px solid #c3c4c7; border-radius: 6px; background: #fff; }
		.puns-design-admin__preview--tile-27 .puns-design-admin__wire { transform: translateY(-2px); box-shadow: 0 10px 18px rgba(15,23,42,.16); background: #fff; border-radius: 6px; }
		.puns-design-admin__preview--tile-28 .puns-design-admin__wire::before { aspect-ratio: 1; height: auto; bottom: 38%; }
		.puns-design-admin__preview--tile-29 .puns-design-admin__wire::after { width: 36%; right: auto; height: 10px; border-radius: 999px; }
		.puns-design-admin__preview--tile-30 .puns-design-admin__wire::before { height: 34%; }
		.puns-design-admin__preview--tile-30 .puns-design-admin__wire::after { top: 46%; height: 14px; }
		.puns-design-admin__preview--tile-31 .puns-design-admin__wire { border: 1px solid rgba(56,88,233,.35); background: rgba(255,255,255,.7); border-radius: 8px; }
		.puns-design-admin__preview--tile-32 .puns-design-admin__wire { border-bottom: 3px solid #3858E9; }
		.puns-design-admin__preview--tile-33 .puns-design-admin__wire::before { width: 24%; right: auto; height: auto; bottom: 0; }
		.puns-design-admin__preview--tile-33 .puns-design-admin__wire::after { left: 34%; }
		.puns-design-admin__preview--tile-34 .puns-design-admin__wire::before { height: 78%; }
		.puns-design-admin__preview--tile-35 .puns-design-admin__wire::before { background: linear-gradient(180deg, #e8ecff, #d8dce3); }
		.puns-design-admin__preview--tile-36 .puns-design-admin__wire { inset: 4px; }
		.puns-design-admin__preview--tile-37 .puns-design-admin__wire { border: 2px solid #dcdcde; border-radius: 0; background: transparent; }
		.puns-design-admin__preview--tile-38 .puns-design-admin__wire::after { top: 10%; left: auto; right: 8%; width: 28%; height: 12px; background: #3858E9; box-shadow: none; border-radius: 0 0 4px 4px; }
		.puns-design-admin__preview--tile-39 .puns-design-admin__wire::after { background: #3858E9; box-shadow: 0 14px 0 rgba(56,88,233,.35), 0 26px 0 #e5e7eb; }
		.puns-design-admin__preview--tile-40 .puns-design-admin__wire::before { clip-path: polygon(0 0, 100% 0, 100% 82%, 0 100%); }
	';

	wp_add_inline_style( 'wp-admin', preg_replace( '/\s+/', ' ', $css ) );
	wp_enqueue_script( 'jquery' );

	$js = <<<'JS'
(function () {
  function updateSelected(area, number, name) {
    var panel = document.getElementById('puns-design-' + area);
    if (panel) {
      var num = panel.querySelector('.puns-design-admin__selected-num');
      var label = panel.querySelector('.puns-design-admin__selected-name');
      if (num) num.textContent = number;
      if (label) label.textContent = name;
    }
    var tabCount = document.querySelector('.puns-design-admin__tab[data-area="' + area + '"] .puns-design-admin__tab-count');
    if (tabCount) tabCount.textContent = number;
  }

  function activateTab(area, push) {
    var tabs = document.querySelectorAll('.puns-design-admin__tab');
    var panels = document.querySelectorAll('.puns-design-admin__panel');
    tabs.forEach(function (tab) {
      var on = tab.getAttribute('data-area') === area;
      tab.classList.toggle('is-active', on);
      tab.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    panels.forEach(function (panel) {
      var on = panel.getAttribute('data-area') === area;
      panel.classList.toggle('is-active', on);
      if (on) {
        panel.removeAttribute('hidden');
      } else {
        panel.setAttribute('hidden', 'hidden');
      }
    });
    if (push && window.history && window.history.replaceState) {
      var url = new URL(window.location.href);
      url.searchParams.set('tab', area);
      window.history.replaceState({}, '', url.toString());
      var referer = document.querySelector('input[name="_wp_http_referer"]');
      if (referer) referer.value = url.pathname + url.search;
    }
  }

  document.addEventListener('click', function (event) {
    var tab = event.target.closest('.puns-design-admin__tab');
    if (!tab || !tab.closest('.puns-design-admin')) return;
    event.preventDefault();
    activateTab(tab.getAttribute('data-area'), true);
  });

  document.addEventListener('change', function (event) {
    var input = event.target;
    if (!input || !input.matches('input[type="radio"][name^="puns_design_options"]')) return;
    var match = input.name.match(/puns_design_options\[([^\]]+)\]/);
    if (!match) return;
    var area = match[1];
    var card = input.closest('.puns-design-admin__choice');
    var label = card ? card.querySelector('.puns-design-admin__label') : null;
    updateSelected(area, input.value, label ? label.textContent.trim() : '');
  });
})();
JS;

	wp_add_inline_script( 'jquery', $js );
}
add_action( 'admin_enqueue_scripts', 'puns_design_options_admin_assets' );

/**
 * Short labels for Designs tabs.
 *
 * @return array<string, string>
 */
function puns_design_tab_labels() {
	return array(
		'global' => __( 'Colors', 'puns' ),
		'fonts'  => __( 'Fonts', 'puns' ),
		'home'   => __( 'Home', 'puns' ),
		'single' => __( 'Single', 'puns' ),
		'tile'   => __( 'Tiles', 'puns' ),
	);
}

/**
 * Current Designs admin tab.
 *
 * @return string
 */
function puns_get_design_admin_tab() {
	$tab  = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'global'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$tabs = array_keys( puns_design_tab_labels() );

	if ( ! in_array( $tab, $tabs, true ) ) {
		return 'global';
	}

	return $tab;
}

/**
 * Render the Designs admin page.
 */
function puns_render_design_options_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$options = get_option( 'puns_design_options', puns_default_design_options() );
	$options = puns_sanitize_design_options( $options );
	$current = puns_get_design_admin_tab();
	$labels  = puns_design_area_labels();
	$tabs    = puns_design_tab_labels();

	$hints = array(
		'global' => __( 'Pick the site-wide color palette. This changes buttons, links, tags, and accents everywhere.', 'puns' ),
		'fonts'  => __( 'Pick a heading and body font pairing for the whole site.', 'puns' ),
		'home'   => __( 'Choose how the homepage hero and latest posts are arranged.', 'puns' ),
		'single' => __( 'Choose the reading layout used on single posts and static pages.', 'puns' ),
		'tile'   => __( 'Choose the card style used for post tiles in grids and lists.', 'puns' ),
	);

	$tab_desc = array(
		'global' => __( 'Site colors & accents', 'puns' ),
		'fonts'  => __( 'Heading & body type', 'puns' ),
		'home'   => __( 'Homepage layout', 'puns' ),
		'single' => __( 'Article layout', 'puns' ),
		'tile'   => __( 'Card / tile style', 'puns' ),
	);

	$base_url = admin_url( 'themes.php?page=puns-designs' );
	?>
	<div class="wrap puns-design-admin">
		<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
			<?php settings_fields( 'puns_designs' ); ?>

			<div class="puns-design-admin__layout">
				<aside class="puns-design-admin__sidebar" aria-label="<?php esc_attr_e( 'Design sections', 'puns' ); ?>">
					<div class="puns-design-admin__sidebar-head">
						<strong><?php esc_html_e( 'Sections', 'puns' ); ?></strong>
						<span><?php esc_html_e( 'Choose what you want to customize.', 'puns' ); ?></span>
					</div>
					<nav class="puns-design-admin__nav">
						<?php foreach ( $tabs as $area => $tab_label ) : ?>
							<?php
							$url          = add_query_arg( 'tab', $area, $base_url );
							$is_active    = ( $current === $area );
							$selected_num = (int) $options[ $area ];
							?>
							<a
								class="puns-design-admin__tab<?php echo $is_active ? ' is-active' : ''; ?>"
								href="<?php echo esc_url( $url ); ?>"
								data-area="<?php echo esc_attr( $area ); ?>"
								aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
							>
								<span class="puns-design-admin__tab-copy">
									<span class="puns-design-admin__tab-title"><?php echo esc_html( $tab_label ); ?></span>
									<span class="puns-design-admin__tab-desc"><?php echo esc_html( isset( $tab_desc[ $area ] ) ? $tab_desc[ $area ] : '' ); ?></span>
								</span>
								<span class="puns-design-admin__tab-count"><?php echo esc_html( (string) $selected_num ); ?></span>
							</a>
						<?php endforeach; ?>
					</nav>
				</aside>

				<div class="puns-design-admin__main">
					<?php foreach ( $labels as $area => $label ) : ?>
						<?php
						$choices       = puns_get_design_choices( $area );
						$selected_num  = (int) $options[ $area ];
						$selected_name = isset( $choices[ $selected_num ] ) ? $choices[ $selected_num ] : '';
						$is_active     = ( $current === $area );
						?>
						<section
							class="puns-design-admin__panel<?php echo $is_active ? ' is-active' : ''; ?>"
							id="puns-design-<?php echo esc_attr( $area ); ?>"
							data-area="<?php echo esc_attr( $area ); ?>"
							<?php echo $is_active ? '' : 'hidden'; ?>
						>
							<div class="puns-design-admin__toolbar">
								<div>
									<h2><?php echo esc_html( $label ); ?></h2>
									<?php if ( ! empty( $hints[ $area ] ) ) : ?>
										<p class="puns-design-admin__hint"><?php echo esc_html( $hints[ $area ] ); ?></p>
									<?php endif; ?>
								</div>
								<div class="puns-design-admin__selected" aria-live="polite">
									<span class="puns-design-admin__selected-num"><?php echo esc_html( (string) $selected_num ); ?></span>
									<span class="puns-design-admin__selected-name"><?php echo esc_html( $selected_name ); ?></span>
								</div>
							</div>
							<div class="puns-design-admin__grid">
								<?php foreach ( $choices as $number => $name ) : ?>
									<label class="puns-design-admin__choice">
										<input
											type="radio"
											name="puns_design_options[<?php echo esc_attr( $area ); ?>]"
											value="<?php echo esc_attr( (string) $number ); ?>"
											<?php checked( $selected_num, (int) $number ); ?>
										>
										<span class="puns-design-admin__card">
											<span class="puns-design-admin__check" aria-hidden="true">✓</span>
											<?php puns_render_design_preview( $area, (int) $number ); ?>
											<span class="puns-design-admin__meta">
												<span class="puns-design-admin__num"><?php echo esc_html( (string) $number ); ?></span>
												<span class="puns-design-admin__label"><?php echo esc_html( $name ); ?></span>
											</span>
										</span>
									</label>
								<?php endforeach; ?>
							</div>
						</section>
					<?php endforeach; ?>

					<div class="puns-design-admin__actions">
						<div class="puns-design-admin__actions-copy">
							<?php esc_html_e( 'Save to publish your selections from every section.', 'puns' ); ?>
						</div>
						<?php
						submit_button(
							__( 'Save Designs', 'puns' ),
							'primary',
							'submit',
							false
						);
						?>
					</div>
				</div>
			</div>
		</form>
	</div>
	<?php
}

/**
 * Append design classes to the body tag.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function puns_design_body_classes( $classes ) {
	$classes[] = puns_design_class( 'global' );
	$classes[] = puns_design_class( 'fonts' );

	// Static pages share single + tile layouts so About / Write / Contact switch with Designs.
	if ( is_singular( 'post' ) || is_page() ) {
		$classes[] = puns_design_class( 'single' );
	}

	if ( is_home() || is_front_page() || is_archive() || is_search() || is_404() ) {
		$classes[] = puns_design_class( 'home' );
	}

	if ( is_home() || is_front_page() || is_archive() || is_search() || is_singular( 'post' ) || is_page() || is_404() ) {
		$classes[] = puns_design_class( 'tile' );
	}

	return $classes;
}
add_filter( 'body_class', 'puns_design_body_classes', 20 );

/**
 * Enqueue design variant stylesheet and selected fonts.
 */
function puns_enqueue_design_styles() {
	wp_enqueue_style(
		'puns-designs',
		get_template_directory_uri() . '/assets/css/designs.css',
		array( 'puns' ),
		PUNS_VERSION
	);

	$preset = puns_get_font_preset( puns_get_design( 'fonts' ) );
	$url    = puns_font_google_url( isset( $preset['google'] ) ? $preset['google'] : array() );

	if ( $url ) {
		wp_enqueue_style( 'puns-design-fonts', $url, array(), null );
	}
}
add_action( 'wp_enqueue_scripts', 'puns_enqueue_design_styles', 20 );

/**
 * Load Google Fonts on the Designs admin screen for previews.
 *
 * @param string $hook_suffix Current admin page hook.
 */
function puns_design_fonts_admin_assets( $hook_suffix ) {
	if ( 'appearance_page_puns-designs' !== $hook_suffix ) {
		return;
	}

	$families = array();
	foreach ( puns_get_font_presets() as $preset ) {
		if ( empty( $preset['google'] ) || ! is_array( $preset['google'] ) ) {
			continue;
		}
		foreach ( $preset['google'] as $family ) {
			$families[ $family ] = $family;
		}
	}

	$url = puns_font_google_url( array_values( $families ) );
	if ( $url ) {
		wp_enqueue_style( 'puns-design-fonts-admin', $url, array(), null );
	}
}
add_action( 'admin_enqueue_scripts', 'puns_design_fonts_admin_assets', 20 );
