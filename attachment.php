<?php

add_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );

add_filter( 'genesis_post_info', 'berkeley_image_size_links' );

function berkeley_image_size_links( $info ) {
	$image_links = array();
	$imgmeta = wp_get_attachment_metadata( get_the_ID() );
	$sizes = $imgmeta['sizes'];
	foreach ( $sizes as $size => $file ) {		
	    $image_url = wp_get_attachment_image_src( get_the_ID(), $size );
	    if ( ! empty( $image_url[0] ) ) {
	        $image_links[] = sprintf( '<a href="%s" alt="%s">%s (%s&times;%s)</a>',
	            esc_url( $image_url[0] ),
	            esc_attr( the_title_attribute( 'echo=0' ) ),
	            esc_html( $size ),
				$file['width'],
				$file['height']
	        );
	    }
	}
	return sprintf( '<span class="image-sizes">%s</span>', implode( ' | ', $image_links ) );
}

genesis();