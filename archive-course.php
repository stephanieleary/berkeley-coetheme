<?php

remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
remove_action( 'genesis_post_content', 'genesis_do_post_image' );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 1 );
	
// add Stickies loop above the main loop
add_action( 'genesis_loop', 'berkeley_sticky_post_loop', 1 );	

// replace the usual post listing with directory table
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'berkeley_course_table_loop', 10 );


function berkeley_course_table_loop() {
	if ( have_posts() ) :

		do_action( 'genesis_before_while' );
		
		// see if there are any courses with times. If so, add that column to the table headers and data arrays.
		$args = array(
			'post_type'  	  => 'course',
			'posts_per_page'  => -1,
			'fields' 		  => 'ids',
			'meta_query' 	  => array(
				array(
					'key' 	  => 'times',
					'compare' => 'EXISTS',
				)
			)
		 );
		$havetimes = get_posts( $args );
		
		$headers = array( esc_html__('Course', 'berkeley-coe-theme'), esc_html__('Number', 'berkeley-coe-theme'), esc_html__('Instructor(s)', 'berkeley-coe-theme') );
		if ( count( $havetimes ) )
			$headers[] = esc_html__('Time', 'berkeley-coe-theme');
			
		echo berkeley_loop_table_headers( $headers );
	
		while ( have_posts() ) : the_post();
		
			//do_action( 'genesis_before_entry' );
			
			$data = array( 
				sprintf( '<a href="%s" title="%s">%s</a>', esc_url( get_permalink() ), the_title_attribute( 'echo=0' ), get_the_title() ),
				get_field( 'course_number' ),
				get_field( 'instructors' )		
			);
			if ( count( $havetimes ) )
				$data[] = get_field( 'times' );
			
			echo berkeley_loop_table_cells( array_combine( $headers, $data ) );
			
			//do_action( 'genesis_after_entry' );

		endwhile; //* end of one post
		
		echo '</tbody></table>';
		
		echo '</div>';
		
		do_action( 'genesis_after_endwhile' );

	else : //* if no posts exist
		do_action( 'genesis_loop_else' );
	endif; //* end loop
}

genesis();