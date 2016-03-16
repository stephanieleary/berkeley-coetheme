<?php

add_filter( 'pre_get_posts', 'berkeley_cpt_archive_sort' );

function berkeley_cpt_archive_sort( $query ) {
	if ( !is_archive() || !isset( $query->query['post_type'] ) )
		return $query;
		
	switch ( $query->query['post_type'] ) {
		
		case 'course':
			$query->set( 'posts_per_page', -1 );
			$query->set( 'order', 'ASC' );
			$query->set( 'orderby', 'meta_value meta_value_num' );
			$query->set( 'meta_key', 'course_number' );
			$query->set( 'meta_query', array(
				array(
					'key' 	  => 'course_number',
					'compare' => 'EXISTS',
				)
			) );
			break;
			
		case 'facility':
			$query->set( 'order', 'ASC' );
			$query->set( 'orderby', 'title' );
			break;
		
		case 'people':
			$query->set( 'order', 'ASC' );
			$query->set( 'orderby', 'meta_value title' );
			$query->set( 'meta_key', 'last_name' );
			break;
			
		default:
			break;
	}
	
	return $query;
}

// Featured Image support

add_action( 'genesis_before_entry', 'berkeley_featured_image_singular', 8 );
function berkeley_featured_image_singular() {
	if ( ! is_singular() || ! has_post_thumbnail() || !get_field( 'display_featured_image' ) )
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
	printf( '<div class="featured-image">%s<div class="wp-caption-text">%s</div></div>', $img, $caption );
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
		
		$contact = '';
		
		$link = get_field( 'link' );
		if ( !empty( $link ) ) :
			$contact .= sprintf( '<a href="%s">%s</a>', $link, 'Website' );
		endif;
		
		$phone = get_field( 'phone_number' );
		$email = get_field( 'email' );
		
		if ( !empty( $email ) ) :
			$contact .= sprintf( '<a href="mailto:%1$s">%1$s</a>', $email );
		endif;
		
		if ( !empty( $phone ) ) :
			$punctuation = array( '(', ')', '-', ':', '.', ' ' );
			$number = str_replace( $punctuation, '', $phone );
			$contact .= sprintf( 'Phone: <a class="tel" href="tel:%d">%s</address>', $number, $phone );
		endif;
		
		$content = sprintf( '<div class="one-half alignleft">%s %s</div>', $contact, $content );
		
		$address = get_field( 'street_address' );
		if ( !empty( $address ) ) :
			$address = sprintf( '<p><address>%s</address></p>', $address );
		endif;
		
		$location = get_field( 'map' );
		if ( !empty( $location ) ):
			$map = sprintf( '<div class="acf-map">
				<div class="marker" data-lat="%s" data-lng="%s"></div>
			</div>', $location['lat'], $location['lng'] );
		endif;
		
		$after_content .= sprintf( '<div class="one-half alignright">%s %s</div>', $address, $map );
		
	
	endif; // facility
	
	
	if ( 'publication' == $post_type ) :
		
		$before_content .= sprintf( '<p class="pub-author">%s</p>', get_field( 'author' ) );
		
		$link = get_field( 'link' );
		if ( !empty( $link ) ) :
			$before_content .= sprintf( '<p class="pub-link"><a href="%s">%s</a></p>', $link, get_field( 'publication_name' ) );
		endif;
		
		$date = get_field( 'publication_date');
		if ( isset( $date ) && !empty( $date ) ) {
			$date = DateTime::createFromFormat( 'Ymd', $date );
			$pub_date = sprintf( '<span class="date">%s</span>', $date->format('F j, Y') );
		}
		$before_content .= sprintf( '<p class="pub-date">%s</p>', $pub_date );
		
		$cite = get_field( 'citation' );
		if ( !empty( $cite ) ) :
			$before_content .= sprintf( '<h2 class="citation">Citation</h2> %s', $cite );
		endif;
		
	endif; // publication
	
	
	if ( 'course' == $post_type ) :
		
		$before_content .= sprintf( '<strong>Course: </strong> %s', 			get_field( 'course_number' ) );
		
		// description is the main content field
		
		$after_content .= '<div class="course-info">';
		$after_content .= sprintf( '<p><strong>Instructor(s):</strong> %s</p>', get_field( 'instructors' ) );
		$after_content .= sprintf( '<p><strong>Credits:</strong> %s</p>', 		get_field( 'credits' ) );
		$after_content .= sprintf( '<p><strong>Prerequisites:</strong> %s</p>', get_field( 'prerequisites' ) );
		$after_content .= sprintf( '<p><strong>Times:</strong> %s</p>', 		get_field( 'times' ) );
		$after_content .= sprintf( '<p><strong>Location:</strong> %s</p>', 		get_field( 'location' ) );
		$after_content .= '</div>';
		
	endif; // course
	
	
	if ( 'people' == $post_type ) :
		
		$before_content = $after_content = '';
		
		$before_content = sprintf( '<div class="job_title">%s</div> ', get_field( 'job_title' ) );

		$phone = get_field( 'phone_number' );
		if ( !empty( $phone ) ) :
			$punctuation = array( '(', ')', '-', ':', '.', ' ' );
			$number = str_replace( $punctuation, '', $phone );
			$before_content .= sprintf( '<p class="bio-phone"><strong>Phone: </strong><a href="tel:%d">%s</a></p>', $number, $phone );
		endif;

		$email = get_field( 'email' );
		if ( !empty( $email ) ) :
			$before_content .= sprintf( '<p class="bio-email"><a href="mailto:%1$s">%1$s</a></p>', $email );
		endif;
		
		// links repeater
		// check if the repeater field has rows of data
		if ( have_rows( 'links' ) ):
			$links = array();
			$before_content .= '<p id="bio-links">';
		 	// loop through the rows of data
		    while ( have_rows( 'links' ) ) : the_row();
				$url = get_sub_field( 'url' );
				$site_title = get_sub_field( 'link_text' );
				if ( !empty( $url ) && !empty( $site_title ) ) {
					$links[] = sprintf( '<a href="%s">%s</a>', $url, $site_title );
				}
		    endwhile;
			$before_content .= sprintf( '%s </p> <!-- #bio-links -->', implode( '<br>', array_filter( $links ) ) );
		endif;
		
		
		if ( is_singular() ) {

			$address = array_filter( array( 
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

			$before_content .= sprintf( '<address>%s</address>', implode( '<br>', array_filter( $address ) ) );


			$hours = get_field( 'hours' );
			if ( !empty( $hours ) ) :
				$before_content .= sprintf( '<p class="bio-hours"><strong>Hours:</strong> %s</p>', $hours );
			endif;

		
			$before_content = '<div class="one-half first alignleft">' . $before_content;

			$content .= '</div>';

			$after_content .= '<div class="one-half alignright">';
			
			// featured image
			$after_content .= get_the_post_thumbnail( get_the_ID(), 'medium' );

			if ( has_term( 'student', 'people_type' ) ) {
				$after_content .= sprintf( '<p class="class-major">%</p>', get_field( 'major' ) );
				$after_content .= sprintf( '<p class="class-year">%</p>', get_field( 'class_year' ) );
				$after_content .= get_the_term_list( get_the_ID(), 'student_type', '<p class="student_type">', ', ', '</p>' );
			}

			if ( has_term( '', 'subject_area' ) )
				$after_content .= get_the_term_list( get_the_ID(), 'subject_area', '<h3>Research Interests:</h3><span class="subject_area">', ', ', '</span>' );

			if ( has_term( 'faculty', 'people_type' ) )	
				$after_content .= get_field( 'research_description' );

			$after_content .= '</div>';

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
					$default = 'default activated';
				$after_content .= sprintf( '<h3 class="accordion-toggle">%s</h3>', $heading );
				$after_content .= sprintf( '<div class="accordion-content %s">%s</div>', $default, $section_content );
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
	$post_meta = '';
	
	switch ( $post_type ) {
		
		case 'post':
			$post_meta = 'Posted on [post_date] by [post_author]';
			break;
		
		case 'facility':
			if ( is_archive() ) :
				$post_meta = sprintf( '<p class="location">%s</p>', get_field( 'location' ) );
				$url = get_field( 'link' );
				if ( !empty( $url ) )
					$post_meta .= sprintf( '<a href="%s" title="URL for %s">Website</p>', esc_url( $url ), the_title_attribute( 'echo=0' ) );
			endif;
			break;
			
		case 'people':
			if ( is_search() ) :
				
				$post_meta = sprintf( '<span class="job_title">%s</span> ', get_field( 'job_title' ) );

				if ( has_term( 'student', 'people_type' ) )
					$post_meta .= get_the_term_list( $post_id, 'student_type', '<span class="student_type">', ', ', '</span>' );
				if ( has_term( 'faculty', 'people_type' ) ) {
					// links repeater
					// check if the repeater field has rows of data
					if ( have_rows( 'links' ) ):
						$post_meta .= '<div id="bio-links">';
					 	// loop through the rows of data
					    while ( have_rows( 'links' ) ) : the_row();
							$post_meta .= '<ul>';
							$url = get_sub_field( 'url' );
							$site_title = get_sub_field( 'link_text' );
							if ( !empty( $url ) && !empty( $site_title ) ) {
								$post_meta .= sprintf( '<li><a href="%s">%s</a></li>', $url, $site_title );
							}
							$post_meta .= '</ul>';
					    endwhile;
						$post_meta .= '</div> <!-- #bio-links -->';
					endif;
				}

				if ( has_term( 'staff', 'people_type' ) )
					$post_meta .= get_field( 'responsibilities' );

				if ( has_term( '', 'subject_area' ) )
					$post_meta .= get_the_term_list( $post_id, 'subject_area', '<span class="subject_area">', ', ', '</span>' );
			endif;
			break;
			
		case 'publication':
			if ( is_archive() ) :
				$post_meta = sprintf( '<span class="author">%s</span> ', get_field( 'author' ) );
				$link = get_field( 'link' );
				if ( !empty( $link ) )
					$post_meta .= sprintf( '<span class="pub-link"><a href="%s">%s</a></span>', $link, get_field( 'publication_name' ) );
				$date = get_field( 'publication_date');
				if ( isset( $date ) && !empty( $date ) ) {
					$date = DateTime::createFromFormat( 'Ymd', $date );
					$post_meta .= sprintf( '<span class="date">%s</span>', $date->format('F j, Y') );
				}
			endif;
			break;
			
		default: 
			break;
	}
	return $post_meta;
}
add_filter( 'genesis_post_info', 'berkeley_post_info_filter' );

function berkeley_post_meta_filter( $post_meta ) {
	$post_type = get_post_type();
	$post_id = get_the_ID();
	$post_meta = '';
	
	switch ( $post_type ) {
		
		case 'post':
			$post_meta = '[post_categories] [post_tags]';
			break;
		
		case 'publication':
			if ( has_term( '', 'subject_area' ) )
				$post_meta = get_the_term_list( $post_id, 'subject_area', '<span class="subject_area">', ', ', '</span>' );
			break;
			
		default: 
			break;
	}
	return $post_meta;
}
add_filter( 'genesis_post_meta', 'berkeley_post_meta_filter' );

