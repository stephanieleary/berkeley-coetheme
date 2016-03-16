<?php

function berkeley_loop_table_headers( $headers ) {
	$headerrow = '';
	foreach ( $headers as $header ) {
		$headerrow .= sprintf( "<th>%s</th>\n", $header );
	}
	
	return sprintf( '<div class="entry">
	<table cellspacing="0" class="responsive">
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
		$datarow .= sprintf( '<td title="%s">%s</td>'."\n", $title, $field );
	}
	
	return sprintf( "<tr id='post-%d' %s>\n %s \n </tr>\n", get_the_ID(), genesis_attr( 'entry' ), $datarow );
}

function berkeley_loop_table_footer() {
	return "</tbody>\n </table>\n </div> <!-- .entry -->";
}


function berkeley_people_table_loop() {
	if ( have_posts() ) :

		do_action( 'genesis_before_while' );
		
		$headers = array( __('Name'), __('Title'), __('Office'), __('Email') );
		
		echo berkeley_loop_table_headers( $headers );
	
		while ( have_posts() ) : the_post();
		
			do_action( 'genesis_before_entry' );
			
			$data = array( 
				__('Name')   => sprintf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute( 'echo=0' ), get_the_title() ),
				__('Title')  => get_field( 'job_title' ),
				__('Office') => get_field( 'address_line_1' ),
				__('Email')  => sprintf( '<a href="mailto:%1$s">%1$s</a>', get_field( 'email' ) )
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