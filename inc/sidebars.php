<?php

//* Add support for after-entry widget area (blog posts only)
add_theme_support( 'genesis-after-entry-widget-area' );

//* Add support for after-entry widget area to CPTs as well
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
		'name'			=> esc_html__( 'Emergency Announcements', 'berkeley-coe-theme' ),
		'description'	=> esc_html__( 'Area between the logo and the main navigation.' , 'berkeley-coe-theme'),
	) );
	// * Slideshow widget area
	genesis_register_sidebar( array(
		'id'			=> 'berkeley-featured',
		'name'			=> esc_html__( 'Featured Content', 'berkeley-coe-theme' ),
		'description'	=> esc_html__( 'Full-width area below the main navigation.', 'berkeley-coe-theme' ),
	) );
	//* News widget area
	genesis_register_sidebar( array(
		'id'			=> 'post',
		'name'			=> esc_html__( 'News', 'berkeley-coe-theme' ),
		'description'	=> esc_html__( 'This is the primary sidebar on news/blog archives.', 'berkeley-coe-theme' ),
	) );
	//* Whitepaper template widget area
	genesis_register_sidebar( array(
		'id'			=> 'whitepaper',
		'name'			=> esc_html__( 'Whitepaper', 'berkeley-coe-theme' ),
		'description'	=> esc_html__( 'This is the navigation area on Whitepaper template pages.', 'berkeley-coe-theme' ),
	) );
	
	
	// CPT-specific sidebar names match CPT names; see berkeley_do_sidebar() below
	$cpts = get_option( 'berkeley_cpts' );
	
	if ( 1 == $cpts[ 'people' ] )
		genesis_register_sidebar( array(
			'id'			=>	'people',
			'name'			=>	esc_html__( 'People', 'berkeley-coe-theme' ),
			'description'	=>	esc_html__( 'This is the primary sidebar on People pages.', 'berkeley-coe-theme' ),
		) );
	if ( 1 == $cpts[ 'facility' ] )
		genesis_register_sidebar( array(
			'id'			=>	'facility',
			'name'			=>	esc_html__( 'Facilities', 'berkeley-coe-theme' ),
			'description'	=>	esc_html__( 'This is the primary sidebar on Facility pages.', 'berkeley-coe-theme' ),
		) );
	if ( 1 == $cpts[ 'course' ] )
		genesis_register_sidebar( array(
			'id'			=>	'course',
			'name'			=>	esc_html__( 'Courses', 'berkeley-coe-theme' ),
			'description'	=>	esc_html__( 'This is the primary sidebar on Course pages.', 'berkeley-coe-theme' ),
		) );
	if ( 1 == $cpts[ 'publication' ] )
		genesis_register_sidebar( array(
			'id'			=>	'publication',
			'name'			=>	esc_html__( 'Publications', 'berkeley-coe-theme' ),
			'description'	=>	esc_html__( 'This is the primary sidebar on Publication pages.', 'berkeley-coe-theme' ),
		) );
	if ( 1 == $cpts[ 'research' ] )
		genesis_register_sidebar( array(
			'id'			=>	'research',
			'name'			=>	esc_html__( 'Research', 'berkeley-coe-theme' ),
			'description'	=>	esc_html__( 'This is the primary sidebar on Research pages.', 'berkeley-coe-theme' ),
		) );
}


// Do widget areas on corresponding post types

add_action( 'get_header', 'berkeley_cpt_switch_sidebar' );

function berkeley_cpt_switch_sidebar() {
	remove_action( 'genesis_sidebar', 'genesis_do_sidebar' ); 
	add_action( 'genesis_sidebar', 'berkeley_do_sidebar' );
}

function berkeley_do_sidebar() {
	if ( function_exists( 'berkeley_find_post_type' ) )
		$type = berkeley_find_post_type();
	else
		$type = get_query_var( 'post_type' );
	
	if ( is_home() && !is_front_page() )
		$type = 'post';
	
	if ( isset( $type ) && !empty( $type ) && is_active_sidebar( $type ) && !in_array( $type, array( 'any', 'page', 'attachment' ) ) )
		dynamic_sidebar( $type );
	else
		genesis_do_sidebar();
}