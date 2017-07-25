<?php

remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
remove_action( 'genesis_post_content', 'genesis_do_post_image' );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 1 );

// add Stickies loop above the main loop
add_action( 'genesis_loop', 'berkeley_sticky_post_loop', 1 );
add_action( 'genesis_loop', 'berkeley_people_types_loop', 10 );
remove_action( 'genesis_loop', 'genesis_do_loop' );

function berkeley_people_types_loop( $taxonomy ) {
	if ( empty( $taxonomy ) )
		$taxonomy = 'people_type';
	$terms = get_terms( array(
	    'taxonomy' => $taxonomy,
	    'hide_empty' => true,
	) );
	if ( empty( $terms ) || is_wp_error( $terms ) )
		return;

	global $query_args;
	foreach ( $terms as $term ) {
		
		$args = array(
			'fields' => 'ids',
			'posts_per_page'  => -1,
			'posts_per_archive_page' => -1,
			'post_type' => 'people',
			'tax_query' => array(
					array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $term->slug,
					),
				),
		);
		
		$have_posts = wp_parse_args( $args, $query_args );
		if ( count( $have_posts ) ) {
			echo '<div class="people_type_loop '.$term->slug. ' ' .$taxonomy.'">';
			remove_action( 'genesis_loop_else', 'genesis_do_noposts' );
			remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
			printf( '<h2 %s>%s</h2>', genesis_attr( 'archive-title' ), strip_tags( $term->name ) );
			unset( $args['fields'] );
			genesis_custom_loop( wp_parse_args( $args, $query_args ) );
			echo '</div>';
		}
		
	}
}

genesis();