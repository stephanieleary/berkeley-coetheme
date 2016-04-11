<?php

// Sticky posts

function berkeley_sticky_post_loop() {
	
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	if ( 1 == $paged ) :
		global $query_args;
		$args = array(
			'posts_per_page'  => 1,
			'posts_per_archive_page' => 1,
			'post_type'  	  => get_query_var( 'post_type' ),
			'post__in'		  => get_option( 'sticky_posts' ),
		 );
		if ( is_tax() ) {
			$queried_object = get_queried_object();
			$args[$queried_object->taxonomy] = $queried_object->slug;
		}
			
		echo '<div class="stickies">';
		remove_action( 'genesis_loop_else', 'genesis_do_noposts' );
		remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
		genesis_custom_loop( wp_parse_args( $args, $query_args ) );
		echo '</div>';
	endif;
}

add_filter( 'post_class', 'berkeley_sticky_post_class' );

function berkeley_sticky_post_class( $classes ) {
	if ( is_sticky() || in_array( get_the_ID(), get_option( 'sticky_posts' ) ) )
		$classes[] = 'sticky';
	return $classes;
}

// Table loops

function berkeley_loop_table_headers( $headers ) {
	$headerrow = '';
	foreach ( $headers as $header ) {
		$headerrow .= sprintf( "<th>%s</th>\n", $header );
	}
	
	return sprintf( '<div class="loop"><table cellspacing="0" class="responsive">
		<thead>
			<tr>
		      %s
		    </tr>
		</thead>
		<tbody>'."\n", $headerrow );
}

function berkeley_loop_table_cells( $data ) {
	$datarow = '';
	foreach ( $data as $title => $field ) {
		$class = '';
		if ( empty( trim ( $field ) ) )
			$class = 'empty';
		$datarow .= sprintf( '<td title="%s" class="%s">%s</td>'."\n", $title, $class, $field );
	}
	
	return sprintf( "<tr id='post-%d' %s>\n %s \n </tr>\n", get_the_ID(), genesis_attr( 'entry' ), $datarow );
}

function berkeley_loop_table_footer() {
	return "</tbody>\n </table>\n</div><!-- .loop -->\n";
}


function berkeley_people_table_loop() {
	if ( have_posts() ) :

		do_action( 'genesis_before_while' );
		
		$headers = array( __('Name', 'beng'), __('Title', 'beng'), __('Office', 'beng'), __('Email', 'beng') );
		
		echo berkeley_loop_table_headers( $headers );
	
		while ( have_posts() ) : the_post();
		
			do_action( 'genesis_before_entry' );
			
			$data = array( 
				__('Name', 'beng')   => sprintf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), get_the_title() ),
				__('Title', 'beng')  => get_field( 'job_title' ),
				__('Office', 'beng') => get_field( 'address_line_1' ),
				__('Email', 'beng')  => sprintf( '<a href="mailto:%1$s">%1$s</a>', get_field( 'email' ) )
			);
			
			echo berkeley_loop_table_cells( $data );
			
			do_action( 'genesis_after_entry' );

		endwhile; //* end of one post
		
		echo berkeley_loop_table_footer();
		
		do_action( 'genesis_after_endwhile' );

	else : //* if no posts exist
		do_action( 'genesis_loop_else' );
	endif; //* end loop
}