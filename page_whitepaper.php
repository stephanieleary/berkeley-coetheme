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

//* Remove navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' );
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
	echo '<h3>In this section:</h3>' . do_shortcode( '[child-pages]' );
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
	
	if ( isset( $pages[$key+1] ) )
		$next = sprintf( '<a class="next alignright" href="%s">Next: %s</a>', get_the_permalink( $pages[$key+1], get_the_title( $pages[$key+1] ) ) );
	
	if ( isset( $pages[$key-1] ) )
		$prev = sprintf( '<a class="prev alignleft" href="%s">Previous: %s</a>', get_the_permalink( $pages[$key-1], get_the_title( $pages[$key-1] ) ) );
	
	$parent = wp_get_post_parent_id( $post_id );
		
	if ( isset( $parent ) && !empty( $parent ) )
		$top = sprintf( '<a class="top aligncenter" href="%s">Up: %s</a>', get_the_permalink( $parent ), get_the_title( $parent ) );
	
	echo $prev . $top . $next;
}

function berkeley_whitepaper_flatten_array(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

//* Run the Genesis loop
genesis();