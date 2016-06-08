<?php
/*
Template Name: White Paper
*/

//* Add custom body class to the head
add_filter( 'body_class', 'berkeley_whitepaper_body_class' );
function berkeley_whitepaper_body_class( $classes ) {
   $classes[] = 'whitepaper';
   return $classes;  
}

//* Force full width content layout
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

//* Remove site header elements
remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );

add_action( 'genesis_header', 'berkeley_whitepaper_header' );

function berkeley_whitepaper_header() {
	genesis_markup( array(
		'html5'   => '<div %s>',
		'xhtml'   => '<div id="title-area">',
		'context' => 'title-area',
	) );
	do_action( 'genesis_site_title' );
	// do_action( 'genesis_site_description' );
	echo '</div>';
}

//* Remove navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_after_header', 'berkeley_do_nav' );
remove_action( 'genesis_after_header', 'genesis_do_subnav' );

//* Remove breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

//* Remove site footer elements
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_footer_widget_areas', 6 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'berkeley_custom_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

add_action( 'genesis_footer', 'berkeley_whitepaper_footer' );

// footer without navigation menus or widget areas
function berkeley_whitepaper_footer() {
	echo '<div class="footer-content"><div class="wrap">';
	do_action( 'whitepaper_footer' );
	echo do_shortcode( get_field( 'footer_text', 'option' ) );
	echo '</div></div><!-- end .footer-content -->';
}

add_action( 'genesis_entry_footer', 'berkeley_whitepaper_do_sidebar' );

function berkeley_whitepaper_do_sidebar() {
	dynamic_sidebar( 'whitepaper' );
}

//* Run the Genesis loop
genesis();