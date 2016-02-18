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

//* Remove breadcrumbs
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

//* Remove site footer elements
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_footer_widget_areas', 6 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'berkeley_custom_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

add_action( 'genesis_footer', 'berkeley_whitepaper_footer' );

// footer without navigation menus
berkeley_whitepaper_footer() {
	echo '<div class="footer-content">';
	echo do_shortcode( get_field( 'footer_text', 'option' ) );
	echo '</div><!-- end .footer-content -->';
}

berkeley_whitepaper_nav() {
	$post_id = get_the_ID();
	$prev = $top = $next = '';
	
	$args = array(
		'sort_column' => 'menu_order',
		'sort_order' => 'ASC',
		'fields' => 'ids'
	);
	$pages = get_pages( $args );
	$pages = berkeley_whitepaper_flatten_array( $pages );
	$key = array_search( $post_id, $pages );
	
	if ( isset( $pages[$key+1] ) && 'page_whitepaper.php' == get_post_meta( $pages[$key+1], '_wp_page_template', true ) ) {
		$next = sprintf( '<a class="next alignright" href="%s">%s</a>', get_the_permalink( $pages[$key+1], get_the_title( $pages[$key+1] ) ) )
	}
	if ( isset( $pages[$key-1] ) && 'page_whitepaper.php' == get_post_meta( $pages[$key-1], '_wp_page_template', true ) )
		$prev = sprintf( '<a class="prev alignleft" href="%s">%s</a>', get_the_permalink( $pages[$key-1], get_the_title( $pages[$key-1] ) ) );
	}
	
	$top_id = berkeley_top_whitepaper_parent();
	if ( !empty( $top_id ) && $page_id !== $top_id )
		$top = sprintf( '<a class="top aligncenter" href="%s">%s</a>', get_the_permalink( $top_id, get_the_title( $top_id ) ) );
	
	echo $prev . $top . $next;
}

function berkeley_whitepaper_flatten_array(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

function berkeley_top_whitepaper_parent( $post_id = NULL ) {
	if ( !is_page() )
    	return;

    if ( !isset( $post_id ) )
        $post_id = get_the_ID();
		
	$ancestors = get_ancestors( $post_id, 'page' );

	if ( !empty( $ancestors ) ) {
		global $wpdb;
		$all_whitepapers = $wpdb->prepare( $wpdb->get_col( 
			"SELECT post_id FROM $wpdb->postmeta
			 WHERE meta_key = %s
			 AND meta_value = %s
			 GROUP BY post_id" ),
			'_wp_page_template',
			'page_whitepaper.php'
		);
		$parent_whitepapers = array_merge( $ancestors, $all_whitepapers );
		if ( !empty( $parent_whitepapers ) )
			return end( $parent_whitepapers );
	}
	
	return $post_id;
}

//* Run the Genesis loop
genesis();