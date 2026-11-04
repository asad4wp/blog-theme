<?php
/**
 * About Us page — block content from the editor.
 *
 * @package Puns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'puns-article puns-static puns-static--about' ); ?>>
	<div class="puns-article__body entry-content puns-static__editor">
		<?php the_content(); ?>
	</div>
</article>
