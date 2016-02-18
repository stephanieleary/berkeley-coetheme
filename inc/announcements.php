<?php

// * Announcement feature
genesis_register_sidebar( array(
	'id'			=> 'announcements',
	'name'			=> __( 'Emergency Announcements' ),
	'description'	=> __( 'Area between the logo and the main navigation.' ),
) );

add_action( 'genesis_after_header', 'berkeley_announcements_widget_area', 1 );

function berkeley_announcements_widget_area() {
	if ( is_active_sidebar( 'announcements' ) ) {

		echo '<div class="announcements"><div class="wrap">';
		dynamic_sidebar( 'announcements' );
		echo '</div></div><!-- end .announcements -->';

	}
}

// * Slideshow widget area
genesis_register_sidebar( array(
	'id'			=> 'featured',
	'name'			=> __( 'Featured Content' ),
	'description'	=> __( 'Full-width area below the main navigation.' ),
) );

add_action( 'genesis_before_content_sidebar_wrap', 'berkeley_slideshow_widget_area', 1 );

function berkeley_slideshow_widget_area() {
	if ( is_home() && is_active_sidebar( 'featured' ) ) {

		echo '<div class="featured"><div class="wrap">';
		dynamic_sidebar( 'featured' );
		echo '</div></div><!-- end .featured -->';

	}
}

add_filter( 'body_class', 'berkeley_slideshow_body_class' );

function berkeley_slideshow_body_class( $classes ) {
	if ( is_home() && is_active_sidebar( 'featured' ) ) {
		$classes[] = 'has-slideshow';  // featured-content is reserved in Genesis
	}
	return $classes;
}