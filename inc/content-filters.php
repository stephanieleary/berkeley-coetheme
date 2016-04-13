<?php

add_filter( 'pre_get_posts', 'berkeley_cpt_archive_sort' );

function berkeley_cpt_archive_sort( $query ) {
	if ( is_admin() )
		return $query;
	if ( !is_archive() )
		return $query;
		
	if ( isset ($query->query['post_type'] ) ) {
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
				$query->set( 'ignore_sticky_posts', true );
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
	}
	
	
	if ( isset( $query->query['people_type'] ) ) {
		$query->set( 'order', 'ASC' );
		$query->set( 'orderby', 'meta_value title' );
		$query->set( 'meta_key', 'last_name' );
	}
	
	if ( isset( $query->query['facility_type'] ) ) {
		$query->set( 'order', 'ASC' );
		$query->set( 'orderby', 'title' );
	}
	
	return $query;
}

// Featured Image support

//add_action( 'genesis_before_entry', 'berkeley_featured_image_singular', 8 );
add_action( 'genesis_entry_header', 'berkeley_featured_image_singular', 1 );
function berkeley_featured_image_singular() {
	if ( ! is_singular() || ! has_post_thumbnail() )
		return;
	
	$showimg = get_post_meta( get_the_ID(), 'display_featured_image', true );
	if( !$showimg )
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
	$caption = get_post_field( 'post_excerpt', get_post_thumbnail_id() );
	printf( '<div class="featured-image">%s<div class="wp-caption-text">%s</div></div>', $img, $caption );
}

// Send taxonomy archive links to the same post type we're currently viewing
add_action( 'genesis_before', 'berkeley_filter_term_links' );

function berkeley_filter_term_links() {
	if ( !is_admin() && function_exists( 'taxonomy_link_for_post_type' ) )
		add_filter( 'term_link', 'taxonomy_link_for_post_type', 10, 3 );
}

// Filter the "no content matched your criteria" error
add_filter( 'genesis_noposts_text', 'berkeley_noposts_text', 10, 2 );
function berkeley_noposts_text( $text ) {
	if ( is_search() ) {
		$text = __( "I'm sorry. I couldn't find any pages with that phrase. Try again?", 'beng' );
	} elseif ( is_archive() ) {
		$text = __( "There are no entries in this section.", 'beng' );
	}
	$text .= get_search_form( false );
	return $text;
}

/*	Content is filtered here instead of in single- and archive- templates
	so the filters will be applied throughout the site--e.g., search results.
/**/

// Main content filters
// Prepend / Append custom field output to post body ($content)

