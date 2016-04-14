<?php

// Make custom image size available in Insert Media

add_filter( 'image_size_names_choose', 'berkeley_image_size_names_choose', 99 );
 
function berkeley_image_size_names_choose( $sizes ) {
    return array_merge( $sizes, array(
        'small' => __( 'Small' ),
    ) );
}

// Blog post image sizes

add_filter( 'genesis_pre_get_option_image_size', 'berkeley_blog_image_sizes' );

function berkeley_blog_image_sizes( $size = 'thumbnail' ) {
	if ( is_sticky() )
		return 'medium';
	
	return $size;
}

add_filter( 'genesis_attr_entry-image', 'berkeley_blog_image_classes', 10, 2 );

function berkeley_blog_image_classes( $attributes, $context ) {
	if ( is_sticky() ) {
		$attributes['class'] = str_replace( 'alignleft', '', $attributes['class'] );
		$attributes['class'] .= ' alignright';
	}
	return $attributes;
}