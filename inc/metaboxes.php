<?php

// Hide some meta boxes by default

add_filter( 'default_hidden_meta_boxes', 'berkeley_coetheme_hidden_meta_boxes', 10, 2 );

function berkeley_coetheme_hidden_meta_boxes( $hidden, $screen ) {
    return array( 
		'genesis-theme-settings-version', 
		'genesis-theme-settings-feeds',
		'i123_widgets_custom_fields' 
	);
}

// Close some meta boxes by default
// The dynamic portions of the hook name, `$page` and `$id`, refer to the screen and screen ID, respectively.

// add_filter( "postbox_classes_{$page}_{$id}", 'berkeley_coetheme_closed_meta_boxes' );

function berkeley_coetheme_closed_meta_boxes( $classes ) {
	if ( isset( $_POST['my_condition'] ) && 'my_condition' == $_POST['my_condition'] )
		array_push( $classes, 'closed' );

	return $classes;
}