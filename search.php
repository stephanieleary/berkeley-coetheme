<?php

// Search results title
remove_action( 'genesis_before_loop', 'genesis_do_search_title' );
add_action( 'genesis_before_loop', 'berkeley_do_search_title' );

function berkeley_do_search_title() {
	global $wp_query;
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	if ( $paged > 1 ) {
		$perpage = get_query_var( 'posts_per_page' );
		$previous = $paged - 1;
		$start = ( $perpage * $previous ) + 1;
		$end = $paged * $wp_query->post_count;
	}
	else {
		$start = 1;
		$end = $wp_query->post_count;
	}
	
	printf( '<div class="archive-description"><h1 class="archive-title">Results %d&ndash;%d of %d for %s</h1></div>', $start, $end, $wp_query->found_posts, esc_html( get_search_query() ) );
}

genesis();