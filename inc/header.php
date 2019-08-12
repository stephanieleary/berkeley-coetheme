<?php

// Header background
add_action( 'wp_head', 'berkeley_eng_custom_header_background' );

function berkeley_eng_custom_header_background() {
	$img = wp_get_attachment_url( get_theme_mod( 'berkeley-header-bg' ) );
	if ( !empty( $img ) )
		printf( '<style type="text/css">
			.site-header { 
				background-image: url("%s"); 
				background-position: center top;
				background-repeat-x: none;
				background-repeat-y: none;
				background-size: cover;
			}
		</style>', $img );
}

// Header image
function berkeley_eng_header_body_classes() {
	add_filter( 'body_class', 'berkeley_eng_header_style' );
}

function berkeley_eng_header_style( $classes ) {
	
	if ( 'blank' == get_header_textcolor() )
		$classes[] = 'custom-header-hide-text';
	
	if ( !empty( get_theme_mod( 'berkeley-header-bg' ) ) )
		$classes[] = 'custom-header-background';
	
	if ( is_active_sidebar( 'header-right' ) )
		$classes[] = 'header-right-active';
	
	if ( has_nav_menu( 'secondary' ) ) {
	     $classes[] = 'nav-secondary-active';
	}
	
	return $classes;
}

add_action( 'genesis_site_title', 'berkeley_eng_header_image', 2 );

function berkeley_eng_header_image() {
	$header_image = get_header_image();
	if ( ! empty( $header_image ) ) : 
		printf( '<a href="%s"><img id="custom-header" src="%s" alt="%s" /></a>', esc_url( home_url() ), esc_url( $header_image ), esc_attr( get_option( 'blogname' ) ) );
	endif;
}

// Berkeley Logo

function berkeley_eng_logo_display() {
	$logo = get_option( 'genesis_be_show_logo' );
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
		printf( '<div id="berkeley-engineering-logo"><a href="https://engineering.berkeley.edu"><img src="%s" alt="%s"></a></div>', $path, esc_html__('Berkeley College of Engineering Logo', 'berkeley-coe-theme') );
	}
}
add_action( 'genesis_site_title', 'berkeley_eng_logo_display', 1 );

// Front page post title

add_action( 'get_header', 'berkeley_eng_hide_home_title' );

function berkeley_eng_hide_home_title() {
	if ( !is_front_page() )
		return;
	
	$hide_title	= get_option( 'genesis_be_hide_title' );
	if ( !$hide_title )
		return;
		
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	add_filter( 'body_class', 'berkeley_eng_notitle_body_class' );
}

function berkeley_eng_notitle_body_class( $classes ) {
   $classes[] = 'notitle';
   return $classes;  
}