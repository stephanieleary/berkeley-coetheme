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

add_action( 'genesis_entry_footer', 'berkeley_whitepaper_children' );
add_action( 'genesis_entry_footer', 'berkeley_whitepaper_nav' );

function berkeley_whitepaper_children() {
	$prefix = apply_filters( 'berkeley_whitepaper_toc_heading', __( 'In this section:', 'beng' ) );
	echo '<h3>' . $prefix . '</h3>' . do_shortcode( '[child-pages]' );
}

function berkeley_whitepaper_nav() {
	$post_id = get_the_ID();
	$prev = $top = $next = '';
	
	// get all Whitepaper pages in a flat list
	$args = array(
		'sort_column' => 'menu_order',
		'sort_order' => 'ASC',
		'fields' => 'ids',
		'post_type' => 'page',
		'meta_key' => '_wp_page_template',
		'meta_value' => 'page_whitepaper.php'
	);
	$pages = get_pages( $args );
	$pages = berkeley_whitepaper_flatten_array( $pages );
	$key = array_search( $post_id, $pages );
	
	if ( isset( $pages[$key+1] ) ) {
		$prefix = apply_filters( 'berkeley_whitepaper_next_label', __( 'Next:', 'beng' ) );
		$next = sprintf( '<a class="next alignright" href="%s">%s %s</a>', get_the_permalink( $pages[$key+1], $prefix, get_the_title( $pages[$key+1] ) ) );
	}
	
	if ( isset( $pages[$key-1] ) ) {
		$prefix = apply_filters( 'berkeley_whitepaper_previous_label', __( 'Previous:', 'beng' ) );
		$prev = sprintf( '<a class="prev alignleft" href="%s">%s %s</a>', get_the_permalink( $pages[$key-1], $prefix, get_the_title( $pages[$key-1] ) ) );
	}
	
	$parent = wp_get_post_parent_id( $post_id );
		
	if ( isset( $parent ) && !empty( $parent ) ) {
		$prefix = apply_filters( 'berkeley_whitepaper_parent_label', __( 'Up:', 'beng' ) );
		$top = sprintf( '<a class="top aligncenter" href="%s">%s %s</a>', get_the_permalink( $parent ), $prefix, get_the_title( $parent ) );
	}
	
	echo $prev . $top . $next;
}

function berkeley_whitepaper_flatten_array(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

//* Run the Genesis loop
genesis();