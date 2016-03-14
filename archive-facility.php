<?php

// replace the usual post listing with links to the facility taxonomy archives
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'berkeley_facility_loop' );

function berkeley_facility_loop() {
	
	$terms = get_terms( 'facility_type', array( 'fields' => 'id=>name', 'hide_empty' => false ) );
	
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		
		echo '<ul>';
		foreach ( $terms as $term_id => $term_name ) {
			printf( '<li><a href="%s">%s</a></li>', get_term_link( $term_id, 'facility_type' ), $term_name );
		}
		echo '</ul>';
	}
}

genesis();