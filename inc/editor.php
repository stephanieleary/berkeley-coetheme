<?php
// Callback function to insert 'styleselect' into the $buttons array
function berkeley_mce_buttons( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'berkeley_mce_buttons' );

// Callback function to filter the MCE settings
function berkeley_mce_style_options( $init_array ) {  
    // Define the style_formats array
    $style_formats = array(  
        // Each array child is a format with it's own settings
        array(  
            'title' => 'Button Link',  
            'selector' => 'a',  
            'classes' => 'button'             
        )
    );  
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );  

    return $init_array;  

} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'berkeley_mce_style_options' );


// New TinyMCE button for blockquotes with cite
function berkeley_pullquote_mce_button() {
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'berkeley_pullquote_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'berkeley_pullquote_register_mce_button' );
	}
}
add_action('admin_head', 'berkeley_pullquote_mce_button');

// Declare script for new button
function berkeley_pullquote_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['blockquote_cite'] = get_stylesheet_directory_uri() .'/js/mce-buttons.js';
	return $plugin_array;
}

// Register new button in the editor
function berkeley_pullquote_register_mce_button( $buttons ) {
	$first = array_slice( $buttons, 0, 6 );
	array_push( $first, 'blockquote_cite' );
	$buttons = array_splice( $buttons, 6, count( $buttons ) );
	return array_merge( $first, $buttons );
}
