<?php
/**
 * Front page — editable Home page when a static front page is set.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) > 0 ) {
	$template = locate_template( 'page-templates/home.php' );
	if ( $template ) {
		include $template;
		return;
	}
}

require get_template_directory() . '/index.php';
