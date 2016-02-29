<?php

add_filter( 'the_content', 'berkeley_people_content_filter' );

function berkeley_people_content_filter( $content ) {
	$before_content = $after_content = '';
	
	$hours = get_field( 'hours' );
	if ( !empty( $hours ) ) :
		$before_content .= sprintf( '<dl><dt>Hours</dt><dd>%s</dd></dl>', $hours );
	endif;
	
	$phone = get_field( 'phone_number' );
	if ( !empty( $phone ) ) :
		$punctuation = array( '(', ')', '-', ':', '.', ' ' );
		$number = str_replace( $punctuation, '', $phone );
		$phone = sprintf( '<a href="tel:%d">%s</address>', $number, $phone );
	endif;
	
	$email = get_field( 'email' );
	if ( !empty( $email ) ) :
		$email = sprintf( '<a href="mailto:%1$s">%1$s</a>', $email );
	endif;
	
	$address = array_filter( array( 
		'phone' => $phone,
		'email' => $email,
		'line1' => get_field( 'address_line_1' ), 
		'line2' => get_field( 'address_line_2' ),
		'city'  => get_field( 'city' ),
		'state' => get_field( 'state' ),
		'zip' 	=> get_field( 'zip' ),
		'country' => get_field( 'country' )
	) );
	
	// avoid line breaks and commas in between certain fields if they're empty
	if ( isset( $address['line2'] ) ) {
		$address['line1'] .= $address['line2'];
		unset($address['line2']);
	}
	
	if ( isset( $address['state'] ) ) {
		$address['city'] .= ', ' . $address['state'];
		unset($address['state']);
	}
	
	if ( isset( $address['zip'] ) ) {
		$address['city'] .= ' ' . $address['zip'];
		unset($address['zip']);
	}
	
	$before_content .= sprintf( '<p><address>%s</address></p>', implode( '<br>', $address ) );
	
	// links repeater
	// check if the repeater field has rows of data
	if ( have_rows( 'links' ) ):
		$after_content .= '<div id="bio-links">';
	 	// loop through the rows of data
	    while ( have_rows( 'links' ) ) : the_row();
			$after_content .= '<ul>';
			$url = get_sub_field( 'url' );
			$title = get_sub_field( 'site_title' );
			if ( !empty( $url ) && !empty( $title ) ) {
				$after_content .= sprintf( '<li><a href="%s">%s</a></li>', $url, $site_title );
			}
			$after_content .= '</ul>';
	    endwhile;
		$after_content .= '</div> <!-- #bio-links -->';
	endif;
	
	// WYSIWYG fields
	$sections = array(
		'education'					=> 'Education',
		'awards'					=> 'Awards',
		'experience'				=> 'Experience',
		'publications'				=> 'Publications',
		'additional_information'	=> 'Additional Information',
		'responsibilities'			=> 'Responsibilities'
	);
	
	foreach ( $sections as $section => $section_title ) {
		$section_content = get_field( $section );
		if ( !empty( $section_content ) ) {
			$after_content .= sprintf( '<h2 id="%s">%s</h2> %s', $section, $section_title, $section_content );
		}
		
	}
}

genesis();