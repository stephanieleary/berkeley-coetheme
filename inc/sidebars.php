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
		'name'			=> __( 'Emergency Announcements', 'beng' ),
		'description'	=> __( 'Area between the logo and the main navigation.' , 'beng'),
	) );
	// * Slideshow widget area
	genesis_register_sidebar( array(
		'id'			=> 'berkeley-featured',
		'name'			=> __( 'Featured Content', 'beng' ),
		'description'	=> __( 'Full-width area below the main navigation.', 'beng' ),
	) );
	
	
	// CPT-specific sidebar names match CPT names; see berkeley_do_sidebar() below
	$cpts = get_option( 'berkeley_cpts' );
	
	if ( 1 == $cpts[ 'people' ] )
		genesis_register_sidebar( array(
			'id'			=>	'people',
			'name'			=>	__( 'People', 'beng' ),
			'description'	=>	__( 'This is the primary sidebar on People pages.', 'beng' ),
		) );
	if ( 1 == $cpts[ 'facility' ] )
		genesis_register_sidebar( array(
			'id'			=>	'facility',
			'name'			=>	__( 'Facilities', 'beng' ),
			'description'	=>	__( 'This is the primary sidebar on Facility pages.', 'beng' ),
		) );
	if ( 1 == $cpts[ 'course' ] )
		genesis_register_sidebar( array(
			'id'			=>	'course',
			'name'			=>	__( 'Courses', 'beng' ),
			'description'	=>	__( 'This is the primary sidebar on Course pages.', 'beng' ),
		) );
	if ( 1 == $cpts[ 'publication' ] )
		genesis_register_sidebar( array(
			'id'			=>	'publication',
			'name'			=>	__( 'Publications', 'beng' ),
			'description'	=>	__( 'This is the primary sidebar on Publication pages.', 'beng' ),
		) );
	if ( 1 == $cpts[ 'research' ] )
		genesis_register_sidebar( array(
			'id'			=>	'research',
			'name'			=>	__( 'Research', 'beng' ),
			'description'	=>	__( 'This is the primary sidebar on Research pages.', 'beng' ),
		) );
}


// Do widget areas on corresponding post types

add_action( 'get_header', 'berkeley_cpt_switch_sidebar' );

function berkeley_cpt_switch_sidebar() {
	if ( is_admin() )
		return;
	
	remove_action( 'genesis_sidebar', 'genesis_do_sidebar' ); 
	add_action( 'genesis_sidebar', 'berkeley_do_sidebar' );
}

function berkeley_do_sidebar() {
	if ( function_exists( 'berkeley_find_post_type' ) )
		$type = berkeley_find_post_type();
	else
		$type = get_query_var( 'post_type' );
	
	if ( isset( $type ) && !empty( $type ) && !in_array( $type, array( 'any', 'post', 'page', 'attachment' ) ) )
		dynamic_sidebar( $type );
	else
		genesis_do_sidebar();
}