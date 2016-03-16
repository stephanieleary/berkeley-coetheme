<?php

//* Add support for after-entry widget area (blog posts only)
add_theme_support( 'genesis-after-entry-widget-area' );

//* Add support for after-entry widget area to pages as well
add_action( 'genesis_entry_footer', 'berkeley_after_entry_widget'  );
 
function berkeley_after_entry_widget() {
	if ( ! is_page() )
		return;

	genesis_widget_area( 'after-entry', array(
		'before' => '<div class="after-entry widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );
}



add_action( 'after_setup_theme', 'berkeley_register_sidebars' );

function berkeley_register_sidebars() {
	// Register widget areas 
	// * Announcement feature
	genesis_register_sidebar( array(
		'id'			=> 'berkeley-announcements',
		'name'			=> __( 'Emergency Announcements' ),
		'description'	=> __( 'Area between the logo and the main navigation.' ),
	) );
	// * Slideshow widget area
	genesis_register_sidebar( array(
		'id'			=> 'berkeley-featured',
		'name'			=> __( 'Featured Content' ),
		'description'	=> __( 'Full-width area below the main navigation.' ),
	) );
	
	
	// CPT-specific sidebar names match CPT names; see berkeley_do_sidebar() below
	genesis_register_sidebar( array(
		'id'			=>	'people',
		'name'			=>	__( 'People' ),
		'description'	=>	__( 'This is the primary sidebar on People pages.' ),
	) );
	genesis_register_sidebar( array(
		'id'			=>	'facility',
		'name'			=>	__( 'Facilities' ),
		'description'	=>	__( 'This is the primary sidebar on Facility pages.' ),
	) );
	genesis_register_sidebar( array(
		'id'			=>	'course',
		'name'			=>	__( 'Courses' ),
		'description'	=>	__( 'This is the primary sidebar on Course pages.' ),
	) );
	genesis_register_sidebar( array(
		'id'			=>	'publication',
		'name'			=>	__( 'Publications' ),
		'description'	=>	__( 'This is the primary sidebar on Publication pages.' ),
	) );
	genesis_register_sidebar( array(
		'id'			=>	'research',
		'name'			=>	__( 'Research' ),
		'description'	=>	__( 'This is the primary sidebar on Research pages.' ),
	) );
}


// Do widget areas on corresponding post types
add_action( 'get_header', 'berkeley_cpt_switch_sidebar' );
function berkeley_cpt_switch_sidebar() {
	if ( is_admin() )
		return;
	
	$type = get_post_type();
	
	if ( isset( $type ) && in_array( $type, array( 'post', 'page', 'attachment' ) ) )
		return;
	
	remove_action( 'genesis_sidebar', 'genesis_do_sidebar' ); 
	add_action( 'genesis_sidebar', 'berkeley_do_sidebar' ); 
}

function berkeley_do_sidebar() {
	$type = get_post_type();
	dynamic_sidebar( $type );
}