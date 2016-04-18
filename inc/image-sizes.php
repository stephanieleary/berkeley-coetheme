<?php

// Disable Genesis's default first-uploaded image fallback

add_filter( 'genesis_get_image_default_args', 'berkeley_image_default_args' );

function berkeley_image_default_args( $args ) {
	$args['fallback'] = '';
	return $args;
}

// Make custom image size available in Insert Media

add_filter( 'image_size_names_choose', 'berkeley_image_size_names_choose' );
 
function berkeley_image_size_names_choose( $sizes ) {
    return array_merge( $sizes, array(
        'small' => __( 'Small' ),
    ) );
}

// Blog post image sizes

add_filter( 'genesis_pre_get_option_image_size', 'berkeley_blog_image_sizes' );

function berkeley_blog_image_sizes( $size = 'thumbnail' ) {
	if ( is_sticky() && ( 'post' == get_post_type() || !is_main_query() ) ) {
		$size = 'medium';
	}
	return $size;
}

add_filter( 'genesis_attr_entry-image', 'berkeley_blog_image_classes', 10, 2 );

function berkeley_blog_image_classes( $attributes, $context ) {
	if ( is_sticky() && ( 'post' == get_post_type() || !is_main_query() ) ) {
		$attributes['class'] = str_replace( array( 'alignleft', 'alignnone', 'aligncenter' ), '', $attributes['class'] );
		$attributes['class'] .= ' alignright';
	}
	return $attributes;
}

// Default site icon / favicon

add_filter( 'get_custom_logo', 'berkeley_site_icon' );

function berkeley_site_icon( $html ) {
	if ( empty( $html ) ) {
		$html = sprintf( '<a href="%s" class="custom-logo-link" rel="home" itemprop="url"><img class="custom-logo" itemprop="logo" src="%s" /></a>', esc_url( home_url( '/' ) ), get_stylesheet_directory_uri() . '/images/BE-favicon.png' );
	}
	return $html;
}

add_filter( 'get_site_icon_url', 'berkeley_site_icon_url', 10, 3 );

function berkeley_site_icon_url( $url = '', $size = 512, $blog_id = 0 ) {
	$default_url = get_stylesheet_directory_uri() . "/images/BE-favicon-{$size}x{$size}.png";
	if ( file_exists( $default_url ) )
		$url = $default_url;
	else
		$url = $default_url = get_stylesheet_directory_uri() . "/images/BE-favicon.png";
	return $url;
}


add_filter( 'genesis_pre_load_favicon', 'berkeley_favicon' );

function berkeley_favicon( $favicon_url ) {
	return get_stylesheet_directory_uri() . '/images/BE-favicon-150x150.png';
}