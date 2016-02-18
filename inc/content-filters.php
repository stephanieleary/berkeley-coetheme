<?php

/*	Content is filtered here instead of in single- and archive- templates
	so the filters will be applied throughout the site--e.g., search results.
/**/

// Main content filters
// Prepend / Append custom field output to post body ($content)

function berkeley_display_custom_field_content( $content ) {
	
	$before_content = $after_content = '';
	$post_type = get_post_type();
	
	// Location field (facilities)
	
	if ( 'facility' == $post_type ) : 
	
		$location = get_field( 'map' );
		if ( !empty( $location ) ):
			$before_content .= sprintf( '<div class="acf-map">
				<div class="marker" data-lat="%s" data-lng="%s"></div>
			</div>', $location['lat'], $location['lng'] );
		endif;
		
		$address = get_field( 'street_address' );
		if ( !empty( $address ) ) :
			$before_content .= sprintf( '<p><address>%s</address></p>', $address );
		endif;
		
		$phone = get_field( 'phone_number' );
		$email = get_field( 'email' );
		
		if ( $phone || $email )
			$after_content .= '<h2>Contact</h2>';
		
		if ( !empty( $phone ) ) :
			$punctuation = array( '(', ')', '-', ':', '.', ' ' );
			$number = str_replace( $punctuation, '', $phone );
			$after_content .= sprintf( '<a class="tel" href="tel:%d">%s</address>', $number, $phone );
		endif;
		
		if ( !empty( $email ) ) :
			$after_content .= sprintf( '<a href="mailto:%1$s">%1$s</a>', $email );
		endif;
		
		$link = get_field( 'link' );
		if ( !empty( $link ) ) :
			$after_content .= sprintf( '<a href="%s" class="button">%s</a>', $link, 'Go to website &rarr;' );
		endif;
		
		$reservations = get_field( 'reservations' );
		if ( !empty( $reservations ) ) :
			$after_content .= sprintf( '<h2 class="reservations">Reservations</h2> %s', $reservations );
		endif;
		
	
	endif; // facility
	
	
	if ( 'publication' == $post_type ) :
		
		$link = get_field( 'link' );
		if ( !empty( $link ) ) :
			$after_content .= sprintf( '<a href="%s" class="button">%s</a>', $link, 'Go to website &rarr;' );
		endif;
		
		$source = get_field( 'source' );
		if ( !empty( $source ) ) :
			$after_content .= sprintf( '<a href="%s" class="button">%s</a>', $source, 'View source &rarr;' );
		endif;
		
		$cite = get_field( 'citation' );
		if ( !empty( $cite ) ) :
			$after_content .= sprintf( '<h2 class="citation">Citation</h2> %s', $cite );
		endif;
		
	endif; // publication
	
	
	if ( 'course' == $post_type ) :
		
		$num = get_field( 'course_number' );
		$credits = get_field( 'credits' );
		$pre = get_field( 'prerequisites' );
		
		$before_content .= sprintf( '<div class="course-info">
			<dl>
				<dt>Course: </dt> <dd> %s </dd>
				<dt>Credits: </dt> <dd> %s </dd>
				<dt>Prerequisites: </dt> <dd> %s </dd>
			</dl></div>', $num, $credits, $pre );
		
		$before_content .= sprintf( '<h3>Instructor(s)</h3><p>%s</p>', get_field( 'instructors' ) );
		$before_content .= sprintf( '<h3>Department</h3><p>%s</p>', get_field( 'department' ) );
		
		$after_content .= sprintf( '<h3>Times </h3><p>%s</p>', get_field( 'times' ) );
		$after_content .= sprintf( '<h3>Location </h3><p>%s</p>', get_field( 'location' ) );
		
		$addtl = get_field( 'additional_information' );
		if ( !empty( $addtl ) ) :
			$after_content .= sprintf( '<h2>Additional Information</h2> %s', $addtl );
		endif;
		
	endif; // course
	
	
	if ( 'people' == $post_type ) :
		
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
		
	endif; // people
	
	
	// Additional Content field (all post types)
	
	// check if the repeater field has rows of data
	if ( have_rows( 'collapsing_sections' ) ):
		$after_content .= '<div id="accordion">';
	 	// loop through the rows of data
	    while ( have_rows( 'collapsing_sections' ) ) : the_row();
	        // display a sub field value
			$heading = get_sub_field( 'section_heading' );
			$section_content = get_sub_field( 'collapsible_section' );
			if ( !empty( $heading ) && !empty( $section_content ) ) {
				$after_content .= sprintf( '<h2 class="accordion-toggle">%s</h2>', $heading );
				$after_content .= sprintf( '<div class="accordion-content">%s</div>', $section_content );
			}
			
	    endwhile;
		$after_content .= '</div> <!-- #accordion -->';
	endif;
	
	return $before_content . $content . $after_content;
}

add_filter( 'the_content', 'berkeley_display_custom_field_content' );


// Post meta filters
// entry header: post info
// entry footer: post meta

function berkeley_post_info_filter( $post_meta ) {
	$post_type = get_post_type();
	$post_id = get_the_ID();
	switch ( $post_type ) {
		
		case 'facility':
			$post_meta = get_the_term_list( $post_id, 'facility_type', '', ', ', '' );
			break;
			
		case 'people':
			$post_meta = sprintf( '<span class="job_title">%s</span> ', get_field( 'job_title' ) );
			$major = get_field( 'major' );
			$year = get_field( 'class_year' );
			if ( $major && $year )
				$major .= ', ' . $year;
			else
				$major = $major . $year;
			$post_meta .= sprintf( '<span class="class_year">%s</span>', $major );
			break;
			
		case 'publication':
			$post_meta = sprintf( '<span class="author">%s</span> ', get_field( 'author' ) );
			$date = get_field( 'publication_date');
			if ( isset( $date ) && !empty( $date ) ) {
				$date = DateTime::createFromFormat( 'Ymd', $date );
				$post_meta .= sprintf( '<span class="date">%s</span>', $date->format('F j, Y') );
			}
			break;
			
		default: 
			$post_meta = 'Posted on [post_date] by [post_author]';
			break;
	}
	return $post_meta;
}
add_filter( 'genesis_post_info', 'berkeley_post_info_filter' );

function berkeley_post_meta_filter( $post_meta ) {
	$post_type = get_post_type();
	$post_id = get_the_ID();
	switch ( $post_type ) {
		
		case 'facility':
			$post_meta = '';
			break;
		
		case 'publication':
			$post_meta = get_field( 'author' );
			if ( get_field( 'citation' ) ) 
				$post_meta .= '<br/>' . get_field( 'citation' );
			break;
			
		case 'people':
			if ( has_term( '', 'people_type' ) )
				$post_meta = get_the_term_list( $post_id, 'people_type', '<span class="people_type">', ', ', '</span>' );
			break;
			
			
		default: 
			$post_meta = '[post_categories] [post_tags]';
			break;
	}
	return $post_meta;
}
add_filter( 'genesis_post_meta', 'berkeley_post_meta_filter' );