function berkeley_display_custom_field_content( $content ) {
	
	$before_content = $after_content = '';
	$post_type = get_post_type();

	
	if ( 'facility' == $post_type && is_singular() ) :
			
		$contact = '';
		
		
		$link = get_field( 'link' );
		if ( !empty( $link ) ) :
			$contact .= sprintf( '<p class="facility-link"><a href="%s">%s</a></p>', $link, 'Website' );
		endif;
		
		$phone = get_field( 'phone_number' );
		$email = get_field( 'email' );
		
		if ( !empty( $email ) ) :
			$contact .= sprintf( '<p class="facility-email"><a href="mailto:%1$s">%1$s</a></p>', $email );
		endif;
		
		if ( !empty( $phone ) ) :
			$punctuation = array( '(', ')', '-', ':', '.', ' ' );
			$number = str_replace( $punctuation, '', $phone );
			$contact .= sprintf( '<p class="facility-phone">Phone: <a class="tel" href="tel:%d">%s</a></p>', $number, $phone );
		endif;
		
		$content = sprintf( '<div class="one-half first"><div class="facility-details">%s</div> %s</div>', $contact, $content );
		
		$address = get_field( 'street_address' );
		if ( !empty( $address ) ) :
			$address = sprintf( '<address>%s</address>', $address );
		endif;
		
		$location = get_field( 'map' );
		if ( !empty( $location ) ):
			$map = sprintf( '<div class="acf-map">
				<div class="marker" data-lat="%s" data-lng="%s"></div>
			</div>', $location['lat'], $location['lng'] );
		endif;
		
		$after_content .= sprintf( '<div class="one-half">%s %s</div>', $address, $map );
		
	
	endif; // facility
	
	
	if ( 'publication' == $post_type ) :
		
		$before_content .= '<div class="pub-details">';
		$before_content .= sprintf( '<p class="pub-author">%s</p>', get_field( 'author' ) );
		
		if ( get_field( 'link' ) )
			$before_content .= sprintf( '<p class="pub-link"><a href="%s">%s</a></p>', get_field( 'link' ), get_field( 'publication_name' ) );
		
		if ( get_field( 'publication_date') )
			$before_content .= sprintf( '<p class="pub-date">%s</p>', get_field( 'publication_date') );
		
		if ( get_field( 'citation' ) )
			$before_content .= sprintf( '<div class="pub-citation">%s</div>', get_field( 'citation' ) );
		
		$before_content .= '</div>';
	endif; // publication
	
	
	if ( 'course' == $post_type ) :
		
		if ( is_singular() ) {
		
			$before_content .= get_field( 'course_number' );
		
			// description is the main content field
		
			$after_content .= '<div class="course-info">';
			if ( !empty( get_field( 'instructors' ) ) )
				$after_content .= sprintf( '<p><strong>Instructor(s):</strong> %s</p>', get_field( 'instructors' ) );
			if ( !empty( get_field( 'credits' ) ) )
				$after_content .= sprintf( '<p><strong>Credits:</strong> %s</p>', 		get_field( 'credits' ) );
			if ( !empty( get_field( 'prerequisites' ) ) )
				$after_content .= sprintf( '<p><strong>Prerequisites:</strong> %s</p>', get_field( 'prerequisites' ) );
			if ( !empty( get_field( 'times' ) ) )
				$after_content .= sprintf( '<p><strong>Time:</strong> %s</p>', 			get_field( 'times' ) );
			if ( !empty( get_field( 'location' ) ) )
				$after_content .= sprintf( '<p><strong>Location:</strong> %s</p>', 		get_field( 'location' ) );
			$after_content .= '</div>';
		
		}
	endif; // course
	
	
	if ( 'people' == $post_type ) :
		
		$before_content = $after_content = '';
		
		$before_content = '<div class="bio-details">';
		
		// featured image
		if ( is_singular() )
			$before_content .= get_the_post_thumbnail( get_the_ID(), 'medium' );
		
		$before_content .= sprintf( '<div class="job_title">%s</div> ', get_field( 'job_title' ) );
		
		if ( has_term( 'staff', 'people_type' ) )
			$before_content .= get_field( 'responsibilities' );

		$email = get_field( 'email' );
		if ( !empty( $email ) ) :
			$before_content .= sprintf( '<p class="bio-email"><a href="mailto:%1$s">%1$s</a></p>', $email );
		endif;
		
		$phone = get_field( 'phone' );
		if ( !empty( $phone ) ) :
			$punctuation = array( '(', ')', '-', ':', '.', ' ' );
			$number = str_replace( $punctuation, '', $phone );
			$before_content .= sprintf( '<p class="bio-phone"><strong>Phone: </strong><a href="tel:%d">%s</a></p>', $number, $phone );
		endif;

		
		
		
		$before_content .= berkeley_links_repeater();
		
		
		if ( is_singular() ) {

			$address = array_filter( array( 
				'line1' => get_field( 'address_line_1' ), 
				'line2' => get_field( 'address_line_2' ),
				'city'  => get_field( 'city' ),
				'state' => get_field( 'state' ),
				'zip' 	=> get_field( 'zip' ),
				'country' => get_field( 'country' )
			) );

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
				if ( has_term( 'staff', 'people_type' ) )
					$label = 'Hours:';
				else
					$label = 'Office Hours:';
				$before_content .= sprintf( '<p class="bio-hours"><strong>%s</strong> %s</p>', $label, $hours );
			endif;
			
			$before_content .= '</div>';

			// $content

			if ( has_term( 'student', 'people_type' ) ) {
				$before_content .= get_the_term_list( get_the_ID(), 'student_type', '<p class="student_type">', ', ', '</p>' );
				if ( get_field( 'major' ) )
					$before_content .= sprintf( '<p class="class-major"><strong>Major:</strong> %s</p>', get_field( 'major' ) );
				if ( get_field( 'class_year' ) )
					$before_content .= sprintf( '<p class="class-year"><strong>Class:</strong> %s</p>', get_field( 'class_year' ) );
					
				$before_content .= '<p></p>';
			}

			if ( has_term( '', 'subject_area' ) )
				$after_content .= get_the_term_list( get_the_ID(), 'subject_area', '<h3>Research Interests</h3><div class="subject_area">', ', ', '</div>' );

			if ( has_term( 'faculty', 'people_type' ) )	
				$after_content .= get_field( 'research_description' );

			// WYSIWYG fields
			$sections = array(
				'education'					=> 'Education',
				'awards'					=> 'Awards',
				'experience'				=> 'Experience',
				'publications'				=> 'Publications',
				'additional_information'	=> 'Additional Information'
			);

			foreach ( $sections as $section => $section_title ) {
				$section_content = get_field( $section );
				if ( !empty( $section_content ) ) {
					$after_content .= sprintf( '<h3 id="%s">%s</h3> %s', $section, $section_title, $section_content );
				}

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
			$class = '';
			if ( !empty( $heading ) && !empty( $section_content ) ) {
				$open = get_sub_field( 'open' );
				if ( $open )
					$class = 'activated';
				$after_content .= sprintf( '<h3 class="accordion-toggle %s">%s</h3>', $class, $heading );
				$after_content .= sprintf( '<div class="accordion-content %s">%s</div>', $class, $section_content );
			}
	    endwhile;
		$after_content .= '</div> <!-- #accordion -->';
	endif;
	
	return $before_content . $content . $after_content;
}

add_filter( 'the_content', 'berkeley_display_custom_field_content' );


function berkeley_links_repeater() {
	$content = '';
	// links repeater
	// check if the repeater field has rows of data
	if ( have_rows( 'links' ) ):
		$links = array();
		$content = '<p class="bio-links">';
	 	// loop through the rows of data
	    while ( have_rows( 'links' ) ) : the_row();
			$url = get_sub_field( 'url' );
			$site_title = get_sub_field( 'link_text' );
			if ( !empty( $url ) && !empty( $site_title ) ) {
				$links[] = sprintf( '<a href="%s">%s</a>', $url, $site_title );
			}
	    endwhile;
		$content .= sprintf( '%s </p> <!-- #bio-links -->', implode( '<br>', array_filter( $links ) ) );
	endif;
	return $content;
}

function berkeley_display_custom_excerpts( $excerpt ) {
	$post_type = get_post_type();
	$post_id = get_the_ID();
	$pre = '';
	
	switch ( $post_type ) {
		case 'people':
			if ( has_term( 'student', 'people_type' ) ) {
				$excerpt = get_the_term_list( get_the_ID(), 'student_type', '<p class="student_type">', ', ', '</p>' );
				if ( get_field( 'major' ) )
					$excerpt .= sprintf( '<p class="class-major"><strong>Major:</strong> %s</p> ', get_field( 'major' ) );
				if ( get_field( 'class_year' ) )
					$excerpt .= sprintf( '<p class="class-year"><strong>Class:</strong> %s</p>', get_field( 'class_year' ) );
			}
			
			if ( has_term( 'faculty', 'people_type' ) ) {
				$excerpt = sprintf( '<span class="job_title">%s </span> ', get_field( 'job_title' ) );
				$excerpt .= berkeley_links_repeater();
			}
			
			if ( has_term( 'staff', 'people_type' ) ) {
				$excerpt = sprintf( '<span class="job_title">%s </span> ', get_field( 'job_title' ) );
				$excerpt .= get_field( 'responsibilities' );
			}

			if ( has_term( '', 'subject_area' ) )
				$excerpt .= get_the_term_list( $post_id, 'subject_area', '<span class="subject_area">', ', ', '</span>' );
			break;
		case 'publication':
			$pre = sprintf( '<p class="pub-author">%s</p>', get_field( 'author' ) );

			if ( get_field( 'link' ) )
				$pre .= sprintf( '<p class="pub-link"><a href="%s">%s</a></p>', get_field( 'link' ), get_field( 'publication_name' ) );

			if ( get_field( 'publication_date' ) )
				$pre .= sprintf( '<p class="pub-date">%s</p>', get_field( 'publication_date') );

			break;
		
		case 'facility':
			
			if ( get_field( 'street_address' ) )
				$pre .= sprintf( '<address>%s</address>', get_field( 'street_address' ) );
			
			if ( get_field( 'link' ) )
				$pre .= sprintf( '<p class="facility-link"><a href="%s">Website</a></p>', $link );
				
			break;
		
		case 'course':
			if ( get_field( 'course_number' ) )
				$pre = sprintf( '<p class="course-number">%s</p>', get_field( 'course_number' ) );
			break;
			
		default: break;
	}
	
	return $pre . $excerpt;
}

add_filter( 'the_excerpt', 'berkeley_display_custom_excerpts' );

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
			break;
			
		case 'people':
			break;
			
		case 'publication':
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
			break;
			
		default: 
			break;
	}
	return $post_meta;
}
add_filter( 'genesis_post_meta', 'berkeley_post_meta_filter' );

