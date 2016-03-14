<?php

// Featured Image support

add_action( 'genesis_before_entry', 'berkeley_featured_image_singular', 8 );
function berkeley_featured_image_singular() {
	if ( ! is_singular() || ! has_post_thumbnail() )
		return;
	
	if ( !get_field( 'display_featured_image' ) )
		return;
		
	/*
    $imgdata = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
    $imgwidth = $imgdata[1]; // thumbnail's width                   
    $wanted_width = 900;
    if ( ( $imgwidth >= $wanted_width ) ) {
		// print here
    }
	/**/
	
	// caption is stored as thumbnail's post excerpt
	// use post content to use description field instead
	$img = get_the_post_thumbnail( get_the_ID(), 'large' );
	$caption = apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', get_post_thumbnail_id() ) );
	printf( '<div class="featured-image">%s<p class="wp-caption-text">%s</p></div>', $img, $caption );
}


/*	Content is filtered here instead of in single- and archive- templates
	so the filters will be applied throughout the site--e.g., search results.
/**/

// Main content filters
// Prepend / Append custom field output to post body ($content)

function berkeley_display_custom_field_content( $content ) {
	
	$before_content = $after_content = '';
	$post_type = get_post_type();

	
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
		
		
		
	endif; // people
	
	
	// Additional Content field (all post types)
	
	// check if the repeater field has rows of data
	if ( have_rows( 'collapsing_sections' ) ):
		$after_content .= '<div id="accordion">';
		$i = 1;
	 	// loop through the rows of data
	    while ( have_rows( 'collapsing_sections' ) ) : the_row();
	        // display a sub field value
			$heading = get_sub_field( 'section_heading' );
			$section_content = get_sub_field( 'collapsible_section' );
			$default = '';
			if ( !empty( $heading ) && !empty( $section_content ) ) {
				if ( 1 == $i )
					$default = 'default';
				$after_content .= sprintf( '<h3 class="accordion-toggle %s">%s</h3>', $default, $heading );
				$after_content .= sprintf( '<div class="accordion-content">%s</div>', $section_content );
			}
			$i++;
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

