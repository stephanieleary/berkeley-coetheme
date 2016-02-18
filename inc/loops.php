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