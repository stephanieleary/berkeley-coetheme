<?php

// Header image

function berkeley_header_body_classes() {
	add_filter( 'body_class', 'berkeley_header_style' );
}

function berkeley_header_style( $classes ) {
     if ( HEADER_TEXTCOLOR == get_header_textcolor() && '' == get_header_image() )
        return $classes;

	if ( 'blank' == get_header_textcolor() )
		$classes[] = 'custom-header-hide-text';
	
	return $classes;
}

add_action( 'genesis_site_title', 'berkeley_header_image', 2 );

function berkeley_header_image() {
	$header_image = get_header_image();
	if ( ! empty( $header_image ) ) : 
		printf( '<a href="%s"><img id="custom-header" src="%s" alt="%s" /></a>', home_url(), esc_url( $header_image ), get_option( 'blogname' ) );
	endif;
}

// Berkeley Logo

function berkeley_logo_display() {
	$logo = genesis_get_option( 'be_logo' );
	if ( $logo ) {
		$path = get_stylesheet_directory_uri() . '/images/';
		if ( is_page_template( 'page_whitepaper.php' ) )
			$path .= 'BE-pacific.png';
		else {
			$colors = genesis_get_option( 'style_selection' );
			switch ( $colors ) {
				case 'earth light':
					$path .= 'BE-southhall.png';
					break;
				case 'woods light':
					$path .= 'BE-stonepine.png';
					break;
				case 'pacific light':
					$path .= 'BE-pacific.png';
					break;
				case 'pool light':
				case 'classic light':
					$path .= 'BE-blue.png';
					break;
				case 'punch light':
					$path .= 'BE-foundersrock.png';
					break;
				case 'classic':
				case 'punch':
					$path .= 'BE-gold-blue.png';
					break;
				case 'earth':
					$path .= 'BE-bayfog-southhall.png';
					break;
				case 'woods':
					$path .= 'BE-bayfog-stonepine.png';
					break;
				default: 
					$path .= 'be_logo_white.png';
					break;
			}
		}
		printf( '<div id="berkeley-engineering-logo"><a href="http://engineering.berkeley.edu"><img src="%s" alt="%s"></a></div>', $path, __('Berkeley College of Engineering Logo', 'beng') );
	}
}
add_action( 'genesis_site_title', 'berkeley_logo_display', 1 );

// Front page post title

add_action( 'get_header', 'berkeley_hide_home_title' );

function berkeley_hide_home_title() {
	if ( !is_front_page() )
		return;
	
	$hide_title	= genesis_get_option( 'hide_home_title' );
	if ( !$hide_title )
		return;
		
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	add_filter( 'body_class', 'berkeley_notitle_body_class' );
}

function berkeley_notitle_body_class( $classes ) {
   $classes[] = 'notitle';
   return $classes;  
}

// Replace primary navigation to remove unnecessary "Main navigation" heading
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'berkeley_do_nav' );
function berkeley_do_nav() {

	//* Do nothing if menu not supported
	if ( ! genesis_nav_menu_supported( 'primary' ) || ! has_nav_menu( 'primary' ) )
		return;

	$class = 'menu genesis-nav-menu menu-primary';
	if ( genesis_superfish_enabled() ) {
		$class .= ' js-superfish';
	}

	genesis_nav_menu( array(
		'theme_location' => 'primary',
		'menu_class'     => $class,
	) );

}

// Filter Skip link text
add_filter( 'genesis_skip_links_output', 'berkeley_skip_links_output' );

function berkeley_skip_links_output( $links ) {
	$links['genesis-content'] = __( 'Skip to main content', 'beng' );
	return $links;
}