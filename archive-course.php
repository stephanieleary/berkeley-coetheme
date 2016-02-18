<?php
// replace the usual post listing with directory table
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'berkeley_course_table_loop' );

function berkeley_course_table_loop() {
	if ( have_posts() ) :

		do_action( 'genesis_before_while' );
		
		$headers = array( __('Course Number'), __('Credits'), __('Title') );
		echo berkeley_loop_table_headers( $headers );
	
		while ( have_posts() ) : the_post();
		
			do_action( 'genesis_before_entry' );
			
			$data = array( 
				__('Course Number') => sprintf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), get_field( 'course_number' ) ),
				__('Credits') => get_field( 'credits' ),
				__('Title') => sprintf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), get_the_title() )		
					);
			
			echo berkeley_loop_table_cells( $data );
			
			do_action( 'genesis_after_entry' );

		endwhile; //* end of one post
		
		echo '</tbody></table>';
		
		echo '</div>';
		
		do_action( 'genesis_after_endwhile' );

	else : //* if no posts exist
		do_action( 'genesis_loop_else' );
	endif; //* end loop
}

genesis();